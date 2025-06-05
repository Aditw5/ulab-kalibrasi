<template>
  <ConfirmDialog/>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div v-if="isStuck" :class="[isStuck && 'is-stuck']" style="margin-top:50px; padding: 0px 0px !important;" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isSelesai="isSelesai" :isWA="isWA" :isLoading="isLoading" @simpan="simpan" :registrasi="props.registrasi"
              @kembaliKeun="kembaliKeun" @tte="modalPengajuanTTE"></ButtonEmr>
          </div>
        </div>
      </div>
      <div v-if="!isStuck" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isSelesai="isSelesai" :isWA="isWA" :isLoading="isLoading" @simpan="simpan" :registrasi="props.registrasi"
              @kembaliKeun="kembaliKeun" @tte="modalPengajuanTTE"></ButtonEmr>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="column">
    <VCard>
      <!-- <div class="column p-0" style="text-align:right">
        <VIconButton type="button" raised circle icon="fas fa-copy" v-tooltip-prime.bottom="'Tetapkan Data'" @click="show"
          color="warning"></VIconButton>
      </div> -->
      <div class="column is-12">
        <Fieldset legend="1. Pasien Detail" :toggleable="true">
          <div class="column is-12">
            <VField :label="props.registrasi.objectdepartemenfk == 9 || props.registrasi.objectdepartemenfk == 18 ? 'Anamnesa' : 'Riwayat Penyakit'">
              <VControl>
                <VTextarea v-model="input.riwayatPenyakit" rows="1" placeholder="Riwayat Penyakit ...">
                </VTextarea>
              </VControl>
            </VField>
          </div>
          <div class="columns is-multiline pt-3 pr-3 pl-3">
            <div class="column is-3">
              <VField label="Tekanan Darah"></VField>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="Tekanan Darah" v-model="input.tekananDarah" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>mmHG</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField label="Nadi"></VField>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="Nadi" v-model="input.nadi" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>x/mnt</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField label="Pernapasan"></VField>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="Pernapasan" v-model="input.pernapasan" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>x/mnt</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField label="Suhu"></VField>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="Suhu" v-model="input.suhu" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>°C </VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField label="SpO2"></VField>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="SPO2" v-model="input.SPO2" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>%</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField label="Tinggi Badan"></VField>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="Tinggi Badan" v-model="input.tinggiBadan" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>Cm</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField label="Berat Badan"></VField>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="Berat Badan" v-model="input.beratBadan" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>Kg</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField label="IMT"></VField>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="IMT" v-model="input.IMT" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static></VButton>
                </VControl>
              </VField>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset legend="2. Pemeriksa Pasien" :toggleable="true">
          <div class="columns is-multiline pl-4 pr-4">
            <div class="column is-5 p-2">
              <VField label="Pemeriksaan Fisik Lainnya">
                <VControl>
                  <VTextarea v-model="input.pemeriksaanFisikLain" rows="2" placeholder="Pemeriksaan Fisik Lainnya ...">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-6 p-2">
              <VField label="Pemeriksa Penunjang">
                <VControl>
                  <VTextarea v-model="input.pemeriksaPenunjang" rows="2" placeholder="Pemeriksa Penunjang ...">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column" style="align-self: center;">
              <VIconButton type="button" raised circle icon="fas fa-copy" v-tooltip-prime.bottom="'Tetapkan Data'"
                :disabled="sourceDataLab.length == 0 ? true : false" @click="show" color="warning"></VIconButton>
            </div>
          </div>
          <div class="columns is-multiline pl-4 pr-4 pt-2" v-if="props.registrasi.objectdepartemenfk == 16">
            <div class="column is-6 p-2">
              <VField label="Terapi Pengobatan">
                <VControl>
                  <VTextarea v-model="input.terapi" rows="2" placeholder="Terapi Pengobatan ...">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-6 p-2" v-if="props.registrasi.objectdepartemenfk == 16">
              <VField label="Hasil Konsultasi">
                <VControl>
                  <VTextarea v-model="input.hasilKonsultasi" rows="2" placeholder="Hasil Konsultasi ...">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
          </div>

        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset legend="3. Diagnosa dan Tindakan" :toggleable="true">
          <h1 class="bold pb-4"> </h1>
          <div class="column is-12">
            <h1 class="mb-3 bold">Diagnosa</h1>
            <div style="overflow-y:auto;" class="mt-1">
              <table class="table-po" style="width:100% !important">
                <thead>
                  <tr class="tr-po">
                    <th class="th-po" width="23%" style="vertical-align: inherit;text-align:center">
                      Jenis
                    </th>
                    <th class="th-po" width="25%" style="vertical-align: inherit;text-align:center">
                      Diagnosa
                      Dokter
                    </th>
                    <th class="th-po" style="vertical-align: inherit;text-align:center"> ICD
                      10
                    </th>
                    <th class="th-po" style="vertical-align: inherit;text-align:center" width="10%">
                      #
                    </th>
                  </tr>
                </thead>
                <tbody v-for="(items, index3) in input.diagnosaDokter" :key="index3">
                  <tr class="tr-po">
                    <td class="td-po">
                      <div class="column p-1">
                        <VField>
                          <VControl class="prime-auto">
                            <AutoComplete v-model="items.jenisDiagnosa" :suggestions="d_JenisDiagnosa"
                              @complete="fetchJenisDiagnosa($event)" :optionLabel="'label'" :dropdown="true"
                              :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                              placeholder=" Jenis ..." class="mt-2" />
                          </VControl>
                        </VField>
                      </div>
                    </td>
                    <td class="td-po">
                      <div class="column pt-3 pb-0">
                        <VField>
                          <VControl icon="feather:bookmark">
                            <VInput type="text" v-model="items.norecDiagnosa" disabled style="display:none"
                              placeholder="Diagnosa Dokter" />
                            <VInput type="text" v-model="items.ketDiagnosaDok" placeholder="Diagnosa Dokter" />

                          </VControl>
                        </VField>
                      </div>
                    </td>
                    <td class="td-po">
                      <div class="column p-1">
                        <VField>
                          <VControl class="prime-auto">
                            <AutoComplete v-model="items.diagnosaIcd10" :suggestions="d_Diagnosa"
                              @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder=" ICD 10 ..."
                              class="mt-2" />
                          </VControl>
                        </VField>
                      </div>
                    </td>
                    <td class="td-po" style="vertical-align: inherit;">
                      <div class="column is-12 pl-0 pr-0">
                        <VButtons style="justify-content:space-between">
                          <VIconButton type="button" raised circle icon="feather:plus" v-tooltip-prime.bottom="'Tambah'"
                            @click="addDiagnosaDok(items)" outlined color="info">
                          </VIconButton>
                          <VIconButton type="button" raised circle v-tooltip-prime.bottom="'Hapus'" outlined
                            :loading="items.isLoadBtnDiagnosaDokter" icon="feather:trash"
                            @click="removeDiagnosaDok(items, index3)" color="danger">
                          </VIconButton>
                        </VButtons>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="column is-12">
            <h1 class="mb-3 bold">Tindakan</h1>
            <table class="table-po">
              <thead>
                <tr class="tr-po">
                  <!-- <th class="th-po" width="2%" style="vertical-align: inherit;">NO</th> -->
                  <th class="th-po" width="25%" style="vertical-align:inherit;text-align: center;">Tindakan Dokter</th>
                  <th class="th-po" style="vertical-align:inherit;text-align: center;">Diagnosa ICD 9</th>
                  <th class="th-po" style="vertical-align:inherit;text-align: center;" width="10%">#</th>
                  <!-- <th class="th-po" rowspan="2" style="vertical-align:inherit;text-align: center;" width="12%">#
                  </th> -->
                </tr>
              </thead>
              <tbody v-for="(items, index) in input.detailTindakan" :key="index">
                <tr class="tr-po">
                  <!-- <td class="td-po" style="vertical-align:inherit">{{ index + 1 }}</td> -->
                  <td class="td-po">
                    <div class="column pt-3 pb-0">
                      <VField>
                        <VControl icon="feather:bookmark">
                          <VInput type="text" v-model="items.norecDiagnosa9" disabled style="display:none"
                            placeholder="Diagnosa Dokter" />
                          <VInput type="text" v-model="items.ketTindakanDokter" placeholder="Tindakan Dokter" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td class="td-po">
                    <div class="column p-1">
                      <VField>
                        <VControl class="prime-auto">
                          <AutoComplete v-model="items.diagnosaIcd9" :suggestions="d_DiagnosaTindakan"
                            @complete="fetchDiagnosa9($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            placeholder="Ketik untuk mencari ..." class="mt-2" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td class="td-po" style="vertical-align: inherit;">
                    <div class="column is-12 pl-0 pr-0">
                      <VButtons style="justify-content:space-between">
                        <VIconButton type="button" raised circle icon="feather:plus" v-tooltip-prime.bottom="'Tambah'"
                          @click="addDiagnosaICD9(items)" outlined color="info">
                        </VIconButton>
                        <VIconButton type="button" raised circle :loading="items.isLoadBtnDiagnosaDokter9"
                          v-tooltip-prime.bottom="'Hapus'" outlined icon="feather:trash"
                          @click="removeDiagnosaICD9(items, index)" color="danger">
                        </VIconButton>
                      </VButtons>
                    </div>
                  </td>
                  <!-- <td class="th-po" style="vertical-align: inherit;"> -->
                  <!-- <div class="column p-0">
                      <div class="columns is-multiline">
                        <div class="column is-6">
                          <VIconButton type="button" raised circle icon="feather:plus" @click="addTindakan()" outlined
                            color="info" v-tooltip.bubble="'Tambah Petugas '">
                          </VIconButton>
                        </div>
                        <div class="column is-6 ml-3-min">
                          <VIconButton v-if="index > 0" type="button" raised circle outlined icon="feather:trash"
                            @click="removeTindakan(index)" color="danger">
                          </VIconButton>
                        </div>
                      </div>
                    </div> -->
                  <!-- </td> -->
                </tr>
              </tbody>
            </table>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset legend="4. Lain Lain" :toggleable="true">
          <div class="columns is-multiline pl-4 pr-4 pt-4">
            <div class="column is-6 p-2">
              <VField label="Alergi (Reaksi Obat)">
                <VControl>
                  <VTextarea v-model="input.alergiReaksiObat" rows="2" placeholder="Alergi Reaksi Obat ...">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-6 p-2">
              <VField label="Hasil Penunjang (Belum Selesai)" class="label-unset">
                <VControl>
                  <VTextarea v-model="input.hasilPenunjang" rows="2" placeholder="Hasil Penunjang ...">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
          </div>

          <div class="columns is-multiline pl-4 pr-4 pt-4">
            <div class="column is-6 p-2">
              <VField label="Diet">
                <VControl>
                  <VTextarea v-model="input.diet" rows="2" placeholder="Diet ...">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-6 p-2">
              <VField label="Intruksi">
                <VControl>
                  <VTextarea v-model="input.hasilIntruksi" rows="2" placeholder="Intruksi ...">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
          </div>
          <div class="columns is-multiline pl-4 pr-4 pt-4" v-if="props.registrasi.objectdepartemenfk == 16">
            <div class="column is-4 p-2">
              <VField label="Skala Nyeri">
                <VControl class="prime-auto">
                  <Dropdown v-model="input.skalaNyeri" showClear :options="listSkalaNyeri" optionLabel="label"
                    class="w-full" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4 p-2" v-if="props.registrasi.objectdepartemenfk == 16">
              <VField label="Kesadaran">
                <VControl>
                  <VInput type="text" v-model="input.kesadaran" placeholder="Kesadaran.." />
                </VControl>
              </VField>
            </div>
            <div class="column is-4 p-2" v-if="props.registrasi.objectdepartemenfk == 16">
              <VField label="GCS">
                <VControl>
                  <VInput type="text" v-model="input.gcs" placeholder="gcs..." />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-12" v-if="props.registrasi.objectdepartemenfk == 16">
            <span>Kondisi Keluar</span>
            <div class="columns is-multiline p-3">
              <div class="column is-2" v-for="(data) in kondisiKeluar">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label" class="p-0"
                      color="primary" square />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline p-3" v-if="input.kondisiKeluar == 'Lainnya'">
              <div class="column">
                <VField>
                  <VControl>
                    <VInput type="text" v-model="input.kondisiKeluarLainya" placeholder="Masukan Lainnya..." />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset legend="5. Pengobatan Lanjut" :toggleable="true">
          <div class="column is-12">
            <div class="columns is-multiline pl-4 pr-4 pt-3">
              <div class="column is-4 p-2">
                <VField label="Poliklinik">
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input.poli" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ketik untuk mencari ..."
                      class="mt-2" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4 p-2">
                <VField label="Tanggal Kontrol" class="mb-5">
                  <VDatePicker v-model="input.tglperjanjian" mode="dateTime" class="mt-4" style="width: 100%" trim-weeks>
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
              <div class="column is-4">
                <VField label="Dokter" class="is-autocomplete-select" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="input.dokterDPJP" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Perawat" @select="saveStatusDPJPincbgs" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12" v-if="props.registrasi.objectdepartemenfk == 16">
            <span>Prognosis Ad Funct</span>
            <div class="columns is-multiline p-3">
              <div class="column is-2" v-for="(data) in prognosisAdFunt">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label" class="p-0"
                      color="primary" square />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12" v-if="props.registrasi.objectdepartemenfk == 16">
            <span>Prognosis Ad Vitam</span>
            <div class="columns is-multiline p-3">
              <div class="column is-2" v-for="(data) in prognosisAdVitam">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label" class="p-0"
                      color="primary" square />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset legend="6. Obat" :toggleable="true">

          <div style="overflow-y:auto;" class="mt-1">
            <table width="100%">
              <thead>
                <tr class="tr-po">
                  <!-- <th class="th-po" width="10%" style="vertical-align: inherit;">Jenis Obat</th> -->
                  <th class="th-po" width="25%" style="vertical-align:inherit;text-align: center;">Nama Obat</th>
                  <th class="th-po" width="9%" style="vertical-align:inherit;text-align: center;">Aturan Pakai</th>
                  <th class="th-po" width="9%" style="vertical-align:inherit;text-align: center;">Jumlah</th>
                  <th class="th-po" width="11%" style="vertical-align:inherit;text-align: center;">Dosis</th>
                  <th class="th-po" width="15%" style="vertical-align:inherit;text-align: center;">Waktu Pakai</th>
                  <th class="th-po" width="8%" style="vertical-align:inherit;text-align: center;">#</th>
                </tr>
              </thead>
              <tbody v-for="(items, index) in input.detailObatResep" :key="index">
                <tr class="tr-po">
                  <!-- <td class="td-po">
                    <div class="column pt-3 pb-0">
                      <VField>
                        <VControl icon="feather:bookmark">
                          <VInput type="text" v-model="items.jenisObat" placeholder="Jenis Obat..." />
                        </VControl>
                      </VField>
                    </div>
                  </td> -->
                  <td class="td-po">
                    <div class="column pt-3 pb-0">
                      <VField>
                        <VControl>
                          <AutoComplete v-model="items.obat" :suggestions="d_produk" @complete="fetchProduk($event)"
                            :optionLabel="'label'" :dropdown="true" :minLength="3" class="is-input" :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td class="td-po">
                    <div class="column pt-3 pb-0">
                      <VField>
                        <VControl>
                          <VInput type="text" v-model="items.aturanPakai" placeholder="Aturan Pakai..." />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td class="td-po">
                    <div class="column pt-3 pb-0">
                      <VField>
                        <VControl>
                          <VInput type="text" v-model="items.jumlah" placeholder="Jumlah Pakai..." />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td class="td-po">
                    <div class="column pt-3 pb-0">
                      <VField>
                        <VControl>
                          <VInput type="text" v-model="items.dosis" placeholder="Dosis..." />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td class="td-po">
                    <div class="column pt-3 pb-0">
                      <VField>
                        <VControl>
                          <VInput type="text" v-model="items.waktuPakai" placeholder="Waktu Pakai..." />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td class="td-po" style="vertical-align: inherit;">
                    <div class="column is-12 pl-0 pr-0">
                      <VButtons style="justify-content: space-around;">
                        <VIconButton type="button" raised circle icon="feather:plus" v-tooltip-prime.bottom="'Tambah'"
                          @click="addNewObat(items)" outlined color="info">
                        </VIconButton>
                        <VIconButton type="button" raised circle v-tooltip-prime.bottom="'Hapus'"
                          outlined icon="feather:trash" @click="removeObat(items)" color="danger">
                        </VIconButton>
                      </VButtons>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12" v-if="input.totalSkor > 0">
        <Fieldset class="p-fieldsets" legend="7. Malnutrition Screening Tooll (MST)" :toggleable="true">
          <div class="column is-12">
            <div class="columns is-multiline px-5" style="border-bottom: 1px solid;">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-10">
                    <h1 class="emr">Pertanyaan</h1>
                  </div>
                  <div class="column is-2">
                    <h1 class="emr">Skor</h1>
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
                              :label="dataDetail.text" class="p-0" color="primary" square disabled/>
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
                                :label="dataDetailChildren.label" class="p-0" color="primary" square disabled/>
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
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <VCard class="border-card pink">
          <h1 class="bold pb-4" v-if="input.totalSkor > 0"> 8. Dokter Pelayanan</h1>
          <h1 class="bold pb-4" v-if="input.totalSkor <= 0 || input.totalSkor === undefined"> 7. Dokter Pelayanan</h1>
          <div class="column is-12">
            <VField>
              <VControl>
                <VTextarea v-model="input.pelaksana" rows="2" placeholder="Dokter Pelaksana">
                </VTextarea>
              </VControl>
            </VField>
          </div>
        </VCard>
      </div>

      <Dialog v-model:visible="showData" maximizable modal header="Hasil Laboratorium" :style="{ width: '80rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <div class="columns is-multiline">
          <div class="column is-6">
            <VCard class="bt-color">
              <h3 style="font-weight:800">Pemeriksaan Penunjang</h3>
              <div class="column is-12">
                <VField>
                  <VControl>
                    <VTextarea v-model="item.sourcePemeriksaanPenunjang" rows="20">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
            </VCard>
          </div>
          <div class="column is-6">
            <VCard class="pembungkus">
              <DataTable dataKey="no" :value="sourceDataLab" v-model:selection="selectedResep" class="p-datatable-sm"
                :paginator="true" :rows="10" scrollable
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                <Column field="no" header="No" :sortable="true" style="min-width: 50px"></Column>
                <Column field="nama_pemeriksaan" header="Layanan" frozen :sortable="true" style="min-width: 200px">
                </Column>
                <Column field="hasil" header="Hasil" :sortable="true" style="min-width: 100px"></Column>
                <Column field="normal" header="Nilai Normal" :sortable="true" style="min-width: 100px"></Column>
                <!-- <Column field="jumlahobat" header="Jumlah Obat" :sortable="true" style="min-width: 200px"></Column> -->
                <Column field="tgl_hasil" header="tanggal" :sortable="true" style="min-width: 200px"></Column>
              </DataTable>

              <div class="column is-12">
                <VButton color="primary" raised @click="tambahHasilLab(selectedResep)" outlined :loading="isLoadTmb"><i
                    class="fas fa-plus mr-3 p-0" aria-hidden="true"></i>Tambah</VButton>
              </div>
            </VCard>
          </div>
        </div>
        <template #footer>
          <VButton color="primary" raised @click="simpanHasilPenunjang(item.sourcePemeriksaanPenunjang)"><i
              class="fas fa-save mr-3" aria-hidden="true"></i> Simpan</VButton>
        </template>
        <!-- <VTabs slider type="rounded" selected="penunjang" :tabs="[
          { label: 'Pemeriksaan Penunjang', value: 'penunjang' },
          { label: 'Obat', value: 'obat' },
        ]">
          <template #tab="{ activeValue }">
            <p v-if="activeValue === 'penunjang'">
            <div class="columns is-multiline">
              <div class="column is-6">
                <VCard class="bt-color">
                  <h3 style="font-weight:800">Pemeriksaan Penunjang</h3>
                  <div class="column is-12">
                    <VField>
                      <VControl>
                        <VTextarea v-model="item.sourcePemeriksaanPenunjang" rows="20" placeholder="Terapi Pengobatan ...">
                        </VTextarea>
                      </VControl>
                    </VField>
                  </div>
                </VCard>
              </div>
              <div class="column is-6">
                <VCard class="pembungkus">
                  <DataTable dataKey="no" :value="sourceDataLab" v-model:selection="selectedResep" class="p-datatable-sm"
                    :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                    <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                    <Column field="no" header="No" :sortable="true" style="min-width: 100px"></Column>
                    <Column field="nama_pemeriksaan" header="Layanan" frozen :sortable="true" style="min-width: 200px">
                    </Column>
                    <Column field="normal" header="Hasil" :sortable="true" style="min-width: 100px"></Column>
                    <Column field="tanggal" header="tanggal" :sortable="true" style="min-width: 200px"></Column>
                  </DataTable>

                  <div class="column is-4" style="display: flex;justify-content: end;">
                    <VButton color="primary" raised @click="tambahHasilLab(selectedResep)"><i
                        class="fas fa-plus mr-3 p-0" aria-hidden="true"></i>Tambah</VButton>
                  </div>
                </VCard>
              </div>
            </div>
            </p>
            <p v-else-if="activeValue === 'obat'">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quid iudicant sensus?
              Primum quid tu dicis breve? Etiam beatissimum? Ne discipulum abducam, times. Quae
              diligentissime contra Aristonem dicuntur a Chryippo. Duo Reges: constructio
              interrete.
            </p>
          </template>
        </VTabs> -->
        <!-- <div class="columns is-multiline">
        <div class="column is-6">
          <h3 class="dark-inverted">Additional Instructions</h3>
          <div class="column is-12">
            <VField>
              <VControl>
                <VTextarea v-model="input.pemeriksaPenunjang" rows="20" placeholder="Terapi Pengobatan ...">
                </VTextarea>
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-6">
          <PanelMenu :model="items" />
        </div>
      </div> -->
      </Dialog>

      <!-- MODAL TTE BSRE -->
      <Dialog v-model:visible="modalSimpanDokumenUntukTTE"  modal header="Dokter Penanda Tangan" :style="{ width: '30vw' }">
        <div class="columns is-multiline p-1">
          <div class="column is-12">
            <VField label="Dokter Penanda Tangan" class="is-rounded-select_Z is-autocomplete-select" v-slot="{ id }">
              <VControl icon="fa:user-md" fullwidth class="prime-auto">
                <AutoComplete v-model="item.dokterTTE"
                  :suggestions="d_Dokter"
                  @complete="fetchDokter($event)"
                  :optionLabel="'label'"
                  :dropdown="true"
                  :minLength="3"
                  :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'"
                  :field="'label'"
                  placeholder="ketik untuk mencari..."
                />
              </VControl>
            </VField>
          </div>
        </div>
        <template #footer>
          <VButton icon="lnir lnir-arrow-left rem-100" rounded outlined color="secondary" @click="modalSimpanDokumenUntukTTE = false" class="mr-2">
            Batal
          </VButton>
          <VButton type="button" rounded outlined color="primary" :loading="isLoading" raised icon="feather:save" @click="savePengajuanTTE()">
            Simpan
          </VButton>
        </template>
      </Dialog>
      <!-- END MODAL TTE BSRE -->

    </VCard>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import Dropdown from 'primevue/dropdown';
