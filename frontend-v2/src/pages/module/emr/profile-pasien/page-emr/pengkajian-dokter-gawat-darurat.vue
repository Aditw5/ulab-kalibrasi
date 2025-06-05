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
                      <div class="column is-2">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input.alloAnamnesis" label="Allo Anamnesis" class="p-0" color="primary"
                              square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-2">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input.autoAnamnesis" label="Auto Anamnesis" class="p-0" color="primary"
                              square />
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
                    <h1 style="font-weight: bold;">Keluhan Utama :</h1>
                    <VControl class="mt-2">
                      <VTextarea rows="3" placeholder="Keluhan Utama..." v-model="input.keluhanUtama">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField>
                    <h1 style="font-weight: bold;">Riwayat Penyakit Sekarang : </h1>
                    <VControl class="mt-2">
                      <VTextarea rows="3" placeholder="Riwayat Penyakit Sekarang..."
                        v-model="input.riwayatPenyakitSekarang">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-4">
                  <VField>
                    <h1 style="font-weight: bold;">Riwayat Penyakit Dahulu : </h1>
                    <VControl class="mt-2">
                      <VTextarea rows="3" placeholder="Riwayat Penyakit Dahulu..." v-model="input.riwayatPenyakitDahulu">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField>
                    <h1 style="font-weight: bold;">Alergi Obat: </h1>
                    <VControl class="mt-2">
                      <VTextarea rows="3" placeholder="Alergi Obat..." v-model="input.alergiObat">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight : bold;">Obat Yang Diminum Saat Ini :</h1>
                  <VField>
                    <VControl class="mt-2">
                      <VTextarea rows="3" placeholder="Alergi Obat..." v-model="input.obatSaatIni">
                      </VTextarea>
                    </VControl>
                    <!-- <VControl>
                      <AutoComplete v-model="input.produk" :suggestions="d_produk" @complete="fetchProduk($event)"
                        :optionLabel="'namaproduk'" :dropdown="true" :minLength="3" class="is-input" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'namaproduk'" placeholder="ketik untuk mencari..." />
                    </VControl> -->
                  </VField>
                </div>
              </div>
            </div>
          </VCard>
        </div>
        <div class="column is-12">
          <Fieldset class="p-fieldsets" legend="Kondisi Pasien Saat Masuk" :toggleable="true">
            <div class="columns is-multiline mb-5">
              <div class="column is-12 P-0">
                <div class="column is-12">
                  <span> Keadaan Umum Sakit : </span>
                </div>
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.keadaanUmumRingan" label="Ringan" class="p-0" color="primary"
                            square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.keadaanUmumSedang" label="Sedang" class="p-0" color="primary"
                            square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.keadaanUmumBerat" label="Berat" class="p-0" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12">
              <span> GCS : </span>
            </div>
            <div class="columns is-multiline mb-5">
              <div class="column is-12">
                <div class="column is-6 pb-0">
                  <div class="columns">
                    <div class="column is-1">
                      <h1 class="mb-3 emr" style="text-align: center;">No</h1>
                    </div>
                    <div class="column is-one-quarter" style="text-align: center;">
                      <h1 class="mb-3 emr">Parameter</h1>
                    </div>
                    <div class="column is-8" style="text-align: center;">
                      <h1 class="mb-3 emr">Nilai</h1>
                    </div>
                  </div>
                  <div class="columns pb-4" v-for="(data) in kesadaran">
                    <div class="column is-1 pt-0 " style="text-align: center;">
                      <h1 class="mb-3 emr"> {{ data.no }}</h1>
                    </div>
                    <div class="column  is-one-quarter pt-0" style="text-align: center;">
                      <h1 class="mb-3 emr">{{ data.parameter }}</h1>
                    </div>
                    <div class="column is-8" style="text-align: end;">
                      <div class="columns is-multiline pb-5">
                        <div class="column is-2 pt-0" v-for="(pilihan) in data.nilai">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox class="p-0" :true-value="pilihan.poin" v-model="input[pilihan.model]"
                                :label="pilihan.poin" color="primary" />
                            </VControl>
                          </VField>
                        </div>
                      </div>

                    </div>

                  </div>
                </div>
              </div>
              <div class="column is-12 P-0">
                <div class="column is-12">
                  <span> Kesadaran : </span>
                </div>
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.KesadaranCM" label="CM" class="p-0" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.KesadaranApatis" label="Apatis" class="p-0" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.KesadaranDelirium" label="Delirium" class="p-0" color="primary"
                            square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.KesadaranSomnolen" label="Somnolen" class="p-0" color="primary"
                            square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.KesadaranSopor" label="Sopor" class="p-0" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.KesadaranComa" label="Coma" class="p-0" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
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
                <div class="column is-3">
                  <div class=" columns is-multiline">
                    <div class="column is-12" style="margin-top:0.5rem">
                      <span> Skor EWS : </span>
                    </div>
                    <div class="column is-12">
                      <VField addons>
                        <VControl>
                          <VInput type="text" class="input" placeholder="Skor EWS" v-model="input.SkorEWS" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>EWS</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-3">
                  <div class=" columns is-multiline">
                    <div class="column is-12" style="margin-top:0.5rem">
                      <span> Skor PEWS : </span>
                    </div>
                    <div class="column is-12">
                      <VField addons>
                        <VControl>
                          <VInput type="text" class="input" placeholder="Skor EWS" v-model="input.SkorPEWS" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>PEWS</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns is-multiline mb-5">
              <div class="column is-12 P-0">
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <VField>
                        <h1 style="font-weight: bold;">Catatan Khusus / Keterangan Lain : </h1>
                        <VControl class="mt-2">
                          <VTextarea rows="3" placeholder="Catatan Khusus / Keterangan Lain..."
                            v-model="input.catatanKhususKeteranganLain">
                          </VTextarea>
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
          <div class="column is-12">
            <Fieldset :toggleable="true" legend="Pemeriksaan Fisik">
              <div class="column" style="overflow: auto;">
                <table class="tg">
                  <thead>
                    <tr>
                      <td class="tg-0lax text-center" colspan="5">Pemeriksaan Fisik</td>
                    </tr>
                    <tr>
                      <td class="tg-0lax text-center" colspan="2"></td>
                      <td class="tg-0lax">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" v-model="input.checkAll" color="primary" true-value="Normal"/>
                          </VControl>
                        </VField>
                      </td>
                      <td class="tg-0lax">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" v-model="input.checkAll" color="primary" true-value="Abnormal"/>
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td width="2%">1</td>
                      <td width="20%">Kepala</td>
                      <td>
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" v-model="input.fisikKepala" label="Normal" color="primary" true-value="Normal"/>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" v-model="input.fisikKepalaAbnormal" label="Abnormal" color="primary" true-value="Abnormal"/>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl  raw subcontrol>
                            <VCheckbox class="p-0" v-model="input.fisikKepalaAbnormalKonjungtivaAnemis"
                              label="Conjungtiva Anemis" color="primary"/>
                          </VControl>
                        </VField>
                        <VField v-if="input.fisikKepalaAbnormalKonjungtivaAnemis">
                          <VControl>
                            <VInput type="text" class="input" placeholder="Keterangan..."
                              v-model="input.fisikKepalaAbnormalKonjungtivaAnemisKet" />
                          </VControl>
                        </VField>
                        <VField>
                          <VControl  raw subcontrol>
                            <VCheckbox class="p-0" v-model="input.fisikKepalaAbnormalSkleraIkterik"
                              label="Sklera Ikterik" color="primary"/>
                          </VControl>
                        </VField>
                        <VField v-if="input.fisikKepalaAbnormalSkleraIkterik">
                          <VControl>
                            <VInput type="text" class="input" placeholder="Keterangan..."
                              v-model="input.fisikKepalaAbnormalSkleraIkterikKet" />
                          </VControl>
                        </VField>
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" v-model="input.fisikKepalaAbnormalLain" label="Lain-Lain"
                              color="primary" />
                          </VControl>
                        </VField>
                        <VField v-if="input.fisikKepalaAbnormalLain">
                          <VControl>
                            <VInput type="text" class="input" placeholder="Keterangan..."
                              v-model="input.fisikKepalaAbnormalLainKet" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td width="2%">2</td>
                      <td width="20%">Leher</td>
                      <td>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-0" v-model="input.fisikLeher" label="Normal" true-value="Normal" color="primary" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-0" v-model="input.fisikLeherAbnormal" label="Abnormal" true-value="Abnormal" color="primary" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-0" v-model="input.fisikLeherJTVMeningkat" label="JTV Meningkat"
                              color="primary" />
                          </VControl>
                        </VField>
                        <VField v-if="input.fisikLeherJTVMeningkat">
                          <VControl>
                            <VInput type="text" class="input" placeholder="Keterangan..."
                              v-model="input.fisikLeherJTVMeningkatKet" />
                          </VControl>
                        </VField>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-0" v-model="input.fisikLeherTumor" label="Tumor" color="primary" />
                          </VControl>
                        </VField>
                        <VField v-if="input.fisikLeherTumor">
                          <VControl>
                            <VInput type="text" class="input" placeholder="Keterangan..."
                              v-model="input.fisikLeherTumorKet" />
                          </VControl>
                        </VField>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-0" v-model="input.fisikLeherLain" label="Lain-Lain" color="primary" />
                          </VControl>
                        </VField>
                        <VField v-if="input.fisikLeherLain">
                          <VControl>
                            <VInput type="text" class="input" placeholder="Keterangan..."
                              v-model="input.fisikLeherLainKet" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td width="2%">3</td>
                      <td width="20%">Thoraks</td>
                      <td>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-0" v-model="input.fisikThoraks" label="Normal" color="primary" true-value="Normal"/>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-0" v-model="input.fisikThoraksAbnorma" label="Abnormal" true-value="Abnormal"
                              color="primary" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-0" v-model="input.fisikThoraksAbnormalBunyiJantung"
                              label="Bunyi Jantung I/II Reguler/Ireguler" color="primary" />
                          </VControl>
                        </VField>
                        <VField v-if="input.fisikThoraksAbnormalBunyiJantung">
                          <VControl>
                            <VInput type="text" class="input" placeholder="Keterangan..."
                              v-model="input.fisikThoraksAbnormalBunyiJantungKet" />
                          </VControl>
                        </VField>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-0" v-model="input.fisikThoraksAbnormalBisingJantung"
                            label="Bising Jantung" color="primary" />
                          </VControl>
                        </VField>
                        <VField v-if="input.fisikThoraksAbnormalBisingJantung">
                          <VControl>
                            <VInput type="text" class="input" placeholder="Keterangan..."
                              v-model="input.fisikThoraksAbnormalBisingJantungKet" />
                          </VControl>
                        </VField>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-0" v-model="input.fisikThoraksAbnormalRonchi" label="Ronchi"
                            color="primary" />
                          </VControl>
                        </VField>
                        <VField v-if="input.fisikThoraksAbnormalRonchi">
                          <VControl>
                            <VInput type="text" class="input" placeholder="Keterangan..."
                              v-model="input.fisikThoraksAbnormalRonchiKet" />
                          </VControl>
                        </VField>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-0" v-model="input.fisikThoraksAbnormalWheezing" label="Wheezing"
                            color="primary" />
                          </VControl>
                        </VField>
                        <VField v-if="input.fisikThoraksAbnormalWheezing">
                          <VControl>
                            <VInput type="text" class="input" placeholder="Keterangan..."
                              v-model="input.fisikThoraksAbnormalWheezingKet" />
                          </VControl>
                        </VField>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-0" v-model="input.fisikThoraksAbnormalLain" label="Lain-Lain"
                              color="primary" />
                          </VControl>
                        </VField>
                        <VField v-if="input.fisikThoraksAbnormalLain">
                          <VControl>
                            <VInput type="text" class="input" placeholder="Keterangan..."
                              v-model="input.fisikThoraksAbnormalLainKet" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td width="2%">4</td>
                      <td width="20%">Abdomen</td>
                      <td>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-0" v-model="input.fisikAbdomen" label="Normal" color="primary" true-value="Normal"/>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-0" v-model="input.fisikAbdomenAbnormal" label="Abnormal" true-value="Abnormal"
                              color="primary" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-0" v-model="input.fisikAbdomenAbnormalDatarCembung"
                              label="Datar / Cembung" color="primary" />
                          </VControl>
                        </VField>
                        <VField v-if="input.fisikAbdomenAbnormalDatarCembung">
                          <VControl>
                            <VInput type="text" class="input" placeholder="Keterangan..."
                              v-model="input.fisikAbdomenAbnormalDatarCembungKet" />
                          </VControl>
                        </VField>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-0" v-model="input.fisikAbdomenAbnormalBisingUsus" label="Bising Usus"
                            color="primary" />
                          </VControl>
                        </VField>
                        <VField v-if="input.fisikAbdomenAbnormalBisingUsus">
                          <VControl>
                            <VInput type="text" class="input" placeholder="Keterangan..."
                              v-model="input.fisikAbdomenAbnormalBisingUsusKet" />
                          </VControl>
                        </VField>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-0" v-model="input.fisikAbdomenAbnormalNyeriTekan" label="Nyeri Tekan"
                            color="primary" />
                          </VControl>
                        </VField>
                        <VField v-if="input.fisikAbdomenAbnormalNyeriTekan">
                          <VControl>
                            <VInput type="text" class="input" placeholder="Keterangan..."
                              v-model="input.fisikAbdomenAbnormalNyeriTekanKet" />
                          </VControl>
                        </VField>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-0" v-model="input.fisikAbdomenAbnormalPerkusi" label="Perkusi"
                            color="primary" />
                          </VControl>
                        </VField>
                        <VField v-if="input.fisikAbdomenAbnormalPerkusi">
                          <VControl>
                            <VInput type="text" class="input" placeholder="Keterangan..."
                              v-model="input.fisikAbdomenAbnormalPerkusiKet" />
                          </VControl>
                        </VField>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-0" v-model="input.fisikAbdomenAbnormalLain" label="Lain-Lain"
                              color="primary" />
                          </VControl>
                        </VField>
                        <VField v-if="input.fisikAbdomenAbnormalLain">
                          <VControl>
                            <VInput type="text" class="input" placeholder="Keterangan..."
                              v-model="input.fisikAbdomenAbnormalLainKet" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td width="2%">5</td>
                      <td width="20%">Ekstremitas</td>
                      <td>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-0" v-model="input.fisikEkstremitas" label="Normal" true-value="Normal"
                              color="primary" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-0" v-model="input.fisikEkstremitasAbnormal" label="Abnormal" true-value="Abnormal"
                              color="primary" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-0" v-model="input.fisikEkstremitasAbnormalOedem" label="Oedem"
                              color="primary" />
                          </VControl>
                        </VField>
                        <VField v-if="input.fisikEkstremitasAbnormalOedem">
                          <VControl>
                            <VInput type="text" class="input" placeholder="Keterangan..."
                              v-model="input.fisikEkstremitasAbnormalOedemKet" />
                          </VControl>
                        </VField>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-0" v-model="input.fisikEkstremitasAbnormalkukuPucat" label="Kuku Pucat"
                            color="primary" />
                          </VControl>
                        </VField>
                        <VField v-if="input.fisikEkstremitasAbnormalkukuPucat">
                          <VControl>
                            <VInput type="text" class="input" placeholder="Keterangan..."
                              v-model="input.fisikEkstremitasAbnormalkukuPucatKet" />
                          </VControl>
                        </VField>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-0" v-model="input.fisikEkstremitasAbnormalkukuSianosis"
                            label="Kuku Sianosis" color="primary" />
                          </VControl>
                        </VField>
                        <VField v-if="input.fisikEkstremitasAbnormalkukuSianosis">
                          <VControl>
                            <VInput type="text" class="input" placeholder="Keterangan..."
                              v-model="input.fisikEkstremitasAbnormalkukuSianosisKet" />
                          </VControl>
                        </VField>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-0" v-model="input.fisikEkstremitasAbnormalkekuatanOtot"
                            label="Kekuatan Otot" color="primary" />
                          </VControl>
                        </VField>
                        <VField v-if="input.fisikEkstremitasAbnormalkekuatanOtot">
                          <VControl>
                            <VInput type="text" class="input" placeholder="Keterangan..."
                              v-model="input.fisikEkstremitasAbnormalkekuatanOtotKet" />
                          </VControl>
                        </VField>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-0" v-model="input.fisikEkstremitasAbnormalLain" label="Lain-Lain"
                              color="primary" />
                          </VControl>
                        </VField>
                        <VField v-if="input.fisikEkstremitasAbnormalLain">
                          <VControl>
                            <VInput type="text" class="input" placeholder="Keterangan..."
                              v-model="input.fisikEkstremitasAbnormalLainKet" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td width="2%">6</td>
                      <td width="20%">Lainnya</td>
                      <td>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-0" v-model="input.fisikLainnya" label="Normal" true-value="Normal"
                              color="primary" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-0" v-model="input.fisikLainnyaAbnormal" label="Abnormal" true-value="Abnormal"
                              color="primary" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl class="mt-2">
                            <VTextarea rows="3" placeholder="..." v-model="input.fisikLainnyaAbnormalKet">
                            </VTextarea>
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </Fieldset>
          </div>
          <div class="column is-12">
            <Fieldset class="p-fieldsets" legend="Diagnosis" :toggleable="true">
              <div class="columns is-multiline p-3">
                <div class="column">
                  <div class="mt-1">
                    <table width="100%" style="border: 1px solid">
                      <thead>
                        <tr class="tr-fkprj">
                          <th class="td-fkprj" width="2%" style="vertical-align: inherit;text-align: center">NO</th>
                          <th class="td-fkprj" width="50%" style="vertical-align:inherit;text-align: center;">Diangosa ICD
                            10
                          </th>
                          <th class="td-fkprj" rowspan="2" style="vertical-align:inherit;text-align: center;" width="7%">
                            Aksi
                          </th>
                        </tr>
                      </thead>
                      <tbody v-for="(items, index) in input.diagnosaICD10" :key="index">
                        <tr class="tr-fkprj">
                          <td class="td-fkprj" style="vertical-align:inherit;text-align:center">{{ items.no }}</td>
                          <td class="td-fkprj">
                            <div class="columns p-1 is-12">
                              <VField class="column is-4">
                                <VControl>
                                  <VInput type="text" v-model="items.keterangan" placeholder="Diagnosa Dokter" />

                                </VControl>
                              </VField>
                              <VField class="column is-8">
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.ICD10Diagnosa" :suggestions="d_Diagnosa"
                                    @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Diagnosa ICD 10 ..." />
                                </VControl>
                              </VField>
                            </div>
                          </td>
                          <td class="td-fkprj" style="vertical-align: inherit;">
                            <div class="column p-0">
                              <VButtons style="justify-content: space-around;">
                                <VIconButton type="button" raised circle icon="feather:plus"
                                  @click="addNewDiagnosaicd10(items)" color="info">
                                </VIconButton>
                                <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle
                                  icon="feather:trash" @click="removeItemDiagnosaIcd10(index)" color="danger">
                                </VIconButton>
                              </VButtons>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="columns is-multiline p-3">
                <div class="column">
                  <div class="mt-1">
                    <table width="100%" style="border: 1px solid">
                      <thead>
                        <tr class="tr-fkprj">
                          <th class="td-fkprj" width="2%" style="vertical-align: inherit;text-align: center">NO</th>
                          <th class="td-fkprj" width="50%" style="vertical-align:inherit;text-align: center;">Diangosa ICD
                            9
                          </th>
                          <th class="td-fkprj" rowspan="2" style="vertical-align:inherit;text-align: center;" width="7%">
                            Aksi
                          </th>
                        </tr>
                      </thead>
                      <tbody v-for="(items, index) in input.diagnosaICD9" :key="index">
                        <tr class="tr-fkprj">
                          <td class="td-fkprj" style="vertical-align:inherit;text-align:center">{{ items.no }}</td>
                          <td class="td-fkprj">
                            <div class="column p-1">
                              <VField>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.ICD9Diagnosa" :suggestions="d_Diagnosa9"
                                    @complete="fetchDiagnosa9($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Diagnosa ICD 9 ..." class="mt-2" />
                                </VControl>
                              </VField>
                            </div>
                          </td>
                          <td class="td-fkprj" style="vertical-align: inherit;">
                            <div class="column p-0">
                              <VButtons style="justify-content: space-around;">
                                <VIconButton type="button" raised circle icon="feather:plus" @click="addNewDiagnosaicd9()"
                                  color="info">
                                </VIconButton>
                                <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle
                                  icon="feather:trash" @click="removeItemDiagnosaIcd9(index)" color="danger">
                                </VIconButton>
                              </VButtons>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <VField>
                    <h1 style="font-weight: bold;">Masalah :</h1>
                    <VControl class="mt-2">
                      <VTextarea rows="3" placeholder="Masalah..." v-model="input.masalah">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline mb-5">
                <div class="column is-12 P-0">
                  <div class="column is-12">
                    <span> Rencana Terapi : </span>
                  </div>
                  <div class="column is-12">
                    <div class="columns is-multiline">
                      <div class="column is-2">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input.rencanaTerapiO2" label="O2" class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-6" v-if="input.rencanaTerapiO2">
                        <VField>
                          <VControl>
                            <VInput type="text" class="input" placeholder="Keterangan..."
                              v-model="input.rencanaTerapiO2Ket" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-2">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input.rencanaTerapiPemasanganInfusRL"
                              label="Pemasangan Infus RL/ NaCL 0,9%/D5/D10 atau.." class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-6" v-if="input.rencanaTerapiPemasanganInfusRL">
                        <VField>
                          <span>Tetes Per Menit (Makro/Mikro)</span>
                          <VControl>
                            <VInput type="text" class="input" placeholder="Tetes Per Menit (Makro/Mikro)..."
                              v-model="input.rencanaTerapiPemasanganInfusRLTetes" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-2">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input.rencanaTerapiInjeksi" label="Injeksi" class="p-0" color="primary"
                              square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-6" v-if="input.rencanaTerapiInjeksi">
                        <VField>
                          <VControl>
                            <VTextarea rows="2" v-model="input.rencanaTerapiInjeksiKet" placeholder="........"
                              class="input" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-2">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input.rencanaTerapiOral" label="Oral" class="p-0" color="primary"
                              square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-6" v-if="input.rencanaTerapiOral">
                        <VField>
                          <VControl>
                            <VTextarea rows="2" v-model="input.rencanaTerapiOralKet" placeholder="........" class="input">
                            </VTextarea>
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-2">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input.rencanaTerapiDiet" label="Diet" class="p-0" color="primary"
                              square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-6" v-if="input.rencanaTerapiDiet">
                        <VField>
                          <VControl>
                            <VTextarea rows="2" v-model="input.rencanaTerapiDietKet" placeholder="........"
                              class="input" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-2">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input.rencanaTerapiLainLain" label="Lain-Lain" class="p-0" color="primary"
                              square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-6" v-if="input.rencanaTerapiLainLain">
                        <VField>
                          <VControl>
                            <VTextarea rows="2" v-model="input.rencanaTerapiLainLainKet" placeholder="........"
                              class="input" />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <VField>
                    <h1 style="font-weight: bold;">Instruksi :</h1>
                    <VControl class="mt-2">
                      <VTextarea rows="3" placeholder="Instruksi..." v-model="input.instruksi">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <VField>
                    <h1 style="font-weight: bold;">Sasaran :</h1>
                    <VControl class="mt-2">
                      <VTextarea rows="3" placeholder="Sasaran..." v-model="input.sasaran">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <VField>
                    <h1 style="font-weight: bold;">Tindakan/Prosedur :</h1>
                    <VControl class="mt-2">
                      <VTextarea rows="3" placeholder="Tindakan/Prosedur..." v-model="input.tindakanProsedur">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
              </div>
            </Fieldset>
          </div>
          <div class="column is-12">
            <Fieldset :toggleable="true" legend="ORDER LIST dan CATATAN WAKTU HASIL PEMERIKSAAN PENUNJANG & KONSULTASI">
              <div class="column is-12 p-2">
                <div class="columns is-multiline">
                  <div class="column" style="overflow: auto;">
                    <table class="tg">
                      <thead>
                        <tr>
                          <th class="tg-0lax text-center" rowspan="2">Jenis Pemeriksaan</th>
                          <th class="tg-0lax text-center" colspan="2"> Waktu :
                            <VField>
                              <VDatePicker v-model="input.waktuOrderList" mode="date" style="width: 100%" trim-weeks
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
                          <th class="tg-0lax text-center" rowspan="2">Keterangan</th>
                          <th class="tg-0lax text-center" rowspan="2">ICD 9</th>
                        </tr>
                        <tr>
                          <th class="tg-0lax">Pemeriksaan/Permintaan</th>
                          <th class="tg-0lax">Hasil</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td width="200px">
                            EKG
                            <div class="column is-12">
                              <VButton color="warning" class="w-100 btn-slim" light rounded outlined
                                v-tooltip-prime.right="'Berkas Pasien'" @click="add()"> Berkas Pasien </VButton>
                            </div>
                            <!-- <p>
                              <VButton bold fullwidth dark-outlined raised color="primary" @click="add()">Berkas Pasien</VButton>
                            </p> -->
                          </td>
                          <td width="250px">
                            <VField>
                              <VControl class="mt-2">
                                <VTextarea rows="3" placeholder="......" v-model="input.pemeriksaanPermintaanEKG">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </td>
                          <td width="250px">
                            <VField>
                              <VControl class="mt-2">
                                <VTextarea rows="3" placeholder="......" v-model="input.hasilEKG">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </td>
                          <td width="230px">
                            <VField>
                              <VControl class="mt-2">
                                <VTextarea rows="3" placeholder="......" v-model="input.keteranganEKG">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </td>
                          <td width="250px">
                            <VField>
                              <VControl class="prime-auto">
                                <AutoComplete v-model="input.ICD9DiagnosaEKG" :suggestions="d_Diagnosa9"
                                  @complete="fetchDiagnosa9($event)" :optionLabel="'label'" :dropdown="true"
                                  :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                  placeholder="Diagnosa ICD 9 ..." class="mt-2" />
                              </VControl>
                            </VField>
                          </td>
                        </tr>
                        <tr>
                          <td width="200px">
                            <div class="column is-12">
                              <VButton color="primary" class="w-100 btn-slim" light rounded outlined
                                v-tooltip-prime.right="'Laboratorium'" @click="isLab = true"> Laboratorium </VButton>
                            </div>
                          </td>
                          <td width="250px">
                            <VField>
                              <VControl class="mt-2">
                                <VTextarea rows="3" placeholder="......" v-model="input.pemeriksaanPermintaanLab">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </td>
                          <td width="250px">
                            <VField>
                              <VControl class="mt-2">
                                <VTextarea rows="3" placeholder="......" v-model="input.hasilLab">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </td>
                          <td width="230px">
                            <VField>
                              <VControl class="mt-2">
                                <VTextarea rows="3" placeholder="......" v-model="input.keteranganLab">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </td>
                          <td width="250px">
                            <VField>
                              <VControl class="prime-auto">
                                <AutoComplete v-model="input.ICD9DiagnosaLab" :suggestions="d_Diagnosa9"
                                  @complete="fetchDiagnosa9($event)" :optionLabel="'label'" :dropdown="true"
                                  :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                  placeholder="Diagnosa ICD 9 ..." class="mt-2" />
                              </VControl>
                            </VField>
                          </td>
                        </tr>
                        <tr>
                          <td width="200px">
                            <div class="column is-12">
                              <VButton color="warning" class="w-100 btn-slim" light rounded outlined
                                v-tooltip-prime.right="'Radiologi'" @click="isRad = true"> Radiologi </VButton>
                            </div>
                          </td>
                          <td width="250px">
                            <VField>
                              <VControl class="mt-2">
                                <VTextarea rows="3" placeholder="......" v-model="input.pemeriksaanPermintaanRadiologi">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </td>
                          <td width="250px">
                            <VField>
                              <VControl class="mt-2">
                                <VTextarea rows="3" placeholder="......" v-model="input.hasilRadiologi">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </td>
                          <td width="230px">
                            <VField>
                              <VControl class="mt-2">
                                <VTextarea rows="3" placeholder="......" v-model="input.keteranganRadiologi">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </td>
                          <td width="250px">
                            <VField>
                              <VControl class="prime-auto">
                                <AutoComplete v-model="input.ICD9DiagnosaRadiologi" :suggestions="d_Diagnosa9"
                                  @complete="fetchDiagnosa9($event)" :optionLabel="'label'" :dropdown="true"
                                  :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                  placeholder="Diagnosa ICD 9 ..." class="mt-2" />
                              </VControl>
                            </VField>
                          </td>
                        </tr>
                        <tr>
                          <td width="200px">
                            <div class="column is-12">
                              <VButton color="success" class="w-100 btn-slim" light rounded outlined
                                v-tooltip-prime.right="'Konsul'" @click="isKonsul = true"> Konsul </VButton>
                            </div>
                          </td>
                          <td width="250px">
                            <VField>
                              <VControl class="mt-2">
                                <VTextarea rows="3" placeholder="......" v-model="input.pemeriksaanPermintaanKonsultasi">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </td>
                          <td width="250px">
                            <VField>
                              <VControl class="mt-2">
                                <VTextarea rows="3" placeholder="......" v-model="input.hasilKonsultasi">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </td>
                          <td width="230px">
                            <VField>
                              <VControl class="mt-2">
                                <VTextarea rows="3" placeholder="......" v-model="input.keteranganKonsultasi">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </td>
                          <td width="250px">
                            <VField>
                              <VControl class="prime-auto">
                                <AutoComplete v-model="input.ICD9DiagnosaKonsultasi" :suggestions="d_Diagnosa9"
                                  @complete="fetchDiagnosa9($event)" :optionLabel="'label'" :dropdown="true"
                                  :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                  placeholder="Diagnosa ICD 9 ..." class="mt-2" />
                              </VControl>
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
          <div clas="mt-5">
              <div class="column is-6">
                <h1 style="font-weight: bold;"> Cirebon, Tanggal </h1>
                <VField class="mt-3">
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
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <img v-if="input.dpjpIGD"
                  :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.dpjpIGD.label ? input.dpjpIGD.label : input.dpjpIGD) + ', ' + (input.tglPembuatan ? input.tglPembuatan : input.tglPembuatan)">
                    <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
                  </div>
                  <div class="column is-6 ">
                    <img v-if="input.ppjpIGD"
                  :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.ppjpIGD ? input.ppjpIGD.label : '-')">
                    <!-- <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan> -->
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
                  <!-- <div class="column is-4 is-offset-2">
                    <h1 class="p-0" style="font-weight: bold;">PPJP Triase</h1>
                    <VField>
                      <VControl class="prime-auto">
                        <AutoComplete v-model="input.ppjpIGD" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="PPJP IGD..." class="mt-2"
                          @item-select="setTandaTangan($event, 'signature_2')" />
                      </VControl>
                    </VField>
                  </div> -->
                </div>
              </div>
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
import ButtonEmr from '../page-emr-plugins/button-emr-igd.vue'
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
const COLLECTION: any = ref('pengkajianDokterGawatDarurat') //table mongodb
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
  // if (NOREC_EMRPASIEN.value == '') return
  useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        console.log(response[0])
        response.forEach(element => {
          input.value = element //set ke inputan
        });
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
  input.value.dpjpIGD = props.registrasi.dokter
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
