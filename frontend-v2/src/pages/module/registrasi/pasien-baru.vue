
<template>
    <div class="page-content-inner">
        <div class="is-navbar">
            <div class="form-layout">
                <div class="form-outer">
                    <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                        <div class="form-header-inner">
                            <div class="left">
                                <h3>Mitra</h3>
                            </div>
                            <div class="right">
                                <div class="buttons">
                                    <VButton icon="lnir lnir-arrow-left rem-100"
                                        :to="{ name: 'module-registrasi-pasien-lama' }" light dark-outlined>
                                        Cancel
                                    </VButton>
                                    <VButton type="button" icon="feather:save" :loading="isLoading" color="primary" raised
                                        @click="savePasien()" v-if="!isRegistrasi"> Save
                                    </VButton>
                                    <VButton type="button" icon="feather:arrow-right-circle" :loading="isLoading"
                                        color="info" raised @click="registrasiPasien()" v-if="isRegistrasi && (kelompokUser != 'laboratorium' && kelompokUser != 'radiologi')"> Registrasi
                                    </VButton>
                                    <VButton type="button" icon="feather:arrow-right-circle" :loading="isLoading"
                                        color="info" raised @click="registrasiLab()" v-if="isRegistrasi && (kelompokUser == 'laboratorium' || kelompokUser == 'radiologi')"> Registrasi
                                    </VButton>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <!--Fieldset-->
                        <div class="form-fieldset">
                            <div class="fieldset-heading">
                                <h4>Informasi Pasien</h4>

                            </div>

                            <div class="columns is-multiline">
                              <div class="column is-12 text-center">
                              <VField>
                                  <VControl>
                                  <VFilePond v-if="files.length"
                                  v-bind:files="files"
                                      class="profile-filepond"
                                      name="profile_filepond"
                                      :chunk-retry-delays="[500, 1000, 3000]"
                                      label-idle="<i class='lnil lnil-cloud-upload'></i>"
                                      :accepted-file-types="['image/png', 'image/jpeg', 'image/gif']"
                                      :image-preview-height="140"
                                      :image-resize-target-width="140"
                                      :image-resize-target-height="140"
                                      image-crop-aspect-ratio="1:1"
                                      style-panel-layout="compact circle"
                                      style-load-indicator-position="center bottom"
                                      style-progress-indicator-position="right bottom"
                                      style-button-remove-item-position="left bottom"
                                      style-button-process-item-position="right bottom"
                                      @addfile="onAddFile"
                                      @removefile="onRemoveFile"
                                    />
                                    <VFilePond
                                     v-else
                                      class="profile-filepond"
                                      name="profile_filepond"
                                      :chunk-retry-delays="[500, 1000, 3000]"
                                      label-idle="<i class='lnil lnil-cloud-upload'></i>"
                                      :accepted-file-types="['image/png', 'image/jpeg', 'image/gif']"
                                      :image-preview-height="140"
                                      :image-resize-target-width="140"
                                      :image-resize-target-height="140"
                                      image-crop-aspect-ratio="1:1"
                                      style-panel-layout="compact circle"
                                      style-load-indicator-position="center bottom"
                                      style-progress-indicator-position="right bottom"
                                      style-button-remove-item-position="left bottom"
                                      style-button-process-item-position="right bottom"
                                      @addfile="onAddFile"
                                      @removefile="onRemoveFile"
                                    />
                                  </VControl>
                                </VField>
                                </div>
                                <!-- <div class="column is-12">
                                    <VField>
                                        <VControl>
                                            <VSwitchBlock v-model="item.isPenunjang" label="Penunjang"
                                                color="danger"  v-if="kelompokUser == 'radiologi'"/>
                                        </VControl>
                                    </VField>
                                </div> -->
                                <div class="column is-6">

                                    <VField id="nik" v-slot="{ field }">
                                        <VLabel class="required-field">NIK</VLabel>
                                        <VControl icon="feather:book" :loading="isLoadingNIK">
                                            <VInput type="text" v-model="item.nik" placeholder="Enter untuk pencarian NIK"
                                                class="is-rounded_Z" v-on:keyup.enter="cariBPJS('nik')" />
                                            <p v-if="field?.errorMessage" class="help is-danger">
                                                {{ field.errorMessage }}
                                            </p>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField id="nobpjs" v-slot="{ field }" label="No BPJS">
                                        <VControl icon="feather:book" :loading="isLoadingBPJS">
                                            <VInput type="text" v-model="item.nobpjs"
                                                placeholder="Enter untuk pencarian No BPJS" class="is-rounded_Z"
                                                v-on:keyup.enter="cariBPJS('nobpjs')" />
                                            <p v-if="field?.errorMessage" class="help is-danger">
                                                {{ field.errorMessage }}
                                            </p>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VLabel class="required-field">Nama Pasien</VLabel>
                                        <VControl icon="feather:user">
                                            <VInput type="text" v-model="item.namapasien" placeholder="Nama Pasien"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VLabel class="required-field">Tempat Lahir</VLabel>
                                        <VControl icon="feather:map-pin">
                                            <VInput type="text" v-model="item.tempatlahir" placeholder="Tempat Lahir"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                  <VField>
                                    <VLabel class="required-field">Tgl Lahir</VLabel>
                                      <VControl class="prime-auto">
                                        <Calendar v-model="item.tgllahir"
                                            selectionMode="single" :manualInput="true"
                                            class="w-100" :showIcon="true" :showTime="false"
                                            hourFormat="24" :date-format="'dd-mm-yy'" placeholder="dd-mm-yyyy" />
                                            </VControl>
                                            </VField>
                                    <!-- <VDatePicker v-model="item.tgllahir" color="green" trim-weeks :input="'YYYY-MM-DD'"
                                        mode="date">
                                        <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                                <VLabel class="required-field">Tgl Lahir</VLabel>
                                                <VControl icon="feather:calendar">
                                                    <VInput type="text" placeholder="Select a date" :value="inputValue"
                                                        v-on="inputEvents" class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </template>
                                    </VDatePicker> -->
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VLabel class="required-field">Jenis Kelamin</VLabel>
                                        <VControl>
                                            <div class="columns is-multiline pt-3 pb-2 pr-5 pl-5">
                                                <div class="column is-12" v-if="d_JK.length == 0">
                                                    <VPlaceloadText :lines="1" />
                                                </div>
                                                <div class="column is-4 p-0" v-for="items in d_JK" :key="items.id">
                                                    <VRadio v-model="item.jenisKelamin" :value="items.id" class="p-0 mb-3"
                                                        :label="items.jeniskelamin" name="{{items.id}}" square
                                                        color="primary" />
                                                </div>
                                            </div>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VLabel>No HP/Ponsel</VLabel>
                                        <VControl icon="feather:phone">
                                            <VInput type="text" v-model="item.nohp" placeholder="No HP"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel class="required-field">Agama</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.agama" :options="d_Agama"  autocomplete="off"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <!--Fieldset-->
                        <div class="form-fieldset">
                            <div class="fieldset-heading">
                                <h4>Informasi Alamat </h4>
                            </div>

                            <div class="columns is-multiline">
                                <div class="column is-9">
                                    <VField>
                                        <VLabel class="required-field">Alamat Lengkap</VLabel>
                                        <VControl>
                                            <VTextarea v-model="item.alamat" rows="1" placeholder="Alamat Lengkap">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-3">
                                    <VField>
                                        <VLabel class="required-field">RT / RW</VLabel>
                                        <VControl>
                                            <VInput type="text" v-model="item.rtrw" placeholder="RT / RW"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-6">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel class="required-field">Provinsi</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.provinsi" :options="d_Provinsi"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }"
                                                @select="changeProvinsi(item.provinsi)" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6" v-if="item.provinsi">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel class="required-field">Kota Kabupaten</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.kotaKabupaten"
                                                :options="d_KotaKabupaten" placeholder="Pilih data" :searchable="true"
                                                :attrs="{ id }" :loading="isLoading"
                                                @select="changeKota(item.kotaKabupaten)" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6" v-if="item.kotaKabupaten">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel class="required-field">Kecamatan</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.kecamatan" :options="d_Kecamatan"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }"
                                                :loading="isLoading" @select="changeKecamatan(item.kecamatan)" />
                                        </VControl>
                                        <!-- <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.kecamatan"
                                                placeholder="Pilih data" :searchable="true" :filter-results="false"
                                                :min-chars="0" :resolve-on-load="false" :loading="isLoading" :delay="0"
                                                :options="async function (query: any) {
                                                    return await fetchKecamatan(query) // check JS block in JSFiddle for implementation
                                                }">

                                            </Multiselect>
                                                    </VControl> -->
                                    </VField>
                                </div>
                                <div class="column is-6" v-if="item.kecamatan">

                                    <!-- <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Kelurahan</VLabel>
                                        <VControl icon="feather:search">

                                            <AutoComplete v-model="item.desaKelurahan" :suggestions="d_Kelurahan"
                                                @complete="fetchDesa($event)" :dropdown="true"
                                                placeholder="Pilih data" field="namadesakelurahan" ppendTo="body">
                                                <template #item="slotProps">
                                                    <div class="flex align-items-center country-item">
                                                        <table class="country-item">
                                                            <tr>
                                                                <th>Kelurahan</th>
                                                                <th>Kecamatan</th>
                                                                <th>Kota Kabupaten</th>
                                                                <th>Provinsi</th>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ slotProps.item.namadesakelurahan }}</td>
                                                                <td>{{ slotProps.item.namakecamatan }}</td>
                                                                <td>{{ slotProps.item.namakotakabupaten }}</td>
                                                                <td>{{ slotProps.item.namapropinsi }}</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </template>
                                            </AutoComplete>
                                        </VControl>
                                                </VField> -->
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Kelurahan</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.desaKelurahan" :options="d_Kelurahan"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }"
                                                :loading="isLoading" @select="changeDesa(item.desaKelurahan)" />
                                            <!-- <Multiselect mode="single" v-model="item.desaKelurahan"
                                                placeholder="Pilih data" :searchable="true" :filter-results="false"
                                                :min-chars="0" :resolve-on-load="false" :loading="isLoading" :delay="0"
                                                :options="async function (query: any) {
                                                    return await fetchDesa(query) // check JS block in JSFiddle for implementation
                                                }" @select="changeDesa(item.desaKelurahan)">

                                                        </Multiselect> -->
                                        </VControl>
                                    </VField>
                                </div>



                                <div class="column is-6" v-if="item.kecamatan">
                                    <VField>
                                        <VLabel>Kode Pos </VLabel>
                                        <VControl icon="feather:airplay" :loading="isLoadingKodePos">
                                            <VInput type="text" v-model="item.kodePos" placeholder="Kode Pos"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-12">
                                    <VField>
                                        <VLabel>Email </VLabel>
                                        <VControl icon="feather:mail">
                                            <VInput type="email" v-model="item.email" placeholder="Email" inputmode="email"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VLabel>Nama Ibu </VLabel>
                                        <VControl icon="pi pi-reddit">
                                            <VInput type="text" v-model="item.namaIbu" placeholder="Nama Ibu"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VControl>
                                            <VSwitchBlock v-model="isInfoTambahan" label="Informasi Tambahan"
                                                color="danger" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <!--Fieldset-->
                        <div class="form-fieldset" v-if="isInfoTambahan">
                            <div class="fieldset-heading">
                                <h4>Informasi Tambahan </h4>
                            </div>

                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Status Perkawinan</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.statusPerkawinan"
                                                :options="d_StatusPerkawinan" placeholder="Pilih data" :searchable="true"
                                                :attrs="{ id }" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Golongan Darah</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.golDar" :options="d_GolonganDarah"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Pendidikan</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.pendidikan" :options="d_Pendidikan"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Pekerjaan</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.pekerjaan" :options="d_Pekerjaan"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-6">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Etnis</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.suku" :options="d_Etnis"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Kebangsaan</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.kebangsaan" :options="d_Kebangsaan"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Negara</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.negara" :options="d_Negara"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VLabel>No Asuransi Lain </VLabel>
                                        <VControl icon="feather:archive">
                                            <VInput type="text" v-model="item.noAsuransiLain" placeholder=""
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VLabel>No Telepon Rumah </VLabel>
                                        <VControl icon="feather:phone">
                                            <VInput type="text" v-model="item.noTelepon" placeholder=""
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VLabel>Nama Ayah </VLabel>
                                        <VControl icon="feather:code">
                                            <VInput type="text" v-model="item.namaAyah" placeholder=""
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VLabel>Nama Keluarga </VLabel>
                                        <VControl icon="feather:code">
                                            <VInput type="text" v-model="item.namaKeluarga" placeholder=""
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VLabel>Nama Suami/Istri</VLabel>
                                        <VControl icon="feather:code">
                                            <VInput type="text" v-model="item.namaSuamiIstri" placeholder=""
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VControl>
                                            <VSwitchBlock v-model="item.isKanker" label="Pasien Kanker"
                                                color="danger" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VControl>
                                            <VSwitchBlock v-model="isPenanggungJawab" label="Isi Penanggung Jawab Pasien"
                                                color="warning" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="form-fieldset" v-if="isPenanggungJawab && isInfoTambahan">
                            <div class="fieldset-heading">
                                <h4>Penanggung Jawab
                                    Pasien</h4>
                                <p>Data penanggung jawab atau keluarga pasien</p>
                            </div>

                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <VField>
                                        <VLabel>Nama Penanggung Jawab</VLabel>
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" v-model="item.penanggungJawabP" placeholder=""
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Hubungan Dengan Pasien</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.hubunganP" :options="d_HubunganPasien"  autocomplete="off"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }"  track-by="value"/>
                                        </VControl>
                                    </VField>
                                    <!-- <VField>
                                        <VLabel>Hubungan Dengan Pasien</VLabel>
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" v-model="item.hubunganP" placeholder=""
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField> -->
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VLabel>No Telepon</VLabel>
                                        <VControl icon="feather:phone">
                                            <VInput type="text" v-model="item.telponP" placeholder=""
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VLabel>Bahasa Sehari-hari</VLabel>
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" v-model="item.bahasaP" placeholder=""
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VLabel>Jenis Kelamin</VLabel>
                                        <VControl>
                                            <div class="columns is-multiline pt-3 pb-2 pr-5 pl-5">
                                                <div class="column is-12" v-if="d_JK.length == 0">
                                                    <VPlaceloadText :lines="1" />
                                                </div>
                                                <div class="column is-4 p-0" v-for="items in d_JK" :key="items.id">
                                                    <VRadio v-model="item.jenisKelP" :value="items.id" class="p-0 mb-3"
                                                        :label="items.jeniskelamin" name="{{items.id}}" square
                                                        color="primary" />
                                                </div>
                                            </div>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VLabel>Umur</VLabel>
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" v-model="item.umurP" placeholder="" class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Pekerjaan</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.pekerjaanP" :options="d_Pekerjaan"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VLabel>Alamat Penanggung Jawab</VLabel>
                                        <VControl>
                                            <VTextarea v-model="item.alamatP" rows="4" placeholder="Alamat Lengkap">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import { useUserSession } from '/@src/stores/userSession'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Calendar from 'primevue/calendar';