import * as H from '/@src/utils/appHelper'
import PanelMenu from 'primevue/panelmenu';
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useConfirm } from 'primevue/useconfirm'
import ConfirmDialog from 'primevue/confirmdialog'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/malnutrition-screening-tools'
import Fieldset from 'primevue/fieldset';


let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let skriningGizi = ref(EMR.skriningGizi())
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
let ID_PASIEN = useRoute().query.nocmfk ? useRoute().query.nocmfk : props.registrasi.nocmfk  as string
console.log(props.registrasi)

let kondisiKeluar = [
  {
    label: "Sembuh",
    model: "kondisiKeluar",
  },
  {
    label: "Pindah RS",
    model: "kondisiKeluar",
  },
  {
    label: "PAPS",
    model: "kondisiKeluar",
  },
  {
    label: "Meninggal",
    model: "kondisiKeluar",
  },
  {
    label: "Lainnya",
    model: "kondisiKeluar",
  },
]

let prognosisAdFunt = [
  {
    label: "Ad Bonam",
    model: "prognosisAdFunct_1",
  },
  {
    label: "Ad Malam",
    model: "prognosisAdFunct_2",
  },
  {
    label: "Dubia Ad Bonam",
    model: "prognosisAdFunct_3",
  },
  {
    label: "Dubia Ad Malam",
    model: "prognosisAdFunct_4",
  },
]

