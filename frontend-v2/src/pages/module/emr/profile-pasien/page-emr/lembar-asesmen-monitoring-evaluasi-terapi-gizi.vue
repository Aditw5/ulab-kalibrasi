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
                <VField label="Nama Pasien">
                  <VInput v-model="input.namapasien" disabled>
                  </VInput>
                </VField>
              </div>
              <div class="column is-4">
                <VField label="Tanggal Lahir">
                  <VInput v-model="input.tgllahir" disabled>
                  </VInput>
                </VField>
              </div>
              <div class="column is-4">
                <VField label="Jenis Kelamin">
                  <VInput v-model="input.jeniskelamin" disabled>
                  </VInput>
                </VField>
              </div>
              <div class="column is-4">
                <VField label="Nomor RM">
                  <VInput v-model="input.nocm" disabled>
                  </VInput>
                </VField>
              </div>
              <div class="column is-4">
                <VField label="Ruangan">
                  <AutoComplete v-model="input.namaruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..."/>
                </VField>
              </div>
              <div class="column is-4">
                <VField label="Tanggal Jam">
                  <VDatePicker v-model="input.tanggalJam" mode="dateTime" style="width: 100%" trim-weeks
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
            </div>
          </div>
        </VCard>
      </div>

      <div class="column is-12">
        <Fieldset :toggleable="true" legend="Kriteria Diagnosis Malnutrisi">
          <div class="column is-12 p-2">
            <div class="columns is-multiline">
              <div class="column" style="overflow: auto;">
                <table class="tg">
                  <thead>
                    <tr>
                      <th class="tg-0lax text-center" colspan="4">Malnutrisi Sedang</th>

                    </tr>
                    <tr>
                      <th class="tg-0lax">Kriteria Amnesis & Gejala Klinis</th>
                      <th class="tg-0lax">Penyakit Atau Cedera Akut</th>
                      <th class="tg-0lax">Penyakit Kronis</th>
                      <th class="tg-0lax">Kondisi Sosial dan Lingkungan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td width="170px">
                        Asupan Energi
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.asupanEnergiPenyakitAtauCederaAkut75persen7hari"
                                class="pt-1 pb-1 " label="< 75% dari kebutuhan energi selama > 7 hari " color="primary"
                                square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="200px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.asupanEnergiPenyakitKronis75persen1bulan" class="pt-1 pb-1 "
                                label="< 75% dari kebutuhan energy selama ≥ 1 bulan" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="200px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.asupanEnergiKondisiSosialLingkungan75persen3bulan"
                                class="pt-1 pb-1 " label="< 75% dari kebutuhan energi selama ≥ 3 bulan " color="primary"
                                square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td width="170px">
                        Kehilangan Berat badan
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.kehilanganBBPenyakitAtauCederaAkut1sampai2persen1minggu"
                                class="pt-1 pb-1 " label="1-2%/1 minggu" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.kehilanganBBPenyakitAtauCederaAkut5persen1bulan"
                                class="pt-1 pb-1 " label="5%/1 bulan" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.kehilanganBBPenyakitAtauCederaAkut7koma5persen3bulan"
                                class="pt-1 pb-1 " label="7.5%/3 bulan" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.kehilanganBBPenyakitKronis5persen1bulan" class="pt-1 pb-1 "
                                label="5%/1 bulan" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.kehilanganBBPenyakitKronis7koma5persen3bulan" class="pt-1 pb-1 "
                                label="7.5%/3 bulan" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.kehilanganBBPenyakitKronis7koma10persen6bulan" class="pt-1 pb-1 "
                                label="10%/6 bulan" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.kehilanganBBPenyakitKronis20persen1tahun" class="pt-1 pb-1 "
                                label="20%/1 tahun" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.kehilanganBBKondisiSosialLingkungan5persen1bulan"
                                class="pt-1 pb-1 " label="5%/1 bulan" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.kehilanganBBKondisiSosialLingkungan7koma5persen3bulan"
                                class="pt-1 pb-1 " label="7.5%/3 bulan" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.kehilanganBBKondisiSosialLingkungan7koma10persen6bulan"
                                class="pt-1 pb-1 " label="10%/6 bulan" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.kehilanganBBKondisiSosialLingkungan20persen1tahun"
                                class="pt-1 pb-1 " label="20%/1 tahun" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td width="170px">
                        Kehilangan Massa Lemak
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.kehilanganMassaLemakPenyakitAtauCederaAkutRingan"
                                class="pt-1 pb-1 " label="Ringan" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.kehilanganMassaLemakPenyakitKronisRingan" class="pt-1 pb-1 "
                                label="Ringan" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.kehilanganMassaLemakKondisiSosialLingkunganRingan"
                                class="pt-1 pb-1 " label="Ringan" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td width="170px">
                        Kehilangan Massa Otot
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.kehilanganMassaOtotPenyakitAtauCederaAkutRingan"
                                class="pt-1 pb-1 " label="Ringan" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.kehilanganMassaOtotPenyakitKronisRingan" class="pt-1 pb-1 "
                                label="Ringan" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.kehilanganMassaOtotKondisiSosialLingkunganRingan"
                                class="pt-1 pb-1 " label="Ringan" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td width="170px">
                        Edema /Ascites
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.edemaAscitesPenyakitAtauCederaAkutRingan" class="pt-1 pb-1 "
                                label="Ringan" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.edemaAscitesPenyakitKronisRingan" class="pt-1 pb-1 "
                                label="Ringan" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.edemaAscitesKondisiSosialLingkunganRingan" class="pt-1 pb-1 "
                                label="Ringan" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="column is-12 p-2">
            <div class="columns is-multiline">
              <div class="column" style="overflow: auto;">
                <table class="tg">
                  <thead>
                    <tr>
                      <th class="tg-0lax text-center" colspan="4">Malnutrisi Berat</th>

                    </tr>
                    <tr>
                      <th class="tg-0lax">Kriteria Amnesis & Gejala Klinis</th>
                      <th class="tg-0lax">Penyakit Atau Cedera Akut</th>
                      <th class="tg-0lax">Penyakit Kronis</th>
                      <th class="tg-0lax">Kondisi Sosial dan Lingkungan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td width="170px">
                        Asupan Energi
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.malnutrsiBeratAsupanEnergiPenyakitAtauCederaAkut50persen5hari"
                                class="pt-1 pb-1 " label="≤ 50% dari kebutuhan energi selama ≥ 5  hari " color="primary"
                                square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="200px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.malnutrsiBeratAsupanEnergiPenyakitKronis75persen1bulan"
                                class="pt-1 pb-1 " label="< 75% dari kebutuhan energy selama ≥ 1 bulan" color="primary"
                                square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="200px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.malnutrisBeratAsupanEnergiKondisiSosialLingkungan50persen1bulan"
                                class="pt-1 pb-1 " label="≤50% dari kebutuhan energi selama ≥1 bulan" color="primary"
                                square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td width="170px">
                        Kehilangan Berat badan
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.malnutrisiBeratKehilanganBBPenyakitAtauCederaAkut2persen1minggu"
                                class="pt-1 pb-1 " label=">2%/1 minggu" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.malnutrisiBeratKehilanganBBPenyakitAtauCederaAkut5persen1bulan"
                                class="pt-1 pb-1 " label=">5%/1 bulan" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox
                                v-model="input.malnutrisiBeratKehilanganBBPenyakitAtauCederaAkut7koma5persen3bulan"
                                class="pt-1 pb-1 " label=">7.5%/3 bulan" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.malnutrisiBeratKehilanganBBPenyakitKronis5persen1bulan"
                                class="pt-1 pb-1 " label=">5%/1 bulan" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.malnutrisiBeratKehilanganBBPenyakitKronis7koma5persen3bulan"
                                class="pt-1 pb-1 " label=">7.5%/3 bulan" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.malnutrisiBeratKehilanganBBPenyakitKronis7koma10persen6bulan"
                                class="pt-1 pb-1 " label=">10%/6 bulan" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.malnutrisiBeratKehilanganBBPenyakitKronis20persen1tahun"
                                class="pt-1 pb-1 " label=">20%/1 tahun" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.malnutrisiBeratKehilanganBBKondisiSosialLingkungan5persen1bulan"
                                class="pt-1 pb-1 " label=">5%/1 bulan" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox
                                v-model="input.malnutrisiBeratKehilanganBBKondisiSosialLingkungan7koma5persen3bulan"
                                class="pt-1 pb-1 " label=">7.5%/3 bulan" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox
                                v-model="input.malnutrisiBeratKehilanganBBKondisiSosialLingkungan7koma10persen6bulan"
                                class="pt-1 pb-1 " label=">10%/6 bulan" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.malnutrisiBeratKehilanganBBKondisiSosialLingkungan20persen1tahun"
                                class="pt-1 pb-1 " label=">20%/1 tahun" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td width="170px">
                        Kehilangan Massa Lemak
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.malnutrisiBeratKehilanganMassaLemakPenyakitAtauCederaAkutRingan"
                                class="pt-1 pb-1 " label="Sedang" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.malnutrisiBeratKehilanganMassaLemakPenyakitKronisBerat"
                                class="pt-1 pb-1 " label="Berat" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.malnutrisiBeratKehilanganMassaLemakKondisiSosialLingkunganBerat"
                                class="pt-1 pb-1 " label="Berat" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td width="170px">
                        Kehilangan Massa Otot
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.malnutrisiBeratKehilanganMassaOtotPenyakitAtauCederaAkutRingan"
                                class="pt-1 pb-1 " label="Sedang" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.malnutrisiBeratKehilanganMassaOtotPenyakitKronisBerat"
                                class="pt-1 pb-1 " label="Berat" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.malnutrisiBeratKehilanganMassaOtotKondisiSosialLingkunganBerat"
                                class="pt-1 pb-1 " label="Berat" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td width="170px">
                        Edema /Ascites
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.malnutrisiBeratEdemaAscitesPenyakitAtauCederaAkutRingan"
                                class="pt-1 pb-1 " label="Sedang" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.malnutrisiBeratEdemaAscitesPenyakitKronisRingan"
                                class="pt-1 pb-1 " label="Berat" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.malnutrisiBeratEdemaAscitesKondisiSosialLingkunganRingan"
                                class="pt-1 pb-1 " label="Berat" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td width="170px">
                        Penurunan Kekuatan Tangan
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.malnutrisiBeratPenurunanTanganPenyakitAtauCederaAkutMenurun"
                                class="pt-1 pb-1 " label="Menurun" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.malnutrisiBeratPenurunanTanganPenyakitKronisMenurun"
                                class="pt-1 pb-1 " label="Menurun" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.malnutrisiBeratPenurunanTanganKondisiSosialLingkunganMenurun"
                                class="pt-1 pb-1 " label="Menurun" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="column is-12 p-2">
            <div class="columns is-multiline">
              <div class="column" style="overflow: auto;">
                <table class="tg">
                  <thead>
                    <tr>
                      <th class="tg-0lax text-center" colspan="2">Kriteria Lain</th>

                    </tr>
                    <tr>
                      <th class="tg-0lax text-center">Malnutrisi Sedang</th>
                      <th class="tg-0lax text-center">Malnutrisi Berat</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td width="170px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.malnutrisiSedangAntropometri" class="pt-1 pb-1 "
                                label="Antropometri: IMT 16.0 - 16.9 kg/m2" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.malnutrisiSedangPemeriksaanLaboratorium" class="pt-1 pb-1 "
                                label="PemeriksaanLaboratorium :Albumin 2.9 - 2.5 g/dL" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="170px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.malnutrisiBeratAntropometri" class="pt-1 pb-1 "
                                label="Antropometri: IMT <16 kg/m2" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.malnutrisiBeratPemeriksaanLaboratorium" class="pt-1 pb-1 "
                                label="PemeriksaanLaboratorium :Albumin <2.5 g/dL" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="column is-12">
            <VCard class="p-3">
              <div class="column is-12 px-2">
                <div class="columns is-multiline mb-5">
                  <div class="column is-12 P-0">
                    <div class="column is-12">
                      <span> CATATAN : </span>
                    </div>
                    <div class="column is-12">
                      <span> Diagnosis Malnutrisi ditegakkan apabila memenuhi minimal 2 dari 6 Kriteria
                        DiagnosisMalnutrisi, ATAU memenuhi Kriteria Lain seperti Antropometri ATAU Pemeriksaan
                        laboratorium.(PNPK Malnutrisi Dewasa 2019. Hal. 30) </span>
                    </div>

                  </div>
                </div>
                <div class="columns is-multiline mb-5">
                  <div class="column is-12">
                    <span> KESIMPULAN :
                    </span>
                  </div>
                  <div class="column is-12">
                    <VField style="padding:0px;">
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.kesimpulanMalnutrisiSedang" class="pt-1 pb-1 " label="MALNUTRISI SEDANG"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VField style="padding:0px;">
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.kesimpulanMalnutrisiBerat" class="pt-1 pb-1 " label="MALNUTRISI BERAT"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-6">
            <img v-if="input.dokterPengkajian"
                  :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.dokterPengkajian.label ? input.dokterPengkajian.label : input.dokterPengkajian) + ', ' + (input.tglPembuatan ? input.tglPembuatan : input.tglPembuatan)">
            <!-- <TandaTangan
              :elemenID="'signature_1'"
              :width="'180'"
              :height="'180'"
            ></TandaTangan> -->
          </div>
        </div>
          <div class="column is-4">
            <h1 class="p-0" style="font-weight: bold">Dokter</h1>
            <VField>
              <VControl class="prime-auto">
                <AutoComplete
                  v-model="input.dokterPengkajian"
                  :suggestions="d_Dokter"
                  @complete="fetchDokter($event)"
                  :optionLabel="'label'"
                  :dropdown="true"
                  :minLength="3"
                  :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'"
                  :field="'label'"
                  placeholder="Dokter..."
                  class="mt-2"
                  @item-select="setTandaTangan($event)"
                />
              </VControl>
            </VField>
          </div>
                  </div>
                </div>
              </div>
            </VCard>
          </div>

        </Fieldset>
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
import ButtonEmr from '../page-emr-plugins/button-emr-gizi.vue'
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