useHead({
    title: 'Pasien - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
let ID_PASIEN = useRoute().query.id as string
let ID_PASIEN_SET = ref()
const date = ref(new Date())
const item: any = reactive({})
let d_JK: any = ref([])
let d_Agama: any = ref([])
let d_GolonganDarah: any = ref([])
let d_StatusPerkawinan: any = ref([])
let d_Pendidikan: any = ref([])
let d_Pekerjaan: any = ref([])
let d_Etnis: any = ref([])
let d_HubunganPasien: any = ref([])
let d_Kebangsaan: any = ref([])
let d_Negara: any = ref([])
let d_Kelurahan: any = ref([])
let d_Kecamatan: any = ref([])
let d_KotaKabupaten: any = ref([])
let d_Provinsi: any = ref([])
let isLoading = ref(false)
let isInfoTambahan = ref(false)
let isLoadingKodePos = ref(false)
let isLoadingNIK = ref(false)
let isDisabled = ref(false)
let isLoadingBPJS = ref(false)
let isPenanggungJawab = ref(false)
let isRegistrasi = ref(false)
let isPenunjang = ref(false)
let isKanker = ref(false)
const files=ref([])
const fileFoto:any = ref(null)
const { y } = useWindowScroll()
const router = useRouter()
const route = useRoute()
const isStuck = computed(() => {
    return y.value > 30
})
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser

async function listDropdown() {

    const response = await useApi().get(
        `/registrasi/list-dropdown`)
    d_JK.value = []
    for (let x = 0; x < response.jk.length; x++) {
        const element = response.jk[x];
        if (element.jeniskelamin != '-') {
            d_JK.value.push(element)
        }
    }
    d_Agama.value = response.agama.map((e: any) => { return { label: e.agama, value: e.id , default: e} })
    d_GolonganDarah.value = response.golongandarah.map((e: any) => { return { label: e.golongandarah, value: e.id , default: e} })
    d_HubunganPasien.value = response.hubunganpasien.map((e: any) => { return { label: e.hubungankeluarga, value: e.id} })
    d_StatusPerkawinan.value = response.statusperkawinan.map((e: any) => { return { label: e.statusperkawinan, value: e.id , default: e} })
    d_Pendidikan.value = response.pendidikan.map((e: any) => { return { label: e.pendidikan, value: e.id , default: e} })
    d_Pekerjaan.value = response.pekerjaan.map((e: any) => { return { label: e.pekerjaan, value: e.id , default: e} })
    d_Etnis.value = response.etnis.map((e: any) => { return { label: e.suku, value: e.id , default: e} })
    d_Kebangsaan.value = response.kebangsaan.map((e: any) => { return { label: e.name, value: e.id, default: e } })
    d_Negara.value = response.negara.map((e: any) => { return { label: e.namanegara, value: e.id , default: e} })
    // d_KotaKabupaten.value = response.kotakabupaten.map((e: any) => { return { label: e.namakotakabupaten, value: e } })
    d_Provinsi.value = response.provinsi.map((e: any) => { return { label: e.namapropinsi, value: e.id , default: e} })

    for (let x = 0; x < response.negara.length; x++) {
        const element = response.negara[x];
        if (element.namanegara.toLowerCase() == 'indonesia') {
            item.negara = element.id
            break
        }
    }
    for (let x = 0; x < response.kebangsaan.length; x++) {
        const element = response.kebangsaan[x];
        if (element.name.toLowerCase() == 'wni') {
            item.kebangsaan = element.id
            break
        }
    }
    if (ID_PASIEN) {
        ID_PASIEN_SET.value = ID_PASIEN
        pasienByID(ID_PASIEN)
    }
    if(route.query.noreservasi){
        pasienReservasi(route.query)
    }


}
async function pasienReservasi(r: any) {
  item.namapasien = r.namapasien
  item.tgllahir = new Date(r.tgllahir)
  item.nohp = r.notelepon
  item.namapasien = r.namapasien
  item.jenisKelamin = r.objectjeniskelaminfk
}
async function pasienByID(id: any) {
    const response = await useApi().get(
        `/registrasi/pasien?id=${id}`)
    if (response.pasien) {
        let r = response.pasien
        item.nik = r.noidentitas
        item.nobpjs = r.nobpjs
        item.namapasien = r.namapasien
        item.tempatlahir = r.tempatlahir
        item.tgllahir = new Date(r.tgllahir)
        item.jenisKelamin = r.objectjeniskelaminfk
        item.nohp = r.nohp
        item.agama = r.objectagamafk != null ? r.objectagamafk : undefined
        item.email = r.email
        item.namaIbu = r.namaibu
        item.statusPerkawinan = r.objectstatusperkawinanfk != null ? r.objectstatusperkawinanfk: undefined
        item.golDar = r.objectgolongandarahfk != null ?r.objectgolongandarahfk : undefined
        item.pendidikan = r.objectpendidikanfk != null ?r.objectpendidikanfk: undefined
        item.pekerjaan = r.objectpekerjaanfk != null ?r.objectpekerjaanfk : undefined
        item.suku = r.objectsukufk != null ?r.objectsukufk : undefined
        item.noAsuransiLain = r.noaditional
        item.noTelepon = r.notelepon
        item.namaAyah = r.namaayah
        item.namaKeluarga = r.namakeluarga
        item.namaSuamiIstri = r.namasuamiistri
        item.penanggungJawabP = r.penanggungjawab
        item.hubunganP = r.hubungankeluargapj
        item.telponP = r.telponpenanggungjawab
        item.bahasaP = r.bahasa
        item.jenisKelP = r.jeniskelaminpenanggungjawab
        item.umurP = r.umurpenanggungjawab
        item.pekerjaanP = r.pekerjaanpenangggungjawab
        item.bahasaP = r.bahasa
        item.alamatP = r.alamatrmh
        item.rtrw = r.rtrw
        item.kebangsaan = r.objectkebangsaanfk != null?r.objectkebangsaanfk: undefined
        item.negara = r.objectnegarafk!= null ?r.objectnegarafk: undefined
        item.alamat = r.alamatlengkap
        if (r.objectpropinsifk) {
            await changeProvinsi( r.objectpropinsifk )
            item.provinsi = r.objectpropinsifk
        }
        if (r.objectkotakabupatenfk) {
            await changeKota(r.objectkotakabupatenfk)
            item.kotaKabupaten =r.objectkotakabupatenfk
        }
        if (r.objectkecamatanfk) {
            await changeKecamatan( r.objectkecamatanfk )
            item.kecamatan = r.objectkecamatanfk
        }
        if (r.objectdesakelurahanfk) {
            item.desaKelurahan = r.objectdesakelurahanfk
        }
        item.kodePos = r.kodepos
        if(r.isfoto){
          let path = 'foto_pasien/' + r.nocmfk + '/' + r.filename
          let file :any= await H.getFileBE(path);
          fileFoto.value = file
          let img :any= await blobToBase64(file)
          console.log(img)
          files.value = [img]
        }

    }

}
const blobToBase64 = (blob:any)  =>{
      return new Promise((resolve, _) => {
        const reader = new FileReader();
        reader.onloadend = () => resolve(reader.result);
        reader.readAsDataURL(blob);
      });
    }
async function savePasien() {
    if (!item.nik) { H.alert('warning', 'NIK harus di isi'); return }
    if (!item.namapasien) { H.alert('warning', 'Nama harus di isi'); return }
    if (!item.tempatlahir) { H.alert('warning', 'Tempat Lahir harus di isi'); return }
    if (!item.nohp) { H.alert('warning', 'Nomor HP harus diisi'); return }
    if (item.nohp.length < 10) { H.alert('warning', 'No Handphone tidak sesuai'); return }
    if (!item.tgllahir) { H.alert('warning', 'Tgl Lahir harus di isi'); return }
    if (!item.jenisKelamin) { H.alert('warning', 'Jenis Kelamin harus di isi'); return }
    if (!item.agama) { H.alert('warning', 'Agama harus di isi'); return }
    // if (!item.nohp) { H.alert('warning', 'No HP harus di isi'); return }
    if (!item.alamat) { H.alert('warning', 'Alamat Lenglap harus di isi'); return }
    if (!item.rtrw) { H.alert('warning', 'RT / RW harus di isi'); return }

    item.progress = cekProggress()
    let json = {
        'pasien': {
            'id': ID_PASIEN ? ID_PASIEN : '',
            'isPenunjang': item.isPenunjang ? item.isPenunjang : false,
            'isbayi': item.isbayi ? item.isbayi : false,
            'nocmfkibu': item.nocmfkibu ? item.nocmfkibu : null,
            'noidentitas': item.nik,
            'nobpjs': item.nobpjs ? item.nobpjs : null,
            'namapasien': item.namapasien,
            'tempatlahir': item.tempatlahir,
            'tgllahir': H.formatDate(item.tgllahir, 'YYYY-MM-DD'),
            'objectjeniskelaminfk': item.jenisKelamin,
            'nohp': item.nohp,
            'objectagamafk': item.agama != undefined? item.agama : null,
            'email': item.email != undefined? item.email : null,
            'namaibu': item.namaIbu != undefined? item.namaIbu : null,
            'objectstatusperkawinanfk': item.statusPerkawinan != undefined? item.statusPerkawinan : null,
            'objectgolongandarahfk': item.golDar != undefined? item.golDar : null,
            'objectpendidikanfk': item.pendidikan != undefined? item.pendidikan : null,
            'objectpekerjaanfk': item.pekerjaan != undefined? item.pekerjaan: null,
            'objectsukufk': item.suku != undefined? item.suku : null,
            'noaditional': item.noAsuransiLain != undefined? item.noAsuransiLain : null,
            'notelepon': item.noTelepon != undefined? item.noTelepon : null,
            'namaayah': item.namaAyah != undefined? item.namaAyah : null,
            'namakeluarga': item.namaKeluarga != undefined? item.namaKeluarga : null,
            'namasuamiistri': item.namaSuamiIstri != undefined? item.namaSuamiIstri : null,
            'penanggungjawab': item.penanggungJawabP != undefined? item.penanggungJawabP : null,
            'hubungankeluargapj': item.hubunganP != undefined? item.hubunganP : null,
            'telponpenanggungjawab': item.telponP != undefined? item.telponP : null,
            'bahasa': item.bahasaP != undefined? item.bahasaP : null,
            'jeniskelaminpenanggungjawab': item.jenisKelP != undefined? item.jenisKelP : null,
            'umurpenanggungjawab': item.umurP != undefined? item.umurP : null,
            'pekerjaanpenangggungjawab': item.pekerjaanP != undefined? item.pekerjaanP : null,
            'alamatrmh': item.alamatP != undefined? item.alamatP : null,
            'objectkebangsaanfk': item.kebangsaan != undefined? item.kebangsaan : null,
            'objectnegarafk': item.negara != undefined ? item.negara : null,
            'progress': item.progress ? item.progress : 0,
            'isKanker': item.isKanker ? item.isKanker : false,
        },
        'alamat': {
            'alamatlengkap': item.alamat,
            'rtrw': item.rtrw,
            'objectpropinsifk': item.provinsi != undefined? item.provinsi : null,
            'objectkotakabupatenfk': item.kotaKabupaten != undefined? item.kotaKabupaten : null,
            'objectkecamatanfk': item.kecamatan != undefined? item.kecamatan : null,
            'objectdesakelurahanfk': item.desaKelurahan != undefined? item.desaKelurahan : null,
            'kodepos': item.kodePos ? item.kodePos : null,
        }
    }
    isLoading.value = true
    isRegistrasi.value = false
    await useApi().post(
        `/registrasi/save-pasien`, json).then(async(response: any) => {

            if(fileFoto.value != null){
              const formData = new FormData()
              formData.append('id', response.data.id)
              formData.append('file', fileFoto.value)
              useApi().postNoMessage('/registrasi/save-pasien-foto', formData)
            }
            isLoading.value = false
            ID_PASIEN = response.data.id
            ID_PASIEN_SET.value = response.data.id
            isRegistrasi.value = true
            if (!ID_PASIEN) {
                registrasiPasien()
            }

        }).catch((e: any) => {
            isLoading.value = false
            console.clear()
            console.log(e)
        })
}
async function cariBPJS(params: any) {
    if (params == 'nik') {
        isLoadingNIK.value = true

        let json = {
            "url": `Peserta/nik/${item.nik}/tglSEP/${H.formatDate(new Date(), 'YYYY-MM-DD')}`,
            "method": "GET",
            "data": null
        }
        await useApi().post(
            `/bridging/bpjs/tools`, json).then((e: any) => {
                isLoadingNIK.value = false
                if(e.peserta){
                    item.namapasien = e.peserta.nama
                    item.nobpjs = e.peserta.noKartu
                    item.tgllahir = new Date(e.peserta.tglLahir)
                    if (e.peserta.sex.toUpperCase() === "L") {
                        item.jenisKelamin = 1//{ id: 1, jeniskelamin: "LAKI-LAKI" }
                    }
                    if (e.peserta.sex.toUpperCase() === "P") {
                        item.jenisKelamin = 2//{ id: 2, jeniskelamin: "PEREMPUAN" }
                    }
                }else{
                    H.alert('info', e.metaData.message)
                    console.log(e.metaData.message)
                }
                // if (e.metaData.code == 200) {
                //     let data = e.response
                //     item.namapasien = data.peserta.nama
                //     item.nobpjs = data.peserta.noKartu
                //     item.tgllahir = new Date(data.peserta.tglLahir)
                //     if (data.peserta.sex.toUpperCase() === "L") {
                //         item.jenisKelamin = 1//{ id: 1, jeniskelamin: "LAKI-LAKI" }
                //     }
                //     if (data.peserta.sex.toUpperCase() === "P") {
                //         item.jenisKelamin = 2//{ id: 2, jeniskelamin: "PEREMPUAN" }
                //     }
                // } else {
                //     H.alert('info', e.metaData.message)
                //     console.log(e.metaData.message)
                // }
            })

    }
    if (params == 'nobpjs') {
        isLoadingBPJS.value = true

        let json = {
            "url": `Peserta/nokartu/${item.nobpjs}/tglSEP/${H.formatDate(new Date(), 'YYYY-MM-DD')}`,
            "method": "GET",
            "data": null
        }
        await useApi().post(
            `/bridging/bpjs/tools`, json).then((e: any) => {
                isLoadingBPJS.value = false
                if(e.peserta){
                    item.namapasien = e.peserta.nama
                    item.nik = e.peserta.nik
                    item.tgllahir = new Date(e.peserta.tglLahir)
                    if (e.peserta.sex.toUpperCase() === "L") {
                        item.jenisKelamin = 1//{ id: 1, jeniskelamin: "LAKI-LAKI" }
                    }
                    if (e.peserta.sex.toUpperCase() === "P") {
                        item.jenisKelamin = 2//{ id: 2, jeniskelamin: "PEREMPUAN" }
                    }
                }else{
                    H.alert('info', e.metaData.message)
                    console.log(e.metaData.message)
                }
                // if (e.metaData.code == '200') {
                //     var data = e.response
                //     item.namapasien = data.peserta.nama
                //     item.nobpjs = data.peserta.noKartu
                //     item.tgllahir = new Date(data.peserta.tglLahir)
                //     if (data.peserta.sex.toUpperCase() === "L") {
                //         item.jenisKelamin = 1//{ id: 1, jeniskelamin: "LAKI-LAKI" }
                //     }
                //     if (data.peserta.sex.toUpperCase() === "P") {
                //         item.jenisKelamin = 2//{ id: 2, jeniskelamin: "PEREMPUAN" }
                //     }
                // } else {
                //     H.alert('info', e.metaData.message)
                // }
            })

    }
}


// async function fetchDesa(event: any) {
//     let query = event == '' ? '' : event.query;
//     isLoading.value = true

//     const response = await useApi().get(
//         `/registrasi/desa-kelurahan-paging?namadesakelurahan=${query}`)
//     isLoading.value = false
//     d_Kelurahan.value = response
//     return response.map((item: any) => {
//         return { value: item, label: item.namadesakelurahan }
//     })
// }
// async function fetchKecamatan(event: any) {
//     let query = event == '' ? '' : event;
//     isLoading.value = true

//     const response = await useApi().get(
//         `/registrasi/kecamatan-paging?namakecamatan=${query}`)
//     isLoading.value = false
//     return response.map((item: any) => {
//         return { value: item, label: item.namakecamatan }
//     })
// }
async function changeProvinsi(event: any) {
    d_KotaKabupaten.value = []
    let query = event == '' ? '' : event;
    isLoading.value = true

    const response = await useApi().get(
        `/registrasi/kotakabupaten?provfk=${query}`)
    isLoading.value = false

    d_KotaKabupaten.value = response.kotakabupaten.map((e: any) => { return { label: e.namakotakabupaten, value: e.id,default:e } })

}
async function changeKota(event: any) {
    d_Kecamatan.value = []
    let query = event == '' ? '' : event;
    isLoading.value = true

    const response = await useApi().get(
        `/registrasi/kecamatan?kotafk=${query}`)
    isLoading.value = false

    d_Kecamatan.value = response.kecamatan.map((e: any) => { return { label: e.namakecamatan, value: e.id,default:e } })

}
async function changeKecamatan(event: any) {
    d_Kelurahan.value = []
    let query = event == '' ? '' : event;
    isLoading.value = true

    const response = await useApi().get(
        `/registrasi/desakelurahan?kecfk=${query}`)
    isLoading.value = false

    d_Kelurahan.value = response.desa.map((e: any) => { return { label: e.namadesakelurahan, value: e.id,default:e } })

}
function changeDesa(event: any) {
    // if (event.objectkecamatanfk)
    //     item.kecamatan = { id: event.objectkecamatanfk, namakecamatan: event.namakecamatan }
    // if (event.objectkotakabupatenfk)
    //     item.kotaKabupaten = { id: event.objectkotakabupatenfk, namakotakabupaten: event.namakotakabupaten }
    // if (event.objectpropinsifk)
    //     item.propinsi = { id: event.objectpropinsifk, namapropinsi: event.namapropinsi }
    // if (event.kodepos)
    item.kodePos = event.kodepos
}
function cekProggress() {
    var countALL = 0
    var data = 0
    countALL = countALL + 1
    if (item.nik) { data = data + 1 }
    countALL = countALL + 1
    if (item.nobpjs) { data = data + 1 }
    countALL = countALL + 1
    if (item.namapasien) { data = data + 1 }
    countALL = countALL + 1
    if (item.tempatlahir) { data = data + 1 }
    countALL = countALL + 1
    if (item.tgllahir) { data = data + 1 }
    countALL = countALL + 1
    if (item.jenisKelamin) { data = data + 1 }
    countALL = countALL + 1
    if (item.nohp) { data = data + 1 }
    countALL = countALL + 1
    if (item.agama) { data = data + 1 }
    countALL = countALL + 1
    if (item.email) { data = data + 1 }
    countALL = countALL + 1
    if (item.namaIbu) { data = data + 1 }
    countALL = countALL + 1
    if (item.statusPerkawinan) { data = data + 1 }
    countALL = countALL + 1
    if (item.golDar) { data = data + 1 }
    countALL = countALL + 1
    if (item.pendidikan) { data = data + 1 }
    countALL = countALL + 1
    if (item.pekerjaan) { data = data + 1 }
    countALL = countALL + 1
    if (item.suku) { data = data + 1 }
    countALL = countALL + 1
    if (item.noAsuransiLain) { data = data + 1 }
    countALL = countALL + 1
    if (item.noTelepon) { data = data + 1 }
    countALL = countALL + 1
    if (item.namaAyah) { data = data + 1 }
    countALL = countALL + 1
    if (item.namaKeluarga) { data = data + 1 }
    countALL = countALL + 1
    if (item.namaSuamiIstri) { data = data + 1 }
    countALL = countALL + 1
    if (item.kebangsaan) { data = data + 1 }
    countALL = countALL + 1
    if (item.negara) { data = data + 1 }
    countALL = countALL + 1
    if (item.alamat) { data = data + 1 }
    countALL = countALL + 1
    if (item.provinsi) { data = data + 1 }
    countALL = countALL + 1
    if (item.kotaKabupaten) { data = data + 1 }
    countALL = countALL + 1
    if (item.kecamatan) { data = data + 1 }
    countALL = countALL + 1
    if (item.desaKelurahan) { data = data + 1 }
    countALL = countALL + 1
    if (item.kodePos) { data = data + 1 }


    return data / countALL * 100
}
function resetForm() {

}
function registrasiPasien() {
  if(route.query.noreservasi){
    router.push({
      name: 'module-registrasi-registrasi-ruangan',
      query: {
        nocmfk: ID_PASIEN_SET.value,
        noreservasi: route.query.noreservasi,
        norec_online: route.query.norec_online,
        tanggalreservasi: route.query.tanggalreservasi,
        ruangan: route.query.ruangan,
        dokter: route.query.dokter,
        dokter_name: route.query.dokter_name,
        kelompok: route.query.kelompok,
        statuspasien: "BARU",
      },
    })
  }else{
    router.push({
        name: 'module-registrasi-registrasi-ruangan',
        query: {
            nocmfk: ID_PASIEN_SET.value,
            statuspasien: "BARU",
        },
    })
  }

}


function registrasiLab() {
  router.push({
    name: 'module-registrasi-registrasi-ruangan-lab',
    query: {
      nocmfk: ID_PASIEN_SET.value,
    },
  })
}

const onAddFile = (error: any, fileInfo: any) => {
  if (error) {
    console.error(error)
    return
  }

  const _file = fileInfo.file as File
  if (_file) {
    fileFoto.value = _file
  }
}

const onRemoveFile = (error: any, fileInfo: any) => {
  if (error) {
    console.error(error)
    return
  }

  console.log(fileInfo)

  fileFoto.value = null
}
listDropdown()

// watch(
//   () => item.nik,
//   () => {
//     if(item.nik.toString().length > 16){
//        H.alert('')
//     }
// })

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.form-layout {
    max-width: 840px;
    margin: 0 auto;
}

.form-fieldset {
    padding: 20px 0;
    max-width: 580px;
    margin: 0 auto;
}
</style>
