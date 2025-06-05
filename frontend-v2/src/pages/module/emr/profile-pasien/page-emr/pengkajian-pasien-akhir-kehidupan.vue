<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
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
                <VField label="Ruang Rawat">
                  <AutoComplete
                    v-model="input.namaruangan"
                    :suggestions="d_Ruangan"
                    @complete="fetchRuangan($event)"
                    :optionLabel="'label'"
                    :dropdown="true"
                    :minLength="3"
                    :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'"
                    :field="'label'"
                    placeholder="ketik untuk mencari..."
                  />
                </VField>
              </div>
              <div class="column is-4">
                <VField label="Tanggal & Pukul Masuk">
                  <VDatePicker
                    v-model="input.tanggalWaktuRegistrasi"
                    mode="dateTime"
                    style="width: 100%"
                    trim-weeks
                    :max-date="new Date()"
                  >
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput
                            :value="inputValue"
                            placeholder="Tanggal"
                            v-on="inputEvents"
                            readonly
                          />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>
            <div class="column is-12">
              <span class="bold-text"> Diagnosis Medis : </span>
              <VField>
                <VTextarea
                  rows="2"
                  placeholder="Diagnosis Medis....."
                  v-model="input.diagnosisMedis"
                ></VTextarea>
              </VField>
            </div>
          </div>
        </VCard>
      </div>

      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="PENGKAJIAN AWAL KEPERAWATAN" :toggleable="true">
          <div class="column is-12">
            <div class="columns is-multiline p-3">
                <div class="column is-3">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Tinggi Badan</h1>
                    <VField addons>
                        <VControl expanded>
                            <VInput type="text" class="input" placeholder="Tinggi Badan"
                                v-model="input.tinggiBadan" />
                        </VControl>
                        <VControl class="field-addon-body">
                            <VButton static>cm</VButton>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Berat Badan</h1>
                    <VField addons>
                        <VControl expanded>
                            <VInput type="text" class="input" placeholder="Berat Badan"
                                v-model="input.beratBadan" />
                        </VControl>
                        <VControl class="field-addon-body">
                            <VButton static>Kg</VButton>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">IMT</h1>
                    <VField addons>
                        <VControl expanded>
                            <VInput type="text" class="input" placeholder="Berat Badan"
                                v-model="input.IMT" />
                        </VControl>
                        <VControl class="field-addon-body">
                            <VButton static>Kg/m²</VButton>
                        </VControl>
                    </VField>
                </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <h1 style="font-weight: bold; margin-bottom: 1rem;">Yang Merawat Pasien</h1>
                  <div class="columns is-multiline">
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox
                            v-model="input.yangMerawat"
                            :true-value="'Suami'"
                            :label="'Suami'"
                            class="p-0"
                            color="primary"
                            square
                          />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox
                            v-model="input.yangMerawat"
                            :true-value="'Istri'"
                            :label="'Istri'"
                            class="p-0"
                            color="primary"
                            square
                          />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox
                            v-model="input.yangMerawat"
                            :true-value="'Anak'"
                            :label="'Anak'"
                            class="p-0"
                            color="primary"
                            square
                          />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox
                            v-model="input.yangMerawat"
                            :true-value="'Tidak Ada'"
                            :label="'Tidak Ada'"
                            class="p-0"
                            color="primary"
                            square
                          />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox
                            v-model="input.yangMerawat"
                            :true-value="'Lain-lain'"
                            :label="'Lain-lain'"
                            class="p-0"
                            color="primary"
                            square
                          />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl>
                          <VInput
                            type="text"
                            class="input"
                            placeholder="... "
                            v-model="input.lainnyaMerawatPasien"
                          />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="column is-12">
              <Fieldset class="p-fieldsets" legend="KAJIAN PSIKOSOSIAL & SPIRITUAL" :toggleable="true">
                <div class="columns is-multiline">
                    <table align="center" class="triase"
                        style="width: 90%; margin-top: 2rem;">
                        <tr>
                            <th style="text-align : center"> Pemahaman </th>
                            <th style="text-align : center"> Pasien </th>
                            <th style="text-align : center"> Keluarga </th>
                        </tr>
                        <tr v-for="(datas) in planning">
                            <td>{{ datas.Kriteria }}</td>
                            <td>
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <VField v-for="(check) in datas.pasien">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0"
                                                    v-model="input[check.model]"
                                                    :true-value="check.label"
                                                    :label="check.label" color="primary"
                                                    square />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </td>
                            <td>
                              <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <VField v-for="(check) in datas.keluarga">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0"
                                                    v-model="input[check.model]"
                                                    :true-value="check.label"
                                                    :label="check.label" color="primary"
                                                    square />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr v-for="(datas) in planning1">
                            <td>{{ datas.Kriteria }}</td>
                            <td colspan="2">
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <VField v-for="(check) in datas.pilihan">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0"
                                                    v-model="input[check.model]"
                                                    :true-value="check.label"
                                                    :label="check.label" color="primary"
                                                    square />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
              </Fieldset>
            </div>

            <div class="column is-12">
              <Fieldset class="p-fieldsets" legend="KAJIAN FISIK" :toggleable="true">
                <div class="column is-12">
                  <h1 style="font-weight: bold;margin-bottom: 0.5rem;">Kesadaran</h1>
                    <div class="columns is-multiline">
                      <div class="column is-2" v-for="(data) in kesadaranFisik">
                          <VField>
                              <VControl raw subcontrol>
                                  <VCheckbox class="p-0" v-model="input[data.model]"
                                      :true-value="data.title" :label="data.title"
                                      color="primary" circle />
                              </VControl>
                          </VField>
                      </div>
                  </div>
                </div>
                <div class="column is-12">
                  <h1 style="font-weight: bold;margin-bottom: 0.5rem;">Perubahan Mental</h1>
                    <div class="columns is-multiline">
                      <div class="column is-2" v-for="(data) in perubahanMental">
                          <VField>
                              <VControl raw subcontrol>
                                  <VCheckbox class="p-0" v-model="input[data.model]"
                                      :true-value="data.title" :label="data.title"
                                      color="primary" circle />
                              </VControl>
                          </VField>
                      </div>
                  </div>
                </div>
                <div class="column is-12">
                  <h1 style="font-weight: bold;margin-bottom: 0.5rem;">Pernafasan</h1>
                    <div class="columns is-multiline">
                      <div class="column is-2">
                          <VField>
                              <VControl raw subcontrol>
                                  <VCheckbox class="p-0" v-model="input.pernafasanFisik"
                                      :true-value="'Irama Ireguler'" :label="'Irama Ireguler'"
                                      color="primary" circle />
                              </VControl>
                          </VField>
                      </div>
                      <div class="column is-2">
                          <VField>
                              <VControl raw subcontrol>
                                  <VCheckbox class="p-0" v-model="input.pernafasanFisik"
                                      :true-value="'Regular'" :label="'Regular'"
                                      color="primary" circle />
                              </VControl>
                          </VField>
                      </div>
                      <div class="column is-3">
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="frekuensi"
                                    v-model="input.frekuensi" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>x/mnt</VButton>
                            </VControl>
                        </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-12">
                  <h1 style="font-weight: bold;margin-bottom: 0.5rem;">Jenis Pernafasan</h1>
                    <div class="columns is-multiline">
                      <div class="column is-2" v-for="(data) in jenisPernafasan">
                          <VField>
                              <VControl raw subcontrol>
                                  <VCheckbox class="p-0" v-model="input[data.model]"
                                      :true-value="data.title" :label="data.title"
                                      color="primary" circle />
                              </VControl>
                          </VField>
                      </div>
                  </div>
                </div>
                <div class="column is-12">
                  <h1 style="font-weight: bold;margin-bottom: 0.5rem;">Sekret</h1>
                    <div class="columns is-multiline">
                      <div class="column is-2" v-for="(data) in sekret">
                          <VField>
                              <VControl raw subcontrol>
                                  <VCheckbox class="p-0" v-model="input[data.model]"
                                      :true-value="data.title" :label="data.title"
                                      color="primary" circle />
                              </VControl>
                          </VField>
                      </div>
                  </div>
                </div>
                <div class="column is-12">
                  <h1 style="font-weight: bold;margin-bottom: 0.5rem;">Sirkulasi Perifir</h1>
                    <div class="columns is-multiline p-3">
                        <div class="column is-3">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Tekanan Darah</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="input" placeholder="tekanan darah"
                                        v-model="input.tekananDarah" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>x/mnt</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Nadi</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="input" placeholder="nadi"
                                        v-model="input.nadi" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>x/mnt</VButton>
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                  <h1 style="font-weight: bold;margin-bottom: 0.5rem;">Irama</h1>
                    <div class="columns is-multiline">
                      <div class="column is-2" v-for="(data) in irama">
                          <VField>
                              <VControl raw subcontrol>
                                  <VCheckbox class="p-0" v-model="input[data.model]"
                                      :true-value="data.title" :label="data.title"
                                      color="primary" circle />
                              </VControl>
                          </VField>
                      </div>
                  </div>
                </div>
                <div class="column is-12">
                  <h1 style="font-weight: bold;margin-bottom: 0.5rem;">Ekstremitas</h1>
                    <div class="columns is-multiline">
                      <div class="column is-2" v-for="(data) in ekstremitas">
                          <VField>
                              <VControl raw subcontrol>
                                  <VCheckbox class="p-0" v-model="input[data.model]"
                                      :true-value="data.title" :label="data.title"
                                      color="primary" circle />
                              </VControl>
                          </VField>
                      </div>
                  </div>
                </div>
                <div class="column is-12">
                  <h1 style="font-weight: bold;margin-bottom: 0.5rem;">Warna Kulit Pucat</h1>
                    <div class="columns is-multiline">
                      <div class="column is-2" v-for="(data) in warnaKulit">
                          <VField>
                              <VControl raw subcontrol>
                                  <VCheckbox class="p-0" v-model="input[data.model]"
                                      :true-value="data.title" :label="data.title"
                                      color="primary" circle />
                              </VControl>
                          </VField>
                      </div>
                  </div>
                </div>
                <div class="column is-12">
                  <h1 style="font-weight: bold;margin-bottom: 0.5rem;">Penglihatan</h1>
                    <div class="columns is-multiline">
                      <div class="column is-2" v-for="(data) in penglihatan">
                          <VField>
                              <VControl raw subcontrol>
                                  <VCheckbox class="p-0" v-model="input[data.model]"
                                      :true-value="data.title" :label="data.title"
                                      color="primary" circle />
                              </VControl>
                          </VField>
                      </div>
                  </div>
                </div>
                <div class="column is-12">
                  <h1 style="font-weight: bold;margin-bottom: 0.5rem;">Reflex Berkedip</h1>
                    <div class="columns is-multiline">
                      <div class="column is-2" v-for="(data) in reflexBerkedip">
                          <VField>
                              <VControl raw subcontrol>
                                  <VCheckbox class="p-0" v-model="input[data.model]"
                                      :true-value="data.title" :label="data.title"
                                      color="primary" circle />
                              </VControl>
                          </VField>
                      </div>
                  </div>
                </div>
                <div class="column is-12">
                  <h1 style="font-weight: bold;margin-bottom: 0.5rem;">Pendengaran</h1>
                    <div class="columns is-multiline">
                      <div class="column is-2" v-for="(data) in pendengaran">
                          <VField>
                              <VControl raw subcontrol>
                                  <VCheckbox class="p-0" v-model="input[data.model]"
                                      :true-value="data.title" :label="data.title"
                                      color="primary" circle />
                              </VControl>
                          </VField>
                      </div>
                  </div>
                </div>
                <div class="column is-12">
                  <h1 style="font-weight: bold;margin-bottom: 0.5rem;">Sensi</h1>
                    <div class="columns is-multiline">
                      <div class="column is-2" v-for="(data) in sensi">
                          <VField>
                              <VControl raw subcontrol>
                                  <VCheckbox class="p-0" v-model="input[data.model]"
                                      :true-value="data.title" :label="data.title"
                                      color="primary" circle />
                              </VControl>
                          </VField>
                      </div>
                  </div>
                </div>
                <div class="column is-12">
                  <h1 style="font-weight: bold;margin-bottom: 0.5rem;">Tonus Otot</h1>
                    <div class="columns is-multiline">
                      <div class="column is-2" v-for="(data) in tonusOtot">
                          <VField>
                              <VControl raw subcontrol>
                                  <VCheckbox class="p-0" v-model="input[data.model]"
                                      :true-value="data.title" :label="data.title"
                                      color="primary" circle />
                              </VControl>
                          </VField>
                      </div>
                  </div>
                </div>
                <div class="column is-12">
                  <h1 style="font-weight: bold;margin-bottom: 0.5rem;">Nafsu Makan</h1>
                    <div class="columns is-multiline">
                      <div class="column is-2" v-for="(data) in nafsuMakan">
                          <VField>
                              <VControl raw subcontrol>
                                  <VCheckbox class="p-0" v-model="input[data.model]"
                                      :true-value="data.title" :label="data.title"
                                      color="primary" circle />
                              </VControl>
                          </VField>
                      </div>
                  </div>
                </div>
                <div class="column is-12">
                  <h1 style="font-weight: bold;margin-bottom: 0.5rem;">Mukosa Mulut</h1>
                    <div class="columns is-multiline">
                      <div class="column is-2" v-for="(data) in mukosaMulut">
                          <VField>
                              <VControl raw subcontrol>
                                  <VCheckbox class="p-0" v-model="input[data.model]"
                                      :true-value="data.title" :label="data.title"
                                      color="primary" circle />
                              </VControl>
                          </VField>
                      </div>
                  </div>
                </div>
                <div class="column is-12">
                  <h1 style="font-weight: bold;margin-bottom: 0.5rem;">Distensi Abdomen</h1>
                    <div class="columns is-multiline">
                      <div class="column is-2" v-for="(data) in distensiAbdomen">
                          <VField>
                              <VControl raw subcontrol>
                                  <VCheckbox class="p-0" v-model="input[data.model]"
                                      :true-value="data.title" :label="data.title"
                                      color="primary" circle />
                              </VControl>
                          </VField>
                      </div>
                  </div>
                </div>
                <div class="column is-12">
                  <h1 style="font-weight: bold;margin-bottom: 0.5rem;">Cegukan</h1>
                    <div class="columns is-multiline">
                      <div class="column is-2" v-for="(data) in cegukan">
                          <VField>
                              <VControl raw subcontrol>
                                  <VCheckbox class="p-0" v-model="input[data.model]"
                                      :true-value="data.title" :label="data.title"
                                      color="primary" circle />
                              </VControl>
                          </VField>
                      </div>
                  </div>
                </div>
                <div class="column is-12">
                  <h1 style="font-weight: bold;margin-bottom: 0.5rem;">Eliminasi</h1>
                    <div class="columns is-multiline">
                      <div class="column is-2" v-for="(data) in eliminasi">
                          <VField>
                              <VControl raw subcontrol>
                                  <VCheckbox class="p-0" v-model="input[data.model]"
                                      :true-value="data.title" :label="data.title"
                                      color="primary" circle />
                              </VControl>
                          </VField>
                      </div>
                      <div class="column is-3">
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="jumlah urine"
                                    v-model="input.jmlUrine" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>cc</VButton>
                            </VControl>
                        </VField>
                    </div>
                  </div>
                </div>
              </Fieldset>
            </div>
          </Fieldset>
        </div>

        <div class="column is-12">
          <Fieldset class="p-fieldsets" legend="" :toggleable="true">
            <table border="1" style="border-collapse: collapse; width: 100%; text-align: center;">
              <thead>
                <tr>
                  <th  style="text-align : center">Keadaan Terbaik</th>
                  <th  style="text-align : center" colspan="10">Skala Identifikasi Gejala (Tingkat Gejala)</th>
                  <th  style="text-align : center"> Keadaan Terburuk</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Tidak Nyeri</td>
                  <td colspan="10">
                    <div class="columns is-multiline is-centered">
                      <template v-for="i in 10">
                        <div class="column is-1 p-5">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox
                                class="p-0"
                                v-model="input.tingkatNyeri"
                                :true-value="i"
                                :label="String(i)"
                                color="primary"
                                circle
                              />
                            </VControl>
                          </VField>
                        </div>
                      </template>
                    </div>
                  </td>
                  <td>Nyeri Tidak Tertahankan</td>
                </tr>
                <tr>
                  <td>Tidak Lelah</td>
                  <td colspan="10">
                    <div class="columns is-multiline is-centered">
                      <template v-for="i in 10">
                        <div class="column is-1 p-5">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox
                                class="p-0"
                                v-model="input.tingkatLelah"
                                :true-value="i"
                                :label="String(i)"
                                color="primary"
                                circle
                              />
                            </VControl>
                          </VField>
                        </div>
                      </template>
                    </div>
                  </td>
                  <td>Sangat Lelah</td>
                </tr>
                <tr>
                  <td>Tidak Mengantuk</td>
                  <td colspan="10">
                    <div class="columns is-multiline is-centered">
                      <template v-for="i in 10">
                        <div class="column is-1 p-5">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox
                                class="p-0"
                                v-model="input.tingkatMengantuk"
                                :true-value="i"
                                :label="String(i)"
                                color="primary"
                                circle
                              />
                            </VControl>
                          </VField>
                        </div>
                      </template>
                    </div>
                  </td>
                  <td>Sangat Mengantuk</td>
                </tr>
                <tr>
                  <td>Tidak Mual</td>
                  <td colspan="10">
                    <div class="columns is-multiline is-centered">
                      <template v-for="i in 10">
                        <div class="column is-1 p-5">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox
                                class="p-0"
                                v-model="input.tingkatMual"
                                :true-value="i"
                                :label="String(i)"
                                color="primary"
                                circle
                              />
                            </VControl>
                          </VField>
                        </div>
                      </template>
                    </div>
                  </td>
                  <td>Sangat Mual</td>
                </tr>
                <tr>
                  <td>Nafsu Makan Baik</td>
                  <td colspan="10">
                    <div class="columns is-multiline is-centered">
                      <template v-for="i in 10">
                        <div class="column is-1 p-5">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox
                                class="p-0"
                                v-model="input.tingkatNafsuMakan"
                                :true-value="i"
                                :label="String(i)"
                                color="primary"
                                circle
                              />
                            </VControl>
                          </VField>
                        </div>
                      </template>
                    </div>
                  </td>
                  <td>Tidak ada nafsu makan</td>
                </tr>
                <tr>
                  <td>Tidak Sesak Nafas</td>
                  <td colspan="10">
                    <div class="columns is-multiline is-centered">
                      <template v-for="i in 10">
                        <div class="column is-1 p-5">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox
                                class="p-0"
                                v-model="input.tingkatSesakNafas"
                                :true-value="i"
                                :label="String(i)"
                                color="primary"
                                circle
                              />
                            </VControl>
                          </VField>
                        </div>
                      </template>
                    </div>
                  </td>
                  <td>Sesak Nafas Sekali</td>
                </tr>
                <tr>
                  <td>Tidak Sedih</td>
                  <td colspan="10">
                    <div class="columns is-multiline is-centered">
                      <template v-for="i in 10">
                        <div class="column is-1 p-5">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox
                                class="p-0"
                                v-model="input.tingkatSedih"
                                :true-value="i"
                                :label="String(i)"
                                color="primary"
                                circle
                              />
                            </VControl>
                          </VField>
                        </div>
                      </template>
                    </div>
                  </td>
                  <td>Sangat Sedih Sekali</td>
                </tr>
                <tr>
                  <td>Tidak Cemas</td>
                  <td colspan="10">
                    <div class="columns is-multiline is-centered">
                      <template v-for="i in 10">
                        <div class="column is-1 p-5">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox
                                class="p-0"
                                v-model="input.tingkatCemas"
                                :true-value="i"
                                :label="String(i)"
                                color="primary"
                                circle
                              />
                            </VControl>
                          </VField>
                        </div>
                      </template>
                    </div>
                  </td>
                  <td>Sangat Cemas</td>
                </tr>
                <tr>
                  <td>Tidak Nyaman</td>
                  <td colspan="10">
                    <div class="columns is-multiline is-centered">
                      <template v-for="i in 10">
                        <div class="column is-1 p-5">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox
                                class="p-0"
                                v-model="input.tingkatNyaman"
                                :true-value="i"
                                :label="String(i)"
                                color="primary"
                                circle
                              />
                            </VControl>
                          </VField>
                        </div>
                      </template>
                    </div>
                  </td>
                  <td>Sangat Tidak Nyaman</td>
                </tr>
              </tbody>
            </table>

            <div class="columns is-multiline">
              <div class="column is-6 p-5">
                <span class="bold-text"> ASESMEN KEPERAWATAN (MASALAH) : </span>
                <VField>
                  <VTextarea
                    rows="3"
                    placeholder="ASESMEN KEPERAWATAN (MASALAH)..."
                    v-model="input.asesmenKeperawatan"
                  ></VTextarea>
                </VField>
              </div>
              <div class="column is-6 p-5">
                <span class="bold-text"> RENCANA kEPERAWATAN : </span>
                <VField>
                  <VTextarea
                    rows="3"
                    placeholder="RENCANA kEPERAWATAN..."
                    v-model="input.rencanaKeperawatan"
                  ></VTextarea>
                </VField>
              </div>
            </div>
          </Fieldset>
        </div>

        <div class="column is-12">
          <Fieldset class="p-fieldsets" legend="CATATAN PERAWATAN / PENGKAJIAN ULANG" :toggleable="true">
            <div class="column is-12">
              <h1 style="font-weight: bold;margin-bottom: 0.5rem;">Komunikasi dengan keluarga</h1>
              <h1 style="font-weight: bold;margin-bottom: 0.5rem;">identifikasi bagaimana memberitahu keluarga tentang masa kritis pasien</h1>
                <div class="columns is-multiline">
                  <div class="column is-4" v-for="(data) in komunikasiKeluarga">
                      <VField>
                          <VControl raw subcontrol>
                              <VCheckbox class="p-0" v-model="input[data.model]"
                                  :true-value="data.title" :label="data.title"
                                  color="primary" circle />
                          </VControl>
                      </VField>
                  </div>
              </div>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;margin-bottom: 0.5rem;">Kerjasama dengan tenaga medis profesional lain</h1>
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data) in kerjasamaMedisLain">
                      <VField>
                          <VControl raw subcontrol>
                              <VCheckbox class="p-0" v-model="input[data.model]"
                                  :true-value="data.title" :label="data.title"
                                  color="primary" circle />
                          </VControl>
                      </VField>
                  </div>
              </div>
            </div>

            <div class="column is-12">
              <VCard>
                  <h1 style="font-weight: bold;" class="text-center">SKALA IDENTIFIKASI GEJALA</h1>
                  <div class="columns is-multiline pl-3">
                    <div class="column" style="overflow: auto;">
                        <table class="tg">
                            <thead>
                                <tr>
                                    <th width="10%" class="col-stuck">Waktu</th>
                                    <th v-for="index in jumlahIndex1">
                                        <VField>
                                          <VDatePicker v-model="input['waktu1_' + index]" mode="date" style="width: 100%" trim-weeks
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
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="col-stuck bg-colatas">
                                        <span>O2</span>
                                    </td>
                                    <td v-for="index in jumlahIndex1">
                                        <VField>
                                            <VControl>
                                                <VInput v-model="input['O2_' + index]" type="text" />
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-stuck bg-colatas">
                                        <span>N2O</span>
                                    </td>
                                    <td v-for="index in jumlahIndex1">
                                        <VField>
                                            <VControl>
                                                <VInput v-model="input['N2O_' + index]" type="text" />
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-stuck bg-colatas">
                                        <span>Desflurane</span>
                                    </td>
                                    <td v-for="index in jumlahIndex1">
                                        <VField>
                                            <VControl>
                                                <VInput v-model="input['Desflurane_' + index]" type="text" />
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-stuck bg-colatas">
                                        <span>Isoflurane</span>
                                    </td>
                                    <td v-for="index in jumlahIndex1">
                                        <VField>
                                            <VControl>
                                                <VInput v-model="input['Isoflurane_' + index]" type="text" />
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-stuck bg-colatas">
                                        <span>Sevoflurane</span>
                                    </td>
                                    <td v-for="index in jumlahIndex1">
                                        <VField>
                                            <VControl>
                                                <VInput v-model="input['Sevoflurane_' + index]" type="text" />
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-stuck bg-colatas">
                                        <span>Infuse</span>
                                    </td>
                                    <td v-for="index in jumlahIndex1">
                                        <VField>
                                            <VControl>
                                                <VInput v-model="input['Infuse_' + index]" type="text" />
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-stuck bg-colatas">
                                        <span>SpoO2</span>
                                    </td>
                                    <td v-for="index in jumlahIndex1">
                                        <VField>
                                            <VControl>
                                                <VInput v-model="input['SpoO2_' + index]" type="text" />
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                  </div>
                </VCard>
            </div>
          </Fieldset>
        </div>
      <div class="column is-4" style="margin-left: auto">
        <VCard>
          <h1 style="font-weight: bold">Cirebon, Tanggal</h1>
          <VField>
            <VDatePicker
              v-model="input.tglPembuatan"
              mode="dateTime"
              style="width: 100%"
              trim-weeks
              :max-date="new Date()"
            >
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput
                      :value="inputValue"
                      placeholder="Tanggal"
                      v-on="inputEvents"
                    />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </VField>
          <div>
            <img v-if="input.petugasPerawat"
              :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.petugasPerawat.label ? input.petugasPerawat.label : input.petugasPerawat) + ', ' + (input.petugasPerawat.value ? input.petugasPerawat.value : input.petugasPerawat) + ', ' + (input.tglPembuatan ? input.tglPembuatan : input.tglPembuatan)">
            <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
            <!-- <TandaTangan
              :elemenID="'signature_1'"
              :width="'180'"
              :height="'180'"
            ></TandaTangan> -->
          </div>
          <div>
            <h1 class="p-0" style="font-weight: bold;">Yang Melakukan Kajian</h1>
            <h1 class="p-0" style="font-weight: bold">Petugas Perawat</h1>
            <VField>
              <VControl class="prime-auto">
                <AutoComplete
                  v-model="input.petugasPerawat"
                  :suggestions="d_Pegawai"
                  @complete="fetchPegawai($event)"
                  :optionLabel="'label'"
                  :dropdown="true"
                  :minLength="3"
                  :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'"
                  :field="'label'"
                  placeholder="Pegawai..."
                  class="mt-2"
                  @item-select="setTandaTangan($event)"
                />
              </VControl>
            </VField>
          </div>
        </VCard>
      </div>
    </VCard>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import {
  h,
  reactive,
  ref,
  computed,
  defineComponent,
  watch,
  onMounted,
  watchEffect,
} from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as EMR from '../page-emr-plugins/pengkajian-pasien-akhir-kehidupan'
import AutoComplete from 'primevue/autocomplete'
import Fieldset from 'primevue/fieldset'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import sleep from '/@src/utils/sleep'
import $ from "jquery";

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let planning = ref(EMR.planning())
let planning1 = ref(EMR.planning1())
let kesadaranFisik = ref(EMR.kesadaranFisik())
let perubahanMental = ref(EMR.perubahanMental())
let jenisPernafasan = ref(EMR.jenisPernafasan())
let sekret = ref(EMR.sekret())
let irama = ref(EMR.irama())
let ekstremitas = ref(EMR.ekstremitas())
let warnaKulit = ref(EMR.warnaKulit())
let penglihatan = ref(EMR.penglihatan())
let reflexBerkedip = ref(EMR.reflexBerkedip())
let pendengaran = ref(EMR.pendengaran())
let sensi = ref(EMR.sensi())
let tonusOtot = ref(EMR.tonusOtot())
let nafsuMakan = ref(EMR.nafsuMakan())
let mukosaMulut = ref(EMR.mukosaMulut())
let distensiAbdomen = ref(EMR.distensiAbdomen())
let cegukan = ref(EMR.cegukan())
let eliminasi = ref(EMR.eliminasi())
let komunikasiKeluarga = ref(EMR.komunikasiKeluarga())
let kerjasamaMedisLain = ref(EMR.kerjasamaMedisLain())
const jumlahIndex1 = ref(10)

