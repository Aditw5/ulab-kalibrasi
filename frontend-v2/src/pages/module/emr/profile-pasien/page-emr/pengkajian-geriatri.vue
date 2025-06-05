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
        <VCard>
            <Fieldset class="p-fieldsets" :legend="'I. ASESMEN KEPERAWATAN (Diisi Oleh Perawat)'" :toggleable="true">
                <Fieldset class="p-fieldsets" legend=" A. ANAMNESIS" :toggleable="true">
                    <div class="columns is-multiline mb-5">
                        <div class="column is-12 P-0">
                            <div class="column is-12">
                                <div class="columns is-multiline">
                                    <div class="column is-2" v-for="(data, i) in anamnesis">
                                        <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input[data.model]" :true-value="data.value" :label="data.label" class="p-0" color="primary" square/>
                                        </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <Fieldset class="p-fieldsets" legend="1. Riwayat Rekreasi" :toggleable="true">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                    <span> Hobby/minat : </span>
                                    <VField>
                                        <VTextarea rows="2" placeholder="Hobby/minat....." v-model="input.riwayatRekreasiHobby">
                                        </VTextarea>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <span> Keanggotaan Organisasi : </span>
                                    <VField>
                                        <VTextarea rows="2" placeholder="Keanggotaan Organisasi....." v-model="input.riwayatRekreasiOrganisasi">
                                        </VTextarea>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <span> Hiburan Perjalanan : </span>
                                    <VField>
                                        <VTextarea rows="2" placeholder="Hiburan Perjalanan....." v-model="input.riwayatRekreasiHiburan">
                                        </VTextarea>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </Fieldset>
                    <Fieldset class="p-fieldsets" legend="2. Riwayat Pekerjaan" :toggleable="true">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <span> Pekerjaan saat ini : </span>
                                    <VField>
                                        <VTextarea rows="2" placeholder="Pekerjaan saat ini....." v-model="input.riwayatPekerjaanSaatIni">
                                        </VTextarea>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <span> Alamat Pekerjaan : </span>
                                    <VField>
                                        <VTextarea rows="2" placeholder="Alamat Pekerjaan....." v-model="input.riwayatPekerjaanAlamat">
                                        </VTextarea>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <span> Berapa jarak dari rumah : </span>
                                    <VField>
                                        <VTextarea rows="2" placeholder="km....." v-model="input.riwayatPekerjaanJarak">
                                        </VTextarea>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <span> Sumber-sumber pendapatan & kecukupan terhadap kebutuhan : </span>
                                    <VField>
                                        <VTextarea rows="2" placeholder="Sumber-sumber pendapatan & kecukupan terhadap kebutuhan....." v-model="input.riwayatPekerjaanPendapatan">
                                        </VTextarea>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </Fieldset>
                    <Fieldset class="p-fieldsets" legend="3. Sistem Pendukung" :toggleable="true">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-2">
                                    <span>Perawat/bidan/dokterfisioterapi :</span>
                                </div>
                                <div class="column is-4">
                                    <VField>
                                        <VTextarea rows="1" placeholder="....." v-model="input.sistemPendukungDokter">
                                        </VTextarea>
                                    </VField>
                                </div>
                                <div class="column is-1">
                                    <span>Jarak :</span>
                                </div>
                                <div class="column is-5">
                                    <VField>
                                        <VTextarea rows="1" placeholder="km...." v-model="input.sistemPendukungDokterJarak">
                                        </VTextarea>
                                    </VField>
                                </div>
                            </div>
                            <div class="columns is-multiline">
                                <div class="column is-2">
                                    <span>Rumah sakit :</span>
                                </div>
                                <div class="column is-4">
                                    <VField>
                                        <VTextarea rows="1" placeholder="....." v-model="input.sistemPendukungRS">
                                        </VTextarea>
                                    </VField>
                                </div>
                                <div class="column is-1">
                                    <span>Jarak :</span>
                                </div>
                                <div class="column is-5">
                                    <VField>
                                        <VTextarea rows="1" placeholder="km...." v-model="input.sistemPendukungRSJarak">
                                        </VTextarea>
                                    </VField>
                                </div>
                            </div>
                            <div class="columns is-multiline">
                                <div class="column is-2">
                                    <span>Klinik :</span>
                                </div>
                                <div class="column is-4">
                                    <VField>
                                        <VTextarea rows="1" placeholder="....." v-model="input.sistemPendukungKlinik">
                                        </VTextarea>
                                    </VField>
                                </div>
                                <div class="column is-1">
                                    <span>Jarak :</span>
                                </div>
                                <div class="column is-5">
                                    <VField>
                                        <VTextarea rows="1" placeholder="km...." v-model="input.sistemPendukungKlinikJarak">
                                        </VTextarea>
                                    </VField>
                                </div>
                            </div>
                            <div class="columns is-multiline">
                                <div class="column is-2">
                                    <span>Pelayanan kesehatan dirumah :</span>
                                </div>
                                <div class="column is-10">
                                    <VField>
                                        <VTextarea rows="2" placeholder="....." v-model="input.sistemPendukungPelayananKesehatan">
                                        </VTextarea>
                                    </VField>
                                </div>
                            </div>
                            <div class="columns is-multiline">
                                <div class="column is-2">
                                    <span>Perawatan sehari-hari dirumah :</span>
                                </div>
                                <div class="column is-10">
                                    <VField>
                                        <VTextarea rows="2" placeholder="....." v-model="input.sistemPendukungPerawatanSehari">
                                        </VTextarea>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </Fieldset>
                    <Fieldset class="p-fieldsets" legend="4. Deskripsi Kekhususan" :toggleable="true">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-2">
                                    <span>Kebiasaan Ritual :</span>
                                </div>
                                <div class="column is-10">
                                    <VField>
                                        <VTextarea rows="2" placeholder="....." v-model="input.deskripsiKekhususanRitual">
                                        </VTextarea>
                                    </VField>
                                </div>
                            </div>
                            <div class="columns is-multiline">
                                <div class="column is-2">
                                    <span>Kebiasaan Lainnya :</span>
                                </div>
                                <div class="column is-10">
                                    <VField>
                                        <VTextarea rows="2" placeholder="....." v-model="input.deskripsiKekhususanLain">
                                        </VTextarea>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </Fieldset>
                    <Fieldset class="p-fieldsets" legend="5. Status Kesehatan" :toggleable="true">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <span>Status Kesehatan Satu Tahun Terakhir :</span>
                                </div>
                                <div class="column is-9">
                                    <VField>
                                        <VTextarea rows="2" placeholder="....." v-model="input.statusKesehatanSatuTahun">
                                        </VTextarea>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </Fieldset>
                    <Fieldset class="p-fieldsets" legend="6. Gangguan Sistem" :toggleable="true">
                        <div class="columns is-multiline">
                        <div class="column is-6" v-for="(data, index) in gangguanSistem" :key="index">
                            <h1 class="emr bold">{{ data.title }}</h1>
                            <div class="column is-12" v-for="(category, subIndex) in data.detail" :key="subIndex">
                                <h1 class="mb-2">{{ category.label }}</h1>
                                <div class="columns is-multiline p-3">
                                    <div v-for="(option, optionIndex) in category.options" :key="optionIndex">
                                        <VField>
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="input[category.model]" :true-value="option.value" :label="option.label" class="p-0" color="primary" square
                                                style="margin-right: 10px;"
                                                />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                                <div class="columns is-multiline" v-if="input[category.model] === 'Ya'">
                                    <div class="column">
                                        <VField>
                                            <VControl>
                                                <VInput type="text" v-model="input[category.model + 'Keterangan']" placeholder="..." />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </Fieldset>
                </Fieldset>
                <Fieldset class="p-fieldsets" legend="B. STATUS FUNGSIONAL (INDEKS ADL BARTHEL)" :toggleable="true">
                    <div class="column">
                        <h1>Mengendalikan rangsang pembuangan tinja, berkemih, membersihkan diri, penggunaan jamban mandiri/dibantu, makan, mobilitas berbaring, duduk, berpindah/jalan, menggunakan baju, naik turun tangga dan mandi SAAT MEMASUKI RUMAH SAKIT </h1>
                        <h1 class="mt-3" style="font-weight: bold;">Kriteria</h1>
                        <div class="column is-12">
                            <p>
                                <VField>
                                    <VControl>
                                        <VCheckbox v-model="input.statusFungsional_Mandiri" class="p-0" color="primary" label="Mandiri : 20" square/>
                                    </VControl>
                                </VField>
                            </p>
                            <p>
                                <VField>
                                    <VControl>
                                        <VCheckbox v-model="input.statusFungsional_KetergantunganRingan" class="p-0" color="primary" label="Ketergantungan ringan : 12 - 19" square/>
                                    </VControl>
                                </VField>
                            </p>
                            <p>
                                <VField>
                                    <VControl>
                                        <VCheckbox v-model="input.statusFungsional_KetergantunganSedang" class="p-0" color="primary" label="Ketergantungan sedang : 9 - 11" square/>
                                    </VControl>
                                </VField>
                            </p>
                            <p>
                                <VField>
                                    <VControl>
                                        <VCheckbox v-model="input.statusFungsional_KetergantunganBerat" class="p-0" color="primary" label="Ketergantungan berat : 5 - 8" square/>
                                    </VControl>
                                </VField>
                            </p>
                            <p>
                                <VField>
                                    <VControl>
                                        <VCheckbox v-model="input.statusFungsional_KetergantunganTotal" class="p-0" color="primary" label="Ketergantungan total : 0 - 4" square/>
                                    </VControl>
                                </VField>
                            </p>
                        </div>
                        <h1>Target perawatan</h1>
                        <div class="column is-12">
                            <p> - Kembali pada scoring ADL sebelum sakit </p>
                            <p> - Turun satu tingkat ketergantungan </p>
                        </div>
                        <h1>SKOR INDEKS ADL BARTHEL (ADL) (diisi saat awal, selama dan saat pulang)</h1>
                    </div>
                    <div class="column" style="overflow: auto;">
                        <table class="tg">
                            <thead>
                                <tr>
                                    <td class="tg-0lax text-center">No. </td>
                                    <td class="tg-0lax text-center">Jenis Kegiatan</td>
                                    <td class="tg-0lax text-center">Sebelum Sakit</td>
                                    <td class="tg-0lax text-center">Selama Rawat/3 hari</td>
                                    <td class="tg-0lax text-center">Saat discharge</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="2%">1</td>
                                    <td width="20%">Mengendalikan Ransangan Pembuangan Tinja</td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional1_Sebelum">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional1_Rawat">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional1_Discharge">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="2%">2</td>
                                    <td width="20%">Mengendalikan Ransangan berkemih</td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional2_Sebelum">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional2_Rawat">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional2_Discharge">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="2%">3</td>
                                    <td width="20%">Membersihkan diri</td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional3_Sebelum">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional3_Rawat">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional3_Discharge">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="2%">4</td>
                                    <td width="20%">Menggunakan jamban</td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional4_Sebelum">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional4_Rawat">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional4_Discharge">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="2%">5</td>
                                    <td width="20%">Makan</td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional5_Sebelum">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional5_Rawat">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional5_Discharge">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="2%">6</td>
                                    <td width="20%">Berubah sikap dari baring ke duduk</td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional6_Sebelum">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional6_Rawat">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional6_Discharge">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="2%">7</td>
                                    <td width="20%">Berpindah atau berjalan</td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional7_Sebelum">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional7_Rawat">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional7_Discharge">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="2%">8</td>
                                    <td width="20%">Memakai Baju</td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional8_Sebelum">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional8_Rawat">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional8_Discharge">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="2%">9</td>
                                    <td width="20%">Naik turun tangga</td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional9_Sebelum">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional9_Rawat">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional9_Discharge">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="2%">10</td>
                                    <td width="20%">Mandi</td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional10_Sebelum">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional10_Rawat">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea rows="2" placeholder="..." v-model="input.statusFungsional10_Discharge">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="2%"></td>
                                    <td width="20%">Total Score</td>
                                    <td>{{ totalStatusFungsional_Sebelum }}</td>
                                    <td>{{ totalStatusFungsional_Rawat }}</td>
                                    <td>{{ totalStatusFungsional_Discharge }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="column">
                        <h1 class="bold">Tindak lanjut hasil scoring: Minimal scoring saat dirawat di RS dan discharge meningkat 2 point atau sama dengan hasil scoring saat masuk RS</h1>
                    </div>
                </Fieldset>
                <Fieldset class="p-fieldsets" legend="C. SKRINING RESIKO DEKUBITUS (SKALA BRADEN SCALE)" :toggleable="true">
                    <div class="columns is-multiline mb-5">
                        <div class="column is-12 P-0">
                            <div class="column is-12">
                                <div class="columns is-multiline">
                                    <div class="column is-3" v-for="(data, i) in dekubitus">
                                        <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input[data.model]" :true-value="data.value" :label="data.label" class="p-0" color="primary" square/>
                                        </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </Fieldset>
                <Fieldset class="p-fieldsets" legend="D. IDENTIFIKASI MASALAH EMOSIONAL (Diisi jika pasien dalam kondisi sadar)" :toggleable="true">
                    <div class="columns is-multiline">
                        <div class="column is-6" v-for="(data, index) in emosional" :key="index">
                            <h1 class="emr bold">{{ data.title }}</h1>
                            <div class="column is-12" v-for="(category, subIndex) in data.detail" :key="subIndex">
                                <h1 class="mb-2">{{ category.label }}</h1>
                                <div class="columns is-multiline p-3">
                                    <div v-for="(option, optionIndex) in category.options" :key="optionIndex">
                                        <VField>
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="input[category.model]" :true-value="option.value" :label="option.label" class="p-0" color="primary" square
                                                style="margin-right: 10px;"
                                                />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                                <div class="columns is-multiline" v-if="input[category.model] === 'Ya'">
                                    <div class="column">
                                        <VField>
                                            <VControl>
                                                <VInput type="text" v-model="input[category.model + 'Keterangan']" placeholder="..." />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                            <h1>{{ data.ket }}</h1>
                            <div class="columns is-multiline" v-if="data.title === 'Pertanyaan tahap kedua'"> 
                                <div class="column is-5">
                                    <h1 class="bold">Bila sama atau lebih dari satu jawaban "Ya" </h1>
                                </div>
                                <div class="column is-6">
                                    <VField class="d-flex align-center">
                                        <VControl>
                                            <VCheckbox v-model="input.emosionalKedua_MasalahEmosional" class="p-0" color="primary" label="MASALAH EMOSIONAL" square/>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-5">
                                    <h1 class="bold">Tindak Lanjut :</h1>
                                </div>
                                <div class="column is-5">
                                    <VField>
                                        <VControl>
                                            <VCheckbox v-model="input.emosionalKedua_TindakLanjut" class="p-0" color="primary" label="Identifikasi Lanjutan oleh dokter ruangan" square/>
                                        </VControl>
                                    </VField> 
                                </div>
                            </div>
                        </div>
                    </div>
                </Fieldset>
                <Fieldset class="p-fieldsets" legend="E. PENGKAJIAN STATUS MENTAL GERONTIK (Diisi jika pasien dalam kondisi sadar)" :toggleable="true">
                    <div class="column">
                        <h1>Identifikasi tingkat kerusakan intelektual dengan menggunakan <i>Short Portable Mental Status Quesioner (SPMSQ)</i></h1>
                        <h1>Instruksi : Ajukan 10 pertanyaan 1-10 pada daftar ini dan catat semua jawaban, catat jumlah kesalahan</h1>
                    </div>
                    <div class="column" style="overflow: auto;">
                        <table class="tg">
                            <thead>
                                <tr>
                                    <td class="tg-0lax text-center">No. </td>
                                    <td class="tg-0lax text-center">Pertanyaan</td>
                                    <td class="tg-0lax text-center">Benar</td>
                                    <td class="tg-0lax text-center">Salah</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="2%">1</td>
                                    <td width="50%">Tanggal berapa hari ini?</td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VCheckbox v-model="input.statusMental1" :true-value="'Benar'" class="p-0" color="primary" square/>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VCheckbox v-model="input.statusMental1" :true-value="'Salah'" class="p-0" color="primary" square/>
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="2%">2</td>
                                    <td width="50%">Hari apa sekarang ini?</td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VCheckbox v-model="input.statusMental2" :true-value="'Benar'" class="p-0" color="primary" square/>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VCheckbox v-model="input.statusMental2" :true-value="'Salah'" class="p-0" color="primary" square/>
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="2%">3</td>
                                    <td width="50%">Apa nama tempat ini?</td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VCheckbox v-model="input.statusMental3" :true-value="'Benar'" class="p-0" color="primary" square/>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VCheckbox v-model="input.statusMental3" :true-value="'Salah'" class="p-0" color="primary" square/>
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="2%">4</td>
                                    <td width="50%">
                                        a. Berapa no telp anda? <br>
                                        b. Dimana alamat anda? (tanyakan bila tidak memiliki telepon)
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VCheckbox v-model="input.statusMental4" :true-value="'Benar'" class="p-0" color="primary" square/>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VCheckbox v-model="input.statusMental4" :true-value="'Salah'" class="p-0" color="primary" square/>
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="2%">5</td>
                                    <td width="50%">Berapa umur anda?</td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VCheckbox v-model="input.statusMental5" :true-value="'Benar'" class="p-0" color="primary" square/>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VCheckbox v-model="input.statusMental5" :true-value="'Salah'" class="p-0" color="primary" square/>
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="2%">6</td>
                                    <td width="50%">Kapan anda lahir? (minimal tahun lahir)</td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VCheckbox v-model="input.statusMental6" :true-value="'Benar'" class="p-0" color="primary" square/>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VCheckbox v-model="input.statusMental6" :true-value="'Salah'" class="p-0" color="primary" square/>
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="2%">7</td>
                                    <td width="50%">Siapa presiden Indonesia sekarang?</td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VCheckbox v-model="input.statusMental7" :true-value="'Benar'" class="p-0" color="primary" square/>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VCheckbox v-model="input.statusMental7" :true-value="'Salah'" class="p-0" color="primary" square/>
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="2%">8</td>
                                    <td width="50%">Siapa presiden sebelumnya?</td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VCheckbox v-model="input.statusMental8" :true-value="'Benar'" class="p-0" color="primary" square/>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VCheckbox v-model="input.statusMental8" :true-value="'Salah'" class="p-0" color="primary" square/>
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="2%">9</td>
                                    <td width="50%">Siapa nama kecil ibu anda?</td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VCheckbox v-model="input.statusMental9" :true-value="'Benar'" class="p-0" color="primary" square/>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VCheckbox v-model="input.statusMental9" :true-value="'Salah'" class="p-0" color="primary" square/>
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="2%">10</td>
                                    <td width="50%">
                                        Perhitungan sederhana: tentukan satu angka(dua digit), kurangi angka satuan, tanyakan berapa?, selanjutnya tanyakan lagi bila dikurangi angka satuan yang sama, secara menurun(tiga kali). <br>
                                        Contoh : 1) 20-4=16, 16-4=12, 12-4=8 <br>
                                                 2) 15-3=12, 15-3=9, 9-3=6 
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VCheckbox v-model="input.statusMental10" :true-value="'Benar'" class="p-0" color="primary" square/>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VCheckbox v-model="input.statusMental10" :true-value="'Salah'" class="p-0" color="primary" square/>
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="2%"></td>
                                    <td width="50%">Total Jawaban Salah</td>
                                    <td>{{ totalStatusMental_Benar }}</td>
                                    <td>{{ totalStatusMental_Salah }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="column">
                        <h1 class="mt-3" style="font-weight: bold;">Keterangan</h1>
                        <div class="column is-12">
                            <p> 1. Kesalahan 0 - 3 : Fungsi intelektual utuh </p>
                            <p> 2. Kesalahan 4 - 5 : Kerusakan intelektual ringan </p>
                            <p> 3. Kesalahan 6 - 8 : Kerusakan intelektual sedang </p>
                            <p> 4. Kesalahan 9 - 10 : Kerusakan intelektual berat </p>
                            <p> *  Jika ditemukan kerusakan intelektual sengan s.d berat (kesalahan 6 - 10, maka edukasi dijelaskan kepada kepada keluarga keluarga atau penanggung jawab )</p>
                        </div>
                    </div>
                </Fieldset>
                <Fieldset class="p-fieldsets" legend="F. MASALAH" :toggleable="true">
                    <div class="columns is-multiline mb-5">
                        <div class="column is-12 P-0">
                            <div class="column is-12">
                                <div class="columns is-multiline">
                                    <div class="column is-2" v-for="(data, i) in masalah">
                                        <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input[data.model]" :true-value="data.value" :label="data.label" class="p-0" color="primary" square/>
                                        </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-12">
                                <div class="columns is-multiline">
                                    <div class="column is-2">
                                        <span>Lainnya :</span>
                                    </div>
                                    <div class="column is-10">
                                        <VField>
                                            <VTextarea rows="2" placeholder="....." v-model="input.masalahLainnya">
                                            </VTextarea>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </Fieldset>
                <Fieldset class="p-fieldsets" legend="G. RENCANA KEPERAWATAN" :toggleable="true">
                    <div class="columns is-multiline">
                        <div class="column is-12 P-0">
                            <div class="column is-12">
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <VField>
                                            <VTextarea rows="3" placeholder="Rencana Keperawatan....." v-model="input.rencanaKeperawatan"></VTextarea>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </Fieldset>
            </Fieldset>
            <Fieldset class="p-fieldsets" :legend="'II. ASESMEN MEDIS (Diisi Oleh Dokter)'" :toggleable="true">
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-3">
                            <span>Keluhan Utama (data subyektif) :</span>
                        </div>
                        <div class="column is-9">
                            <VField>
                                <VTextarea rows="2" placeholder="....." v-model="input.keluhanUtama">
                                </VTextarea>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-3">
                            <span>Riwayat penyakit sekarang :</span>
                        </div>
                        <div class="column is-9">
                            <VField>
                                <VTextarea rows="2" placeholder="....." v-model="input.riwayatPenyakitSekarang">
                                </VTextarea>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-3">
                            <span>Riwayat penyakit dahulu :</span>
                        </div>
                        <div class="column is-9">
                            <VField>
                                <VTextarea rows="2" placeholder="....." v-model="input.riwayatPenyakitDahulu">
                                </VTextarea>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-3">
                            <span>Riwayat penyakit keluarga :</span>
                        </div>
                        <div class="column is-9">
                            <VField>
                                <VTextarea rows="2" placeholder="....." v-model="input.riwayatPenyakitKeluarga">
                                </VTextarea>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-3">
                            <span>Riwayat alergi Makanan :</span>
                        </div>
                        <div class="column is-3">
                            <VField>
                                <VTextarea rows="2" placeholder="....." v-model="input.riwayatAlergiMakanan">
                                </VTextarea>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <span>Riwayat alergi Obat :</span>
                        </div>
                        <div class="column is-4">
                            <VField>
                                <VTextarea rows="2" placeholder="....." v-model="input.riwayatAlergiObat">
                                </VTextarea>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-3">
                            <span>Pemeriksaan fisik dan penunjang (data obyektif) :</span>
                        </div>
                        <div class="column is-9">
                            <VField>
                                <VTextarea rows="3" placeholder="....." v-model="input.pemeriksaanFisikPenunjang">
                                </VTextarea>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-3">
                            <span>Diagnosis :</span>
                        </div>
                        <div class="column is-9">
                            <VField>
                                <VTextarea rows="2" placeholder="....." v-model="input.diagnosis">
                                </VTextarea>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-3">
                            <span>Penatalaksanaan/perencanaan pelayanan (terapi, tindakan, konsultasi, pemeriksaan penunjang lanjutan, edukasi,perencanaan pulang) :</span>
                        </div>
                        <div class="column is-9">
                            <VField>
                                <VTextarea rows="2" placeholder="....." v-model="input.penatalaksanaanPelayanan">
                                </VTextarea>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-3">
                            <span>Instruksi :</span>
                        </div>
                        <div class="column is-9">
                            <VField>
                                <VTextarea rows="2" placeholder="....." v-model="input.instruksi">
                                </VTextarea>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-3">
                            <span>Sasaran :</span>
                        </div>
                        <div class="column is-9">
                            <VField>
                                <VTextarea rows="2" placeholder="....." v-model="input.sasaran">
                                </VTextarea>
                            </VField>
                        </div>
                    </div>
                </div>
            </Fieldset>
            <Fieldset class="p-fieldsets" :legend="'III. TENAGA MEDIS'" :toggleable="true">
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-4">
                            <h1 style="font-weight: bold">Dokter Assesmen</h1>
                            <h1 style="font-weight: bold">Tanggal dan Jam</h1>
                            <VDatePicker v-model="input.tglPengkajianDokter" class="pt-3" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents"/>
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold">Perawat Assesmen</h1>
                            <h1 style="font-weight: bold">Tanggal dan Jam</h1>
                            <VDatePicker v-model="input.tglpengkajianPerawat" class="pt-3" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents"/>
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold">Verifikasi DPJP</h1>
                            <h1 style="font-weight: bold">Tanggal dan Jam</h1>
                            <VDatePicker v-model="input.tglVerifikasi" class="pt-3" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents"/>
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-4">
                            <img v-if="input.dokterPengkajian"
                                :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.dokterPengkajian.label ? input.dokterPengkajian.label : input.dokterPengkajian) + ', ' + (input.tglPengkajianDokter ? input.tglPengkajianDokter : input.tglPengkajianDokter)">
                            <!-- <TandaTangan
                            :elemenID="'signature_1'"
                            :width="'180'"
                            :height="'180'"
                            ></TandaTangan> -->
                        </div>
                        <div class="column is-4">
                            <img v-if="input.perawatPengkajian"
                                :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.perawatPengkajian.label ? input.perawatPengkajian.label : input.perawatPengkajian) + ', ' + (input.tglpengkajianPerawat ? input.tglpengkajianPerawat : input.tglpengkajianPerawat)">
                            <!-- <TandaTangan
                            :elemenID="'signature_2'"
                            :width="'180'"
                            :height="'180'"
                            ></TandaTangan> -->
                        </div>
                        <div class="column is-4">
                            <img v-if="input.dpjpPengkajian"
                                :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.dpjpPengkajian.label ? input.dpjpPengkajian.label : input.dpjpPengkajian) + ', ' + (input.tglVerifikasi ? input.tglVerifikasi : input.tglVerifikasi)">
                            <!-- <TandaTangan
                            :elemenID="'signature_2'"
                            :width="'180'"
                            :height="'180'"
                            ></TandaTangan> -->
                        </div>
                        <div class="column is-4">
                            <h1 class="p-0" style="font-weight: bold">Dokter</h1>
                            <VField>
                                <VControl class="prime-auto">
                                    <AutoComplete v-model="input.dokterPengkajian" :suggestions="d_Pegawai" @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Dokter..." class="mt-2" @item-select="setTandaTangan($event)"/>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1 class="p-0" style="font-weight: bold">Perawat</h1>
                            <VField>
                                <VControl class="prime-auto">
                                    <AutoComplete v-model="input.perawatPengkajian" :suggestions="d_Pegawai" @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Perawat..." class="mt-2" @item-select="setTandaTangan($event)"/>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1 class="p-0" style="font-weight: bold">DPJP</h1>
                            <VField>
                                <VControl class="prime-auto">
                                    <AutoComplete v-model="input.dpjpPengkajian" :suggestions="d_Pegawai" @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="DPJP..." class="mt-2" @item-select="setTandaTangan($event)"/>
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
            </Fieldset>
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
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/pengkajian-geriatri'



