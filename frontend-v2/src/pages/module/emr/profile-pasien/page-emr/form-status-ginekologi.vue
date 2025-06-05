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
                            <!-- <VButton type="button" rounded outlined color="warning" raised icon="lnir lnir-printer" @click="print()">
                Cetak
              </VButton> -->
                            <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                :loading="isLoading" @click="simpan()"> Simpan
                            </VButton>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="form-body p-2">
        <div class="business-dashboard hr-dashboard">
            <div class="columns is-multiline">
                <VCard class="border-card pink">
                    <div class="columns is-multiline p-3">
                        <!-- Triase -->
                        <div class="column is-12">
                            <div class="columns is-multiline p-2">
                                <!-- Keterangan Pasien Awal -->
                                <div class="column is-12">
                                    <VCard class="border-card pink">
                                        <div class="column is-12">
                                            <div class="columns is-multiline">
                                                <!-- Skoring nyeri -->
                                                <div class="column is-12">
                                                    <div class="columns is-multiline is-12 pl-5 pr-5 pt-5 pb-0">
                                                        <div class="column is-7">
                                                            <h1 style="font-weight: bold;">Skoring nyeri (Wong Baker
                                                                Faces)
                                                            </h1>
                                                            <div class="columns pt-4">
                                                                <div class="column" style="text-align: center;"
                                                                    v-for="(image, i) in listImageNyeri.detail">
                                                                    <VAvatar size="medium" :picture="image.img"
                                                                        :class="isAktive == i ? 'active' : ''"
                                                                        @click="test(image, i)" />
                                                                    <p>{{ image.descNilai }}</p>
                                                                    <p>{{ image.nama }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="column is-5">
                                                            <h1 style="font-weight: bold;">Score</h1>
                                                            <div class="pt-4">
                                                                <VField v-for="(skor) in listSkoringNyeri.detail">
                                                                    <VControl raw subcontrol class="p-0">
                                                                        <VCheckbox class="pt-0" v-model="input.skor"
                                                                            :true-value="skor.descNilai"
                                                                            :label="skor.nama" color="primary" square />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <!-- Resiko Jatuh Morse -->
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold;">
                                                        Resiko Jatuh Morse
                                                    </h1>
                                                    <table class="assesment-awal">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="2">Skor :</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="(datas) in ketJumlahPoin">
                                                                <td>
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input[datas.model]"
                                                                                style="font-weight: bold !important;color:var(--dark-text);"
                                                                                :true-value="datas.value"
                                                                                :label="datas.title" class="p-0 pr-3"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </td>
                                                                <td>
                                                                    <h1 class="emr">{{ datas.nilai }}</h1>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <hr>
                                                <!-- Skrining Gizi Awal -->
                                                <div class="column is-12">
                                                    <h1 style=" font-weight: bold; margin-bottom: 1rem;"> Skrining
                                                        Gizi Awal :</h1>
                                                    <div class="columns is-multiline">
                                                        <!-- dosis sirkulasi -->
                                                        <div class="column is-6">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox class="p-0" color="primary" square
                                                                        true-value="<2" label="< 2"
                                                                        v-model="input.SkriningGiziAwal" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <!-- dosis awal -->
                                                        <div class="column is-6">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox class="p-0" color="primary" square
                                                                        true-value="≥2" label="≥ 2"
                                                                        v-model="input.SkriningGiziAwal" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Riwayat Alergi -->
                                                <div class="column is-12">
                                                    <h1 style=" font-weight: bold; margin-bottom: 1rem;">
                                                        Riwayat Alergi :</h1>
                                                    <div class="columns is-multiline">
                                                        <!-- Riwayat Alergi Ya -->
                                                        <div class="column is-6">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox class="p-0" color="primary" square
                                                                        true-value="Ya" label="Ya"
                                                                        v-model="input.RiwayatAlergi" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <!-- Riwayat Alergi Tidak -->
                                                        <div class="column is-6">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox class="p-0" color="primary" square
                                                                        true-value="Tidak" label="Tidak"
                                                                        v-model="input.RiwayatAlergi" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <!-- obat -->
                                                        <div class="column is-6" v-if="input.RiwayatAlergi == 'Ya'">
                                                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">
                                                                Obat
                                                            </h1>
                                                            <VControl>
                                                                <VTextarea class="textarea"
                                                                    v-model="input.riwayatAlergiObat" rows="2"
                                                                    placeholder="Obat" autocomplete="off"
                                                                    autocapitalize="off" spellcheck="true" />
                                                            </VControl>
                                                        </div>
                                                        <!-- makanan -->
                                                        <div class="column is-6" v-if="input.RiwayatAlergi == 'Ya'">
                                                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">
                                                                Makanan
                                                            </h1>
                                                            <VControl>
                                                                <VTextarea class="textarea"
                                                                    v-model="input.riwayatAlergiMakanan" rows="2"
                                                                    placeholder="Makanan" autocomplete="off"
                                                                    autocapitalize="off" spellcheck="true" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <!-- Lokasi -->
                                                <div class="column is-6">
                                                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Lokasi</h1>
                                                    <VField>
                                                        <VControl>
                                                            <VInput type="text" v-model="input.lokasi"
                                                                placeholder="Lokasi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <!-- Durasi -->
                                                <div class="column is-6">
                                                    <h1 style="font-weight: bold; margin-bottom: 1rem;">
                                                        Durasi
                                                    </h1>
                                                    <VField addons>
                                                        <VControl expanded>
                                                            <VInput type="text" class="input" placeholder="Durasi"
                                                                v-model="input.durasi" />
                                                        </VControl>
                                                        <VControl class="field-addon-body">
                                                            <VButton static>Mnt</VButton>
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <!-- Akut/Kronik -->
                                                <div class="column is-4">
                                                    <h1 style="font-weight: bold; margin-bottom: 1rem;">
                                                        Akut/Kronik
                                                    </h1>
                                                    <div class="columns is-multiline">
                                                        <VField>
                                                            <VControl>
                                                                <VCheckbox v-model="input.akutKronik" true-value="Akut"
                                                                    label="Akut" color="primary" square />
                                                            </VControl>
                                                        </VField>
                                                        <VField>
                                                            <VControl>
                                                                <VCheckbox v-model="input.akutKronik"
                                                                    true-value="Kronik" label="Kronik" color="primary"
                                                                    square />
                                                            </VControl>

                                                        </VField>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </VCard>
                                </div>
                                <!-- end Keterangan Pasien Awal -->

                                <!-- Tubuh -->
                                <div class="column is-12">
                                    <Fieldset class="p-fieldsets" legend="Tubuh" :toggleable="true">
                                        <div class="column is-12">
                                            <div class="columns is-multiline p-3">
                                                <!-- Anatomi Tubuh -->
                                                <div class="column is-12">
                                                    <VCardHead title="Anatomi Tubuh" class="border-card yellow">
                                                        <div class="columns is-multiline">
                                                            <div class="column is-8">
                                                                <div :style="'background-image:url(' + GambarAnatomiTubuh + ')'"
                                                                    style="text-align: center; z-index: 9999;background-repeat: no-repeat;background-position: center;width: 600px;height: 800px;">
                                                                    <canvas id="anatomiTubuh" height="880"
                                                                        width="600"></canvas>
                                                                </div>
                                                                <VButton type="button" rounded outlined color="danger"
                                                                    raised icon="feather:trash"
                                                                    @click="clearAllAnatomiTubuh()">
                                                                    Clear
                                                                </VButton>
                                                            </div>
                                                            <div class="column is-4">
                                                                <div class="columns is-multiline">
                                                                    <div class="column is-12">
                                                                        <DataTable
                                                                            :value="input.dataMaskingAnatomiTubuh"
                                                                            class="p-datatable-sm" showGridlines>
                                                                            <template #empty>
                                                                                <p>Data Belum Diisi !.</p>
                                                                            </template>
                                                                            <Column field="no" header="No"></Column>
                                                                            <Column field="noted" header="Noted">
                                                                            </Column>
                                                                        </DataTable>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </VCardHead>
                                                </div>
                                                <!-- Abdomen -->
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold; margin-bottom: 0.5rem;">
                                                        Abdomen
                                                    </h1>
                                                    <VControl>
                                                        <VTextarea class="textarea" v-model="input.Abdomen" rows="3"
                                                            placeholder="Abdomen" autocomplete="off"
                                                            autocapitalize="off" spellcheck="true" />
                                                    </VControl>
                                                </div>
                                            </div>
                                        </div>
                                    </Fieldset>
                                </div>
                                <!-- end Anatomi Tubuh -->

                                <!-- Vagina -->
                                <div class="column is-12">
                                    <Fieldset class="p-fieldsets" legend="Vagina" :toggleable="true">
                                        <div class="column is-12">
                                            <div class="columns is-multiline p-3">
                                                <!--  Vagina -->
                                                <div class="column is-12">
                                                    <VCardHead title="Vagina" class="border-card yellow">
                                                        <div class="columns is-multiline">
                                                            <div class="column is-8">
                                                                <div :style="'background-image:url(' + GambarVagina + ')'"
                                                                    style="text-align: center; z-index: 9999;background-repeat: no-repeat;background-position: center;width: 600px;height: 800px;">
                                                                    <canvas id="vagina" height="880"
                                                                        width="600"></canvas>
                                                                </div>
                                                                <VButton type="button" rounded outlined color="danger"
                                                                    raised icon="feather:trash"
                                                                    @click="clearAllVagina()">
                                                                    Clear
                                                                </VButton>
                                                            </div>
                                                            <div class="column is-4">
                                                                <div class="columns is-multiline">
                                                                    <div class="column is-12">
                                                                        <DataTable :value="input.dataMaskingVagina"
                                                                            class="p-datatable-sm" showGridlines>
                                                                            <template #empty>
                                                                                <p>Data Belum Diisi !.</p>
                                                                            </template>
                                                                            <Column field="no" header="No"></Column>
                                                                            <Column field="noted" header="Noted">
                                                                            </Column>
                                                                        </DataTable>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </VCardHead>
                                                </div>
                                                <!-- Pemeriksaan dalam -->
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold; margin-bottom: 0.5rem;">
                                                        Pemeriksaan dalam
                                                    </h1>
                                                    <VControl>
                                                        <VTextarea class="textarea" v-model="input.PemeriksaanDalam"
                                                            rows="3" placeholder="Pemeriksaan dalam" autocomplete="off"
                                                            autocapitalize="off" spellcheck="true" />
                                                    </VControl>
                                                </div>
                                                <!-- Vulva/vagina -->
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold; margin-bottom: 0.5rem;">
                                                        Vulva/vagina
                                                    </h1>
                                                    <VControl>
                                                        <VTextarea class="textarea" v-model="input.VulvaVagina" rows="3"
                                                            placeholder="Vulva/vagina" autocomplete="off"
                                                            autocapitalize="off" spellcheck="true" />
                                                    </VControl>
                                                </div>
                                                <!-- Portio -->
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold; margin-bottom: 0.5rem;">
                                                        Portio
                                                    </h1>
                                                    <VControl>
                                                        <VTextarea class="textarea" v-model="input.Portio" rows="3"
                                                            placeholder="Portio" autocomplete="off" autocapitalize="off"
                                                            spellcheck="true" />
                                                    </VControl>
                                                </div>
                                                <!-- Corpus Uteri -->
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold; margin-bottom: 0.5rem;">
                                                        Corpus Uteri
                                                    </h1>
                                                    <VControl>
                                                        <VTextarea class="textarea" v-model="input.CorpusUteri" rows="3"
                                                            placeholder="Corpus Uteri" autocomplete="off"
                                                            autocapitalize="off" spellcheck="true" />
                                                    </VControl>
                                                </div>
                                                <!-- Kanan/kiri uterus -->
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold; margin-bottom: 0.5rem;">
                                                        Kanan/kiri uterus
                                                    </h1>
                                                    <VControl>
                                                        <VTextarea class="textarea" v-model="input.KananKiriUterus"
                                                            rows="3" placeholder="Kanan/kiri uterus" autocomplete="off"
                                                            autocapitalize="off" spellcheck="true" />
                                                    </VControl>
                                                </div>
                                                <!-- Cavumdouglas -->
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold; margin-bottom: 0.5rem;">
                                                        Cavumdouglas
                                                    </h1>
                                                    <VControl>
                                                        <VTextarea class="textarea" v-model="input.Cavumdouglas"
                                                            rows="3" placeholder="Cavumdouglas" autocomplete="off"
                                                            autocapitalize="off" spellcheck="true" />
                                                    </VControl>
                                                </div>
                                            </div>
                                        </div>
                                    </Fieldset>
                                </div>
                                <!-- end  Vagina -->

                                <!-- Serviks -->
                                <div class="column is-12">
                                    <Fieldset class="p-fieldsets" legend="Serviks" :toggleable="true">
                                        <div class="column is-12">
                                            <div class="columns is-multiline p-3">
                                                <!--  Serviks -->
                                                <div class="column is-12">
                                                    <VCardHead title="Serviks" class="border-card yellow">
                                                        <div class="columns is-multiline">
                                                            <div class="column is-8">
                                                                <div :style="'background-image:url(' + GambarServiks + ')'"
                                                                    style="text-align: center; z-index: 9999;background-repeat: no-repeat;background-position: center;width: 600px;height: 800px;">
                                                                    <canvas id="serviks" height="880"
                                                                        width="600"></canvas>
                                                                </div>
                                                                <VButton type="button" rounded outlined color="danger"
                                                                    raised icon="feather:trash"
                                                                    @click="clearAllServiks()">
                                                                    Clear
                                                                </VButton>
                                                            </div>
                                                            <div class="column is-4">
                                                                <div class="columns is-multiline">
                                                                    <div class="column is-12">
                                                                        <DataTable :value="input.dataMaskingServiks"
                                                                            class="p-datatable-sm" showGridlines>
                                                                            <template #empty>
                                                                                <p>Data Belum Diisi !.</p>
                                                                            </template>
                                                                            <Column field="no" header="No"></Column>
                                                                            <Column field="noted" header="Noted">
                                                                            </Column>
                                                                        </DataTable>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </VCardHead>
                                                </div>
                                                <!-- Inspekulo -->
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold; margin-bottom: 0.5rem;">
                                                        Inspekulo
                                                    </h1>
                                                    <VControl>
                                                        <VTextarea class="textarea" v-model="input.Inspekulo" rows="3"
                                                            placeholder="Inspekulo" autocomplete="off"
                                                            autocapitalize="off" spellcheck="true" />
                                                    </VControl>
                                                </div>
                                                <!-- Fluor -->
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold; margin-bottom: 0.5rem;">
                                                        Fluor
                                                    </h1>
                                                    <VControl>
                                                        <VTextarea class="textarea" v-model="input.Fluor" rows="3"
                                                            placeholder="Fluor" autocomplete="off" autocapitalize="off"
                                                            spellcheck="true" />
                                                    </VControl>
                                                </div>
                                                <!-- Fluksus -->
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold; margin-bottom: 0.5rem;">
                                                        Fluksus
                                                    </h1>
                                                    <VControl>
                                                        <VTextarea class="textarea" v-model="input.Fluksus" rows="3"
                                                            placeholder="Fluksus" autocomplete="off"
                                                            autocapitalize="off" spellcheck="true" />
                                                    </VControl>
                                                </div>
                                                <!-- Carcinomatus -->
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold; margin-bottom: 0.5rem;">
                                                        Carcinomatus
                                                    </h1>
                                                    <VControl>
                                                        <VTextarea class="textarea" v-model="input.Carcinomatus"
                                                            rows="3" placeholder="Carcinomatus" autocomplete="off"
                                                            autocapitalize="off" spellcheck="true" />
                                                    </VControl>
                                                </div>
                                            </div>
                                        </div>
                                    </Fieldset>
                                </div>
                                <!-- end  Serviks -->

                                <!-- Keluhan Utama / Kronologis Kejadian -->
                                <div class="column is-12">
                                    <Fieldset class="p-fieldsets" legend="Keluhan Utama / Kronologis Kejadian"
                                        :toggleable="true">
                                        <div class="column is-12">
                                            <div class="columns is-multiline p-3">
                                                <!-- Anamnesa -->
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold; margin-bottom: 0.5rem;">
                                                        Anamnesa
                                                    </h1>
                                                    <VControl>
                                                        <VTextarea class="textarea" v-model="input.Anamnesa" rows="3"
                                                            placeholder="Anamnesa" autocomplete="off"
                                                            autocapitalize="off" spellcheck="true" />
                                                    </VControl>
                                                </div>
                                                <!-- PemeriksaanFisik -->
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold; margin-bottom: 0.5rem;">
                                                        Pemeriksaan Fisik
                                                    </h1>
                                                    <VControl>
                                                        <VTextarea class="textarea" v-model="input.PemeriksaanFisik"
                                                            rows="3" placeholder="Pemeriksaan Fisik" autocomplete="off"
                                                            autocapitalize="off" spellcheck="true" />
                                                    </VControl>
                                                </div>
                                                <hr>
                                                <!-- rencanaPemeriksaanPenunjang -->
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold; margin-bottom: 0.5rem;">
                                                        Rencana Pemeriksaan Penunjang
                                                    </h1>
                                                    <VControl>
                                                        <VTextarea class="textarea"
                                                            v-model="input.rencanaPemeriksaanPenunjang" rows="3"
                                                            placeholder="Pemeriksaan Fisik" autocomplete="off"
                                                            autocapitalize="off" spellcheck="true" />
                                                    </VControl>
                                                </div>
                                                <!-- Diagnosa Kerja -->
                                                <div class="column is-12">
                                                    <div class="columns is-multiline">
                                                        <!-- Diagnosa -->
                                                        <div class="column is-2">
                                                            <h1 style="margin-bottom: 1rem; font-weight: bold;">Diagnosa
                                                                Kerja:
                                                            </h1>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VField class="is-rounded-select is-autocomplete-select"
                                                                v-slot="{ id }">
                                                                <VControl icon="lnir lnir-diagnosis" fullwidth>
                                                                    <Multiselect mode="single"
                                                                        v-model="input.diagnosaKerja"
                                                                        placeholder="Pilih data" :searchable="true"
                                                                        :filter-results="false" :min-chars="0"
                                                                        :resolve-on-load="true" :delay="0"
                                                                        :options="async function (query) { return await fetchDiagnosa10(query) }"
                                                                        autocomplete="off" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <!-- ICD 10  -->
                                                        <div class="column is-2">
                                                            <h1 style="margin-bottom: 1rem; font-weight: bold;">
                                                                ICD 10:
                                                            </h1>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VField class="is-rounded-select is-autocomplete-select"
                                                                v-slot="{ id }">
                                                                <VControl icon="lnir lnir-diagnosis" fullwidth>
                                                                    <Multiselect mode="single"
                                                                        v-model="input.icd10Kerja"
                                                                        placeholder="Pilih data" :searchable="true"
                                                                        :filter-results="false" :min-chars="0"
                                                                        :resolve-on-load="true" :delay="0"
                                                                        :options="async function (query) { return await fetchDiagnosa10(query) }"
                                                                        autocomplete="off" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Diagnosa Banding1 -->
                                                <div class="column is-12">
                                                    <div class="columns is-multiline">
                                                        <!-- Diagnosa -->
                                                        <div class="column is-2">
                                                            <h1 style="margin-bottom: 1rem; font-weight: bold;">Diagnosa
                                                                Banding 1:
                                                            </h1>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VField class="is-rounded-select is-autocomplete-select"
                                                                v-slot="{ id }">
                                                                <VControl icon="lnir lnir-diagnosis" fullwidth>
                                                                    <Multiselect mode="single"
                                                                        v-model="input.diagnosaBanding1"
                                                                        placeholder="Pilih data" :searchable="true"
                                                                        :filter-results="false" :min-chars="0"
                                                                        :resolve-on-load="true" :delay="0"
                                                                        :options="async function (query) { return await fetchDiagnosa10(query) }"
                                                                        autocomplete="off" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <!-- ICD 10  -->
                                                        <div class="column is-2">
                                                            <h1 style="margin-bottom: 1rem; font-weight: bold;">
                                                                ICD 10:
                                                            </h1>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VField class="is-rounded-select is-autocomplete-select"
                                                                v-slot="{ id }">
                                                                <VControl icon="lnir lnir-diagnosis" fullwidth>
                                                                    <Multiselect mode="single"
                                                                        v-model="input.icd10Banding1"
                                                                        placeholder="Pilih data" :searchable="true"
                                                                        :filter-results="false" :min-chars="0"
                                                                        :resolve-on-load="true" :delay="0"
                                                                        :options="async function (query) { return await fetchDiagnosa10(query) }"
                                                                        autocomplete="off" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Diagnosa Banding2 -->
                                                <div class="column is-12">
                                                    <div class="columns is-multiline">
                                                        <!-- Diagnosa -->
                                                        <div class="column is-2">
                                                            <h1 style="margin-bottom: 1rem; font-weight: bold;">Diagnosa
                                                                Banding 2:
                                                            </h1>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VField class="is-rounded-select is-autocomplete-select"
                                                                v-slot="{ id }">
                                                                <VControl icon="lnir lnir-diagnosis" fullwidth>
                                                                    <Multiselect mode="single"
                                                                        v-model="input.diagnosaBanding2"
                                                                        placeholder="Pilih data" :searchable="true"
                                                                        :filter-results="false" :min-chars="0"
                                                                        :resolve-on-load="true" :delay="0"
                                                                        :options="async function (query) { return await fetchDiagnosa10(query) }"
                                                                        autocomplete="off" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <!-- ICD 10  -->
                                                        <div class="column is-2">
                                                            <h1 style="margin-bottom: 1rem; font-weight: bold;">
                                                                ICD 10:
                                                            </h1>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VField class="is-rounded-select is-autocomplete-select"
                                                                v-slot="{ id }">
                                                                <VControl icon="lnir lnir-diagnosis" fullwidth>
                                                                    <Multiselect mode="single"
                                                                        v-model="input.icd10Banding2"
                                                                        placeholder="Pilih data" :searchable="true"
                                                                        :filter-results="false" :min-chars="0"
                                                                        :resolve-on-load="true" :delay="0"
                                                                        :options="async function (query) { return await fetchDiagnosa10(query) }"
                                                                        autocomplete="off" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <!-- rencanaTerapi -->
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold; margin-bottom: 0.5rem;">
                                                        Rencana Terapi
                                                    </h1>
                                                    <VControl>
                                                        <VTextarea class="textarea" v-model="input.rencanaTerapi"
                                                            rows="3" placeholder="Pemeriksaan Fisik" autocomplete="off"
                                                            autocapitalize="off" spellcheck="true" />
                                                    </VControl>
                                                </div>
                                                <!-- Tindakan Utama -->
                                                <div class="column is-12">
                                                    <div class="columns is-multiline">
                                                        <!-- Tindakan -->
                                                        <div class="column is-2">
                                                            <h1 style="margin-bottom: 1rem; font-weight: bold;">
                                                                Tindakan Utama:
                                                            </h1>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VField class="is-rounded-select is-autocomplete-select"
                                                                v-slot="{ id }">
                                                                <VControl icon="lnir lnir-diagnosis" fullwidth>
                                                                    <Multiselect mode="single"
                                                                        v-model="input.tindakanUtama"
                                                                        placeholder="Pilih data" :searchable="true"
                                                                        :filter-results="false" :min-chars="0"
                                                                        :resolve-on-load="true" :delay="0"
                                                                        :options="async function (query) { return await fetchDiagnosa9(query) }"
                                                                        autocomplete="off" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <!-- ICD 9 CM  -->
                                                        <div class="column is-2">
                                                            <h1 style="margin-bottom: 1rem; font-weight: bold;">
                                                                ICD 9 CM:
                                                            </h1>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VField class="is-rounded-select is-autocomplete-select"
                                                                v-slot="{ id }">
                                                                <VControl icon="lnir lnir-diagnosis" fullwidth>
                                                                    <Multiselect mode="single"
                                                                        v-model="input.icd9cmKerja"
                                                                        placeholder="Pilih data" :searchable="true"
                                                                        :filter-results="false" :min-chars="0"
                                                                        :resolve-on-load="true" :delay="0"
                                                                        :options="async function (query) { return await fetchDiagnosa9(query) }"
                                                                        autocomplete="off" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Tindakan Sekunder1 -->
                                                <div class="column is-12">
                                                    <div class="columns is-multiline">
                                                        <!-- Diagnosa -->
                                                        <div class="column is-2">
                                                            <h1 style="margin-bottom: 1rem; font-weight: bold;">
                                                                Tindakan Sekunder 1:
                                                            </h1>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VField class="is-rounded-select is-autocomplete-select"
                                                                v-slot="{ id }">
                                                                <VControl icon="lnir lnir-diagnosis" fullwidth>
                                                                    <Multiselect mode="single"
                                                                        v-model="input.tindakanSekunder1"
                                                                        placeholder="Pilih data" :searchable="true"
                                                                        :filter-results="false" :min-chars="0"
                                                                        :resolve-on-load="true" :delay="0"
                                                                        :options="async function (query) { return await fetchDiagnosa9(query) }"
                                                                        autocomplete="off" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <!-- ICD 9 CM  -->
                                                        <div class="column is-2">
                                                            <h1 style="margin-bottom: 1rem; font-weight: bold;">
                                                                ICD 9 CM:
                                                            </h1>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VField class="is-rounded-select is-autocomplete-select"
                                                                v-slot="{ id }">
                                                                <VControl icon="lnir lnir-diagnosis" fullwidth>
                                                                    <Multiselect mode="single"
                                                                        v-model="input.icd9cmSekunder1"
                                                                        placeholder="Pilih data" :searchable="true"
                                                                        :filter-results="false" :min-chars="0"
                                                                        :resolve-on-load="true" :delay="0"
                                                                        :options="async function (query) { return await fetchDiagnosa10(query) }"
                                                                        autocomplete="off" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- tindakan Sekunder2 -->
                                                <div class="column is-12">
                                                    <div class="columns is-multiline">
                                                        <!-- tindakan -->
                                                        <div class="column is-2">
                                                            <h1 style="margin-bottom: 1rem; font-weight: bold;">Tindakan
                                                                Sekunder 2:
                                                            </h1>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VField class="is-rounded-select is-autocomplete-select"
                                                                v-slot="{ id }">
                                                                <VControl icon="lnir lnir-diagnosis" fullwidth>
                                                                    <Multiselect mode="single"
                                                                        v-model="input.tindakanSekunder2"
                                                                        placeholder="Pilih data" :searchable="true"
                                                                        :filter-results="false" :min-chars="0"
                                                                        :resolve-on-load="true" :delay="0"
                                                                        :options="async function (query) { return await fetchDiagnosa9(query) }"
                                                                        autocomplete="off" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <!-- ICD 9 CM  -->
                                                        <div class="column is-2">
                                                            <h1 style="margin-bottom: 1rem; font-weight: bold;">
                                                                ICD 9 CM:
                                                            </h1>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VField class="is-rounded-select is-autocomplete-select"
                                                                v-slot="{ id }">
                                                                <VControl icon="lnir lnir-diagnosis" fullwidth>
                                                                    <Multiselect mode="single"
                                                                        v-model="input.icd9cmSekunder2"
                                                                        placeholder="Pilih data" :searchable="true"
                                                                        :filter-results="false" :min-chars="0"
                                                                        :resolve-on-load="true" :delay="0"
                                                                        :options="async function (query) { return await fetchDiagnosa9(query) }"
                                                                        autocomplete="off" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Diputuskan Untuk -->
                                                <div class="column is-12">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-2">
                                                            <h2 style="font-weight: bold; margin-bottom: 1rem;">
                                                                Diputuskan Untuk :
                                                            </h2>
                                                        </div>
                                                        <div class="column is-10">
                                                            <div class="columns is-multiline"
                                                                v-for="(data, i) in diputuskanUntuk" :key="i">
                                                                <div v-if="data.details" class="column is-12">
                                                                    <div class="columns is-multiline">
                                                                        <div class="column is-4">
                                                                            <VField>
                                                                                <VControl raw subcontrol>
                                                                                    <VCheckbox
                                                                                        v-model="input[data.model]"
                                                                                        :true-value="data.value"
                                                                                        :label="data.label"
                                                                                        class="p-0 mb-3" color="primary"
                                                                                        square />
                                                                                </VControl>
                                                                            </VField>
                                                                        </div>
                                                                        <div class="column is-6"
                                                                            v-if="input.diputuskanUntuk == data.value">
                                                                            <div class="columns is-multiline"
                                                                                v-for="(dataDetail, i) in data.details"
                                                                                :key="i">
                                                                                <VField>
                                                                                    <VControl raw subcontrol>
                                                                                        <VCheckbox
                                                                                            v-model="input[dataDetail.model]"
                                                                                            :true-value="dataDetail.value"
                                                                                            :label="dataDetail.label"
                                                                                            class="p-0 mb-3"
                                                                                            color="primary" square />
                                                                                    </VControl>
                                                                                </VField>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div v-else class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.diputuskanUntuk"
                                                                                :true-value="data.value"
                                                                                :label="data.label" class="p-0 mb-3"
                                                                                color="primary" square />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </Fieldset>
                                </div>
                                <!-- end Keluhan Utama / Kronologis Kejadian -->
                            </div>
                        </div>

                        <!-- Tanda Tangan Dokter -->
                        <div class="column is-12">
                            <div class="column is-12" style="margin-right: auto;">
                                <VCard class="border-card pink">
                                    <div class="columns is-multiline p-3">
                                        <!-- Tanda Tangan -->
                                        <div class="column is-6 is-offset-6">
                                            <VField>
                                                <h1 style="font-weight: bold;">Subang , tanggal
                                                    dan jam</h1>
                                            </VField>
                                            <VField>
                                                <VDatePicker v-model="input.tanggal" mode="dateTime" style="width: 100%"
                                                    trim-weeks :max-date="new Date()">
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VField>
                                                            <VControl icon="feather:calendar" fullwidth>
                                                                <VInput :value="inputValue" placeholder=""
                                                                    v-on="inputEvents" />
                                                            </VControl>
                                                        </VField>
                                                    </template>
                                                </VDatePicker>
                                            </VField>
                                            <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'">
                                            </TandaTangan>
                                            <h1 class="mt-2" style="font-weight: bold; margin-bottom: 1rem;">
                                                Dokter</h1>
                                            <VField class="is-autocomplete-select" v-slot="{ id }">
                                                <VControl icon="feather:search">
                                                    <AutoComplete v-model="input.dokter" :suggestions="d_Dokter"
                                                        @complete="fetchDokter($event)" :optionLabel="'label'"
                                                        :dropdown="true" :minLength="3" :appendTo="'body'"
                                                        :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                        placeholder="ketik nama Dokter"
                                                        @item-select="setTandaTangan($event, 'signature_1')" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <!-- end Tanda Tangan -->
                                    </div>
                                </VCard>
                            </div>
                        </div>
                        <!-- end Tanda Tangan -->
                    </div>
                </VCard>
            </div>
        </div>
    </div>
    <!-- Modal Anatomi Tubuh -->
    <VModal is="form" :open="modalCanvaAnatomiTubuh" title="Keterangan" :cancel-label="'Tutup'" actions="right"
        @close="modalCanvaAnatomiTubuh = false, clearAnatomiTubuh()">
        <template #content>
            <div class="columns is-multiline">
                <div class="column is-12">
                    <VField label="Noted">
                        <VInput v-model="input.notedAnatomiTubuh" placeholder="Noted..."></VInput>
                    </VField>
                </div>
            </div>
        </template>
        <template #action>
            <VButton icon="feather:trash" @click="hapusModalCanvasAnatomiTubuh('anatomiTubuh')" :loading="isLoading"
                color="danger" raised>Hapus
            </VButton>
            <VButton icon="feather:save" @click="saveModalCanvasAnatomiTubuh()" :loading="isLoading" color="primary"
                raised>
                Simpan
            </VButton>
        </template>
    </VModal>
    <!-- Modal Vagina -->
    <VModal is="form" :open="modalCanvaVagina" title="Keterangan" :cancel-label="'Tutup'" actions="right"
        @close="modalCanvaVagina = false, clearVagina()">
        <template #content>
            <div class="columns is-multiline">
                <div class="column is-12">
                    <VField label="Noted">
                        <VInput v-model="input.notedVagina" placeholder="Noted..."></VInput>
                    </VField>
                </div>
            </div>
        </template>
        <template #action>
            <VButton icon="feather:trash" @click="hapusModalCanvasVagina('vagina')" :loading="isLoading" color="danger"
                raised>Hapus
            </VButton>
            <VButton icon="feather:save" @click="saveModalCanvasVagina()" :loading="isLoading" color="primary" raised>
                Simpan
            </VButton>
        </template>
    </VModal>
    <!-- Modal Serviks -->
    <VModal is="form" :open="modalCanvaServiks" title="Keterangan" :cancel-label="'Tutup'" actions="right"
        @close="modalCanvaServiks = false, clearServiks()">
        <template #content>
            <div class="columns is-multiline">
                <div class="column is-12">
                    <VField label="Noted">
                        <VInput v-model="input.notedServiks" placeholder="Noted..."></VInput>
                    </VField>
                </div>
            </div>
        </template>
        <template #action>
            <VButton icon="feather:trash" @click="hapusModalCanvasServiks('serviks')" :loading="isLoading"
                color="danger" raised>Hapus
            </VButton>
            <VButton icon="feather:save" @click="saveModalCanvasServiks()" :loading="isLoading" color="primary" raised>
                Simpan
            </VButton>
        </template>
    </VModal>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import Checkbox from 'primevue/checkbox';
// import Image from 'primevue/image';
import Fieldset from 'primevue/fieldset';
import * as EMR from '../page-emr-plugins/form-status-ginekologi'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import sleep from '/@src/utils/sleep'
import $ from "jquery";
import RadioButton from 'primevue/radiobutton';
import ConfirmDialog from 'primevue/confirmdialog'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'



let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let riwayatAlergiObat = ref(EMR.riwayatAlergiObat())

let ketJumlahPoin = ref(EMR.descripJmlPoin())

let listImageNyeri = ref(EMR.listImageNyeri())
let listSkoringNyeri = ref(EMR.listSkoringNyeri())
let diputuskanUntuk = ref(EMR.diputuskanUntuk())


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
    }
)
const route = useRoute()
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const loadData: any = ref(true)
const jumlahImdex = ref(1)
const isAktive = ref()
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref('FormStatusGinekologi') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    tanggal: new Date(),
})
const GambarAnatomiTubuh: any = ref('')
const modalCanvaAnatomiTubuh: any = ref(false)
const DataSumberAnatomiTubuh: any = ref([])
const numberMouseAnatomiTubuh: any = ref(0)