let vitalSign = ref(EMR.vitalSign())
let vitalSignBayi = ref(EMR.vitalSignBayi())
let caraMasukRS = ref(EMR.caraMasukRS())
let tibaRS = ref(EMR.tibaRS())
let macamTrauma = ref(EMR.macamTrauma())
let faktorPencetus = ref(EMR.faktorPencetus())
let perjalananPenyakit = ref(EMR.perjalananPenyakit())
let riwayatPenyakitDahulu = ref(EMR.riwayatPenyakitDahulu())
let riwayatPenyakitDahuluAnak = ref(EMR.riwayatPenyakitDahuluAnak())
let terapiKomplementari = ref(EMR.terapiKomplementari())
let riwayatAlergiObatMakanan = ref(EMR.riwayatAlergiObatMakanan())
let keadaanHamil = ref(EMR.keadaanHamil())
let menyusui = ref(EMR.menyusui())
let listImageNyeri: any = ref(EMR.imgNyeri())
let listSkoringNyeri: any = ref(EMR.skoringNyeri())
let riwayatKelahiran = ref(EMR.riwayatKelahiran())
let riwayatImunisasi = ref(EMR.riwayatImunisasi())
let riwayatPsikologis = ref(EMR.riwayatPsikologis())
let kebiasaanAnak = ref(EMR.kebiasaanAnak())
let bentukUbunUbun = ref(EMR.bentukUbunUbun())
let parameter = ref(EMR.parameter())
let polaMakanAnak = ref(EMR.polaMakanAnak())
let makananYangDiberikan = ref(EMR.makananYangDiberikan())
let kebiasaanSebelumTidur = ref(EMR.kebiasaanSebelumTidur())
let aktivitasBermain = ref(EMR.aktivitasBermain())
let riwayatPenyakitKeluarga = ref(EMR.riwayatPenyakitKeluarga())
let pernapasan = ref(EMR.pernapasan())
let sirkulasiCairan = ref(EMR.sirkulasiCairan())
let pengecapan = ref(EMR.pengecapan())
let penciuman = ref(EMR.penciuman())
let bicara = ref(EMR.bicara())
let mulut = ref(EMR.mulut())
let defekasi = ref(EMR.defekasi())
let miksi = ref(EMR.miksi())
let Gastrointestinal = ref(EMR.Gastrointestinal())
let polaTidur = ref(EMR.polaTidur())
let taliPusat = ref(EMR.taliPusat())
let refleksMenelan = ref(EMR.refleksMenelan())
let refleksMenangis = ref(EMR.refleksMenangis())
let refleksMenghisap = ref(EMR.refleksMenghisap())
let refleksMenoleh = ref(EMR.refleksMenoleh())
let refleksGenggam = ref(EMR.refleksGenggam())
let refleksBabinski = ref(EMR.refleksBabinski())
let refleksMoro = ref(EMR.refleksMoro())
let tonicNeck = ref(EMR.tonicNeck())
let peningkatanTik = ref(EMR.peningkatanTik())
let integrasiKulit = ref(EMR.integrasiKulit())
let pakaiAlatBantu = ref(EMR.pakaiAlatBantu())
let eliminasiXhr = ref(EMR.eliminasiXhr())
let seksualitas = ref(EMR.seksualitas())
let aktivitasIstirahat = ref(EMR.aktivitasIstirahat())
let tulangMusculo = ref(EMR.tulangMusculo())
let bentukTubuhMusculo = ref(EMR.bentukTubuhMusculo())
let sendiMusculo = ref(EMR.sendiMusculo())
let psikososial = ref(EMR.psikososial())
let sosialSupport = ref(EMR.sosialSupport())
let nilaiBudayaPenyebabPenyakit = ref(EMR.nilaiBudayaPenyebabPenyakit())
let polaKomunikasi = ref(EMR.polaKomunikasi())
let polaMakan = ref(EMR.polaMakan())
let makananPokok = ref(EMR.makananPokok())
let pantangMakanan = ref(EMR.pantangMakanan())
let pengaruhKepercayaan = ref(EMR.pengaruhKepercayaan())
let kebutuhanBelajar = ref(EMR.kebutuhanBelajar())
let pemahamanPenyakit = ref(EMR.pemahamanPenyakit())
let pemahamanPengobatan = ref(EMR.pemahamanPengobatan())
let pemahamanPerawatan = ref(EMR.pemahamanPerawatan())
let pemahamanNutrisi = ref(EMR.pemahamanNutrisi())
let hambatanMenerimaNutrisi = ref(EMR.hambatanMenerimaNutrisi())
let indeksMasaTubuh = ref(EMR.indeksMasaTubuh())
let kehilanganBB = ref(EMR.kehilanganBB())
let asupanMakananPasienKurang = ref(EMR.asupanMakananPasienKurang())
let penyakitBeratPasien = ref(EMR.penyakitBeratPasien())
let keluhanNyeri = ref(EMR.keluhanNyeri())
let metodePenilaianNyeri = ref(EMR.metodePenilaianNyeri())
let nyeriBerpindah = ref(EMR.nyeriBerpindah())
let lamaNyeri = ref(EMR.lamaNyeri())
let rasaNyeri = ref(EMR.rasaNyeri())
let seberapaSeringNyeri = ref(EMR.seberapaSeringNyeri())
let berapaLamaNyeri = ref(EMR.berapaLamaNyeri())
let penyebabNyeriBerkurangBertambah = ref(EMR.penyebabNyeriBerkurangBertambah())
let diagnosisKeperawatan = ref(EMR.diagnosisKeperawatan())
let pasienKeluargaTahuRencanaPulang = ref(EMR.pasienKeluargaTahuRencanaPulang())
let apakahPasienTinggalSendiri = ref(EMR.apakahPasienTinggalSendiri())
let kawatirKetikaKembaliKerumah = ref(EMR.kawatirKetikaKembaliKerumah())
let apakhDirumahAdaYangMerawat = ref(EMR.apakhDirumahAdaYangMerawat())
let jenisTempatTinggalPasien = ref(EMR.jenisTempatTinggalPasien())
let tempatTinggalAdaTangga = ref(EMR.tempatTinggalAdaTangga())
let pengasuh = ref(EMR.pengasuh())
let tanggungJawabMemelihara = ref(EMR.tanggungJawabMemelihara())
let perawatanLajutanDiruamah = ref(EMR.perawatanLajutanDiruamah())
let enamJenisObatSaatPulang = ref(EMR.enamJenisObatSaatPulang())
let permohonanPendampingan = ref(EMR.permohonanPendampingan())
let transportasiSaatPulang = ref(EMR.transportasiSaatPulang())

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

