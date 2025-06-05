<template>
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


  <div class="columns is-multiline p-2">
    <div class="column is-12">
      <VCard>
        <div class="column is-12">
          <Fieldset :toggleable="true" legend="PERSIAPAN EDUKASI">
            <div class="column is-12 p-2">
              <div class="columns is-multiline">
                <div class="column is-12" v-for="(data, i) in informasi">
                  <div class="is-flex">
                    <div class="column is-2">
                      <span>{{ data.label }} :</span>
                    </div>
                    <div class="column is-10">
                      <div class="columns is-multiline">
                        <div class="column is-3" v-for="(detail, d) in data.children">
                          <VField style="padding:0px;" v-if="detail.type == 'checkbox'">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input[detail.model]" class="pt-1 pb-1 " :true-value="detail.value"
                                :label="detail.label" color="primary" square />
                            </VControl>
                          </VField>
                          <VField style="padding:0px 10px;" v-if="detail.type == 'input'">
                            <VControl>
                              <VInput v-model="detail.tindakan" class="input" :placeholder="detail.placeholder">
                              </VInput>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="column is-12">
                  <div class="column is-12">
                    <span>Hamabatan Edukasi :</span>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-3">
                      <VField style="padding:0px;">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.HambatanEdukasiTidakAda" class="pt-1 pb-1 "
                            label="Tidak Ada" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <VField style="padding:0px;">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.HambatanEdukasiPenglihatanTerganggu" class="pt-1 pb-1 "
                            label="Penglihatan Terganggu" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <VField style="padding:0px;">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.HambatanEdukasiBahasa" class="pt-1 pb-1 "
                            label="Bahasa" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <VField style="padding:0px;">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.HambatanEdukasiKognitifTerbatas" class="pt-1 pb-1 "
                            label="Kognitif Terbatas" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <VField style="padding:0px;">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.HambatanEdukasiMotivasiKurang" class="pt-1 pb-1 "
                            label="Motivasi Kurang" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <VField style="padding:0px;">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.HambatanEdukasiBudayaAgama" class="pt-1 pb-1 "
                            label="Budaya/Agama/Spiritual" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <VField style="padding:0px;">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.HambatanEdukasaiEmosional" class="pt-1 pb-1 "
                            label="Emosional" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <VField style="padding:0px;">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.HambatanEdukasaiPendengaranTerganggu" class="pt-1 pb-1 "
                            label="Pendengaran Terganggu" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <VField style="padding:0px;">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.HambatanEdukasaiGangguanBicara" class="pt-1 pb-1 "
                            label="Gangguan Bicara" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <VField style="padding:0px;">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.HambatanEdukasaiFisikLemah" class="pt-1 pb-1 "
                            label="Fisik Lemah" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <VField style="padding:0px;">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.HambatanEdukasaiLainlain" class="pt-1 pb-1 "
                            label="Lain-Lain" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </Fieldset>
        </div>
        <div class="column is-12">
          <Fieldset :toggleable="true" legend="KEBUTUHAN EDUKASI">
            <div class="column is-12 p-2">
              <div class="columns is-multiline">
                <div class="column is-12">
                  <div class="column is-12">
                    <span>Kebutuhan Edukasi :</span>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <VField style="padding:0px;">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.kebutuhanEdukasiDiagnosis" class="pt-1 pb-1 "
                            label="Diagnosis, tanda gejala, tatalaksana" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-12">
                      <VField style="padding:0px;">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.kebutuhanEdukasiProsedur" class="pt-1 pb-1 "
                            label="Prosedur diagnostik tertentu" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-12">
                      <VField style="padding:0px;">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.kebutuhanEdukasiManfaat" class="pt-1 pb-1 "
                            label="Manfaat, efek samping, interaksi obat dan makanan" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-12">
                      <VField style="padding:0px;">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.kebutuhanEdukasiProgamDiet" class="pt-1 pb-1 "
                            label="Program diet dan nutrisi" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-12">
                      <VField style="padding:0px;">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.kebutuhanEdukasiManajemen" class="pt-1 pb-1 "
                            label="Manajemen nyeri, cuci tangan, penggunaan APD" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-12">
                      <VField style="padding:0px;">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.kebutuhanEdukasiPenggunaanALat" class="pt-1 pb-1 "
                            label="Penggunaan Alat kesehatan" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-12">
                      <VField style="padding:0px;">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.kebutuhanEdukasiProsedur" class="pt-1 pb-1 "
                            label="Prosedur perawatan (spesifik)" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-12">
                      <VField style="padding:0px;">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.kebutuhanEdukasiTeknikRehabilitasi" class="pt-1 pb-1 "
                            label="Teknik Rehabilitasi " color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-12">
                      <VField style="padding:0px;">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.kebutuhanEdukasiWaktuKontrol" class="pt-1 pb-1 "
                            label="Waktu kontrol dan penggunaan obat di rumah" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </Fieldset>
        </div>
        <div class="column is-12" style="width:100%; overflow-x: scroll;">
          <Fieldset :toggleable="true" legend="DETAIL EDUKASI">
            <div class="column is-12 p-2">
              <div class="columns is-multiline">
                <div class="column">
                  <table class="tg">
                    <thead>
                      <tr>
                        <th class="tg-0lax text-center" rowspan="2" colspan="2">KEBUTUHAN EDUKASI : TOPIK EDUKASI</th>
                        <th class="tg-0lax text-center" colspan="2">TERLAKSANA</th>
                        <th class="tg-0lax text-center" rowspan="2">TANGGAL EDUKASI</th>
                        <th class="tg-0lax text-center" colspan="2">SASARAN(PASIEN/KELUARGA/LAIN-LAIN)</th>
                        <th class="tg-0lax text-center" rowspan="2">TINGKAT PEMAHAMAN AWAL</th>
                        <th class="tg-0lax text-center" rowspan="2">METODE EDUKASI</th>
                        <th class="tg-0lax text-center" rowspan="2">MATERIAL EDUKASI</th>
                        <th class="tg-0lax text-center" colspan="2">EDUKATOR</th>
                        <th class="tg-0lax text-center" rowspan="2">EVALUASI</th>
                        <th class="tg-0lax text-center" rowspan="2">TANGGAL RE-EDUKASI</th>
                      </tr>
                      <tr>
                        <th class="tg-0lax">YA</th>
                        <th class="tg-0lax">TIDAK</th>
                        <th class="tg-0lax">Nama</th>
                        <th class="tg-0lax">TTD</th>
                        <th class="tg-0lax">Nama</th>
                        <th class="tg-0lax">TDD</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td width="230px">
                          <span>1. Hak dan kewajiban pasien dan keluarga</span>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.hakKewajibanPasienTrue" class="pt-1 pb-1 " color="primary"
                                  square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.hakKewajibanPasienTerlaksanaYa" class="pt-1 pb-1 "
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.hakKewajibanPasienTerlaksanaTidak" class="pt-1 pb-1 "
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.hakKewajibanPasienTglEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                        <td width="180px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.hakKewajibanPasienNamaSasaran" class="input"
                                placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'edukator_0'" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.hakKewajibanPasienPemahamanSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.hakKewajibanPasienPemahamanEdukasiUlang" class="pt-1 pb-1 "
                                  label="Edukasi Ulang" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.hakKewajibanPasienPemahamanHalBaru" class="pt-1 pb-1 "
                                  label="Hal Baru" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.hakKewajibanPasienMetodEdukasiIndvidu" class="pt-1 pb-1 "
                                  label="Individu" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.hakKewajibanPasienMetodEdukasikelompok" class="pt-1 pb-1 "
                                  label="Kelompok" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.hakKewajibanPasienMetodEdukasiCeramah" class="pt-1 pb-1 "
                                  label="Ceramah" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.hakKewajibanPasienMetodEdukasiDemonstrasi" class="pt-1 pb-1 "
                                  label="Demonstrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.hakKewajibanPasienMaterialEdukasiLeaflet" class="pt-1 pb-1 "
                                  label="Leaflet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.hakKewajibanPasienMaterialEdukasiBooklet" class="pt-1 pb-1 "
                                  label="Booklet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.hakKewajibanPasienMaterialEdukasiLembarBalik" class="pt-1 pb-1 "
                                  label="Lembar Balik" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.hakKewajibanPasienMaterialEdukasiAudioVisual" class="pt-1 pb-1 "
                                  label="Audio Visual" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.hakKewajibanPasienNamaEdukator" class="input"
                                placeholder="Nama Edukator...">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.hakKewajibanPasienNamaEdukator" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Edukator..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'hakKewajibanPasienEdukatorTTD'" :width="'150'" :height="'150'"
                            class="dek" /> -->
                          <img v-if="input.hakKewajibanPasienNamaEdukator"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.hakKewajibanPasienNamaEdukator ? input.hakKewajibanPasienNamaEdukator.label : '-')">
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.hakKewajibanPasienEvaluasiReedukasi" class="pt-1 pb-1 "
                                  label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.hakKewajibanPasienEvaluasiRedemonstrasi" class="pt-1 pb-1 "
                                  label="Re-Demostrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.hakKewajibanPasienEvaluasiSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.hakKewajibanPasienTglReEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                      </tr>

                      <!-- 2 -->

                      <tr>
                        <td width="230px">
                          <span>2. Pengertian penyakit dan dasr diagnosa</span>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.pengertianPenyakitTrue" class="pt-1 pb-1 " color="primary"
                                  square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.pengertianPenyakitTerlaksanaYa" class="pt-1 pb-1 "
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.pengertianPenyakitTerlaksanaTidak" class="pt-1 pb-1 "
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.pengertianPenyakitTglEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                        <td width="180px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.pengertianPenyakitNamaSasaran" class="input"
                                placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'edukator_1'" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.pengertianPenyakitPemahamanSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.pengertianPenyakitPemahamanEdukasiUlang" class="pt-1 pb-1 "
                                  label="Edukasi Ulang" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.pengertianPenyakitPemahamanHalBaru" class="pt-1 pb-1 "
                                  label="Hal Baru" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.pengertianPenyakitMetodEdukasiIndvidu" class="pt-1 pb-1 "
                                  label="Individu" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.pengertianPenyakitMetodEdukasikelompok" class="pt-1 pb-1 "
                                  label="Kelompok" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.pengertianPenyakitMetodEdukasiCeramah" class="pt-1 pb-1 "
                                  label="Ceramah" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.pengertianPenyakitMetodEdukasiDemonstrasi" class="pt-1 pb-1 "
                                  label="Demonstrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.pengertianPenyakitMaterialEdukasiLeaflet" class="pt-1 pb-1 "
                                  label="Leaflet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.pengertianPenyakitMaterialEdukasiBooklet" class="pt-1 pb-1 "
                                  label="Booklet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.pengertianPenyakitMaterialEdukasiLembarBalik" class="pt-1 pb-1 "
                                  label="Lembar Balik" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.pengertianPenyakitMaterialEdukasiAudioVisual" class="pt-1 pb-1 "
                                  label="Audio Visual" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.pengertianPenyakiNamaEdukator" class="input"
                                placeholder="Nama Edukator...">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.pengertianPenyakiNamaEdukator" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Edukator..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'pengertianPenyakitEdukatorTTD'" :width="'150'" :height="'150'"
                            class="dek" /> -->
                          <img v-if="input.pengertianPenyakiNamaEdukator"
                              :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.pengertianPenyakiNamaEdukator ? input.pengertianPenyakiNamaEdukator.label : '-')">
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.pengertianPenyakitEvaluasiReedukasi" class="pt-1 pb-1 "
                                  label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.pengertianPenyakitEvaluasiRedemonstrasi" class="pt-1 pb-1 "
                                  label="Re-Demostrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.pengertianPenyakitEvaluasiSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.pengertianPenyakitTglReEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                      </tr>

                      <!-- 3 -->

                      <tr>
                        <td width="230px">
                          <span>3. Masalah Kinis</span>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.masalahKlinisTrue" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.masalahKlinisTerlaksanaYa" class="pt-1 pb-1 " color="primary"
                                  square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.masalahKlinisTerlaksanaTidak" class="pt-1 pb-1 " color="primary"
                                  square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.masalahKlinisTglEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                        <td width="180px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.masalahKlinisNamaSasaran" class="input"
                                placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'edukator_2'" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.masalahKlinisPemahamanSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.masalahKlinisPemahamanEdukasiUlang" class="pt-1 pb-1 "
                                  label="Edukasi Ulang" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.masalahKlinisPemahamanHalBaru" class="pt-1 pb-1 "
                                  label="Hal Baru" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.masalahKlinisMetodEdukasiIndvidu" class="pt-1 pb-1 "
                                  label="Individu" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.masalahKlinisMetodEdukasikelompok" class="pt-1 pb-1 "
                                  label="Kelompok" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.masalahKlinisMetodEdukasiCeramah" class="pt-1 pb-1 "
                                  label="Ceramah" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.masalahKlinisMetodEdukasiDemonstrasi" class="pt-1 pb-1 "
                                  label="Demonstrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.masalahKlinisMaterialEdukasiLeaflet" class="pt-1 pb-1 "
                                  label="Leaflet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.masalahKlinisMaterialEdukasiBooklet" class="pt-1 pb-1 "
                                  label="Booklet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.masalahKlinisMaterialEdukasiLembarBalik" class="pt-1 pb-1 "
                                  label="Lembar Balik" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.masalahKlinisMaterialEdukasiAudioVisual" class="pt-1 pb-1 "
                                  label="Audio Visual" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.masalahKlinisNamaEdukator" class="input"
                                placeholder="Nama Edukator...">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.masalahKlinisNamaEdukator" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Edukator..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'masalahKlinistEdukatorTTD'" :width="'150'" :height="'150'"
                            class="dek" /> -->
                          <img v-if="input.masalahKlinisNamaEdukator"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.masalahKlinisNamaEdukator ? input.masalahKlinisNamaEdukator.label : '-')">
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.masalahKlinisEvaluasiReedukasi" class="pt-1 pb-1 "
                                  label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.masalahKlinisEvaluasiRedemonstrasi" class="pt-1 pb-1 "
                                  label="Re-Demostrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.masalahKlinisEvaluasiSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.masalahKlinisTglReEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                      </tr>

                      <!-- 4 -->

                      <tr>
                        <td width="230px">
                          <span>4. Tanda Gejala Penyakit</span>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tandaGejalaPenyakitTrue" class="pt-1 pb-1 " color="primary"
                                  square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tandaGejalaPenyakitYa" class="pt-1 pb-1 " color="primary"
                                  square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tandaGejalaPenyakitTidak" class="pt-1 pb-1 " color="primary"
                                  square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.tandaGejalaPenyakitTglEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                        <td width="180px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.tandaGejalaPenyakitNamaSasaran" class="input"
                                placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'edukator_3'" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tandaGejalaPenyakitPemahamanSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tandaGejalaPenyakitPemahamanEdukasiUlang" class="pt-1 pb-1 "
                                  label="Edukasi Ulang" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tandaGejalaPenyakitPemahamanHalBaru" class="pt-1 pb-1 "
                                  label="Hal Baru" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tandaGejalaPenyakitMetodEdukasiIndvidu" class="pt-1 pb-1 "
                                  label="Individu" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tandaGejalaPenyakitMetodEdukasikelompok" class="pt-1 pb-1 "
                                  label="Kelompok" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tandaGejalaPenyakitMetodEdukasiCeramah" class="pt-1 pb-1 "
                                  label="Ceramah" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tandaGejalaPenyakitMetodEdukasiDemonstrasi" class="pt-1 pb-1 "
                                  label="Demonstrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tandaGejalaPenyakitMaterialEdukasiLeaflet" class="pt-1 pb-1 "
                                  label="Leaflet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tandaGejalaPenyakitMaterialEdukasiBooklet" class="pt-1 pb-1 "
                                  label="Booklet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tandaGejalaPenyakitMaterialEdukasiLembarBalik"
                                  class="pt-1 pb-1 " label="Lembar Balik" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tandaGejalaPenyakitMaterialEdukasiAudioVisual"
                                  class="pt-1 pb-1 " label="Audio Visual" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.tandaGejalaPenyakitNamaEdukator" class="input"
                                placeholder="Nama Edukator...">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.tandaGejalaPenyakitNamaEdukator" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Edukator..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'tandaGejalaPenyakitEdukatorTTD'" :width="'150'" :height="'150'"
                            class="dek" /> -->
                          <img v-if="input.tandaGejalaPenyakitNamaEdukator"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.tandaGejalaPenyakitNamaEdukator ? input.tandaGejalaPenyakitNamaEdukator.label : '-')">
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tandaGejalaPenyakitEvaluasiReedukasi" class="pt-1 pb-1 "
                                  label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tandaGejalaPenyakitEvaluasiRedemonstrasi" class="pt-1 pb-1 "
                                  label="Re-Demostrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tandaGejalaPenyakitEvaluasiSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.tandaGejalaPenyakitTglReEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                      </tr>

                      <!-- 5 -->

                      <tr>
                        <td width="230px">
                          <span>5. Penatalaksanaan Penyakit</span>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penatalaksanaanPenyakiTrue" class="pt-1 pb-1 " color="primary"
                                  square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penatalaksanaanPenyakiYa" class="pt-1 pb-1 " color="primary"
                                  square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penatalaksanaanPenyakiTidak" class="pt-1 pb-1 " color="primary"
                                  square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.penatalaksanaanPenyakiTglEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                        <td width="180px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.penatalaksanaanPenyakiNamaSasaran" class="input"
                                placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'edukator_4'" :width="'150'" :height="'150'"
                            class="dek" />
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penatalaksanaanPenyakiPemahamanSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penatalaksanaanPenyakiPemahamanEdukasiUlang" class="pt-1 pb-1 "
                                  label="Edukasi Ulang" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penatalaksanaanPenyakiPemahamanHalBaru" class="pt-1 pb-1 "
                                  label="Hal Baru" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penatalaksanaanPenyakiMetodEdukasiIndvidu" class="pt-1 pb-1 "
                                  label="Individu" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penatalaksanaanPenyakiMetodEdukasikelompok" class="pt-1 pb-1 "
                                  label="Kelompok" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penatalaksanaanPenyakiMetodEdukasiCeramah" class="pt-1 pb-1 "
                                  label="Ceramah" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penatalaksanaanPenyakiMetodEdukasiDemonstrasi"
                                  class="pt-1 pb-1 " label="Demonstrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penatalaksanaanPenyakiMaterialEdukasiLeaflet" class="pt-1 pb-1 "
                                  label="Leaflet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penatalaksanaanPenyakiMaterialEdukasiBooklet" class="pt-1 pb-1 "
                                  label="Booklet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penatalaksanaanPenyakiMaterialEdukasiLembarBalik"
                                  class="pt-1 pb-1 " label="Lembar Balik" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penatalaksanaanPenyakiMaterialEdukasiAudioVisual"
                                  class="pt-1 pb-1 " label="Audio Visual" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.penatalaksanaanPenyakiNamaEdukator" class="input"
                                placeholder="Nama Edukator...">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.penatalaksanaanPenyakiNamaEdukator" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Edukator..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'penatalaksanaanPenyakiEdukatorTTD'" :width="'150'" :height="'150'"
                            class="dek" /> -->
                          <img v-if="input.penatalaksanaanPenyakiNamaEdukator"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.penatalaksanaanPenyakiNamaEdukator ? input.penatalaksanaanPenyakiNamaEdukator.label : '-')">
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penatalaksanaanPenyakiEvaluasiReedukasi" class="pt-1 pb-1 "
                                  label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penatalaksanaanPenyakiEvaluasiRedemonstrasi" class="pt-1 pb-1 "
                                  label="Re-Demostrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penatalaksanaanPenyakiEvaluasiSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.penatalaksanaanPenyakiTglReEdukasi" mode="date"
                              style="width: 100%" trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                      </tr>

                      <!-- 6 -->

                      <tr>
                        <td width="230px">
                          <span>6. Prosedur Diagnostik Tertentu, Bila Ada (Sebutka)</span>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurDiagnostikTrue" class="pt-1 pb-1 " color="primary"
                                  square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurDiagnostikYa" class="pt-1 pb-1 " color="primary"
                                  square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurDiagnostikTidak" class="pt-1 pb-1 " color="primary"
                                  square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.prosedurDiagnostikTglEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                        <td width="180px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.prosedurDiagnostikNamaSasaran" class="input"
                                placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'edukator_5'" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurDiagnostikPemahamanSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurDiagnostikPemahamanEdukasiUlang" class="pt-1 pb-1 "
                                  label="Edukasi Ulang" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurDiagnostikPemahamanHalBaru" class="pt-1 pb-1 "
                                  label="Hal Baru" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurDiagnostikMetodEdukasiIndvidu" class="pt-1 pb-1 "
                                  label="Individu" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurDiagnostikMetodEdukasikelompok" class="pt-1 pb-1 "
                                  label="Kelompok" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurDiagnostikMetodEdukasiCeramah" class="pt-1 pb-1 "
                                  label="Ceramah" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurDiagnostikMetodEdukasiDemonstrasi" class="pt-1 pb-1 "
                                  label="Demonstrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurDiagnostikMaterialEdukasiLeaflet" class="pt-1 pb-1 "
                                  label="Leaflet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurDiagnostikMaterialEdukasiBooklet" class="pt-1 pb-1 "
                                  label="Booklet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurDiagnostikMaterialEdukasiLembarBalik" class="pt-1 pb-1 "
                                  label="Lembar Balik" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurDiagnostikMaterialEdukasiAudioVisual" class="pt-1 pb-1 "
                                  label="Audio Visual" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.prosedurDiagnostikNamaEdukator" class="input"
                                placeholder="Nama Edukator...">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.prosedurDiagnostikNamaEdukator" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Edukator..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'prosedurDiagnostikEdukatorTTD'" :width="'150'" :height="'150'"
                            class="dek" /> -->
                          <img v-if="input.prosedurDiagnostikNamaEdukator"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.prosedurDiagnostikNamaEdukator ? input.prosedurDiagnostikNamaEdukator.label : '-')">
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurDiagnostikEvaluasiReedukasi" class="pt-1 pb-1 "
                                  label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurDiagnostikEvaluasiRedemonstrasi" class="pt-1 pb-1 "
                                  label="Re-Demostrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurDiagnostikEvaluasiSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.prosedurDiagnostikTglReEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                      </tr>

                      <!-- 7 -->

                      <tr>
                        <td width="230px">
                          <span>7. Komplikasi</span>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.komplikasiTrue" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.komplikasiYa" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.komplikasiTidak" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.komplikasiTglEdukasi" mode="date" style="width: 100%" trim-weeks
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
                        </td>
                        <td width="180px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.komplikasiNamaSasaran" class="input" placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'edukator_6'" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.komplikasiPemahamanSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.komplikasiPemahamanEdukasiUlang" class="pt-1 pb-1 "
                                  label="Edukasi Ulang" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.komplikasiPemahamanHalBaru" class="pt-1 pb-1 " label="Hal Baru"
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.komplikasiMetodEdukasiIndvidu" class="pt-1 pb-1 "
                                  label="Individu" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.komplikasiMetodEdukasikelompok" class="pt-1 pb-1 "
                                  label="Kelompok" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.komplikasiMetodEdukasiCeramah" class="pt-1 pb-1 "
                                  label="Ceramah" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.komplikasiMetodEdukasiDemonstrasi" class="pt-1 pb-1 "
                                  label="Demonstrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.komplikasiMaterialEdukasiLeaflet" class="pt-1 pb-1 "
                                  label="Leaflet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.komplikasiMaterialEdukasiBooklet" class="pt-1 pb-1 "
                                  label="Booklet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.komplikasiMaterialEdukasiLembarBalik" class="pt-1 pb-1 "
                                  label="Lembar Balik" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.komplikasiMaterialEdukasiAudioVisual" class="pt-1 pb-1 "
                                  label="Audio Visual" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.komplikasiNamaEdukator" class="input" placeholder="Nama Edukator...">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.komplikasiNamaEdukator" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Edukator..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'komplikasiEdukatorTTD'" :width="'150'" :height="'150'" class="dek" /> -->
                          <img v-if="input.komplikasiNamaEdukator"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.komplikasiNamaEdukator ? input.komplikasiNamaEdukator.label : '-')">
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.komplikasiEvaluasiReedukasi" class="pt-1 pb-1 "
                                  label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.komplikasiEvaluasiRedemonstrasi" class="pt-1 pb-1 "
                                  label="Re-Demostrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.komplikasiEvaluasiSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.komplikasiTglReEdukasi" mode="date" style="width: 100%" trim-weeks
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
                        </td>
                      </tr>

                      <!-- 8 -->

                      <tr>
                        <td width="230px">
                          <span>8. Kemungkinan hasil yang diharapkan/tidak terduga :</span>
                          <div class="column is-12">
                            <VField>
                              <VControl>
                                <VTextarea v-model="input.kemungkinanYangDiahrapkan" rows="1" placeholder=".....">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.kemungkinanHasilTrue" class="pt-1 pb-1 " color="primary"
                                  square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.kemungkinanHasilYa" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.kemungkinanHasilTidak" class="pt-1 pb-1 " color="primary"
                                  square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.kemungkinanHasilTglEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                        <td width="180px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.kemungkinanHasilNamaSasaran" class="input"
                                placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'sasaran_0'" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.kemungkinanHasilPemahamanSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.kemungkinanHasilPemahamanEdukasiUlang" class="pt-1 pb-1 "
                                  label="Edukasi Ulang" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.kemungkinanHasilPemahamanHalBaru" class="pt-1 pb-1 "
                                  label="Hal Baru" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.kemungkinanHasilMetodEdukasiIndvidu" class="pt-1 pb-1 "
                                  label="Individu" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.kemungkinanHasilMetodEdukasikelompok" class="pt-1 pb-1 "
                                  label="Kelompok" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.kemungkinanHasilMetodEdukasiCeramah" class="pt-1 pb-1 "
                                  label="Ceramah" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.kemungkinanHasilMetodEdukasiDemonstrasi" class="pt-1 pb-1 "
                                  label="Demonstrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.kemungkinanHasilMaterialEdukasiLeaflet" class="pt-1 pb-1 "
                                  label="Leaflet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.kemungkinanHasilMaterialEdukasiBooklet" class="pt-1 pb-1 "
                                  label="Booklet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.kemungkinanHasilMaterialEdukasiLembarBalik" class="pt-1 pb-1 "
                                  label="Lembar Balik" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.kemungkinanHasilMaterialEdukasiAudioVisual" class="pt-1 pb-1 "
                                  label="Audio Visual" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.kemungkinanHasilNamaEdukator" class="input"
                                placeholder="Nama Edukator...">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.kemungkinanHasilNamaEdukator" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Edukator..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'kemungkinanHasilEdukatorTTD'" :width="'150'" :height="'150'"
                            class="dek" /> -->
                          <img v-if="input.kemungkinanHasilNamaEdukator"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.kemungkinanHasilNamaEdukator ? input.kemungkinanHasilNamaEdukator.label : '-')">
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.kemungkinanHasilEvaluasiReedukasi" class="pt-1 pb-1 "
                                  label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.kemungkinanHasilEvaluasiRedemonstrasi" class="pt-1 pb-1 "
                                  label="Re-Demostrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.kemungkinanHasilEvaluasiSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.kemungkinanHasilTglReEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                      </tr>

                      <!-- 9 -->

                      <tr>
                        <td width="230px">
                          <span>9. Prognosis :</span>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prognosisTrue" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prognosisYa" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prognosisTidak" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.prognosisTglEdukasi" mode="date" style="width: 100%" trim-weeks
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
                        </td>
                        <td width="180px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.prognosisNamaSasaran" class="input" placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'sasaran_1'" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prognosisPemahamanSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prognosisPemahamanEdukasiUlang" class="pt-1 pb-1 "
                                  label="Edukasi Ulang" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prognosisPemahamanHalBaru" class="pt-1 pb-1 " label="Hal Baru"
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prognosisMetodEdukasiIndvidu" class="pt-1 pb-1 "
                                  label="Individu" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prognosisMetodEdukasikelompok" class="pt-1 pb-1 "
                                  label="Kelompok" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prognosisMetodEdukasiCeramah" class="pt-1 pb-1 " label="Ceramah"
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prognosisMetodEdukasiDemonstrasi" class="pt-1 pb-1 "
                                  label="Demonstrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prognosisMaterialEdukasiLeaflet" class="pt-1 pb-1 "
                                  label="Leaflet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prognosisMaterialEdukasiBooklet" class="pt-1 pb-1 "
                                  label="Booklet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prognosisMaterialEdukasiLembarBalik" class="pt-1 pb-1 "
                                  label="Lembar Balik" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prognosisMaterialEdukasiAudioVisual" class="pt-1 pb-1 "
                                  label="Audio Visual" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.prognosisNamaEdukator" class="input" placeholder="Nama Edukator...">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.prognosisNamaEdukator" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Edukator..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'prognosisEdukatorTTD'" :width="'150'" :height="'150'" class="dek" /> -->
                          <img v-if="input.prognosisNamaEdukator"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.prognosisNamaEdukator ? input.prognosisNamaEdukator.label : '-')">
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prognosisEvaluasiReedukasi" class="pt-1 pb-1 "
                                  label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prognosisEvaluasiRedemonstrasi" class="pt-1 pb-1 "
                                  label="Re-Demostrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prognosisEvaluasiSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.prognosisTglReEdukasi" mode="date" style="width: 100%" trim-weeks
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
                        </td>
                      </tr>

                      <!-- 10 -->

                      <tr>
                        <td width="230px">
                          <span>10. Alternatif :</span>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.alternatifTrue" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.alternatifYa" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.alternatifTidak" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.alternatifTglEdukasi" mode="date" style="width: 100%" trim-weeks
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
                        </td>
                        <td width="180px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.alternatifNamaSasaran" class="input" placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'sasaran_2'" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.alternatifPemahamanSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.alternatifPemahamanEdukasiUlang" class="pt-1 pb-1 "
                                  label="Edukasi Ulang" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.alternatifPemahamanHalBaru" class="pt-1 pb-1 " label="Hal Baru"
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.alternatifMetodEdukasiIndvidu" class="pt-1 pb-1 "
                                  label="Individu" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.alternatifMetodEdukasikelompok" class="pt-1 pb-1 "
                                  label="Kelompok" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.alternatifMetodEdukasiCeramah" class="pt-1 pb-1 "
                                  label="Ceramah" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.alternatifMetodEdukasiDemonstrasi" class="pt-1 pb-1 "
                                  label="Demonstrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.alternatifMaterialEdukasiLeaflet" class="pt-1 pb-1 "
                                  label="Leaflet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.alternatifMaterialEdukasiBooklet" class="pt-1 pb-1 "
                                  label="Booklet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.alternatifMaterialEdukasiLembarBalik" class="pt-1 pb-1 "
                                  label="Lembar Balik" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.alternatifMaterialEdukasiAudioVisual" class="pt-1 pb-1 "
                                  label="Audio Visual" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.alternatifNamaEdukator" class="input" placeholder="Nama Edukator...">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.alternatifNamaEdukator" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Edukator..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'alternatifEdukatorTTD'" :width="'150'" :height="'150'" class="dek" /> -->
                          <img v-if="input.alternatifNamaEdukator"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.alternatifNamaEdukator ? input.alternatifNamaEdukator.label : '-')">
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.alternatifEvaluasiReedukasi" class="pt-1 pb-1 "
                                  label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.alternatifEvaluasiRedemonstrasi" class="pt-1 pb-1 "
                                  label="Re-Demostrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.alternatifEvaluasiSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.alternatifTglReEdukasi" mode="date" style="width: 100%" trim-weeks
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
                        </td>
                      </tr>

                      <!-- 10 -->

                      <tr>
                        <td width="230px">
                          <span>11. Manfaat Obat-obatan yang diberikan :</span>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.manfaatObatTrue" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.manfaatObatYa" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.manfaatObatTidak" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.manfaatObatTglEdukasi" mode="date" style="width: 100%" trim-weeks
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
                        </td>
                        <td width="180px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.manfaatObatNamaSasaran" class="input" placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'sasaran_3'" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.manfaatObatPemahamanSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.manfaatObatPemahamanEdukasiUlang" class="pt-1 pb-1 "
                                  label="Edukasi Ulang" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.manfaatObatPemahamanHalBaru" class="pt-1 pb-1 " label="Hal Baru"
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.manfaatObatMetodEdukasiIndvidu" class="pt-1 pb-1 "
                                  label="Individu" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.manfaatObatMetodEdukasikelompok" class="pt-1 pb-1 "
                                  label="Kelompok" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.manfaatObatMetodEdukasiCeramah" class="pt-1 pb-1 "
                                  label="Ceramah" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.manfaatObatMetodEdukasiDemonstrasi" class="pt-1 pb-1 "
                                  label="Demonstrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.manfaatObatMaterialEdukasiLeaflet" class="pt-1 pb-1 "
                                  label="Leaflet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.manfaatObatMaterialEdukasiBooklet" class="pt-1 pb-1 "
                                  label="Booklet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.manfaatObatMaterialEdukasiLembarBalik" class="pt-1 pb-1 "
                                  label="Lembar Balik" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.manfaatObatMaterialEdukasiAudioVisual" class="pt-1 pb-1 "
                                  label="Audio Visual" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.manfaatObatNamaEdukator" class="input"
                                placeholder="Nama Edukator...">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.manfaatObatNamaEdukator" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Edukator..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'manfaatObatEdukatorTTD'" :width="'150'" :height="'150'" class="dek" /> -->
                          <img v-if="input.manfaatObatNamaEdukator"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.manfaatObatNamaEdukator ? input.manfaatObatNamaEdukator.label : '-')">
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.manfaatObatEvaluasiReedukasi" class="pt-1 pb-1 "
                                  label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.manfaatObatEvaluasiRedemonstrasi" class="pt-1 pb-1 "
                                  label="Re-Demostrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.manfaatObatEvaluasiSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.manfaatObatTglReEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                      </tr>

                      <!-- 12 -->

                      <tr>
                        <td width="230px">
                          <span>12. Efek samping obat-obaatan yang diberikan :</span>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.efekSampingTrue" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.efekSampingYa" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.efekSampingTidak" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.efekSampingTglEdukasi" mode="date" style="width: 100%" trim-weeks
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
                        </td>
                        <td width="180px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.efekSampingNamaSasaran" class="input" placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'sasaran_4'" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.efekSampingPemahamanSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.efekSampingPemahamanEdukasiUlang" class="pt-1 pb-1 "
                                  label="Edukasi Ulang" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.efekSampingPemahamanHalBaru" class="pt-1 pb-1 " label="Hal Baru"
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.efekSampingMetodEdukasiIndvidu" class="pt-1 pb-1 "
                                  label="Individu" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.efekSampingMetodEdukasikelompok" class="pt-1 pb-1 "
                                  label="Kelompok" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.efekSampingMetodEdukasiCeramah" class="pt-1 pb-1 "
                                  label="Ceramah" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.efekSampingMetodEdukasiDemonstrasi" class="pt-1 pb-1 "
                                  label="Demonstrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.efekSampingMaterialEdukasiLeaflet" class="pt-1 pb-1 "
                                  label="Leaflet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.efekSampingMaterialEdukasiBooklet" class="pt-1 pb-1 "
                                  label="Booklet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.efekSampingMaterialEdukasiLembarBalik" class="pt-1 pb-1 "
                                  label="Lembar Balik" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.efekSampingMaterialEdukasiAudioVisual" class="pt-1 pb-1 "
                                  label="Audio Visual" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.efekSampingNamaEdukator" class="input"
                                placeholder="Nama Edukator...">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.efekSampingNamaEdukator" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Edukator..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'efekSampingEdukatorTTD'" :width="'150'" :height="'150'" class="dek" /> -->
                          <img v-if="input.efekSampingNamaEdukator"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.efekSampingNamaEdukator ? input.efekSampingNamaEdukator.label : '-')">
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.efekSampingEvaluasiReedukasi" class="pt-1 pb-1 "
                                  label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.efekSampingEvaluasiRedemonstrasi" class="pt-1 pb-1 "
                                  label="Re-Demostrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.efekSampingEvaluasiSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.efekSampingTglReEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                      </tr>

                      <!-- 13 -->

                      <tr>
                        <td width="230px">
                          <span>13. Interaksi obat dan makanan :</span>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.interaksiObatmakananTrue" class="pt-1 pb-1 " color="primary"
                                  square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.interaksiObatmakananYa" class="pt-1 pb-1 " color="primary"
                                  square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.interaksiObatmakananTidak" class="pt-1 pb-1 " color="primary"
                                  square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.interaksiObatmakananTglEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                        <td width="180px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.interaksiObatmakananNamaSasaran" class="input"
                                placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'sasaran_5'" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.interaksiObatmakananPemahamanSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.interaksiObatmakananPemahamanEdukasiUlang" class="pt-1 pb-1 "
                                  label="Edukasi Ulang" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.interaksiObatmakananPemahamanHalBaru" class="pt-1 pb-1 "
                                  label="Hal Baru" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.interaksiObatmakananMetodEdukasiIndvidu" class="pt-1 pb-1 "
                                  label="Individu" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.interaksiObatmakananMetodEdukasikelompok" class="pt-1 pb-1 "
                                  label="Kelompok" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.interaksiObatmakananMetodEdukasiCeramah" class="pt-1 pb-1 "
                                  label="Ceramah" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.interaksiObatmakananMetodEdukasiDemonstrasi" class="pt-1 pb-1 "
                                  label="Demonstrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.interaksiObatmakananMaterialEdukasiLeaflet" class="pt-1 pb-1 "
                                  label="Leaflet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.interaksiObatmakananMaterialEdukasiBooklet" class="pt-1 pb-1 "
                                  label="Booklet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.interaksiObatmakananMaterialEdukasiLembarBalik"
                                  class="pt-1 pb-1 " label="Lembar Balik" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.interaksiObatmakananMaterialEdukasiAudioVisual"
                                  class="pt-1 pb-1 " label="Audio Visual" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.interaksiObatmakananNamaEdukator" class="input"
                                placeholder="Nama Edukator...">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.interaksiObatmakananNamaEdukator" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Edukator..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'interaksiObatmakananEdukatorTTD'" :width="'150'" :height="'150'"
                            class="dek" /> -->
                          <img v-if="input.interaksiObatmakananNamaEdukator"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.interaksiObatmakananNamaEdukator ? input.interaksiObatmakananNamaEdukator.label : '-')">
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.interaksiObatmakananEvaluasiReedukasi" class="pt-1 pb-1 "
                                  label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.interaksiObatmakananEvaluasiRedemonstrasi" class="pt-1 pb-1 "
                                  label="Re-Demostrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.interaksiObatmakananEvaluasiSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.interaksiObatmakananTglReEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                      </tr>

                      <!-- 14 -->

                      <tr>
                        <td width="230px">
                          <span>14. Program diet yang nutrisi Sebutkan :</span>
                          <div class="column is-12">
                            <VField>
                              <VControl>
                                <VTextarea v-model="input.programDietSebutkan" rows="1" placeholder=" ...">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.programDietTrue" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.programDietYa" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.programDietTidak" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.programDietTglEdukasi" mode="date" style="width: 100%" trim-weeks
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
                        </td>
                        <td width="180px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.programDietNamaSasaran" class="input" placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'sasaran_6'" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.programDietPemahamanSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.programDietPemahamanEdukasiUlang" class="pt-1 pb-1 "
                                  label="Edukasi Ulang" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.programDietPemahamanHalBaru" class="pt-1 pb-1 " label="Hal Baru"
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.programDietMetodEdukasiIndvidu" class="pt-1 pb-1 "
                                  label="Individu" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.programDietMetodEdukasikelompok" class="pt-1 pb-1 "
                                  label="Kelompok" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.programDietMetodEdukasiCeramah" class="pt-1 pb-1 "
                                  label="Ceramah" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.programDietMetodEdukasiDemonstrasi" class="pt-1 pb-1 "
                                  label="Demonstrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.programDietMaterialEdukasiLeaflet" class="pt-1 pb-1 "
                                  label="Leaflet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.programDietMaterialEdukasiBooklet" class="pt-1 pb-1 "
                                  label="Booklet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.programDietMaterialEdukasiLembarBalik" class="pt-1 pb-1 "
                                  label="Lembar Balik" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.programDietMaterialEdukasiAudioVisual" class="pt-1 pb-1 "
                                  label="Audio Visual" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.programDietNamaEdukator" class="input"
                                placeholder="Nama Edukator...">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.programDietNamaEdukator" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Edukator..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'programDietEdukatorTTD'" :width="'150'" :height="'150'" class="dek" /> -->
                          <img v-if="input.programDietNamaEdukator"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.programDietNamaEdukator ? input.programDietNamaEdukator.label : '-')">
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.programDietEvaluasiReedukasi" class="pt-1 pb-1 "
                                  label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.programDietEvaluasiRedemonstrasi" class="pt-1 pb-1 "
                                  label="Re-Demostrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.programDietEvaluasiSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.programDietTglReEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                      </tr>

                      <!-- 15 -->

                      <tr>
                        <td width="230px">
                          <span>15. Manajemen nyeri VAS</span>
                          <div class="column is-12">
                            <span>1. Non Farmakologi</span>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.ManajNyeriNonFarmaDIstraksi" class="pt-1 pb-1 " color="primary"
                                  square label="a. Distraksi, Relaksasi" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.ManajNyeriNonFarmaKompres" class="pt-1 pb-1 " color="primary"
                                  square label="b. Kompres Hanga/Dingin" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.ManajNyeriNonFarmaTeknik" class="pt-1 pb-1 " color="primary"
                                  square label="c. Teknik Ecuplesure" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px 10px;">
                              <span>d.</span>
                              <VControl>
                                <VInput v-model="input.ManajNyeriNonFarmaD" class="input" placeholder="...">
                                </VInput>
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <span>2. Farmakologi</span>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px 10px;">
                              <span>a.</span>
                              <VControl>
                                <VInput v-model="input.ManajNyeriFarmaA" class="input" placeholder="...">
                                </VInput>
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px 10px;">
                              <span>b.</span>
                              <VControl>
                                <VInput v-model="input.ManajNyeriFarmaB" class="input" placeholder="...">
                                </VInput>
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.ManajemenNyeriTrue" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.ManajemenNyeriYa" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.ManajemenNyeriTidak" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.ManajemenNyeriTglEdukasi" mode="date" style="width: 100%" trim-weeks
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
                        </td>
                        <td width="180px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.ManajemenNyeriNamaSasaran" class="input" placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'pasien_0'" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.ManajemenNyeriPemahamanSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.ManajemenNyeriPemahamanEdukasiUlang" class="pt-1 pb-1 "
                                  label="Edukasi Ulang" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.ManajemenNyeriPemahamanHalBaru" class="pt-1 pb-1 " label="Hal Baru"
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.ManajemenNyeriMetodEdukasiIndvidu" class="pt-1 pb-1 "
                                  label="Individu" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.ManajemenNyeriMetodEdukasikelompok" class="pt-1 pb-1 "
                                  label="Kelompok" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.ManajemenNyeriMetodEdukasiCeramah" class="pt-1 pb-1 "
                                  label="Ceramah" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.ManajemenNyeriMetodEdukasiDemonstrasi" class="pt-1 pb-1 "
                                  label="Demonstrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.ManajemenNyeriMaterialEdukasiLeaflet" class="pt-1 pb-1 "
                                  label="Leaflet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.ManajemenNyeriMaterialEdukasiBooklet" class="pt-1 pb-1 "
                                  label="Booklet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.ManajemenNyeriMaterialEdukasiLembarBalik" class="pt-1 pb-1 "
                                  label="Lembar Balik" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.ManajemenNyeriMaterialEdukasiAudioVisual" class="pt-1 pb-1 "
                                  label="Audio Visual" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.ManajemenNyeriNamaEdukator" class="input"
                                placeholder="Nama Edukator...">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.ManajemenNyeriNamaEdukator" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Edukator..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'ManajemenNyeriEdukatorTTD'" :width="'150'" :height="'150'" class="dek" /> -->
                          <img v-if="input.ManajemenNyeriNamaEdukator"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.ManajemenNyeriNamaEdukator ? input.ManajemenNyeriNamaEdukator.label : '-')">
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.ManajemenNyeriEvaluasiReedukasi" class="pt-1 pb-1 "
                                  label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.ManajemenNyeriEvaluasiRedemonstrasi" class="pt-1 pb-1 "
                                  label="Re-Demostrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.ManajemenNyeriEvaluasiSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.ManajemenNyeriTglReEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                      </tr>

                      <!-- 16 -->

                      <tr>
                        <td width="230px">
                          <span>16. Penggunaan alat kedokteran (alat kesehatan), (sebutkan) :</span>
                          <div class="column is-12">
                            <VField>
                              <VControl>
                                <VTextarea v-model="input.penggunaanAlatKedokteranSebutkan" rows="1" placeholder=" ...">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAlatKedokteranTrue" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAlatKedokteranYa" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAlatKedokteranTidak" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.penggunaanAlatKedokteranTglEdukasi" mode="date" style="width: 100%" trim-weeks
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
                        </td>
                        <td width="180px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.penggunaanAlatKedokteranNamaSasaran" class="input" placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'pasien_1'" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAlatKedokteranPemahamanSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAlatKedokteranPemahamanEdukasiUlang" class="pt-1 pb-1 "
                                  label="Edukasi Ulang" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAlatKedokteranPemahamanHalBaru" class="pt-1 pb-1 " label="Hal Baru"
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAlatKedokteranMetodEdukasiIndvidu" class="pt-1 pb-1 "
                                  label="Individu" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAlatKedokteranMetodEdukasikelompok" class="pt-1 pb-1 "
                                  label="Kelompok" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAlatKedokteranMetodEdukasiCeramah" class="pt-1 pb-1 "
                                  label="Ceramah" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAlatKedokteranMetodEdukasiDemonstrasi" class="pt-1 pb-1 "
                                  label="Demonstrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAlatKedokteranMaterialEdukasiLeaflet" class="pt-1 pb-1 "
                                  label="Leaflet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAlatKedokteranMaterialEdukasiBooklet" class="pt-1 pb-1 "
                                  label="Booklet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAlatKedokteranMaterialEdukasiLembarBalik" class="pt-1 pb-1 "
                                  label="Lembar Balik" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAlatKedokteranMaterialEdukasiAudioVisual" class="pt-1 pb-1 "
                                  label="Audio Visual" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.penggunaanAlatKedokteranNamaEdukator" class="input"
                                placeholder="Nama Edukator...">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.penggunaanAlatKedokteranNamaEdukator" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Edukator..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'penggunaanAlatKedokteranEdukatorTTD'" :width="'150'" :height="'150'" class="dek" /> -->
                          <img v-if="input.penggunaanAlatKedokteranNamaEdukator"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.penggunaanAlatKedokteranNamaEdukator ? input.penggunaanAlatKedokteranNamaEdukator.label : '-')">
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAlatKedokteranEvaluasiReedukasi" class="pt-1 pb-1 "
                                  label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAlatKedokteranEvaluasiRedemonstrasi" class="pt-1 pb-1 "
                                  label="Re-Demostrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAlatKedokteranEvaluasiSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.penggunaanAlatKedokteranTglReEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                      </tr>

                      <!-- 17 -->

                      <tr>
                        <td width="230px">
                          <span>17. Cuci tangan yang benar :</span>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.cuciTanganTrue" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.cuciTanganYa" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.cuciTanganTidak" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.cuciTanganTglEdukasi" mode="date" style="width: 100%" trim-weeks
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
                        </td>
                        <td width="180px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.cuciTanganNamaSasaran" class="input" placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'pasien_2'" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.cuciTanganPemahamanSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.cuciTanganPemahamanEdukasiUlang" class="pt-1 pb-1 "
                                  label="Edukasi Ulang" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.cuciTanganPemahamanHalBaru" class="pt-1 pb-1 " label="Hal Baru"
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.cuciTanganMetodEdukasiIndvidu" class="pt-1 pb-1 "
                                  label="Individu" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.cuciTanganMetodEdukasikelompok" class="pt-1 pb-1 "
                                  label="Kelompok" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.cuciTanganMetodEdukasiCeramah" class="pt-1 pb-1 "
                                  label="Ceramah" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.cuciTanganMetodEdukasiDemonstrasi" class="pt-1 pb-1 "
                                  label="Demonstrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.cuciTanganMaterialEdukasiLeaflet" class="pt-1 pb-1 "
                                  label="Leaflet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.cuciTanganMaterialEdukasiBooklet" class="pt-1 pb-1 "
                                  label="Booklet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.cuciTanganMaterialEdukasiLembarBalik" class="pt-1 pb-1 "
                                  label="Lembar Balik" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.cuciTanganMaterialEdukasiAudioVisual" class="pt-1 pb-1 "
                                  label="Audio Visual" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.cuciTanganNamaEdukator" class="input"
                                placeholder="Nama Edukator...">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.cuciTanganNamaEdukator" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Edukator..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'cuciTanganEdukatorTTD'" :width="'150'" :height="'150'" class="dek" /> -->
                          <img v-if="input.cuciTanganNamaEdukator"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.cuciTanganNamaEdukator ? input.cuciTanganNamaEdukator.label : '-')">
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.cuciTanganEvaluasiReedukasi" class="pt-1 pb-1 "
                                  label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.cuciTanganEvaluasiRedemonstrasi" class="pt-1 pb-1 "
                                  label="Re-Demostrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.cuciTanganEvaluasiSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.cuciTanganTglReEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                      </tr>

                      <!-- 18 -->

                      <tr>
                        <td width="230px">
                          <span>18. Penggunaan APD (masker dan sarug tangan)</span>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAPDTrue" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAPDYa" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAPDTidak" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.penggunaanAPDTglEdukasi" mode="date" style="width: 100%" trim-weeks
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
                        </td>
                        <td width="180px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.penggunaanAPDNamaSasaran" class="input" placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'pasien_3'" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAPDPemahamanSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAPDPemahamanEdukasiUlang" class="pt-1 pb-1 "
                                  label="Edukasi Ulang" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAPDPemahamanHalBaru" class="pt-1 pb-1 " label="Hal Baru"
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAPDMetodEdukasiIndvidu" class="pt-1 pb-1 "
                                  label="Individu" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAPDMetodEdukasikelompok" class="pt-1 pb-1 "
                                  label="Kelompok" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAPDMetodEdukasiCeramah" class="pt-1 pb-1 "
                                  label="Ceramah" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAPDMetodEdukasiDemonstrasi" class="pt-1 pb-1 "
                                  label="Demonstrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAPDMaterialEdukasiLeaflet" class="pt-1 pb-1 "
                                  label="Leaflet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAPDMaterialEdukasiBooklet" class="pt-1 pb-1 "
                                  label="Booklet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAPDMaterialEdukasiLembarBalik" class="pt-1 pb-1 "
                                  label="Lembar Balik" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAPDMaterialEdukasiAudioVisual" class="pt-1 pb-1 "
                                  label="Audio Visual" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.penggunaanAPDNamaEdukator" class="input"
                                placeholder="Nama Edukator...">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.penggunaanAPDNamaEdukator" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Edukator..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'penggunaanAPDEdukatorTTD'" :width="'150'" :height="'150'" class="dek" /> -->
                          <img v-if="input.penggunaanAPDNamaEdukator"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.penggunaanAPDNamaEdukator ? input.penggunaanAPDNamaEdukator.label : '-')">
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAPDEvaluasiReedukasi" class="pt-1 pb-1 "
                                  label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAPDEvaluasiRedemonstrasi" class="pt-1 pb-1 "
                                  label="Re-Demostrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.penggunaanAPDEvaluasiSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.penggunaanAPDTglReEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                      </tr>

                      <!-- 19 -->

                      <tr>
                        <td width="230px">
                          <span>19. Prosedur perawatan (spesifik)</span>
                          <div class="column is-12">
                            <VField style="padding:0px 10px;">
                              <span>a.</span>
                              <VControl>
                                <VInput v-model="input.prosedurKeperawatanA" class="input" placeholder="...">
                                </VInput>
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanATrue" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanAYa" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanATidak" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.prosedurKeperawatanATglEdukasi" mode="date" style="width: 100%" trim-weeks
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
                        </td>
                        <td width="180px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.prosedurKeperawatanANamaSasaran" class="input" placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'pasien_4'" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanAPemahamanSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanAPemahamanEdukasiUlang" class="pt-1 pb-1 "
                                  label="Edukasi Ulang" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanAPemahamanHalBaru" class="pt-1 pb-1 " label="Hal Baru"
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanAMetodEdukasiIndvidu" class="pt-1 pb-1 "
                                  label="Individu" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanAMetodEdukasikelompok" class="pt-1 pb-1 "
                                  label="Kelompok" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanAMetodEdukasiCeramah" class="pt-1 pb-1 "
                                  label="Ceramah" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanAMetodEdukasiDemonstrasi" class="pt-1 pb-1 "
                                  label="Demonstrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanAMaterialEdukasiLeaflet" class="pt-1 pb-1 "
                                  label="Leaflet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanAMaterialEdukasiBooklet" class="pt-1 pb-1 "
                                  label="Booklet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanAMaterialEdukasiLembarBalik" class="pt-1 pb-1 "
                                  label="Lembar Balik" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanAMaterialEdukasiAudioVisual" class="pt-1 pb-1 "
                                  label="Audio Visual" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.prosedurKeperawatanANamaEdukator" class="input"
                                placeholder="Nama Edukator...">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.prosedurKeperawatanANamaEdukator" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Edukator..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'prosedurKeperawatanAEdukatorTTD'" :width="'150'" :height="'150'" class="dek" /> -->
                          <img v-if="input.prosedurKeperawatanANamaEdukator"
                          :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.prosedurKeperawatanANamaEdukator ? input.prosedurKeperawatanANamaEdukator.label : '-')">
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanAEvaluasiReedukasi" class="pt-1 pb-1 "
                                  label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanAEvaluasiRedemonstrasi" class="pt-1 pb-1 "
                                  label="Re-Demostrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanAEvaluasiSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.prosedurKeperawatanATglReEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                      </tr>

                      <!-- 19.b -->

                      <tr>
                        <td width="230px">
                          <div class="column is-12">
                            <VField style="padding:0px 10px;">
                              <span>b.</span>
                              <VControl>
                                <VInput v-model="input.prosedurKeperawatanB" class="input" placeholder="...">
                                </VInput>
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanBTrue" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanBYa" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanBTidak" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.prosedurKeperawatanBTglEdukasi" mode="date" style="width: 100%" trim-weeks
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
                        </td>
                        <td width="180px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.prosedurKeperawatanBNamaSasaran" class="input" placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'pasien_5'" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanBPemahamanSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanBPemahamanEdukasiUlang" class="pt-1 pb-1 "
                                  label="Edukasi Ulang" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanBPemahamanHalBaru" class="pt-1 pb-1 " label="Hal Baru"
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanBMetodEdukasiIndvidu" class="pt-1 pb-1 "
                                  label="Individu" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanBMetodEdukasikelompok" class="pt-1 pb-1 "
                                  label="Kelompok" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanBMetodEdukasiCeramah" class="pt-1 pb-1 "
                                  label="Ceramah" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanBMetodEdukasiDemonstrasi" class="pt-1 pb-1 "
                                  label="Demonstrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanBMaterialEdukasiLeaflet" class="pt-1 pb-1 "
                                  label="Leaflet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanBMaterialEdukasiBooklet" class="pt-1 pb-1 "
                                  label="Booklet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanBMaterialEdukasiLembarBalik" class="pt-1 pb-1 "
                                  label="Lembar Balik" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanBMaterialEdukasiAudioVisual" class="pt-1 pb-1 "
                                  label="Audio Visual" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.prosedurKeperawatanBNamaEdukator" class="input"
                                placeholder="Nama Edukator...">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.prosedurKeperawatanBNamaEdukator" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Edukator..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'prosedurKeperawatanBEdukatorTTD'" :width="'150'" :height="'150'" class="dek" /> -->
                          <img v-if="input.prosedurKeperawatanBNamaEdukator"
                          :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.prosedurKeperawatanBNamaEdukator ? input.prosedurKeperawatanBNamaEdukator.label : '-')">
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanBEvaluasiReedukasi" class="pt-1 pb-1 "
                                  label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanBEvaluasiRedemonstrasi" class="pt-1 pb-1 "
                                  label="Re-Demostrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanBEvaluasiSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.prosedurKeperawatanBTglReEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                      </tr>

                      <!-- 19.c -->

                      <tr>
                        <td width="230px">
                          <div class="column is-12">
                            <VField style="padding:0px 10px;">
                              <span>c.</span>
                              <VControl>
                                <VInput v-model="input.prosedurKeperawatanC" class="input" placeholder="...">
                                </VInput>
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanCTrue" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanCYa" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanCTidak" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.prosedurKeperawatanCTglEdukasi" mode="date" style="width: 100%" trim-weeks
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
                        </td>
                        <td width="180px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.prosedurKeperawatanCNamaSasaran" class="input" placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'pasien_6'" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanCPemahamanSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanCPemahamanEdukasiUlang" class="pt-1 pb-1 "
                                  label="Edukasi Ulang" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanCPemahamanHalBaru" class="pt-1 pb-1 " label="Hal Baru"
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanCMetodEdukasiIndvidu" class="pt-1 pb-1 "
                                  label="Individu" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanCMetodEdukasikelompok" class="pt-1 pb-1 "
                                  label="Kelompok" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanCMetodEdukasiCeramah" class="pt-1 pb-1 "
                                  label="Ceramah" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanCMetodEdukasiDemonstrasi" class="pt-1 pb-1 "
                                  label="Demonstrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanCMaterialEdukasiLeaflet" class="pt-1 pb-1 "
                                  label="Leaflet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanCMaterialEdukasiBooklet" class="pt-1 pb-1 "
                                  label="Booklet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanCMaterialEdukasiLembarBalik" class="pt-1 pb-1 "
                                  label="Lembar Balik" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanCMaterialEdukasiAudioVisual" class="pt-1 pb-1 "
                                  label="Audio Visual" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.prosedurKeperawatanCNamaEdukator" class="input"
                                placeholder="Nama Edukator...">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.prosedurKeperawatanCNamaEdukator" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Edukator..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'prosedurKeperawatanCEdukatorTTD'" :width="'150'" :height="'150'" class="dek" /> -->
                          <img v-if="input.prosedurKeperawatanCNamaEdukator"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.prosedurKeperawatanCNamaEdukator ? input.prosedurKeperawatanCNamaEdukator.label : '-')">
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanCEvaluasiReedukasi" class="pt-1 pb-1 "
                                  label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanCEvaluasiRedemonstrasi" class="pt-1 pb-1 "
                                  label="Re-Demostrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanCEvaluasiSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.prosedurKeperawatanCTglReEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                      </tr>

                      <!-- 19.d -->

                      <tr>
                        <td width="230px">
                          <div class="column is-12">
                            <VField style="padding:0px 10px;">
                              <span>d.</span>
                              <VControl>
                                <VInput v-model="input.prosedurKeperawatanD" class="input" placeholder="...">
                                </VInput>
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanDTrue" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanDYa" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanDTidak" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.prosedurKeperawatanDTglEdukasi" mode="date" style="width: 100%" trim-weeks
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
                        </td>
                        <td width="180px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.prosedurKeperawatanDNamaSasaran" class="input" placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'pasien_7'" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanDPemahamanSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanDPemahamanEdukasiUlang" class="pt-1 pb-1 "
                                  label="Edukasi Ulang" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanDPemahamanHalBaru" class="pt-1 pb-1 " label="Hal Baru"
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanDMetodEdukasiIndvidu" class="pt-1 pb-1 "
                                  label="Individu" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanDMetodEdukasikelompok" class="pt-1 pb-1 "
                                  label="Kelompok" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanDMetodEdukasiCeramah" class="pt-1 pb-1 "
                                  label="Ceramah" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanDMetodEdukasiDemonstrasi" class="pt-1 pb-1 "
                                  label="Demonstrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanDMaterialEdukasiLeaflet" class="pt-1 pb-1 "
                                  label="Leaflet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanDMaterialEdukasiBooklet" class="pt-1 pb-1 "
                                  label="Booklet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanDMaterialEdukasiLembarBalik" class="pt-1 pb-1 "
                                  label="Lembar Balik" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanDMaterialEdukasiAudioVisual" class="pt-1 pb-1 "
                                  label="Audio Visual" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.prosedurKeperawatanDNamaEdukator" class="input"
                                placeholder="Nama Edukator...">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.prosedurKeperawatanDNamaEdukator" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Edukator..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'prosedurKeperawatanDEdukatorTTD'" :width="'150'" :height="'150'" class="dek" /> -->
                          <img v-if="input.prosedurKeperawatanDNamaEdukator"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.prosedurKeperawatanDNamaEdukator ? input.prosedurKeperawatanDNamaEdukator.label : '-')">
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanDEvaluasiReedukasi" class="pt-1 pb-1 "
                                  label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanDEvaluasiRedemonstrasi" class="pt-1 pb-1 "
                                  label="Re-Demostrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanDEvaluasiSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.prosedurKeperawatanDTglReEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                      </tr>

                      <!-- 19.e -->

                      <tr>
                        <td width="230px">
                          <div class="column is-12">
                            <VField style="padding:0px 10px;">
                              <span>e.</span>
                              <VControl>
                                <VInput v-model="input.prosedurKeperawatanE" class="input" placeholder="...">
                                </VInput>
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanETrue" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanEYa" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanETidak" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.prosedurKeperawatanETglEdukasi" mode="date" style="width: 100%" trim-weeks
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
                        </td>
                        <td width="180px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.prosedurKeperawatanENamaSasaran" class="input" placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'pasien_8'" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanEPemahamanSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanEPemahamanEdukasiUlang" class="pt-1 pb-1 "
                                  label="Edukasi Ulang" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanEPemahamanHalBaru" class="pt-1 pb-1 " label="Hal Baru"
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanEMetodEdukasiIndvidu" class="pt-1 pb-1 "
                                  label="Individu" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanEMetodEdukasikelompok" class="pt-1 pb-1 "
                                  label="Kelompok" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanEMetodEdukasiCeramah" class="pt-1 pb-1 "
                                  label="Ceramah" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanEMetodEdukasiDemonstrasi" class="pt-1 pb-1 "
                                  label="Demonstrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanEMaterialEdukasiLeaflet" class="pt-1 pb-1 "
                                  label="Leaflet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanEMaterialEdukasiBooklet" class="pt-1 pb-1 "
                                  label="Booklet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanEMaterialEdukasiLembarBalik" class="pt-1 pb-1 "
                                  label="Lembar Balik" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanEMaterialEdukasiAudioVisual" class="pt-1 pb-1 "
                                  label="Audio Visual" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.prosedurKeperawatanENamaEdukator" class="input"
                                placeholder="Nama Edukator...">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.prosedurKeperawatanENamaEdukator" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Edukator..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'prosedurKeperawatanEEdukatorTTD'" :width="'150'" :height="'150'" class="dek" /> -->
                          <img v-if="input.prosedurKeperawatanENamaEdukator"
                          :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.prosedurKeperawatanENamaEdukator ? input.prosedurKeperawatanENamaEdukator.label : '-')">
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanEEvaluasiReedukasi" class="pt-1 pb-1 "
                                  label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanEEvaluasiRedemonstrasi" class="pt-1 pb-1 "
                                  label="Re-Demostrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanEEvaluasiSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.prosedurKeperawatanETglReEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                      </tr>

                      <!-- 19.f -->

                      <tr>
                        <td width="230px">
                          <div class="column is-12">
                            <VField style="padding:0px 10px;">
                              <span>f.</span>
                              <VControl>
                                <VInput v-model="input.prosedurKeperawatanF" class="input" placeholder="...">
                                </VInput>
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanFTrue" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanFYa" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanFTidak" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.prosedurKeperawatanFTglEdukasi" mode="date" style="width: 100%" trim-weeks
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
                        </td>
                        <td width="180px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.prosedurKeperawatanFNamaSasaran" class="input" placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'pasien_9'" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanFPemahamanSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanFPemahamanEdukasiUlang" class="pt-1 pb-1 "
                                  label="Edukasi Ulang" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanFPemahamanHalBaru" class="pt-1 pb-1 " label="Hal Baru"
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanFMetodEdukasiIndvidu" class="pt-1 pb-1 "
                                  label="Individu" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanFMetodEdukasikelompok" class="pt-1 pb-1 "
                                  label="Kelompok" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanFMetodEdukasiCeramah" class="pt-1 pb-1 "
                                  label="Ceramah" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanFMetodEdukasiDemonstrasi" class="pt-1 pb-1 "
                                  label="Demonstrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanFMaterialEdukasiLeaflet" class="pt-1 pb-1 "
                                  label="Leaflet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanFMaterialEdukasiBooklet" class="pt-1 pb-1 "
                                  label="Booklet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanFMaterialEdukasiLembarBalik" class="pt-1 pb-1 "
                                  label="Lembar Balik" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanFMaterialEdukasiAudioVisual" class="pt-1 pb-1 "
                                  label="Audio Visual" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.prosedurKeperawatanFNamaEdukator" class="input"
                                placeholder="Nama Edukator...">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.prosedurKeperawatanFNamaEdukator" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Edukator..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'prosedurKeperawatanFEdukatorTTD'" :width="'150'" :height="'150'" class="dek" /> -->
                          <img v-if="input.prosedurKeperawatanFNamaEdukator"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.prosedurKeperawatanFNamaEdukator ? input.prosedurKeperawatanFNamaEdukator.label : '-')">
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanFEvaluasiReedukasi" class="pt-1 pb-1 "
                                  label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanFEvaluasiRedemonstrasi" class="pt-1 pb-1 "
                                  label="Re-Demostrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.prosedurKeperawatanFEvaluasiSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.prosedurKeperawatanFTglReEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                      </tr>

                      <!-- 20 -->

                      <tr>
                        <td width="230px">
                          <span>20. teknik-teknik rehabilitasi</span>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tekniRehabTrue" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tekniRehabYa" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tekniRehabTidak" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.tekniRehabTglEdukasi" mode="date" style="width: 100%" trim-weeks
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
                        </td>
                        <td width="180px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.tekniRehabNamaSasaran" class="input" placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'pasien_10'" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tekniRehabPemahamanSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tekniRehabPemahamanEdukasiUlang" class="pt-1 pb-1 "
                                  label="Edukasi Ulang" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tekniRehabPemahamanHalBaru" class="pt-1 pb-1 " label="Hal Baru"
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tekniRehabMetodEdukasiIndvidu" class="pt-1 pb-1 "
                                  label="Individu" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tekniRehabMetodEdukasikelompok" class="pt-1 pb-1 "
                                  label="Kelompok" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tekniRehabMetodEdukasiCeramah" class="pt-1 pb-1 "
                                  label="Ceramah" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tekniRehabMetodEdukasiDemonstrasi" class="pt-1 pb-1 "
                                  label="Demonstrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tekniRehabMaterialEdukasiLeaflet" class="pt-1 pb-1 "
                                  label="Leaflet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tekniRehabMaterialEdukasiBooklet" class="pt-1 pb-1 "
                                  label="Booklet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tekniRehabMaterialEdukasiLembarBalik" class="pt-1 pb-1 "
                                  label="Lembar Balik" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tekniRehabMaterialEdukasiAudioVisual" class="pt-1 pb-1 "
                                  label="Audio Visual" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.tekniRehabNamaEdukator" class="input"
                                placeholder="Nama Edukator...">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.tekniRehabNamaEdukator" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Edukator..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'tekniRehabEdukatorTTD'" :width="'150'" :height="'150'" class="dek" /> -->
                          <img v-if="input.tekniRehabNamaEdukator"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.tekniRehabNamaEdukator ? input.tekniRehabNamaEdukator.label : '-')">
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tekniRehabEvaluasiReedukasi" class="pt-1 pb-1 "
                                  label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tekniRehabEvaluasiRedemonstrasi" class="pt-1 pb-1 "
                                  label="Re-Demostrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tekniRehabEvaluasiSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.tekniRehabTglReEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                      </tr>

                      <!-- 21 -->

                      <tr>
                        <!-- <td width="230px">
                          <span>21. Waktu kontrol dan penggunaan obat-obatan di rumah</span>
                        </td> -->
                        <td width="230px">
                          <span>21. Waktu kontrol dan penggunaan obat-obatan di rumah</span>
                          <div class="column is-12">
                            <VField style="padding:0px 10px;">
                              <VControl>
                                <VTextarea v-model="input.obatPulang" rows="6" disabled>
                                </VTextarea>
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.waktuKontrolTrue" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.waktuKontrolYa" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.waktuKontrolTidak" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.waktuKontrolTglEdukasi" mode="date" style="width: 100%" trim-weeks
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
                        </td>
                        <td width="180px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.waktuKontrolNamaSasaran" class="input" placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'pasien_11'" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.waktuKontrolPemahamanSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.waktuKontrolPemahamanEdukasiUlang" class="pt-1 pb-1 "
                                  label="Edukasi Ulang" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.waktuKontrolPemahamanHalBaru" class="pt-1 pb-1 " label="Hal Baru"
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.waktuKontrolMetodEdukasiIndvidu" class="pt-1 pb-1 "
                                  label="Individu" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.waktuKontrolMetodEdukasikelompok" class="pt-1 pb-1 "
                                  label="Kelompok" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.waktuKontrolMetodEdukasiCeramah" class="pt-1 pb-1 "
                                  label="Ceramah" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.waktuKontrolMetodEdukasiDemonstrasi" class="pt-1 pb-1 "
                                  label="Demonstrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.waktuKontrolMaterialEdukasiLeaflet" class="pt-1 pb-1 "
                                  label="Leaflet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.waktuKontrolMaterialEdukasiBooklet" class="pt-1 pb-1 "
                                  label="Booklet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.waktuKontrolMaterialEdukasiLembarBalik" class="pt-1 pb-1 "
                                  label="Lembar Balik" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.waktuKontrolMaterialEdukasiAudioVisual" class="pt-1 pb-1 "
                                  label="Audio Visual" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.waktuKontrolNamaEdukator" class="input"
                                placeholder="Nama Edukator...">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.waktuKontrolNamaEdukator" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Edukator..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'waktuKontrolEdukatorTTD'" :width="'150'" :height="'150'" class="dek" /> -->
                          <img v-if="input.waktuKontrolNamaEdukator"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.waktuKontrolNamaEdukator ? input.waktuKontrolNamaEdukator.label : '-')">
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.waktuKontrolEvaluasiReedukasi" class="pt-1 pb-1 "
                                  label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.waktuKontrolEvaluasiRedemonstrasi" class="pt-1 pb-1 "
                                  label="Re-Demostrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.waktuKontrolEvaluasiSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.waktuKontrolTglReEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                      </tr>

                      <!-- freetext1 -->

                      <tr>
                        <td width="230px">
                          <div class="column is-12">
                            <VField>
                              <VControl>
                                <VTextarea v-model="input.textAre1" rows="1" placeholder=".....">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre1True" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre1Ya" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre1Tidak" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.textAre1TglEdukasi" mode="date" style="width: 100%" trim-weeks
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
                        </td>
                        <td width="180px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.textAre1NamaSasaran" class="input" placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'pasien_12'" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre1PemahamanSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre1PemahamanEdukasiUlang" class="pt-1 pb-1 "
                                  label="Edukasi Ulang" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre1PemahamanHalBaru" class="pt-1 pb-1 " label="Hal Baru"
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre1MetodEdukasiIndvidu" class="pt-1 pb-1 "
                                  label="Individu" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre1MetodEdukasikelompok" class="pt-1 pb-1 "
                                  label="Kelompok" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre1MetodEdukasiCeramah" class="pt-1 pb-1 "
                                  label="Ceramah" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre1MetodEdukasiDemonstrasi" class="pt-1 pb-1 "
                                  label="Demonstrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre1MaterialEdukasiLeaflet" class="pt-1 pb-1 "
                                  label="Leaflet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre1MaterialEdukasiBooklet" class="pt-1 pb-1 "
                                  label="Booklet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre1MaterialEdukasiLembarBalik" class="pt-1 pb-1 "
                                  label="Lembar Balik" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre1MaterialEdukasiAudioVisual" class="pt-1 pb-1 "
                                  label="Audio Visual" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.textAre1NamaEdukator" class="input"
                                placeholder="Nama Edukator...">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.textAre1NamaEdukator" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Edukator..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'textAre1EdukatorTTD'" :width="'150'" :height="'150'" class="dek" /> -->
                          <img v-if="input.textAre1NamaEdukator"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.textAre1NamaEdukator ? input.textAre1NamaEdukator.label : '-')">
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre1EvaluasiReedukasi" class="pt-1 pb-1 "
                                  label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre1EvaluasiRedemonstrasi" class="pt-1 pb-1 "
                                  label="Re-Demostrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre1EvaluasiSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.textAre1TglReEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                      </tr>

                      <!-- FreeText2 -->

                      <tr>
                        <td width="230px">
                          <div class="column is-12">
                            <VField>
                              <VControl>
                                <VTextarea v-model="input.textAre2" rows="1" placeholder=".....">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre2True" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre2Ya" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="20px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre2Tidak" class="pt-1 pb-1 " color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.textAre2TglEdukasi" mode="date" style="width: 100%" trim-weeks
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
                        </td>
                        <td width="180px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.textAre2NamaSasaran" class="input" placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'pasien_13'" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre2PemahamanSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre2PemahamanEdukasiUlang" class="pt-1 pb-1 "
                                  label="Edukasi Ulang" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre2PemahamanHalBaru" class="pt-1 pb-1 " label="Hal Baru"
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre2MetodEdukasiIndvidu" class="pt-1 pb-1 "
                                  label="Individu" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre2MetodEdukasikelompok" class="pt-1 pb-1 "
                                  label="Kelompok" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre2MetodEdukasiCeramah" class="pt-1 pb-1 "
                                  label="Ceramah" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre2MetodEdukasiDemonstrasi" class="pt-1 pb-1 "
                                  label="Demonstrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre2MaterialEdukasiLeaflet" class="pt-1 pb-1 "
                                  label="Leaflet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre2MaterialEdukasiBooklet" class="pt-1 pb-1 "
                                  label="Booklet" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre2MaterialEdukasiLembarBalik" class="pt-1 pb-1 "
                                  label="Lembar Balik" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre2MaterialEdukasiAudioVisual" class="pt-1 pb-1 "
                                  label="Audio Visual" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input.textAre2NamaEdukator" class="input"
                                placeholder="Nama Edukator...">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.textAre2NamaEdukator" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Edukator..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'textAre2EdukatorTTD'" :width="'150'" :height="'150'" class="dek" /> -->
                          <img v-if="input.textAre2NamaEdukator"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.textAre2NamaEdukator ? input.textAre2NamaEdukator.label : '-')">
                        </td>
                        <td width="180px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre2EvaluasiReedukasi" class="pt-1 pb-1 "
                                  label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre2EvaluasiRedemonstrasi" class="pt-1 pb-1 "
                                  label="Re-Demostrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.textAre2EvaluasiSudahMengerti" class="pt-1 pb-1 "
                                  label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td width="180px">
                          <VField>
                            <VDatePicker v-model="input.textAre2TglReEdukasi" mode="date" style="width: 100%"
                              trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </Fieldset>
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
import * as EMR from '../page-emr-plugins/formulir-informasi-edukasi-pasien-ranap'
import SignaturePad from 'signature_pad';



