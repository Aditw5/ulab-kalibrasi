<template>
  <FloatingButtonSimpan @click="simpan" :loading="isLoading" />
  <FloatingButtonCetak @click="print" :loading="isLoading" />
  <!-- <FloatingButtonSave @click="simpan" :loading="isLoading" /> -->
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div v-if="isStuck" :class="[isStuck && 'is-stuck']" style="margin-top:50px; padding: 0px 0px !important;" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" 
:isDisableSimpanButton="props.pasien.isDisableSimpanButton"
:isLoading="isLoading" @simpan="simpan"
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
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" 
:isDisableSimpanButton="props.pasien.isDisableSimpanButton"
:isLoading="isLoading" @simpan="simpan"
              @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>

    </div>
  </div>


  <div class="column">

    <VCard>
      <div class="column is-12">
        <VCard class="p-3">
          <h1 style="font-weight: bold; margin-bottom: 1rem;"> DETAIL KUNJUNGAN PASIEN</h1>
          <div class="column is-12 px-2">
            <div class="columns is-multiline mb-5">
              <div class="column is-3">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Allo Anamnesis" label="Allo Anamnesis"
                      v-model="input.allow" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Auto Anamnesis" label="Auto Anamnesis"
                      v-model="input.allow" />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-4">
                <VField label="Usia Saat Kunjuangan">
                  <VControl>
                    <VInput type="text" class="input" placeholder="Umur " v-model="input.umur" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <VField label="Tanggal Registrasi">
                  <VDatePicker v-model="input.tanggalWaktuRegistrasi" mode="dateTime" style="width: 100%" trim-weeks
                    :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" readonly />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
              <div class="column is-4">
                <VField label="Tanggal/Jam Kunjuangan">
                  <VDatePicker v-model="input.tanggalWaktuKunjuangan" mode="dateTime" style="width: 100%" trim-weeks
                    :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal Waktu Kunjungan" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-6">
                <VField label="Alasan Kunjungan/keluhan Utama">
                  <VTextarea rows="2" placeholder="Alasan Kunjungan......" v-model="input.alasanKunjunagn"></VTextarea>
                </VField>
              </div>
              <div class="column is-6">
                <VField label="Riwayat Penyakit Dahulu">
                  <VTextarea rows="2" placeholder="Riwayat Penyakit Dahulu......" v-model="input.riwayatPenyakitDahulu">
                  </VTextarea>
                </VField>
              </div>
              <div class="column is-6">
                <VField label="Riwayat Alergi">
                  <VTextarea rows="2" placeholder="Riwayat Alergi......" v-model="input.riwayatAlergi"></VTextarea>
                </VField>
              </div>
              <div class="column is-6">
                <VField label="Riwayat Obat">
                  <VTextarea rows="2" placeholder="Riwayat Obat......" v-model="input.riwayatObat"></VTextarea>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-6">
                <VField label="Poli">
                  <AutoComplete v-model="input.namaruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                </VField>
              </div>
            </div>
          </div>
        </VCard>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="RIWAYAT PSIKOSOSIAL DAN SPRITUAL" :toggleable="true">
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12 P-0">
                <div class="column is-12" style="margin-top:0.5rem">
                  <span> Hubungan pasien dengan keluarga : </span>
                </div>
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-2" v-for="(data, i) in hubunganPasien">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.hubunganPasien" :true-value="data.title" :label="data.title"
                            class="p-0" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
              <div class="column is-12 P-0">
                <div class="column is-12" style="margin-top:0.5rem">
                  <span> Status psikologis : </span>
                </div>
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-2" v-for="(data, i) in statusPsikologisPasien">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.statusPsikologisPasien" :true-value="data.value" :label="data.label"
                            class="p-0" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
              <div class="column is-12 P-0">
                <div class="column is-4" style="margin-top:0.5rem">
                  <span style="font-weight: bold;"> Spritual : </span>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-12 P-0">
                    <div class="is-flex" v-for="(data, i) in spritualPasien">
                      <div class="column is-4">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input[data.model]" :true-value="data.value" :label="data.title"
                              class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-6">
                        <VField>
                          <VControl>
                            <VInput type="text" class="input" placeholder="... " v-model="input[data.ketValue]" />
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
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="Pemeriksaan Fisik" :toggleable="true">
          <div class="column is-12">
            <div class="columns is-multiline p-3">
              <div class="column is-3" v-for="(data, i) in vitalSign ">
                <div class=" columns is-multiline">
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> {{ data.label }} : </span>
                  </div>
                  <div class="column is-12">
                    <VPlaceloadText :lines="1" v-if="isLoadingVitalSign" />
                    <VField addons v-else>
                      <VControl>
                        <VInput type="text" class="input" :placeholder="data.label" v-model="input[data.model]" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>{{ data.addon }} </VButton>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-12 p-3">
            <VField label="Status Gizi">
              <VTextarea rows="3" placeholder="Status Gizi......" v-model="input.statusGizi"></VTextarea>
            </VField>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="Pengkajian Keperawatan" :toggleable="true">
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-2">
                <h5>Respirasi :</h5>
              </div>
              <div class="column is-2" v-for="(data, i) in respirasi">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.respirasi" :true-value="data.value" :label="data.label"
                      class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
            </div>
            <div v-if="input.respirasi == 'respirasiTidakNormal'">
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.respirasiTidakNormalVesikuler"  label="Vesikuler" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.respirasiTidakNormalRonchi"  label="Ronchi" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.respirasiTidakNormalBatukBerdahak"  label="Batuk Berdahak" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.respirasiTidakNormalNafasTidakTeratur"  label="Nafas Tidak Teratur" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.respirasiTidakNormalNafasLambat"  label="Nafas Lambat/Dispune" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.respirasiTidakNormalPenggunaanOtotBahu"  label="Penggunaan Otot Bahu Nafas" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.respirasiTidakNormalBatukKering"  label="Batuk Kering" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.respirasiTidakNormalBatukDarah"  label="Batuk darah" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.respirasiTidakNormalWheezing"  label="Wheezing" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.respirasiTidakNormalSulitDahak"  label="Sulit Mengeluarkan Dahak" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.respirasiTidakNormalNafasCepat"  label="Nafas Cepat" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.respirasiTidakNormalApnoe"  label="Apnoe" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-2">
                <h5>Sirkulasi :</h5>
              </div>
              <div class="column is-2" v-for="(data, i) in sirkulasi">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.sirkulasi" :true-value="data.value" :label="data.label"
                      class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
            </div>
            <div v-if="input.sirkulasi == 'sirkulasiTidakNormal'">
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.sirkulasiBerdebar"  label="Berdebar-debar" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.sirkulasiNyeriDada"  label="Nyeri Dada" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.sirkulasiPingsan"  label="Pingsan" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.sirkulasiMurmur"  label="Murmur" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.sirkulasiGailop"  label="Gailop" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.sirkulasiMerasaDingin"  label="MerasaDingin" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.sirkulasiSianosis"  label="Sianosis/Pucat" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.sirkulasiCapilary"  label="Capilary Refil" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.sirkulasiBengkak"  label="Bengkak Anasarka" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.sirkulasiKesemutan"  label="Kesemutan" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.sirkulasiLainLain"  label="Lain-lain" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4" v-if="input.sirkulasiLainLain">
                  <VField>
                    <VControl raw subcontrol>
                      <VInput v-model="input.sirkulasiKetLainLain" placeholder="Lain-lain" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-2">
                <h5>Nutrisi dan Cairan :</h5>
              </div>
              <div class="column is-2" v-for="(data, i) in nutrisi">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.nutrisi" :true-value="data.value" :label="data.label"
                      class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
            </div>
            <div v-if="input.nutrisi == 'nutrisiTidakNormal'">
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Tinggi Badan" v-model="input.nutrisiTB" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>cm</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Berat Badan" v-model="input.nutrisiBB" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>Kg</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Lingkar Kepala" v-model="input.nutrisiLingkarKepala" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>cm</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.nutrisiPolaMakandanMinum"  label="Pola Makan dan Minum" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4" v-if="input.nutrisiPolaMakandanMinum">
                  <VField label="(Frekwensi, jenis, jumlah, kebiasaan dan patungan)">
                    <VControl raw subcontrol>
                      <VInput  v-model="input.nutrisiPolaMakandanMinumLainLain" placeholder="(Frekwensi, jenis, jumlah, kebiasaan dan patungan)" />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-4">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.nutrisiPolaMakandanMinumUntukBayi"  label="Pola Makan dan Minum Untuk Bayi" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.nutrisiDiare"  label="Diare" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.nutrisiUbun"  label="Ubun-ubung Cekung" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.nutrisiMataCekung"  label="Mata Cekung" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.nutrisiKulitKering"  label="Kulit Kering" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.nutrisiTandaDehidrasi"  label="Tanda Dehidrasi" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.nutrisiAnoreksia"  label="Anoreksia" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.nutrisiMuntah"  label="Muntah" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.nutrisiKurangMinum"  label="Kurang Minum" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.nutrisiTifakNAfsuMakan"  label="Tidak Nafsu Makan" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.nutrisiSulitMenelan"  label="Sulit Menelan" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.nutrisiSulitMengunyah"  label="Sulit Mengunyah" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.nutrisiGangguanMenghisap"  label="Gangguan Menghisap" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.nutrisiKebersihanMulut"  label="Kebersihan Mulut" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.nutrisiSelaluMerasaKenyang"  label="Selalu Merasa Kenyang" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.nutrisiTidakBisaKentut"  label="Tidak Bisa Kentut" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.nutrisiBisingUsus"  label="Bising Usus" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.nutrisiKembung"  label="Kembung" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.nutrisiKebiasaanKonsumsi"  label="Kebiasaan Konsumsi yang tidak sehat seperti : garam/fast food/alkohol" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.nutrisiLainlain"  label="Lain-lain" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-2">
                <h5>Eliminasi :</h5>
              </div>
              <div class="column is-2" v-for="(data, i) in eliminasi">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.eliminasi" :true-value="data.value" :label="data.label"
                      class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
            </div>
            <div v-if="input.eliminasi == 'eliminasiTidakNormal'">
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <h2>BAK</h2>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.eliminasiBAKAyang"  label="Ayang-ayangan" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.eliminasiBAKKencingTertahan"  label="Kencing Tertahan" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.eliminasiBAKSukaMenahanKencing"  label="Suka Menahan Kencing" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.eliminasiBAKKencingTidakPuas"  label="Kencing Tidak Puas" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.eliminasiBAKKencingNyeri"  label="Kencing Nyeri" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.eliminasiBAKNgompol"  label="Ngompol Saat Tidur" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.eliminasiBAKKencingBeser"  label="Kencing Beser" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.eliminasiBAKKencingMenetes"  label="Kencing Menetes" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.eliminasiBAKKencingDarah"  label="Kencing Darah" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.eliminasiBAKKencingTidakTerkontrol"  label="Kencing Tidak Terkontrol" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.eliminasiBAKKencingMalam"  label="Kencing Malam" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.eliminasiBAKFrekwensiBertambah"  label="BAK Frekwensi Bertambah" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <h2>BAB</h2>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.eliminasiBABKeras"  label="BAB Keras" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.eliminasiBABCair"  label="BAB Cair" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.eliminasiBABArusKemerahan"  label="Arus Kemerahan" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.eliminasiBABTidakBisaDitahan"  label="BAB Tidak Bisa Ditahan" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.eliminasiBABNyeri"  label="BAB Nyeri" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.eliminasiBABKecilkecil"  label="BAB Kecil-kecil" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.eliminasiBABkebiasaanMenggunakan"  label="Kebiasaan Menggunakan Pencahar" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.eliminasiBABLebih2Kalipermg"  label="BAB < 2 kali/mg" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.eliminasiBABKurang2kalipermg"  label="BAB kurang 2 kali/mg" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.eliminasiBABberdarah"  label="BAB berdarah" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.eliminasiBABLeb"  label="Lain-lain" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-2">
                <h5>Neurosensori :</h5>
              </div>
              <div class="column is-2" v-for="(data, i) in neurosensori">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.neurosensori" :true-value="data.value" :label="data.label"
                      class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
            </div>
            <div v-if="input.neurosensori == 'neurosensoriTidakNormal'">
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField label="GCS">
                    <VControl>
                      <VInput type="text" v-model="input.neurosensoriGcs" placeholder="gcs..." />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <Vlabel style="color: #849aa5;">Ukuran Pupil</Vlabel>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Ukuran Pupil" v-model="input.neurosensoriUkuranPupil" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>cm</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField label="Respon Pada Cahaya">
                    <VControl>
                      <VInput type="text" v-model="input.neurosensoriResponCahaya" placeholder="Respon Pada Cahaya" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField label="Kemerahan">
                    <VControl>
                      <VInput type="text" v-model="input.neurosensoriKemerahan" placeholder="Kemerahan" />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.neurosensoriComposMentis"  label="Compos Mentis" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.neurosensoriSomnolen"  label="Somnolen" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.neurosensoriSoporocoma"  label="Soporocoma" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.neurosensoriKoma"  label="Koma" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.neurosensoriSakitKepala"  label="Sakit kepala" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.neurosensoriPusing"  label="Pusing" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.neurosensoriMudahLupa"  label="Mudah Lupa" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.neurosensoriLinglung"  label="Linglung" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.neurosensoriGelisah"  label="Gelisah" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.neurosensoriTidakterKoordinsai"  label="Tidak Terkoordinsai" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.neurosensoriTidakMengingat"  label="Tidak Mengingat" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.neurosensoriIngatanJangkaPendek"  label="Ingatan Jangka Pendek" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.neurosensoriKesemutan"  label="Kesemutan" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.neurosensoriHilangSensasi"  label="Hilang Sensasi" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.neurosensoriMatiRasa"  label="Mati Rasa" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.neurosensoriPengecapanTurun"  label="Pengecapan Turun" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.neurosensoriAsomsia"  label="Asomsia" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.neurosensoriPelo"  label="Pelo" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.neurosensoriRiwayatJatuhKecelakaan"  label="Riwayat Jatuh Kecelakaan" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-4">
                  <VField label="Lain-lain">
                    <VControl>
                      <VInput type="text" v-model="input.neurosensoriLainlain" placeholder="Lain-lain" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-2">
                <h5>Aktivitas dan Istirahat :</h5>
              </div>
              <div class="column is-2" v-for="(data, i) in aktivitasTidur">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.aktivitasTidur" :true-value="data.value" :label="data.label"
                      class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
            </div>
            <div v-if="input.aktivitasTidur == 'aktivitasTidurTidakNormal'">
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.aktivitasTidurNyeriSaatTidur"  label="Nyeri Saat Tidur" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.aktivitasTidurTidakBisagerak"  label="Tidak Bisa Gerak" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.aktivitasTidurTidurBerlebih"  label="Tidur Berlebih" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.aktivitasTidurSesakSaatBergerak"  label="Sesak Saat Bergerak" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.aktivitasTidurTanganKakiBergetar"  label="Tangan Kaki Bergetar" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.aktivitasTidurLetih"  label="Letih" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.aktivitasTidurSulitTidur"  label="Sulit Tidur" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.aktivitasTidurPerubahanPolaTidur"  label="Perubahan Pola Tidur" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.aktivitasTidurTidakPunyaTenaga"  label="Tidak Punya Tenaga" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.aktivitasTidurKesemutan"  label="Kesemutan" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.aktivitasTidurEngganBergerak"  label="Enggan Bergerak" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.aktivitasTidurTidakPulihSaatTidur"  label="Tidak Pulih Saat Tidur" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.aktivitasTidurAsomsia"  label="Asomsia" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.aktivitasTidurLemah"  label="Lemah" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                    <h2>Status Fungsional</h2>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.aktivitasTidurNyeriSaatGerak"  label="Nyeri Saat Gerak" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.aktivitasTidurDibantuSebagian"  label="Dibantu Sebagian" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.aktivitasTidurButuhMobilisasi"  label="Butuh Mobilisasi" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.aktivitasTidurBantuanMakan"  label="Bantuan Makan & Minum" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.aktivitasTidurDibantuPenuh"  label="Dibantu Penuh" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.aktivitasTidurButuhBantuanEliminasi"  label="Bantuan Eliminasi" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.aktivitasTidurButuhMandi"  label="Butuh Mandi" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-8">
                  <div class="columns is-multiline">
                    <div class="column is-8">
                      <h1 class="emr">Skrining Jatuh :</h1>
                    </div>
                    <div class="column is-2">
                      <h1 class="emr">Ya</h1>
                    </div>
                    <div class="column is-2">
                      <h1 class="emr">Tidak</h1>
                    </div>
                  </div>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-8">
                  <div class="columns is-multiline">
                    <div class="column is-2"></div>
                    <div class="column is-6">
                      <h1 class="emr">Pernah Jatuh dalam Kurun 3 Bulan</h1>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.skringJatuhPernahJatuhJrun3BulanYa" class="p-0"
                            color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.skringJatuhPernahJatuhJrun3BulanTidak" class="p-0"
                            color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-8">
                  <div class="columns is-multiline">
                    <div class="column is-2"></div>
                    <div class="column is-6">
                      <h1 class="emr">Diagnosis sekunder lebih dari satu</h1>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.skringJatuhDiagnosisSekunderLebihDariSatuYa" class="p-0"
                            color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.skringJatuhDiagnosisSekunderLebihDariSatuTidak" class="p-0"
                            color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-8">
                  <div class="columns is-multiline">
                    <div class="column is-2"></div>
                    <div class="column is-6">
                      <h1 class="emr">Bed rest</h1>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.skringJatuhBedRestYa" class="p-0"
                            color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.skringJatuhBedRestTidak" class="p-0"
                            color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-8">
                  <div class="columns is-multiline">
                    <div class="column is-2"></div>
                    <div class="column is-6">
                      <h1 class="emr">Walker/Kruk</h1>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.skringJatuhWalkerYa" class="p-0"
                            color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.skringJatuhWalkerTidak" class="p-0"
                            color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-8">
                  <div class="columns is-multiline">
                    <div class="column is-2"></div>
                    <div class="column is-6">
                      <h1 class="emr">Berpegangan dengan perabotan</h1>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.skringJatuhBerpeganganDenganPerabotanYa" class="p-0"
                            color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.skringJatuhBerpeganganDenganPerabotanTidak" class="p-0"
                            color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-8">
                  <div class="columns is-multiline">
                    <div class="column is-2"></div>
                    <div class="column is-6">
                      <h1 class="emr">Infus</h1>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.skringJatuhInfusYa" class="p-0"
                            color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.skringJatuhInfusTidak" class="p-0"
                            color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-2">
                <h5>Nyeri dan Kenyamanan :</h5>
              </div>
              <div class="column is-2" v-for="(data, i) in nyerikeamanan">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.nyerikeamanan" :true-value="data.value" :label="data.label"
                      class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
            </div>
            <div v-if="input.nyerikeamananTidakNormal">
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-4">
                  <div class="column is-12">
                    <table border="2">
                      <tbody>
                        <tr style="border: none;">
                            <td>
                              <div>
                                <div class="column is-12">
                                  <VField>
                                    <VControl raw subcontrol>
                                      <VCheckbox v-model="input.nyerikeamananTidakAdaNyeri" class="p-0" 
                                        color="primary" label="0 -1 = Tidak Ada Nyeri" circle />
                                    </VControl>
                                  </VField>
                                </div>
                              </div>
                            </td>
                        </tr>
                        <tr style="border: none;">
                            <td>
                              <div>
                                <div class="column is-12">
                                  <VField>
                                    <VControl raw subcontrol>
                                      <VCheckbox v-model="input.nyerikeamananSedikitNyeri" class="p-0" 
                                        color="primary" label="2 - 3 = Sedikit Nyeri" circle />
                                    </VControl>
                                  </VField>
                                </div>
                              </div>
                            </td>
                        </tr>
                        <tr style="border: none;">
                            <td>
                              <div>
                                <div class="column is-12">
                                  <VField>
                                    <VControl raw subcontrol>
                                      <VCheckbox v-model="input.nyerikeamananCukupNyeri" class="p-0" 
                                        color="primary" label="4 - 5 = Cukup Nyeri" circle />
                                    </VControl>
                                  </VField>
                                </div>
                              </div>
                            </td>
                        </tr>
                        <tr style="border: none;">
                            <td>
                              <div>
                                <div class="column is-12">
                                  <VField>
                                    <VControl raw subcontrol>
                                      <VCheckbox v-model="input.nyerikeamananLumayanNyeri" class="p-0" 
                                        color="primary" label="6 - 7 = Lumayan Nyeri" circle />
                                    </VControl>
                                  </VField>
                                </div>
                              </div>
                            </td>
                        </tr>
                        <tr style="border: none;">
                            <td>
                              <div>
                                <div class="column is-12">
                                  <VField>
                                    <VControl raw subcontrol>
                                      <VCheckbox v-model="input.nyerikeamananSangatNyeri" class="p-0" 
                                        color="primary" label="8 - 9 = Sangat Nyeri" circle />
                                    </VControl>
                                  </VField>
                                </div>
                              </div>
                            </td>
                        </tr>
                        <tr style="border: none;">
                            <td>
                              <div>
                                <div class="column is-12">
                                  <VField>
                                    <VControl raw subcontrol>
                                      <VCheckbox v-model="input.nyerikeamananAmatSangatNyeri" class="p-0" 
                                        color="primary" label="10 = Amat Sangat Nyeri" circle />
                                    </VControl>
                                  </VField>
                                </div>
                              </div>
                            </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="column is-6">
                  <div class="columns is-multiline">
                    <div class="column is-4">
                      <VField label="P">
                        <VControl>
                          <VInput type="text" v-model="input.nyerikeamananP" placeholder="P" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-4">
                      <VField label="Q">
                        <VControl>
                          <VInput type="text" v-model="input.nyerikeamananQ" placeholder="Q" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-4">
                      <VField label="R">
                        <VControl>
                          <VInput type="text" v-model="input.nyerikeamananR" placeholder="R" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-4">
                      <VField label="S">
                        <VControl>
                          <VInput type="text" v-model="input.nyerikeamananS" placeholder="S" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-4">
                      <VField label="T">
                        <VControl>
                          <VInput type="text" v-model="input.nyerikeamananT" placeholder="T" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-6">
                      <h2 style="color: #849aa5;">Nyeri Pada Pasien Yang Tidak Kompeten Dlaam Subyektif :</h2>
                    </div>
                    <div class="column is-6">
                      <VField>
                        <VControl>
                          <VInput type="text" v-model="input.nyerikeamananNyeriPadaPasienTidakKompeten" placeholder="Nyeri Pada Pasien Yang Tidak Kompeten Dlaam Subyektif" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-6">
                      <h2 style="color: #849aa5;">Rasa Tidak Nyaman :</h2>
                    </div>
                    <div class="column is-6">
                      <VField>
                        <VControl>
                          <VInput type="text" v-model="input.nyerikeamananRasaTidakNayam" placeholder="Rasa Tidak Nyaman" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-2">
                <h5>Penyuluhan dan Pembelajaran :</h5>
              </div>
              <div class="column is-2" v-for="(data, i) in kebutuhanBelajar">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.kebutuhanBelajar" :true-value="data.value" :label="data.label"
                      class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
            </div>
            <div v-if="input.kebutuhanBelajar == 'kebutuhanBelajarTidakNormal'">
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-8">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.kebutuhanBelajarHambatanPengetahuanFisik"  label="Hambatan Pengetahuan Fisik" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-8">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.kebutuhanBelajarHambatanMotivasi"  label="Hambatan Motivasi" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.kebutuhanBelajarBahasa"  label="Hambatan Bahasa" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-4">
                  <h2 style="color: #849aa5;">Edukasi Keperawatan Yang Dibutuhkan</h2>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.kebutuhanBelajarEdukasiYgDibutuhkan" placeholder="..." />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-2">
                <h5>Kebersihan Diri :</h5>
              </div>
              <div class="column is-2" v-for="(data, i) in kebersihanDiri">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.kebersihanDiri" :true-value="data.value" :label="data.label"
                      class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
            </div>
            <div v-if="input.kebersihanDiri == 'kebersihanDiriTidakNormal'">
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-3">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.kebersihanDiriRambit"  label="Rambut" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.kebersihanDiriGenetalia"  label="Genetalia" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-3">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.kebersihanDiriGigiMulut"  label="Gigi Mulut" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.kebersihanDiriLainlain"  label="Lain-lain" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="input.kebersihanDiriLainlain">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.kebersihanDiriLainlainKet" placeholder="..." />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-8">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.kebersihanDiriMandi"  label="Kebutuhan Mandi" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-2">
                <h5>Keamanan dan Proteksi :</h5>
              </div>
              <div class="column is-2" v-for="(data, i) in proteksiKeamanan">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.proteksiKeamanan" :true-value="data.value" :label="data.label"
                      class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
            </div>
            <div v-if="input.proteksiKeamanan == 'proteksiKeamananTidakNormal'">
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-3">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.proteksiKeamananKetidakseimbangan"  label="Ketidakseimbangan Termoregulasi" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.proteksiKeamananPruiritus"  label="Pruiritus" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.proteksiKeamananPendengaran"  label="Pendengaran" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-3">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.proteksiKeamananKerusakanIntegumen"  label="Kerusakan Integumen" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.proteksiKeamananHipertemia"  label="Hipertemia" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.proteksiKeamananPenglihatan"  label="Penglihatan" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-3">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.proteksiKeamananHipotermia"  label="Hipotermia" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.proteksiKeamananLuka"  label="Luka" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.proteksiKeamananPotensiCidera"  label="Potensi Cidera" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2"></div>
                <div class="column is-2"></div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.proteksiKeamananLainlain"  label="Lain-lain" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="input.proteksiKeamananLainlain">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.proteksiKeamananLainlainKet" placeholder="..." />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="Skrining Gizi" :toggleable="true">
          <h1 style="font-weight: bold;">Berdasarkan Mainutrition Screening Tooll (MST)</h1>
          <div class="column is-12">
            <div class="columns is-multiline px-5" style="border-bottom: 1px solid;">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-10">
                    <h1 class="emr">Parameter</h1>
                  </div>
                  <div class="column is-2">
                    <h1 class="emr">Nilai</h1>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-12">
            <div class="columns is-multiline px-3">
              <div class="column is-12" v-for="(data, i) in skriningGizi">
                <span class="label-pengkajian"> {{ data.label }}</span>
                <div class="px-3">
                  <div class=" px-1" v-for="(dataDetail, i) in data.children">
                    <div class="columns is-multiline">
                      <div class="column is-8">
                        <span class="label-pengkajian"> {{ dataDetail.label }}</span>
                      </div>
                      <div class="column is-2">
                        <VField v-if="dataDetail.children == null">
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input[dataDetail.model]" :true-value="dataDetail.value"
                              :label="dataDetail.text" class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-2">
                        {{ dataDetail.value }}
                      </div>
                    </div>
                    <div class="px-1" v-if="dataDetail.children">
                      <div class="columns is-multiline px-1" v-for="(dataDetailChildren, i) in dataDetail.children">
                        <div class="column is-8">
                        </div>
                        <div class="column is-2">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input[dataDetailChildren.model]" :true-value="dataDetailChildren.value"
                                :label="dataDetailChildren.label" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-2">
                          {{ dataDetailChildren.value }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="column is-10">
                <div class="is-pulled-right">
                  <span style="font-weight: bold;font-size:15px;" class="label-pengkajian mt-3">Total
                    Skor</span>
                  <VField class="pt-3">
                    <VControl>
                      <VInput type="text" v-model="input.totalSkor" disabled />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="column is-12">
                <span class="label-pengkajian"> 3. Pasien dengan diagnosis khusus</span>
                <div class="columns is-multiline p-3">
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.adaDiagnosaKhusus" true-value="Ya" label="Ya" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.adaDiagnosaKhusus" true-value="Tidak" label="Tidak" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline px-3">
                  <div class="column is-2" v-if="input.adaDiagnosaKhusus == 'Ya'" v-for="(data, index) in diagnosaKhusus">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input[data.model]" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12" v-if="input.diagnosaKhusus == 'lain_lain'">
                    <VField label="Diagnosa Lain">
                      <VTextarea rows="2" placeholder="Diagnosa Lain......" v-model="input.diagnosaLain"></VTextarea>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline px-3">
                  <div class="column is-12">
                    <p style="font-weight: bold;font-size:12px;">(Bila skor ≥ 2 dan atau pasien dengan diagnosi s/ kondisi
                      khusus
                      dilaporkan ke dokter
                      pemeriksa)</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="Status Fungsional" :toggleable="true">
          <div class="columns is-multiline px-3">
            <div class="column is-3" v-for="(data, index) in statusFungsional">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[data.model]" :true-value="data.value" :label="data.label" class="p-0"
                    color="primary" square />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Dilaporkan Pada Dokter">
                <VDatePicker v-model="input.tanggalWaktuDilaporkanPadaDokter" mode="dateTime" style="width: 100%"
                  trim-weeks :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Waktu pelaporan ke dokter" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="Skrining Resiko Jatuh / Cidera dan Nyeri" :toggleable="true">
          <h1 style="font-weight: bold;">SKRINING RISIKO CEDERA / JATUH :</h1>
          <div class="columns is-multiline px-3 mt-5">
            <div class="column is-12" v-for="(data, index) in skriningResikoJatuh">
              <span class="label-pengkajian">{{ data.title }}</span>
              <div class="columns is-multiline mt-3 px-3">
                <div class="column is-3" v-for="(detail, index) in data.value">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input[detail.model]" :true-value="detail.value" :label="detail.subTitle"
                        class="p-0" color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12">
              <span class="label-pengkajian">Diberitahukan ke dokter</span>
              <div class="columns  is-flex is-multiline mt-3 px-3">
                <div class="column is-3">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.diberitahuanKeDokter" true-value="ya" label="Ya" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6" v-if="input.diberitahuanKeDokter == 'ya'">
                  <VField label="Dilaporkan Pada Dokter">
                    <VDatePicker v-model="input.tanggalWaktuCederaDiberitahuanPadaDokter" mode="dateTime"
                      style="width: 100%" trim-weeks :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" placeholder="Tanggal Waktu Kunjungan" v-on="inputEvents" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.diberitahuanKeDokter" true-value="tidak" label="Tidak" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
          <h1 style="font-weight: bold;">SKRINING NYERI :</h1>
          <div class="columns is-multiline px-3 mt-5">
            <div class="column is-12" v-for="(data, index) in skriningNyeri">
              <span class="label-pengkajian">{{ data.label }}</span>
              <div class="columns is-flex is-multiline mt-3 px-3">
                <div class="column is-3" v-for="(detail, index2) in data.children">
                  <VField v-if="detail.type == 'checkbox'">
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input[detail.model]" :true-value="detail.value" :label="detail.label"
                        class="p-0" color="primary" square />
                    </VControl>
                  </VField>
                  <div class="column" v-if="detail.type == 'date'">
                    <VField label="Dilaporkan Pada Dokter">
                      <VDatePicker v-model="input.tanggalWaktuNyeriDiberitahuanPadaDokter" mode="dateTime"
                        style="width: 100%" trim-weeks :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                          <VField>
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" placeholder="Tanggal Waktu Kunjungan" v-on="inputEvents" />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </VField>
                  </div>
                  <VField v-if="detail.type == 'text'" :label="detail.label">
                    <VControl>
                      <VInput type="text" class="input" :placeholder="detail.label" v-model="input[detail.model]" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="Detail keperawatan" :toggleable="true">
          <div class="column p-5">
            <span style="font-size:11pt;font-weight:bold">Daftar Masalah Keperawatan</span>
            <div class="mt-1">
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VField class="cis-rounded-select_Z  is-autocomplete-select">
                    <VLabel class="required-field">Diagnosa Keperawatan</VLabel>
                    <VControl icon="feather:search">
                      <Multiselect mode="single" v-model="input.diagnosaKeperawatn" :options="d_DiagnosaKeperawatan"
                        placeholder="Pilih data..." :searchable="true" optionLabel="label" :loading="isLoadingDropdown"
                        @select="changeDiagnosaKeperawatan(input.diagnosaKeperawatn)" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">

                  <VField class="cis-rounded-select_Z  is-autocomplete-select">
                    <VLabel class="required-field">Tujuan Keperawatan</VLabel>
                    <VControl icon="feather:search">
                      <Multiselect mode="single" v-model="input.tujuanKeperawatan" :options="d_TujuanKeperawatan"
                        placeholder="Pilih data..." :searchable="true" :loading="isLoadingDropdown"
                        @select="changeTujuanKeperawatan(input.tujuanKeperawatan)" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField class="cis-rounded-select_Z  is-autocomplete-select">
                    <VLabel class="required-field">Intervensi Keperawatan</VLabel>
                    <VControl icon="feather:search">
                      <Multiselect mode="single" v-model="input.intervensiKeperawatan" :options="d_IntervensiKeperawatan"
                        placeholder="Pilih data..." :searchable="true" :loading="isLoadingDropdown"
                        @select="changeIntervensiKeperawatan(input.intervensiKeperawatan)" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField class="cis-rounded-select_Z  is-autocomplete-select">
                    <VLabel class="required-field">Implementasi Keperawatan</VLabel>
                    <VControl icon="feather:search">
                      <Multiselect mode="single" v-model="input.implementasiKeperawatan" :loading="isLoadingDropdown"
                        :options="d_ImplemtasiKeperawatan" placeholder="Pilih data..." :searchable="true" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-4" style="margin-left: auto;">
        <VCard>
          <h1 style="font-weight: bold;"> Cirebon, Tanggal </h1>
          <VField>
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
          <div style="text-align: center">
            <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
            <img v-if="input.petugasPerawat"
              :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.petugasPerawat ? input.petugasPerawat.label : '-')">
          </div>
          <div>
            <h1 class="p-0" style="font-weight: bold;">Petugas Perawat</h1>
            <VField>
              <VControl class="prime-auto">
                <AutoComplete v-model="input.petugasPerawat" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Pegawai..." class="mt-2" @item-select="setTandaTangan($event)" />
              </VControl>
            </VField>
          </div>
        </VCard>
      </div>
      <div class="column is-12">
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
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/pengkajian-awal-pj'
import { async } from '@firebase/util'
import MultiSelect from 'primevue/multiselect';
import FloatingButtonSimpan from './float-button-save-pengkajian-rajal.vue'
import FloatingButtonCetak from './float-button-cetak-pengkajian-keperawatan.vue';