let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let anamnesis = ref(EMR.anamnesis())
let masalah = ref(EMR.masalah())
let dekubitus = ref(EMR.dekubitus())
let emosional = ref(EMR.emosional())
let gangguanSistem = ref(EMR.gangguanSistem())

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
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
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
const input: any = ref({})
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
        if (response[0].tandaTanganDokter) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganDokter)
        }
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
  object.tandaTanganDokter = H.tandaTangan().get("signature_1")
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

const setTandaTangan = async (e: any) => {
  const response = await useApi().get(
    `/emr/tanda-tangan/${e.value.value}`)
  if (response != null) {
    H.tandaTangan().set("signature_1", response.tandaTanganDokter)
    input.value.tandaTanganDokter = response.tandaTanganDokter
  } else {
    H.tandaTangan().set("signature_1", '')
  }
}
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}
const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.tglPembuatan = new Date()
  input.value.dokterPengkajian = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
}

// Computed property to calculate the total score
const totalStatusFungsional_Sebelum = computed(() => {
    return (parseInt(input.value.statusFungsional1_Sebelum, 10) || 0) + (parseInt(input.value.statusFungsional2_Sebelum, 10) || 0) + (parseInt(input.value.statusFungsional3_Sebelum, 10) || 0) + (parseInt(input.value.statusFungsional4_Sebelum, 10) || 0) + (parseInt(input.value.statusFungsional5_Sebelum, 10) || 0) + (parseInt(input.value.statusFungsional6_Sebelum, 10) || 0) + (parseInt(input.value.statusFungsional7_Sebelum, 10) || 0) + (parseInt(input.value.statusFungsional8_Sebelum, 10) || 0) + (parseInt(input.value.statusFungsional9_Sebelum, 10) || 0) + (parseInt(input.value.statusFungsional10_Sebelum, 10) || 0)
})
const totalStatusFungsional_Rawat = computed(() => {
    return (parseInt(input.value.statusFungsional1_Rawat, 10) || 0) + (parseInt(input.value.statusFungsional2_Rawat, 10) || 0) + (parseInt(input.value.statusFungsional3_Rawat, 10) || 0) + (parseInt(input.value.statusFungsional4_Rawat, 10) || 0) + (parseInt(input.value.statusFungsional5_Rawat, 10) || 0) + (parseInt(input.value.statusFungsional6_Rawat, 10) || 0) + (parseInt(input.value.statusFungsional7_Rawat, 10) || 0) + (parseInt(input.value.statusFungsional8_Rawat, 10) || 0) + (parseInt(input.value.statusFungsional9_Rawat, 10) || 0) + (parseInt(input.value.statusFungsional10_Rawat, 10) || 0)
})
const totalStatusFungsional_Discharge = computed(() => {
    return (parseInt(input.value.statusFungsional1_Discharge, 10) || 0) + (parseInt(input.value.statusFungsional2_Discharge, 10) || 0) + (parseInt(input.value.statusFungsional3_Discharge, 10) || 0) + (parseInt(input.value.statusFungsional4_Discharge, 10) || 0) + (parseInt(input.value.statusFungsional5_Discharge, 10) || 0) + (parseInt(input.value.statusFungsional6_Discharge, 10) || 0) + (parseInt(input.value.statusFungsional7_Discharge, 10) || 0) + (parseInt(input.value.statusFungsional8_Discharge, 10) || 0) + (parseInt(input.value.statusFungsional9_Discharge, 10) || 0) + (parseInt(input.value.statusFungsional10_Discharge, 10) || 0)
})
const totalStatusMental_Benar = computed(() => {
    let count = 0;
    if (input.value.statusMental1 == "Benar") {count++;}
    if (input.value.statusMental2 == "Benar") {count++;}
    if (input.value.statusMental3 == "Benar") {count++;}
    if (input.value.statusMental4 == "Benar") {count++;}
    if (input.value.statusMental5 == "Benar") {count++;}
    if (input.value.statusMental6 == "Benar") {count++;}
    if (input.value.statusMental7 == "Benar") {count++;}
    if (input.value.statusMental8 == "Benar") {count++;}
    if (input.value.statusMental9 == "Benar") {count++;}
    if (input.value.statusMental10 == "Benar") {count++;}
    return count;
})
const totalStatusMental_Salah = computed(() => {
    let count = 0;
    if (input.value.statusMental1 == "Salah") {count++;}
    if (input.value.statusMental2 == "Salah") {count++;}
    if (input.value.statusMental3 == "Salah") {count++;}
    if (input.value.statusMental4 == "Salah") {count++;}
    if (input.value.statusMental5 == "Salah") {count++;}
    if (input.value.statusMental6 == "Salah") {count++;}
    if (input.value.statusMental7 == "Salah") {count++;}
    if (input.value.statusMental8 == "Salah") {count++;}
    if (input.value.statusMental9 == "Salah") {count++;}
    if (input.value.statusMental10 == "Salah") {count++;}
    return count; 
})

setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
.table-fro {
  width: 100%;
  border: 1px solid black;
}

.th-fro,
.td-fro {
  padding: 7px;
  border: 1px solid black;
  vertical-align: inherit;
}

.setFRO-center {
  text-align: center !important;
}

.p-fieldset-legend {
  margin-left: 15px;
}

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
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
  vertical-align: middle
}

.tg th {
  border-color: var(--fade-grey-dark-3);
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
  vertical-align: top;
  font-weight: bold;
}
</style>