const MARKINGSITE: any = ref('')
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading: any = ref(false)
const isLoadingVitalSign: any = ref(false)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const d_Ruangan: any = ref([])
const d_Agama: any = ref([])
const isAktive = ref()
const d_Suku: any = ref([])
const d_Kelas: any = ref([])
const d_Diagnosa: any = ref([])
const isLoadingTT: any = ref(false)
const totalSkor: any = ref(0)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
})
const COLLECTION: any = ref('PengkajianPasienAkhirKehidupan') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
  MARKINGSITE.value = '/images/emr/asesmen_medis.png'
}
const isDewasa = props.pasien.umur.split('thn')[0] >= 18 ? true : false
const loadRiwayat = () => {
  // if (NOREC_EMRPASIEN.value == '') return
  useApi()
    .get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    )
    .then((response: any) => {
      if (response.length) {
        if (response[0].tandaTanganPerawat) {
          H.tandaTangan().set('signature_1', response[0].tandaTanganPerawat)
        }
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        if (response[0].tandaBodyDraw) {
          H.tandaTangan().set("markingsite", response[0].tandaBodyDraw, 600, 500)
        }
      }
    })
}

const fetchPegawai = async (filter: any) => {
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    )
    .then((response) => {
      d_Pegawai.value = response
    })
}
const setTandaTangan = async (e: any) => {
  const response = await useApi().get(`/emr/tanda-tangan/${e.value.value}`)
  if (response != null) {
    H.tandaTangan().set('signature_1', response.ttd)
    input.value.tandaTanganPerawat = response.ttd
  } else {
    H.tandaTangan().set('signature_1', '')
  }
}

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  )
  d_Ruangan.value = response
}

