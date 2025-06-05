<template>
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ props.FORM_NAME }}</h3>
                    </div>
                    <div class="right">
                        <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                            @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <div class="columns is-multiline p-2">
        <div class="column is-12">
            <VCard>
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-5">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> DPJP</h1>
                            <VField class="is-autocomplete-select" v-slot="{ id }">
                                <VControl icon="feather:search">
                                    <AutoComplete v-model="input.dokterRawat" :suggestions="d_Pegawai"
                                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="Cari Pegawai" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-5">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Dokter Anastesi</h1>
                            <VField class="is-autocomplete-select" v-slot="{ id }">
                                <VControl icon="feather:search">
                                    <AutoComplete v-model="input.dokterAnastesi" :suggestions="d_Pegawai"
                                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="Cari Pegawai" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-5">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Dokter Bedah</h1>
                            <VField class="is-autocomplete-select" v-slot="{ id }">
                                <VControl icon="feather:search">
                                    <AutoComplete v-model="input.dokterBedah" :suggestions="d_Pegawai"
                                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="Cari Pegawai" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-5">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Ruangan </h1>
                            <VField>
                                <VControl>
                                    <AutoComplete v-model="input.namaruangan" :suggestions="d_Ruangan"
                                        @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="Cari Ruangan" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                    <div class="column is-12">
                        <TabView>
                            <TabPanel header="A. PRE OPERASI">
                                <div class="column is-12">
                                    <Fieldset :toggleable="true" legend="PENGKAJIAN">
                                        <div class="column is-12" v-for="(datas) in Pengkajian">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                                            <div class="columns is-multiline">
                                                <div class="column is-3" v-for="(data) in datas.value">
                                                    <VField v-if="data.type == 'checkBox'">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data.model]"
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                class="p-0" color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                    <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                                                        style="margin-bottom: 0.5rem;">
                                                        <VControl raw subcontrol>
                                                            <input v-model="input[data.model]" class="input p-0" />
                                                        </VControl>
                                                    </VField>

                                                </div>
                                            </div>

                                        </div>
                                        <div class="column is-12" v-for="(data) in tandaVital">
                                            <h1 style="font-weight : bold; margin-bottom: 0.5rem;">{{ data.title }}</h1>
                                            <div class="columns is-multiline">
                                                <div class="column is-3" v-for="(detail) in data.value">
                                                    <h1 style="font-weight : bold; margin-bottom: 0.5rem;">{{
                                                        detail.subTitle }}</h1>
                                                    <VField addons v-if="detail.type == 'textfiled'">
                                                        <VControl expanded>
                                                            <VInput type="text" class="input"
                                                                v-model="input[detail.model]" />
                                                        </VControl>
                                                        <VControl class="field-addon-body">
                                                            <VButton static>{{ detail.satuan }}</VButton>
                                                        </VControl>
                                                    </VField>
                                                    <VField v-else-if="detail.type == 'textbox'">
                                                        <VControl>
                                                            <input v-model="input[detail.model]" class="input"
                                                                :placeholder="detail.subTitle + ' ...'" />
                                                        </VControl>
                                                    </VField>

                                                    <VField v-else>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[detail.model]"
                                                                :true-value="detail.satuan" :label="detail.satuan"
                                                                color="primary" circle />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column is-12" v-for="(datas) in kesadaran">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                                            <div class="columns is-multiline">
                                                <div class="column is-3" v-for="(data) in datas.value">
                                                    <VField v-if="data.type == 'checkBox'">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data.model]"
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                class="p-0" color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                    <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                                                        style="margin-bottom: 0.5rem;">
                                                        <VControl raw subcontrol>
                                                            <input v-model="input[data.model]" class="input p-0" />
                                                        </VControl>
                                                    </VField>

                                                </div>
                                            </div>

                                        </div>
                                        <h1 style="font-weight: bold;"> Muskuloskeletal </h1>
                                        <div class="column is-12" v-for="(datas) in Molekul">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                                            <div class="columns is-multiline">
                                                <div class="column is-3" v-for="(data) in datas.value">
                                                    <VField v-if="data.type == 'checkBox'">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data.model]"
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                class="p-0" color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                    <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                                                        style="margin-bottom: 0.5rem;">
                                                        <VControl raw subcontrol>
                                                            <input v-model="input[data.model]" class="input p-0" />
                                                        </VControl>
                                                    </VField>

                                                </div>
                                            </div>

                                        </div>
                                        <h1 style="font-weight: bold;"> Integumen </h1>
                                        <div class="column is-12" v-for="(datas) in Integumen">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                                            <div class="columns is-multiline">
                                                <div class="column is-2" v-for="(data) in datas.value">
                                                    <VField v-if="data.type == 'checkBox'">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data.model]"
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                class="p-0" color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                    <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                                                        style="margin-bottom: 0.5rem;">
                                                        <VControl raw subcontrol>
                                                            <input v-model="input[data.model]" class="input p-0" />
                                                        </VControl>
                                                    </VField>

                                                </div>
                                            </div>

                                        </div>
                                        <div class="column is-8">
                                            <h1 style="font-weight: bold;"> Pengkajian Lainnya </h1>
                                            <VField>
                                                <VControl>
                                                    <VTextarea class="textarea" v-model="input.pengkajianLainnya" rows="2"
                                                        placeholder="Pengkajian Lainnya" autocomplete="off"
                                                        autocapitalize="off" spellcheck="true" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </Fieldset>

                                </div>
                                <div class="column is-12">
                                    <Fieldset :toggleable="true" legend="DIAGNOSA">
                                        <div class="column is-12" v-for="(datas) in diagnosa">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                                            <div class="columns is-multiline">
                                                <div class="column is-4" v-for="(data) in datas.value">
                                                    <VField v-if="data.type == 'checkBox'">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data.model]"
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                class="p-0" color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                    <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                                                        style="margin-bottom: 0.5rem;">
                                                        <VControl raw subcontrol>
                                                            <input v-model="input[data.model]" class="input p-0" />
                                                        </VControl>
                                                    </VField>

                                                </div>
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold;"> Diagnosa Lainnya </h1>
                                                    <VField>
                                                        <VControl>
                                                            <VTextarea class="textarea" v-model="input.diagnosaLainnya"
                                                                rows="2" placeholder="Diagnosa Lainnya" autocomplete="off"
                                                                autocapitalize="off" spellcheck="true" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>

                                        </div>
                                    </Fieldset>
                                </div>
                                <div class="column is-12">
                                    <Fieldset :toggleable="true" legend="INTERVENSI">
                                        <div class="column is-12" v-for="(datas) in intervensi">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                                            <div class="columns is-multiline">
                                                <div class="column is-12" v-for="(data) in datas.value">
                                                    <VField v-if="data.type == 'checkBox'">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data.model]"
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                class="p-0" color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                    <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                                                        style="margin-bottom: 0.5rem;">
                                                        <VControl raw subcontrol>
                                                            <input v-model="input[data.model]" class="input p-0" />
                                                        </VControl>
                                                    </VField>

                                                </div>
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold;"> Intervensi Lainnya </h1>
                                                    <VField>
                                                        <VControl>
                                                            <VTextarea class="textarea" v-model="input.intervensiLainnya"
                                                                rows="2" placeholder="Intervensi Lainnya" autocomplete="off"
                                                                autocapitalize="off" spellcheck="true" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>

                                        </div>
                                    </Fieldset>
                                </div>
                                <div class="column is-12">
                                    <Fieldset :toggleable="true" legend="IMPLEMENTASI / TINDAKAN">
                                        <div class="column is-12" v-for="(datas) in implementasi">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                                            <div class="columns is-multiline">
                                                <div class="column is-12" v-for="(data) in datas.value">
                                                    <VField v-if="data.type == 'checkBox'">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data.model]"
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                class="p-0" color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                    <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                                                        style="margin-bottom: 0.5rem;">
                                                        <VControl raw subcontrol>
                                                            <input v-model="input[data.model]" class="input p-0" />
                                                        </VControl>
                                                    </VField>

                                                </div>
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold;"> Implementasi Lainnya </h1>
                                                    <VField>
                                                        <VControl>
                                                            <VTextarea class="textarea" v-model="input.implementasiLainnya"
                                                                rows="2" placeholder="Implementasi Lainnya"
                                                                autocomplete="off" autocapitalize="off" spellcheck="true" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>

                                        </div>
                                    </Fieldset>
                                </div>
                                <div class="column is-4 mt-3" style="margin-left: auto;">
                                    <VCard class="border-card pink">
                                        <div class="column is-9">
                                            <VField>
                                                <h1 style="font-weight: bold;">Tanggal / Jam</h1>
                                            </VField>
                                            <VField>
                                                <VDatePicker v-model="input.tanggal" mode="dateTime" style="width: 100%"
                                                    trim-weeks :max-date="new Date()">
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VField>
                                                            <VControl icon="feather:calendar" fullwidth>
                                                                <VInput :value="inputValue" placeholder="Tanggal"
                                                                    v-on="inputEvents" />
                                                            </VControl>
                                                        </VField>
                                                    </template>
                                                </VDatePicker>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'">
                                            </TandaTangan>
                                        </div>
                                        <div class="column pt-0">
                                            <VField>
                                                <h1 style="font-weight: bold;">Perawat OK</h1>
                                            </VField>
                                            <VField class="is-autocomplete-select" v-slot="{ id }">
                                                <VControl icon="feather:search">
                                                    <AutoComplete v-model="input.perawatOK" :suggestions="d_Pegawai"
                                                        @complete="fetchPegawai($event)" :optionLabel="'label'"
                                                        :dropdown="true" :minLength="3" :appendTo="'body'"
                                                        :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                        placeholder="ketik nama petugas"
                                                        @item-select="setTandaTangan($event)" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </VCard>
                                </div>
                            </TabPanel>
                            <TabPanel header="B. INTRA OPERASI">
                                <div class="column is-12">
                                    <Fieldset :toggleable="true" legend="PENGKAJIAN">
                                        <div class="column is-12" v-for="(datas) in pengkajian2">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                                            <div class="columns is-multiline">
                                                <div class="column is-3" v-for="(data) in datas.value">
                                                    <VField v-if="data.type == 'checkBox'">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data.model]"
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                class="p-0" color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                    <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                                                        style="margin-bottom: 0.5rem;">
                                                        <VControl raw subcontrol>
                                                            <input v-model="input[data.model]" class="input p-0" />
                                                        </VControl>
                                                    </VField>

                                                </div>
                                            </div>

                                        </div>
                                        <div class="column is-12">
                                            <h1 style="font-weight: bold;"> Pengkajian Lainnya </h1>
                                            <VField>
                                                <VControl>
                                                    <VTextarea class="textarea" v-model="input.pengkajianLainnya" rows="2"
                                                        placeholder="Pengkajian Lainnya" autocomplete="off"
                                                        autocapitalize="off" spellcheck="true" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </Fieldset>
                                </div>
                                <div class="column is-12">
                                    <Fieldset :toggleable="true" legend="DIAGNOSA">
                                        <div class="column is-12" v-for="(datas) in diagnosa2">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                                            <div class="columns is-multiline">
                                                <div class="column is-4" v-for="(data) in datas.value">
                                                    <VField v-if="data.type == 'checkBox'">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data.model]"
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                class="p-0" color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                    <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                                                        style="margin-bottom: 0.5rem;">
                                                        <VControl raw subcontrol>
                                                            <input v-model="input[data.model]" class="input p-0" />
                                                        </VControl>
                                                    </VField>

                                                </div>
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold;"> Diagnosa Lainnya </h1>
                                                    <VField>
                                                        <VControl>
                                                            <VTextarea class="textarea" v-model="input.diagnosaLainnya2"
                                                                rows="2" placeholder="Diagnosa Lainnya" autocomplete="off"
                                                                autocapitalize="off" spellcheck="true" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>

                                        </div>
                                    </Fieldset>
                                </div>
                                <div class="column is-12">
                                    <Fieldset :toggleable="true" legend="INTERVENSI">
                                        <div class="column is-12" v-for="(datas) in intervensi2">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                                            <div class="columns is-multiline">
                                                <div class="column is-12" v-for="(data) in datas.value">
                                                    <VField v-if="data.type == 'checkBox'">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data.model]"
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                class="p-0" color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                    <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                                                        style="margin-bottom: 0.5rem;">
                                                        <VControl raw subcontrol>
                                                            <input v-model="input[data.model]" class="input p-0" />
                                                        </VControl>
                                                    </VField>

                                                </div>
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold;"> Intervensi Lainnya </h1>
                                                    <VField>
                                                        <VControl>
                                                            <VTextarea class="textarea" v-model="input.intervensiLainnya2"
                                                                rows="2" placeholder="Intervensi Lainnya" autocomplete="off"
                                                                autocapitalize="off" spellcheck="true" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>

                                        </div>
                                    </Fieldset>
                                </div>
                                <div class="column is-12">
                                    <Fieldset :toggleable="true" legend="IMPLEMENTASI / TINDAKAN">
                                        <div class="column is-12" v-for="(datas) in implementasi2">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                                            <div class="columns is-multiline">
                                                <div class="column is-12" v-for="(data) in datas.value">
                                                    <VField v-if="data.type == 'checkBox'">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data.model]"
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                class="p-0" color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                    <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                                                        style="margin-bottom: 0.5rem;">
                                                        <VControl raw subcontrol>
                                                            <input v-model="input[data.model]" class="input p-0" />
                                                        </VControl>
                                                    </VField>

                                                </div>
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold;"> Implementasi Lainnya </h1>
                                                    <VField>
                                                        <VControl>
                                                            <VTextarea class="textarea" v-model="input.implementasiLainnya2"
                                                                rows="2" placeholder="Implementasi Lainnya"
                                                                autocomplete="off" autocapitalize="off" spellcheck="true" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>

                                        </div>
                                    </Fieldset>
                                </div>
                                <div class="column is-4 mt-3" style="margin-left: auto;">
                                    <VCard class="border-card pink">
                                        <div class="column is-9">
                                            <VField>
                                                <h1 style="font-weight: bold;">Tanggal / Jam</h1>
                                            </VField>
                                            <VField>
                                                <VDatePicker v-model="input.tanggal" mode="dateTime" style="width: 100%"
                                                    trim-weeks :max-date="new Date()">
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VField>
                                                            <VControl icon="feather:calendar" fullwidth>
                                                                <VInput :value="inputValue" placeholder="Tanggal"
                                                                    v-on="inputEvents" />
                                                            </VControl>
                                                        </VField>
                                                    </template>
                                                </VDatePicker>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'">
                                            </TandaTangan>
                                        </div>
                                        <div class="column pt-0">
                                            <VField>
                                                <h1 style="font-weight: bold;">Perawat Sirkuler</h1>
                                            </VField>
                                            <VField class="is-autocomplete-select" v-slot="{ id }">
                                                <VControl icon="feather:search">
                                                    <AutoComplete v-model="input.perawatSirkuler" :suggestions="d_Pegawai"
                                                        @complete="fetchPegawai($event)" :optionLabel="'label'"
                                                        :dropdown="true" :minLength="3" :appendTo="'body'"
                                                        :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                        placeholder="ketik nama petugas"
                                                        @item-select="setTandaTangan($event)" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </VCard>
                                </div>

                            </TabPanel>
                            <TabPanel header="C. POST OPERASI">
                                <div class="column is-12">
                                    <Fieldset :toggleable="true" legend="PENGKAJIAN">
                                        <div class="column is-12" v-for="(datas) in pengkajian3">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                                            <div class="columns is-multiline">
                                                <div class="column is-3" v-for="(data) in datas.value">
                                                    <VField v-if="data.type == 'checkBox'">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data.model]"
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                class="p-0" color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                    <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                                                        style="margin-bottom: 0.5rem;">
                                                        <VControl raw subcontrol>
                                                            <input v-model="input[data.model]" class="input p-0" />
                                                        </VControl>
                                                    </VField>

                                                </div>
                                            </div>

                                        </div>
                                        <div class="column is-12">
                                            <h1 style="font-weight: bold;"> Pengkajian Lainnya </h1>
                                            <VField>
                                                <VControl>
                                                    <VTextarea class="textarea" v-model="input.pengkajianLainnya3" rows="2"
                                                        placeholder="Pengkajian Lainnya" autocomplete="off"
                                                        autocapitalize="off" spellcheck="true" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </Fieldset>
                                </div>
                                <div class="column is-12">
                                    <Fieldset :toggleable="true" legend="DIAGNOSA">
                                        <div class="column is-12" v-for="(datas) in diagnosa3">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                                            <div class="columns is-multiline">
                                                <div class="column is-4" v-for="(data) in datas.value">
                                                    <VField v-if="data.type == 'checkBox'">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data.model]"
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                class="p-0" color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                    <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                                                        style="margin-bottom: 0.5rem;">
                                                        <VControl raw subcontrol>
                                                            <input v-model="input[data.model]" class="input p-0" />
                                                        </VControl>
                                                    </VField>

                                                </div>
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold;"> Diagnosa Lainnya </h1>
                                                    <VField>
                                                        <VControl>
                                                            <VTextarea class="textarea" v-model="input.diagnosaLainnya3"
                                                                rows="2" placeholder="Diagnosa Lainnya" autocomplete="off"
                                                                autocapitalize="off" spellcheck="true" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>

                                        </div>
                                    </Fieldset>
                                </div>
                                <div class="column is-12">
                                    <Fieldset :toggleable="true" legend="INTERVENSI">
                                        <div class="column is-12" v-for="(datas) in intervensi3">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                                            <div class="columns is-multiline">
                                                <div class="column is-12" v-for="(data) in datas.value">
                                                    <VField v-if="data.type == 'checkBox'">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data.model]"
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                class="p-0" color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                    <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                                                        style="margin-bottom: 0.5rem;">
                                                        <VControl raw subcontrol>
                                                            <input v-model="input[data.model]" class="input p-0" />
                                                        </VControl>
                                                    </VField>

                                                </div>
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold;"> Intervensi Lainnya </h1>
                                                    <VField>
                                                        <VControl>
                                                            <VTextarea class="textarea" v-model="input.intervensiLainnya3"
                                                                rows="2" placeholder="Intervensi Lainnya" autocomplete="off"
                                                                autocapitalize="off" spellcheck="true" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>

                                        </div>
                                    </Fieldset>
                                </div>
                                <div class="column is-12">
                                    <Fieldset :toggleable="true" legend="IMPLEMENTASI / TINDAKAN">
                                        <div class="column is-12" v-for="(datas) in implementasi3">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                                            <div class="columns is-multiline">
                                                <div class="column is-12" v-for="(data) in datas.value">
                                                    <VField v-if="data.type == 'checkBox'">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data.model]"
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                class="p-0" color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                    <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                                                        style="margin-bottom: 0.5rem;">
                                                        <VControl raw subcontrol>
                                                            <input v-model="input[data.model]" class="input p-0" />
                                                        </VControl>
                                                    </VField>

                                                </div>
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold;"> Implementasi Lainnya </h1>
                                                    <VField>
                                                        <VControl>
                                                            <VTextarea class="textarea" v-model="input.implementasiLainnya3"
                                                                rows="2" placeholder="Implementasi Lainnya"
                                                                autocomplete="off" autocapitalize="off" spellcheck="true" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>

                                        </div>
                                    </Fieldset>
                                </div>
                                <div class="column is-4 mt-3" style="margin-left: auto;">
                                    <VCard class="border-card pink">
                                        <div class="column is-9">
                                            <VField>
                                                <h1 style="font-weight: bold;">Tanggal / Jam</h1>
                                            </VField>
                                            <VField>
                                                <VDatePicker v-model="input.tanggal" mode="dateTime" style="width: 100%"
                                                    trim-weeks :max-date="new Date()">
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VField>
                                                            <VControl icon="feather:calendar" fullwidth>
                                                                <VInput :value="inputValue" placeholder="Tanggal"
                                                                    v-on="inputEvents" />
                                                            </VControl>
                                                        </VField>
                                                    </template>
                                                </VDatePicker>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'">
                                            </TandaTangan>
                                        </div>
                                        <div class="column pt-0">
                                            <VField>
                                                <h1 style="font-weight: bold;">Perawat RR</h1>
                                            </VField>
                                            <VField class="is-autocomplete-select" v-slot="{ id }">
                                                <VControl icon="feather:search">
                                                    <AutoComplete v-model="input.perawatRR" :suggestions="d_Pegawai"
                                                        @complete="fetchPegawai($event)" :optionLabel="'label'"
                                                        :dropdown="true" :minLength="3" :appendTo="'body'"
                                                        :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                        placeholder="ketik nama petugas"
                                                        @item-select="setTandaTangan($event)" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </VCard>
                                </div>
                            </TabPanel>
                            <TabPanel header="D. EVALUASI">
                                <div class="column is-12">
                                    <Fieldset :toggleable="true" legend="S/O">
                                        <div class="column is-12" v-for="(datas) in evaluasiS">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                                            <div class="columns is-multiline">
                                                <div class="column is-3" v-for="(data) in datas.value">
                                                    <VField v-if="data.type == 'checkBox'">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data.model]"
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                class="p-0" color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                    <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                                                        style="margin-bottom: 0.5rem;">
                                                        <VControl raw subcontrol>
                                                            <input v-model="input[data.model]" class="input p-0" />
                                                        </VControl>
                                                    </VField>

                                                </div>
                                            </div>

                                        </div>
                                    </Fieldset>
                                </div>
                                <div class="column is-12">
                                    <Fieldset :toggleable="true" legend="A">
                                        <div class="column is-12" v-for="(datas) in evaluasiA">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                                            <div class="columns is-multiline">
                                                <div class="column is-3" v-for="(data) in datas.value">
                                                    <VField v-if="data.type == 'checkBox'">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data.model]"
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                class="p-0" color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                    <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                                                        style="margin-bottom: 0.5rem;">
                                                        <VControl raw subcontrol>
                                                            <input v-model="input[data.model]" class="input p-0" />
                                                        </VControl>
                                                    </VField>

                                                </div>
                                            </div>

                                        </div>
                                    </Fieldset>
                                </div>
                                <div class="column is-12">
                                    <Fieldset :toggleable="true" legend="P">
                                        <div class="column is-12" v-for="(datas) in evaluasiP">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                                            <div class="columns is-multiline">
                                                <div class="column is-8" v-for="(data) in datas.value">
                                                    <VField v-if="data.type == 'checkBox'">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data.model]"
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                class="p-0" color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                    <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                                                        style="margin-bottom: 0.5rem;">
                                                        <VControl raw subcontrol>
                                                            <input v-model="input[data.model]" class="input p-0" />
                                                        </VControl>
                                                    </VField>

                                                </div>
                                            </div>

                                        </div>
                                    </Fieldset>
                                </div>
                                <div class="column is-4 mt-3" style="margin-left: auto;">
                                    <VCard class="border-card pink">
                                        <div class="column is-9">
                                            <VField>
                                                <h1 style="font-weight: bold;">Tanggal / Jam</h1>
                                            </VField>
                                            <VField>
                                                <VDatePicker v-model="input.tanggal" mode="dateTime" style="width: 100%"
                                                    trim-weeks :max-date="new Date()">
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VField>
                                                            <VControl icon="feather:calendar" fullwidth>
                                                                <VInput :value="inputValue" placeholder="Tanggal"
                                                                    v-on="inputEvents" />
                                                            </VControl>
                                                        </VField>
                                                    </template>
                                                </VDatePicker>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'">
                                            </TandaTangan>
                                        </div>
                                        <div class="column pt-0">
                                            <VField>
                                                <h1 style="font-weight: bold;">Perawat RR</h1>
                                            </VField>
                                            <VField class="is-autocomplete-select" v-slot="{ id }">
                                                <VControl icon="feather:search">
                                                    <AutoComplete v-model="input.perawatRR" :suggestions="d_Pegawai"
                                                        @complete="fetchPegawai($event)" :optionLabel="'label'"
                                                        :dropdown="true" :minLength="3" :appendTo="'body'"
                                                        :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                        placeholder="ketik nama petugas"
                                                        @item-select="setTandaTangan($event)" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </VCard>
                                </div>
                            </TabPanel>
                        </TabView>



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
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import * as EMR from '../page-emr-plugins/asuhan-keperawatan-peri-operatif'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let Pengkajian = ref(EMR.Pengkajian())
let tandaVital = ref(EMR.tandaVital())
let kesadaran = ref(EMR.kesadaran())
let Molekul = ref(EMR.Molekul())
let Integumen = ref(EMR.Integumen())
let diagnosa = ref(EMR.diagnosa())
let intervensi = ref(EMR.intervensi())
let implementasi = ref(EMR.implementasi())
let pengkajian2 = ref(EMR.pengkajian2())
let diagnosa2 = ref(EMR.diagnosa2())
let intervensi2 = ref(EMR.intervensi2())
let implementasi2 = ref(EMR.implementasi2())
let pengkajian3 = ref(EMR.pengkajian3())
let diagnosa3 = ref(EMR.diagnosa3())
let intervensi3 = ref(EMR.intervensi3())
let implementasi3 = ref(EMR.implementasi3())
let evaluasiS = ref(EMR.evaluasiS())
let evaluasiA = ref(EMR.evaluasiA())
let evaluasiP = ref(EMR.evaluasiP())