let prognosisAdVitam = [
  {
    label: "Ad Bonam",
    model: "prognosisAdVitam_1",
  },
  {
    label: "Ad Malam",
    model: "prognosisAdVitam_2",
  },
  {
    label: "Dubia Ad Bonam",
    model: "prognosisAdVitam_3",
  },
  {
    label: "Dubia Ad Malam",
    model: "prognosisAdVitam_4",
  },
]

let listSkalaNyeri = [
  {
    label: '0 - 1 Tidak Nyeri',
  },
  {
    label: '2 - 3 Sedikit Nyeri',
  },
  {
    label: '4 - 5 Cukup Nyeri',
  },
  {
    label: '6 - 7 Lumayan Nyeri',
  },
  {
    label: '8 - 9 Sangat Nyeri',
  },
  {
    label: '10 Amat Sanagat Nyeri',
  },
]

const { y } = useWindowScroll()
const selectedResep = ref();
const isStuck = computed(() => { return y.value >= 20 })
const isLoading: any = ref(false)
const isSelesai: any = ref(false)
const isKondisiAwal: any = ref(false)
const isKondisiAkhir: any = ref(false)
const isWA: any = ref(false)
const isLoadTmb: any = ref(false)
const showData: any = ref(false)
const listColoXr: any = ref([])
const d_Dokter: any = ref([])
let hasilPenunjangs: any = ref([])
const d_Diagnosa = ref([])
const d_produk = ref([])
const d_Ruangan = ref([])
const d_DiagnosaTindakan = ref([])
const sourceDataLab = ref([])
const d_SkalaNyeri = ref([])
const d_JenisDiagnosa = ref([])
const dataSourceDiagnosaX = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  tglpelayanan: new Date(),
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})

