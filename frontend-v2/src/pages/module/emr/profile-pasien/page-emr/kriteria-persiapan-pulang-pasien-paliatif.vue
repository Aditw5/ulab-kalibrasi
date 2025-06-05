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

  <div class="columns is-12">
    <VCard>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top:0.5rem">
            <span> Tanggal Penilaian :</span>
          </div>
          <div class="column is-10">
            <VField>
              <VControl class="prime-auto">
                <Calendar v-model="input.tanggalPenilaian" selectionMode="single" :manualInput="true" class="w-100"
                  :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top:0.5rem">
            <span> DPJP yang Merujuk :</span>
          </div>
          <div class="column is-10">
            <VField>
              <VControl class="prime-auto">
                <AutoComplete v-model="input.dpjpYangMerujuk" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="DPJP" class="mt-2" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top:0.5rem">
            <span> Dokter Paliatif :</span>
          </div>
          <div class="column is-10">
            <VField>
              <VControl class="prime-auto">
                <AutoComplete v-model="input.dokterPaliatif" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Dokter" class="mt-2" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <table class="tbl-kmICU">
        <thead>
          <tr>
            <th>NO</th>
            <th>KRITERIA YANG DI NILAI</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td class="p-4" style="text-align: left">
              <h1>STATUS KESEHATAN MEDIS </h1>
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.faseStabil" label="Fase stabil, dengan simptom yang dapat dikontrol" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.faseTidakStabil" label="Fase tidak stabil, dimana symptom  tidak sepenuhnya dapat terkontrol" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.fasePerburukan" label="Fase perburukan" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.faseSkaratul" label="Fase sakaratul maut (tahap terminal)" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
            </td>
          </tr>
          <tr>
            <td>2</td>
            <td class="p-4" style="text-align: left">
              <h1>RENCANA PERAWATAN DI RUMAH</h1>
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.akhirKehidupan" label="Akhir Kehidupan" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.perawatanKondisiUmum" label="Perawatan kondisi umum untuk pengobatan " class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.lainLainRencanaPerawatanDiRumah" label="Lain - Lain" class="p-0" color="primary" square />
                  </VControl>
                </VField>
                <VField class="column is-3">
                  <VControl>
                    <VInput type="text" class="input" placeholder="lainnya... " v-model="input.textLainLainRencanaPerawatanDiRumah" />
                  </VControl>
                </VField>
              </div>
            </td>
          </tr>
          <tr>
            <td>3</td>
            <td class="p-4">
              <h1>FAKTOR KELUARGA</h1>
              <div class="mt-2">
                <table class="tbl-kmICU">
                  <thead>
                    <tr>
                      <th width="60%"></th>
                      <th width="20%">YA</th>
                      <th width="20%">TIDAK</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="p-4">
                        <h4>Keluarga sudah mengerti dan menerima kondisi penyakit pasien</h4>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYa1" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidak1" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <h4>Keluarga sudah mempersiapkan diri secara psikologis untuk menghadapi proses kematian pasien.</h4>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYa2" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidak2" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <h4>Keluarga memahami perawatan pasien  di rumah</h4>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYa3" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidak3" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4" colspan="3">
                        <h4 class="text-bold">Perawatan Di Rumah</h4>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <VField>
                            <VControl>
                              <VCheckbox v-model="input.perawatanOralHygiene" label="Perawatan oral hygiene" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYa4" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidak4" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <VField>
                            <VControl>
                              <VCheckbox v-model="input.perawatanKulit" label="Perawatan kulit" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYa5" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidak5" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <VField>
                            <VControl>
                              <VCheckbox v-model="input.perawatanMemandikanPasien" label="Perawatan memandikan pasien" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYa6" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidak6" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <VField>
                            <VControl>
                              <VCheckbox v-model="input.caraMobilisasi" label="Cara Mobilisasi" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYa7" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidak7" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <VField>
                            <VControl>
                              <VCheckbox v-model="input.perawatanKateter" label="Perawatan kateter" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYa8" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidak8" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <VField>
                            <VControl>
                              <VCheckbox v-model="input.perawatanNgt" label="Perawatan NGT" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYa9" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidak9" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <VField>
                            <VControl>
                              <VCheckbox v-model="input.gantiBalutan" label="Ganti balutan" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYa10" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidak10" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <VField>
                            <VControl>
                              <VCheckbox v-model="input.perawatanLainLain" label="Lain-lain" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                      </td>
                      <td class="p-4" colspan="2">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VTextarea class="textarea" v-model="input.textareaLainLainPerawatan" rows="3"
                                placeholder="......" autocomplete="off" autocapitalize="off"
                                spellcheck="true" />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </td>
          </tr>
          <tr>
            <td>4</td>
            <td class="p-4">
              <div class="mt-2">
                <table class="tbl-kmICU">
                  <thead>
                    <tr>
                      <th width="60%">CAREGIVER</th>
                      <th width="20%">YA</th>
                      <th width="20%">TIDAK</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="p-4">
                        <VField>
                          <VControl>
                            <VTextarea class="textarea" v-model="input.textareaCaregiver1" rows="1"
                              placeholder="1....." autocomplete="off" autocapitalize="off"
                              spellcheck="true" />
                          </VControl>
                        </VField>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYaCaregiver1" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidak1" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <VField>
                          <VControl>
                            <VTextarea class="textarea" v-model="input.textareaCaregiver2" rows="1"
                              placeholder="2....." autocomplete="off" autocapitalize="off"
                              spellcheck="true" />
                          </VControl>
                        </VField>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYaCaregiver2" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidak2" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <VField>
                          <VControl>
                            <VTextarea class="textarea" v-model="input.textareaCaregiver3" rows="1"
                              placeholder="3....." autocomplete="off" autocapitalize="off"
                              spellcheck="true" />
                          </VControl>
                        </VField>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYaCaregiver3" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidak3" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <VField>
                          <VControl>
                            <VTextarea class="textarea" v-model="input.textareaCaregiver4" rows="1"
                              placeholder="4....." autocomplete="off" autocapitalize="off"
                              spellcheck="true" />
                          </VControl>
                        </VField>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYaCaregiver4" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidak4" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <VField>
                          <VControl>
                            <VTextarea class="textarea" v-model="input.textareaCaregiver5" rows="1"
                              placeholder="5....." autocomplete="off" autocapitalize="off"
                              spellcheck="true" />
                          </VControl>
                        </VField>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYaCaregiver5" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidak5" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </td>
          </tr>
          <tr>
            <td>5</td>
            <td class="p-4">
              <h1>Gejala</h1>
              <ol class="p-4">
                <li>
                  <VField>
                    <VControl>
                      <VTextarea class="textarea" v-model="input.gejala1" rows="1"
                        placeholder="1....." autocomplete="off" autocapitalize="off"
                        spellcheck="true" />
                    </VControl>
                  </VField>
                </li>
                <li class="mt-4">
                  <VField>
                    <VControl>
                      <VTextarea class="textarea" v-model="input.gejala2" rows="1"
                        placeholder="2....." autocomplete="off" autocapitalize="off"
                        spellcheck="true" />
                    </VControl>
                  </VField>
                </li>
                <li class="mt-4">
                  <VField>
                    <VControl>
                      <VTextarea class="textarea" v-model="input.gejala3" rows="1"
                        placeholder="3....." autocomplete="off" autocapitalize="off"
                        spellcheck="true" />
                    </VControl>
                  </VField>
                </li>
                <li class="mt-4">
                  <VField>
                    <VControl>
                      <VTextarea class="textarea" v-model="input.gejala4" rows="1"
                        placeholder="4....." autocomplete="off" autocapitalize="off"
                        spellcheck="true" />
                    </VControl>
                  </VField>
                </li>
              </ol>
            </td>
          </tr>
          <tr>
            <td>6</td>
            <td>
              <div class="column is-12 p-0">
                <div class="is-flex">
                  <div class="column is-12" style="margin-top:0.5rem; text-align: left;">
                    <span> Pengertian Pasien terhadap Kondisi nya :</span>
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.ceklisYaPengertianPasien" label="Ya" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.ceklisTidakPengertianPasien" label="Tidak" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <td>7</td>
            <td class="p-4 column is-8">
              <div class="mt-2">
                <table class="tbl-kmICU">
                  <thead>
                    <tr>
                      <th width="40%">INTAKE</th>
                      <th width="10%">YA</th>
                      <th width="10%">TIDAK</th>
                      <th width="40%">TANGGAL PENGGANTIAN</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="p-4">
                        <p>Oral</p>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYaOral" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisOral" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl class="prime-auto">
                              <Calendar v-model="input.tanggalPenggantian1" selectionMode="single" :manualInput="true" class="w-100"
                                :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <p>NGT</p>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYaNgt" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidakNgt" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl class="prime-auto">
                              <Calendar v-model="input.tanggalPenggantian2" selectionMode="single" :manualInput="true" class="w-100"
                                :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <p>Infus</p>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYaInfus" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidakInfus" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl class="prime-auto">
                              <Calendar v-model="input.tanggalPenggantian3" selectionMode="single" :manualInput="true" class="w-100"
                                :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <p>a. SC</p>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYaSc" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidakSc" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl class="prime-auto">
                              <Calendar v-model="input.tanggalPenggantian4" selectionMode="single" :manualInput="true" class="w-100"
                                :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <p>b. IV</p>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYaIv" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidakIv" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl class="prime-auto">
                              <Calendar v-model="input.tanggalPenggantian5" selectionMode="single" :manualInput="true" class="w-100"
                                :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </td>
          </tr>
          <tr>
            <td>8</td>
            <td class="p-4 column is-8">
              <div class="mt-2">
                <table class="tbl-kmICU">
                  <thead>
                    <tr>
                      <th width="40%">ELIMINASI</th>
                      <th width="10%">YA</th>
                      <th width="10%">TIDAK</th>
                      <th width="40%">TANGGAL PENGGANTIAN</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="p-4">
                        <p>Spontan</p>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYaSpontan" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisSpontan" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl class="prime-auto">
                              <Calendar v-model="input.tanggalPenggantian6" selectionMode="single" :manualInput="true" class="w-100"
                                :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <p>Kateter</p>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYaKateter" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidakKateter" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl class="prime-auto">
                              <Calendar v-model="input.tanggalPenggantian7" selectionMode="single" :manualInput="true" class="w-100"
                                :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <p>Nefrostomy</p>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYaNefrostomy" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidakNefrostomy" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl class="prime-auto">
                              <Calendar v-model="input.tanggalPenggantian8" selectionMode="single" :manualInput="true" class="w-100"
                                :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <p>Colostomy</p>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYaColostomy" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidakColostomy" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl class="prime-auto">
                              <Calendar v-model="input.tanggalPenggantian9" selectionMode="single" :manualInput="true" class="w-100"
                                :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <VField>
                          <VControl>
                            <VTextarea class="textarea" v-model="input.eliminasiText" rows="1"
                              placeholder="....." autocomplete="off" autocapitalize="off"
                              spellcheck="true" />
                          </VControl>
                        </VField>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYaEliminasi" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidakEliminasi" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl class="prime-auto">
                              <Calendar v-model="input.tanggalPenggantian10" selectionMode="single" :manualInput="true" class="w-100"
                                :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </td>
          </tr>
          <tr>
            <td>9</td>
            <td class="p-4 column is-8">
              <div class="mt-2">
                <table class="tbl-kmICU">
                  <thead>
                    <tr>
                      <th width="40%">SISTEM RESPIRASI</th>
                      <th width="10%">YA</th>
                      <th width="10%">TIDAK</th>
                      <th width="40%">TANGGAL PENGGANTIAN</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="p-4">
                        <p>Oksigen</p>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYaOksigen" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisOksigen" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl class="prime-auto">
                              <Calendar v-model="input.tanggalPenggantian11" selectionMode="single" :manualInput="true" class="w-100"
                                :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <p>Tracheostomy</p>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYaTracheostomy" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidakTracheostomy" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl class="prime-auto">
                              <Calendar v-model="input.tanggalPenggantian12" selectionMode="single" :manualInput="true" class="w-100"
                                :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <p>Suction</p>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYaSuction" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidakSuction" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl class="prime-auto">
                              <Calendar v-model="input.tanggalPenggantian13" selectionMode="single" :manualInput="true" class="w-100"
                                :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <p>Nebulizer</p>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYaNebulizer" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidakNebulizer" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl class="prime-auto">
                              <Calendar v-model="input.tanggalPenggantian14" selectionMode="single" :manualInput="true" class="w-100"
                                :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <VField>
                          <VControl>
                            <VTextarea class="textarea" v-model="input.sistemRespirasiText" rows="1"
                              placeholder="....." autocomplete="off" autocapitalize="off"
                              spellcheck="true" />
                          </VControl>
                        </VField>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYaSistemRespirasi" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidakSistemRespirasi" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl class="prime-auto">
                              <Calendar v-model="input.tanggalPenggantian15" selectionMode="single" :manualInput="true" class="w-100"
                                :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </td>
          </tr>
          <tr>
            <td>10</td>
            <td class="p-4 column is-8">
              <div class="mt-2">
                <table class="tbl-kmICU">
                  <thead>
                    <tr>
                      <th width="40%">SKIN CARE</th>
                      <th width="10%">YA</th>
                      <th width="10%">TIDAK</th>
                      <th width="40%">TANGGAL PENGGANTIAN</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="p-4">
                        <p>Dekubitus</p>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYaDekubitus" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisDekubitus" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl class="prime-auto">
                              <Calendar v-model="input.tanggalPenggantian11" selectionMode="single" :manualInput="true" class="w-100"
                                :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <p>Luka</p>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYaLuka" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidakLuka" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl class="prime-auto">
                              <Calendar v-model="input.tanggalPenggantian12" selectionMode="single" :manualInput="true" class="w-100"
                                :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <VField>
                          <VControl>
                            <VTextarea class="textarea" v-model="input.skinCareText1" rows="1"
                              placeholder="....." autocomplete="off" autocapitalize="off"
                              spellcheck="true" />
                          </VControl>
                        </VField>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYaSkinCare1" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidakSkinCare1" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl class="prime-auto">
                              <Calendar v-model="input.tanggalPenggantian13" selectionMode="single" :manualInput="true" class="w-100"
                                :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-4">
                        <VField>
                          <VControl>
                            <VTextarea class="textarea" v-model="input.skinCareText2" rows="1"
                              placeholder="....." autocomplete="off" autocapitalize="off"
                              spellcheck="true" />
                          </VControl>
                        </VField>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisYaSkinCare2" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklisTidakSkinCare2" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="p-4">
                        <div class="mt-2">
                          <VField>
                            <VControl class="prime-auto">
                              <Calendar v-model="input.tanggalPenggantian14" selectionMode="single" :manualInput="true" class="w-100"
                                :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <h1 class="my-4">Obat-obatan yang dibutuhkan untuk perawatan di rumah sudah dipersiapkan</h1>
      <table class="tbl-kmICU">
        <thead>
          <tr>
            <th width="5%" rowspan="2">NO</th>
            <th width="25%" rowspan="2">Nama Obat dan Dosis</th>
            <th width="70%" colspan="5">Tanggal</th>
          </tr>
          <tr>
            <th>
              <VField>
                <VControl class="prime-auto">
                  <Calendar v-model="input.tanggalObat1" selectionMode="single" :manualInput="true" class="w-100"
                    :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                </VControl>
              </VField>
            </th>
            <th>
              <VField>
                <VControl class="prime-auto">
                  <Calendar v-model="input.tanggalObat2" selectionMode="single" :manualInput="true" class="w-100"
                    :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                </VControl>
              </VField>
            </th>
            <th>
              <VField>
                <VControl class="prime-auto">
                  <Calendar v-model="input.tanggalObat3" selectionMode="single" :manualInput="true" class="w-100"
                    :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                </VControl>
              </VField>
            </th>
            <th>
              <VField>
                <VControl class="prime-auto">
                  <Calendar v-model="input.tanggalObat4" selectionMode="single" :manualInput="true" class="w-100"
                    :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                </VControl>
              </VField>
            </th>
            <th>
              <VField>
                <VControl class="prime-auto">
                  <Calendar v-model="input.tanggalObat5" selectionMode="single" :manualInput="true" class="w-100"
                    :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                </VControl>
              </VField>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="p-4">
              <p>1</p>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosis1" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan1" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan2" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan3" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan4" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan5" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
          </tr>
          <tr>
            <td class="p-4">
              <p>2</p>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosis2" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan6" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan7" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan8" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan9" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan10" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
          </tr>
          <tr>
            <td class="p-4">
              <p>3</p>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosis3" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan11" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan12" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan13" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan14" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan15" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
          </tr>
          <tr>
            <td class="p-4">
              <p>4</p>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosis4" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan16" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan17" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan18" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan19" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan20" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
          </tr>
          <tr>
            <td class="p-4">
              <p>5</p>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosis1" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan21" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan22" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan23" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan24" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan25" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
          </tr>
          <tr>
            <td class="p-4">
              <p>6</p>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosis6" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan26" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan27" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan28" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan29" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan30" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
          </tr>
          <tr>
            <td class="p-4">
              <p>7</p>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosis7" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan31" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan32" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan33" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan34" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan35" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
          </tr>
          <tr>
            <td class="p-4">
              <p>8</p>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosis8" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan36" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan37" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan38" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan39" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
            <td class="p-4">
              <div class="mt-2">
                <VField>
                  <VControl>
                    <VTextarea class="textarea" v-model="input.namaObatDanDosisKeterangan40" rows="1"
                      placeholder="......" autocomplete="off" autocapitalize="off"
                      spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top:0.5rem">
            <span> Kontrol Paliatif  tanggal :</span>
          </div>
          <div class="column is-4">
            <VField>
              <VControl class="prime-auto">
                <Calendar v-model="input.tanggalKontrolPaliatif" selectionMode="single" :manualInput="true" class="w-100"
                  :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top:0.5rem">
            <span> Kontrol DPJP  tanggal :</span>
          </div>
          <div class="column is-4">
            <VField>
              <VControl class="prime-auto">
                <Calendar v-model="input.tanggalKontrolDpjp" selectionMode="single" :manualInput="true" class="w-100"
                  :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top:0.5rem">
            <span> Follow up :</span>
          </div>
          <div class="column is-4">
            <VField>
              <VControl>
                <VCheckbox v-model="input.ceklisObat" label="Obat" class="p-0" color="primary" square />
              </VControl>
            </VField>
            <VField>
              <VControl>
                <VCheckbox v-model="input.ceklisGejala" label="Gejala" class="p-0" color="primary" square />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top:0.5rem">
            <span> Rencana Tindakan :</span>
          </div>
          <div class="column is-4">
            <VField>
              <VControl>
                <VTextarea class="textarea" v-model="input.textareaRencanaTindakan" rows="3"
                  placeholder="......" autocomplete="off" autocapitalize="off"
                  spellcheck="true" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12">
              <div class="columns is-multiline">
                  <div class="column is-6"></div>
                  <div class="column is-6">
                      <img v-if="input.petugasPerawat"
                          :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.petugasPerawat ? input.petugasPerawat.label : '-')">
                  </div>
                  <div class="column is-6"></div>
                  <div class="column is-4">
                      <h1 class="p-0" style="font-weight: bold;">Petugas yang mengkaji </h1>
                      <VField>
                          <VControl class="prime-auto">
                              <AutoComplete v-model="input.petugasPerawat" :suggestions="d_Pegawai"
                                  @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                  :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                  :field="'label'" placeholder="Petugas yang mengkaji..." class="mt-2"
                                  @item-select="setTandaTangan($event)" />
                          </VControl>
                      </VField>
                  </div>
              </div>
          </div>
    </VCard>
  </div>
