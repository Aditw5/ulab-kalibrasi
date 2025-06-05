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
                    <Fieldset :toggleable="true"
                        legend="Lembar Verifikasi Dan Penandaan Lokasi Prosedur Pasien Operasi">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <table border="1" class="triase">
                                    <thead>
                                        <th class="bg-brown"
                                            style="width: 3%;font-weight: bold;vertical-align: middle !important;font-size: 25px;">
                                            <span>I</span><br>
                                            <span>R</span><br>
                                            <span>N</span><br>
                                            <span>A</span>
                                        </th>
                                        <th style="width: 97%;">
                                            <table class="triase">
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 50%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-4 mt-3">
                                                                    <h1 class="p-0" style="font-weight: bold;">Dokter Operator :</h1>
                                                                </div>
                                                                <div class="column is-8">
                                                                    <VField>
                                                                        <VControl class="prime-auto">
                                                                            <AutoComplete v-model="input.dokterOperator" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                                                                :optionLabel="'label'" :dropdown="true"
                                                                                :minLength="3" :appendTo="'body'"
                                                                                :loadingIcon="'pi pi-spinner'"
                                                                                :field="'label'"
                                                                                placeholder="Dokter Operator..." class="mt-2"
                                                                                @item-select="setTandaTangan($event, 'signature_1')" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 50%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-4 mt-3">
                                                                    <h1 class="p-0" style="font-weight: bold;">Dokter Anastesi :</h1>
                                                                </div>
                                                                <div class="column is-8">
                                                                    <VField>
                                                                        <VControl class="prime-auto">
                                                                            <AutoComplete v-model="input.dokterAnastesi" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                                                                :optionLabel="'label'" :dropdown="true"
                                                                                :minLength="3" :appendTo="'body'"
                                                                                :loadingIcon="'pi pi-spinner'"
                                                                                :field="'label'"
                                                                                placeholder="Dokter Anastesi..." class="mt-2"
                                                                                @item-select="setTandaTangan($event, 'signature_1')" />
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
                                                        <th>
                                                            Asesmen Pra Operasi :
                                                        </th>
                                                        <th>
                                                            Verifikasi Pra Operasi :
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 50%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-4 mt-3">
                                                                    <h1 style="font-weight: bold;"> Jam & Tanggal :</h1>
                                                                </div>
                                                                <div class="column is-8">
                                                                        <VField class="mt-3">
                                                                            <VDatePicker v-model="input.tglPraOperasi"
                                                                                mode="dateTime" style="width: 100%"
                                                                                trim-weeks :max-date="new Date()">
                                                                                <template
                                                                                    #default="{ inputValue, inputEvents }">
                                                                                    <VField>
                                                                                        <VControl
                                                                                            icon="feather:calendar"
                                                                                            fullwidth>
                                                                                            <VInput :value="inputValue"
                                                                                                placeholder="Jam & Tanggal"
                                                                                                v-on="inputEvents" />
                                                                                        </VControl>
                                                                                    </VField>
                                                                                </template>
                                                                            </VDatePicker>
                                                                        </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.dataSubyektif"
                                                                                class="p-0" label="Data Subyektif (anamnesis)"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-12" v-if="input.dataSubyektif">
                                                                    <VField>
                                                                        <VControl>
                                                                            <VTextarea class="textarea" v-model="input.dataSubyektifKet" rows="2"
                                                                                placeholder="......" autocomplete="off" autocapitalize="off" spellcheck="true" />
                                                                            </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.dataObyektif"
                                                                                class="p-0" label="Data Obyektif (pemeriksaan fisik)"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-12" v-if="input.dataObyektif">
                                                                    <VField>
                                                                        <VControl>
                                                                            <VTextarea class="textarea" v-model="input.dataObyektifKet" rows="2"
                                                                                placeholder="......" autocomplete="off" autocapitalize="off" spellcheck="true" />
                                                                            </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 50%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <h1>Berkas Rekam Medis Terkait :</h1>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.berkasRekammedisInformed"
                                                                                class="p-0" label="Informed Consent"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.berkasRekammedisLain"
                                                                                class="p-0" label="Lain-lain"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-12" v-if="input.berkasRekammedisLain">
                                                                    <VField>
                                                                        <VControl>
                                                                            <VInput
                                                                                v-model="input.berkasRekammedisLainKet"
                                                                                placeholder="....." />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline mt-3">
                                                                <div class="column is-12">
                                                                    <h1>Hasil Pemeriksaan penunjang yang telah teridentifikasi secara benar :</h1>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-8">
                                                                    <VControl>
                                                                        <VTextarea v-model="input.pemeriksaPenunjang" rows="5" placeholder="Hasil Pemeriksaan Penunjang ...">
                                                                        </VTextarea>
                                                                    </VControl>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VLabel>Copy Hasil Laboratorium</VLabel>
                                                                        <VControl>
                                                                            <VIconButton style="margin-left: 30%" type="button" raised circle icon="fas fa-bong" v-tooltip-prime.bottom="'Terapkan Data'"
                                                                            @click="show" color="warning">
                                                                            </VIconButton>
                                                                        </VControl>
                                                                    </VField>
                                                                    <VField>
                                                                        <VLabel>Copy Hasil Radiologi</VLabel>
                                                                        <VControl>
                                                                            <VIconButton style="margin-left: 30%" type="button" raised circle icon="fas fa-radiation" v-tooltip-prime.bottom="'Terapkan Data'"
                                                                            @click="showRad" color="warning">
                                                                            </VIconButton>
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline mt-3">
                                                                <div class="column is-12">
                                                                    <h1>Darah / Alat khusus yang diperlukan :</h1>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VTextarea v-model="input.darahAlatKhsusDiperlukan" rows="5" placeholder="Darah / Alat khusus yang diperlukan...">
                                                                            </VTextarea>
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table class="triase">
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 50%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <h1>Diagnosis praoperasi</h1>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline"
                                                                v-for="(items, index) in input.diagnosaICD10"
                                                                :key="index">
                                                                <div class="column is-1">
                                                                    {{ items.no }}
                                                                </div>
                                                                <div class="column is-9">
                                                                    <VField>
                                                                        <VControl class="prime-auto">
                                                                            <AutoComplete v-model="items.ICD10Diagnosa"
                                                                                :suggestions="d_Diagnosa"
                                                                                @complete="fetchDiagnosa($event)"
                                                                                :optionLabel="items.label" :dropdown="true"
                                                                                :minLength="3" :appendTo="'body'"
                                                                                :loadingIcon="'pi pi-spinner'"
                                                                                :field="items.label"
                                                                                :placeholder="items.label" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-2">
                                                                    <VButtons style="justify-content: space-around;">
                                                                        <VIconButton type="button" raised circle
                                                                            icon="feather:plus"
                                                                            @click="addNewDiagnosaicd10()" color="info">
                                                                        </VIconButton>
                                                                        <VIconButton class="mt-1" v-if="index > 0"
                                                                            type="button" raised circle
                                                                            icon="feather:trash"
                                                                            @click="removeItemDiagnosaIcd10(index)"
                                                                            color="danger">
                                                                        </VIconButton>
                                                                    </VButtons>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 50%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <h1>ASA</h1>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl icon="feather:data" fullwidth>
                                                                            <VInput
                                                                                v-model="input.ASA"
                                                                                placeholder="....." />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline mt-3">
                                                                <div class="column is-6">
                                                                    <h1>Estimasi waktu yang dibutuhkan</h1>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <!-- <VDatePicker v-model="input.estimasiYangDibutuhkan" color="green" mode="time" is24hr>
                                                                        <template #default="{ inputValue, inputEvents }">
                                                                            <VField>
                                                                                <VControl icon="feather:clock">
                                                                                    <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                                                                                </VControl>
                                                                            </VField>
                                                                        </template>
                                                                    </VDatePicker> -->
                                                                    <VField>
                                                                        <VControl fullwidth>
                                                                            <VInput v-model="input.estimasiYangDibutuhkan"
                                                                                placeholder="....."/>
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table class="triase">
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 50%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <h1>Rencana Operasi</h1>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VTextarea rows="3" placeholder="..."
                                                                                v-model="input.rencanaOperasi">
                                                                            </VTextarea>
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 50%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <h1>Rencana Anastesi</h1>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VTextarea rows="3" placeholder="..."
                                                                                v-model="input.rencanaAnastesi">
                                                                            </VTextarea>
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table class="triase">
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 50%;">
                                                            <div clas="mt-5" style="text-align: center;">
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-3" style="text-align: center;">
                                                                    </div>
                                                                    <div class="column is-6" style="text-align: center;">
                                                                        <h1 style="font-weight: bold;"> 
                                                                            Tanda Tangan Dokter Operator
                                                                        </h1>
                                                                        <VField class="mt-3">
                                                                            <VDatePicker v-model="input.tglPembuatanOperator"
                                                                                mode="dateTime" style="width: 100%"
                                                                                trim-weeks :max-date="new Date()">
                                                                                <template
                                                                                    #default="{ inputValue, inputEvents }">
                                                                                    <VField>
                                                                                        <VControl
                                                                                            icon="feather:calendar"
                                                                                            fullwidth>
                                                                                            <VInput :value="inputValue"
                                                                                                placeholder="Tanggal"
                                                                                                v-on="inputEvents" />
                                                                                        </VControl>
                                                                                    </VField>
                                                                                </template>
                                                                            </VDatePicker>
                                                                        </VField>
                                                                    </div>
                                                                    <div class="column is-3" style="text-align: center;">
                                                                    </div>
                                                                </div>
                                                                <div class="columns is-multiline"
                                                                    style="text-align: center;">
                                                                    <div class="column is-12"
                                                                        style="text-align: center;">
                                                                        <img v-if="input.ttdDokterOperator"
                                                                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.ttdDokterOperator.label ? input.ttdDokterOperator.label : input.ttdDokterOperator) + ', ' + (input.ttdDokterOperator.value ? input.ttdDokterOperator.value : input.ttdDokterOperator) + ', ' + (input.tglPembuatanOperator ? input.tglPembuatanOperator : input.tglPembuatanOperator)">
                                                                        <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
                                                                    </div>
                                                                </div>
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-3" style="text-align: center;">
                                                                    </div>
                                                                    <div class="column is-6" style="text-align: center;">
                                                                        <VField>
                                                                            <VControl class="prime-auto">
                                                                                <AutoComplete v-model="input.ttdDokterOperator"
                                                                                    :suggestions="d_Dokter"
                                                                                    @complete="fetchDokter($event)"
                                                                                    :optionLabel="'label'"
                                                                                    :dropdown="true" :minLength="3"
                                                                                    :appendTo="'body'"
                                                                                    :loadingIcon="'pi pi-spinner'"
                                                                                    :field="'label'"
                                                                                    placeholder="Tanda Tangan Dokter Operator..."
                                                                                    class="mt-2"
                                                                                    @item-select="setTandaTangan($event, 'signature_1')" />
                                                                            </VControl>
                                                                        </VField>
                                                                    </div>
                                                                    <div class="column is-3" style="text-align: center;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 50%;">
                                                            <div clas="mt-5" style="text-align: center;">
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-3" style="text-align: center;">
                                                                    </div>
                                                                    <div class="column is-6" style="text-align: center;">
                                                                        <h1 style="font-weight: bold;"> 
                                                                            Tanda Tangan Dokter Anastesi
                                                                        </h1>
                                                                        <VField class="mt-3">
                                                                            <VDatePicker v-model="input.tglPembuatanAnastesi"
                                                                                mode="dateTime" style="width: 100%"
                                                                                trim-weeks :max-date="new Date()">
                                                                                <template
                                                                                    #default="{ inputValue, inputEvents }">
                                                                                    <VField>
                                                                                        <VControl
                                                                                            icon="feather:calendar"
                                                                                            fullwidth>
                                                                                            <VInput :value="inputValue"
                                                                                                placeholder="Tanggal"
                                                                                                v-on="inputEvents" />
                                                                                        </VControl>
                                                                                    </VField>
                                                                                </template>
                                                                            </VDatePicker>
                                                                        </VField>
                                                                    </div>
                                                                    <div class="column is-3" style="text-align: center;">
                                                                    </div>
                                                                </div>
                                                                <div class="columns is-multiline"
                                                                    style="text-align: center;">
                                                                    <div class="column is-12"
                                                                        style="text-align: center;">
                                                                        <img v-if="input.ttdDokterAnastesi"
                                                                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.ttdDokterAnastesi.label ? input.ttdDokterAnastesi.label : input.ttdDokterAnastesi) + ', ' + (input.ttdDokterAnastesi.value ? input.ttdDokterAnastesi.value : input.ttdDokterAnastesi) + ', ' + (input.tglPembuatanAnastesi ? input.tglPembuatanAnastesi : input.tglPembuatanAnastesi)">
                                                                        <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
                                                                    </div>
                                                                </div>
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-3" style="text-align: center;">
                                                                    </div>
                                                                    <div class="column is-6" style="text-align: center;">
                                                                        <VField>
                                                                            <VControl class="prime-auto">
                                                                                <AutoComplete v-model="input.ttdDokterAnastesi"
                                                                                    :suggestions="d_Dokter"
                                                                                    @complete="fetchDokter($event)"
                                                                                    :optionLabel="'label'"
                                                                                    :dropdown="true" :minLength="3"
                                                                                    :appendTo="'body'"
                                                                                    :loadingIcon="'pi pi-spinner'"
                                                                                    :field="'label'"
                                                                                    placeholder="Tanda Tangan Dokter Anastesi..."
                                                                                    class="mt-2"
                                                                                    @item-select="setTandaTangan($event, 'signature_1')" />
                                                                            </VControl>
                                                                        </VField>
                                                                    </div>
                                                                    <div class="column is-3" style="text-align: center;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table class="triase">
                                                <thead class="bg-brown">
                                                    <tr>
                                                        <th colspan="3"
                                                            style="text-align: center;font-weight: bold;font-size: 15px;">
                                                            Berikan tanda pada gambar sesuai dengan penandaan lokasi operasi pada tubuh pasien
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 70%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12" style="text-align: center">
                                                                    <span>Berikan penandaan pada lokasi tubuh pasien dengan tanda</span><br>
                                                                    <span>garis (-), tanda lingkar (o), atau panah (->), JANGAN</span><br>
                                                                    <span>Menggunakan silang (x)</span><br>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 30%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <span>Deskripsi singkat apabila tidak dapat dilakukan</span><br>
                                                                    <span>penandaan pada tubuh pasien</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 70%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-4" style="text-align: center">
                                                                    <span>Depan</span>
                                                                </div>
                                                                <div class="column is-4" style="text-align: center">
                                                                    <span>Belakang</span>
                                                                </div>
                                                                <div class="column is-2">
                                                                    <span>Sisi Kanan</span>
                                                                </div>
                                                                <div class="column is-2">
                                                                    <span>Sisi Kiri</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 30%;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 70%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <div :style="'background-image:url(' + MARKINGSITE + ')'"
                                                                        style="text-align: center; z-index: 9999;background-repeat: no-repeat;background-position: center;width: 800px;height: 300px;">
                                                                        <canvas id="markingsite" height="300" width="800"></canvas>
                                                                    </div><br>
                                                                    <VButton type="button" rounded outlined color="danger" raised icon="feather:trash"
                                                                        @click="clearCanvas('markingsite')"> Clear
                                                                    </VButton>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 30%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VTextarea rows="15" placeholder="...."
                                                                                v-model="input.penandaanGambar">
                                                                            </VTextarea>
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table class="triase">
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 50%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-4">
                                                                    <span>Posisi pasien dalam operasi :</span>
                                                                </div>
                                                                <div class="column is-8">
                                                                    <VField>
                                                                        <VControl icon="feather:data" fullwidth>
                                                                            <VInput
                                                                                v-model="input.posisiPasienDalamOperasi"
                                                                                placeholder="....." />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 50%;">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </th>
                                    </thead>
                                </table>
                                <table border="1" class="triase">
                                    <thead>
                                        <th class="bg-brown"
                                            style="width: 3%;font-weight: bold;vertical-align: middle !important;font-size: 25px;">
                                            <span>I</span><br>
                                            <span>R</span><br>
                                            <span>N</span><br>
                                            <span>A</span><br><br>
                                            <span>&</span><br><br>
                                            <span>I</span><br>
                                            <span>B</span><br>
                                            <span>S</span><br>
                                        </th>
                                        <th style="width: 97%;">
                                            <table class="triase">
                                                <thead class="bg-brown">
                                                    <tr>
                                                        <th colspan="2" style="text-align: center;font-weight: bold;font-size: 15px;">
                                                            <span>PERSIAPAN DAN EDUKASI PASIEN</span><br>
                                                            <span>Preoperasi oleh perawat asal pasien dan timbang terima dengan perawat kamar operasi</span>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 65%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.persiapanGelang"
                                                                                class="p-0" label="Gelang identitas"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.persiapanPewarna"
                                                                                class="p-0" label="Pewarna kuku"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.persiapanLaverment"
                                                                                class="p-0" label="Laverment"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.persiapanCBedah"
                                                                                class="p-0" label="C Bedah"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.persiapanPeroanPipi"
                                                                                class="p-0" label="Perona Pipi"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.persiapanBidai"
                                                                                class="p-0" label="Bidai"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.persiapanCAnastesi"
                                                                                class="p-0" label="C Anastesi"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.persiapanEyeShadow"
                                                                                class="p-0" label="Eye Shadow"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.persiapanPuasa"
                                                                                class="p-0" label="Puasa"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.persiapanGigiPalsu"
                                                                                class="p-0" label="Gigi Palsu"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.persiapanMadiKeramas"
                                                                                class="p-0" label="Mandi Keramas"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <h1>Jam</h1>
                                                                    <VDatePicker v-model="input.persiapanJam" color="green" mode="time" is24hr>
                                                                        <template #default="{ inputValue, inputEvents }">
                                                                            <VField>
                                                                                <VControl icon="feather:clock">
                                                                                    <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                                                                                </VControl>
                                                                            </VField>
                                                                        </template>
                                                                    </VDatePicker>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.persiapanSoftlens"
                                                                                class="p-0" label="Softlens"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.persiapanPersiapanKulit"
                                                                                class="p-0" label="Persiapan Kulit"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.persiapanInfus"
                                                                                class="p-0" label="Infus"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.persiapanLipstik"
                                                                                class="p-0" label="Lipstik"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.persiapanOral"
                                                                                class="p-0" label="Oral hygiene"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.persiapanDC"
                                                                                class="p-0" label="DC"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.persiapanAksesoris"
                                                                                class="p-0" label="Aksesoris"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.persiapanFikasi"
                                                                                class="p-0" label="Fikasi Leher"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.persiapanNGT"
                                                                                class="p-0" label="NGT"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.persiapanLatihanNafas"
                                                                                class="p-0" label="Latihan Nafas Dalam"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.persiapanLatihanAktifitas"
                                                                                class="p-0" label="Latihan Aktifitas"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.persiapanWSD"
                                                                                class="p-0" label="WSD"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.persiapanLatihanBatuk"
                                                                                class="p-0" label="Latihan Batuk Afektif"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.persiapanLatihanRelaxasi"
                                                                                class="p-0" label="Latihan Relaxasi"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.persiapanDrainage"
                                                                                class="p-0" label="Drainage"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.persiapanCatatanALaergi"
                                                                                class="p-0" label="Catatan Alergi"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 35%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12" style="text-align: center">
                                                                    <span>Penyakit Kronis :</span>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline mt-3">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.kronisDM"
                                                                                class="p-0" label="DM"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.kronisParu"
                                                                                class="p-0" label="TB Paru"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.kronisHipertensi"
                                                                                class="p-0" label="Hipertensi"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.kronisHipertensiBCA"
                                                                                class="p-0" label="Hipertensi B-C-A"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.kronisHivAids"
                                                                                class="p-0" label="HIV / AIDS"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.kronisPremedikasi"
                                                                                class="p-0" label="Premedikasi"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline" v-if="input.kronisPremedikasi">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl>
                                                                            <VInput
                                                                                v-model="input.kronisPremedikasiKet"
                                                                                placeholder="....." />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.kronisAntibiotik"
                                                                                class="p-0" label="Antibiotik Profilaksis"
                                                                                color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline" v-if="input.kronisAntibiotik">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl>
                                                                            <VInput
                                                                                v-model="input.kronisAntibiotikKet"
                                                                                placeholder="....." />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table class="triase">
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 34%;text-align: center;">
                                                            <div clas="mt-5" style="text-align: center;">
                                                                <div class="columns is-multiline"
                                                                    style="text-align: center;">
                                                                    <div class="column is-12"
                                                                        style="text-align: center;">
                                                                        <h1 style="font-weight: bold;"> 
                                                                            Tanda Tangan Perawat Ruangan
                                                                        </h1>
                                                                        <VField class="mt-3">
                                                                            <VDatePicker v-model="input.tglPembuatanPerawat"
                                                                                mode="dateTime" style="width: 100%"
                                                                                trim-weeks :max-date="new Date()">
                                                                                <template
                                                                                    #default="{ inputValue, inputEvents }">
                                                                                    <VField>
                                                                                        <VControl
                                                                                            icon="feather:calendar"
                                                                                            fullwidth>
                                                                                            <VInput :value="inputValue"
                                                                                                placeholder="Tanggal"
                                                                                                v-on="inputEvents" />
                                                                                        </VControl>
                                                                                    </VField>
                                                                                </template>
                                                                            </VDatePicker>
                                                                        </VField>
                                                                    </div>
                                                                </div>
                                                                <div class="columns is-multiline"
                                                                    style="text-align: center;">
                                                                    <div class="column is-12"
                                                                        style="text-align: center;">
                                                                        <img v-if="input.perawatRuangan"
                                                                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.perawatRuangan.label ? input.perawatRuangan.label : input.perawatRuangan) + ' ,' + (input.perawatRuangan.value ? input.perawatRuangan.value : input.perawatRuangan) + ' ,' + (input.tglPembuatanPerawat ? input.tglPembuatanPerawat : input.tglPembuatanPerawat)">
                                                                        <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
                                                                    </div>
                                                                </div>
                                                                <div class="columns is-multiline"
                                                                    style="text-align: center;">
                                                                    <div class="column is-12"
                                                                        style="text-align: center;">
                                                                        <VField>
                                                                            <VControl class="prime-auto">
                                                                                <AutoComplete v-model="input.perawatRuangan"
                                                                                    :suggestions="d_Perawat"
                                                                                    @complete="fetchPerawat($event)"
                                                                                    :optionLabel="'label'"
                                                                                    :dropdown="true" :minLength="3"
                                                                                    :appendTo="'body'"
                                                                                    :loadingIcon="'pi pi-spinner'"
                                                                                    :field="'label'"
                                                                                    placeholder="Perawat Ruangan..."
                                                                                    class="mt-2" />
                                                                            </VControl>
                                                                        </VField>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 33%;text-align: center;">
                                                            <div clas="mt-5" style="text-align: center;">
                                                                <div class="columns is-multiline"
                                                                    style="text-align: center;">
                                                                    <div class="column is-12"
                                                                        style="text-align: center;">
                                                                        <h1 style="font-weight: bold;"> Tanda Tangan Pasien/Keluarga
                                                                        </h1>
                                                                    </div>
                                                                </div>
                                                                <div class="columns is-multiline"
                                                                    style="text-align: center;">
                                                                    <div class="column is-12"
                                                                        style="text-align: center;">
                                                                        <TandaTangan :elemenID="'signaturePasienYangMenyatakan'" :width="'180'" :height="'180'"></TandaTangan>
                                                                    </div>
                                                                </div>
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-12" style="text-align: center;">
                                                                        <VField class="pt-2">
                                                                            <VControl class="prime-auto">
                                                                            <VInput type="text" v-model="input.namapasienKeluarga" />
                                                                            </VControl>
                                                                        </VField>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 33%;text-align: center;">
                                                            <div clas="mt-5" style="text-align: center;">
                                                                <div class="columns is-multiline"
                                                                    style="text-align: center;">
                                                                    <div class="column is-12"
                                                                        style="text-align: center;">
                                                                        <h1 style="font-weight: bold;"> 
                                                                            Tanda Tangan Perawat IBS
                                                                        </h1>
                                                                        <VField class="mt-3">
                                                                            <VDatePicker v-model="input.tglPembuatanPerawatIBS"
                                                                                mode="dateTime" style="width: 100%"
                                                                                trim-weeks :max-date="new Date()">
                                                                                <template
                                                                                    #default="{ inputValue, inputEvents }">
                                                                                    <VField>
                                                                                        <VControl
                                                                                            icon="feather:calendar"
                                                                                            fullwidth>
                                                                                            <VInput :value="inputValue"
                                                                                                placeholder="Tanggal"
                                                                                                v-on="inputEvents" />
                                                                                        </VControl>
                                                                                    </VField>
                                                                                </template>
                                                                            </VDatePicker>
                                                                        </VField>
                                                                    </div>
                                                                </div>
                                                                <div class="columns is-multiline"
                                                                    style="text-align: center;">
                                                                    <div class="column is-12"
                                                                        style="text-align: center;">
                                                                        <img v-if="input.perawatIBS"
                                                                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.perawatIBS.label ? input.perawatIBS.label : input.perawatIBS) + ' ,' + (input.perawatIBS.value ? input.perawatIBS.value : input.perawatIBS) + ' ,' + (input.tglPembuatanPerawatIBS ? input.tglPembuatanPerawatIBS : input.tglPembuatanPerawatIBS)">
                                                                        <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
                                                                    </div>
                                                                </div>
                                                                <div class="columns is-multiline"
                                                                    style="text-align: center;">
                                                                    <div class="column is-12"
                                                                        style="text-align: center;">
                                                                        <VField>
                                                                            <VControl class="prime-auto">
                                                                                <AutoComplete v-model="input.perawatIBS"
                                                                                    :suggestions="d_Perawat"
                                                                                    @complete="fetchPerawat($event)"
                                                                                    :optionLabel="'label'"
                                                                                    :dropdown="true" :minLength="3"
                                                                                    :appendTo="'body'"
                                                                                    :loadingIcon="'pi pi-spinner'"
                                                                                    :field="'label'"
                                                                                    placeholder="Perawat IBS..."
                                                                                    class="mt-2" />
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
                    <div class="mt-5">
                        <Fieldset :toggleable="true" legend="Surgical Safety Checklist - Sign in, time out dan Sign Out">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                <table border="1" class="triase">
                                    <thead>
                                        <tr>
                                            <th>Sign in</th>
                                            <th>Time Out</th>
                                            <th>Sign Out</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="font-weight: 500;">
                                                <h1 style="text-align: center;font-weight: bold">Dialkukan Sebelum induksi anastesi, dihadiri minimal oleh perawat</h1>
                                            </td>
                                            <td>
                                                <h1 style="text-align: center;font-weight: bold">Dialkukan Sebelum insisi, dihadiri minimal oleh perawat, ahli anastesi, operator</h1>
                                            </td>
                                            <td>
                                                <h1 style="text-align: center;font-weight: bold">Dialkukan Sebelum pasien meninggalkan kamar Bedah, dihadiri oleh perawat, ahli anastesi, operator</h1>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="25%" style="font-weight: 500;">
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <span>Apakah pasien telah memberikan konfirmasi kebenaran identitasnya, lokasi operasinya, prosedurnya dan telah memberikan persetujuan dalam lembar informed concent ?</span>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.signKonfirmasiKebenaranYa" class="p-0" label="Ya"
                                                                    color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.timeOutSeluruhAnggota" class="p-0" label="Seluruh anggota tim telah menyebutkan nama dan peran masing-masing"
                                                                    color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.timeOutPastikanNama" class="p-0" label="Pastikan nama pasien, nama prosedur, dan dimana insisi akan dilakukan"
                                                                    color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <span>Tim Keperawatan secara lisan mengkonfirmasi di hadapan tim :</span>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.signOutNamaProsedur" class="p-0" label="Nama Prosedur"
                                                                    color="primary" /> 
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.signOutKelngkapanHitungan" class="p-0" label="Kelengkapan hitungan instrumen, spons dan jarum sudah sesuai"
                                                                    color="primary" /> 
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="25%" style="font-weight: 500;">
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <span>Apakah lokasi operasi sudah diberi tanda / marking ?</span>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.signApakahLokasiYa" class="p-0" label="Ya"
                                                                    color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.signApakahLokasiTidak" class="p-0" label="Tidak"
                                                                    color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <span>Antibiotik profilaksis telah diberikan dalam 60 menit ?</span>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.timeoutAntibiotikYa" class="p-0" label="Ya, Oleh"
                                                                    color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-12" v-if="input.timeoutAntibiotikYa">
                                                        <VField>
                                                            <VControl icon="feather:data" fullwidth>
                                                                <VInput
                                                                    v-model="input.timeoutAntibiotikYaKet" placeholder="....." />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.timeoutAntibiotikTidak" class="p-0" label="Tidak"
                                                                    color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <table border="1" class="triase">
                                                    <thead>
                                                        <th>Nama Barang</th>
                                                        <th>Jumlah Sebelum</th>
                                                        <th>Jumlah Intra</th>
                                                        <th>Jumlah Tambahan</th>
                                                        <th>Jumlah Pasca</th>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Instrumen</td>
                                                            <td>
                                                                <VField>
                                                                    <VControl icon="feather:data" fullwidth>
                                                                        <VInput
                                                                            v-model="input.instrumenJumlahSebelum" placeholder="....." />
                                                                    </VControl>
                                                                </VField>
                                                            </td>
                                                            <td>
                                                                <VField>
                                                                    <VControl icon="feather:data" fullwidth>
                                                                        <VInput
                                                                            v-model="input.instrumenJumlahIntra" placeholder="....." />
                                                                    </VControl>
                                                                </VField>
                                                            </td>
                                                            <td>
                                                                <VField>
                                                                    <VControl icon="feather:data" fullwidth>
                                                                        <VInput
                                                                            v-model="input.instrumenJumlahTambahan" placeholder="....." />
                                                                    </VControl>
                                                                </VField>
                                                            </td>
                                                            <td>
                                                                <VField>
                                                                    <VControl icon="feather:data" fullwidth>
                                                                        <VInput
                                                                            v-model="input.instrumenJumlahPasca" placeholder="....." />
                                                                    </VControl>
                                                                </VField>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Jarun</td>
                                                            <td>
                                                                <VField>
                                                                    <VControl icon="feather:data" fullwidth>
                                                                        <VInput
                                                                            v-model="input.jarumJumlahSebelum" placeholder="....." />
                                                                    </VControl>
                                                                </VField>
                                                            </td>
                                                            <td>
                                                                <VField>
                                                                    <VControl icon="feather:data" fullwidth>
                                                                        <VInput
                                                                            v-model="input.jarumJumlahIntra" placeholder="....." />
                                                                    </VControl>
                                                                </VField>
                                                            </td>
                                                            <td>
                                                                <VField>
                                                                    <VControl icon="feather:data" fullwidth>
                                                                        <VInput
                                                                            v-model="input.jarumJumlahTambahan" placeholder="....." />
                                                                    </VControl>
                                                                </VField>
                                                            </td>
                                                            <td>
                                                                <VField>
                                                                    <VControl icon="feather:data" fullwidth>
                                                                        <VInput
                                                                            v-model="input.jarumJumlahPasca" placeholder="....." />
                                                                    </VControl>
                                                                </VField>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Kassa</td>
                                                            <td>
                                                                <VField>
                                                                    <VControl icon="feather:data" fullwidth>
                                                                        <VInput
                                                                            v-model="input.kassaJumlahSebelum" placeholder="....." />
                                                                    </VControl>
                                                                </VField>
                                                            </td>
                                                            <td>
                                                                <VField>
                                                                    <VControl icon="feather:data" fullwidth>
                                                                        <VInput
                                                                            v-model="input.kassaJumlahIntra" placeholder="....." />
                                                                    </VControl>
                                                                </VField>
                                                            </td>
                                                            <td>
                                                                <VField>
                                                                    <VControl icon="feather:data" fullwidth>
                                                                        <VInput
                                                                            v-model="input.kassaJumlahTambahan" placeholder="....." />
                                                                    </VControl>
                                                                </VField>
                                                            </td>
                                                            <td>
                                                                <VField>
                                                                    <VControl icon="feather:data" fullwidth>
                                                                        <VInput
                                                                            v-model="input.kassaJumlahPasca" placeholder="....." />
                                                                    </VControl>
                                                                </VField>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total</td>
                                                            <td>
                                                                <VField>
                                                                    <VControl icon="feather:data" fullwidth>
                                                                        <VInput
                                                                            v-model="input.totalJumlahSebelum" placeholder="....." />
                                                                    </VControl>
                                                                </VField>
                                                            </td>
                                                            <td>
                                                                <VField>
                                                                    <VControl icon="feather:data" fullwidth>
                                                                        <VInput
                                                                            v-model="input.totalJumlahIntra" placeholder="....." />
                                                                    </VControl>
                                                                </VField>
                                                            </td>
                                                            <td>
                                                                <VField>
                                                                    <VControl icon="feather:data" fullwidth>
                                                                        <VInput
                                                                            v-model="input.totalJumlahTambahan" placeholder="....." />
                                                                    </VControl>
                                                                </VField>
                                                            </td>
                                                            <td>
                                                                <VField>
                                                                    <VControl icon="feather:data" fullwidth>
                                                                        <VInput
                                                                            v-model="input.totalJumlahPasca" placeholder="....." />
                                                                    </VControl>
                                                                </VField>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="25%" style="font-weight: 500;">
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <span>Apakah Mesin dan Obat anastesi telah di cek lengkap ?</span>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.signMesinObat" class="p-0" label="Ya, Oleh"
                                                                    color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                            </td>
                                            <td rowspan="2">
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <span style="font-weight: bold">ANTISIPASI KEJADIAN KRITIS</span>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <span>Operator</span>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.timeOutOperatorHakKritis" class="p-0" label="Hak Kritis atau langkah tak terdugga apakah mungkin diambil ?"
                                                                    color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.timeOutOperatorBerapaEstimasi" class="p-0" label="Berapa estimasi lama operasi ?"
                                                                    color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.timeOutOperatorAntisapasiKehlangan" class="p-0" label="Antisipasi kehilangan darah yang dipersiapkan?"
                                                                    color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline mt-3">
                                                    <div class="column is-12">
                                                        <span>Tim Anastesi</span>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.timeOutTimAdakah" class="p-0" label="Adakah terdapat hal penting mengenai pasien perlu diperhatikan ?"
                                                                    color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline mt-3">
                                                    <div class="column is-12">
                                                        <span>Tim Keperawatan</span>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.timeOutTimKeperSudahkah" class="p-0" label="Sudahkah steritas dipastikan (termasuk hasil indikator) ?"
                                                                    color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.timeOutTimKeperMasalah" class="p-0" label="Adakah masalah atau perhatian khusus mengenai peralatan ?"
                                                                    color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline mt-3 ml-2">
                                                    <div class="column is-12">
                                                        <span>Hasil pemeriksaan imaging penting ditampilkan ?</span>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.timeOutTimKeperHasilYa" class="p-0" label="Ya ?"
                                                                    color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.timeOutTimKeperHasilTidak" class="p-0" label="Tidak dapat diterapkan ?"
                                                                    color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                            </td>
                                            <td rowspan="2">
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.signOutLabel" class="p-0" label="Label spesimen (minimal terdapat asal jaringan), nama psien, tanggal lahir dan nomer RM"
                                                                    color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.signOutPermasalahanPeralatan" class="p-0" label="Apakah terdapat permasalahn peralatan yang perlu disampaikan,"
                                                                    color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline" v-if="input.signOutPermasalahanPeralatan">
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VTextarea v-model="input.signOutPermasalahanPeralatanKet" rows="3"></VTextarea>
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.signOutkepadaOperator" class="p-0" label="Kepada Operator, Dokter Anastesi dan Tim Keperawatan, apakah terdapat pesan khusus untuk pemulihan pasien ?"
                                                                    color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: 500;">
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <span>Apakah Pasien Memiliki Riwayat alergi yang diketahui</span>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.signRiwayatAlergiYa" class="p-0" label="Ya ?"
                                                                    color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.signRiwayatAlergiTidak" class="p-0" label="Tidak ?"
                                                                    color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline mt-3">
                                                    <div class="column is-12">
                                                        <span>Resiko kesulitan pada jalan napas atau resiko aspirasi ?</span>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.signResikoKesulitanTidak" class="p-0" label="Tidak"
                                                                    color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.signResikoKesulitanYa" class="p-0" label="Ya, peralatan/bantuan sudah tersedia"
                                                                    color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline mt-3">
                                                    <div class="column is-12">
                                                        <span>Resiko kehilangan darah >500 ml (7 ml/kgBB pada anak)</span>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.signResikoKehilanganDarahTidak" class="p-0" label="Tidak"
                                                                    color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.signResikoKehilanganDarahYa" class="p-0" label="Ya, sudah ada akses intravena yang adekuat dan sudah ada rencana terapi cairan"
                                                                    color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                                        <td>
                                                            <div clas="mt-5" style="text-align: center;">
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-12" style="text-align: center;">
                                                                        <h1 style="font-weight: bold;"> 
                                                                            Dokter Anastesi
                                                                        </h1>
                                                                        <VField class="mt-3">
                                                                            <VDatePicker v-model="input.tglPembuatanDokterAnastesi"
                                                                                mode="dateTime" style="width: 100%"
                                                                                trim-weeks :max-date="new Date()">
                                                                                <template
                                                                                    #default="{ inputValue, inputEvents }">
                                                                                    <VField>
                                                                                        <VControl
                                                                                            icon="feather:calendar"
                                                                                            fullwidth>
                                                                                            <VInput :value="inputValue"
                                                                                                placeholder="Tanggal"
                                                                                                v-on="inputEvents" />
                                                                                        </VControl>
                                                                                    </VField>
                                                                                </template>
                                                                            </VDatePicker>
                                                                        </VField>
                                                                    </div>
                                                                </div>
                                                                <div class="columns is-multiline"
                                                                    style="text-align: center;">
                                                                    <div class="column is-12"
                                                                        style="text-align: center;">
                                                                        <img v-if="input.ttdSignInDokterAnastesi"
                                                                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.ttdSignInDokterAnastesi.label ? input.ttdSignInDokterAnastesi.label : input.ttdSignInDokterAnastesi) + ', ' + (input.ttdSignInDokterAnastesi.value ? input.ttdSignInDokterAnastesi.value : input.ttdSignInDokterAnastesi) + ', ' + (input.tglPembuatanDokterAnastesi ? input.tglPembuatanDokterAnastesi : input.tglPembuatanDokterAnastesi)">
                                                                        <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
                                                                    </div>
                                                                </div>
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-12" style="text-align: center;">
                                                                        <VField>
                                                                            <VControl class="prime-auto">
                                                                                <AutoComplete v-model="input.ttdSignInDokterAnastesi"
                                                                                    :suggestions="d_Dokter"
                                                                                    @complete="fetchDokter($event)"
                                                                                    :optionLabel="'label'"
                                                                                    :dropdown="true" :minLength="3"
                                                                                    :appendTo="'body'"
                                                                                    :loadingIcon="'pi pi-spinner'"
                                                                                    :field="'label'"
                                                                                    placeholder="Dokter Anastesi..."
                                                                                    class="mt-2"/>
                                                                            </VControl>
                                                                        </VField>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div clas="mt-5" style="text-align: center;">
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-12" style="text-align: center;">
                                                                        <h1 style="font-weight: bold;"> 
                                                                            Perawat Sekuler
                                                                        </h1>
                                                                        <VField class="mt-3">
                                                                            <VDatePicker v-model="input.tglPembuatanPerawatSekuler"
                                                                                mode="dateTime" style="width: 100%"
                                                                                trim-weeks :max-date="new Date()">
                                                                                <template
                                                                                    #default="{ inputValue, inputEvents }">
                                                                                    <VField>
                                                                                        <VControl
                                                                                            icon="feather:calendar"
                                                                                            fullwidth>
                                                                                            <VInput :value="inputValue"
                                                                                                placeholder="Tanggal"
                                                                                                v-on="inputEvents" />
                                                                                        </VControl>
                                                                                    </VField>
                                                                                </template>
                                                                            </VDatePicker>
                                                                        </VField>
                                                                    </div>
                                                                </div>
                                                                <div class="columns is-multiline"
                                                                    style="text-align: center;">
                                                                    <div class="column is-12"
                                                                        style="text-align: center;">
                                                                        <img v-if="input.perawatSekuler"
                                                                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.perawatSekuler.label ? input.perawatSekuler.label : input.perawatSekuler) + ', ' + (input.perawatSekuler.value ? input.perawatSekuler.value : input.perawatSekuler) + ', ' + (input.tglPembuatanPerawatSekuler ? input.tglPembuatanPerawatSekuler : input.tglPembuatanPerawatSekuler)">
                                                                        <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
                                                                    </div>
                                                                </div>
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-12" style="text-align: center;">
                                                                        <VField>
                                                                            <VControl class="prime-auto">
                                                                                <AutoComplete v-model="input.perawatSekuler"
                                                                                    :suggestions="d_Perawat"
                                                                                    @complete="fetchPerawat($event)"
                                                                                    :optionLabel="'label'"
                                                                                    :dropdown="true" :minLength="3"
                                                                                    :appendTo="'body'"
                                                                                    :loadingIcon="'pi pi-spinner'"
                                                                                    :field="'label'"
                                                                                    placeholder="Perawat Sekuler..."
                                                                                    class="mt-2" />
                                                                            </VControl>
                                                                        </VField>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div clas="mt-5" style="text-align: center;">
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-12" style="text-align: center;">
                                                                        <h1 style="font-weight: bold;"> 
                                                                            Dokter Operator
                                                                        </h1>
                                                                        <VField class="mt-3">
                                                                            <VDatePicker v-model="input.tglPembuatanDokterOperator"
                                                                                mode="dateTime" style="width: 100%"
                                                                                trim-weeks :max-date="new Date()">
                                                                                <template
                                                                                    #default="{ inputValue, inputEvents }">
                                                                                    <VField>
                                                                                        <VControl
                                                                                            icon="feather:calendar"
                                                                                            fullwidth>
                                                                                            <VInput :value="inputValue"
                                                                                                placeholder="Tanggal"
                                                                                                v-on="inputEvents" />
                                                                                        </VControl>
                                                                                    </VField>
                                                                                </template>
                                                                            </VDatePicker>
                                                                        </VField>
                                                                    </div>
                                                                </div>
                                                                <div class="columns is-multiline"
                                                                    style="text-align: center;">
                                                                    <div class="column is-12"
                                                                        style="text-align: center;">
                                                                        <img v-if="input.ttdSignInDokterOperator"
                                                                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.ttdSignInDokterOperator.label ? input.ttdSignInDokterOperator.label : input.ttdSignInDokterOperator) + ', ' + (input.ttdSignInDokterOperator.value ? input.ttdSignInDokterOperator.value : input.ttdSignInDokterOperator) + ', ' + (input.tglPembuatanDokterOperator ? input.tglPembuatanDokterOperator : input.tglPembuatanDokterOperator)">
                                                                        <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
                                                                    </div>
                                                                </div>
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-12" style="text-align: center;">
                                                                        <VField>
                                                                            <VControl class="prime-auto">
                                                                                <AutoComplete v-model="input.ttdSignInDokterOperator"
                                                                                    :suggestions="d_Dokter"
                                                                                    @complete="fetchDokter($event)"
                                                                                    :optionLabel="'label'"
                                                                                    :dropdown="true" :minLength="3"
                                                                                    :appendTo="'body'"
                                                                                    :loadingIcon="'pi pi-spinner'"
                                                                                    :field="'label'"
                                                                                    placeholder="Dokter Operator..."
                                                                                    class="mt-2"/>
                                                                            </VControl>
                                                                        </VField>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                        </tr>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </Fieldset>
                    </div>
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
    <Dialog v-model:visible="showData" maximizable modal header="Hasil Laboratorium" :style="{ width: '80rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <div class="columns is-multiline">
          <div class="column is-6">
            <VCard class="bt-color">
              <h3 style="font-weight:800">Pemeriksaan Penunjang</h3>
              <div class="column is-12">
                <VField>
                  <VControl>
                    <VTextarea v-model="item.sourcePemeriksaanPenunjang" rows="20">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
            </VCard>
          </div>
          <div class="column is-6">
            <VCard class="pembungkus">
              <DataTable dataKey="no" :value="sourceDataLab" v-model:selection="selectedRad" class="p-datatable-sm"
                :paginator="true" :rows="10" scrollable
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                <Column field="no" header="No" :sortable="true" style="min-width: 50px"></Column>
                <Column field="nama_pemeriksaan" header="Layanan" frozen :sortable="true" style="min-width: 200px">
                </Column>
                <Column field="hasil" header="Hasil" :sortable="true" style="min-width: 100px"></Column>
                <Column field="normal" header="Nilai Normal" :sortable="true" style="min-width: 100px"></Column>
                <!-- <Column field="jumlahobat" header="Jumlah Obat" :sortable="true" style="min-width: 200px"></Column> -->
                <Column field="tgl_hasil" header="tanggal" :sortable="true" style="min-width: 200px"></Column>
              </DataTable>

              <div class="column is-12">
                <VButton color="primary" raised @click="tambahHasilLab(selectedRad)" outlined :loading="isLoadTmb"><i
                    class="fas fa-plus mr-3 p-0" aria-hidden="true"></i>Tambah</VButton>
              </div>
            </VCard>
          </div>
        </div>
        <template #footer>
          <VButton color="primary" raised @click="simpanHasilPenunjang(item.sourcePemeriksaanPenunjang)"><i
              class="fas fa-save mr-3" aria-hidden="true"></i> Simpan</VButton>
        </template>
      </Dialog>
      <Dialog v-model:visible="showDataRad" maximizable modal header="Hasil Radiologi" :style="{ width: '80rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <div class="columns is-multiline">
          <div class="column is-6">
            <VCard class="bt-color">
              <h3 style="font-weight:800">Pemeriksaan Penunjang</h3>
              <div class="column is-12">
                <VField>
                  <VControl>
                    <VTextarea v-model="item.sourcePemeriksaanPenunjangRad" rows="20">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
            </VCard>
          </div>
          <div class="column is-6">
            <VCard class="pembungkus">
              <DataTable dataKey="no" :value="sourceDataRad" v-model:selection="selectedResep" class="p-datatable-sm"
                :paginator="true" :rows="10" scrollable
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                <Column field="no" header="No" :sortable="true" style="min-width: 50px"></Column>
                <Column field="hasilexpertise" header="Hasil Expertise" frozen :sortable="true" style="min-width: 300px">
                </Column>
              </DataTable>

              <div class="column is-12">
                <VButton color="primary" raised @click="tambahHasilRad(selectedResep)" outlined :loading="isLoadTmb"><i
                    class="fas fa-plus mr-3 p-0" aria-hidden="true"></i>Tambah</VButton>
              </div>
            </VCard>
          </div>
        </div>
        <template #footer>
          <VButton color="primary" raised @click="simpanHasilPenunjangRad(item.sourcePemeriksaanPenunjangRad)"><i
              class="fas fa-save mr-3" aria-hidden="true"></i> Simpan</VButton>
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
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/laporan-operasi'
import Dialog from 'primevue/dialog';
import OrderLab from './order-laboratorium.vue'
import sleep from '/@src/utils/sleep'
import $ from "jquery";