let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let hubunganPasien = ref(EMR.hubunganPasien())
let spritualPasien = ref(EMR.spritualPasien())
let statusPsikologisPasien = ref(EMR.statusPsikologisPasien())
let sirkulasi = ref(EMR.sirkulasi())
let respirasi = ref(EMR.respirasi())
let nutrisi = ref(EMR.nutrisi())
let eliminasi = ref(EMR.eliminasi())
let neurosensori = ref(EMR.neurosensori())
let aktivitasTidur = ref(EMR.aktivitasTidur())
let nyerikeamanan = ref(EMR.nyerikeamanan())
let kebutuhanBelajar = ref(EMR.kebutuhanBelajar())
let kebersihanDiri = ref(EMR.kebersihanDiri())
let proteksiKeamanan = ref(EMR.proteksiKeamanan())
let vitalSign = ref(EMR.vitalSign())
let skriningGizi = ref(EMR.skriningGizi())
let diagnosaKhusus = ref(EMR.diagnosaKhusus())
let statusFungsional = ref(EMR.statusFungsional())
let skriningResikoJatuh = ref(EMR.skriningResikoJatuh())
let skriningNyeri = ref(EMR.skriningNyeri())
let detailKeperawatan = ref(EMR.detailKeperawatan())

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
const d_hari = [
  { value: 'Senin', label: 'Senin' },
  { value: 'Selasa', label: 'Selasa' },
  { value: 'Rabu', label: 'Rabu' },
  { value: 'Kamis', label: 'Kamis' },
  { value: 'Jumat', label: 'Jumat' },
  { value: 'Sabtu', label: 'Sabtu' },
  { value: 'Minggu', label: 'Minggu' },
]
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value >= 20 })
const isLoading: any = ref(false)
const isLoadingDropdown: any = ref(false)
const isLoadingVitalSign: any = ref(false)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const d_Ruangan: any = ref([])
const d_DiagnosaKeperawatan: any = ref([])
const d_TujuanKeperawatan: any = ref([])
const d_IntervensiKeperawatan: any = ref([])
const d_ImplemtasiKeperawatan: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('pengkajianAwalRawatJalanKeperawatan') //table mongodb
const NOREC_EMRPASIEN: any = ref(norec_emr ? norec_emr : '')
const input: any = ref({
  diagnosaKeper: [{ no: 1 }],
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = async () => {
  // if (NOREC_EMRPASIEN.value == '') return
  await useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0]
        if (response[0].tandaTanganPerawat) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganPerawat)
        }
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        if (response[0].diagnosaKeperawatn) {
          changeDiagnosaKeperawatan(response[0].diagnosaKeperawatn)
          input.value.tujuanKeperawatan = response[0].tujuanKeperawatan
        }
        if (response[0].tujuanKeperawatan) {
          changeTujuanKeperawatan(response[0].tujuanKeperawatan)
          input.value.intervensiKeperawatan = response[0].intervensiKeperawatan
        }
        if (response[0].intervensiKeperawatan) {
          changeIntervensiKeperawatan(response[0].intervensiKeperawatan)
          input.value.implementasiKeperawatan = response[0].implementasiKeperawatan
        }
      }
    })
}