</template>

<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useHead } from '@vueuse/head'
import { useWindowScroll } from '@vueuse/core'
import { useUserSession } from '/@src/stores/userSession'
import { useApi } from '/@src/composable/useApi'
import Calendar from 'primevue/calendar'
import AutoComplete from 'primevue/autocomplete';

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

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


const input: any = ref({})

const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref("kriteriaPersiapanPulangPasienPaliatif") //table mongodb
const NOREC_EMRPASIEN: any = ref(norec_emr ? norec_emr : '')
const { y } = useWindowScroll()
const d_Dokter: any = ref([])
const d_Pegawai: any = ref([])
const isLoading: any = ref(false)

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
const isStuck = computed(() => { return y.value >= 20 })

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}
  input.value.tanggalMRS = H.formatDate(new Date(input.value.tanggalMRS), 'YYYY-MM-DD HH:mm')
  input.value.tanggalKICU = H.formatDate(new Date(input.value.tanggalKICU), 'YYYY-MM-DD HH:mm')
  input.value.tanggalMICU = H.formatDate(new Date(input.value.tanggalMICU), 'YYYY-MM-DD HH:mm')
  input.value.tanggalVerif = H.formatDate(new Date(input.value.tanggalVerif), 'YYYY-MM-DD HH:mm')
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

const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const setAutoFill = () => {
  input.value.dpjp = props.registrasi.dokter
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

setAutoFill()
setView()
loadRiwayat()
</script>
<style scoped lang="scss">
.tbl-kmICU {
  width: 100%;
  border-collapse: collapse;
  font-size: 12pt;
  text-align: center;
}

.tbl-kmICU tr,
.tbl-kmICU th,
.tbl-kmICU td {
  border: 1px solid black;
}
</style>