const selectedRad = ref();
const selectedResep = ref();
const d_Ruangan: any = ref([])
const d_Dokter: any = ref([])
const d_Perawat: any = ref([])
const isLab: any = ref(false)
const showData: any = ref(false)
const showDataRad: any = ref(false)
const isLoadTmb: any = ref(false)
const sourceDataLab = ref([])
const sourceDataRad = ref([])
const MARKINGSITE: any = ref('')
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let hasilPenunjangs: any = ref([])

let laporan = ref(EMR.laporan())

const show = () => {
  showData.value = true
}

const showRad = () => {
  showDataRad.value = true
}

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
    diagnosaICD10: [{ no: 1 }],
    // estimasiYangDibutuhkan: new Date(),
    persiapanJam: new Date()
})
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
    MARKINGSITE.value = '/images/simrs/kanan-kiri.png'
}

const fetchPerawat = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Perawat.value = response
  })
}

const SourceHasilLab = async () => {
  await useApi().get(`/laboratorium/source-hasil-lab?noregistrasi=${props.registrasi.noregistrasi}`).then((response) => {
    response.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    sourceDataLab.value = response
  })
}

const getHasilRad = async () => {
  await useApi().get(`/emr/get-hasil-radiologi-emr?noregistrasi=${props.registrasi.noregistrasi}&norec_pd=${props.registrasi.norec_pd}`).then((response) => {
    response.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    sourceDataRad.value = response
  })
}

