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
    <div class="columns is-multiline p-5">
      <div class="column is-12">
        <VCard>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Tanggal dan Jam : </span>
              </div>
              <div class="column is-6">
                <VField class="mt-2">
                  <VDatePicker v-model="input.tglDanJam" mode="dateTime" style="width: 100%" trim-weeks
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
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Dirujuk dari : </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="lainnya... " v-model="input.dirujukDari" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> DPJP : </span>
              </div>
              <div class="column is-6">
                <VField>
                    <VControl class="prime-auto">
                        <AutoComplete v-model="input.dpjp" :suggestions="d_Dokter"
                            @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                            :field="'label'" placeholder="DPJP ..." class="mt-2" />
                    </VControl>
                </VField>
              </div>
            </div>
          </div>
        </VCard>
        <VCard>
          <Fieldset :toggleable="true" legend="STATUS KESEHATAN SAAT INI (diisi oleh perawat)">
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> A. Tujuan perawatan paliatif : </span>
                </div>
                <div class="column is-6">
                  <VControl>
                    <div class="columns is-multiline pt-3 pb-2 pr-5 pl-3">
                      <div class="column is-12" v-if="d_TujuanPerawatPaliatif.length == 0">
                        <VPlaceloadText :lines="1" />
                      </div>
                      <div class="column is-4 mt-2 p-0" v-for="items in d_TujuanPerawatPaliatif" :key="items.id">
                        <VRadio v-model="input.tujuanPerawatPaliatif" :value="items.label" class="p-0 mb-3" :label="items.label"
                          square color="primary" />
                          <VField v-if="items.label == 'Lain lain'">
                            <VControl>
                              <VInput type="text" class="input" placeholder="lainnya... " v-model="input.keluargaLainnya" />
                            </VControl>
                          </VField>
                      </div>
                    </div>
                  </VControl>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> B. Keluhan utama :  </span>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VTextarea class="textarea" v-model="input.keluhanUtama" rows="3"
                        placeholder="......" autocomplete="off" autocapitalize="off"
                        spellcheck="true" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> C. Lama keluhan :  </span>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VTextarea class="textarea" v-model="input.lamaKeluhan" rows="3"
                        placeholder="......" autocomplete="off" autocapitalize="off"
                        spellcheck="true" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> D. Diagnosis :  </span>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VTextarea class="textarea" v-model="input.diagnosis" rows="3"
                        placeholder="......" autocomplete="off" autocapitalize="off"
                        spellcheck="true" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> E. Metastasis :  </span>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VTextarea class="textarea" v-model="input.metastasis" rows="3"
                        placeholder="......" autocomplete="off" autocapitalize="off"
                        spellcheck="true" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </Fieldset>
        </VCard>
        <VCard>
          <Fieldset :toggleable="true" legend="I. RIWAYAT KESEHATAN SEBELUMNYA (diisi oleh perawat)">
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> A. Riwayat Penyakit : </span>
                </div>
                <div class="column is-10">
                <div class="columns is-multiline is-flex">
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.tidakAdaRiwayatKesehatan" class="p-0" true-value="Tidak ada"
                          label="Tidak ada" color="primary" circle />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Asma" class="p-0" true-value="Asma"
                          label="Asma" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Diabetes" class="p-0" true-value="Diabetes"
                          label="Diabetes" color="primary" circle />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Hipertensi" class="p-0" true-value="Hipertensi"
                          label="Hipertensi" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Jantung" class="p-0" true-value="Jantung"
                          label="Jantung" color="primary" circle />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Kanker" class="p-0" true-value="Kanker"
                          label="Kanker" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.lainLainRiwayatKesehatan" class="p-0" true-value="Lain-lain"
                          label="Lain-lain" color="primary" circle />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="lainnya... " v-model="input.textboxLainLainRiwayatKesehatan" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:1rem">
                  <span> B. Riwayat pengobatan : </span>
                </div>
                <div class="column is-10">
                  <div class="column is-12 p-0">
                    <div class="is-flex">
                      <div class="column is-2" style="margin-top:0.5rem">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input.Operasi" class="p-0" true-value="Operasi"
                              label="Operasi" color="primary" circle />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-10">
                        <div class="is-flex">
                          <VRadio v-model="input.operasiYaTidak" value="tidakOperasi" class="p-0 mt-3 mr-3" label="Tidak"
                            square color="primary"/>
                          <VRadio v-model="input.operasiYaTidak" value="yaOperasi" class="p-0 mt-3 mr-3" label="Ya, Sebutkan"
                            square color="primary" />
                          <VField class="mr-3">
                            <VControl>
                              <VInput type="text" class="input" placeholder="lainnya... " v-model="input.sebutkanOperasi" />
                            </VControl>
                          </VField>
                          <VField>
                            <VDatePicker v-model="input.tglDanJamOperasi" mode="dateTime" style="width: 100%" trim-weeks
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
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <!-- <span> B. Riwayat pengobatan : </span> -->
                </div>
                <div class="column is-10">
                  <div class="column is-12 p-0">
                    <div class="is-flex">
                      <div class="column is-2" style="margin-top:0.5rem">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input.Radiasi" class="p-0" true-value="Radiasi"
                              label="Radiasi" color="primary" circle />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-10">
                        <div class="is-flex">
                          <VRadio v-model="input.RadiasiYaTidak" value="tidakRadiasi" class="p-0 mt-3 mr-3" label="Tidak"
                            square color="primary"/>
                          <VRadio v-model="input.RadiasiYaTidak" value="yaRadiasi" class="p-0 mt-3 mr-3" label="Ya, Sebutkan"
                            square color="primary" />
                          <VField class="mr-3">
                            <VControl>
                              <VInput type="text" class="input" placeholder="lainnya... " v-model="input.sebutkanRadiasi" />
                            </VControl>
                          </VField>
                          <VField>
                            <VDatePicker v-model="input.tglDanJamRadiasi" mode="dateTime" style="width: 100%" trim-weeks
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
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <!-- <span> B. Riwayat pengobatan : </span> -->
                </div>
                <div class="column is-10">
                  <div class="column is-12 p-0">
                    <div class="is-flex">
                      <div class="column is-2" style="margin-top:0.5rem">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input.terapiSistemikKanker" class="p-0" true-value="Terapi sistemik kanker"
                              label="Terapi sistemik kanker" color="primary" circle />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-10">
                        <div class="is-flex">
                          <VRadio v-model="input.terapiSistemikKankerYaTidak" value="tidakterapiSistemikKanker" class="p-0 mt-3 mr-3" label="Tidak"
                            square color="primary"/>
                          <VRadio v-model="input.terapiSistemikKankerYaTidak" value="yaterapiSistemikKanker" class="p-0 mt-3 mr-3" label="Ya, Sebutkan"
                            square color="primary" />
                          <VField class="mr-3">
                            <VControl>
                              <VInput type="text" class="input" placeholder="lainnya... " v-model="input.sebutkanterapiSistemikKanker" />
                            </VControl>
                          </VField>
                          <VField>
                            <VDatePicker v-model="input.tglDanJamTerapiSistemKanker" mode="dateTime" style="width: 100%" trim-weeks
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
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <!-- <span> B. Riwayat pengobatan : </span> -->
                </div>
                <div class="column is-10">
                  <div class="column is-12 p-0">
                    <div class="is-flex">
                      <div class="column is-2" style="margin-top:0.5rem">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input.lainLainRiwayatPengobatan" class="p-0" true-value="Lain - Lain"
                              label="Lain - Lain" color="primary" circle />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-10">
                        <VField>
                          <VControl>
                            <VTextarea class="textarea" v-model="input.textareaLainLainRiwayatPengobatan" rows="3"
                              placeholder="......" autocomplete="off" autocapitalize="off"
                              spellcheck="true" />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> C. Riwayat alergi : </span>
                </div>
                <div class="column is-10">
                  <div class="column is-12 p-0">
                    <div class="is-flex">
                      <div class="column is-10">
                        <div class="is-flex">
                          <VRadio v-model="input.riwayaAlergiYaTidak" value="tidakRiwayatAlergi" class="p-0 mt-3 mr-3" label="Tidak"
                            square color="primary"/>
                          <VRadio v-model="input.riwayaAlergiYaTidak" value="yaRiwayatAlergi" class="p-0 mt-3 mr-3" label="Ya, Sebutkan"
                            square color="primary" />
                          <VField class="mr-3">
                            <VControl>
                              <VInput type="text" class="input" placeholder="lainnya... " v-model="input.sebutkanterapiSistemikKanker" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> Reaksi Alergi </span>
                </div>
                <div class="column is-10">
                  <VField>
                    <VControl>
                      <VTextarea class="textarea" v-model="input.textareaLainLainRiwayatPengobatan" rows="3"
                        placeholder="......" autocomplete="off" autocapitalize="off"
                        spellcheck="true" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-4">
              <h1>D. Hasil pemeriksaan penunjang yang sudah dikerjakan : </h1>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> Laboratorium </span>
                </div>
                <div class="column is-10">
                  <div class="column is-12 is-flex">
                    <VRadio v-model="input.riwayaAlergiYaTidak" value="tidakLaboratorium" class="p-0 mt-3 mr-3" label="Tidak Ada"
                      square color="primary"/>
                    <VRadio v-model="input.riwayaAlergiYaTidak" value="yaLaboratorium" class="p-0 mt-3 mr-3" label="Ya, Hasil"
                      square color="primary" />
                    <VField class="column is-9">
                      <VControl>
                        <VTextarea class="textarea" v-model="input.textareaLaboratorium" rows="3"
                          placeholder="......" autocomplete="off" autocapitalize="off"
                          spellcheck="true" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> Radiologi </span>
                </div>
                <div class="column is-10">
                  <div class="column is-12 is-flex">
                    <VRadio v-model="input.riwayaAlergiYaTidak" value="tidakRadiologi" class="p-0 mt-3 mr-3" label="Tidak Ada"
                      square color="primary"/>
                    <VRadio v-model="input.riwayaAlergiYaTidak" value="yaRadiologi" class="p-0 mt-3 mr-3" label="Ya, Hasil"
                      square color="primary" />
                    <VField class="column is-9">
                      <VControl>
                        <VTextarea class="textarea" v-model="input.textareaRadiologi" rows="3"
                          placeholder="......" autocomplete="off" autocapitalize="off"
                          spellcheck="true" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> Patologi Anatomi </span>
                </div>
                <div class="column is-10">
                  <div class="column is-12 is-flex">
                    <VRadio v-model="input.riwayaAlergiYaTidak" value="tidakPa" class="p-0 mt-3 mr-3" label="Tidak Ada"
                      square color="primary"/>
                    <VRadio v-model="input.riwayaAlergiYaTidak" value="yaPa" class="p-0 mt-3 mr-3" label="Ya, Hasil"
                      square color="primary" />
                    <VField class="column is-9">
                      <VControl>
                        <VTextarea class="textarea" v-model="input.textareaPa" rows="3"
                          placeholder="......" autocomplete="off" autocapitalize="off"
                          spellcheck="true" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            
          </Fieldset>
        </VCard>
        <VCard>
          <Fieldset :toggleable="true" legend="III. PENGKAJIAN (diisi oleh perawat)">
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> A. Kesadaran : </span>
                </div>
                <div class="column is-5">
                  <div class="columns is-multiline is-flex">
                    <div class="column">
                      <VRadio v-model="input.Kesadaran" value="AKesadaran" class="p-0 mt-3 mr-3" label="A"
                        square color="primary" />
                    </div>
                    <div class="column">
                      <VRadio v-model="input.Kesadaran" value="CKesadaran" class="p-0 mt-3 mr-3" label="C"
                        square color="primary" />
                    </div>
                    <div class="column">
                      <VRadio v-model="input.Kesadaran" value="VKesadaran" class="p-0 mt-3 mr-3" label="V"
                        square color="primary" />
                    </div>
                    <div class="column">
                      <VRadio v-model="input.Kesadaran" value="PKesadaran" class="p-0 mt-3 mr-3" label="P"
                        square color="primary" />
                    </div>
                    <div class="column">
                      <VRadio v-model="input.Kesadaran" value="UKesadaran" class="p-0 mt-3 mr-3" label="U"
                        square color="primary" />
                    </div>
                  </div>
                  <div>
                    <h2>(A: Alert, C: Confusion, V: Voice , P: Pain, U: Unconsious)</h2>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> B. Tanda vital : </span>
                </div>
                <div class="column is-5">
                  <div>
                    <label>Tekanan Darah</label>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Tekanan Darah" v-model="input.TD" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>mmHg</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="mt-2">
                    <label>Nadi</label>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Nadi" v-model="input.Nadi" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>x/mnt</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="mt-2">
                    <label>Pernapasan</label>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Pernapasan" v-model="input.Pernapasan" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>x/mnt</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="mt-2">
                    <label>Suhu</label>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Suhu" v-model="input.Suhu" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>°C</VButton>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <h1>C. ESAS (Edmonton Symptom Assessment Scale)</h1>
              <table border="1" class="mt-4">
                  <thead>
                    <tr>
                      <th>Tidak nyeri</th>
                      <th>0</th>
                      <th>1</th>
                      <th>2</th>
                      <th>3</th>
                      <th>4</th>
                      <th>5</th>
                      <th>6</th>
                      <th>7</th>
                      <th>8</th>
                      <th>9</th>
                      <th>10</th>
                      <th>Nyeri berat</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(datas, i) in obat">
                      <td width="18%" style="font-weight: 600;">{{ datas.label }}</td>
                      <td v-for="data in datas.children">
                        <div class="column is-12" v-for="checkbox in data">
                          <VField v-if="checkbox.type == 'input'" addons>
                            <VControl subcontrol expanded style="width:60px !important; ">
                              <VInput type="text" v-model="input[checkbox.model]"></VInput>
                            </VControl>
                            <VControl v-if="checkbox.button" class="field-addon-body">
                            </VControl>
                          </VField>
                          <VField v-if="checkbox.type == 'waktu'">
                            <VControl>
                                <VInput type="time" class="input form-timepicker mt-2" style="width:60px !important; " placeholder=""
                                    v-model="input[checkbox.model]" />
                            </VControl>
                          </VField>
                          <VField v-if="checkbox.type == 'cekbox'">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input[checkbox.model]" class="p-0" :true-value="checkbox.model"
                                color="primary" circle />
                            </VControl>
                          </VField>
                          <div v-if="checkbox.type == 'label'">
                            {{ checkbox.label }}
                          </div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="13" class="text-center">
                        <p>{{ "Ket :    Ringan = < 3      Sedang =   4 – 6          Berat =    >  7"}}</p>
                      </td>
                    </tr>
                  </tbody>
                </table>
            </div>
            <div class="column is-12 p-0">
              <div class="p-1">
                <h1>D.Skrining nyeri</h1>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> 1. Nyeri </span>
                </div>
                <div class="column is-10">
                  <div class="column is-12 is-flex">
                    <VRadio v-model="input.riwayaAlergiYaTidak" value="tidakNyerii" class="p-0 mt-3 mr-3" label="Tidak"
                      square color="primary"/>
                    <VRadio v-model="input.riwayaAlergiYaTidak" value="yaNyerii" class="p-0 mt-3 mr-3" label="Ya, Skor"
                      square color="primary" />
                    <VField class="mr-2">
                      <VControl>
                        <VInput type="text" class="input" placeholder="Skor" v-model="input.SkorNyeri" />
                      </VControl>
                    </VField>
                    <div class="clumn mr-2 mt-2">
                      <h5>Metode</h5>
                    </div>
                    <div class="clumn mr-2">
                      <VRadio v-model="input.metodeNyeri" value="NRS" class="p-0 mt-3 mr-3" label="NRS"
                      square color="primary"/>
                    </div>
                    <div class="clumn mr-2">
                      <VRadio v-model="input.metodeNyeri" value="FLACC" class="p-0 mt-3 mr-3" label="FLACC"
                      square color="primary"/>
                    </div>
                    <div class="clumn mr-2">
                      <VRadio v-model="input.metodeNyeri" value="WongBaker" class="p-0 mt-3 mr-3" label="WongBaker"
                      square color="primary"/>
                    </div>
                    <div class="clumn mr-2">
                      <VRadio v-model="input.metodeNyeri" value="BPS" class="p-0 mt-3 mr-3" label="BPS"
                      square color="primary"/>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> 2. P = Provocative : </span>
                </div>
                <div class="column is-10">
                <div class="columns is-multiline is-flex">
                  <div class="column">
                    <h2>Apa yang memprovokasi nyeri?</h2>
                  </div>
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Rudapaksa" class="p-0" true-value="Rudapaksa"
                          label="Rudapaksa" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Benturan" class="p-0" true-value="Benturan"
                          label="Benturan" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Mobiilisasi" class="p-0" true-value="Mobiilisasi"
                          label="Mobiilisasi" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> 3 .Q =.Quality : </span>
                </div>
                <div class="column is-10">
                <div class="columns is-multiline is-flex">
                  <div class="column">
                    <h2>Seperti apa rasanya ?</h2>
                  </div>
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Berdenyut" class="p-0" true-value="Berdenyut"
                          label="Berdenyut" color="primary" circle />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Tajam" class="p-0" true-value="Tajam"
                          label="Tajam" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Kram" class="p-0" true-value="Kram"
                          label="Kram" color="primary" circle />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Tertusuktusuk" class="p-0" true-value="Tertusuk-tusuk"
                          label="Tertusuk-tusuk" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Terbakar" class="p-0" true-value="Terbakar"
                          label="Terbakar" color="primary" circle />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Ngilu" class="p-0" true-value="Ngilu"
                          label="Ngilu" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Perih" class="p-0" true-value="Perih"
                          label="Perih" color="primary" circle />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Menyengat" class="p-0" true-value="Menyengat"
                          label="Menyengat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Kolik" class="p-0" true-value="Kolik"
                          label="Kolik" color="primary" circle />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Teriris" class="p-0" true-value="Teriris"
                          label="Teriris" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.lainLainSepertiApaRasanya" class="p-0" true-value="Lain-lain"
                          label="Lain-lain" color="primary" circle />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="lainnya... " v-model="input.lainnyaSepertiApa" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> 4. R = Regio : </span>
                </div>
                <div class="column is-10">
                <div class="columns is-multiline is-flex is-12">
                  <div class="column is-3">
                    <h2>Di mana daerah nyeri</h2>
                  </div>
                  <div class="column is-7">
                    <VField>
                    <VControl>
                      <VTextarea class="textarea" v-model="input.textareaDiManaDaerah" rows="3"
                        placeholder="......" autocomplete="off" autocapitalize="off"
                        spellcheck="true" />
                    </VControl>
                  </VField>
                  </div>
                </div>
                </div>
              </div>
            </div>
            <div class="column is-12">
              <div class="column is-8" style="text-align: center;">
                <div :style="'background-image:url(/images/emr/asesmen_medis.png)'"
                  style="text-align: center; z-index: 9999;background-repeat: no-repeat;background-position: center;width: 900px;height: 600px; background-size: contain;">
                  <canvas id="markingsite_1" height="600" width="900"></canvas>
                </div>
                <VButton type="button" rounded outlined color="danger" raised icon="feather:trash"
                  @click="clearCanvas('markingsite_1')"> Clear
                </VButton>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> 5. S = Severity : </span>
                </div>
                <div class="column is-10">
                <div class="columns is-multiline is-flex is-12">
                  <div class="column is-3">
                    <h2>Berapa skala nyeri, sebutkan</h2>
                  </div>
                  <div class="column is-7">
                    <VField>
                    <VControl>
                      <VTextarea class="textarea" v-model="input.textareaBerapaSkalaNyeri" rows="3"
                        placeholder="......" autocomplete="off" autocapitalize="off"
                        spellcheck="true" />
                    </VControl>
                  </VField>
                  </div>
                </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <Image src="/images/emr/wongBaker.png" alt="Image" width="600" class="center mx-auto" preview />
            </div>
            <div class="column is-12 p-0">
              <h1>E. Status fungsional</h1>
            </div>
            <div class="column is-12 p-4">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.mandiriTindakLanjut" class="p-0" true-value="Mandiri, tindak lanjut : edukasi"
                    label="Mandiri, tindak lanjut : edukasi" color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-12 p-4">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.ketergantunganTindakLanjut" class="p-0" true-value="Ketergantungan , tindak lanjut : lapor DPJP"
                    label="Ketergantungan , tindak lanjut : lapor DPJP" color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-12 p-0">
              <h1>A. Skrining risiko jatuh </h1>
            </div>
            <div class="column is-12 p-0">
              <h1>Pasien rawat inap : (Mengikuti formulir rawat inap) </h1>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> Pasien rawat jalan : </span>
                </div>
                <div class="column is-10">
                <div class="columns is-multiline is-flex">
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.tidakBerisikoPasienRajal" class="p-0" true-value="Tidak berisiko"
                          label="Tidak berisiko" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.risikoRendahPasienRajal" class="p-0" true-value="Risiko rendah"
                          label="Risiko rendah" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.risikoTinggiPasienRajal" class="p-0" true-value="Risiko tinggi"
                          label="Risiko tinggi" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <table border="1" class="mt-4">
                <thead>
                  <tr>
                    <th>KOMPONEN PENILAIAN</th>
                    <th>YA</th>
                    <th>TIDAK</th>
                    <th>KETERANGAN</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td width="18%" style="font-weight: 600;">a. Apakah ada keluhan pusing/ vertigo</td>
                    <td>
                      <div class="column is-12">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input.yaKeluhanPusing" class="p-0" true-value="yaKeluhanPusing"
                              color="primary" circle />
                          </VControl>
                        </VField>
                      </div>
                    </td>
                    <td>
                      <div class="column is-12">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input.tidakKeluhanPusing" class="p-0" true-value="tidakKeluhanPusing"
                              color="primary" circle />
                          </VControl>
                        </VField>
                      </div>
                    </td>
                    <td rowspan="3">
                      <div class="column is-12">
                        <p>Pasien dinilai BERISIKO JATUH bila terdapat jawaban ”Ya” pada salah satu pertanyaan.</p>
                        <p>Lakukan : </p>
                        <p>∙ Pemasangan stiker kuning </p>
                        <p>∙ Berikan edukasi dan brosur pencegahan jatuh </p>
                        <p>∙ Memastikan lingkungan disekitar aman.</p>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td width="18%" style="font-weight: 600;">b. Apakah mengalami kesulitan saat   berdiri/ berjalan</td>
                    <td>
                      <div class="column is-12">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input.yaMengalamiKesulitanBerdiri" class="p-0" true-value="yaMengalamiKesulitanBerdiri"
                              color="primary" circle />
                          </VControl>
                        </VField>
                      </div>
                    </td>
                    <td>
                      <div class="column is-12">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input.tidakMengalamiKesulitanBerdiri" class="p-0" true-value="tidakMengalamiKesulitanBerdiri"
                              color="primary" circle />
                          </VControl>
                        </VField>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td width="18%" style="font-weight: 600;">c. Apakah pernah mengalami jatuh dalam 6 bulan terakhir</td>
                    <td>
                      <div class="column is-12">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input.yaJatuhDalamEnamBulan" class="p-0" true-value="yaJatuhDalamEnamBulan"
                              color="primary" circle />
                          </VControl>
                        </VField>
                      </div>
                    </td>
                    <td>
                      <div class="column is-12">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input.tidakJatuhDalamEnamBulan" class="p-0" true-value="tidakJatuhDalamEnamBulan"
                              color="primary" circle />
                          </VControl>
                        </VField>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> B. Psikologi </span>
                </div>
                <div class="column is-10">
                  <VField>
                    <VControl>
                      <VTextarea class="textarea" v-model="input.textareaPsikologi" rows="3"
                        placeholder="......" autocomplete="off" autocapitalize="off"
                        spellcheck="true" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> C. Sosial ekonomi  </span>
                </div>
                <div class="column is-10">
                  <VField>
                    <VControl>
                      <VTextarea class="textarea" v-model="input.textareaSosialEkonomi" rows="3"
                        placeholder="......" autocomplete="off" autocapitalize="off"
                        spellcheck="true" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> D. Spiritual/ Budaya </span>
                </div>
                <div class="column is-10">
                  <VField>
                    <VControl>
                      <VTextarea class="textarea" v-model="input.textareaSpiritualdanBudaya" rows="3"
                        placeholder="......" autocomplete="off" autocapitalize="off"
                        spellcheck="true" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-3">
              <h1>H. Peniliaian kualitas hidup (terlampir di formulir Kuesioner Kualitas Hidup McGill) </h1>
            </div>
            <div class="column is-12 p-3">
              <h1>I. Kebutuhan edukasi </h1>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> Topik pembelajaran: </span>
                </div>
                <div class="column is-10">
                <div class="columns is-multiline is-flex">
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.latihanRelaksasi" class="p-0" true-value="Latihan relaksasi "
                          label="Latihan relaksasi " color="primary" circle />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.latihanBatukEfektif" class="p-0" true-value="Latihan batuk efektif"
                          label="Latihan batuk efektif" color="primary" circle />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.perawatanInfus" class="p-0" true-value="Perawatan infus"
                          label="Perawatan infus" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Perawatankateter" class="p-0" true-value="Perawatan kateter"
                          label="Perawatan kateter" color="primary" circle />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Perawatanluka" class="p-0" true-value="Perawatan luka"
                          label="Perawatan luka" color="primary" circle />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Perawatandrain" class="p-0" true-value="Perawatan drain"
                          label="Perawatan drain" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.PenggunaanObatObatan" class="p-0" true-value="Penggunaan obat - obatan"
                          label="Penggunaan obat - obatan" color="primary" circle />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Lainnyaaaaa" class="p-0" true-value="Lainnya "
                          label="Lainnya " color="primary" circle />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="lainnya... " v-model="input.lainnyaTextTopikPembelajaran" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> Media pembelajaran: </span>
                </div>
                <div class="column is-10">
                <div class="columns is-multiline is-flex">
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.diskusiDanTanyaJawab" class="p-0" true-value="Diskusi dan tanya jawab"
                          label="Diskusi dan tanya jawab" color="primary" circle />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Leaflet" class="p-0" true-value="Leaflet"
                          label="Leaflet" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Lainnyaaaaaaa" class="p-0" true-value="Lainnya"
                          label="Lainnya" color="primary" circle />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="lainnya... " v-model="input.lainnyaTextMediaPembelajaran" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> Media Komunikasi : </span>
                </div>
                <div class="column is-10">
                <div class="columns is-multiline is-flex">
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Diagnosis" class="p-0" true-value="Diagnosis"
                          label="Diagnosis" color="primary" circle />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Prognosis" class="p-0" true-value="Prognosis"
                          label="Prognosis" color="primary" circle />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.TujuanPengobatan" class="p-0" true-value="Tujuan Pengobatan"
                          label="Tujuan Pengobatan" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.acp" class="p-0" true-value="ACP"
                          label="ACP" color="primary" circle />
                      </VControl>
                    </VField>
                    <VRadio v-model="input.acpRadio" value="Tempat Perawatan Akhir Kehidupan" class="p-0 mt-3 mr-3" label="Tempat Perawatan Akhir Kehidupan"
                      square color="primary"/>
                    <VRadio v-model="input.acpRadio" value="Resusitasi" class="p-0 mt-3 mr-3" label="Resusitasi"
                      square color="primary"/>
                    <VRadio v-model="input.acpRadio" value="Wali Yang di Tunjuk" class="p-0 mt-3 mr-3" label="Wali Yang di Tunjuk"
                      square color="primary"/>
                    <VRadio v-model="input.acpRadio" value="Wasiat" class="p-0 mt-3 mr-3" label="Wasiat"
                      square color="primary"/>
                    <VField>
                      <VControl>
                        <VTextarea class="textarea" v-model="input.textareaWasiat" rows="3"
                          placeholder="......" autocomplete="off" autocapitalize="off"
                          spellcheck="true" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                </div>
              </div>
            </div>
          </Fieldset>
        </VCard>
        <VCard>
          <Fieldset :toggleable="true" legend="PERFORMANCE STATUS (diisi oleh dokter)">
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> ECOG  : </span>
                </div>
                <div class="column is-6">
                  <VControl>
                    <div class="columns is-multiline pt-3 pb-2 pr-5 pl-3">
                      <div class="column is-12" v-if="d_Ecog.length == 0">
                        <VPlaceloadText :lines="1" />
                      </div>
                      <div class="column is-2 mt-2 p-0" v-for="items in d_Ecog" :key="items.id">
                        <VRadio v-model="input.Ecog" :value="items.label" class="p-0 mb-3" :label="items.label"
                          square color="primary" />
                          <VField v-if="items.label == 'Lain lain'">
                            <VControl>
                              <VInput type="text" class="input" placeholder="lainnya... " v-model="input.keluargaLainnya" />
                            </VControl>
                          </VField>
                      </div>
                    </div>
                  </VControl>
                </div>
              </div>
            </div>
          </Fieldset>
        </VCard>
        <VCard>
          <Fieldset :toggleable="true" legend="MASALAH MEDIS (diisi oleh dokter)">
            <div class="column is-12 p-0">
              <h1>i. Diagnosis medis </h1>
              <VField class="column is-6">
                <VControl>
                  <VInput type="text" class="input" placeholder="diagnosa" v-model="input.diganosaMedis1" />
                </VControl>
              </VField>
              <VField class="column is-6">
                <VControl>
                  <VInput type="text" class="input" placeholder="diagnosa" v-model="input.diganosaMedis2" />
                </VControl>
              </VField>
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.notApplicable1" class="p-0" true-value="Not Applicable"
                    label="Not Applicable" color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-12 p-0 mt-4">
              <h1>ii. Diagnosis Kanker </h1>
              <VField class="column is-6">
                <VControl>
                  <VInput type="text" class="input" placeholder="diagnosa" v-model="input.diganosaKanker1" />
                </VControl>
              </VField>
              <VField class="column is-6">
                <VControl>
                  <VInput type="text" class="input" placeholder="diagnosa" v-model="input.diganosaKanker2" />
                </VControl>
              </VField>
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.notApplicable2" class="p-0" true-value="Not Applicable"
                    label="Not Applicable" color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> Sisi tubuh  : </span>
                </div>
                <div class="column is-6">
                  <VControl>
                    <div class="columns is-multiline pt-3 pb-2 pr-5 pl-3">
                      <div class="column is-12" v-if="d_SisiTubuh.length == 0">
                        <VPlaceloadText :lines="1" />
                      </div>
                      <div class="column is-2 mt-2 p-0" v-for="items in d_SisiTubuh" :key="items.id">
                        <VRadio v-model="input.Ecog" :value="items.label" class="p-0 mb-3" :label="items.label"
                          square color="primary" />
                      </div>
                    </div>
                  </VControl>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> Klasifikasi  : </span>
                </div>
                <div class="column is-5">
                  <div>
                    <label>T</label>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="T" v-model="input.T" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="mt-2">
                    <label>N</label>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="N" v-model="input.N" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="mt-2">
                    <label>M</label>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="M" v-model="input.M" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="mt-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.notApplicable3" class="p-0" true-value="Not Applicable"
                          label="Not Applicable" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </Fieldset>
        </VCard>
        <VCard>
          <Fieldset :toggleable="true" legend="MASALAH KEPERAWATAN : (diisi oleh perawat)">
            <table border="1" class="mt-4">
                <tbody>
                  <tr>
                      <td v-for="(datas, i) in masalahKeperawatan">
                        <div v-for="data in datas.children">
                          <div class="column is-12" v-for="checkbox in data">
                            <VField v-if="checkbox.type == 'input'">
                              <VControl subcontrol expanded>
                                <VInput type="text" v-model="input[checkbox.model]"></VInput>
                              </VControl>
                            </VField>
                            <VField v-if="checkbox.type == 'cekbox'">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input[checkbox.model]" class="p-0" :true-value="checkbox.model"
                                  color="primary" :label="checkbox.label" circle />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </td>
                    </tr>
                </tbody>
              </table>
          </Fieldset>
        </VCard>
        <VCard>
          <Fieldset :toggleable="true" legend="PERENCANAAN ASUHAN (CARE PLAN) PASIEN TERINTEGRASI">
            <table border="1" class="mt-4">
                <tbody>
                  <tr>
                      <td rowspan="3">
                        <div class="column is-12 p-0">
                          <h1>1. Tata Laksana (diisi oleh dokter) </h1>
                        </div>
                        <div class="column is-12 p-0">
                          <div>
                            <div class="column is-12" style="margin-top:0.5rem">
                              <span> a. Pemeriksaan penunjang : </span>
                            </div>
                            <div class="column is-12">
                              <VField>
                                <VControl>
                                  <VTextarea class="textarea" v-model="input.textareaPemeriksaanPenunjang" rows="3"
                                    placeholder="......" autocomplete="off" autocapitalize="off"
                                    spellcheck="true" />
                                </VControl>
                              </VField>
                            </div>
                          </div>
                        </div>
                        <div class="column is-12 p-0">
                          <div>
                            <div class="column is-12" style="margin-top:0.5rem">
                              <span> b. Terapi / Tindakan / Konsul :  </span>
                            </div>
                            <div class="column is-12">
                              <VField>
                                <VControl>
                                  <VTextarea class="textarea" v-model="input.textareaTerapiTindakanKonsul" rows="3"
                                    placeholder="......" autocomplete="off" autocapitalize="off"
                                    spellcheck="true" />
                                </VControl>
                              </VField>
                            </div>
                          </div>
                        </div>
                        <div class="column is-12 p-0">
                          <div>
                            <div class="column is-12" style="margin-top:0.5rem">
                              <span> c. Diet : </span>
                            </div>
                            <div class="column is-12">
                              <VField>
                                <VControl>
                                  <VTextarea class="textarea" v-model="input.textareaDietAkhir" rows="3"
                                    placeholder="......" autocomplete="off" autocapitalize="off"
                                    spellcheck="true" />
                                </VControl>
                              </VField>
                            </div>
                          </div>
                        </div>
                        <div class="column is-12 p-0">
                          <h1>d. Edukasi : (lihat dan isi formulir edukasi terintegrasi)  </h1>
                        </div>
                        <div class="column is-12 p-0">
                          <h1>2. Target pengobatan : (diisi oleh dokter)   </h1>
                        </div>
                        <div class="column is-12 p-0">
                          <div class="is-flex">
                            <div class="column is-7" style="margin-top:0.5rem">
                              <span> Penatalaksanaan Gejala : </span>
                            </div>
                            <div class="column is-5">
                              <VControl>
                                <div class="columns is-multiline pt-3 pb-2 pr-5 pl-3">
                                  <div class="column is-12" v-if="d_SisiTubuh.length == 0">
                                    <VPlaceloadText :lines="1" />
                                  </div>
                                  <div class="column is-2 mt-2 mr-2 p-0">
                                    <VRadio v-model="input.PenatalaksanaanGejala" value="Ya" class="p-0 mb-3" label="Ya"
                                      square color="primary" />
                                  </div>
                                  <div class="column is-2 mt-2 p-0">
                                    <VRadio v-model="input.PenatalaksanaanGejala" value="Tidak" class="p-0 mb-3" label="Tidak"
                                      square color="primary" />
                                  </div>
                                </div>
                              </VControl>
                            </div>
                          </div>
                        </div>
                        <div class="column is-12 p-0">
                          <h1>Perbaikan Kondisi Umum untuk persiapan Terapi Kanker :</h1>
                        </div>
                        <div class="column is-12 p-0">
                          <div class="is-flex">
                            <div class="column is-7" style="margin-top:0.5rem">
                              <span> Kemoterapi : </span>
                            </div>
                            <div class="column is-5">
                              <VControl>
                                <div class="columns is-multiline pt-3 pb-2 pr-5 pl-3">
                                  <div class="column is-2 mt-2 mr-2 p-0">
                                    <VRadio v-model="input.Kemoterapi" value="Ya" class="p-0 mb-3" label="Ya"
                                      square color="primary" />
                                  </div>
                                  <div class="column is-2 mt-2 p-0">
                                    <VRadio v-model="input.Kemoterapi" value="Tidak" class="p-0 mb-3" label="Tidak"
                                      square color="primary" />
                                  </div>
                                </div>
                              </VControl>
                            </div>
                          </div>
                        </div>
                        <div class="column is-12 p-0">
                          <div class="is-flex">
                            <div class="column is-7" style="margin-top:0.5rem">
                              <span> Radiasi : </span>
                            </div>
                            <div class="column is-5">
                              <VControl>
                                <div class="columns is-multiline pt-3 pb-2 pr-5 pl-3">
                                  <div class="column is-2 mt-2 mr-2 p-0">
                                    <VRadio v-model="input.Radiasi" value="Ya" class="p-0 mb-3" label="Ya"
                                      square color="primary" />
                                  </div>
                                  <div class="column is-2 mt-2 p-0">
                                    <VRadio v-model="input.Radiasi" value="Tidak" class="p-0 mb-3" label="Tidak"
                                      square color="primary" />
                                  </div>
                                </div>
                              </VControl>
                            </div>
                          </div>
                        </div>
                        <div class="column is-12 p-0">
                          <div class="is-flex">
                            <div class="column is-7" style="margin-top:0.5rem">
                              <span> Operasi : </span>
                            </div>
                            <div class="column is-5">
                              <VControl>
                                <div class="columns is-multiline pt-3 pb-2 pr-5 pl-3">
                                  <div class="column is-2 mt-2 mr-2 p-0">
                                    <VRadio v-model="input.OperasiTerakhir" value="Ya" class="p-0 mb-3" label="Ya"
                                      square color="primary" />
                                  </div>
                                  <div class="column is-2 mt-2 p-0">
                                    <VRadio v-model="input.OperasiTerakhir" value="Tidak" class="p-0 mb-3" label="Tidak"
                                      square color="primary" />
                                  </div>
                                </div>
                              </VControl>
                            </div>
                          </div>
                        </div>
                        <div class="column is-12 p-0">
                          <h1>Perawatan Terminal : </h1>
                        </div>
                        <div class="column is-12 p-0">
                          <div class="is-flex">
                            <div class="column is-7" style="margin-top:0.5rem">
                              <span> ICU : </span>
                            </div>
                            <div class="column is-5">
                              <VControl>
                                <div class="columns is-multiline pt-3 pb-2 pr-5 pl-3">
                                  <div class="column is-2 mt-2 mr-2 p-0">
                                    <VRadio v-model="input.ICU" value="Ya" class="p-0 mb-3" label="Ya"
                                      square color="primary" />
                                  </div>
                                  <div class="column is-2 mt-2 p-0">
                                    <VRadio v-model="input.ICU" value="Tidak" class="p-0 mb-3" label="Tidak"
                                      square color="primary" />
                                  </div>
                                </div>
                              </VControl>
                            </div>
                          </div>
                        </div>
                        <div class="column is-12 p-0">
                          <div class="is-flex">
                            <div class="column is-7" style="margin-top:0.5rem">
                              <span> DNR : </span>
                            </div>
                            <div class="column is-5">
                              <VControl>
                                <div class="columns is-multiline pt-3 pb-2 pr-5 pl-3">
                                  <div class="column is-2 mt-2 mr-2 p-0">
                                    <VRadio v-model="input.DNR" value="Ya" class="p-0 mb-3" label="Ya"
                                      square color="primary" />
                                  </div>
                                  <div class="column is-2 mt-2 p-0">
                                    <VRadio v-model="input.DNR" value="Tidak" class="p-0 mb-3" label="Tidak"
                                      square color="primary" />
                                  </div>
                                </div>
                              </VControl>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td v-for="(datas, i) in perencanaanAsuhan">
                        <div v-for="data in datas.children">
                          <div class="column is-12" v-for="checkbox in data">
                            <VField v-if="checkbox.type == 'input'">
                              <VControl subcontrol expanded>
                                <VInput type="text" v-model="input[checkbox.model]"></VInput>
                              </VControl>
                            </VField>
                            <VField v-if="checkbox.type == 'cekbox'">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input[checkbox.model]" class="p-0" :true-value="checkbox.model"
                                  color="primary" :label="checkbox.label" circle />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </td>
                    </tr>
                  <tr>
                    <td>
                      <div class="column is-12 p-0">
                        <img v-if="input.petugasPerawat"
                          :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.petugasPerawat ? input.petugasPerawat.label : '-')">
                      </div>
                      <div class="column is-12 p-0">
                        <h1 class="p-0" style="font-weight: bold;">Perawat </h1>
                        <VField>
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.petugasPerawat" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                    :field="'label'" placeholder="Petugas Kesehatan..." class="mt-2" />
                            </VControl>
                        </VField>
                      </div>
                      <div class="column is-12 p-0 mt-4">
                        <h1 class="p-0" style="font-weight: bold;">Pukul </h1>
                        <VField>
                            <VDatePicker v-model="input.tglDanJamTtdPerawat" mode="dateTime" style="width: 100%" trim-weeks
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
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="column is-12 p-0">
                        <img v-if="input.petugasDokter"
                          :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.petugasDokter ? input.petugasDokter.label : '-')">
                      </div>
                      <div class="column is-12 p-0">
                        <h1 class="p-0" style="font-weight: bold;">Dokter </h1>
                        <VField>
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.petugasDokter" :suggestions="d_Dokter"
                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                    :field="'label'" placeholder="Petugas Kesehatan..." class="mt-2" />
                            </VControl>
                        </VField>
                      </div>
                      <div class="column is-12 p-0 mt-4">
                        <h1 class="p-0" style="font-weight: bold;">Pukul </h1>
                        <VField>
                            <VDatePicker v-model="input.tglDanJamTtdDokter" mode="dateTime" style="width: 100%" trim-weeks
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
                    </td>
                  </tr>
                </tbody>
              </table>
          </Fieldset>
        </VCard>
      </div>
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
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/pengkajian-awal-pasien-paliatif'
import $ from "jquery";
import sleep from '/@src/utils/sleep'
import Image from 'primevue/image';


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let masalahKeperawatan = ref(EMR.masalahKeperawatan())
let perencanaanAsuhan = ref(EMR.perencanaanAsuhan())
let obat = ref(EMR.obat())

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
const d_Petugas: any = ref([])
const d_Dokter: any = ref([])
const d_TujuanPerawatPaliatif: any = ref([])
const d_Ecog: any = ref([])
const d_SisiTubuh: any = ref([])
const d_Ditransfer: any = ref([])
const d_Pegawai: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('pengkajianAwalPasienPaliatif') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const getUsia = props.pasien.umur.split('thn')[0]
const loadRiwayat = () => {
  // if (NOREC_EMRPASIEN.value == '') return
  useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        if (response[0].tandaTanganDpjpIGD) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganDpjpIGD)
        }
        if (response[0].tandaTanganPPJPIGD) {
          H.tandaTangan().set("signature_2", response[0].tandaTanganPPJPIGD)
        }
        if (response[0].arsirTubuh) {
          let sigCanvas: any = document.getElementById('markingsite_1');
          if (sigCanvas) {

            let context = sigCanvas.getContext("2d");
            context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
            let imagess = response[0].arsirTubuh
            let background = new Image();
            background.src = imagess
            background.onload = function () {
              context.drawImage(background, 0, 0, sigCanvas.width, sigCanvas.height);
            }
          }
        }
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  let sigCanvas = document.getElementById("markingsite_1");
    if (sigCanvas) {
    let context = sigCanvas.getContext("2d");
    const dataURL = sigCanvas.toDataURL();
    input.value.arsirTubuh = dataURL
    object.arsirTubuh = dataURL
    }

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.tandaTanganDpjpIGD = H.tandaTangan().get("signature_1")
  object.tandaTanganPPJPIGD = H.tandaTangan().get("signature_2")
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': 'triase-igd',
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
let sigCanvas: any = document.getElementById("markingsite_1");
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

  $("#markingsite_1").mousedown(function (mouseEvent: any) {

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

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}


const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}
const fetchPetugas = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Petugas.value = response
  })
}
const setTandaTangan = async (e: any, i: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signature_" + i, element.ttd)
    } else {
      H.tandaTangan().set("signature_" + i, '')
    }
  })
}
const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.tglPembuatan = new Date()
  await useApi().get(
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=VitalSign" + "&field=tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi,beratBadan,SPO2"
  ).then((response) => {
    if (response == null) return
    input.value.anakSuhu = response.suhu
    input.value.anakBeratBadan = response.beratBadan
    input.value.anakSPO2 = response.SPO2
    input.value.frekuensiNadi = response.nadi
    input.value.frekuensiNafas = response.pernapasan
  })
  input.value.dpjpIGD = props.registrasi.dokter
  input.value.ppjpIGD = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
}

const listTujuan = [
  {value: 1, label: "Konsul"},
  {value: 2, label: "Rawat bersama"},
  {value: 3, label: "Alih rawat"},
  {value: 4, label: "Lain lain"}
]
d_TujuanPerawatPaliatif.value = listTujuan

const listEco = [
  {value: 1, label: "1"},
  {value: 2, label: "2"},
  {value: 3, label: "3"},
  {value: 4, label: "4"},
  {value: 5, label: "5"}
]
d_Ecog.value = listEco

const listSisiTubuh = [
  {value: 1, label: "Kiri"},
  {value: 2, label: "Kanan"},
  {value: 3, label: "Bilateral"}
]
d_SisiTubuh.value = listSisiTubuh

const listRuangTransfer = [
  {value: 1, label: "IGD"},
  {value: 2, label: "ICU"},
  {value: 3, label: "PICU"},
  {value: 4, label: "NICU"},
  {value: 5, label: "lainnya"},
]
d_Ditransfer.value = listRuangTransfer

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

.triase th,
td {
  padding: 8px;
  vertical-align: top !important;
}
</style>
