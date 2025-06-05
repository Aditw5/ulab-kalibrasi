<template>
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
            <div v-if="isStuck" :class="[isStuck && 'is-stuck']" style="margin-top:50px; padding: 0px 0px !important;" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ props.FORM_NAME }}</h3>
                    </div>
                    <div class="right">
                        <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan"
                            @kembaliKeun="kembaliKeun"></ButtonEmr>
                    </div>
                </div>
            </div>
            <div v-if="!isStuck" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ props.FORM_NAME }}</h3>
                    </div>
                    <div class="right">
                        <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan"
                        @kembaliKeun="kembaliKeun"></ButtonEmr>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="column">
        <div class="columns is-multiline p-5">
            <div class="column is-12">
                <VCard>
                    <Fieldset :toggleable="true" legend="DAFTAR TILIK PREVERIFIKASI PASIEN OPERASI / TINDAKAN INVASIF">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <table border="1" class="triase">
                                    <thead>
                                        <th style="width: 100%;">
                                            <table class="triase">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2" style="font-weight: bold;font-size: 18px;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    Jam Tiba di Kamar Operasi :
                                                                 </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VDatePicker v-model="input.jamTibaKamarOperasi" color="green" mode="time" is24hr>
                                                                        <template #default="{ inputValue, inputEvents }">
                                                                            <VField>
                                                                                <VControl icon="feather:clock">
                                                                                    <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                                                                                </VControl>
                                                                            </VField>
                                                                        </template>
                                                                    </VDatePicker>
                                                                    </VField>
                                                                 </div>
                                                            </div>
                                                        </th>
                                                        <th colspan="2" style="font-weight: bold;font-size: 18px;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    Jam Keluar dari Ruang Operasi :
                                                                 </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VDatePicker v-model="input.jamKeluarRuangOperasi" color="green" mode="time" is24hr>
                                                                        <template #default="{ inputValue, inputEvents }">
                                                                            <VField>
                                                                                <VControl icon="feather:clock">
                                                                                    <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                                                                                </VControl>
                                                                            </VField>
                                                                        </template>
                                                                    </VDatePicker>
                                                                    </VField>
                                                                 </div>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                    <tr style="font-size: 15px;">
                                                        <th>Ruang Rawat
                                                        </th>
                                                        <th>Tanggal operasi
                                                        </th>
                                                        <th>Urutan Jadwal Operasi
                                                        </th>
                                                        <th>Lokasi Operasi / Tindakan
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 25%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <AutoComplete v-model="input.namaRuangRawat" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                                                                            :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                                                            :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 25%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VDatePicker v-model="input.tglOperasi" mode="dateTime" style="width: 100%"
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
                                                            </div>
                                                        </td>
                                                        <td style="width: 25%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VTextarea rows="3" placeholder="..." v-model="input.ururtanJadwalOperasi"></VTextarea>
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 25%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.lokasiOperasiKanan"
                                                                                class="p-0" label="Kanan"
                                                                                color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.lokasiOperasiKiri"
                                                                                class="p-0" label="Kiri"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table class="triase">
                                                <thead class="bg-brown">
                                                    <tr style="font-size: 15px;">
                                                        <th colspan="5">PRE OPERASI
                                                        </th>
                                                        <th colspan="4">PASKA OPERASI
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 5%;">
                                                           No
                                                        </td>
                                                        <td style="width: 32%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    Kelengkapan
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                RI
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                OK
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                RR
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 33%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                Kelengkapan
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                OK
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                RR
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                RI
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 5%;">
                                                           1
                                                        </td>
                                                        <td style="width: 32%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    Status pasien (ruangan & poliklinik)
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1StatusRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1StatusOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1StatusRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 33%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                Kesadaran Nilai
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1KesadaranOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1KesadaranRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1KesadaranRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 5%;">
                                                           2
                                                        </td>
                                                        <td style="width: 32%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                   Informed COnsent ( Bedah & Anastesi) telaj ditandatangani
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1InformedRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1InformedsOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1InformedRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 33%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                Status
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1StatusOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1StatusRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1StatusRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 5%;">
                                                           3
                                                        </td>
                                                        <td style="width: 32%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                   Gelang identitas terpasang
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1GelangRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1GelangOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1GelangRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 33%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                Laporan Operasi
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1LapOperasiOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1LapOperasiRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1LapOperasiRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 5%;">
                                                           4
                                                        </td>
                                                        <td style="width: 32%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                   Konsul Kardiologi
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1kardiologiRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1kardiologiOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1kardiologiRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 33%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                Laporan Anastesi
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1LapAnastesiOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1LapAnastesiRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1LapAnastesiRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 5%;">
                                                           5
                                                        </td>
                                                        <td style="width: 32%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                   Konsul Penyakit Dalam
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1DalamRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1DalamOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1DalamRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 33%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                Resep
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1ResepOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1ResepRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1ResepRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 5%;">
                                                           6
                                                        </td>
                                                        <td style="width: 32%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                   Konsul Paru
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1ParuRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1ParuOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1ParuRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 33%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    Laporan Nosokomial
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1NosokomialOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1NosokomialRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1NosokomialRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 5%;">
                                                           7
                                                        </td>
                                                        <td style="width: 32%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                   Konsul Anak
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1AnakRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1AnakOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1AnakRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 33%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    Ringkasan Pulang
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1PulangOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1PulangRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1PulangRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 5%;">
                                                           8
                                                        </td>
                                                        <td style="width: 32%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                   Konsul Anastesi
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1KonsulAnastesiRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1KonsulAnastesiOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1KonsulAnastesiRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 33%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    Formulir Pemeriksaan Patologi
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1PatologiOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1PatologiRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1PatologiRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 5%;">
                                                           9
                                                        </td>
                                                        <td style="width: 32%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                   Golongan darah & darah tersedia
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1DarahRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1DarahOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasiDarahRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 33%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    Bahan spesimen : kultur, PA
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1BahanOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1BahanRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1BahanRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 5%;">
                                                           10
                                                        </td>
                                                        <td style="width: 32%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                   Hasil Laboratorium
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1LabRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1LabOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1LabRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 33%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    Hasil Radiologi, USG, CT Scan MRI
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1RadOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1RadRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1RadRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 5%;">
                                                           11
                                                        </td>
                                                        <td style="width: 32%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                   Hasil radiologi, USG, CT Scan, MRI
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1RadRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1RadOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1RadRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 33%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    Transfusi Darah
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1TransfusiOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1TransfusiRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.paskaOperasi1TransfusiRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 5%;">
                                                           12
                                                        </td>
                                                        <td style="width: 32%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                   Puasa
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1PuasaRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1PuasaOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1PuasaRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 33%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    CATATAN :
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VTextarea rows="3" placeholder="..." v-model="input.preOperasi1Catatan"></VTextarea>
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                        </td>
                                                        <td style="width: 5%;">
                                                        </td>
                                                        <td style="width: 5%;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 5%;">
                                                           13
                                                        </td>
                                                        <td style="width: 32%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                   Huknah
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1HuknahRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1HuknahOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1HuknahRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 33%;">
                                                        </td>
                                                        <td style="width: 5%;">
                                                        </td>
                                                        <td style="width: 5%;">
                                                        </td>
                                                        <td style="width: 5%;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 5%;">
                                                           14
                                                        </td>
                                                        <td style="width: 32%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                   Kebersihan Pasien (Mandi dengan antiseptik, cuci rambut, sikat gigi)
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1KebersihanRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1KebersihanOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1KebersihanRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td colspan="4" rowspan="5" style="width: 48%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <div :style="'background-image:url(' + MARKINGSITE + ')'"
                                                                        style="text-align: center; z-index: 9999;background-repeat: no-repeat;background-position: center;width: 600px;height: 300px;">
                                                                        <canvas id="markingsite" height="300" width="600"></canvas>
                                                                    </div><br>
                                                                    <VButton type="button" rounded outlined color="danger" raised icon="feather:trash"
                                                                        @click="clearCanvas('markingsite')"> Clear
                                                                    </VButton>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 5%;">
                                                           15
                                                        </td>
                                                        <td style="width: 32%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                   Lapangan Operasi dicukur
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1LapanganRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1LapanganOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1LapanganRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 5%;">
                                                           16
                                                        </td>
                                                        <td style="width: 32%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                   Gigi palsu, kaca mata, kontak, lensa, hearing aid, wig telah dilepas dan disimpan
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1GigiRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1GigiOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1GigiRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 5%;">
                                                           17
                                                        </td>
                                                        <td style="width: 32%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                   Tata rias dan cat kuku dihapus
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1TataRiasRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1TataRiasOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1TataRiasRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 5%;">
                                                           18
                                                        </td>
                                                        <td style="width: 32%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                   Penandaan (Mark site)
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1MarkRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1MarkOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1MarkRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 5%;">
                                                           19
                                                        </td>
                                                        <td style="width: 32%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                   Infus
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1InfusRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1InfusOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1InfusRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td colspan="4" style="width: 48%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                   Keterangan gambar
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 5%;">
                                                           20
                                                        </td>
                                                        <td style="width: 32%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                   Kateter
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1KateterRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1KateterOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1KateterRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td colspan="4" style="width: 48%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                   1. Area/sisi tubuh yang dilakukan tindakan operasi
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 5%;">
                                                           21
                                                        </td>
                                                        <td style="width: 32%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                   Alat Khusus/Implan tersedia
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1ImplanRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1ImplanOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1ImplanRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td colspan="4" style="width: 48%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                   2. Beri tanda silang guna mendeskripsikan secara singkat tindakan operasi
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 5%;">
                                                           22
                                                        </td>
                                                        <td style="width: 32%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                  Pesenan ICU tersedia
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1PesenanRI"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1PesenanOK"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.preOperasi1PesenanRR"
                                                                                class="p-0" color="primary"  />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td colspan="4" style="width: 48%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                   3. Penulisan tindakan operasi harus singkat, jelas & akurat
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table class="triase">
                                                <thead>
                                                    <tr>
                                                        <th colspan="3" style="text-align:center;font-weight: bold;font-size: 18px;">
                                                           Nama & TTD Petugas
                                                        </th>
                                                        <th colspan="3" style="text-align:center;font-weight: bold;font-size: 18px;">
                                                           Nama & TTD Petugas
                                                        </th>
                                                    </tr>
                                                    <tr style="font-size: 15px;">
                                                        <th style="text-align:center;font-weight: bold;">Pengantar
                                                        </th>
                                                        <th colspan="2" style="text-align:center;font-weight: bold;">Penerima
                                                        </th>
                                                        <th style="text-align:center;font-weight: bold;">Pengantar
                                                        </th>
                                                        <th colspan="2" style="text-align:center;font-weight: bold;">Penerima
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 16%;">
                                                            <div clas="mt-5" style="text-align: center;">
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-12" style="text-align: center;">
                                                                        <h1 style="font-weight: bold;"> Cirebon, Tanggal </h1>
                                                                        <VField class="mt-3">
                                                                            <VDatePicker v-model="input.tglPembuatanPengantar1" mode="dateTime" style="width: 100%"
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
                                                                </div>
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-12" style="text-align: center;">
                                                                        <img v-if="input.pengantar1"
                                                                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.pengantar1.label ? input.pengantar1.label : input.pengantar1) + ', ' + (input.pengantar1.value ? input.pengantar1.value : input.pengantar1) + ', ' + (input.tglPembuatanPengantar1 ? input.tglPembuatanPengantar1 : input.tglPembuatanPengantar1)">
                                                                        <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
                                                                    </div>
                                                                </div>
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-12" style="text-align: center;">
                                                                        <h1 class="p-0" style="font-weight: bold;">Pengantar</h1>
                                                                        <VField>
                                                                            <VControl class="prime-auto">
                                                                                <AutoComplete v-model="input.pengantar1" :suggestions="d_Pegawai"
                                                                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                                                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                                                    :field="'label'" placeholder="Pengantar..." class="mt-2"/>
                                                                            </VControl>
                                                                        </VField>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 16%;">
                                                            <div clas="mt-5" style="text-align: center;">
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-12" style="text-align: center;">
                                                                        <h1 style="font-weight: bold;"> Cirebon, Tanggal </h1>
                                                                        <VField class="mt-3">
                                                                            <VDatePicker v-model="input.tglPembuatanPenerimaPre1" mode="dateTime" style="width: 100%"
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
                                                                </div>
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-12" style="text-align: center;">
                                                                        <img v-if="input.penerimaPre1"
                                                                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.penerimaPre1.label ? input.penerimaPre1.label : input.penerimaPre1) + ', ' + (input.penerimaPre1.value ? input.penerimaPre1.value : input.penerimaPre1) + ', ' + (input.tglPembuatanPenerimaPre1 ? input.tglPembuatanPenerimaPre1 : input.tglPembuatanPenerimaPre1)">
                                                                        <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
                                                                    </div>
                                                                </div>
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-12" style="text-align: center;">
                                                                        <h1 class="p-0" style="font-weight: bold;">Penerima</h1>
                                                                        <VField>
                                                                            <VControl class="prime-auto">
                                                                                <AutoComplete v-model="input.penerimaPre1" :suggestions="d_Pegawai"
                                                                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                                                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                                                    :field="'label'" placeholder="Penerima..." class="mt-2"/>
                                                                            </VControl>
                                                                        </VField>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 17%;">
                                                            <div clas="mt-5" style="text-align: center;">
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-12" style="text-align: center;">
                                                                        <h1 style="font-weight: bold;"> Cirebon, Tanggal </h1>
                                                                        <VField class="mt-3">
                                                                            <VDatePicker v-model="input.tglPembuatanPenerimaPre2" mode="dateTime" style="width: 100%"
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
                                                                </div>
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-12" style="text-align: center;">
                                                                        <img v-if="input.penerimaPre2"
                                                                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.penerimaPre2.label ? input.penerimaPre2.label : input.penerimaPre2) + ', ' + (input.penerimaPre2.value ? input.penerimaPre2.value : input.penerimaPre2) + ', ' + (input.tglPembuatanPenerimaPre2 ? input.tglPembuatanPenerimaPre2 : input.tglPembuatanPenerimaPre2)">
                                                                        <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
                                                                    </div>
                                                                </div>
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-12" style="text-align: center;">
                                                                        <h1 class="p-0" style="font-weight: bold;">Penerima</h1>
                                                                        <VField>
                                                                            <VControl class="prime-auto">
                                                                                <AutoComplete v-model="input.penerimaPre2" :suggestions="d_Pegawai"
                                                                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                                                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                                                    :field="'label'" placeholder="Penerima..." class="mt-2"/>
                                                                            </VControl>
                                                                        </VField>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 17%;">
                                                            <div clas="mt-5" style="text-align: center;">
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-12" style="text-align: center;">
                                                                        <h1 style="font-weight: bold;"> Cirebon, Tanggal </h1>
                                                                        <VField class="mt-3">
                                                                            <VDatePicker v-model="input.tglPembuatanPengantar2" mode="dateTime" style="width: 100%"
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
                                                                </div>
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-12" style="text-align: center;">
                                                                        <img v-if="input.pengantar2"
                                                                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.pengantar2.label ? input.pengantar2.label : input.pengantar2) + ', ' + (input.pengantar2.value ? input.pengantar2.value : input.pengantar2) + ', ' + (input.tglPembuatanPengantar2 ? input.tglPembuatanPengantar2 : input.tglPembuatanPengantar2)">
                                                                        <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
                                                                    </div>
                                                                </div>
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-12" style="text-align: center;">
                                                                        <h1 class="p-0" style="font-weight: bold;">Pengantar</h1>
                                                                        <VField>
                                                                            <VControl class="prime-auto">
                                                                                <AutoComplete v-model="input.pengantar2" :suggestions="d_Pegawai"
                                                                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                                                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                                                    :field="'label'" placeholder="Pengantar..." class="mt-2"/>
                                                                            </VControl>
                                                                        </VField>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 17%;">
                                                            <div clas="mt-5" style="text-align: center;">
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-12" style="text-align: center;">
                                                                        <h1 style="font-weight: bold;"> Cirebon, Tanggal </h1>
                                                                        <VField class="mt-3">
                                                                            <VDatePicker v-model="input.tglPembuatanPenerimaPaska1" mode="dateTime" style="width: 100%"
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
                                                                </div>
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-12" style="text-align: center;">
                                                                        <img v-if="input.penerimaPaska1"
                                                                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.penerimaPaska1.label ? input.penerimaPaska1.label : input.penerimaPaska1) + ', ' + (input.penerimaPaska1.value ? input.penerimaPaska1.value : input.penerimaPaska1) + ', ' + (input.tglPembuatanPenerimaPaska1 ? input.tglPembuatanPenerimaPaska1 : input.tglPembuatanPenerimaPaska1)">
                                                                        <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
                                                                    </div>
                                                                </div>
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-12" style="text-align: center;">
                                                                        <h1 class="p-0" style="font-weight: bold;">Penerima</h1>
                                                                        <VField>
                                                                            <VControl class="prime-auto">
                                                                                <AutoComplete v-model="input.penerimaPaska1" :suggestions="d_Pegawai"
                                                                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                                                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                                                    :field="'label'" placeholder="Penerima..." class="mt-2"/>
                                                                            </VControl>
                                                                        </VField>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 17%;">
                                                            <div clas="mt-5" style="text-align: center;">
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-12" style="text-align: center;">
                                                                        <h1 style="font-weight: bold;"> Cirebon, Tanggal </h1>
                                                                        <VField class="mt-3">
                                                                            <VDatePicker v-model="input.tglPembuatanPenerimaPaska2" mode="dateTime" style="width: 100%"
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
                                                                </div>
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-12" style="text-align: center;">
                                                                        <img v-if="input.penerimaPaska2"
                                                                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.penerimaPaska2.label ? input.penerimaPaska2.label : input.penerimaPaska2) + ', ' + (input.penerimaPaska2.value ? input.penerimaPaska2.value : input.penerimaPaska2) + ', ' + (input.tglPembuatanPenerimaPaska2 ? input.tglPembuatanPenerimaPaska2 : input.tglPembuatanPenerimaPaska2)">
                                                                        <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
                                                                    </div>
                                                                </div>
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-12" style="text-align: center;">
                                                                        <h1 class="p-0" style="font-weight: bold;">Penerima</h1>
                                                                        <VField>
                                                                            <VControl class="prime-auto">
                                                                                <AutoComplete v-model="input.penerimaPaska2" :suggestions="d_Pegawai"
                                                                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                                                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                                                    :field="'label'" placeholder="Penerima..." class="mt-2"/>
                                                                            </VControl>
                                                                        </VField>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </th>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </Fieldset>
                </VCard>
            </div>
        </div>
    </div>
    <Dialog v-model:visible="isLab" modal header="Order Laboratorim" :style="{ width: '80vw' }">
        <OrderLab :pasien="props.pasien" :registrasi="props.registrasi" />
        <template #footer>
        <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="tutupLabSetAutofill()">
            Tutup
        </VButton>
        </template>
    </Dialog>

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
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/laporan-operasi'
import Dialog from 'primevue/dialog';
import OrderLab from './order-laboratorium.vue'
import sleep from '/@src/utils/sleep'
import $ from "jquery";