const tambahHasilLab = async (e: any) => {
  let noorder = []
  let nourut = []
  let sementara = []
  item.sourcePemeriksaanPenunjang = ''
  isLoadTmb.value = true
  e.forEach((element: any) => {
    nourut = [...new Set([...nourut, element.no_urut])]
    noorder = [...new Set([...noorder, element.no_order])]
  });
  await useApi().get(`/laboratorium/hasil-lab?noregistrasi=${props.registrasi.noregistrasi}&nourut=${nourut}&noorder=${noorder}`).then((response) => {
    response.forEach(element => {
      sementara = [...new Set([...sementara, element.hasillab])]
    });
    sementara.forEach(element => {
      item.sourcePemeriksaanPenunjang += element
    });
    isLoadTmb.value = false
  })

  hasilPenunjangs.value.forEach(element => {
    item.sourcePemeriksaanPenunjang += element
  });
}

const tambahHasilRad = async (e: any) => {
  let tanggalreport = []
  let sementara = []
  item.sourcePemeriksaanPenunjangRad = ''
  isLoadTmb.value = true
  e.forEach((element: any) => {
    tanggalreport = [...new Set([...tanggalreport, element.tanggalreport])]
  });
  await useApi().get(`/emr/get-hasil-radiologi-emr?noregistrasi=${props.registrasi.noregistrasi}&norec_pd=${props.registrasi.norec_pd}&tanggalreport=${tanggalreport}`).then((response) => {
    response.forEach(element => {
      sementara = [...new Set([...sementara, element.hasilexpertise])]
    });
    sementara.forEach(element => {
      item.sourcePemeriksaanPenunjangRad += element
    });
    isLoadTmb.value = false
  })

  hasilPenunjangs.value.forEach(element => {
    item.sourcePemeriksaanPenunjangRad += element
  });
}