const GambarVagina: any = ref('')
const modalCanvaVagina: any = ref(false)
const DataSumberVagina: any = ref([])
const numberMouseVagina: any = ref(0)

const GambarServiks: any = ref('')
const modalCanvaServiks: any = ref(false)
const DataSumberServiks: any = ref([])
const numberMouseServiks: any = ref(0)

const d_Dokter: any = ref([])
const d_Perawat: any = ref([])
const d_Ruangan: any = ref([])
const d_Diagnosa: any = ref([])
const diagnosaList: any = ref([])
const tindakanList: any = ref([])
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
    GambarAnatomiTubuh.value = '/images/simrs/body-depan.png'
    GambarVagina.value = '/images/simrs/vagina.png'
    GambarServiks.value = '/images/simrs/serviks.png'
}

const fetchDokter = async (filter: any) => {
    await useApi()
        .get(
            `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
        )
        .then((response) => {
            d_Dokter.value = response
        })

}
const fetchPerawat = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Perawat.value = response
    })
}
async function fetchDiagnosa9(filter: any) {
    let query = ''
    if (filter) {
        query = filter.toLowerCase()
    }
    const response = await useApi().get(
        `/diagnosa/diagnosa-ix-paging?name=${query}&limit=10`)

    return response.diagnosatindakan.map((item: any) => {
        return { value: item.id, label: item.kddiagnosatindakan + ' - ' + item.namadiagnosatindakan, default: item }
    })
}
async function fetchDiagnosa10(filter: any) {

    let query = ''
    if (filter) {
        query = filter.toLowerCase()
    }
    const response = await useApi().get(
        `/diagnosa/diagnosa-x-paging?name=${query}&limit=10`
    )

    return response.diagnosa.map((item: any) => {
        return {
            value: item.id,
            label: item.kddiagnosa + ' - ' + item.namadiagnosa,
            default: item,
        }
    })
}
const setTandaTangan = async (e: any) => {
    const response = await useApi().get(
        `/emr/tanda-tangan/${e.value.value}`)
    if (response != null) {
        H.tandaTangan().set("signature_1", response.ttd)
        input.value.tandaTanganDokter = response.ttd
    } else {
        H.tandaTangan().set("signature_1", '')
    }
}

const test = (e: any, i: any) => {
    let listSkor = listSkoringNyeri.value.detail
    let listImage = listImageNyeri.value.detail

    listSkor.forEach((element: any) => {
        if (element.descNilai == e.descNilai) {
            input.value.skor = e.descNilai
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
        "rangeNilai": 45,
    }
    let resikoTinggi = {
        "keterangan": "Resiko Tinggi",
        "rangeNilai": 46,
    }

    ketJumlahPoin.value.forEach((element: any) => {
        if (e <= 24 && e <= element.value.rangeNilai) {
            input.value.tingkatResiko = tidakBeresiko
        }
        else if (e <= 45 && e <= element.value.rangeNilai) {
            input.value.tingkatResiko = resikoRendah
        }
        else if (e > 46 && e > element.value.rangeNilai) {
            input.value.tingkatResiko = resikoTinggi
        }

    })
}
const fetchDiagnosa = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa&query=${filter.query}&limit=10`)
    d_Diagnosa.value = response
}
const fetchRuangan = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=id&query=${filter.query}&limit=10`)
    d_Ruangan.value = response
}
const loadRiwayatDiagnosa = async () => {
    // riwayat diagnosa
    const diagnosa = await useApi().get(`diagnosa/riwayat-diagnosa-x?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}`);

    // Set nilai dari diagnosa ke dalam input form
    diagnosaList.value = diagnosa.data.map(item => ({
        diagnosa: item.id_diagnosa,
        icd10: item.id_diagnosa
    }));

    input.value.diagnosaKerja = diagnosaList.value[0] ? diagnosaList.value[0].diagnosa : null;
    input.value.icd10Kerja = diagnosaList.value[0] ? diagnosaList.value[0].icd10 : null;
    input.value.diagnosaBanding1 = diagnosaList.value[1] ? diagnosaList.value[1].diagnosa : null;
    input.value.icd10Banding1 = diagnosaList.value[1] ? diagnosaList.value[1].icd10 : null;
    input.value.diagnosaBanding2 = diagnosaList.value[2] ? diagnosaList.value[2].diagnosa : null;
    input.value.icd10Banding2 = diagnosaList.value[2] ? diagnosaList.value[2].icd10 : null;
}

const loadRiwayatTindakan = async () => {
    try {
        // Menunggu hasil dari pemanggilan API
        const tindakanResponse = await useApi().get(`diagnosa/riwayat-diagnosa-ix?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}`);

        // Set nilai dari tindakan ke dalam input form
        const tindakanList = tindakanResponse.data.map(item => ({
            tindakan: item.objectdiagnosatindakanfk,
            icd9: item.objectdiagnosatindakanfk
        }));

        input.value.tindakanUtama = tindakanList[0]?.tindakan || null;
        input.value.icd9cmKerja = tindakanList[0]?.icd9 || null;
        input.value.tindakanSekunder1 = tindakanList[1]?.tindakan || null;
        input.value.icd9cmSekunder1 = tindakanList[1]?.icd9 || null;
        input.value.tindakanSekunder2 = tindakanList[2]?.tindakan || null;
        input.value.icd9cmSekunder2 = tindakanList[2]?.icd9 || null;
    } catch (error) {
        console.error('Error loading riwayat tindakan:', error);
    }
};

const loadRiwayat = async () => {
    // if (NOREC_EMRPASIEN.value == '') return

    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (response.length) {
        input.value = response[0] //set ke inputan
        if (response[0].tandaTanganDokter) {
            H.tandaTangan().set("signature_1", response[0].tandaTanganDokter)
        }
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }

        if (response[0].anatomiTubuh) {
            let sigCanvasAnatomiTubuh: any = document.getElementById('anatomiTubuh')
            if (sigCanvasAnatomiTubuh) {
                let context = sigCanvasAnatomiTubuh.getContext('2d')
                context.clearRect(0, 0, sigCanvasAnatomiTubuh.width, sigCanvasAnatomiTubuh.height)
                let imagess = response[0].anatomiTubuh
                let background = new Image()
                background.src = imagess
                background.onload = function () {
                    context.drawImage(background, 0, 0, sigCanvasAnatomiTubuh.width, sigCanvasAnatomiTubuh.height)
                }
            }
        }
        if (response[0].vagina) {
            let sigCanvasVagina: any = document.getElementById('vagina')
            if (sigCanvasVagina) {
                let context = sigCanvasVagina.getContext('2d')
                context.clearRect(0, 0, sigCanvasVagina.width, sigCanvasVagina.height)
                let imagess = response[0].vagina
                let background = new Image()
                background.src = imagess
                background.onload = function () {
                    context.drawImage(background, 0, 0, sigCanvasVagina.width, sigCanvasVagina.height)
                }
            }
        }
        if (response[0].serviks) {
            let sigCanvasServiks: any = document.getElementById('serviks')
            if (sigCanvasServiks) {
                let context = sigCanvasServiks.getContext('2d')
                context.clearRect(0, 0, sigCanvasServiks.width, sigCanvasServiks.height)
                let imagess = response[0].serviks
                let background = new Image()
                background.src = imagess
                background.onload = function () {
                    context.drawImage(background, 0, 0, sigCanvasServiks.width, sigCanvasServiks.height)
                }
            }
        }
    }
}

const simpan = () => {
    console.log(COLLECTION.value)
    debugger
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    let sigCanvasAnatomiTubuh = document.getElementById('anatomiTubuh')
    if (sigCanvasAnatomiTubuh) {
        let context = sigCanvasAnatomiTubuh.getContext('2d')
        const dataURL = sigCanvasAnatomiTubuh.toDataURL()
        input.value.anatomiTubuh = dataURL
        object.anatomiTubuh = dataURL
    }

    let sigCanvasVagina = document.getElementById('vagina')
    if (sigCanvasVagina) {
        let context = sigCanvasVagina.getContext('2d')
        const dataURL = sigCanvasVagina.toDataURL()
        input.value.vagina = dataURL
        object.vagina = dataURL
    }

    let sigCanvasServiks = document.getElementById('serviks')
    if (sigCanvasServiks) {
        let context = sigCanvasServiks.getContext('2d')
        const dataURL = sigCanvasServiks.toDataURL()
        input.value.serviks = dataURL
        object.serviks = dataURL
    }

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object.tandaTanganDokter = H.tandaTangan().get("signature_1")
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
        }).catch((e: any) => {
            isLoading.value = false
        })
}

const kembaliKeun = () => {
    window.history.back()
}

const getPosition = (mouseEvent: any, sigCanvas: any) => {
    let rect = sigCanvas.getBoundingClientRect()
    return {
        X: mouseEvent.clientX - rect.left,
        Y: mouseEvent.clientY - rect.top,
    }
}
const markignSite = (idCanvas) => {
    let sigCanvas: any = document.getElementById(idCanvas)
    let context = sigCanvas.getContext('2d')
    context.strokeStyle = 'red'
    context.lineJoin = 'round'
    context.lineWidth = 2
    let is_touch_device = 'ontouchstart' in document.documentElement

    if (is_touch_device) {
        let drawer: any = {
            isDrawing: false,
            touchstart: function (coors: any) {
                context.beginPath()
                context.moveTo(coors.x, coors.y)
                this.isDrawing = true
            },
            touchmove: function (coors: any) {
                if (this.isDrawing) {
                    context.lineTo(coors.x, coors.y)
                    context.stroke()
                }
            },
            touchend: function (coors: any) {
                if (this.isDrawing) {
                    this.touchmove(coors)
                    this.isDrawing = false
                }
            },
        }

        function draw(event: any) {
            let coors = {
                x: event.targetTouches[0].pageX,
                y: event.targetTouches[0].pageY,
            }

            let obj = sigCanvas

            if (obj.offsetParent) {
                do {
                    coors.x -= obj.offsetLeft
                    coors.y -= obj.offsetTop
                } while ((obj = obj.offsetParent) != null)
            }

            drawer[event.type](coors)
        }

        sigCanvas.addEventListener('touchstart', draw, false)
        sigCanvas.addEventListener('touchmove', draw, false)
        sigCanvas.addEventListener('touchend', draw, false)

        sigCanvas.addEventListener(
            'touchmove',
            function (event: any) {
                event.preventDefault()
            },
            false
        )
    } else {
        $(`#${idCanvas}`).mousedown(function (mouseEvent: any) {
            let position = getPosition(mouseEvent, sigCanvas)
            context.moveTo(position.X, position.Y)
            context.beginPath()
            $(this)
                .mousemove(function (mouseEvent: any) {
                    drawLine(mouseEvent, sigCanvas, context)
                })
                .mouseup(function (mouseEvent: any) {
                    finishDrawing(mouseEvent, sigCanvas, context)
                })
                .mouseout(function (mouseEvent: any) {
                    finishDrawing(mouseEvent, sigCanvas, context)
                })
        })
    }
}
const drawLine = (mouseEvent: any, sigCanvas: any, context: any) => {
    let position = getPosition(mouseEvent, sigCanvas)

    context.lineTo(position.X, position.Y)
    context.stroke()
}
const finishDrawing = (mouseEvent: any, sigCanvas: any, context: any) => {
    drawLine(mouseEvent, sigCanvas, context)

    context.closePath()
    $(sigCanvas).unbind('mousemove').unbind('mouseup').unbind('mouseout')
}