const props = withDefaults(
    defineProps<{
        pasien?: any
        registrasi?: any
        FORM_NAME?: string
        FORM_URL?: string
        COLLECTION?: string
    }>(),
    {
        pasien: {},
        registrasi: {},
        FORM_NAME: '',
        FORM_URL: '',
        COLLECTION: '',
    }
)
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value >= 20 })
const isLoading: any = ref(false)
const d_Pegawai: any = ref([])
const d_Ruangan: any = ref([])
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    tanggal: new Date(),
})
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
                if (response[0].tandaTanganPerawat) {
                    H.tandaTangan().set("signature_1", response[0].tandaTanganPerawat)
                }
            }
        })
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.tandaTanganPerawat = H.tandaTangan().get("signature_1")
    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': props.FORM_URL,
        'name_form': props.FORM_NAME,
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

const fetchPegawai = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Pegawai.value = response
    })
}
const fetchRuangan = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=id&query=${filter.query}&limit=10`)
    d_Ruangan.value = response
}
const setTandaTangan = async (e: any) => {
    const response = await useApi().get(
        `/emr/tanda-tangan/${e.value.value}`)
    if (response != null) {
        H.tandaTangan().set("signature_1", response.ttd)
        input.value.tandaTanganPerawat = response.ttd
    } else {
        H.tandaTangan().set("signature_1", '')
    }
}

const kembaliKeun = () => {
    window.history.back()
}
const setAutoFill = async () => {
    input.value.dokterRawat = props.registrasi.dokter
}
setView()
setAutoFill()
loadRiwayat()
</script>
  
<style lang="scss"></style>
  