const COLLECTION: any = ref("resumeMedis") //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  diagnosaDokter: [{
    no: 1,
  }],
  detailTindakan: [{
    no: 1
  }],
  detailObatResep: [{
    no: 1
  }],
  detailDokterPelayanan: [{
    no: 1
  }]
})

const confirm = useConfirm();

const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = () => {

  input.value.dokterDPJP = { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk }
  useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        if(response[0].detailObatResep.length == 0){
          riwayatOrderResep()
        }
        input.value = response[response.length-1]
        input.value.riwayatPenyakit = response[0].riwayatPenyakit ??  response[0].riwayatPenyakitSekarang
        for (const key in input.value) {
            const element = input.value[key];
            if(response[response.length-1][key] !== undefined){
              input.value[key] = response[response.length-1][key]
            }
        } //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        response[0].statusSelesai ? isSelesai.value = response[0].statusSelesai : isSelesai.value = false
        response[0].statusSelesai ? isWA.value = response[0].statusSelesai : isWA.value = false

        isKondisiAwal.value = isSelesai.value
        if(!isSelesai.value){
          setAutofillTerapi()
          riwayatDiagnosa10()
          riwayatDiagnosa9()
          riwayatOrderResep()
        }
      }else{
        setAutofillTerapi()
        riwayatDiagnosa10()
        riwayatDiagnosa9()
        riwayatOrderResep()
      }
    })
}


const SourceHasilLab = async () => {
  // Initialize sourceDataLab.value as empty only if necessary
  sourceDataLab.value = [];

  // Fetch first set of lab results (new data)
  await useApi()
    .get(`/bridging/penunjang/get-hasil-new-registrasi?norec_apd=${item.NOREC_APD}&nocmfk=${ID_PASIEN}&noregistrasi=${props.registrasi.noregistrasi}&nocm=${props.pasien.nocm}`)
    .then((response) => {
      if (response.length > 0) {
        const formattedResponse = response.flatMap((group) =>
          group
            .filter((item) => !item.IsHeader) // Only take non-header items
            .map((item) => ({
              nama_pemeriksaan: item.TestName,
              tgl_hasil: item.ValidateOn,
              normal: item.RefRange,
              no_order: item.OrderTestId,
              no_urut: parseInt(item.DispSequence, 10),
              hasil: item.ResultValue,
            }))
        );

        // Append formatted response to sourceDataLab.value
        sourceDataLab.value = sourceDataLab.value.concat(formattedResponse);
      }
    });

  // Fetch second set of lab results (existing data)
  await useApi()
    .get(`/laboratorium/source-hasil-lab?noregistrasi=${props.registrasi.noregistrasi}`)
    .then((response) => {
      const formattedExistingResponse = response.map((element) => ({
        ...element,
      }));

      // Append response to sourceDataLab.value without overwriting
      sourceDataLab.value = sourceDataLab.value.concat(formattedExistingResponse);
    });

  // Sort combined data by `tgl_hasil`
  sourceDataLab.value.sort((a, b) => new Date(a.tgl_hasil) - new Date(b.tgl_hasil));

  // Reassign sequential numbering after sorting
  sourceDataLab.value = sourceDataLab.value.map((item, index) => ({
    ...item,
    no: index + 1, // Assign new sequential number
  }));
};