// Fungsi menggambar angka dan fill kuning
const drawNumber = (position: any, context: any, number: number) => {
    const width = 20;
    const height = 20;
    const radius = 3;
    context.fillStyle = "yellow";
    context.beginPath();
    context.moveTo(position.X + radius, position.Y - 5); // Atas
    context.arcTo(position.X + width, position.Y - 5, position.X + width, position.Y + height + 5, radius); // Kanan Atas
    context.arcTo(position.X + width, position.Y + height + 5, position.X - 5, position.Y + height + 5, radius); // Kanan Bawah
    context.arcTo(position.X - 5, position.Y + height + 5, position.X - 5, position.Y - 5, radius); // Kiri Bawah
    context.arcTo(position.X - 5, position.Y - 5, position.X + width, position.Y - 5, radius); // Kiri Atas
    context.closePath();
    context.fill();
    context.strokeStyle = "black";
    context.lineWidth = 1;
    context.stroke();
    context.fillStyle = "black";
    context.font = "15px Arial";
    context.textAlign = "center";
    context.textBaseline = "middle";
    context.fillText(number, position.X + width / 2, position.Y + height / 2);
};

// Fungsi creating data
const markignSite2 = (idCanvas, sourceCanvas) => {
    let sigCanvas = document.getElementById(idCanvas);
    let context = sigCanvas.getContext('2d');
    context.strokeStyle = 'red';
    context.lineJoin = 'round';
    context.lineWidth = 2;
    let is_touch_device = 'ontouchstart' in document.documentElement;
    if (is_touch_device) {
    } else {
        $(`#${idCanvas}`).mousedown(function (mouseEvent) {
            let position = getPosition(mouseEvent, sigCanvas);
            context.moveTo(position.X, position.Y);
            context.beginPath();
            const rect = sigCanvas.getBoundingClientRect();
            const mouseX = mouseEvent.clientX - rect.left;
            const mouseY = mouseEvent.clientY - rect.top;
            let found = false;
            sourceCanvas.value.forEach((data, i) => {
                const dataCenterX = data.position.X + 20 / 2;
                const dataCenterY = data.position.Y + 30 / 2;
                const backgroundWidth = 20;
                const backgroundHeight = 30;

                if (
                    mouseX >= dataCenterX - backgroundWidth / 2 &&
                    mouseX <= dataCenterX + backgroundWidth / 2 &&
                    mouseY >= dataCenterY - backgroundHeight / 2 &&
                    mouseY <= dataCenterY + backgroundHeight / 2
                ) {
                    // console.log(`Titik (${mouseX}, ${mouseY}) sudah terisi dengan nomor ${data.no} ${data.noted}.`);
                    if (idCanvas == 'anatomiTubuh') {
                        input.value.notedAnatomiTubuh = data.noted;
                        input.value.keteranganAnatomiTubuh = data.keterangan;
                        input.value.currentDataIndexAnatomiTubuh = i;

                        showModalCanvasAnatomiTubuh();
                    } else if (idCanvas == 'vagina') {
                        input.value.notedVagina = data.noted;
                        input.value.keteranganVagina = data.keterangan;
                        input.value.currentDataIndexVagina = i;

                        showModalCanvasVagina();
                    } else if (idCanvas == 'serviks') {
                        input.value.notedServiks = data.noted;
                        input.value.keteranganServiks = data.keterangan;
                        input.value.currentDataIndexServiks = i;

                        showModalCanvasServiks();
                    }
                    found = true;
                    return;
                }
            });

            if (!found) {
                input.value.position = position;
                input.value.context = context;
                if (idCanvas == 'anatomiTubuh') {
                    input.value.currentDataIndexAnatomiTubuh = undefined;

                    showModalCanvasAnatomiTubuh();
                } else if (idCanvas == 'vagina') {
                    input.value.currentDataIndexVagina = undefined;
                    showModalCanvasVagina();
                } else if (idCanvas == 'serviks') {
                    input.value.currentDataIndexServiks = undefined;
                    showModalCanvasServiks();
                }
            }
        });
    }
};

