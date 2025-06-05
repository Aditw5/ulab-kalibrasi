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
                <span> OS : </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="... " v-model="input.OS" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Umur : </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="... " v-model="input.umur" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> DIagnosa : </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="... " v-model="input.diagnosa" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> No. Form : </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="... " v-model="input.nomorForm" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Ruangan : </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="... " v-model="input.ruangan" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Diterima Tgl : </span>
              </div>
              <div class="column is-6">
                <VField class="mt-2">
                  <VDatePicker v-model="input.tglDiterima" mode="dateTime" style="width: 100%" trim-weeks
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
                <span> Petugas : </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="... " v-model="input.petugas" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <Fieldset :toggleable="true" legend="CEK LISS PEMERIKSAAN GOLONGAN DARAH ABO & Rhesus">
            <table class="tbl-kmICU">
            <thead>
              <tr>
                <th rowspan="3">Identitas OS / Donor</th>
                <th colspan="3" >Sel Grouping</th>
                <th colspan="3" >Serum Typing</th>
                <th>Auto Kontrol</th>
                <th colspan="2">Rhesus Faktor</th>
              </tr>
              <tr>
                <th>1 tts Sel 5%</th>
                <th>1 tts Sel 5%</th>
                <th>1 tts Sel 5%</th>
                <th>2 tts serum</th>
                <th>2 tts serum</th>
                <th>2 tts serum</th>
                <th>2 tts serum</th>
                <th>1 tts Sel 5%</th>
                <th>1 tts Sel 5%</th>
              </tr>
              <tr>
                <th>2 tts Anti-A</th>
                <th>2 tts Anti-B</th>
                <th>2 tts Anti-AB</th>
                <th>1 tts tes Sel A 5%</th>
                <th>1 tts tes Sel B 5%</th>
                <th>1 tts tes Sel O 5%</th>
                <th>1 tts Sel 5%</th>
                <th>1 tts tes Anti-D</th>
                <th>1 tts tes B Aib 6%</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="width: 28%">
                  <div class="column is-12 p-0">
                      <div class="column is-12">
                        <VField>
                          <VControl>
                            <VInput type="text" class="input" placeholder="... " v-model="input.textPemeriksaanGolonganDarah1" />
                          </VControl>
                        </VField>
                      </div>
                  </div>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah1" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah2" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah3" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah4" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah5" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah6" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah7" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah8" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah9" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
              </tr>
              <tr>
                <td colspan="10">
                  <p>Kocok - kocok hingga tercampur rata, putar 3000 rpm 15 - 20 detik -> baca reaksi</p>
                </td>
              </tr>
              <tr>
                <td style="width: 28%">
                  <div class="column is-12 p-0">
                      <div class="column is-12">
                        <VField>
                          <VControl>
                            <VInput type="text" class="input" placeholder="... " v-model="input.textPemeriksaanGolonganDarah2" />
                          </VControl>
                        </VField>
                      </div>
                  </div>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah10" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah11" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah12" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah13" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah14" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah15" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah16" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah17" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah18" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
              </tr>
              <tr>
                <td style="width: 28%">
                  <div class="column is-12 p-0">
                      <div class="column is-12">
                        <VField>
                          <VControl>
                            <VInput type="text" class="input" placeholder="... " v-model="input.textPemeriksaanGolonganDarah3" />
                          </VControl>
                        </VField>
                      </div>
                  </div>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah19" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah20" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah21" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah22" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah23" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah24" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah25" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah26" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah27" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
              </tr>
              <tr>
                <td style="width: 28%">
                  <div class="column is-12 p-0">
                      <div class="column is-12">
                        <VField>
                          <VControl>
                            <VInput type="text" class="input" placeholder="... " v-model="input.textPemeriksaanGolonganDarah4" />
                          </VControl>
                        </VField>
                      </div>
                  </div>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah28" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah29" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah30" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah31" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah32" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah33" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah34" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah35" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah36" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
              </tr>
              <tr>
                <td style="width: 28%">
                  <div class="column is-12 p-0">
                      <div class="column is-12">
                        <VField>
                          <VControl>
                            <VInput type="text" class="input" placeholder="... " v-model="input.textPemeriksaanGolonganDarah5" />
                          </VControl>
                        </VField>
                      </div>
                  </div>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah37" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah39" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah40" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah41" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah42" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah43" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah44" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah45" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
                <td style="width: 8%">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ceklisPemeriksaanGolonganDarah46" class="p-0" color="primary" circle />
                    </VControl>
                  </VField>
                </td>
              </tr>
              <tr>
                <td colspan="10">
                  <p>Kocok - kocok hingga tercampur rata, putar 3000 rpm 15 - 20 detik -> baca reaksi</p>
                </td>
              </tr>
            </tbody>
          </table>
          </Fieldset>
        </VCard>
        <VCard>
          <Fieldset :toggleable="true" legend="HASIL PEMERIKSAAN GOLONGAN DARAH">
            <div class="column is-12">
              <table border="1">
                <thead>
                  <tr>
                    <th rowspan="2">Identitas OS / Donor</th>
                    <th rowspan="2">Tgl Pengambilan</th>
                    <th rowspan="2">Jns Drh</th>
                    <th colspan="3">Sel Grouping</th>
                    <th colspan="3">Serum Grouping</th>
                    <th rowspan="2">Auto Kontrol</th>
                    <th colspan="3">Rhesus</th>
                    <th colspan="2">Kesimpulan</th>
                    <th rowspan="2" colspan="2">Pemeriksaan</th>
                  </tr>
                  <tr>
                    <th>Anti -A</th>
                    <th>Anti -B</th>
                    <th>Anti -AB</th>
                    <th>Sel A</th>
                    <th>Sel B</th>
                    <th>Sel O</th>
                    <th>Anti -D</th>
                    <th>Bv.al 6%</th>
                    <th>Dµ</th>
                    <th>ABO</th>
                    <th>Rh</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(datas, i) in  hasilPemeriksaan">
                    <!-- <td width="18%" style="font-weight: 600;">{{ datas.label }}</td> -->
                    <td v-for="data in datas.children">
                      <div class="column is-12" v-for="checkbox in data">
                        <VField v-if="checkbox.type == 'input'">
                          <VControl subcontrol expanded style="width:30px !important; ">
                            <VInput type="text" v-model="input[checkbox.model]"></VInput>
                          </VControl>
                        </VField>
                        <VField v-if="checkbox.type == 'inputawal'">
                          <VControl subcontrol expanded style="width:90px !important; ">
                            <VInput type="text" v-model="input[checkbox.model]"></VInput>
                          </VControl>
                        </VField>
                        <VField v-if="checkbox.type == 'waktu'">
                          <VDatePicker v-model="input[checkbox.model]" mode="dateTime" style="width: 100%" trim-weeks
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
            </div>
          </Fieldset>
        </VCard>
        <VCard>
          <Fieldset :toggleable="true" legend="CEK LIST PEMERIKSAAN UJI COCOK SERASI DENGAN GEL TEST">
            <div class="columns is-multiline">
              <div class="column is-12">
                <table border="1" class="tbl-kmICU">
                  <thead>
                    <tr>
                      <th>Mayor I</th>
                      <th>Mayor II</th>
                      <th>Mayor III</th>
                      <th>Mayor IV</th>
                      <th>Minor I</th>
                      <th>Minor II</th>
                      <th>Minor III</th>
                      <th>Minor IV</th>
                      <th>Auto Kontrol</th>
                      <th>Auoto Pool I</th>
                      <th>Auoto Pool II</th>
                      <th>Inter Pool I</th>
                      <th>Inter Pool II</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(datas, i) in ujiCocok">
                      <!-- <td width="18%" style="font-weight: 600;">{{ datas.label }}</td> -->
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
                              <VCheckbox v-model="input[checkbox.model]" class="p-0" color="primary" circle :label="checkbox.label" />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="13">
                        <p style="text-align: center">Inkubasi kartu gel selama 15 menit pada suhu 37°C</p>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="13">
                        <p style="text-align: center">Putar kartu gel pada confrigure dengan kecepatan 1030 rpm selama 10 menit -> baca reaksi</p>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </Fieldset>
        </VCard>
        <VCard>
          <Fieldset :toggleable="true" legend="HASIL PEMERIKSAAN INDIRECT COMB'STEST (IDCT)">
            <div class="columns is-multiline">
              <div class="column is-12">
                <h1>1. METODE SIAMED - ID</h1>
                <table border="1" class="tbl-kmICU">
                  <thead>
                    <tr>
                      <th colspan="4">MAYOR</th>
                      <th colspan="4">MINOR</th>
                      <th rowspan="2">AC</th>
                      <th colspan="2">AUTO POOL</th>
                      <th colspan="2">IP/IC</th>
                      <th rowspan="2">Kesimpulan</th>
                      <th rowspan="2">Pemeriksaan</th>
                    </tr>
                    <tr>
                      <th>I</th>
                      <th>II</th>
                      <th>III</th>
                      <th>IV</th>
                      <th>I</th>
                      <th>II</th>
                      <th>III</th>
                      <th>IV</th>
                      <th>I</th>
                      <th>II</th>
                      <th>I</th>
                      <th>II</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(datas, i) in cairanIntravena">
                      <!-- <td width="18%" style="font-weight: 600;">
                        <div class="column is-12">
                          <VField v-if="datas.type == 'input'" addons>
                            <VControl subcontrol expanded style="width:100px !important; ">
                              <VInput type="text" v-model="input[datas.model]"></VInput>
                            </VControl>
                          </VField>
                        </div>
                      </td> -->
                      <td v-for="data in datas.children">
                        <div class="column is-12" v-for="checkbox in data">
                          <VField v-if="checkbox.type == 'input'">
                            <VControl subcontrol expanded>
                              <VInput type="text" v-model="input[checkbox.model]"></VInput>
                            </VControl>
                          </VField>
                          <VField  v-if="checkbox.type == 'input2'">
                            <VControl>
                              <VTextarea rows="1" placeholder="..."
                                v-model="input[checkbox.model]">
                              </VTextarea>
                            </VControl>
                          </VField>
                          <VField v-if="checkbox.type == 'waktu'">
                            <VControl>
                                <VInput type="time" class="input form-timepicker mt-2" style="width:60px !important; " placeholder=""
                                    v-model="input[checkbox.model]" />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </Fieldset>
        </VCard>
        <VCard>
          <Fieldset :toggleable="true" legend="PEMERIKSAAN DIRECT COOMB'S TEST (DCT)">
            <div class="columns is-multiline">
              <div class="column is-12">
                <table border="1">
                  <thead>
                    <tr>
                      <th>Gel Test</th>
                      <th>Coomb's Serum</th>
                      <th>Saline</th>
                      <th>Anti - C3d</th>
                      <th>Anti - Igd </th>
                      <th>Kesimpulan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td rowspan="2">
                        <div class="column is-12">
                          <VField >
                            <h5>CCC :</h5>
                            <VControl>
                              <VTextarea rows="3" placeholder="..."
                                v-model="input.ccc">
                              </VTextarea>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td>
                        <div class="column is-12">
                          <VField>
                            <VControl subcontrol expanded style="width:60px !important; ">
                              <VInput type="text" v-model="input.coombSerum1"></VInput>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td>
                        <div class="column is-12">
                          <VField>
                            <VControl subcontrol expanded style="width:60px !important; ">
                              <VInput type="text" v-model="input.saline1"></VInput>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td>
                        <div class="column is-12">
                          <VField>
                            <VControl subcontrol expanded style="width:60px !important; ">
                              <VInput type="text" v-model="input.antiC3d"></VInput>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td>
                        <div class="column is-12">
                          <VField>
                            <VControl subcontrol expanded style="width:60px !important; ">
                              <VInput type="text" v-model="input.antiIgd"></VInput>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td>
                        <div class="column is-12">
                          <VField >
                            <VControl>
                              <VTextarea rows="1" placeholder="..."
                                v-model="input.kesimpulan1">
                              </VTextarea>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div class="column is-12">
                          <VField>
                            <VControl subcontrol expanded style="width:60px !important; ">
                              <VInput type="text" v-model="input.coombSerum2"></VInput>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td>
                        <div class="column is-12">
                          <VField>
                            <VControl subcontrol expanded style="width:60px !important; ">
                              <VInput type="text" v-model="input.saline2"></VInput>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td>
                        <div class="column is-12">
                          <VField>
                            <VControl subcontrol expanded style="width:60px !important; ">
                              <VInput type="text" v-model="input.antiC3d2"></VInput>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td>
                        <div class="column is-12">
                          <VField>
                            <VControl subcontrol expanded style="width:60px !important; ">
                              <VInput type="text" v-model="input.antiIgd2"></VInput>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td>
                        <div class="column is-12">
                          <VField >
                            <VControl>
                              <VTextarea rows="1" placeholder="..."
                                v-model="input.kesimpulan2">
                              </VTextarea>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </Fieldset>
        </VCard>
        <div class="column is-12 p-0">
          <div class="is-flex">
            <div class="column is-2" style="margin-top:0.5rem">
              <span> Tanggal Pemeriksaan : </span>
            </div>
            <div class="column is-3">
              <VField class="mt-2">
                <VDatePicker v-model="input.tanggalPemeriksaan" mode="dateTime" style="width: 100%" trim-weeks
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
              <span> s / d  </span>
            </div>
            <div class="column is-3">
              <VField class="mt-2">
                <VDatePicker v-model="input.tanggalSampaiDengan" mode="dateTime" style="width: 100%" trim-weeks
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
              <span> Diperiksa Oleh : </span>
            </div>
            <div class="column is-6">
              <VField>
                <VControl>
                  <VInput type="text" class="input" placeholder="... " v-model="input.diperiksaOleh" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        <div class="column is-12 p-0">
          <div class="is-flex">
            <div class="column is-2" style="margin-top:0.5rem">
              <span> Dicek oleh : </span>
            </div>
            <div class="column is-6">
              <VField>
                <VControl>
                  <VInput type="text" class="input" placeholder="... " v-model="input.dicekOleh" />
                </VControl>
              </VField>
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
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/lembar-kerja-crossmatching'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let hasilPemeriksaan = ref(EMR.hasilPemeriksaan())
let ujiCocok = ref(EMR.ujiCocok())
let cairanIntravena = ref(EMR.cairanIntravena())
let hasilIntervensi = ref(EMR.hasilIntervensi())

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
const d_Ditemukan: any = ref([])
const d_Ditransfer: any = ref([])
const d_Pegawai: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('lembarKerjaCrossmatching') //table mongodb
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
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

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

const listSiapa = [
  {value: 1, label: "Orang awam"},
  {value: 2, label: "Pekarya"},
  {value: 3, label: "Medis/paramedis"},
  {value: 4, label: "Cleaning Service"},
  {value: 5, label: "Satpam"},
  {value: 6, label: "lainnya"},
]
d_Ditemukan.value = listSiapa

const listRuangTransfer = [
  {value: 1, label: "IGD"},
  {value: 2, label: "ICU"},
  {value: 3, label: "PICU"},
  {value: 4, label: "NICU"},
  {value: 5, label: "lainnya"},
]
d_Ditransfer.value = listRuangTransfer
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

.tbl-kmICU {
  width: 100%;
  border-collapse: collapse;
  font-size: 12pt;
  text-align: center;
  vertical-align: middle;
}

.tbl-kmICU tr,
.tbl-kmICU th,
.tbl-kmICU td {
  border: 1px solid black;
  text-align: center;
  vertical-align: middle;
}
</style>