let object: any = {}
const simpan = async () => {
  object = {}
  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  if(props.registrasi.objectdepartemenfk == 16 || props.registrasi.objectdepartemenfk == 9){
    if (input.value.kondisiKeluar == 'Lainnya') {
        if (!input.value.kondisiKeluarLainya) {
        H.alert('warning', 'Kondisi Keluar Lainnya Tidak Boleh Kosong')
        isLoading.value = false
        return
      }
    }
    confirm.require({
          message: 'Sudah benar-benar selesai isi resume?',
          header: 'Konfirmasi Resume Medis',
          icon: 'pi pi-info-circle',
          acceptClass: 'p-button-danger',
          rejectClass: 'is-light mx-2',
          accept: () => {
              object.statusSelesai = true
              object.statusWA = true
              isKondisiAkhir.value = true
              lanjutSimpan()
          },
          reject: () => {
            object.statusSelesai = false
            object.statusWA = false
            isKondisiAkhir.value = false
            lanjutSimpan()
          },
          acceptLabel: 'Sudah Final',
          rejectLabel: 'Belum Selesai',

      })
  }else{
    object.statusSelesai = true
    lanjutSimpan()
  }

}

const lanjutSimpan = async () =>{
  let ID = input.value.id ? input.value.id : ''
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': props.FORM_URL,
    'name_form': props.FORM_NAME,
    'jenis_emr': 'resume_medis',
    'data': object
  }
  isLoading.value = true
  isSelesai.value = object.statusSelesai
  isWA.value = object.statusSelesai

  if(props.registrasi.objectdepartemenfk !== 16 || props.registrasi.objectdepartemenfk !== 9){
    isSelesai.value = true
  }

  input.value.diagnosaDokter.forEach(element => {
    if (input.value.diagnosaDokter.length > 0) {
      if(!element.norecDiagnosa){
        element.norecDiagnosa = ''
      }
      if (!element.jenisDiagnosa) {
        H.alert('error', 'Jenis Diagnosa Tidak Boleh Kosong')
        isLoading.value = false
        return
      }
    }
  });

  input.value.detailTindakan.forEach(element => {
    if (input.value.detailTindakan.length > 1) {
      if (!element.ketTindakanDokter && !element.diagnosaIcd9) {
        H.alert('error', '1 Baris Tindakan tidak boleh kosong')
        isLoading.value = false
        return
      }
    }
  });

  if(isKondisiAwal.value && !isKondisiAkhir.value){
  }else{
    if (input.value.diagnosaDokter[0].jenisDiagnosa != undefined) {
      await useApi().post(`/diagnosa/save-more-diagnosa`, { 'diagnosis': input.value.diagnosaDokter, 'noregistrasifk': props.registrasi.norec_apd }).then((response: any) => {
        riwayatDiagnosa10()
      }).catch((e: any) => {
        console.log(e)
      })
    }

    if (input.value.detailTindakan[0].ketTindakanDokter != undefined) {
      await useApi().post('/diagnosa/save-more-diagnosa-ix', { 'diagnosaPerawat': input.value.detailTindakan, 'noregistrasifk': props.registrasi.norec_apd }).then((response: any) => {
        riwayatDiagnosa9()
      }).catch((e: any) => {
        console.log(e)
      })
    }
  }


  useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
    isLoading.value = false
    NOREC_EMRPASIEN.value = response.norec_emr
    input.value.id = response.id

    saveStatusDPJPincbgs()
    loadRiwayat()
    // if (ID_PASIEN != null) {
    //   let jsonSave = {
    //     norec: input.value.norec !== undefined ? input.value.norec : '',
    //     nocm: props.pasien.nocm,
    //     norec_pd: props.registrasi.norec_pd,
    //     tglperjanjian: H.formatDate(input.value.tglperjanjian, 'YYYY-MM-DD HH:mm'),
    //     objectruanganfk: input.value.poli.value,
    //     objectdokterfk: input.value.dokterDPJP,
    //     method: 'save'
    //   }

    //   isLoading.value = true
    //   useApi().post('/emr/simpan-perjanjian', jsonSave).then((r) => {
    //     insertRencanKontrolBPJS(props.registrasi.nosep,
    //       input.value.dokter.kddokterbpjs,
    //       input.value.poli.kdsubspesialisbpjs,
    //       input.value.tanggal)

    //     isLoading.value = false
    //   }).catch((e: any) => {
    //     isLoading.value = false
    //   })
    // }

  }).catch((e: any) => {
    isLoading.value = false
  })
}

// const insertRencanKontrolBPJS = (noSep: any, kdDokter: any, kdPoli: any, Tglkontrol: any) => {
//   let tglRencanaKontrol = H.formatDate(Tglkontrol, 'YYYY-MM-DD')
//   let data = {
//     "url": "RencanaKontrol/insert",
//     "method": "POST",
//     "data": {
//       "request": {
//         "noSEP": noSep,
//         "kodeDokter": kdDokter,
//         "poliKontrol": kdPoli,
//         "tglRencanaKontrol": tglRencanaKontrol,
//         "user": H.pegawaiLogin().namaLengkap
//       }
//     }
//   }

//   useApi().postBPJS('/bridging/bpjs/tools', data).then(function (x) {
//     if (x.data.metaData.code == 201) {
//       let jsonGet = {
//         "url": `jadwaldokter/kodepoli/${kdPoli}/tanggal/${tglRencanaKontrol}`,
//         "jenis": "antrean",
//         "method": "GET",
//         "data": null
//       }
//       useApi().postBPJS('/bridging/bpjs/tools', jsonGet).then(function (y) {
//         if (y.data.metaData.code == 200) {
//           let listDPJP = y.data.response;
//           let randDpjp = listDPJP[Math.floor(Math.random() * listDPJP.length)]
//           insertRencanKontrolBPJS(noSep, randDpjp.kodedokter, kdPoli, Tglkontrol)
//         }
//       })
//     }

//     if (x.data.metaData.code == 203) {
//       let bulan = H.formatDate(Tglkontrol, 'MM')
//       let tahun = H.formatDate(Tglkontrol, 'YYYY')
//       let jsonGet = {
//         "url": `RencanaKontrol/ListRencanaKontrol/Bulan/${bulan}/Tahun/${tahun}/Nokartu/${props.pasien.nobpjs}/filter/2`,
//         "method": "GET",
//         "data": null
//       }
//       useApi().postBPJS('/bridging/bpjs/tools', jsonGet).then((z) => {
//         for (let i = 0; i < z.data.response.list.length; i++) {
//           const element = z.data.response.list[i];
//           if (element.noSepAsalKontrol == props.registrasi.nosep) {
//             let jsonDel = {
//               "url": "RencanaKontrol/Delete",
//               "method": "DELETE",
//               "data": {
//                 "request": {
//                   "t_suratkontrol": {
//                     "noSuratKontrol": element.noSuratKontrol,
//                     "user": H.pegawaiLogin().namaLengkap
//                   }
//                 }
//               }
//             }
//             useApi().postBPJS('/bridging/bpjs/tools', jsonDel).then((d) => {
//               if (d.data.metaData.code == 200) {
//                 insertRencanKontrolBPJS(noSep, kdDokter, kdPoli, Tglkontrol)
//               }
//             });
//             break;
//           }
//         }
//       })
//     }

//   })
// }