let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let informasi = ref(EMR.informasi())
let detailEdukasi = ref(EMR.detailEdukasi())

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
);

const d_Petugas: any = ref([])
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value >= 20 })
const isLoading: any = ref(false)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Ruangan: any = ref([])
const d_Dokter: any = ref([])
const dataTTD: any = ref([])
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
  details: [{ no: 1 }]
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
        if(NOREC_EMRPASIEN.value == ''){
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        input.value = response[0] //set ke inputan
        if (response[0].ttdedukator0) {
          H.tandaTangan().set("edukator_0", response[0].ttdedukator0)
        }
        if (response[0].ttdedukator1) {
          H.tandaTangan().set("edukator_1", response[0].ttdedukator1)
        }
        if (response[0].ttdedukator2) {
          H.tandaTangan().set("edukator_2", response[0].ttdedukator2)
        }
        if (response[0].ttdedukator3) {
          H.tandaTangan().set("edukator_3", response[0].ttdedukator3)
        }
        if (response[0].ttdedukator4) {
          H.tandaTangan().set("edukator_4", response[0].ttdedukator4)
        }
        if (response[0].ttdedukator5) {
          H.tandaTangan().set("edukator_5", response[0].ttdedukator5)
        }
        if (response[0].ttdedukator6) {
          H.tandaTangan().set("edukator_6", response[0].ttdedukator6)
        }
        if (response[0].ttdsasaran0) {
          H.tandaTangan().set("sasaran_0", response[0].ttdsasaran0)
        }
        if (response[0].ttdsasaran1) {
          H.tandaTangan().set("sasaran_1", response[0].ttdsasaran1)
        }
        if (response[0].ttdsasaran2) {
          H.tandaTangan().set("sasaran_2", response[0].ttdsasaran2)
        }
        if (response[0].ttdsasaran3) {
          H.tandaTangan().set("sasaran_3", response[0].ttdsasaran3)
        }
        if (response[0].ttdsasaran4) {
          H.tandaTangan().set("sasaran_4", response[0].ttdsasaran4)
        }
        if (response[0].ttdsasaran5) {
          H.tandaTangan().set("sasaran_5", response[0].ttdsasaran5)
        }
        if (response[0].ttdsasaran6) {
          H.tandaTangan().set("sasaran_6", response[0].ttdsasaran6)
        }
        if (response[0].ttdpasien0) {
          H.tandaTangan().set("pasien_0", response[0].ttdpasien0)
        }
        if (response[0].ttdpasien1) {
          H.tandaTangan().set("pasien_1", response[0].ttdpasien1)
        }
        if (response[0].ttdpasien2) {
          H.tandaTangan().set("pasien_2", response[0].ttdpasien2)
        }
        if (response[0].ttdpasien3) {
          H.tandaTangan().set("pasien_3", response[0].ttdpasien3)
        }
        if (response[0].ttdpasien4) {
          H.tandaTangan().set("pasien_4", response[0].ttdpasien4)
        }
        if (response[0].ttdpasien5) {
          H.tandaTangan().set("pasien_5", response[0].ttdpasien5)
        }
        if (response[0].ttdpasien6) {
          H.tandaTangan().set("pasien_6", response[0].ttdpasien6)
        }
        if (response[0].ttdpasien7) {
          H.tandaTangan().set("pasien_7", response[0].ttdpasien7)
        }
        if (response[0].ttdpasien8) {
          H.tandaTangan().set("pasien_8", response[0].ttdpasien8)
        }
        if (response[0].ttdpasien9) {
          H.tandaTangan().set("pasien_9", response[0].ttdpasien9)
        }
        if (response[0].ttdpasien10) {
          H.tandaTangan().set("pasien_10", response[0].ttdpasien10)
        }
        if (response[0].ttdpasien11) {
          H.tandaTangan().set("pasien_11", response[0].ttdpasien11)
        }
        if (response[0].ttdpasien12) {
          H.tandaTangan().set("pasien_12", response[0].ttdpasien12)
        }
        if (response[0].ttdpasien13) {
          H.tandaTangan().set("pasien_13", response[0].ttdpasien13)
        }

        if (!response[0].hakKewajibanPasienNamaSasaran) {
          input.value.hakKewajibanPasienNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        if (!response[0].pengertianPenyakitNamaSasaran) {
          input.value.pengertianPenyakiNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        if (!response[0].masalahKlinisNamaSasaran) {
          input.value.masalahKlinisNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        if (!response[0].tandaGejalaPenyakitNamaSasaran) {
          input.value.tandaGejalaPenyakitNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        if (!response[0].penatalaksanaanPenyakiNamaSasaran) {
          input.value.penatalaksanaanPenyakiNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        if (!response[0].prosedurDiagnostikNamaSasaran) {
          input.value.prosedurDiagnostikNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        if (!response[0].komplikasiNamaSasaran) {
          input.value.komplikasiNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        if (!response[0].kemungkinanHasilNamaSasaran) {
          input.value.kemungkinanHasilNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        if (!response[0].prognosisNamaSasaran) {
          input.value.prognosisNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        if (!response[0].alternatifNamaSasaran) {
          input.value.alternatifNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        if (!response[0].manfaatObatNamaSasaran) {
          input.value.manfaatObatNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        if (!response[0].efekSampingNamaSasaran) {
          input.value.efekSampingNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        if (!response[0].interaksiObatmakananNamaSasaran) {
          input.value.interaksiObatmakananNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        if (!response[0].programDietNamaSasaran) {
          input.value.programDietNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        if (!response[0].ManajemenNyeriNamaSasaran) {
          input.value.ManajemenNyeriNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        if (!response[0].penggunaanAlatKedokteranNamaSasaran) {
          input.value.penggunaanAlatKedokteranNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        if (!response[0].cuciTanganNamaSasaran) {
          input.value.cuciTanganNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        if (!response[0].penggunaanAPDNamaSasaran) {
          input.value.penggunaanAPDNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        if (!response[0].prosedurKeperawatanANamaSasaran) {
          input.value.prosedurKeperawatanANamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        if (!response[0].prosedurKeperawatanBNamaSasaran) {
          input.value.prosedurKeperawatanBNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        if (!response[0].prosedurKeperawatanCNamaSasaran) {
          input.value.prosedurKeperawatanCNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        if (!response[0].prosedurKeperawatanDNamaSasaran) {
          input.value.prosedurKeperawatanDNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        if (!response[0].prosedurKeperawatanENamaSasaran) {
          input.value.prosedurKeperawatanENamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        if (!response[0].prosedurKeperawatanFNamaSasaran) {
          input.value.prosedurKeperawatanFNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        if (!response[0].tekniRehabNamaSasaran) {
          input.value.tekniRehabNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        if (!response[0].tekniRehabNamaSasaran) {
          input.value.tekniRehabNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        if (!response[0].waktuKontrolNamaSasaran) {
          input.value.waktuKontrolNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        if (!response[0].textAre1NamaSasaran) {
          input.value.textAre1NamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        if (!response[0].textAre2NamaSasaran) {
          input.value.textAre2NamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
        }
        setAutofillTerapi()
      }
    })
  //       if (NOREC_EMRPASIEN.value == '') {
  //         NOREC_EMRPASIEN.value = response[0].emrpasienfk
  //       }
  //       dataTTD.value = response[0]
  //     }
  //   })
  // H.tandaTangan().set("edukator_0", dataTTD.value.edukator_0)
  // H.tandaTangan().set("edukator_1", dataTTD.value.edukator_1)
  // H.tandaTangan().set("edukator_2", dataTTD.value.edukator_2)
  // H.tandaTangan().set("edukator_3", dataTTD.value.edukator_3)
  // H.tandaTangan().set("edukator_4", dataTTD.value.edukator_4)
  // H.tandaTangan().set("edukator_5", dataTTD.value.edukator_5)
  // H.tandaTangan().set("edukator_6", dataTTD.value.edukator_6)

  // H.tandaTangan().set("sasaran_0", dataTTD.value.sasaran_0)
  // H.tandaTangan().set("sasaran_1", dataTTD.value.sasaran_1)
  // H.tandaTangan().set("sasaran_2", dataTTD.value.sasaran_2)
  // H.tandaTangan().set("sasaran_3", dataTTD.value.sasaran_3)
  // H.tandaTangan().set("sasaran_4", dataTTD.value.sasaran_4)
  // H.tandaTangan().set("sasaran_5", dataTTD.value.sasaran_5)
  // H.tandaTangan().set("sasaran_6", dataTTD.value.sasaran_6)


}

const fetchPetugas = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Petugas.value = response
  })
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
  object.ttdedukator0 = H.tandaTangan().get("edukator_0")
  object.ttdedukator1 = H.tandaTangan().get("edukator_1")
  object.ttdedukator2 = H.tandaTangan().get("edukator_2")
  object.ttdedukator3 = H.tandaTangan().get("edukator_3")
  object.ttdedukator4 = H.tandaTangan().get("edukator_4")
  object.ttdedukator5 = H.tandaTangan().get("edukator_5")
  object.ttdedukator6 = H.tandaTangan().get("edukator_6")

  object.ttdsasaran0 = H.tandaTangan().get("sasaran_0")
  object.ttdsasaran1 = H.tandaTangan().get("sasaran_1")
  object.ttdsasaran2 = H.tandaTangan().get("sasaran_2")
  object.ttdsasaran3 = H.tandaTangan().get("sasaran_3")
  object.ttdsasaran4 = H.tandaTangan().get("sasaran_4")
  object.ttdsasaran5 = H.tandaTangan().get("sasaran_5")
  object.ttdsasaran6 = H.tandaTangan().get("sasaran_6")

  object.ttdpasien0 = H.tandaTangan().get("pasien_0")
  object.ttdpasien1 = H.tandaTangan().get("pasien_1")
  object.ttdpasien2 = H.tandaTangan().get("pasien_2")
  object.ttdpasien3 = H.tandaTangan().get("pasien_3")
  object.ttdpasien4 = H.tandaTangan().get("pasien_4")
  object.ttdpasien5 = H.tandaTangan().get("pasien_5")
  object.ttdpasien6 = H.tandaTangan().get("pasien_6")
  object.ttdpasien7 = H.tandaTangan().get("pasien_7")
  object.ttdpasien8 = H.tandaTangan().get("pasien_8")
  object.ttdpasien9 = H.tandaTangan().get("pasien_9")
  object.ttdpasien10 = H.tandaTangan().get("pasien_10")
  object.ttdpasien11 = H.tandaTangan().get("pasien_11")
  object.ttdpasien12 = H.tandaTangan().get("pasien_12")
  object.ttdpasien13 = H.tandaTangan().get("pasien_13")

  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)


  // object = input.value
  // for (let index = 0; index < 7; index++) {
  //   if (input.value['tanggal_' + index]) {
  //     object['edukator_' + index] = H.tandaTangan().get("edukator_" + index);
  //     object['sasaran_' + index] = H.tandaTangan().get("sasaran_" + index);
  //   }
  // }
  // object.pasien = H.setObjectPasien(props.pasien)ssss
  // object.registrasi = H.setObjectRegistrasi(props.registrasi)
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

// const fetchPegawai = async (filter: any) => {

//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
//   ).then((response) => {
//     d_Pegawai.value = response
//   })
// }


// const fetchDokter = async (filter: any) => {
//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
//   ).then((response) => {
//     d_Dokter.value = response
//   })
// }

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.textAre2NamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.textAre1NamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.waktuKontrolNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.tekniRehabNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.prosedurKeperawatanFNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.prosedurKeperawatanENamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.prosedurKeperawatanDNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.prosedurKeperawatanCNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.prosedurKeperawatanBNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.prosedurKeperawatanANamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.penggunaanAPDNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.cuciTanganNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.penggunaanAlatKedokteranNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.ManajemenNyeriNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.programDietNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.interaksiObatmakananNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.efekSampingNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.manfaatObatNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.alternatifNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.prognosisNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.kemungkinanHasilNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.komplikasiNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.prosedurDiagnostikNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.penatalaksanaanPenyakiNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.tandaGejalaPenyakitNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.masalahKlinisNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.pengertianPenyakiNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.hakKewajibanPasienNamaEdukator = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
}

const setAutofillTerapi = () => {
  useApi().get(`/farmasi/riwayat-resep-pulang?norec_pd=${props.registrasi.norec_pd}`).then((response) => {
    if (response.length > 0) {
      input.value.obatPulang = ''
      response.forEach((element: any, index: number) => {
        element.details.forEach(item => {
          input.value.obatPulang += item.namaproduk;
          if (index < response.details.length - 1) {
            input.value.obatPulang += ', \n'; // Menambahkan koma dan baris baru
          } else {
            input.value.obatPulang += '\n'; // Untuk produk terakhir, hanya newline tanpa koma
          }
        })
      });
    }
  })
}

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
  width: 150%;
}

.tg td {
  // border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 12px;
  overflow: auto;
  padding: 10px 5px;
  word-break: normal;
}

.tg th {
  // border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 13px;
  font-weight: normal;
  overflow: auto;
  padding: 10px 5px;
  word-break: normal;
}

.tg .tg-0lax {
  text-align: left;
  vertical-align: middle
}
</style>
