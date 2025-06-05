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
    <div class="column is-12 ">
      <VCard>
        <div class="columns is-multiline p-2">
          <VCard>
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-12 P-0">
                  <div class="column is-12">
                    <div class="columns is-multiline">
                      <div class="column is-3">
                        <VField>
                          <h1 style="font-weight: bold;">1. Tanggal</h1>
                          <VDatePicker v-model="input.tanggal" mode="date" style="width: 100%" trim-weeks
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
                      <div class="column is-3">
                        <VField>
                          <h1 style="font-weight: bold;">2. Nama Bidan</h1>
                          <VControl>
                            <VInput type="text" v-model="input.namaBidan" />
                          </VControl>
                        </VField>
                      </div>
                      
                    </div>
                  </div>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VField>
                    <h1 style="font-weight: bold;">3. Tempat Persalinan: </h1>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.rumahIbu" label="Rumah Ibu" class="p-0" color="primary"
                        square />
                    </VControl>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.polindas" label="Polindas" class="p-0" color="primary"
                        square />
                    </VControl>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.klinikSwasta" label="Klinik Swasta" class="p-0" color="primary"
                        square />
                    </VControl>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.puskesmas" label="Puskesmas" class="p-0" color="primary"
                        square />
                    </VControl>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.rumahSakit" label="Rumah Sakit" class="p-0" color="primary"
                        square />
                    </VControl>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.lainnya" label="Lainnya" class="p-0" color="primary"
                        square />
                      <VInput type="text" v-model="input.inputLainnya" />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VField>
                    <h1 style="font-weight: bold;">4. Alamat Tempat Persalinan :</h1>
                    <VControl class="mt-2">
                      <VTextarea rows="3" placeholder="Alamat tempat persalinan..." v-model="input.alamatPersalinan">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VField>
                    <h1 style="font-weight: bold;">5. Catatan: </h1>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.checkboxCatatan" label="Rujuk, kala I/II/III/IV" class="p-0" color="primary"
                        square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-4">
                  <VField>
                    <h1 style="font-weight: bold;">6. Alasan Merujuk : </h1>
                    <VControl class="mt-2">
                      <VTextarea rows="3" placeholder="Alasan Merujuk..." v-model="input.alasanMerujuk">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField>
                    <h1 style="font-weight: bold;">7. Tempat Rujukan: </h1>
                    <VControl class="mt-2">
                      <VTextarea rows="3" placeholder="Tempat rujukan..." v-model="input.tempatRujukan">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <VField>
                    <h1 style="font-weight: bold;">8. Pendamping pada saat merujuk: </h1>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.pendampingBidan" label="Bidan" class="p-0 m-1" color="primary" square />
                    </VControl>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.pendampingTeman" label="Teman" class="p-0 m-1" color="primary" square />
                    </VControl>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.pendampingSuami" label="Suami" class="p-0 m-1" color="primary" square />
                    </VControl>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.pendampingDukun" label="Dukun" class="p-0 m-1" color="primary" square />
                    </VControl>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.pendampingKeluarga" label="Keluarga" class="p-0 m-1" color="primary" square />
                    </VControl>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.pendampingTidak" label="Tidak Ada" class="p-0 m-1" color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <VField>
                    <h1 style="font-weight: bold;">9. Masalah dalam kehamilan / persalinan ini: </h1>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.masalahkehamilanGD" label="Gawat Darurat" class="p-0 m-1" color="primary" square />
                    </VControl>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.masalahkehamilanPendarahan" label="Pendarahan" class="p-0 m-1" color="primary" square />
                    </VControl>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.masalahkehamilanHDK" label="HDK" class="p-0 m-1" color="primary" square />
                    </VControl>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.masalahkehamilanInfeksi" label="Infeksi" class="p-0 m-1" color="primary" square />
                    </VControl>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.masalahkehamilanPMTCT" label="PMTCT" class="p-0 m-1" color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </VCard>
        </div>
        <div class="column is-12">
          <Fieldset class="p-fieldsets" legend="Kala I" :toggleable="true">
            <div class="columns is-multiline mb-5">
              <div class="column is-12 P-0">
                <div class="column is-12">
                  <VField>
                    <h1 style="font-weight: bold;">10. Partogram melewati garis waspada: </h1>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.partogramY" label="Y" class="p-1" color="primary" square />
                    </VControl>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.partogramT" label="T" class="p-1" color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField>
                    <h1 style="font-weight: bold;">11. Masalah lain, sebutkan: </h1>
                    <VControl class="mt-2">
                      <VTextarea rows="3" placeholder="Sebutkan..." v-model="input.masalahLainSebutkan">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField>
                    <h1 style="font-weight: bold;">12. Penatalaksanaan masalah tsb: </h1>
                    <VControl class="mt-2">
                      <VTextarea rows="3" placeholder="Sebutkan..." v-model="input.penatalaksanaanMasalah">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField>
                    <h1 style="font-weight: bold;">13. Hasilnya: </h1>
                    <VControl class="mt-2">
                      <VTextarea rows="3" placeholder="Sebutkan..." v-model="input.hasilnyaKala1">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </Fieldset>
        </div>
        <div class="columns is-multiline">
          <div class="column is-12">
            <Fieldset :toggleable="true" legend="Kala II">
              <div class="columns is-multiline mb-5">
                <div class="column is-12 P-0">
                  <div class="column is-12">
                    <VField>
                      <h1 style="font-weight: bold;">14. Episiotomi: </h1>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.episiotomiYa" label="Ya, indikasi" class="p-1" color="primary" square /> 
                        <div class="column is-4">
                          <VInput type="text" v-model="input.episiotomiIndikasi" />
                        </div>
                      </VControl>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.episiotomiTidak" label="Tidak" class="p-1" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VField>
                      <h1 style="font-weight: bold;">15. Pendamping pada saat persalinan: </h1>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pendampingPersalinanSuami" label="Suami" class="p-1" color="primary" square />
                      </VControl>
                      <VControl raw subcontrol> 
                        <VCheckbox v-model="input.pendampingPersalinanTeman" label="Teman" class="p-1" color="primary" square />
                      </VControl>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pendampingPersalinanKeluarga" label="Keluarga" class="p-1" color="primary" square />
                      </VControl>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pendampingPersalinanDukun" label="Dukun" class="p-1" color="primary" square />
                      </VControl>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pendampingPersalinanTidak" label="Tidak Ada" class="p-1" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VField>
                      <h1 style="font-weight: bold;">16. Gawat janin: </h1>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.gawatJaninYa" label="Ya, tindakan yang dilakukan" class="p-1" color="primary" square />
                        <VField>
                          <VControl>
                            <div class="column is-5">
                              a. <VInput type="text" v-model="input.gawatJaninA" />
                              b. <VInput type="text" v-model="input.gawatJaninB" />
                            </div>
                          </VControl>
                        </VField>
                      </VControl>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.gawatJaninTidak" label="Tidak" class="p-1" color="primary" square />
                      </VControl>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.gawatJaninDJJ" label="Pemantauan DJJ setiap 5-10 menit selama kala II, hasil:" class="p-1" color="primary" square />
                        <div class="column is-5">
                          <VInput type="text" v-model="input.gawatJaninDJJInput" />
                        </div>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VField>
                      <h1 style="font-weight: bold;">17. Distosia Bahu: </h1>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.DistosiaYa" label="Ya, tindakan yang dilakukan" class="p-1" color="primary" square />
                        <VField>
                          <VControl>
                            <div class="column is-5">
                              a. <VInput type="text" v-model="input.DistosiaYaInput" />
                            </div>
                          </VControl>
                        </VField>
                      </VControl>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.DistosiaTidak" label="Tidak" class="p-1" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <h1 style="font-weight: bold;">18. Masalah lain, penatalaksanaan masalah tsb dan hasilnya: </h1>
                      <VControl class="mt-2">
                        <VTextarea rows="3" placeholder="Masalah lain..." v-model="input.masalahLain18">
                        </VTextarea>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </Fieldset>
          </div>
          <div class="column is-12">
            <Fieldset :toggleable="true" legend="Kala III">
              <div class="columns is-multiline mb-5">
                <div class="column is-12 P-0">
                  <div class="column is-12">
                    <VField>
                      <h1 style="font-weight: bold;">19. Inisiasi Menyusu Dini: </h1>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.InisiasiYa" label="Ya" class="p-1" color="primary" square /> 
                      </VControl>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.InisiasiTidak" label="Tidak, Alasannya" class="p-1" color="primary" square />
                        <div class="column is-4">
                          <VInput type="text" v-model="input.InisiasiTidakInput" />
                        </div>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VField>
                      <h1 style="font-weight: bold;">20. Lama Kala III: </h1>
                      <div class="column is-4">
                        <VInput type="text" v-model="input.lamaKalaTiga" />
                      </div>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VField>
                      <h1 style="font-weight: bold;">21. Pemberian Oksitosin 10 UI secara IM? </h1>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.PemberianOksitosinYa" label="Ya, waktu" class="p-1" color="primary" square />
                        <VField>
                          <VControl>
                            <div class="column is-5">
                              <VInput type="text" v-model="input.PemberianOksitosinYaInput" placeholder="menit sesudah persalinan" />
                            </div>
                          </VControl>
                        </VField>
                      </VControl>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.PemberianOksitosinTidak" label="Tidak, alasan" class="p-1" color="primary" square />
                        <VControl>
                            <div class="column is-5">
                              <VInput type="text" v-model="input.PemberianOksitosinTidakAlasan" />
                            </div>
                          </VControl>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VField>
                      <h1 style="font-weight: bold;">22. Penjepitan tali pusat </h1>
                      <VControl raw subcontrol>
                        <VField>
                          <VControl>
                            <div class="column is-5">
                              <VInput type="text" v-model="input.PenjepitanPusat" placeholder="menit setelah bayi lahir" />
                            </div>
                          </VControl>
                        </VField>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VField>
                      <h1 style="font-weight: bold;">23. Pemberian Ulang Oksitosin (2x)? </h1>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.PemberianUlangYa" label="Ya, alasan" class="p-1" color="primary" square />
                        <VField>
                          <VControl>
                            <div class="column is-5">
                              <VInput type="text" v-model="input.PemberianUlangYaInput" />
                            </div>
                          </VControl>
                        </VField>
                      </VControl>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.PemberianUlangTidak" label="Tidak" class="p-1" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VField>
                      <h1 style="font-weight: bold;">24. Penanganan Tali Pusat Terkendali? </h1>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.penangananTaliPusatYa" label="Ya" class="p-1" color="primary" square />
                      </VControl>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.penangananTaliPusatTidak" label="Tidak" class="p-1" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VField>
                      <h1 style="font-weight: bold;">25. Massase fundus uteri? </h1>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.MassaseYa" label="Ya" class="p-1" color="primary" square />
                      </VControl>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.MassaseTidak" label="Tidak, Alasan" class="p-1" color="primary" square />
                        <div class="column is-5">
                          <VInput type="text" v-model="input.MassaseTidakInput" />
                        </div>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VField>
                      <h1 style="font-weight: bold;">26. Plasenta lahir lengkap(intact):  </h1>
                      <VControl> <VCheckbox v-model="input.plasentaYa" label="Ya" class="p-1" color="primary" square /> </VControl>
                    </VField>
                    <VField>
                      <VControl raw subcontrol>
                        <VField> <VControl> <VCheckbox v-model="input.plasentaTidak" label="Tidak" class="p-1" color="primary" square /> </VControl> </VField>
                      </VControl>
                    </VField>
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.plasentaJika" label="Jika tidak lengkap, tindakan yang dilakukan" class="p-1" color="primary" square />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl>
                          <div class="column is-5">
                            a. <VInput type="text" v-model="input.plasentaJikaA" />
                            b. <VInput type="text" v-model="input.plasentaJikaB" />
                          </div>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VField>
                      <h1 style="font-weight: bold;">27. Plasenta tidak lahir > 30 menit: </h1>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.plasentaTidakLahirTidak" label="Tidak" class="p-1" color="primary" square />
                      </VControl>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.plasentaTidakLahirYa" label="Ya, tindakan" class="p-1" color="primary" square />
                        <div class="column is-5">
                          <VInput type="text" v-model="input.plasentaTidakLahirYaInput" />
                        </div>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VField>
                      <h1 style="font-weight: bold;">28. Laserasi: </h1>                      
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.laserasiYa" label="Ya, dimana" class="p-1" color="primary" square />
                        <div class="column is-5">
                          <VInput type="text" v-model="input.laserasiYaInput" />
                        </div>
                      </VControl>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.laserasiTidak" label="Tidak" class="p-1" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VField>
                      <h1 style="font-weight: bold;">29. Jika laserasi perineum, derajat: </h1>                      
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Jikalaserasi1" label="1" class="p-1" color="primary" square />                       
                      </VControl>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Jikalaserasi2" label="2" class="p-1" color="primary" square />                       
                      </VControl>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Jikalaserasi3" label="3" class="p-1" color="primary" square />                        
                      </VControl>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Jikalaserasi4" label="4" class="p-1" color="primary" square />                      
                      </VControl>
                      Tindakan
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.laserasiperineumYa" label="Penjahitan, dengan / tanpa anastesi" class="p-1" color="primary" square />                        
                      </VControl>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.laserasiperineumTidak" label="Tidak dijahit, Alasan" class="p-1" color="primary" square />
                        <div class="column is-5">
                          <VInput type="text" v-model="input.laserasiperineumTidakInput" />
                        </div>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VField>
                      <h1 style="font-weight: bold;">30. Atonia Uteri: </h1>                      
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.AtoniaUteriYa" label="Ya, tindakan" class="p-1" color="primary" square />
                        <div class="column is-5">
                          <VInput type="text" v-model="input.AtoniaUteriYaInput" />
                        </div>
                      </VControl>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.AtoniaUteriTidak" label="Tidak" class="p-1" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VField>
                      <h1 style="font-weight: bold;">31. Jumlah darah yang keluar / pendarahan: </h1>                      
                      <VControl raw subcontrol>
                        <div class="column is-7">
                          <VInput type="text" v-model="input.jumlahPendarahan" />
                        </div>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VField>
                      <h1 style="font-weight: bold;">32. Masalah dan penatalaksanaan masalah: </h1>                      
                      <VControl raw subcontrol>
                        <div class="column is-7">
                          <VInput type="text" v-model="input.masalahPenataLaksanaan" />
                        </div>
                      </VControl>
                      Hasilnya
                      <VControl raw subcontrol>
                        <div class="column is-7">
                          <VInput type="text" v-model="input.hasilmasalahPenataLaksanaan" />
                        </div>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </Fieldset>
          </div>
          <div class="column is-12">
            <Fieldset :toggleable="true" legend="Kala IV">
              <div class="columns is-multiline mb-5">
                <div class="column is-12 P-0">
                  <div class="column is-12">
                    <VField>
                      <h1 style="font-weight: bold;">33. Kondisi Ibu: </h1>
                      <VControl raw subcontrol>
                        KU
                        <div class="column is-4">
                          <VInput type="text" v-model="input.kondisiIbuKU" />
                        </div>
                        TD
                        <div class="column is-4">
                          <VInput type="text" v-model="input.kondisiIbuTD" placeholder="mmHg"/>
                        </div>
                        Nadi
                        <div class="column is-4">
                          <VInput type="text" v-model="input.kondisiIbuNadi" placeholder="x/menit"/>
                        </div>
                        Napas
                        <div class="column is-4">
                          <VInput type="text" v-model="input.kondisiIbuNapas" placeholder="x/menit"/>
                        </div>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <h1 style="font-weight: bold;">34. Masalah dan penatalaksanaan masalah: </h1>
                      <VControl class="mt-2">
                        <VTextarea rows="3" placeholder="Masalah lain..." v-model="input.masalahPenataLaksanaanMasalah">
                        </VTextarea>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </Fieldset>
          </div>
          <div class="column is-12">
            <Fieldset :toggleable="true" legend="BAYI BARU LAHIR">
              <div class="columns is-multiline mb-5">
                <div class="column is-12 P-0">
                  <div class="column is-12">
                    <VField>
                      <h1 style="font-weight: bold;">35. Berat badan: </h1>
                      <VControl raw subcontrol>
                        <div class="column is-4">
                          <VInput type="text" v-model="input.beratBadan" placeholder="gram"/>
                        </div>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <h1 style="font-weight: bold;">36. Panjang Badan: </h1>
                      <VControl class="mt-2">
                        <VInput type="text" v-model="input.panjangBadan" placeholder="cm"/>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <h1 style="font-weight: bold;">37. Jenis Kelamin:  </h1>
                      <VControl class="mt-2"> <VCheckbox v-model="input.jenisKelaminL" label="L" class="p-1" color="primary" square /> </VControl> 
                    </VField>
                    <VField>
                      <VControl class="mt-2"><VCheckbox v-model="input.jenisKelaminP" label="P" class="p-1" color="primary" square /> </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <h1 style="font-weight: bold;">38. Penilaian bayi baru lahir:  </h1>
                      <VCheckbox v-model="input.penilaianBayiBaik" label="Baik" class="p-1" color="primary" square />
                    </VField>
                    <VField>
                      <VCheckbox v-model="input.penilaianBayiNyulit" label="Ada Penyulit" class="p-1" color="primary" square />
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VField>
                      <h1 style="font-weight: bold;">39. Bayi Lahir: </h1>
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.bayiLahirNormal" label="Normal, tindakan:" class="p-1" color="primary" square /> 
                        </VControl>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.bayiLahirMengeringkan" label="Mengeringkan" class="ml-1" color="primary" square />
                        </VControl>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.bayiLahirMenghangatkan" label="Menghangatkan" class="ml-1" color="primary" square />
                        </VControl>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.bayiLahirRK" label="Rangsang taktil" class="ml-1" color="primary" square />
                        </VControl>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.bayiLahirPakaian" label="Pakian / selimut bayi dan tempatkan disisi ibu" class="ml-1" color="primary" square />
                        </VControl>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.bayiLahirTindakan" label="Tindakan pencegahan infeksi mata" class="ml-1" color="primary" square />
                        </VControl>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.bayiLahirAsfiksia" label="Asfiksia ringan / pucat / biru / lemas, tindakan" class="p-1" color="primary" square />
                        </VControl>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.bayiLahirMengeringkan2" label="Mengeringkan" class="ml-1" color="primary" square />
                        </VControl>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.bayiLahirMenghangatkan2" label="Menghangatkan" class="ml-1" color="primary" square />
                        </VControl>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.bayiLahirRangsangan2" label="Rangsangan taktil" class="ml-1" color="primary" square />
                        </VControl>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.bayiLahirBebaskan" label="Bebaskan jalan nafas" class="ml-1" color="primary" square />
                          <VInput type="text" v-model="input.bayiLahirBebaskanInput" style="width: 35%;"/>
                        </VControl>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.bayiLahirPakaian2" label="Pakaian / selimut bayi dan tempatkan disisi ibu" class="ml-1" color="primary" square />
                        </VControl>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.bayiLahirTindakan2" label="Tindakan pencegahan infeksi mata" class="ml-1" color="primary" square />
                        </VControl>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.bayiLahirCacat" label="Cacat bawaan, sebutkan:" class="p-1" color="primary" square />
                          <VInput type="text" v-model="input.bayiLahirCacatInput" style="width: 35%;"/>
                        </VControl>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.bayiLahirHipotermi" label="Hipotermi, tindakan:" class="p-1" color="primary" square />
                        </VControl>
                        <VControl raw subcontrol>
                          a. <VInput type="text" v-model="input.bayiLahirA" style="width: 35%; margin: 5px;"/> <br>
                          b. <VInput type="text" v-model="input.bayiLahirB" style="width: 35%; margin: 5px;"/> <br>
                          c. <VInput type="text" v-model="input.bayiLahirC" style="width: 35%; margin: 5px;"/>
                        </VControl>
                      </VField>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VField>
                      <h1 style="font-weight: bold;">40. Pemberian ASI setelah jam pertama bayi lahir: </h1>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pemberianASIYa" label="Ya, waktu" class="p-1" color="primary" square /> <VInput type="text" v-model="input.pemberianASIYaInput" style="width: 35%;"/> Jam setelah bayi lahir<br>
                      </VControl>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pemberianASITidak" label="Tidak, alasan" class="p-1" color="primary" style="margin-top: 10px;" square /> 
                        <VInput type="text" v-model="input.pemberianASITidakInput" style="width: 35%; margin-top: 10px;"/>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VField>
                      <h1 style="font-weight: bold;">41. Masalah lain, sebutkan: </h1>
                      <VInput type="text" v-model="input.masalahLain41" style="width: 35%;"/>
                      <br>
                      Hasilnya : <br>
                      <VInput type="text" v-model="input.masalahLainHasilnya" style="width: 40%;"/>
                    </VField>
                  </div>
                </div>
              </div>
            </Fieldset>
          </div>
          <div class="column is-12">
            <Fieldset class="p-fieldsets" legend="TABEL PEMANTAUAN KALA IV" :toggleable="true">
              <div class="columns is-multiline mb-5">
                <div class="column is-12 P-0">
                  <table class="tg">
                    <thead>
                      <tr>
                        <td class="tg-0lax text-center">Jam ke</td>
                        <td class="tg-0lax text-center">Waktu</td>
                        <td class="tg-0lax text-center">Tekanan Darah</td>
                        <td class="tg-0lax text-center">Nadi</td>
                        <td class="tg-0lax text-center">Suhu</td>
                        <td class="tg-0lax text-center">Tinggi Fundus Uteri</td>
                        <td class="tg-0lax text-center">Kontraksi Uterus</td>
                        <td class="tg-0lax text-center">Kandung Kemih</td>
                        <td class="tg-0lax text-center">Darah yang Keluar</td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td width="4%" style="text-align: center;" rowspan="4">1</td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.satu"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.dua"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.tiga"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.empat"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.lima"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.enam"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.tujuh"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.delapan"/>
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.sembilan"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.sepuluh"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.sebelas"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.duabelas"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.tigabelas"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.empatbelas"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.limabelas"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.enambelas"/>
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.tujubelas"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.lapanbelas"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.bilanbelas"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.duapuluh"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.duasatu"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.duadua"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.duatiga"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.duaempat"/>
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.dualima"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.duaenam"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.duatujuh"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.dualapan"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.duasembilan"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.tigapuluh"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.tigasatu"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.tigadua"/>
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td width="4%" style="text-align: center;" rowspan="2">2</td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.tigatiga"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.tigaempat"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.tigalima"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.tigaenam"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.tigatujuh"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.tigalapan"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.tigasembilan"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.empatpuluh"/>
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.empatsatu"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.empatdua"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.empattiga"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.empatempat"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.empatlima"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.empatEnam"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.empatTujuh"/>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" v-model="input.empatLapan"/>
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </Fieldset>
          </div>
          <div clas="mt-5">
              <div class="column is-12">
                <h1 style="font-weight: bold;"> Cirebon, Tanggal </h1>
                <VField class="mt-3">
                  <VDatePicker v-model="input.tglPembuatan1" mode="dateTime" style="width: 100%" trim-weeks
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
              <!-- <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <img v-if="input.dpjpIGD"
                  :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.dpjpIGD.label ? input.dpjpIGD.label : input.dpjpIGD) + ', ' + (input.tglPembuatan ? input.tglPembuatan : input.tglPembuatan)">
                  </div>
                  <div class="column is-6 ">
                    <img v-if="input.ppjpIGD"
                  :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.ppjpIGD ? input.ppjpIGD.label : '-')">
                  </div>
                  <div class="column is-6">
                    <h1 class="p-0" style="font-weight: bold;">Dokter IGD</h1>
                    <VField>
                      <VControl class="prime-auto">
                        <AutoComplete v-model="input.dpjpIGD" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="DPJP IGD..." class="mt-2"
                          @item-select="setTandaTangan($event, 'signature_1')" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4 is-offset-2">
                    <h1 class="p-0" style="font-weight: bold;">PPJP Triase</h1>
                    <VField>
                      <VControl class="prime-auto">
                        <AutoComplete v-model="input.ppjpIGD" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="PPJP IGD..." class="mt-2"
                          @item-select="setTandaTangan($event, 'signature_2')" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div> -->
            </div>
        </div>
      </VCard>
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
  <Dialog v-model:visible="isRad" modal header="Order Radiologi" :style="{ width: '80vw' }">
    <OrderRad :pasien="props.pasien" :registrasi="props.registrasi" />
    <template #footer>
      <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="tutupRadSetAutofill()">
        Tutup
      </VButton>
    </template>
  </Dialog>
  <Dialog v-model:visible="isKonsul" modal header="Konsultasi" :style="{ width: '80vw' }">
    <Konsul :pasien="props.pasien" :registrasi="props.registrasi" />
    <template #footer>
      <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="tutupDanSetAutofill()">
        Tutup
      </VButton>
    </template>
  </Dialog>
  <VModal :open="modalBerkasPasien" size="big" title="Berkas Pasien" actions="right" @close="modalBerkasPasien = false">
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-12">
            <TBerkasPasien  :pasien="props.pasien" :registrasi="props.registrasi"/>
          </div>
        </div>
      </form>
    </template>
    <!-- <template #action>
      <VButton icon="feather:plus" @click="save()" :loading="isLoadingTT" color="primary" raised>Simpan</VButton>
    </template> -->
  </VModal>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import TBerkasPasien from './berkas-pasien.vue'
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
import Dialog from 'primevue/dialog';
import OrderLab from './order-laboratorium.vue'
import OrderRad from './order-radiologi.vue'
import Konsul from './konsultasi.vue'
import * as EMR from '../page-emr-plugins/pengkajian-dokter-igd'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let vitalSign = ref(EMR.vitalSign())
const d_DiagnosaKeperawatan: any = ref([])
const isLab: any = ref(false)
const isRad: any = ref(false)
const isKonsul: any = ref(false)
const modalBerkasPasien = ref(false)
const d_JenisDiagnosa: any = ref([])
const d_Diagnosa: any = ref([])
const d_Diagnosa9: any = ref([])
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
const isLoadingVitalSign: any = ref(false)
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value >= 20 })
const isLoading: any = ref(false)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const d_produk: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('CatatanPersalinan') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  detailObatDibawaPasien: [{ no: 1 }],
  detailObatPerawatanIGD: [{ no: 1 }],
  detailObatRawatInap: [{ no: 1 }],
  diagnosaICD10: [{ no: 1 }],
  diagnosaICD9: [{ no: 1 }],
})

const fetchJenisDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/jenisdiagnosa_m?select=id,jenisdiagnosa&param_search=jenisdiagnosa&query=${filter.query}&limit=10`)
  d_JenisDiagnosa.value = response
}

const fetchDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/diagnosa/diagnosa-x-paging?name=${filter.query}&limit=10`)

  d_Diagnosa.value = response.diagnosa.map((item: any) => {
    return { value: item.id, label: item.kddiagnosa + ' - ' + item.namadiagnosa }
  })
}

const fetchDiagnosa9 = async (filter: any) => {
  const response = await useApi().get(
    `/diagnosa/diagnosa-ix-paging?name=${filter.query}&limit=10`)

  d_Diagnosa9.value = response.diagnosatindakan.map((item: any) => {
    return { value: item.id, label: item.kddiagnosatindakan + ' - ' + item.namadiagnosatindakan, default: item }
  })
}

const fetchDiagnosaKeperawatan = async (filter: any) => {
  await useApi().get(`/emr/get-dropdown-diagnosa-keperawatan?search=${filter.query}`).then((response) => {
    d_DiagnosaKeperawatan.value = response
  })
}
const addNewDiagnosaicd10 = (item) => {
  if(!item.keterangan && !item.ICD10Diagnosa){
    H.alert('warning', 'Diagnosa dokter atau ICD X harus ada')
    return
  }
  input.value.diagnosaICD10.push({
    no: input.value.diagnosaICD10[input.value.diagnosaICD10.length - 1].no + 1,
  });
}
const removeItemDiagnosaIcd10 = (index: any) => {
  input.value.diagnosaICD10.splice(index, 1)
}
const addNewDiagnosaicd9 = () => {
  input.value.diagnosaICD9.push({
    no: input.value.diagnosaICD9[input.value.diagnosaICD9.length - 1].no + 1,
  });
}
const removeItemDiagnosaIcd9 = (index: any) => {
  input.value.diagnosaICD9.splice(index, 1)
}
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
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
  const dataDiagnosa = input.value.diagnosaICD10
  dataDiagnosa.forEach(element=>{
    let diagnosaSave = {
          'diagnosapasien': {
              'norec': element.norec ? element.norec : '',
              'noregistrasifk': props.registrasi.norec_apd,
              'tglregistrasi': props.registrasi.tglregistrasi,
              'ketdiagnosis': element.keterangan ? element.keterangan : null,
              'iskasusbaru': null,
              'iskasuslama': null,
          },
          'detaildiagnosapasien': {
              'objectdiagnosafk': element.ICD10Diagnosa ? element.ICD10Diagnosa.value : null,
              'tglinputdiagnosa': H.formatDate(new Date(), 'YYYY-MM-DD HH:mm:ss'),
              'objectjenisdiagnosafk': null,
              'noregistrasi': props.registrasi.noregistrasi
          },
          'pasien' : {
            'nocm': props.pasien.nocm,
            'namapasien': props.pasien.namapasien,
            'noregistrasi': props.registrasi.noregistrasi,
          }

      }
        useApi().post(
        `/diagnosa/save-diagnosa-selesai`, diagnosaSave).then((response: any) => {
          element.norec = response.norec
        }).catch((e: any) => {
            console.error(e);

        })
  })

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