const addDiagnosa = async (data: any) => {
  data.isLoadBtnDiagnosaDokter = true
  let objSave = {
    'diagnosapasien': {
      'norec': data.norecDiagnosa ? data.norecDiagnosa : '',
      'noregistrasifk': props.registrasi.norec_apd,
      'ketdiagnosis': data.ketDiagnosaDok ? data.ketDiagnosaDok : null,
      'iskasusbaru': null,
      'iskasuslama': null,
    },
    'detaildiagnosapasien': {
      'objectdiagnosafk': data.diagnosaIcd10 ? data.diagnosaIcd10.value : null,
      'tglinputdiagnosa': H.formatDate(new Date(), 'YYYY-MM-DD HH:mm:ss'),
      'objectjenisdiagnosafk': data.jenisDiagnosa ? data.jenisDiagnosa.value : null,
      'noregistrasi': props.registrasi.noregistrasi,
    },
    'pasien': {
      'nocm': props.pasien.nocm,
      'namapasien': props.pasien.namapasien,
      'noregistrasi': props.registrasi.noregistrasi,
    }
  }

  await useApi().post(`/diagnosa/save-diagnosa`, objSave).then((response: any) => {
    riwayatDiagnosa10()
    data.isLoadBtnDiagnosaDokter = false
  }).catch((e: any) => {
    console.log(e)
  })

}

// const addDiagnosaDok = async (data: any) => {
//   input.value.diagnosaDokter.push({
//     no: input.value.diagnosaDokter[input.value.diagnosaDokter.length - 1].no + 1,
//     norecDiagnosa: '',
//   });
// }

const addDiagnosaDok = async (data: any) => {
  const areAllDiagnosasValid = input.value.diagnosaDokter.every(element => !!element.jenisDiagnosa);

  if (areAllDiagnosasValid) {
    input.value.diagnosaDokter.push({
      no: input.value.diagnosaDokter[input.value.diagnosaDokter.length - 1].no + 1,
      norecDiagnosa: '',
    });
  } else {
    H.alert('warning', 'Terdapat Diagnosa & Jenis Diagnosa Kosong');
    isLoading.value = false;
    return;
  }
}

const removeDiagnosaDok = async (data: any, index: any) => {
  data.isLoadBtnDiagnosaDokter = true
  if (data.norecDiagnosa) {
    await useApi().postNoMessage(`/diagnosa/delete-diagnosa-x`, { norec: data.norecDiagnosa }).then((response: any) => {
      data.isLoadBtnDiagnosaDokter = false
      input.value.diagnosaDokter.splice(index, 1)
      riwayatDiagnosa10()
    }).catch((e: any) => {
      input.value.diagnosaDokter.splice(index, 1)
    })
  } else {
    input.value.diagnosaDokter.splice(index, 1)
  }
}

// const addDiagnosaICD9 = async (data: any) => {
//   input.value.detailTindakan.push({
//     no: input.value.detailTindakan[input.value.detailTindakan.length - 1].no + 1,
//     norecDiagnosa9: '',
//   });
// }

const addDiagnosaICD9 = async (data: any) => {
  const areAllValid = input.value.detailTindakan.every(element => !!element.ketTindakanDokter || !!element.diagnosaIcd9);

  if (areAllValid) {
    input.value.detailTindakan.push({
      no: input.value.detailTindakan[input.value.detailTindakan.length - 1].no + 1,
      norecDiagnosa9: '',
    });
  } else {
    H.alert('warning', 'Tindakan Dokter Atau Diagnosa ICD9 Tidak Boleh Kosong');
    isLoading.value = false;
    return;
  }
}

const removeDiagnosaICD9 = async (data: any, index: any) => {
  data.isLoadBtnDiagnosaDokter9 = true
  if (data.norecDiagnosa9) {
    await useApi().postNoMessage(`/diagnosa/delete-diagnosa-ix`, { norec: data.norecDiagnosa9 }).then((response: any) => {
      data.isLoadBtnDiagnosaDokter9 = false
      input.value.detailTindakan.splice(index, 1)
      riwayatDiagnosa9()
    }).catch((e: any) => {
      data.isLoadBtnDiagnosaDokter9 = false
      input.value.detailTindakan.splice(index, 1)
    })
  } else {
    data.isLoadBtnDiagnosaDokter9 = false
    input.value.detailTindakan.splice(index, 1)
  }

}

const addNewObat = () => {
  input.value.detailObatResep.push({
    no: input.value.detailObatResep[input.value.detailObatResep.length - 1].no + 1
  });
}

const removeObat = (index: any) => {
  input.value.detailObatResep.splice(index, 1)
  if(input.value.detailObatResep.length == 0) {
    input.value.detailObatResep.push({
    no: 1
  });
  }
}

const show = () => {
  showData.value = true
}

const setAutofillTerapi = () => {
  useApi().get(`/emr/get-riwayat-resep?norec_pd=${props.registrasi.norec_pd}`).then((response) => {
    if (response.length > 0) {
      input.value.terapi = ''
      // response.forEach((element: any, i: any) => {
      //   input.value.terapi += element.namaproduk + ','
      // });
      response.forEach((element: any, index: number) => {
        input.value.terapi += element.namaproduk;
        if (index < response.length - 1) {
          input.value.terapi += ', \n'; // Menambahkan koma dan baris baru
        } else {
          input.value.terapi += '\n'; // Untuk produk terakhir, hanya newline tanpa koma
        }
      });
    }
  })
}

const setAutoFillVitalSign = () => {
  // useApi().get(`/emr/get-order-konsul?nocm=${props.pasien.nocm}&noregistrasi=${props.registrasi.noregistrasi}`).then((response: any) => {
  //   if (response.data.length > 0) {
  //     input.value.hasilKonsultasi = ''
  //     response.data.forEach((element: any) => {
  //       if (element.keteranganlainnya) {
  //         input.value.hasilKonsultasi += `${element.namalengkap} : ${element.keteranganlainnya} \n`
  //       }
  //     });
  //   }
  // })

  useApi().get(
    `/emr/get-petugas?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}`).then((response: any) => {
      input.value.pelaksana = ''
      response.forEach((element: any, i: any) => {
        input.value.pelaksana += element.namalengkap + '\n'
      });
    })

  useApi().get("emr/auto-fill?norec_pd=" + props.registrasi.norec_pd + "&collection=VitalSign" +
    "&field=tinggiBadan,beratBadan,tekananDarah,suhu,nadi,pernapasan,SPO2,IMT"
  ).then((response) => {
    if (response != null) {
      input.value.tinggiBadan = response.tinggiBadan ? response.tinggiBadan : ''
      input.value.beratBadan = response.beratBadan ? response.beratBadan : ''
      input.value.tekananDarah = response.tekananDarah ? response.tekananDarah : ''
      input.value.nadi = response.nadi ? response.nadi : ''
      input.value.suhu = response.suhu ? response.suhu : ''
      input.value.pernapasan = response.pernapasan ? response.pernapasan : ''
      input.value.SPO2 = response.SPO2 ? response.SPO2 : ''
      input.value.IMT = response.IMT ? response.IMT : ''

    }
  })

  useApi().get("emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=MalnutritionScreeningTools" + "&field=tidakAdaTurunBeratBadan,turunBeratBadan,asupanMakan,totalSkor"
  ).then((response) => {
    if (response != null) {
      input.value.totalSkor = response.totalSkor
      input.value.tidakAdaTurunBeratBadan = response.tidakAdaTurunBeratBadan
      input.value.turunBeratBadan = response.turunBeratBadan
      input.value.asupanMakan = response.asupanMakan
    }
  })

}

const autoFillGizi = () => {
  useApi().get("emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=MonitoringEvaluasiTerapiGizi" + "&field=tinggiBadan,beratBadan,IMT"
  ).then((response) => {
    if (response != null) {
      input.value.tinggiBadan = response.tinggiBadan ? response.tinggiBadan : ''
      input.value.beratBadan = response.beratBadan ? response.beratBadan : ''
      input.value.IMT = response.IMT ? response.IMT : ''
    }
  })
}