const print = async () => {
  H.printBlade(`emr/cetak/${COLLECTION.value}?pdf=true&emrpasienfk=${NOREC_EMRPASIEN.value}`)
}

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
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
// const fetchDiagnosaKeperawatan = async (filter: any) => {
//   await useApi().get(`/emr/get-dropdown-diagnosa-keperawatan?search=${filter.query}`).then((response) => {
//     d_DiagnosaKeperawatan.value = response
//   })
// }

async function fetchDiagnosaKeperawatan() {
  await useApi().get(`/emr/list-diagnosa-keperawatan`).then((response) => {
    d_DiagnosaKeperawatan.value = response.diagnosaKeperawatan.map((e: any) => {
      return { value: e.kodediagnosakep, label: e.diagnosakep, default: e }
    })
  })
}


const addNewDiagnosaKeper = () => {
  input.value.diagnosaKeper.push({
    no: input.value.diagnosaKeper[input.value.diagnosaKeper.length - 1].no + 1,
  });
}
const removeItemDiagnosaKeper = (index: any) => {
  input.value.diagnosaKeper.splice(index, 1)
}
async function changeDiagnosaKeperawatan(event: any) {
  let query = event == '' ? '' : event;
  isLoadingDropdown.value = true;
  await useApi().get(`/emr/list-tujuan-keperawatan?objectdiagnosakepfk=${query}`).then((response: any) => {
    d_TujuanKeperawatan.value = response.tujuanPerawat.map((e: any) => {
      return { value: e.id, label: e.tujuankep, default: e }
    })
    isLoadingDropdown.value = false;
  })
}