const showModalCanvasAnatomiTubuh = () => {
    modalCanvaAnatomiTubuh.value = true
}
// Fungsi Hapus Canvas dan data DataSumberAnatomiTubuh
const clearAllAnatomiTubuh = () => {
    const sigCanvas = document.getElementById('anatomiTubuh');
    const context = sigCanvas.getContext('2d');
    DataSumberAnatomiTubuh.value = [];
    input.value.dataMaskingAnatomiTubuh = DataSumberAnatomiTubuh.value;
    context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
    numberMouseAnatomiTubuh.value = 0;
};
const saveModalCanvasAnatomiTubuh = () => {
    let dataIndex = input.value.currentDataIndexAnatomiTubuh;

    if (dataIndex !== undefined) {
        DataSumberAnatomiTubuh.value[dataIndex].notedAnatomiTubuh = input.value.notedAnatomiTubuh ?? '-';
        DataSumberAnatomiTubuh.value[dataIndex].keterangan = input.value.keterangan ?? '-';
    } else {
        let data = {
            noted: input.value.notedAnatomiTubuh ?? '-',
            keterangan: input.value.keterangan ?? '-',
            no: numberMouseAnatomiTubuh.value + 1,
            position: input.value.position
        };
        DataSumberAnatomiTubuh.value.push(data);
        input.value.dataMaskingAnatomiTubuh = DataSumberAnatomiTubuh.value;
        drawNumber(input.value.position, input.value.context, data.no);
        numberMouseAnatomiTubuh.value++;
    }

    modalCanvaAnatomiTubuh.value = false;
    clearAnatomiTubuh();
};
const hapusModalCanvasAnatomiTubuh = (idCanvas) => {
    const sigCanvas = document.getElementById(idCanvas);
    const context = sigCanvas.getContext('2d');
    let dataIndex = input.value.currentDataIndexAnatomiTubuh;

    if (dataIndex !== undefined && dataIndex !== null) {
        DataSumberAnatomiTubuh.value.splice(dataIndex, 1);
        input.value.dataMaskingAnatomiTubuh = DataSumberAnatomiTubuh.value;
        context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
        DataSumberAnatomiTubuh.value.forEach(data => {
            drawNumber(data.position, context, data.no);
        });
        modalCanvaAnatomiTubuh.value = false;
    }
};
const clearAnatomiTubuh = () => {
    delete input.value.notedAnatomiTubuh
    delete input.value.keterangan
}