const AutoFillDokterGawatDarurat = () => {
  useApi().get("emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=pengkajianDokterGawatDarurat" + "&field=riwayatPenyakitSekarang,fisikKepalaAbnormal,fisikLeherAbnormal,fisikThoraksAbnormal,fisikAbdomenAbnormal,fisikEkstremitasAbnormal,fisikLainnyaAbnormal"
  ).then((response) => {
    if (response != null) {
      input.value.riwayatPenyakit = response.riwayatPenyakitSekarang
      let pemeriksaanFisik = [];

      if (response.fisikKepalaAbnormal) {
        pemeriksaanFisik.push("kepala");
      }
      if (response.fisikLeherAbnormal) {
        pemeriksaanFisik.push("leher");
      }
      if (response.fisikThoraksAbnormal) {
        pemeriksaanFisik.push("thoraks");
      }
      if (response.fisikAbdomenAbnormal) {
        pemeriksaanFisik.push("abdomen");
      }
      if (response.fisikEkstremitasAbnormal) {
        pemeriksaanFisik.push("ekstremitas");
      }
      if (response.fisikLainnyaAbnormal) {
        pemeriksaanFisik.push("Lainnya");
      }
      input.value.pemeriksaanFisikLain = pemeriksaanFisik.join(", ");
    }
  })
}
const getPemeriksaanFisik = async () => {
  try {
    const response = await useApi().get(`/emr/get-pemeriksaan-fisik?norec_pd=${props.registrasi.norec_pd}`);
    if (response.data) {
      processPemeriksaanFisik(response.data);
    }
  } catch (error) {
    console.error('Error fetching pemeriksaan fisik:', error);
  }
}
const processPemeriksaanFisik = (data) => {
  let abnormalFindings = [];

  if (data.fisikKepala === 'Abnormal') {
    abnormalFindings.push(`Kepala: ${data.fisikKepalaAbnormalKonjungtivaAnemisKet || ''} ${data.fisikKepalaAbnormalSkleraIkterikKet || ''} ${data.fisikKepalaAbnormalLainKet || ''}`);
  }
  if (data.fisikLeher === 'Abnormal') {
    abnormalFindings.push(`Leher: ${data.fisikLeherJTVMeningkatKet || ''} ${data.fisikLeherTumorKet || ''} ${data.fisikLeherLainKet || ''}`);
  }
  if (data.fisikThoraks === 'Abnormal') {
    abnormalFindings.push(`Thoraks: ${data.fisikThoraksAbnormalBunyiJantungKet || ''} ${data.fisikThoraksAbnormalBisingJantungKet || ''} ${data.fisikThoraksAbnormalRonchiKet || ''} ${data.fisikThoraksAbnormalWheezingKet || ''} ${data.fisikThoraksAbnormalLainKet || ''}`);
  }
  if (data.fisikAbdomen === 'Abnormal') {
    abnormalFindings.push(`Abdomen: ${data.fisikAbdomenAbnormalDatarCembungKet || ''} ${data.fisikAbdomenAbnormalBisingUsusKet || ''} ${data.fisikAbdomenAbnormalNyeriTekanKet || ''} ${data.fisikAbdomenAbnormalPerkusiKet || ''} ${data.fisikAbdomenAbnormalLainKet || ''}`);
  }
  if (data.fisikEkstremitas === 'Abnormal') {
    abnormalFindings.push(`Ekstremitas: ${data.fisikEkstremitasAbnormalOedemKet || ''} ${data.fisikEkstremitasAbnormalkukuPucatKet || ''} ${data.fisikEkstremitasAbnormalkukuSianosisKet || ''} ${data.fisikEkstremitasAbnormalkekuatanOtotKet || ''} ${data.fisikEkstremitasAbnormalLainKet || ''}`);
  }
  if (data.fisikLainnya === 'Abnormal') {
    abnormalFindings.push(`Lainnya: ${data.fisikLainnyaAbnormalKet || ''}`);
  }

  if (abnormalFindings.length > 0) {
    input.value.fisikLainnyaAbnormalKet = abnormalFindings.join('. ').trim();
  } else {
    input.value.fisikLainnyaAbnormalKet = 'Tidak ada temuan abnormal';
  }
}

const riwayatDiagnosa10 = async () => {
  // if(props.registrasi.objectdepartemenfk != 16){
  await useApi().get("diagnosa/riwayat-diagnosa-x-cppt?noregistrasi=" + props.registrasi.noregistrasi
  ).then((response) => {
    if (response.data.length <= 0) {
      input.value.diagnosaDokter = [{
        no: 1,
        norecDiagnosa: '',
      }]
    } else {
      input.value.diagnosaDokter = response.data.map((e: any, i: any) => ({
        no: i + 1,
        jenisDiagnosa: { value: e.objectjenisdiagnosafk, label: e.jenisdiagnosa },
        norecDiagnosa: e.norec,
        ketDiagnosaDok: e.keterangan,
        diagnosaIcd10: e.namadiagnosa && e.id ? { value: e.id, label: e.kddiagnosa + "--" + e.namadiagnosa } : ''
      }));
    }
      // input.value.diagnosaDokter.push({ no: input.value.diagnosaDokter[input.value.diagnosaDokter.length - 1].no + 1 })
    }).catch((e: any) => {
    })
  // }
}

const riwayatDiagnosa9 = async () => {
  await useApi().get(
    `/diagnosa/riwayat-diagnosa-ix?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}`).then((response: any) => {
      if (response.data.length <= 0) {
        input.value.detailTindakan = [{
          no: 1,
          norecDiagnosa9: '',
        }]
      } else {
        input.value.detailTindakan = response.data.map((detail: any, i: any) => {
          return {
            no: i + 1,
            norecDiagnosa9: detail.norec_diagnosapasien,
            ketTindakanDokter: detail.keterangantindakan,
            diagnosaIcd9: detail.kddiagnosatindakan && detail.namadiagnosatindakan ? { label: detail.kddiagnosatindakan + "--" + detail.namadiagnosatindakan, value: detail.objectdiagnosatindakanfk } : ''
          }
        })
        // input.value.detailTindakan.push({ no: input.value.detailTindakan[input.value.detailTindakan.length - 1].no + 1 })
      }
    }).catch((e: any) => {
    })
}

const riwayatOrderResep = async () => {
  if(props.registrasi.objectdepartemenfk == 16 || props.registrasi.objectdepartemenfk == 9){
    await useApi().get(`/farmasi/riwayat-resep-pulang?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}`).then((response: any) => {
      if (response.length <= 0) {
        input.value.detailObatResep = [{
          no: 1
        }]
      } else {
        input.value.detailObatResep = response.flatMap((e: any) =>
          e.details.map((detail: any) => {
            return {
              jenisObat: detail.jeniskemasan,
              obat: { value: detail.objectprodukfk, label: detail.namaproduk },
              aturanPakai: detail.aturanpakai,
              jumlah: detail.jumlah,
              waktuPakai: detail.satuanresep,
              dosis: detail.dosis,
            }
          })
        );
      }
    })
  }else{
    await useApi().get(`/emr/get-riwayat-resep?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}`).then((response: any) => {
      if (response.length <= 0) {
        input.value.detailObatResep.push({
          no: 1
        })
        console.log(input.value.detailObatResep)
      } else {
        input.value.detailObatResep = response.filter((detail) => detail.satuanresep !== null)
        .map( (detail: any) => {
            return { jenisObat: detail.jeniskemasan, obat: { value: detail.objectprodukfk, label: detail.namaproduk }, aturanPakai: detail.aturanpakai, jumlah: detail.jumlah, dosis: detail.dosis, waktuPakai: detail.satuanresep }
        })
        // input.value.detailObatResep = response.map((e: any) => {
        //   }
        // );
      }
    })
  }
}

const fetchJenisDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/jenisdiagnosa_m?select=id,jenisdiagnosa&param_search=jenisdiagnosa&query=${filter.query}&limit=10`)
  d_JenisDiagnosa.value = response
}

const fetchDiagnosa = async (filter: any) => {
  const response = await useApi().get(`/diagnosa/diagnosa-x-paging?name=${filter.query}&limit=10`)
  d_Diagnosa.value = response.diagnosa.map((item: any) => {
    return { value: item.id, label: item.kddiagnosa + ' -- ' + item.namadiagnosa }
  })
}

const fetchDiagnosa9 = async (filter: any) => {
  await useApi().get(`/diagnosa/diagnosa-ix-paging?name=${filter.query}&limit=10`).then((response) => {
    d_DiagnosaTindakan.value = response.diagnosatindakan.map((e: any) => ({
      value: e.id,
      label: e.kddiagnosatindakan + " -- " + e.namadiagnosatindakan
    }));
  })
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}
const saveStatusDPJPincbgs = () => {
  if (input.dokterDPJP != undefined) {
    let jsonSend = {
      'norec_pd': NOREC_PD,
      'status': input.dokterDPJP ? true : false
    };

    useApi().post('bridging/inacbgs/save-status-dpjp', jsonSend).then((response: any) => {
      console.log("Update statusdpjpincbgs berhasil", response);
    }).catch((err: any) => {
      console.log("Error saat mengupdate statusdpjpincbgs", err);
    });
  }
}

const fetchRuangan = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&settingdatafix=objectdepartemenfk,kdDepartemenRawatJalanFix&limit=10`
  ).then((response) => {
    d_Ruangan.value = response
  })
}

const fetchProduk = async (filter: any) => {
  const response = await useApi().get(
    `/farmasi/dropdown-obat?namaproduk=${filter.query}&limit=10`)
  d_produk.value = response.map((e: any) => {
    return { label: e.namaproduk, value: e.id }
  })

}

const kembaliKeun = () => {
  window.history.back()
}

const getHasilRad = async () => {
  await useApi().get(`/emr/get-hasil-radiologi?noregistrasi=${props.registrasi.noregistrasi}&norec_pd=${props.registrasi.norec_pd}`).then((response: any) => {
    if (response.length > 0) {
      input.value.pemeriksaPenunjang = ''
      response.forEach(element => {
        if (sourceDataLab.value.length == 0) {
          input.value.pemeriksaPenunjang += element.hasilexpertise.replace(/#&#/g, '\n');
        }
        hasilPenunjangs.value.push(element.hasilexpertise.replace(/#&#/g, '\n'))
      });
    }
  })
}


const tambahHasilLab = async (e: any) => {
  let noorder = []
  let nourut = []
  let sementara = []
  item.sourcePemeriksaanPenunjang = ''
  isLoadTmb.value = true
  e.forEach((element: any) => {
    nourut = [...new Set([...nourut, element.no_urut])]
    noorder = [...new Set([...noorder, element.no_order])]
  });

  e.forEach(element => {
    // Format each part to align properly
    const tglHasil = H.formatDate(element.tgl_hasil, 'YYYY-MM-DD HH:mm').padEnd(20, ' '); // Date column width

    // Trim extra spaces from nama_pemeriksaan and pad to 30 characters
    const namaPemeriksaan = element.nama_pemeriksaan.trim().padEnd(10, ' '); // Name column width

    const hasil = element.hasil.toString().padEnd(10, ' '); // Result column width
    const normal = `Normal : ${element.normal}`; // Normal range, no padding needed at end

    // Concatenate formatted strings
    const hasilLab = `${tglHasil}${namaPemeriksaan}${hasil}${normal}\n`;

    // Add to 'sementara' set and 'item.sourcePemeriksaanPenunjang' for unique results
    sementara = [...new Set([...sementara, hasilLab])];
    item.sourcePemeriksaanPenunjang += hasilLab;
  });

isLoadTmb.value = false

  hasilPenunjangs.value.forEach(element => {
    item.sourcePemeriksaanPenunjang += element
  });
}

const simpanHasilPenunjang = (e: any) => {
  input.value.pemeriksaPenunjang = ''
  if (!e) {
    H.alert('error', 'Hasil Penunjang Kosong')
    return
  }

  input.value.pemeriksaPenunjang += e
  showData.value = false

}

watch(
    () => [
        input.value.beratBadan,
        input.value.tinggiBadan],
    (value) => {
        let txtFirstNumberValue: any = input.value.beratBadan;
        let txtSecondNumberValue: any = input.value.tinggiBadan;
        let result: any = parseFloat(txtFirstNumberValue) / (parseFloat(txtSecondNumberValue) / 100
            * parseFloat(txtSecondNumberValue) / 100);

        input.value.IMT = parseFloat(result).toFixed(2)
        if (isNaN(input.value.IMT)) {
            input.value.IMT = 0
        }
    }
)

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

setView()
onMounted(async () => {
  isLoading.value = true
  await SourceHasilLab()
  await getHasilRad()
  await autoFillGizi()
  await setAutoFillVitalSign()
  await AutoFillDokterGawatDarurat()
  await loadRiwayat()
  // await setAutofillTerapi()
  // await riwayatDiagnosa10()
  // await riwayatDiagnosa9()
  // await riwayatOrderResep()
  isLoading.value = false
})


// TTE START
const modalSimpanDokumenUntukTTE: any = ref(false);
const modalInputTTE: any = ref(false);
const showPassword = ref(false);

const modalPengajuanTTE = () => {
  item.dokterTTE = { label: H.pegawaiLogin().namalengkap, value: H.pegawaiLogin().id };
  modalSimpanDokumenUntukTTE.value = true;
};
// SAVE dokumen to local storage dan database untuk monitoring
const savePengajuanTTE = async () => {
  isLoading.value = true;
  let params = {
    norec_pd: props.registrasi.norec_pd,
    objectpegawaifk: item.dokterTTE.value,
    emrpasienfk: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    api: 'EMR-ReportEMRCtrl@cetakEMR-resumeMedis' //hardcore ambil dari dokumenklaim_m
  }
  const response = await useApi().post(`/bsre-esign/save-dokumen-pengajuan-tte`, params);
  if (response.status === 201) {
    if (response.data) {
      item.norecDOK = response.data.norec || "";
      item.file = response.data.file || "";
      item.namadokumen = response.data.namadokumen || "";
      item.file = response.data.file || "";

      modalSimpanDokumenUntukTTE.value = false;
    } else {
      console.warn("Response.data is undefined");
    }
    H.alert("success", "Berhasil Simpan Dokumen");
    isLoading.value = false;
  } else {
    H.alert("error", "Periksa Kembali Dokumen Anda");
    isLoading.value = false;
  }
};
// TTE END

</script>

<style lang="scss">
.label-ass {
  font-weight: 500;
}

.title-ass {
  font-weight: bold;
}

.table-po {
  width: 100% !important;
  border-collapse: collapse !important;
}

.table-po,
.tr-po,
.th-po,
.td-po {
  border: 0.5px solid black !important;
}

.th-po,
.td-po {
  padding: 8px !important;
}

.bold {
  font-weight: bold;
}

.label-unset {
  text-overflow: unset !important;
}

.pembungkus {
  height: 100% !important;
  border-top: 2px solid var(--blue-500);
}

.bt-color {
  height: 100% !important;
  border-top: 2px solid var(--nonaktif);
}

.hidde-card {
  border: none;
  padding-top: 0px;
  padding-bottom: 0px;
}
</style>