function add() {
  modalBerkasPasien.value = true
}


let kesadaran = ref([
  {
    "no": 1,
    "parameter": "E",
    "nilai": [
      {
        "model": "kesadaranE",
        "poin": "1"
      },
      {
        "model": "kesadaranE",
        "poin": "2"
      },
      {
        "model": "kesadaranE",
        "poin": "3"
      },
      {
        "model": "kesadaranE",
        "poin": "4"
      },
    ]
  },
  {
    "no": 2,
    "parameter": "M",
    "nilai": [
      {
        "model": "kesadaranM",
        "poin": "1"
      },
      {
        "model": "kesadaranM",
        "poin": "2"
      },
      {
        "model": "kesadaranM",
        "poin": "3"
      },
      {
        "model": "kesadaranM",
        "poin": "4"
      },
      {
        "model": "kesadaranM",
        "poin": "5"
      },
      {
        "model": "kesadaranM",
        "poin": "6"
      },
    ]
  },
  {
    "no": 3,
    "parameter": "V",
    "nilai": [
      {
        "model": "kesadaranV",
        "poin": "1"
      },
      {
        "model": "kesadaranV",
        "poin": "2"
      },
      {
        "model": "kesadaranV",
        "poin": "3"
      },
      {
        "model": "kesadaranV",
        "poin": "4"
      },
      {
        "model": "kesadaranV",
        "poin": "5"
      },
    ]
  },
])