async function changeTujuanKeperawatan(event: any) {
  let query = event == '' ? '' : event;
  isLoadingDropdown.value = true;
  await useApi().get(`/emr/list-intervensi?objecttujuankeperawatan=${query}`).then((response: any) => {
    d_IntervensiKeperawatan.value = response.intervensi.map((e: any) => {
      return { value: e.id, label: e.name, default: e }
    })
    isLoadingDropdown.value = false;
  })
}
async function changeIntervensiKeperawatan(event: any) {
  let query = event == '' ? '' : event;
  isLoadingDropdown.value = true;
  await useApi().get(`/emr/list-implementasi?objectintervensifk=${query}`).then((response: any) => {
    d_ImplemtasiKeperawatan.value = response.implementasi.map((e: any) => {
      return { value: e.id, label: e.name, default: e }
    })
    isLoadingDropdown.value = false;
  })
}


watch(() => [
  input.value.turunBeratBadan,
  input.value.tidakAdaTurunBeratBadan,
  input.value.asupanMakan,
], () => {


  let poin1 = input.value.turunBeratBadan ? parseInt(input.value.turunBeratBadan) : 0
  let poin2 = input.value.tidakAdaTurunBeratBadan ? parseInt(input.value.tidakAdaTurunBeratBadan) : 0
  let poin3 = input.value.asupanMakan ? parseInt(input.value.asupanMakan) : 0

  const total = poin1 + poin2 + poin3
  input.value.totalSkor = total
})
const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.tanggalWaktuRegistrasi = props.registrasi.tglregistrasi
  input.value.tanggalWaktuKunjuangan = new Date()
  input.value.tglPembuatan = new Date()
  input.value.umur = props.pasien.umur
  input.value.namaruangan = props.registrasi.namaruangan
  input.value.petugasPerawat = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }

  isLoadingVitalSign.value = true;
  await useApi().get(
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=VitalSign" + "&field=beratBadan,tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi"
  ).then((response) => {
    if(response != null){
      input.value.beratBadan = response.beratBadan
      input.value.tinggiBadan = response.tinggiBadan
      input.value.IMT = response.IMT
      input.value.lingkarPerut = response.lingkarPerut
      input.value.tekananDarah = response.tekananDarah
      input.value.pernapasan = response.pernapasan
      input.value.suhu = response.suhu
      input.value.nadi = response.nadi
      input.value.nutrisiBB = response.beratBadan
      input.value.nutrisiTB = response.tinggiBadan
    }
    isLoadingVitalSign.value =false;
  })
}