// Vagina
const showModalCanvasVagina = () => {
    modalCanvaVagina.value = true
}
// Fungsi Hapus Canvas dan data DataSumberVagina
const clearAllVagina = () => {
    const sigCanvas = document.getElementById('vagina');
    const context = sigCanvas.getContext('2d');
    DataSumberVagina.value = [];
    input.value.dataMaskingVagina = DataSumberVagina.value;
    context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
    numberMouseVagina.value = 0;
};
const saveModalCanvasVagina = () => {
    let dataIndex = input.value.currentDataIndexVagina;

    if (dataIndex !== undefined) {
        DataSumberVagina.value[dataIndex].notedVagina = input.value.notedVagina ?? '-';
        DataSumberVagina.value[dataIndex].keterangan = input.value.keterangan ?? '-';
    } else {
        let data = {
            noted: input.value.notedVagina ?? '-',
            keterangan: input.value.keterangan ?? '-',
            no: numberMouseVagina.value + 1,
            position: input.value.position
        };
        DataSumberVagina.value.push(data);
        input.value.dataMaskingVagina = DataSumberVagina.value;
        drawNumber(input.value.position, input.value.context, data.no);
        numberMouseVagina.value++;
    }

    modalCanvaVagina.value = false;
    clearVagina();
};
const hapusModalCanvasVagina = (idCanvas) => {
    const sigCanvas = document.getElementById(idCanvas);
    const context = sigCanvas.getContext('2d');
    let dataIndex = input.value.currentDataIndexVagina;

    if (dataIndex !== undefined && dataIndex !== null) {
        DataSumberVagina.value.splice(dataIndex, 1);
        input.value.dataMaskingVagina = DataSumberVagina.value;
        context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
        DataSumberVagina.value.forEach(data => {
            drawNumber(data.position, context, data.no);
        });
        modalCanvaVagina.value = false;
    }
};
const clearVagina = () => {
    delete input.value.notedVagina
    delete input.value.keterangan
}


