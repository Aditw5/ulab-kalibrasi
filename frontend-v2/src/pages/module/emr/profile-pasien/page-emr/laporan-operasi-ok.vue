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

    <div class="column is-12">
        <VCard>
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-6">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Nama Operator </h1>
                        <VField>
                            <VControl>
                                <AutoComplete v-model="input.namaOperator" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari nama Pegawai" />
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-4">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Nama Asisten Operator</h1>
                        <VField>
                            <VControl>
                                <AutoComplete v-model="input.namaAsistenOperator1" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari nama Pegawai" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Nama Asisten Operator</h1>
                        <VField>
                            <VControl>
                                <AutoComplete v-model="input.namaAsistenOperator2" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari nama Pegawai" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Nama Asisten Operator</h1>
                        <VField>
                            <VControl>
                                <AutoComplete v-model="input.namaAsistenOperator3" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari nama Pegawai" />
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-6">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Nama Ahli Anastesi</h1>
                        <VField>
                            <VControl>
                                <AutoComplete v-model="input.namaAhliAnastesi" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari nama Pegawai" />
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-4">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Nama Asisten Ahli Anastesi</h1>
                        <VField>
                            <VControl>
                                <AutoComplete v-model="input.namaAsistenAnastesi1" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari nama Pegawai" />
                            </VControl>
                        </VField>
                    </div>
                    <!-- <div class="column is-4">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Nama Asisiten Ahli Anastesi</h1>
                        <VField>
                            <VControl>
                                <AutoComplete v-model="input.namaAsistenAnastesi2" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari nama Pegawai" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Nama Asisiten Ahli Anastesi</h1>
                        <VField>
                            <VControl>
                                <AutoComplete v-model="input.namaAsistenAnastesi3" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari nama Pegawai" />
                            </VControl>
                        </VField>
                    </div> -->
                </div>
                <div class="column is-12">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Jenis Anastesi</h1>
                    <div class="columns is-multiline">
                        <div class="column is-2 mb-3">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.JenisAnastesiUmum" label="Umum" class="p-0" color="primary"
                                        square />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2 mb-3">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.JenisAnastesiSpiral" label="Spinal" class="p-0"
                                        color="primary" square />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2 mb-3">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.JenisAnastesiEpidurai" label="Epidurai" class="p-0"
                                        color="primary" square />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2 mb-3">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.JenisAnastesiBSP" label="BSP*" class="p-0" color="primary"
                                        square />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2 mb-3">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.JenisAnastesiCSE" label="CSE**" class="p-0" color="primary"
                                        square />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2 mb-3">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.JenisAnastesiLokal" label="Lokal" class="p-0" color="primary"
                                        square />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Jenis Operasi</h1>
                    <div class="columns is-multiline">
                        <div class="column is-2 mb-3">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.JenisOperasiKecil" label="Kecil" class="p-0" color="primary"
                                        square />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2 mb-3">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.JenisOperasiSedang" label="Sedang I/II/III" class="p-0"
                                        color="primary" square />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2 mb-3">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.JenisOperasiBesar" label="Besar I/II/III" class="p-0"
                                        color="primary" square />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2 mb-3">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.JenisOperasiKhusus" label="Khusus" class="p-0" color="primary"
                                        square />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-6">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Diagnosa Pra-Bedah </h1>
                        <VField>
                            <VControl>
                              <VInput type="text" class="input" placeholder="Diagnosa pra bedah"
                                        v-model="input.diagnosaPraBedah" />
                                <!-- <AutoComplete v-model="input.diagnosaPraBedah" :suggestions="d_Diagnosa"
                                    @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari Diagnosa" /> -->
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Diagnosa Pasca-Bedah </h1>
                        <VField>
                            <VControl>
                              <VInput type="text" class="input" placeholder="Diagnosa pasca bedah"
                                        v-model="input.diagnosaPascaBedah" />
                                <!-- <AutoComplete v-model="input.diagnosaPascaBedah" :suggestions="d_Diagnosa"
                                    @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari Diagnosa" /> -->
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Indikasi Operasi </h1>
                        <VField>
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Indikasi Operasi"
                                        v-model="input.indikasiOperasi" />
                                </VControl>
                            </VField>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Nama Operasi </h1>
                        <VField>
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Nama Operasi"
                                        v-model="input.namaOperasi" />
                                </VControl>
                            </VField>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Jaringan yang dieksisi </h1>
                        <VField>
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Jaringan yang dieksisi"
                                        v-model="input.jaringanYangDieksisi" />
                                </VControl>
                            </VField>
                        </VField>
                    </div>
                </div>

                <div class="column is-12">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Pemeriksaan PA</h1>
                    <div class="columns is-multiline">
                        <div class="column is-2 mb-3">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.pemeriksaanPAYa" label="Ya" class="p-0" color="primary"
                                        square />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2 mb-3">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.pemeriksaanPATidak" label="Tidak" class="p-0" color="primary"
                                        square />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-2 mt-3">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Implan</h1>
                        </div>
                        <div class="column is-2 mb-3">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> No : </h1>
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="No" v-model="input.implanNo" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2 mb-3">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Nama :</h1>
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Nama" v-model="input.implanNama" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2 mb-3">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Jenis :</h1>
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Jenis" v-model="input.implanJenis" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-2">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Tanggal Operasi </h1>
                            <VField>
                                <VDatePicker v-model="input.tglOperasi" mode="date" style="width: 100%" trim-weeks
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
                        <!-- <div class="column is-2">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Jam Operasi</h1>
                            <VDatePicker v-model="input.jamOperasi" color="green" mode="time" is24hr>
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VControl icon="feather:clock">
                                            <VInput :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </div> -->
                        <div class="column is-2">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Mulai Jam</h1>
                            <VDatePicker v-model="input.mulaiJamOperasi" color="green" mode="time" is24hr>
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VControl icon="feather:clock">
                                            <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-2">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Operasi Selesai</h1>
                            <VDatePicker v-model="input.jamOperasiSelesai" color="green" mode="time" is24hr>
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VControl icon="feather:clock">
                                            <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-2">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Durasi Operasi</h1>
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Durasi Operasi"
                                        v-model="input.durasiOpperasi" disabled />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-8">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Komplikasi Operasi</h1>
                            <VControl>
                                <VTextarea class="textarea" v-model="input.komplikasiOperasi" rows="2" placeholder="Catatan"
                                    autocomplete="off" autocapitalize="off" spellcheck="true" />
                            </VControl>
                        </div>
                    </div>
                </div>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-3 mb-3">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Jumlah perdarahan : </h1>
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Jumlah perdarahan"
                                        v-model="input.jumlahPerdarahan" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3 mb-3">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Darah yang hilang :</h1>
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Darah yang hilang"
                                        v-model="input.darahYangHilang" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3 mb-3">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Darah yang masuk lewat transfusi :</h1>
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Darah yang masuk lewat transfusi"
                                        v-model="input.darahYangMasukLewatTransfusi" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Perawatan pasca operasi</h1>
                    <div class="columns is-multiline">
                        <div class="column is-2 mb-3">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.perawatanPascaOperasiRuangan" label="Ruangan" class="p-0"
                                        color="primary" square />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3 mb-3" v-if="input.perawatanPascaOperasiRuangan">
                            <VField>
                                <AutoComplete v-model="input.perawatanPascaOperasiNamaRuangan" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="ketik untuk mencari..." />
                            </VField>
                        </div>
                        <div class="column is-2 mb-3">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.perawatanPascaOperasiHCU" label="HCU" class="p-0"
                                        color="primary" square />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-10">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Laporan Operasi</h1>
                            <VControl>
                                <VTextarea class="textarea" v-model="input.laporanOperasi" rows="7"
                                    placeholder="Laporan operasi" autocomplete="off" autocapitalize="off"
                                    spellcheck="true" />
                            </VControl>
                        </div>
                    </div>
                </div>
                <div class="column is-12" style="overflow-y: auto;">
                    <table class="tg table-tg">
                        <thead>
                            <tr>
                                <td class="tg-0lax text-center">Laporan Post Operasi</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="width:55%">
                                    <table class="tg">
                                        <tr>
                                            <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;">S
                                            </td>
                                            <td>
                                                <VField>
                                                    <VControl>
                                                        <VTextarea v-model="input.S" rows="5" placeholder="Subjective...">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;">O
                                            </td>
                                            <td>
                                                <VField>
                                                    <VControl>
                                                        <VTextarea v-model="input.O" rows="5" placeholder="Objective...">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;">A
                                            </td>
                                            <td>
                                                <VField>
                                                    <VControl>
                                                        <VTextarea v-model="input.A" rows="5" placeholder="Analysis...">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;">P
                                            </td>
                                            <td>
                                                <VField>
                                                    <VControl>
                                                        <VTextarea v-model="input.P" rows="5" placeholder="Planning...">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div clas="mt-5">
                    <div class="column is-4">
                        <h1 style="font-weight: bold;"> Cirebon, Tanggal </h1>
                        <VField class="mt-3">
                            <VDatePicker v-model="input.tglPembuatan" mode="dateTime" style="width: 100%" trim-weeks
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
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-6">
                                <img v-if="input.dpjpPasien"
                                    :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.dpjpPasien.label ? input.dpjpPasien.label : input.dpjpPasien)">
                                <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
                            </div>
                            <div class="column is-6">

                            </div>
                            <div class="column is-4">
                                <h1 class="p-0" style="font-weight: bold;">Dokter Penanggung Jawab Pasien</h1>
                                <VField>
                                    <VControl class="prime-auto">
                                        <AutoComplete v-model="input.dpjpPasien" :suggestions="d_Dokter"
                                            @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="DPJP IGG..." class="mt-2"
                                            @item-select="setTandaTangan($event, 'signature_1')" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4 is-offset-2">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </VCard>
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
// import Fieldset from 'primevue/fieldset';
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/laporan-operasi'