watch(() => [
  input.value.checkAll
],()=>{
  if(input.value.checkAll == 'Normal'){
    input.value.fisikAbdomen = 'Normal'
    input.value.fisikKepala = 'Normal'
    input.value.fisikLeher = 'Normal'
    input.value.fisikEkstremitas = 'Normal'
    input.value.fisikThoraks = 'Normal'
    input.value.fisikLainnya = 'Normal'
  }

  if(input.value.checkAll == 'Abnormal'){
    input.value.fisikAbdomen = 'Abnormal'
    input.value.fisikKepala = 'Abnormal'
    input.value.fisikLeher = 'Abnormal'
    input.value.fisikEkstremitas = 'Abnormal'
    input.value.fisikThoraks = 'Abnormal'
    input.value.fisikLainnya = 'Abnormal'
  }
})

watch(() => [
  input.value.kesadaranE,
  input.value.kesadaranM,
  input.value.kesadaranV,
], () => {

  let poin1 = input.value.kesadaranE ? parseInt(input.value.kesadaranE) : 0
  let poin2 = input.value.kesadaranM ? parseInt(input.value.kesadaranM) : 0
  let poin3 = input.value.kesadaranV ? parseInt(input.value.kesadaranV) : 0

  const total = poin1 + poin2 + poin3

  input.value.KesadaranCM = false;
  input.value.KesadaranApatis = false;
  input.value.KesadaranDelirium = false;
  input.value.KesadaranSomnolen = false;
  input.value.KesadaranSopor = false;
  input.value.KesadaranComa = false;
  if (total == 15 || total == 14) {
  input.value.KesadaranCM = true;
  } else if (total == 13 || total == 12) {
    input.value.KesadaranApatis = true;
  } else if (total == 11 || total == 10) {
    input.value.KesadaranDelirium = true;
  } else if (total == 9 || total == 8 || total == 7) {
    input.value.KesadaranSomnolen = true;
  } else if (total == 6 || total == 5) {
    input.value.KesadaranSopor = true;
  } else if (total == 3) {
    input.value.KesadaranComa = true;
  }
})