// Serviks
const showModalCanvasServiks = () => {
    modalCanvaServiks.value = true
}
// Fungsi Hapus Canvas dan data DataSumberServiks
const clearAllServiks = () => {
    const sigCanvas = document.getElementById('serviks');
    const context = sigCanvas.getContext('2d');
    DataSumberServiks.value = [];
    input.value.dataMaskingServiks = DataSumberServiks.value;
    context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
    numberMouseServiks.value = 0;
};
const saveModalCanvasServiks = () => {
    let dataIndex = input.value.currentDataIndexServiks;

    if (dataIndex !== undefined) {
        DataSumberServiks.value[dataIndex].notedServiks = input.value.notedServiks ?? '-';
        DataSumberServiks.value[dataIndex].keterangan = input.value.keterangan ?? '-';
    } else {
        let data = {
            noted: input.value.notedServiks ?? '-',
            keterangan: input.value.keterangan ?? '-',
            no: numberMouseServiks.value + 1,
            position: input.value.position
        };
        DataSumberServiks.value.push(data);
        input.value.dataMaskingServiks = DataSumberServiks.value;
        drawNumber(input.value.position, input.value.context, data.no);
        numberMouseServiks.value++;
    }

    modalCanvaServiks.value = false;
    clearServiks();
};
const hapusModalCanvasServiks = (idCanvas) => {
    const sigCanvas = document.getElementById(idCanvas);
    const context = sigCanvas.getContext('2d');
    let dataIndex = input.value.currentDataIndexServiks;

    if (dataIndex !== undefined && dataIndex !== null) {
        DataSumberServiks.value.splice(dataIndex, 1);
        input.value.dataMaskingServiks = DataSumberServiks.value;
        context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
        DataSumberServiks.value.forEach(data => {
            drawNumber(data.position, context, data.no);
        });
        modalCanvaServiks.value = false;
    }
};
const clearServiks = () => {
    delete input.value.notedServiks
    delete input.value.keterangan
}