const simpanHasilPenunjang = (e: any) => {
  if (!e) {
    H.alert('error', 'Hasil Penunjang Kosong')
    return
  }

  if (!input.value.pemeriksaPenunjang) {
    input.value.pemeriksaPenunjang = ''
  }
  if (input.value.pemeriksaPenunjang !== '') {
    input.value.pemeriksaPenunjang += '\n'
  }

  input.value.pemeriksaPenunjang += e
  showData.value = false
}


const simpanHasilPenunjangRad = (e: any) => {
  if (!e) {
    H.alert('error', 'Hasil Penunjang Kosong')
    return
  }

  if (!input.value.pemeriksaPenunjang) {
    input.value.pemeriksaPenunjang = ''
  }
  if (input.value.pemeriksaPenunjang !== '') {
    input.value.pemeriksaPenunjang += '\n'
  }

  input.value.pemeriksaPenunjang += e
  showDataRad.value = false
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
                    H.tandaTangan().set("markingsite", response[0].tandaBodyDraw, 800, 300)
                }
                if (response[0].ttdPasienyangMenyatakan) {
                    H.tandaTangan().set("signaturePasienYangMenyatakan", response[0].ttdPasienyangMenyatakan)
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
    object.tandaBodyDraw = H.tandaTangan().get("markingsite")
    object.ttdPasienyangMenyatakan = H.tandaTangan().get("signaturePasienYangMenyatakan")
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

const kembaliKeun = () => {
    window.history.back()
}

const tutupLabSetAutofill = async () => {
    isLab.value = false;
    setAutoFill()
}

const setAutoFill = async () => {
    input.value.tglPembuatan = new Date()
    input.value.dpjpPasien = props.registrasi.dokter
    input.value.namaPasien = props.pasien.namapasien
    input.value.namapasienKeluarga = props.pasien.namapasien
    input.value.tglLahir = props.pasien.tgllahir
    input.value.dokterAnastesi = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
    input.value.dokterOperator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
    input.value.ttdDokterAnastesi = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
    input.value.ttdSignInDokterAnastesi = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }

    useApi().get(
        "emr/auto-fill-cppt?nocmfk=" + ID_PASIEN + "&norec_pd=" + props.registrasi.norec_pd +
        "&collection=CPPTDetail" + "&field=tgl,flag,S,P,O,uuid,nocmfk,diagnosaDokter" + "&flag=dokter"
    ).then((response) => {
        if (response) {
            console.log("cpptdetail", response)
            input.value.dataSubyektif = true
            input.value.dataSubyektifKet = response.S
            input.value.dataObyektif = true
            input.value.dataObyektifKet = response.O
            input.value.rencanaOperasi = response.P
            // input.value.S = response.S
            // input.value.P = response.P
            // input.value.O = response.O
            if (response && response.diagnosaDokter) {
                input.value.diagnosaICD10 = response.diagnosaDokter.map((element, index) => {
                return {
                    no: index + 1,  // Increment the number to match the "no" field
                    value: element.diagnosaa.value || '',
                    label: element.diagnosaa.label || '',
                    //ICD10Diagnosa: null,  // Initialize ICD10Diagnosa for binding to AutoComplete
                };
                });
            }
        }
    })

    useApi().get("diagnosa/riwayat-diagnosa-x-cppt?noregistrasi=" + props.registrasi.noregistrasi
    ).then((response) => {
        if (response != null) {
            let analysis = '';
            let count = 1;
            response.data.forEach(element => {
                analysis += `${count}. ${element.kddiagnosa}, nama diagnosa = ${element.namadiagnosa}, jenis diagnosa = ${element.jenisdiagnosa}, keterangan = ${element.keterangan} \n`;
                count++;
            });
            analysis = analysis.slice(0, -2);
            input.value.A = ref(analysis);
        }
    })
    await useApi().get(
        `/laboratorium/riwayat-order?nocmfk=${ID_PASIEN}&norec_pd=${item.NOREC_PD}`).then((response: any) => {
            if (response != null) {
                let lab = '';
                response.forEach(element => {
                    element.details.forEach((item, index) => {
                        lab += item.namaproduk
                        if (index != element.details.length - 1) {
                            lab += ', '
                        }
                    })
                })
                input.value.pemeriksaanLaboratorium = ref(lab)
            }
        })
}

async function performAction() {
  await sleep(1000);
  markignSite();
}
performAction();
setView()
onMounted(async () => {
  isLoading.value = true
  await SourceHasilLab()
  await getHasilRad()
  await setAutoFill()
  isLoading.value = false
})
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