const d_Ruangan: any = ref([])
const d_Dokter: any = ref([])
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
    jamOperasi: new Date(),
    mulaiJamOperasi: new Date(),
    tglOperasi: new Date(),
    jamOperasiSelesai: new Date()
})

// Menambahkan watch untuk menghitung durasi operasi
watch([() => input.value.mulaiJamOperasi, () => input.value.jamOperasiSelesai], ([mulai, selesai]) => {
    if (mulai && selesai) {
        const mulaiDate = new Date(mulai)
        const selesaiDate = new Date(selesai)
        
        // Hitung selisih dalam milidetik
        const diffMs = selesaiDate.getTime() - mulaiDate.getTime()
        
        // Konversi ke jam dan menit
        const diffHrs = Math.floor(diffMs / 3600000)
        const diffMins = Math.floor((diffMs % 3600000) / 60000)
        
        // Format durasi
        input.value.durasiOpperasi = `${diffHrs} jam ${diffMins} menit`
    }
}, { deep: true })

const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
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
const setAutoFill = async () => {
  input.value.tglPembuatan = new Date()
  input.value.dpjpPasien = props.registrasi.dokter
  useApi().get(
        "emr/auto-fill-cppt?nocmfk=" + ID_PASIEN + "&norec_pd=" + props.registrasi.norec_pd +
        "&collection=CPPTDetail" + "&field=tgl,flag,S,P,O,uuid,nocmfk"
    ).then((response) => {
        if (response != null) {
            input.value.S = response.S
            input.value.P = response.P
            input.value.O = response.O
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
}
setView()
setAutoFill()
loadRiwayat()
</script>
<style lang="scss">
.CPPT_HEIGHT {
    overflow: auto;
    height: 600px;
}

.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
    background-color: var(--white);
    border-color: var(--fade-grey-dark-2) !important;
}

.tg-card {
    background-color: #feffed;
}

.is-dark {
    .tg {
        background-color: var(--dark-sidebar-light-6)
    }

    .tg-card {
        background-color: var(--dark-sidebar-light-6)
    }
}

.tg td {
    border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg th {
    border-color: var(--fade-grey-dark-3) !important;
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: normal;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg .tg-0lax {
    text-align: left;
    vertical-align: top
}

.scroll-container-rev {
    height: 1000px;
    overflow: auto;
}

@media (max-width: 1144px) {

    .table-tg {
        width: 150%;
    }
}
</style>