onMounted(async () => {
    await sleep(1000)
    markignSite2('anatomiTubuh', DataSumberAnatomiTubuh)
    markignSite2('vagina', DataSumberVagina)
    markignSite2('serviks', DataSumberServiks)
})


const addNewItem = () => {

    input.value.detailsTglSkor.push({
        no: input.value.detailsTglSkor[input.value.detailsTglSkor.length - 1].no + 1,
        tgl: new Date(),
    });
}
const removeItem = (index: any) => {
    input.value.detailsTglSkor.splice(index, 1)
}
const print = async () => {
    // await useApi().get(`emr/cetak?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    H.printBlade(`emr/cetak-asesmen-awal-keper-ri?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
}
const setAutoFill = async () => {
}

setView()
setAutoFill()


onBeforeMount(async () => {
    try {
        await loadRiwayatTindakan()
        await loadRiwayatDiagnosa()
        await loadRiwayat()
        let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
        if (cache) input.value = cache
        loadData.value = false


    } catch (error) {
        console.error('Error mount cache TAB EMR:', error);
    }
});

onBeforeRouteLeave((to, from, next) => {
    try {
        let rouutename = from?.name
        H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
    } catch (error) {
        console.error('Error leave cache TAB EMR:', error);
    }
    next();
});
</script>

