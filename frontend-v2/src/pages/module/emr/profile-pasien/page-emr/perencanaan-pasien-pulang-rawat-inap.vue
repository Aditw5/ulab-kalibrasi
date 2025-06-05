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


  <div class="column">
    <VCard>
      <div class="column is-12">
        <VCard class="p-3">
          <div class="column is-12 px-2">
            <div class="columns is-multiline">
              <div class="column is-4">
                <VField label="Tanggal Masuk RS">
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
                <VField label="Ruang Asal">
                  <AutoComplete v-model="input.namaruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                </VField>
              </div>
              <div class="column is-4">
                <VField label="Tanggal Keluar RS">
                  <VDatePicker v-model="input.tanggalKeluarRS" mode="dateTime" style="width: 100%" trim-weeks>
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
            </div>
            <div class="columns is-multiline mb-5">
              <div class="column is-12 P-0">
                <div class="column is-12">
                  <span> Keadaan Saat pulang Dari Rumah Sakit : </span>
                </div>
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.keaadaanSaatPulangRsSembuh" label="Sembuh" class="p-0" color="primary"
                            square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.keaadaanSaatPulangRsPindahRs" label="Pindah ke RS Lain" class="p-0"
                            color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.keaadaanSaatPulangRsRujukanRsLain" label="Rujukan RS Lain" class="p-0"
                            color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.keaadaanSaatPulangMeneruskanberobatJalan"
                            label="Meneruskan Dengan Berobat Jalan" class="p-0" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.keaadaanSaatPulangAtasPermintaanSendiri"
                            label="Atas Permintaan Sendiri" class="p-0" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-4" v-if="input.keaadaanSaatPulangAtasPermintaanSendiri">
                      <VField>
                        <VControl raw subcontrol>
                          <VTextarea rows="2" placeholder="Alasan Atas Permintaan Sendiri....."
                            v-model="input.keaadaanSaatPulangAtasPermintaanSendiriKet"></VTextarea>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.keaadaanSaatPulangMeninggal" label="Meninggal" class="p-0"
                            color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </VCard>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="A. Kontrol" :toggleable="true">
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <span> kontrol : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField label="Waktu">
                      <VDatePicker v-model="input.kontrolWaktu" mode="dateTime" style="width: 100%" trim-weeks>
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
                  <div class="column is-6">
                    <VField label="Tempat">
                      <VInput type="text" class="input" placeholder="Tempat" v-model="input.kontrolTempat" />
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="B. Lanjutan Perawatan di Rumah" :toggleable="true">
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <span> Lanjutan Perawatan di Rumah : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.lanjutanPerawatanRumahPerawatanLukaOperasi"
                          label="Perawatan Luka Operasi" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.lanjutanPerawatanRumahPerawatanDiri"
                          label="Perawatan diri/hifiene perseorangan" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.lanjutanPerawatanRumahPencegahanDecubitus" label="Pencegahan Decubitus"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.lanjutanPerawatanRumahLatihanPergerakanSendi"
                          label="Latihan Pergerakan Sendi (ROM)" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.lanjutanPerawatanRumahLatihanPerawatanKateter" label="Perawatan Kateter"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.lanjutanPerawatanRumahRendamDuduk" label="Rendam Duduk" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.lanjutanPerawatanRumahPemberianMakanNGT"
                          label="Pemberian Makan Melalui NGT" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.lanjutanPerawatanRumahPenyuntikanInsulin" label="Penyuntikan Insulin"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.lanjutanPerawatanRumahPerawatanPayudara" label="Perawatan Payudara"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.lanjutanPerawatanRumahVulva" label="Vulva Hygiene" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.lanjutanPerawatanRumahPerawatanTaliPusat" label="Perawatan Tali Pusat"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.lanjutanPerawatanRumahImunisasiLanjutan" label="Imunisasi Lanjutan"
                          class="p-0" color="primary" square />
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
        <Fieldset class="p-fieldsets" legend="C. Pengaturan Diet Nutrisi" :toggleable="true">
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <span> Pendidikan Gizi : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pendidikanGiziYa" label="Ya" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4" v-if="input.pendidikanGiziYa">
                    <VField>
                      <VControl raw subcontrol>
                        <VInput type="text" class="input" placeholder="Jenis DIet"
                          v-model="input.pendidikanGiziYaJenisDiet" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pendidikanGiziTidak" label="Tidak" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4" v-if="input.pendidikanGiziTidak">
                    <VField>
                      <VControl raw subcontrol>
                        <VInput type="text" class="input" placeholder="Alasan"
                          v-model="input.pendidikanGiziTidakAlasan" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 P-0">
              <div class="column is-12">
                <span> Leaflet : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.leafletAda" label="Ada" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.leafletTidak" label="Tidak" class="p-0" color="primary" square />
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
        <Fieldset class="p-fieldsets"
          legend="D. Pemakaian Alat Bantu Untuk Pemenuhan Nutris Peroral/elimina : NGT/Karakter" :toggleable="true">
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <span>Tanggal Pemasangan</span>
                    <VField>
                      <VDatePicker v-model="input.tanggaPemasanganAlatBantu" mode="dateTime" style="width: 100%"
                        trim-weeks>
                        <template #default="{ inputValue, inputEvents }">
                          <VField>
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" placeholder="Tanggal Pemasangan" v-on="inputEvents" readonly />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <span>Tanggal Ganti Alat</span>
                    <VField>
                      <VDatePicker v-model="input.tanggaGantiAlat" mode="dateTime" style="width: 100%" trim-weeks>
                        <template #default="{ inputValue, inputEvents }">
                          <VField>
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" placeholder="Tanggal Ganti ALat" v-on="inputEvents" readonly />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <span>No. NGT/No. Kateter</span>
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput type="text" class="input" placeholder="No. NGT/No. Kateter"
                          v-model="input.noNgtNoKateter" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <span>Perawatan NGT/Karakter</span>
                    <VField>
                      <VTextarea rows="2" placeholder="Perawatan NGT/Kateter....." v-model="input.perawatanNgtKateter"></VTextarea>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets"
          legend="E. Obat-Obatan Yang Masih Diminum Jumlah dan Yang Harus Diperhatikan Selama Minum Obat" :toggleable="true">
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <span>Obat-Obatan yang masih diminum jumlah dan yang harus diperhatikan selama minum obat (sebutkan) jenis obat dosis dan pemberiannya</span>
                    <VField>
                      <VTextarea rows="2" placeholder="..........." v-model="input.obatObatanYangMasihDiminum"></VTextarea>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets"
          legend="F. Aktifitas dan Istirahat" :toggleable="true">
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <span> Alat Bantu yang digunakan pasien untuk perawatan di rumah : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.alatBantuPasienDirumahWalkerTreepod" label="Walker/Treepod" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.alatBantuPasienDirumahCruch" label="Cruch" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.alatBantuPasienDirumahKursiRoda" label="Kursi Roda" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.alatBantuPasienDirumahTempatTidur" label="Tempat Tidur" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.alatBantuPasienDirumahBruce" label="Bruce" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.alatBantuPasienDirumahNackCollar" label="Nack Collar" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.alatBantuPasienDirumahOksigen" label="Oksigen" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.alatBantuPasienDirumahNebulizer" label="Nebulizer" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.alatBantuPasienDirumahAlatPemantau" label="Alat Pemantau" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.alatBantuPasienDirumahAlatPenghisap" label="Alat Penghisap lender/slym suction" class="p-0" color="primary" square />
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
        <Fieldset class="p-fieldsets"
          legend="G. Pelayanan Kesehatan yang Digunakan Selama Perawatan" :toggleable="true">
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pelayananSelamaPerawatanPelayananDirumah" label="Pelayanan Kesehatan di rumah" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pelayananSelamaPerawatanPuskesmas" label="Puskesmas" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pelayananSelamaPerawatanPraktikMandiri" label="Prkatik Mandiri Perawatan Luka" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pelayananSelamaPerawatanDokterKeluarga" label="Dokter Keluarga" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pelayananSelamaPerawatanFisioterapi" label="Fisioterapi" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pelayananSelamaPerawatanSpeechTerpai" label="Speech Terapi" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pelayananSelamaPerawatanPekerjaSosial" label="Pekerja Sosial" class="p-0" color="primary" square />
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
        <Fieldset class="p-fieldsets"
          legend="H. orang Yang Membantu Pasien saat Perawatan dirumah" :toggleable="true">
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.orangMembantuPerawatanDirumahSuamiIstri" label="Suami/Istri" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.orangMembantuPerawatanDirumahAnak" label="Anak" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.orangMembantuPerawatanDirumahOrangTua" label="orang Tua" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.orangMembantuPerawatanDirumahKakakAdik" label="Kakak/Adik" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.orangMembantuPerawatanDirumahPerawat" label="Perawat" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.orangMembantuPerawatanDirumahPos" label="POS" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.orangMembantuPerawatanDirumahSaudara" label="Saudara" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.orangMembantuPerawatanDirumahLain" label="Lain-Lain" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4" v-if="input.orangMembantuPerawatanDirumahLain">
                    <VField>
                      <VControl raw subcontrol>
                        <VInput type="text" class="input" placeholder="Lain-Lain"
                          v-model="input.orangMembantuPerawatanDirumahLainKet" />
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
        <Fieldset class="p-fieldsets"
          legend="I. Hasil Pemeriksaan Diagostik yang Disertakan Saat Pulang" :toggleable="true">
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pemeriksaanDiagnostikSaatPulangLaboratorium" label="Laboratorium" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pemeriksaanDiagnostikSaatPulangPaAnatomi" label="Patalogi Anatomi" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pemeriksaanDiagnostikSaatPulangEkg" label="EKG" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pemeriksaanDiagnostikSaatPulangFotoRontgen" label="Foto Rontgen" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pemeriksaanDiagnostikSaatPulangUsg" label="Pemeriksaan USG" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pemeriksaanDiagnostikSaatPulangEcho" label="Pemeriksaan ECHO" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pemeriksaanDiagnostikSaatPulangMri" label="Pemeriksaan MRI" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pemeriksaanDiagnostikSaatPulangLain" label="Lain-Lain" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4" v-if="input.pemeriksaanDiagnostikSaatPulangLain">
                    <VField>
                      <VControl raw subcontrol>
                        <VInput type="text" class="input" placeholder="Lain-Lain"
                          v-model="input.pemeriksaanDiagnostikSaatPulangLainKet" />
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
        <Fieldset class="p-fieldsets"
          legend="J. Catatan Khusus Perawat" :toggleable="true">
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.catatanKhusuPerawatCekGulaDarah" label="Cek Gula Darah Sehari Sebelum Kontrol" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.catatanKhusuPerawatFotoRontgenEkgLaboratHarusDibawa" label="Foto Rontgen, EKG, Hasil Pemeriksaan Laboratorium harus dibawa pada saat Kontrol" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.catatanKhusuPerawatBilaAdaKeluhanSegeraKontrol" label="Bila Ada Keluhan Segera Kontrol Ke Rumah Sakit atau Tempat Pelayanan Kesehatan Tersekat " class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.catatanKhusuPerawatLain" label="Lain-Lain" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4" v-if="input.catatanKhusuPerawatLain">
                    <VField>
                      <VControl raw subcontrol>
                        <VInput type="text" class="input" placeholder="Lain-Lain"
                          v-model="input.catatanKhusuPerawatLainKet" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="columns is-multiline">
        <div class="column is-4" style="margin-left: auto;">
          <VCard>
            <h1 style="font-weight: bold;"> Cirebon,  </h1>
            <VField>
              <VDatePicker v-model="input.tglPerawatRuangan" mode="dateTime" style="width: 100%" trim-weeks
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
            <div>
              <!-- <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan> -->
              <img v-if="input.petugasPerawatTerima"
                :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.petugasPerawatTerima ? input.petugasPerawatTerima.label : '-')">
            </div>
            <div>
              <h1 class="p-0" style="font-weight: bold;">Perawat Ruangan</h1>
              <VField>
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.petugasPerawatTerima" :suggestions="d_Pegawai"
                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pegawai..."
                    class="mt-2" @item-select="setTandaTangan($event)" />
                </VControl>
              </VField>
            </div>
          </VCard>
        </div>
        <div class="column is-4" style="margin-left: auto;">
          <VCard>
            <h1 style="font-weight: bold;">Pasien / Keluarga </h1>
            <VField>
              <VDatePicker v-model="input.tglTTDPasienKeluarga" mode="dateTime" style="width: 100%" trim-weeks
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
            <div>
              <TandaTangan :elemenID="'signature_3'" :width="'180'" :height="'180'"></TandaTangan>
            </div>
            <div class="mt-4">
              <VField>
                <VControl class="prime-auto">
                  <VInput type="text" class="input prime-auto" placeholder="Pasien/Keluarga" v-model="input.namapasien"
                    :suggestions="d_Pasien" @complete="fetchPasien($event)" />
                </VControl>
              </VField>
            </div>
          </VCard>
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
import * as EMR from '../page-emr-plugins/pengkajian-awal-pj'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let vitalSign = ref(EMR.vitalSign())

