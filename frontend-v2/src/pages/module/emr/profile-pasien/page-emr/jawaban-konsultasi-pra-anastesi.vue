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
                    <Fieldset :toggleable="true" legend="PENGKAJIAN MEDIS PRA ANASTESI / PRA SEDASI">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <table border="1" class="triase">
                                    <thead>
                                        <th class="bg-brown"
                                            style="width: 3%;font-weight: bold;vertical-align: middle !important;font-size: 25px;">
                                            I
                                        </th>
                                        <th style="width: 97%;">
                                            <table class="triase">
                                                <thead class="bg-brown">
                                                    <tr>
                                                        <th colspan="3"
                                                            style="text-align: center;font-weight: bold;font-size: 18px;">
                                                            SISTEM</th>
                                                    </tr>
                                                    <tr style="font-size: 15px;">
                                                        <th>Pernapasan
                                                            <VField style="text-align: right;">
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox
                                                                        v-model="input.pernapasanDalamBatasNormal"
                                                                        class="p-0" label="Dalam batas normal"
                                                                        color="primary" circle />
                                                                </VControl>
                                                            </VField>
                                                        </th>
                                                        <th>Cardiovasculer
                                                            <VField style="text-align: right;">
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox
                                                                        v-model="input.cardiovasculerDalamBatasNormal"
                                                                        class="p-0" label="Dalam batas normal"
                                                                        color="primary" circle />
                                                                </VControl>
                                                            </VField>
                                                        </th>
                                                        <th>Renal/Endocrine
                                                            <VField style="text-align: right;">
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox
                                                                        v-model="input.renalendocrineDalamBatasNormal"
                                                                        class="p-0" label="Dalam batas normal"
                                                                        color="primary" circle />
                                                                </VControl>
                                                            </VField>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 30%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.pernafasanAsma"
                                                                                class="p-0" label="Asma" color="primary"
                                                                                circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.pernafasanBatukProduktif"
                                                                                class="p-0" label="Batuk Produktif"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.pernafasanBronchitis"
                                                                                class="p-0" label="Bronchitis"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.pernafasanTuberculosis"
                                                                                class="p-0" label="Tuberculosis"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.pernafasanObstruktif"
                                                                                class="p-0" label="Obstruktif Kronis"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.pernafasanInfeksiPernafasan"
                                                                                class="p-0"
                                                                                label="Infeksi Pernafasan atas"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.pernafasanOrthopneu"
                                                                                class="p-0" label="Orthopneu"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.pernafasanPneumonia"
                                                                                class="p-0" label="Pneumonia"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 40%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.cardiovascularAbnormal"
                                                                                class="p-0" label="Abnormal EKG"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.cardiovascularPenyakitJantung"
                                                                                class="p-0" label="Penyakit Jantung"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.cardiovascularAngina"
                                                                                class="p-0" label="Angina"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.cardiovascularRheumatik"
                                                                                class="p-0" label="Rheumatik"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.cardiovascularSkleotik"
                                                                                class="p-0" label="Skleotik Aorta"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.cardiovascularMurmur"
                                                                                class="p-0" label="Murmur"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.cardiovascularGagalJantung"
                                                                                class="p-0" label="Gagal Jantung"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.cardiovascularPenyakitKatup"
                                                                                class="p-0" label="Penyakit Katup"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.cardiovascularKogestif"
                                                                                class="p-0" label="Kogestif"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.cardiovascularJantung"
                                                                                class="p-0" label="Jantung"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.cardiovascularDysrhitmia"
                                                                                class="p-0" label="Dysrhitmia"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.cardiovascularPacemaker"
                                                                                class="p-0" label="Pacemaker"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.cardiovascularhipertensi"
                                                                                class="p-0" label="Hipertensi"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.cardiovascularInfark"
                                                                                class="p-0" label="Infark Miokard"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 30%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.renalDiabetes"
                                                                                class="p-0" label="Diabetes"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.renalRetensiUrine"
                                                                                class="p-0" label="Retensi Urine"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.renalGagalGinjal"
                                                                                class="p-0"
                                                                                label="Gagal Ginjal/Dyalisis"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.renalInfeksiSaluranKemih"
                                                                                class="p-0"
                                                                                label="Infeksi Saluran Kemih"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.renalPenyakitThryoid"
                                                                                class="p-0" label="Penyakit Thyroid"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.renalPenurunanBB"
                                                                                class="p-0" label="Penurunan BB"
                                                                                color="primary" circle />
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
                                                        <th>Hepato/Gastrointestinal
                                                            <VField style="text-align: right;">
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox v-model="input.hepatoDalamBatasNormal"
                                                                        class="p-0" label="Dalam batas normal"
                                                                        color="primary" circle />
                                                                </VControl>
                                                            </VField>
                                                        </th>
                                                        <th>Neuro/Musculoskeletal
                                                            <VField style="text-align: right;">
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox v-model="input.neuroDalamBatasNormal"
                                                                        class="p-0" label="Dalam batas normal"
                                                                        color="primary" circle />
                                                                </VControl>
                                                            </VField>
                                                        </th>
                                                        <th>Lain-lain
                                                            <VField style="text-align: right;">
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox v-model="input.lainlainDalamBatasNormal"
                                                                        class="p-0" label="Dalam batas normal"
                                                                        color="primary" circle />
                                                                </VControl>
                                                            </VField>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 30%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.hepatoObstruksiUsus"
                                                                                class="p-0" label="Obstruksi Usus"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.hepatoUlcusGascer"
                                                                                class="p-0" label="Ulcus gaster/duodeum"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.hepatoCirrhosisHepatitis"
                                                                                class="p-0" label="Cirrhosis Hepatitis"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.hepatoHerniaHiatal"
                                                                                class="p-0" label="Hernia hiatal"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.hepatoIkterik"
                                                                                class="p-0" label="Ikterik"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.hepatoRefluks"
                                                                                class="p-0" label="Refluks esofagus"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 40%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.neuroArhiritis"
                                                                                class="p-0" label="Arhiritis"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.neuroKelemahanOtot"
                                                                                class="p-0" label="Kelemahan Otot"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.neuroKelainanTTulang"
                                                                                class="p-0"
                                                                                label="Kelainan tulang belakang"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.neuroPeny"
                                                                                class="p-0" label="Peny" color="primary"
                                                                                circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.neuroCVS"
                                                                                class="p-0"
                                                                                label="CVS/Stroke TIAs Intrakranial"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.neuroParalisi"
                                                                                class="p-0" label="Paralisis"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.neuroPenurunanKesadaaaran"
                                                                                class="p-0" label="Penurunan kesadaran"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.neuroSyncope"
                                                                                class="p-0" label="Syncope"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.neuroKejang"
                                                                                class="p-0" label="Kejang"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.neuroLBP"
                                                                                class="p-0" label="LBP/HNP"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 30%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.lainAnemia"
                                                                                class="p-0" label="Anemia"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.lainImunosupreesed"
                                                                                class="p-0" label="Imunosupreesed"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.lainResikoPendarahan"
                                                                                class="p-0" label="Resiko pendarahan"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.lainKehamilan"
                                                                                class="p-0" label="Kehamilan"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.lainKanker"
                                                                                class="p-0" label="Kanker"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.lainTerapi"
                                                                                class="p-0" label="Terapi steroid"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.lainChemoterapi"
                                                                                class="p-0" label="Chemoterapi"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.lainRiwayatTranfusi"
                                                                                class="p-0" label="Riwayat Transfusi"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.lainHermophil"
                                                                                class="p-0" label="Hermophil"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.lainAlergi"
                                                                                class="p-0" label="Alergi"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl icon="feather:data" fullwidth>
                                                                            <VInput v-model="input.lainText"
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
                                                <thead class="bg-brown">
                                                    <tr>
                                                        <th style="text-align: center;font-weight: bold;font-size: 15px;">LAIN-LAIN</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 100%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-2">
                                                                    <span>Merokok</span>
                                                                </div>
                                                                <div class="column is-1">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.laiiLainMerokokYa"
                                                                                class="p-0" label="Ya"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-1">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.laiiLainMerokokTidak"
                                                                                class="p-0" label="Tidak"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.laiiLainMerokRiwayatAnastesi"
                                                                                class="p-0" label="Riwayat Anastesi"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.laiiLainMerokRiwayatOperasi"
                                                                                class="p-0" label="Riwayat Operasi"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-2">
                                                                    <span>Konsumsi Alkohol</span>
                                                                </div>
                                                                <div class="column is-1">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.laiiLainAlkoholYa"
                                                                                class="p-0" label="Ya"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-1">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.laiiLainAlkoholTidak"
                                                                                class="p-0" label="Tidak"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.laiiLainAlkoholRiwayatKomplikasiAnastesi"
                                                                                class="p-0" label="Riwayat Komplikasi Anastesi"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.laiiLainAlkoholKlrg"
                                                                                class="p-0" label="Riwayat Klrg dg Komplikasi Anastesi"
                                                                                color="primary" circle />
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
                                                    <tr>
                                                        <th colspan="3" style="text-align: center;font-weight: bold;font-size: 15px;">PEMERIKSAAN</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 50%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-2">
                                                                    <span>Wajah</span>
                                                                </div>
                                                                <div class="column is-5">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.pemeriksaanDalamBatasNormal"
                                                                                class="p-0" label="dalam batas normal"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-5">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.pemeriksaanRahangSempit"
                                                                                class="p-0" label="rahang sempi"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-2">
                                                                </div>
                                                                <div class="column is-5">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.pemeriksaanBengkak"
                                                                                class="p-0" label="bengkak"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-5">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.pemeriksaanKumis"
                                                                                class="p-0" label="Kumis/Jenggot"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-2">
                                                                </div>
                                                                <div class="column is-5">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.pemeriksaanGigiPalsu"
                                                                                class="p-0" label="gigi palsu rahang"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-5">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.pemeriksaanKelainanBentuk"
                                                                                class="p-0" label="Kelainan Bentuk"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-2">
                                                                </div>
                                                                <div class="column is-5">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.pemeriksaanGigiOmpong"
                                                                                class="p-0" label="gigi ompong"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 35%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.pemeriksaanBukaMulut"
                                                                                class="p-0" label="Buka mulut ≥ 3 jari pasien"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.pemeriksaanJarakThyro"
                                                                                class="p-0" label="Jarak thyro mental ≥ 3 jari pasien"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 15%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.pemeriksaanBukaMulutYa"
                                                                                class="p-0" label="Ya"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.pemeriksaanBukaMulutTidak"
                                                                                class="p-0" label="Tidak"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.pemeriksaanBukaJarakThyroYa"
                                                                                class="p-0" label="Ya"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.pemeriksaanBukaJarakThyroTidak"
                                                                                class="p-0" label="Tidak"
                                                                                color="primary" circle />
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
                                                                    <span>Thoraks/abdomen</span>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.thoraksHamil"
                                                                                class="p-0" label="Hamil"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.thoraksAscites"
                                                                                class="p-0" label="Ascites"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.thoraksDalambatasNormal"
                                                                                class="p-0" label="dalam batas normal"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.thoraksObstruksiUsus"
                                                                                class="p-0" label="Obstruksi Usus"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.thoraksObesitas"
                                                                                class="p-0" label="Obesitas"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-3">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.thoraksSuaraNafas"
                                                                                class="p-0" label="suara nafas"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-3">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.thoraksVbs"
                                                                                class="p-0" label="VBS"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-3">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.thoraksWheezing"
                                                                                class="p-0" label="Whezing"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-3">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.thoraksRonkhi"
                                                                                class="p-0" label="Ronkhi"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 50%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                   <span>Skor Mallampati :</span>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-2">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.skorMallampatiI"
                                                                                class="p-0" label="I"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-2">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.skorMallampatiII"
                                                                                class="p-0" label="II"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-2">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.skorMallampatiIII"
                                                                                class="p-0" label="III"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-2">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.skorMallampatiIV"
                                                                                class="p-0" label="IV"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-2">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.skorMallampatiV"
                                                                                class="p-0" label="V"
                                                                                color="primary" circle />
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
                                                                    <span>Obstruksi jalan nafas</span>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.obstruksiJalanNafasTumor"
                                                                                class="p-0" label="Tumor"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.obstruksiJalanNafasGigiPalsu"
                                                                                class="p-0" label="Gigi Palsu"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.obstruksiJalanNafasDalamBatasNormal"
                                                                                class="p-0" label="Dalam batas normal"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.obstruksiJalanNafasStridor"
                                                                                class="p-0" label="Stridor"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.obstruksiJalanNafasKelainanBawaan"
                                                                                class="p-0" label="Kelainan bawaan"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 50%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.obstruksiJalanNafasTekananDarah"
                                                                                class="p-0" label="Tekanan darah"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.obstruksiJalanNafasLajuNadi"
                                                                                class="p-0" label="Laju nadi"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.obstruksiJalanNafasLajuNafas"
                                                                                class="p-0" label="Laju Nafas"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.obstruksiJalanNafasSaturasi"
                                                                                class="p-0" label="Saturasi"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.obstruksiJalanNafasTemperatur"
                                                                                class="p-0" label="Temperatur"
                                                                                color="primary" circle />
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
                                                    <tr>
                                                        <th style="text-align: center;font-weight: bold;font-size: 15px;">PEMERIKSAAN DIAGNOSTIK</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 100%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-2">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.pemeriksaanDiagnostikEKG"
                                                                                class="p-0" label="EKG"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-2">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.pemeriksaanDiagnostikRontgenThoraks"
                                                                                class="p-0" label="ROntgen thoraks"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-2">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.pemeriksaanDiagnostikDalamBatasNormal"
                                                                                class="p-0" label="Dalam batas normal"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-2">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.pemeriksaanDiagnostikSusp"
                                                                                class="p-0" label="Susp. TB Paru"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-2">
                                                                    <VField>
                                                                        <VControl icon="feather:data" fullwidth>
                                                                            <VInput v-model="input.pemeriksaanDiagnostiktext"
                                                                                placeholder="....."/>
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-2">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.pemeriksaanDiagnostikCTI"
                                                                                class="p-0" label="CTI/MRI"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-2" v-if="input.pemeriksaanDiagnostikCTI">
                                                                    <VField>
                                                                        <VControl icon="feather:data" fullwidth>
                                                                            <VInput v-model="input.pemeriksaanDiagnostiktCTIKet"
                                                                                placeholder="CTI/MRI Ket"/>
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
                                                    <tr>
                                                        <th style="text-align: center;font-weight: bold;font-size: 15px;">PEMERIKSAAN LABORATORIUM</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 100%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-4 mt-4">
                                                                    <VButton color="primary" class="w-100 btn-slim" light rounded outlined
                                                                        v-tooltip-prime.right="'Laboratorium'" @click="isLab = true"> Laboratorium 
                                                                    </VButton>
                                                                </div>
                                                                <div class="column is-8">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VTextarea rows="3" placeholder="..." v-model="input.pemeriksaanLaboratorium"></VTextarea>
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
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
                                            A
                                        </th>
                                        <th style="width: 97%;">
                                            <table class="triase">
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 50%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-4">
                                                                    <span>Permasalahan / Diagnosis :</span>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline" v-for="(items, index) in input.diagnosaICD10" :key="index">
                                                                <div class="column is-1">
                                                                    {{ items.no }}
                                                                </div>
                                                                <div class="column is-9">
                                                                    <VField>
                                                                        <VControl class="prime-auto">
                                                                        <AutoComplete v-model="items.ICD10Diagnosa" :suggestions="d_Diagnosa"
                                                                            @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true"
                                                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                                            placeholder="Diagnosis ..." />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-2">
                                                                    <VButtons style="justify-content: space-around;">
                                                                        <VIconButton type="button" raised circle icon="feather:plus"
                                                                            @click="addNewDiagnosaicd10()" color="info">
                                                                        </VIconButton>
                                                                        <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle
                                                                            icon="feather:trash" @click="removeItemDiagnosaIcd10(index)" color="danger">
                                                                        </VIconButton>
                                                                    </VButtons>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 20%;">
                                                            <div class="columns is-multiline mt-3">
                                                                <div class="column is-12" style="text-align: center;">
                                                                   <span>Status fisik</span><br>
                                                                   <span>ASA</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 30%;">
                                                            <div class="columns is-multiline mt-3">
                                                                <div class="column is-2">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.statusFisiki1"
                                                                                class="p-0" label="1"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-2">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.statusFisiki2"
                                                                                class="p-0" label="2"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-2">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.statusFisiki3"
                                                                                class="p-0" label="3"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-2">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.statusFisiki4"
                                                                                class="p-0" label="4"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-2">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.statusFisiki5"
                                                                                class="p-0" label="5"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-2">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.statusFisikiE"
                                                                                class="p-0" label="E"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
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
                                            R
                                        </th>
                                        <th style="width: 97%;">
                                            <table class="triase">
                                                <thead class="bg-brown">
                                                    <tr>
                                                        <th colspan="3" style="text-align: center;font-weight: bold;font-size: 15px;">RENCANA TINDAKAN ANASTESI</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 30%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <span>Teknik Abestesi Yang Disepakati :</span>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.teknikAbestesiGeneral"
                                                                                class="p-0" label="General Anesthesia"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.teknikAbestesiRegional"
                                                                                class="p-0" label="Regional : Spinal/Epidural/Peripheral"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.teknikAbestesiCombined"
                                                                                class="p-0" label="Combined general reginal anesthesia"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.teknikAbestesiMonitored"
                                                                                class="p-0" label="Monitored anasthesia care (MAC) / Sedasi"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.teknikAbestesiBlokSaraf"
                                                                                class="p-0" label="Blok Sara Perifer"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.teknikAbestesiSupraklavikula"
                                                                                class="p-0" label="Supraklavikula"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.teknikAbestesiFemoral"
                                                                                class="p-0" label="Femoral-Sciatic"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-5">
                                                                    <VField>
                                                                        <VControl icon="feather:data" fullwidth>
                                                                            <VInput v-model="input.teknikAbestesiLain"
                                                                                placeholder="....."/>
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 40%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                   <span>Prosedur / Monitoring / Invasive :</span>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.prosedurMonitoringArteri"
                                                                                class="p-0" label="Arteri line / CVC / PAC / TEE / Tranfusi darah"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline mt-4">
                                                                <div class="column is-12">
                                                                   <span>Penatalaksanaan Nyeri :</span>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-3">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.penatalaksanaanNyeriIntravena"
                                                                                class="p-0" label="Intravena"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-3">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.penatalaksanaanNyeriEpidural"
                                                                                class="p-0" label="Epidural"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-2">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.penatalaksanaanNyeriPAC"
                                                                                class="p-0" label="PAC"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.penatalaksanaanNyeriBlokSaraf"
                                                                                class="p-0" label="Blok Saraf Perifer"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-5">
                                                                    <VField>
                                                                        <VControl icon="feather:data" fullwidth>
                                                                            <VInput v-model="input.penatalaksanaanNyeriLain"
                                                                                placeholder="....."/>
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 30%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                   <span>Rencana Perawatan Post Anastesi</span>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.rencanaPerawatanPulang"
                                                                                class="p-0" label="Pulang" color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-3">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.rencanaPerawatanRawat"
                                                                                class="p-0" label="Rawat" color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-9" v-if="input.rencanaPerawatanRawat">
                                                                    <VField>
                                                                        <AutoComplete v-model="input.rencanaPerawatanRawatKet" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                                                                            :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                                                            :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
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
                                                        <td style="width: 70%;">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <span>Instruksi Pra-Anastesi (termasuk obat premedikasi)</span>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.instruksiSetuju"
                                                                                class="p-0" label="Setuju anastesi (operasi)"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.instruksiPuasa"
                                                                                class="p-0" label="Puasa 6 jam pre op"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.instruksiLaporHasilPenunjang"
                                                                                class="p-0" label="Lapor hasil penunjang"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.instruksiSediaDarah"
                                                                                class="p-0" label="Sedia Darah"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.instruksiLaporHasilKoreksi"
                                                                                class="p-0" label="Lapor hasil koreksi"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.instruksiPerbaikan"
                                                                                class="p-0" label="Perbaikan Keadaan Umum"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.instruksiPerbaikanHasil"
                                                                                class="p-0" label="Perbaikan hasil penunjang"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.instruksiPremedikasi"
                                                                                class="p-0" label="Premedikasi"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6" v-if="input.instruksiPremedikasi">
                                                                    <VField>
                                                                        <VControl fullwidth>
                                                                            <VInput v-model="input.instruksiPremedikasiKet"
                                                                                placeholder="....."/>
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="columns is-multiline">
                                                                <div class="column is-6">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.instruksiKonsulBagian"
                                                                                class="p-0" label="Konsul Bagian"
                                                                                color="primary" circle />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-6" v-if="input.instruksiKonsulBagian">
                                                                    <VField>
                                                                        <VControl fullwidth>
                                                                            <VInput v-model="input.instruksiKonsulBagianket"
                                                                                placeholder="....."/>
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 30%;text-align: center;">
                                                            <div clas="mt-5" style="text-align: center;">
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-12" style="text-align: center;">
                                                                        <h1 style="font-weight: bold;"> Cirebon, Tanggal </h1>
                                                                        <VField class="mt-3">
                                                                            <VDatePicker v-model="input.tglPembuatan" mode="dateTime" style="width: 100%"
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
                                                                        <img v-if="input.dpjpPasien"
                                                                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.dpjpPasien.label ? input.dpjpPasien.label : input.dpjpPasien) ">
                                                                        <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
                                                                    </div>
                                                                </div>
                                                                <div class="columns is-multiline" style="text-align: center;">
                                                                    <div class="column is-12" style="text-align: center;">
                                                                        <h1 class="p-0" style="font-weight: bold;">Nama & tanda tangan dokter anastesi</h1>
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
})
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
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

const tutupLabSetAutofill = async () => {
  isLab.value = false;
  setAutoFill()
}

const setAutoFill = async () => {
    input.value.tglPembuatan = new Date()
    // input.value.dpjpPasien = props.registrasi.dokter
    input.value.dpjpPasien = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
    input.value.namaPasien = props.pasien.namapasien
    input.value.tglLahir = props.pasien.tgllahir

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

    useApi().get("diagnosa/riwayat-diagnosa-x-cppt?noregistrasi=" + props.registrasi.noregistrasi
    ).then((response) => {
        if (response.data.length <= 0) {
      input.value.diagnosaICD10 = [{
        no: 1,
        norecDiagnosa: '',
        }]
    } else {
      input.value.diagnosaICD10 = response.data.map((e: any, i: any) => ({
        no: i + 1,
        jenisDiagnosa: { value: e.objectjenisdiagnosafk, label: e.jenisdiagnosa },
        norecDiagnosa: e.norec,
        ketDiagnosaDok: e.keterangan,
        ICD10Diagnosa: e.namadiagnosa && e.id ? { value: e.id, label: e.kddiagnosa + "--" + e.namadiagnosa } : ''
      }));
    }
        }).catch((e: any) => {
    })

}
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