const fetchAgama = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/agama_m?select=id,agama&param_search=agama&query=${filter.query}&limit=10`
  )
  d_Agama.value = response
}

const fetchSuku = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/suku_m?select=id,suku&param_search=suku&query=${filter.query}&limit=10`
  )
  d_Suku.value = response
}

const fetchDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/diagnosa_m?select=id,namadiagnosa&param_search=namadiagnosa&query=${filter.query}&limit=10`
  )
  d_Diagnosa.value = response
}

const fetchKelas = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/kelas_m?select=id,namakelas&param_search=namakelas&query=${filter.query}&limit=10`
  )
  d_Kelas.value = response
}

const fetchDokter = async (filter: any) => {
  const response = await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  )
  d_Dokter.value = response
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.tandaTanganPerawat = H.tandaTangan().get('signature_1')
  object.tandaTanganPerawat = H.tandaTangan().get("signature_1")
  object.tandaBodyDraw = H.tandaTangan().get("markingsite")
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: props.FORM_URL,
    name_form: props.FORM_NAME,
    jenis_emr: 'asesmen_medis',
    data: object,
  }
  isLoading.value = true
  useApi()
    .post(`/emr/simpan-emr`, json)
    .then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}

const skor = (e: any, i: any) => {

let listSkor = listSkoringNyeri.value.detail

listSkor.forEach((element: any) => {
  if (element.descNilai == e.descNilai) {
    input.value.skoringNyeri = e.descNilai
  }
});
isAktive.value = i

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
  let sigCanvas: any = document.getElementById("markingsite");
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

    $("#markingsite").mousedown(function (mouseEvent: any) {

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

let kesadaran = ref([
  {
    no: 1,
    parameter: 'E',
    nilai: [
      {
        model: 'kesadaranE',
        poin: '1',
      },
      {
        model: 'kesadaranE',
        poin: '2',
      },
      {
        model: 'kesadaranE',
        poin: '3',
      },
      {
        model: 'kesadaranE',
        poin: '4',
      },
    ],
  },
  {
    no: 2,
    parameter: 'M',
    nilai: [
      {
        model: 'kesadaranM',
        poin: '1',
      },
      {
        model: 'kesadaranM',
        poin: '2',
      },
      {
        model: 'kesadaranM',
        poin: '3',
      },
      {
        model: 'kesadaranM',
        poin: '4',
      },
      {
        model: 'kesadaranM',
        poin: '5',
      },
      {
        model: 'kesadaranM',
        poin: '6',
      },
    ],
  },
  {
    no: 3,
    parameter: 'V',
    nilai: [
      {
        model: 'kesadaranV',
        poin: '5',
      },
      {
        model: 'kesadaranV',
        poin: '4',
      },
      {
        model: 'kesadaranV',
        poin: '3',
      },
      {
        model: 'kesadaranV',
        poin: '2',
      },
      {
        model: 'kesadaranV',
        poin: '1',
      },
    ],
  },
])

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

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.tanggalWaktuRegistrasi = props.registrasi.tglregistrasi
  input.value.tanggalWaktuKunjuangan = new Date()
  input.value.tglPembuatan = new Date()
  input.value.umur = props.pasien.umur
  input.value.namaruangan = props.registrasi.namaruangan
  input.value.agama = props.pasien.agama
  input.value.suku = props.pasien.suku
  input.value.namakelas = props.registrasi.namakelas
  input.value.dokter = props.registrasi.dokter
  input.value.petugasPerawat = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  // input.value.namadiagnosa = props.diagnosis.namadiagnosa

  isLoadingVitalSign.value = true
  await useApi()
    .get(
      'emr/auto-fill?norec_pd=' +
        props.registrasi.norec_pd +
        '&collection=VitalSign' +
        '&field=beratBadan,tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi'
    )
    .then((response) => {
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
      isLoadingVitalSign.value = false
    })
}
watchEffect(() => {
  totalSkor.value = 0
  parameter.value.forEach((element, index) => {
    const skor = 'skor' + (index + 1)
    totalSkor.value += input.value[skor] ? parseFloat(input.value[skor]) : 0
  })
})
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
.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 150%;
    // width: 100%;
}

.tg-khusus{
    border-collapse: collapse;
    border-spacing: 0;
    width: 50%;
    // width: 100%;
}

.tg td {
    border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 1px;

    // font-size: 14px;
    overflow: hidden;
    padding: 7px;
    word-break: normal;
}

.tg-khusus td {
    border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 1px;

    // font-size: 14px;
    overflow: hidden;
    padding: 7px;
    word-break: normal;
}

.tg tr {
    height: 20px;
}

.tg-khusus tr {
    height: 20px;
}

.tg th {
    border-color: var(--fade-grey-dark-3);
    border-style: solid;
    border-width: 1px;
    vertical-align: middle;
    // font-size: 14px;
    text-align: center !important;
    font-weight: bold;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg-khusus th {
    border-color: var(--fade-grey-dark-3);
    border-style: solid;
    border-width: 1px;
    vertical-align: middle;
    // font-size: 14px;
    text-align: center !important;
    font-weight: bold;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.col-stuck {
    width: 150px;
    position: sticky;
    left: 0;
    z-index: 2;
    background-color: aliceblue;
    vertical-align: inherit;
}

.custom-fieldset {
    border: 1px solid;
    border-radius: 5px;
    border-color: var(--fade-grey-dark-2);
}

.custom-legenda {
    margin-left: 15px;
    font-weight: 500;
}

table.triase {
    border-collapse: collapse;
    width: 100%;
}

table.triase,
.triase th,
.triase td {
    border: 0.5px solid grey;
}


.triase th,
.triase td {
    padding: 8px;
    vertical-align: middle !important;
}

table-container {
  margin: 20px;
}

.medical-table {
  width: 100%;
  border-collapse: collapse;
}

.medical-table th, .medical-table td {
  border: 1px solid black;
  text-align: center;
  padding: 8px;
}

.medical-table th {
  background-color: #f2f2f2;
}
</style>

<!-- <style scoped lang="scss">
.tbl-kmiccu {
  width: 100%;
  border-collapse: collapse;
  font-size: 12pt;
}

.tbl-kmiccu th {
  text-align: center;
  vertical-align: middle;
}

.tbl-kmiccu tr,
.tbl-kmiccu th,
.tbl-kmiccu td {
  border: 1px solid black;
  padding: 5px;
}
</style> -->