setView()
setAutoFill()
loadRiwayat()
fetchDiagnosaKeperawatan()
</script>

<style lang="scss">

.btn-cppt {
  -webkit-border-radius: 20px;
  -moz-border-radius: 20px;
  border-radius: 20px;
  cursor: pointer;
  font-size: 12px;
  font-weight: bolder;
  color: #cd9424;
  padding: 7px 20px;
  min-width: 90px;
  text-align: center;
  position: fixed;
  -webkit-transition-duration: 0.25s;
  -webkit-transition-duration: 0.25s;
  -moz-transition-duration: 0.25s;
  -o-transition-duration: 0.25s;
  transition-duration: 0.25s;
  -webkit-transition-property: background-color, -webkit-box-shadow;
  right: 0;
  -webkit-transition-property: background-color, box-shadow;
  -o-transition-property: background-color, box-shadow;
  transition-property: background-color, box-shadow;
  z-index: 2;
}
.btn-border-cppt {
  border: 2px solid #cd9424;
}

.btn-full-cppt {
  background-color: #a9ebbd;
}
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

.label-pengkajian {
  font-weight: 400;
}

.table-fkprj,
.tr-fkprj,
.th-fkprj,
.td-fkprj {
  border: 1.6px solid black !important;
}

.th-fkprj,
.td-fkprj {
  padding: 8px !important;
}
</style>