<style lang="scss">
.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
}

.tg td {
    border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 1px;

    // font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.p-fieldset-legend {
    margin-left: 15px;
}

.tg tr {
    height: 20px;
}

.tg th {
    border-color: var(--fade-grey-dark-3);
    border-style: solid;
    border-width: 1px;
    vertical-align: middle;
    // font-size: 14px;
    font-weight: bold;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg .tg-0lax {
    text-align: left;
    vertical-align: top
}

.input-30 {
    height: 30px;
}

.bg-colatas {
    position: sticky;
    background-color: aliceblue;
    left: 0;
    z-index: 2;
}

.bg-colatas2 {
    position: sticky;
    background-color: aliceblue;
    left: 150px;
    z-index: 2;
}

.bg-colatas3 {

    background-color: aliceblue;
    left: 0px;
    z-index: 2;
}


.bg-col {
    position: sticky;
    background-color: aliceblue;
    left: 0;
    z-index: 2;
}

.bg-col2 {
    position: sticky;
    background-color: aliceblue;
    left: 57px;
    z-index: 2;
}

.bg-col3 {
    position: sticky;
    background-color: aliceblue;
    left: 357px;
    z-index: 2;
}



.bg-th {
    text-align: center;
    background-color: #dedfe2d3;
}
</style>