const tutupDanSetAutofill = async () => {
  isKonsul.value = false;
  setAutoFill()
}

const tutupRadSetAutofill = async () => {
  isRad.value = false;
  setAutoFill()
}

const tutupLabSetAutofill = async () => {
  isLab.value = false;
  setAutoFill()
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


const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
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
const addNewItemObatRawatIGD = () => {
  input.value.detailObatPerawatanIGD.push({
    no: input.value.detailObatPerawatanIGD[input.value.detailObatPerawatanIGD.length - 1].no + 1,
  });
}
const removeItemObatRawatIGD = (index: any) => {
  input.value.detailObatPerawatanIGD.splice(index, 1)
}
const addNewItemObatRawatInap = () => {
  input.value.detailObatRawatInap.push({
    no: input.value.detailObatRawatInap[input.value.detailObatRawatInap.length - 1].no + 1,
  });
}
const removeItemObatRawatInap = (index: any) => {
  input.value.detailObatRawatInap.splice(index, 1)
}


const fetchProduk = async (filter: any) => {
  const response = await useApi().get(
    `/farmasi/dropdown-obat?namaproduk=${filter.query}&limit=10&igd=true`)
  d_produk.value = response

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

  isLoadingVitalSign.value = true;
  let obat = ''
  let instruksi = ''
  let lab = ''
  let rad = ''
  let konsul = ''
  let ketkonsul = ''
  let hasilkonsul = ''
  let hasillabemr = ''
  let hasilrad = ''
  input.value.fisikAbdomen = 'Normal'
  input.value.fisikKepala = 'Normal'
  input.value.fisikLeher = 'Normal'
  input.value.fisikEkstremitas = 'Normal'
  input.value.fisikThoraks = 'Normal'
  input.value.fisikLainnya = 'Normal'
  await useApi().get(
    `/farmasi/riwayat-order-resep-cppt?nocmfk=${ID_PASIEN}&verif=false&isVerif=true&norec_pd=${item.NOREC_PD}`).then((response: any) => {
      if (response != null) {
        response.forEach(element => {
          obat = ''
          // instruksi = ''
          if (element.jenis == 'Verif') {
            element.details.forEach((item, index) => {
              obat += item.namaproduk
              // instruksi += `${item.namaproduk} jumlah ${item.jumlah} dengan aturan pakai ${item.aturanpakai}`
              if (index != element.details.length - 1) {
                obat += ', '
                // instruksi += ', '
              }
            })
          }
        })
        input.value.rencanaTerapiOral = true
        input.value.rencanaTerapiOralKet = ref(obat)
        // input.value.instruksi = ref(instruksi)
      }
    })
  await useApi().get(
    `/laboratorium/riwayat-order?nocmfk=${ID_PASIEN}&norec_pd=${item.NOREC_PD}`).then((response: any) => {
      if (response != null) {
        lab = '';
        response.forEach(element => {
          element.details.forEach((item, index) => {
            lab += item.namaproduk
            if (index != element.details.length - 1) {
              lab += ', '
            }
          })
        })
        input.value.pemeriksaanPermintaanLab = ref(lab)
      }
    })
  await useApi().get(
    `/radiologi/riwayat-order?nocmfk=${ID_PASIEN}&nocm=${props.pasien.nocm}&norec_pd=${item.NOREC_PD}&noregistrasi=${props.registrasi.noregistrasi}`).then((response: any) => {
      if (response != null) {
        rad = '';
        response.forEach(element => {
          element.details.forEach((item, index) => {
            rad += item.namaproduk
            if (index != element.details.length - 1) {
              rad += ', '
            }
          })
        })
        input.value.pemeriksaanPermintaanRadiologi = ref(rad)
      }
    })
  await useApi().get(
    `/emr/get-order-konsul?nocm=${props.pasien.nocm}&noregistrasi=${props.registrasi.noregistrasi}`).then((response: any) => {
      if (response.data != null) {
        let konsul = '';
        let ketkonsul = '';
        let hasilkonsul = '';
        response.data.forEach(element => {
          konsul += `${element.tglorder}, ${element.ruangantujuan}\n`;
          ketkonsul += `${element.keteranganorder}\n`;
          if (element.keteranganlainnya != null) {
            hasilkonsul += `${element.keteranganlainnya}\n`;
          }
        });
        konsul = konsul.slice(0, -2);
        input.value.pemeriksaanPermintaanKonsultasi = ref(konsul);
        input.value.keteranganKonsultasi = ref(ketkonsul);
        input.value.hasilKonsultasi = ref(hasilkonsul);
      }
    });
  await useApi().get(
    `/laboratorium/hasil-lab?noregistrasi=${props.registrasi.noregistrasi}`).then((response) => {
      if (response != null) {
        let hasillabemr = '';
        response.forEach(element => {
          hasillabemr += `${element.hasillab}\n`;
        });
        input.value.hasilLab = ref(hasillabemr);
      }
    });
  await useApi().get(
    `/emr/get-hasil-radiologi-emr?noregistrasi=${props.registrasi.noregistrasi}&norec_pd=${props.registrasi.norec_pd}`).then((response) => {
      if (response != null) {
        let hasilrad = '';
        response.forEach(element => {
          hasilrad += `${element.hasilexpertise}\n`;
        });
        input.value.hasilRadiologi = ref(hasilrad);
      }
    });

  await useApi().get(
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=VitalSign" + "&field=tekananDarah,pernapasan,nadi,SPO2,suhu"
  ).then((response) => {
    if (response != null) {
      input.value.tekananDarah = response.tekananDarah
      input.value.pernapasan = response.pernapasan
      input.value.nadi = response.nadi
      input.value.SPO2 = response.SPO2
      input.value.suhu = response.suhu
    }
    isLoadingVitalSign.value = false;
  })
  await useApi().get(
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=PengkajianAwalPasienGawatDarurat" + "&field=riwayatPenyakitSaatIni,riwayatPenyakitSebelumnya"
  ).then((response) => {
    if (response != null) {
      input.value.riwayatPenyakitSekarang = response.riwayatPenyakitSaatIni
      input.value.riwayatPenyakitDahulu = response.riwayatPenyakitSebelumnya
    }
    isLoadingVitalSign.value = false;
  })
  await useApi().get(
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=Triase" + "&field=tandaVitalPernafasa,frekuensiNafas,frekuensiNadi,saturasiOksigen,sikulasiSuhu"
  ).then((response) => {
    if (response != null) {
      input.value.tekananDarah = response.tandaVitalPernafasa
      input.value.pernapasan = response.frekuensiNafas
      input.value.nadi = response.frekuensiNadi
      input.value.SPO2 = response.saturasiOksigen
      input.value.suhu = response.sikulasiSuhu
    }
    isLoadingVitalSign.value = false;
  })
  input.value.tglPembuatan = new Date()
  // input.value.dpjpIGD = props.registrasi.dokter
}
setView()
onMounted(async ()=>{
  await setAutoFill()
  await loadRiwayat()
})
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