const props = withDefaults(
  defineProps<{
    pasien?: any
    registrasi?: any
    diagnosis?: any
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
const isLoadingVitalSign: any = ref(false)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const d_Ruangan: any = ref([])
const d_produk: any = ref([])
const d_Kelas: any = ref([])
const d_Pasien: any = ref([])
const d_Diagnosa: any = ref([])
const isLoadingTT: any = ref(false)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const input: any = ref({
  detailObatDibawaPasien: [{ no: 1 }]
})
const COLLECTION: any = ref('PerencanaanPasienPulangRawatInap') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
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
        if (response[0].tandaTanganPerawatRuangan) {
          H.tandaTangan().set("signature_2", response[0].tandaTanganPerawatRuangan)
        }
        if (response[0].tandaTanganKeluargaPasien) {
          H.tandaTangan().set("signature_3", response[0].tandaTanganKeluargaPasien)
        }
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }
    })
}

const addNewItemObatDibawaPulang = () => {
  input.value.detailObatDibawaPasien.push({
    no: input.value.detailObatDibawaPasien[input.value.detailObatDibawaPasien.length - 1].no + 1,
  });
}
const removeItemObatDibawaPulang = (index: any) => {
  input.value.detailObatDibawaPasien.splice(index, 1)
}

const fetchProduk = async (filter: any) => {
  const response = await useApi().get(
    `/farmasi/dropdown-obat?namaproduk=${filter.query}&limit=10`)
  d_produk.value = response

}