const input: any = ref({})
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
const d_Diagnosa: any = ref([])
const isLoadingTT: any = ref(false)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref("LembarAsesmenMonitoringEvaluasiTerapiGizi") //table mongodb
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
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      if (response.length > 0) {
        input.value = response[0] //set ke inputan
        if (response[0].tandaTanganDokter) {
          H.tandaTangan().set('signature_1', response[0].tandaTanganDokter)
        }
        if (response[0].tandaTanganPasien) {
          H.tandaTangan().set('signature_2', response[0].tandaTanganPasien)
        }
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      } else {
        setAutoFill()
      }
    }
    })
}

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}

const fetchDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/diagnosa_m?select=id,namadiagnosa&param_search=namadiagnosa&query=${filter.query}&limit=10`)
  d_Diagnosa.value = response
}

const fetchKelas = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/kelas_m?select=id,namakelas&param_search=namakelas&query=${filter.query}&limit=10`)
  d_Kelas.value = response
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
  input.value.tgllahir = props.pasien.tgllahir
  input.value.namapasien = props.pasien.namapasien
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.nocm = props.pasien.nocm
  input.value.namaruangan = props.registrasi.namaruangan
  input.value.namakelas = props.registrasi.namakelas
  input.value.dokter = props.registrasi.dokter
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
