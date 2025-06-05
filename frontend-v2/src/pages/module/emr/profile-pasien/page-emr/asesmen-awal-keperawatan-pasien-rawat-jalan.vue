<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Asesmen Awal Keperawatan Pasien Rawat Jalan</h3>
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
                                    :loading="isLoading || isLoadingPasien" @click="simpan()"> Simpan
                                </VButton>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-body p-2">
                    <div class="business-dashboard hr-dashboard">
                        <div class="columns is-multiline">
                            <div class="column is-12" v-if="isLoadingPasien">
                                <PlaceloadHeader class="m-3" />
                            </div>
                            <div class="column is-12" v-if="!isLoadingPasien">
                                <VCard class="border-card pink">
                                    <div class="column is-12 pl-0 pr-0">
                                        <h2 style="font-size: medium;font-weight: 600;">Anamnesia</h2>
                                        <hr>
                                    </div>
                                    <div class="columns is-multiline pl-4 pr-4">
                                        <div class="column is-6 pl-2 pr-2">
                                            <h1 style="font-weight: bold;" class="mb-3">Keluhan Utama</h1>
                                            <VField>
                                                <VControl>
                                                    <VTextarea v-model="input.keluahanUtama" rows="3"
                                                        placeholder="Keluhan Utama ...">
                                                    </VTextarea>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6 pl-2 pr-2">
                                            <h1 style="font-weight: bold;" class="mb-3">Riwayat Penyakit Dahulu</h1>
                                            <VField>
                                                <VControl>
                                                    <VTextarea v-model="input.riwayatPenyakitDahulu" rows="3"
                                                        placeholder="Riwayat Penyakit Dahulu ...">
                                                    </VTextarea>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12 pl-2 pr-2">
                                            <h1 style="font-weight: bold;" class="mb-3">Riwayat Alergi</h1>
                                            <VField>
                                                <VControl>
                                                    <VTextarea v-model="input.riwayatAlergi" rows="2" placeholder=" ...">
                                                    </VTextarea>
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                    <div class="column is-12 pl-0 pr-0">
                                        <h2 style="font-size: medium;font-weight: 600;">Faktor Risiko Penyakit Jantung</h2>
                                        <hr>
                                    </div>
                                    <div class="column is-12 pb-0">
                                        <h1 style="font-weight: bold;">Hipertensi</h1>
                                        <div class="is-flex mt-2">
                                            <VField v-for="(data) in listHipertensi">
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[data.model]" :true-value="data.title"
                                                        :label="data.title" color="primary" circle />
                                                </VControl>
                                            </VField>

                                        </div>
                                    </div>
                                    <div class="columns pl-3">
                                        <div class="column is-3">
                                            <h1 style="font-weight: bold;">Merokok</h1>
                                            <div class="is-flex">
                                                <VField>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox v-model="input.merokok" true-value="Ya"
                                                            label="Ya (Berapa pack/hari)" color="danger" circle />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                        <div class="column is-5 pt-5 mt-4">
                                            <VControl>
                                                <input v-model="input.ketMerokok" type="text" class="input" />
                                            </VControl>
                                        </div>
                                        <div class="column pt-5 mt-3">
                                            <div class="is-flex">
                                                <VField>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox v-model="input.merokok" true-value="Tidak" label="Tidak"
                                                            color="primary" circle />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="column is-12 pb-0 pt-0">
                                        <h1 style="font-weight: bold;">Diabetes Melitus</h1>
                                        <div class="is-flex mt-2">
                                            <VField v-for="(data) in listDiabetes">
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[data.model]" :true-value="data.title"
                                                        :label="data.title" color="primary" circle />
                                                </VControl>
                                            </VField>

                                        </div>
                                    </div>

                                    <div class="column is-12 pb-0 pt-0">
                                        <h1 style="font-weight: bold;">Dyslipidemia (kelainan kolestrol darah)</h1>
                                        <div class="is-flex mt-2">
                                            <VField v-for="(data) in listDyslipidemia">
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[data.model]" :true-value="data.title"
                                                        :label="data.title" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>

                                    <div class="column is-7 pb-0 pt-0">
                                        <h1 style="font-weight: bold;">Riwayat serangan jantung dini pada orang tua (pria
                                            &lt; 55 tahun atau wanita &lt; 65 tahun)</h1>
                                        <div class="column is-7 p-0">
                                            <div class="is-flex mt-2" style="justify-content: space-between;">
                                                <VField v-for="(pilihan) in listDuaPilihan">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox v-model="input.riwayatSeranganJantung"
                                                            :true-value="pilihan" :label="pilihan" color="primary" circle />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="column is-12 pl-3 pr-3 pt-0">
                                        <h1 style="font-weight: bold;" class="mb-3">Riwayat Pengobatan</h1>
                                        <VField>
                                            <VControl>
                                                <VTextarea v-model="item.keluahan" rows="2" placeholder="Keluhan Utama ...">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </div>

                                    <div class="column is-12 pl-0 pr-0 pt-3">
                                        <h2 style="font-size: medium;font-weight: 600;">Tanda Vital</h2>
                                        <hr>
                                    </div>

                                    <div class="column is-12 pl-5 pr-5">
                                        <div class="columns">
                                            <div class="column is-4 pt-0">
                                                <VField>
                                                    <h1 style="font-weight: bold;">Tekanan Darah</h1>
                                                    <VControl>
                                                        <input v-model="input.tekananDarah" class="input"
                                                            placeholder="Tekanan Darah" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4 pt-0">
                                                <VField>
                                                    <h1 style="font-weight: bold;">Frekuensi Nadi</h1>
                                                    <VControl>
                                                        <input v-model="input.nadi" class="input"
                                                            placeholder="Frekuensi Nadi" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4 pt-0">
                                                <VField>
                                                    <h1 style="font-weight: bold;">Suhu</h1>
                                                    <VControl>
                                                        <input v-model="input.suhu" class="input" placeholder="Suhu" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="column is-12 pl-5 pr-5">
                                        <div class="columns pt-3">
                                            <div class="column is-6 pt-0">
                                                <VField>
                                                    <h1 style="font-weight: bold;">Frekuensi Nafas</h1>
                                                    <VControl>
                                                        <input v-model="input.pernapasan" class="input"
                                                            placeholder="Frekuensi Nafas" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-6 pt-0">
                                                <VField>
                                                    <h1 style="font-weight: bold;">Skor Nyeri</h1>
                                                    <VControl>
                                                        <input v-model="item.skorNyeri" class="input"
                                                            placeholder="Skor Nyeri" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="columns is-multiline is-12 pl-5 pr-5 pt-5 pb-0">
                                        <div class="column is-7">
                                            <h1 style="font-weight: bold;">Skoring nyeri (Wong Baker Faces)</h1>
                                            <div class="columns pt-4">
                                                <div class="column" style="text-align: center;"
                                                    v-for="(image, i) in listImageNyeri.detail">
                                                    <VAvatar size="medium" :picture="image.img"
                                                        style="cursor:pointer !important"
                                                        :class="isAktive == i ? 'active' : ''" @click="skor(image, i)" />
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
                                                        <VCheckbox class="pt-0" v-model="input.skoringNyeri"
                                                            :true-value="skor.descNilai" :label="skor.nama" color="primary"
                                                            circle />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="column is-12 is-12 pl-5 pr-5 pt-0">
                                        <h1 style="font-weight: bold;">Skala resiko jatuh</h1>
                                        <div class="column is-12">
                                            <div v-for="(isi) in pertanyaanA" class="pl-0">
                                                <h1 style="font-weight: bold;">{{ isi.pertanyaan }}</h1>
                                                <div class="column is-4 ml-2 pb-0"
                                                    style=" display: flex; justify-content: space-between;">
                                                    <VField v-for="(pilihan) in isi.jawaban">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox :true-value="pilihan" :label="pilihan.jawab"
                                                                v-model="input.sempoyongan" color="primary" circle
                                                                class="p-0" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="column is-12 pb-0">
                                            <div v-for="(isi) in pertanyaanB" class="pl-0">
                                                <h1 style="font-weight: bold;">{{ isi.pertanyaan }}</h1>
                                                <div class="column is-4 ml-2"
                                                    style=" display: flex; justify-content: space-between;">
                                                    <VField v-for="(pilihan) in isi.jawaban">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox :true-value="pilihan" :label="pilihan.jawab"
                                                                v-model="input.saatDuduk" color="primary" circle
                                                                class="p-0" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="column is-12">
                                            <div v-for="(isi) in pertanyaanC" class="pl-0">
                                                <h1 style="font-weight: bold;">{{ isi.pertanyaan }}</h1>
                                                <div class="column is-12 ml-2"
                                                    style=" display: flex; justify-content: space-between;">
                                                    <VField v-for="(pilihan) in isi.detail">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox :true-value="pilihan.value" :label="pilihan.title"
                                                                v-model="input.hasil" color="primary" circle class="p-0" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="column is-12 pl-0 pr-0 pt-0">
                                        <h2 style="font-size: medium;font-weight: 600;">Antropometri</h2>
                                        <hr>
                                    </div>

                                    <div class="column is-12 pl-5 pr-5">
                                        <div class="columns">
                                            <div class="column is-3 pt-0">
                                                <VField>
                                                    <h1 style="font-weight: bold;">Berat Badan</h1>
                                                    <VControl>
                                                        <input v-model="input.beratBadan" class="input" placeholder="KG" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-3 pt-0">
                                                <VField>
                                                    <h1 style="font-weight: bold;">Tinggi Badan</h1>
                                                    <VControl>
                                                        <input v-model="input.tinggiBadan" class="input" placeholder="cm" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-3 pt-0">
                                                <VField>
                                                    <h1 style="font-weight: bold;">Lingkar Perut</h1>
                                                    <VControl>
                                                        <input v-model="input.lingkarPerut" class="input"
                                                            placeholder="Lingkar Perut ..." />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-3 pt-0">
                                                <VField>
                                                    <h1 style="font-weight: bold;">IMT</h1>
                                                    <VControl>
                                                        <input v-model="input.IMT" class="input" placeholder="IMT ..." />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="column is-12">
                                        <Fieldset legend="RESIKO NUTRISIONAL">
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
                                                <div class="columns is-multiline"
                                                    v-for="(data) in resikoNutrisional.pertama">
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
                                                            <h1 :class="nilai.keterangan ? 'emr mb-5' : 'emr'">{{ nilai.poin
                                                            }}
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
                                                            <h1 :class="nilai.keterangan ? 'emr mb-3' : 'emr'">{{ nilai.poin
                                                            }}
                                                            </h1>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="column is-3" style="margin-left: auto;">
                                                <VField label="Jumlah Total">
                                                    <VControl raw subcontrol>
                                                        <input v-model="input.totalNilaiMST" class="input v-else"
                                                            disabled />
                                                    </VControl>
                                                </VField>
                                            </div>

                                            <div class="column is-12">
                                                <VCard>
                                                    <h1 style="font-weight: bold;">Keterangan</h1>
                                                    <div class="columns is-multiline p-3">
                                                        <div class="column is-2">
                                                            <p v-for="(range) in listRangeNilaiPoin" class="mt-2">{{ range
                                                            }}</p>
                                                        </div>
                                                        <div class="column is-4">
                                                            <p v-for="(desc) in listDESCNilai" class="mt-2">: {{ desc }}</p>
                                                        </div>
                                                    </div>
                                                </VCard>
                                            </div>
                                        </Fieldset>
                                    </div>

                                    <div class="column is-12 pl-0 pr-0">
                                        <h2 style="font-size: medium;font-weight: 600;">Fungsional</h2>
                                        <hr>
                                    </div>

                                    <div class="column is-12">
                                        <div class="columns is-multiline">
                                            <div class="column is-one-quarter pt-0" v-for="(data) in fungsionalPertama">

                                                <VField>
                                                    <h1 style="font-weight: bold;" class="mb-2">{{ data.title }}</h1>
                                                    <VControl>
                                                        <input v-model="input[data.model]" class="input"
                                                            :placeholder="data.title + ' ...'" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="column is-12">
                                        <h1 style="font-weight: bold;">Agama</h1>
                                        <div class="columns">
                                            <div class="column">
                                                <div class="is-flex mt-2">
                                                    <VField v-for="(agama) in listAgama">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input.agama" :true-value="agama"
                                                                :label="agama" color="primary" circle />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="column is-6">
                                        <h1 style="font-weight: bold;">Nilai-nilai yang dianut</h1>
                                        <VField class="p-3">
                                            <VControl>
                                                <input v-model="input.nilaiDianut" class="input"
                                                    placeholder="Nilai-nilai yang dianut" />
                                            </VControl>
                                        </VField>
                                    </div>

                                    <div class="column is-12">
                                        <h1 style="font-weight: bold;">Status Perkawinan</h1>
                                        <div class="columns">
                                            <div class="column">
                                                <div class="is-flex mt-2">
                                                    <VField v-for="(status) in listStatus">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input.statusPerkawinan" :true-value="status"
                                                                :label="status" color="primary" circle />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="column is-12">
                                        <h1 style="font-weight: bold;">Keluarga</h1>
                                        <div class="columns">
                                            <div class="column">
                                                <div class="is-flex mt-2">
                                                    <VField v-for="(keluarga) in listKeluarga">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input.keluarga" :true-value="keluarga"
                                                                :label="keluarga" color="primary" circle />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="column is-12">
                                        <h1 style="font-weight: bold;">Tempat Tinggal</h1>
                                        <div class="columns">
                                            <div class="column is-2" v-for="(tempat) in listTempatTinggal">
                                                <VField>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox v-model="input.tempatTinggal" :true-value="tempat"
                                                            :label="tempat" color="primary" circle />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column ">
                                                <VField class="p-3">
                                                    <VControl>
                                                        <input v-model="input.keteranganTempat" class="input" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="column is-12">
                                        <h1 style="font-weight: bold;">Status Psikologis</h1>
                                        <div class="columns">
                                            <div class="column" v-for="(psikologi) in listPsikologis">
                                                <VField>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox v-model="input.statusPsikologis" :true-value="psikologi"
                                                            :label="psikologi" color="primary" circle />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="column is-4">
                                        <h1 style="font-weight: bold;">Edukasi</h1>
                                        <VField class="p-3">
                                            <VControl>
                                                <input v-model="input.edukasi" class="input" placeholder="edukasi" />
                                            </VControl>
                                        </VField>
                                    </div>

                                    <div class="column is-12">
                                        <h1 style="font-weight: bold;">Hambatan Edukasi</h1>
                                        <div class="columns is-multiline">
                                            <div class="column is-2">
                                                <VField>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox v-model="input.hambatanEdukasi" true-value="Bahasa"
                                                            label="Bahasa" color="primary" circle />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4 mt-2">
                                                <VField>
                                                    <VControl>
                                                        <input v-model="input.keteranganBahasa" class="input"
                                                            placeholder="Keterangan Bahasa" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>

                                        <div class="columns is-multiline">
                                            <div class="column is-6 pt-0">
                                                <VField>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox v-model="input.hambatanEdukasi"
                                                            true-value="Cacat/fisik/kognitif"
                                                            label="Cacat/fisik/kognitif (gangguan penglihatan/pendengaran lain)"
                                                            color="primary" circle />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4 mt-2 pt-0">
                                                <VField>
                                                    <VControl>
                                                        <input v-model="input.keteranganCacat" class="input"
                                                            placeholder="Keterangan Catat" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="column is-12" v-for="(value) in listMore">
                                        <h1 style="font-weight: bold;" class="mb-3">{{ value.title }}
                                        </h1>
                                        <VField>
                                            <VControl>
                                                <VTextarea v-model="input[value.model]" rows="3"
                                                    placeholder="Keluhan Utama ...">
                                                </VTextarea>
                                            </VControl>
                                        </VField>

                                    </div>

                                </VCard>
                                <div class="column is-4" style="margin-left: auto;">
                                    <VCard class="border-card pink">
                                        <div class="column">
                                            <h1 class="mb-3" style="font-weight: bold;">Tanggal dan Jam</h1>
                                            <VField>
                                                <VDatePicker v-model="input.waktuDibuat" mode="dateTime" style="width: 100%"
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
                                        <div class="column">
                                            <VField class="is-autocomplete-select" v-slot="{ id }">
                                                <VControl icon="feather:search">
                                                    <AutoComplete v-model="input.perawat" :suggestions="d_pegawai"
                                                        @complete="fetchDokter($event)" :optionLabel="'label'"
                                                        :dropdown="true" :minLength="3" :appendTo="'body'"
                                                        :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                        placeholder="Cari Perawat" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </VCard>
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
import { h, reactive, ref, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import * as EMR from '../page-emr-plugins/asesmen-awal-keper-rj'

useHead({
    title: 'Asesmen Awal - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
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

const pasien: any = ref({})
const d_pegawai: any = ref([])
const isLoadingPasien: any = ref(false)
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: props.registrasi.norec_apd,
    RUANGAN_LAST: props.registrasi.objectruanganlastfk,
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    date: {
        tanggal: new Date,
        jam: new Date
    },
    airway: [],
    disability: []

})

const COLLECTION: any = ref('AsesmenAwalKeperawatanRJ') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()

let listHipertensi:any = ref(EMR.hipertensi())
let listDiabetes:any = ref(EMR.diabetes())
let listDyslipidemia:any = ref(EMR.dyslipidemia())
let listDuaPilihan:any = ref(EMR.duaPilihan())
let listAgama:any = ref(EMR.agama())
let listStatus:any = ref(EMR.status())
let listKeluarga:any = ref(EMR.keluarga())
let listTempatTinggal:any = ref(EMR.tempatTinggal())
let listPsikologis:any = ref(EMR.psikologis())
let listMore:any = ref(EMR.more())
let listImageNyeri:any = ref(EMR.imgNyeri())
let listSkoringNyeri: any = ref(EMR.skoringNyeri())
let resikoNutrisional:any = ref(EMR.resikoNutrisional())
let fungsionalPertama:any = ref(EMR.fungsionalPertama())
let listRangeNilaiPoin:any = ref(EMR.nilaiPoin())
let listDESCNilai:any = ref(EMR.descNilai())
let pertanyaanA:any = ref(EMR.pertanyaanA())
let pertanyaanB:any = ref(EMR.pertanyaanB())
let pertanyaanC:any = ref(EMR.pertanyaanC())


const loadRiwayat = () => {

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
    object.nocm = pasien.value.nocm

    object.pasien = H.setObjectPasien(pasien.value)
    object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
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
            // NOREC_EMRPASIEN.value = response.norec_emr
        }).catch((e: any) => {
            isLoading.value = false
        })

    // console.log(resultValue)
}