const fetchPasien = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/pasien_m?select=id,namapasien&param_search=namapasien&query=${filter.query}&limit=10`)
  d_Pasien.value = response
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
    H.tandaTangan().set("signature_2", response.ttd)
    input.value.tandaTanganDokter = response.ttd
  } else {
    H.tandaTangan().set("signature_2", '')
  }
}

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}

const fetchDokter = async (filter: any) => {
  const response = await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`)
  d_Dokter.value = response
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.tandaTanganPerawatRuangan = H.tandaTangan().get("signature_2")
  object.tandaTanganKeluargaPasien = H.tandaTangan().get("signature_3")
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
  input.value.tanggalWaktuRegistrasi = props.registrasi.tglregistrasi
  input.value.tanggalWaktuKunjuangan = new Date()
  input.value.tglPembuatan = new Date()
  input.value.umur = props.pasien.umur
  input.value.namaruangan = props.registrasi.namaruangan
  input.value.dokter = props.registrasi.dokter
  input.value.namapasien = props.pasien.namapasien
  input.value.petugasPerawatTerima = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  // input.value.namadiagnosa = props.diagnosis.namadiagnosa

  isLoadingVitalSign.value = true;
  await useApi().get(
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=VitalSign" + "&field=beratBadan,tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi"
  ).then((response) => {
    if (response != null) {
      input.value.beratBadan = response.beratBadan
      input.value.tinggiBadan = response.tinggiBadan
      input.value.IMT = response.IMT
      input.value.lingkarPerut = response.lingkarPerut
      input.value.tekananDarah = response.tekananDarah
      input.value.pernapasan = response.pernapasan
      input.value.suhu = response.suhu
      input.value.nadi = response.nadi
    }
    isLoadingVitalSign.value = false;
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
</style>
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