const d_Ruangan: any = ref([])
const d_Dokter: any = ref([])
const isLab: any = ref(false)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let laporan = ref(EMR.laporan())

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
const MARKINGSITE: any = ref('')
const isLoading: any = ref(false)
const d_Pegawai: any = ref([])
const d_Diagnosa: any = ref([])
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
    jamTibaKamarOperasi: new Date(),
    jamKeluarRuangOperasi: new Date(),
})
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
    MARKINGSITE.value = '/images/simrs/tilik.png'
}

const addNewDiagnosaicd10 = () => {
  input.value.diagnosaICD10.push({
    no: input.value.diagnosaICD10[input.value.diagnosaICD10.length - 1].no + 1,
  });
}
const removeItemDiagnosaIcd10 = (index: any) => {
  input.value.diagnosaICD10.splice(index, 1)
}

const fetchDokter = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}
const fetchPegawai = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Pegawai.value = response
}
const fetchDiagnosa = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa&query=${filter.query}&limit=10`)
    d_Diagnosa.value = response
}
const fetchRuangan = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
    d_Ruangan.value = response
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
                if (response[0].tandaBodyDraw) {
                    H.tandaTangan().set("markingsite", response[0].tandaBodyDraw, 600, 300)
                }
            }
        })
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object.tandaBodyDraw = H.tandaTangan().get("markingsite")
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

const kembaliKeun = () => {
    window.history.back()
}

const tutupLabSetAutofill = async () => {
  isLab.value = false;
  setAutoFill()
}

const clearCanvas = (canvas: any) => {

var sigCanvas: any = document.getElementById(canvas);
var context = sigCanvas.getContext("2d");
context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);

}

const getPosition = (mouseEvent: any, sigCanvas: any) => {
  let rect = sigCanvas.getBoundingClientRect();
  return {
    X: mouseEvent.clientX - rect.left,
    Y: mouseEvent.clientY - rect.top
  };
}

const markignSite = () => {
  let sigCanvas: any = document.getElementById("markingsite");
  // sigCanvas.height = 500
  // sigCanvas.width = 500
  let context = sigCanvas.getContext("2d");
  context.strokeStyle = "red";
  context.lineJoin = "round";
  context.lineWidth = 2;
  let is_touch_device = 'ontouchstart' in document.documentElement;

  if (is_touch_device) {

    let drawer: any = {
      isDrawing: false,
      touchstart: function (coors: any) {
        context.beginPath();
        context.moveTo(coors.x, coors.y);
        this.isDrawing = true;
      },
      touchmove: function (coors: any) {
        if (this.isDrawing) {
          context.lineTo(coors.x, coors.y);
          context.stroke();
        }
      },
      touchend: function (coors: any) {
        if (this.isDrawing) {
          this.touchmove(coors);
          this.isDrawing = false;
        }
      }
    };


    function draw(event: any) {
      let coors = {
        x: event.targetTouches[0].pageX,
        y: event.targetTouches[0].pageY
      };

      let obj = sigCanvas;

      if (obj.offsetParent) {

        do {
          coors.x -= obj.offsetLeft;
          coors.y -= obj.offsetTop;
        }

        while ((obj = obj.offsetParent) != null);
      }


      drawer[event.type](coors);
    }


    sigCanvas.addEventListener('touchstart', draw, false);
    sigCanvas.addEventListener('touchmove', draw, false);
    sigCanvas.addEventListener('touchend', draw, false);


    sigCanvas.addEventListener('touchmove', function (event: any) {
      event.preventDefault();

    }, false);
  } else {

    $("#markingsite").mousedown(function (mouseEvent: any) {

      let position = getPosition(mouseEvent, sigCanvas);
      context.moveTo(position.X, position.Y);
      context.beginPath();
      $(this).mousemove(function (mouseEvent: any) {
        drawLine(mouseEvent, sigCanvas, context);
      }).mouseup(function (mouseEvent: any) {
        finishDrawing(mouseEvent, sigCanvas, context);
      }).mouseout(function (mouseEvent: any) {
        finishDrawing(mouseEvent, sigCanvas, context);
      });
    });

  }
}

const drawLine = (mouseEvent: any, sigCanvas: any, context: any) => {

let position = getPosition(mouseEvent, sigCanvas);

context.lineTo(position.X, position.Y);
context.stroke();
}
const finishDrawing = (mouseEvent: any, sigCanvas: any, context: any) => {
drawLine(mouseEvent, sigCanvas, context);

context.closePath();
$(sigCanvas).unbind("mousemove")
  .unbind("mouseup")
  .unbind("mouseout");
}

const setAutoFill = async () => {
    input.value.tglPembuatan = new Date()
    input.value.tglOperasi = new Date()
    input.value.dpjpPasien = props.registrasi.dokter
    input.value.namaPasien = props.pasien.namapasien
    input.value.tglLahir = props.pasien.tgllahir
    input.value.namaRuangRawat = props.registrasi.namaruangan
}
async function performAction() {
  await sleep(1000);
  markignSite();
}
performAction();
setView()
setAutoFill()
loadRiwayat()
</script>
<style lang="scss">
table.triase {
    border-collapse: collapse;
    width: 100%;
}

table.triase,
th,
.triase td {
    border: 1px solid black;
}

table.triase,
th {
    // text-align: center;

}

.bg-green {
    background-color: var(--primary);
}

.bg-warning {
    background-color: var(--warning);
}

.bg-danger {
    background-color: var(--danger);
}

.bg-brown {
    background-color: gray;
}

.triase th,
td {
    padding: 8px;
    vertical-align: top !important;
}
</style>