const kembaliKeun = () => {
    window.history.back()
}
const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
    console.log(norec_emr)
}

const fetchDokter = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_pegawai.value = response
    })
}

const skor = (e: any, i: any) => {

    let listSkor = listSkoringNyeri.value.detail

    listSkor.forEach((element: any) => {
        if (element.descNilai == e.descNilai) {
            input.value.skoringNyeri = e.descNilai
        }
    });
    isAktive.value = i

}

const getDataExist = async () => {
    await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}`).then((response) => {

        input.value.beratBadan = response.beratBadan
        input.value.tinggiBadan = response.tinggiBadan
        input.value.IMT = response.IMT
        input.value.lingkarPerut = response.lingkarPerut
        input.value.tekananDarah = response.tekananDarah
        input.value.nadi = response.nadi
        input.value.suhu = response.suhu
        input.value.pernapasan = response.pernapasan
    })
}

const print = async () => {
    H.printBlade(`emr/cetak-asesmen-keper-rj?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
}


getDataExist()
fetchPasien()
loadRiwayat()

watch(() => [
    input.value.penurunanBB,
    input.value.penurunanNafsuMakan,
], () => {

    let poin1 = input.value.penurunanBB ? parseInt(input.value.penurunanBB.poin) : 0
    let poin2 = input.value.penurunanNafsuMakan ? parseInt(input.value.penurunanNafsuMakan.poin) : 0

    const total = poin1 + poin2
    input.value.totalNilaiMST = total

})

</script>


<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/asesmen-awal.scss';

.v-avatar.is-medium.active {
    padding: 3px;
    background: var(--success);
    display: inline-table !important;
}

.p-fieldset-legend {
    margin-left: 14px;
}

.p-fieldset .p-fieldset-content {
    background: none;
}

// .p-fieldset.p-component{
//     border-left: ;
// }

table.assesment {
    border-collapse: collapse;
    width: 100%;
}


.assesment th {
    text-align: center !important;
    border-bottom: 1px solid black;
    // border: 1px solid black;
}

.assesment th,
.assesment td {
    padding: 8px;
    vertical-align: middle !important;
}

hr {
    background-color: hsl(0deg 6.81% 88.68%);
    border: none;
    display: block;
    height: 2px;
    margin: 1rem 0;
}

// tr:hover {
//     background-color: #f5f5f5;
// }
</style>
