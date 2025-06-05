<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
            <div v-if="isStuck" :class="[isStuck && 'is-stuck']" style="margin-top:50px; padding: 0px 0px !important;" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ props.FORM_NAME }}</h3>
                    </div>
                    <div class="right">
                        <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan"
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
                        <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan"
                        @kembaliKeun="kembaliKeun"></ButtonEmr>
                    </div>
                </div>
            </div>

    </div>
  </div>

  <div class="column is-12">
    <VCard>
      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-4">
            <span class="label-apas">Tanggal Dan Jam</span>
            <VField class="column is-10 p-0 mt-3">
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
          <div class="column is-4">
            <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Diagnosis </h1>
            <VField>
              <VControl>
                <AutoComplete v-model="input.diagnosaPraBedah" :suggestions="d_Diagnosa"
                  @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Diagnosa" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Rencana Tindakan </h1>
            <VField class="pt-3">
              <VControl>
                <VTextarea v-model="input.rencananTindakan" rows="2" placeholder="Rencana Tindakan">
                </VTextarea>
              </VControl>
            </VField>
          </div>
        </div>
      </div>
    </VCard>
  </div>
  <div class="column">
    <div class="column">
      <Fieldset :toggleable="true" legend="Subjective">
        <div class="column">
          <div class="column is-12">
            <div class="columns is-multiline">
              <h1 style="font-weight: bold; margin-bottom: 1rem;">Riwayat Operasi</h1>
              <div class="column is-2 mb-3">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.riwyatOperasiTidak" label="Tidak" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2 mb-3">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.riwyatOperasiYa" label="Ya, bila Ya" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 mb-3" v-if="input.riwyatOperasiYa">
                <VField>
                  <VControl>
                    <VInput type="text" v-model="input.riwyatOperasiKet" />
                  </VControl>
                </VField>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 1rem;">Hipertensi</h1>
              <div class="column is-2 mb-3">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.hipertensiTidak" label="Tidak" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2 mb-3">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.hipertensiYa" label="Ya, bila Ya" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 mb-3" v-if="input.hipertensiYa">
                <VField>
                  <VControl>
                    <VInput type="text" v-model="input.hipertensiKet" />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-6 mb-3">
                <h1 style="font-weight: bold; margin-bottom: 1rem;">Penyakit yang pernah diderita</h1>
                <VField>
                  <VControl>
                    <VTextarea v-model="input.penyakitYangpernahDiderita" placeholder="Penyakit Yang Pernah Diderita"
                      rows="2">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
              <div class="column is-6 mb-3">
                <h1 style="font-weight: bold; margin-bottom: 1rem;">Pengobatan</h1>
                <VField>
                  <VControl>
                    <VTextarea v-model="input.pengobatan" placeholder="Pengobatan" rows="2">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <h1 style="font-weight: bold; margin-bottom: 1rem;">ASN</h1>
            </div>
            <div class="columns is-multiline">
              <div class="column is-2 mb-3">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.asnTidak" label="Tidak" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2 mb-3">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.asnYa" label="Ya, bila Ya" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 mb-3" v-if="input.asnYa">
                <VField>
                  <h1>Pengobatan</h1>
                  <VControl>
                    <VInput type="text" v-model="input.asnPengobatan" />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <h1 style="font-weight: bold; margin-bottom: 1rem;">Merokok</h1>
            </div>
            <div class="columns is-multiline">
              <div class="column is-2 mb-3">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.merokokTidak" label="Tidak" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2 mb-3">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.merokokYa" label="Ya, bila Ya" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 mb-3" v-if="input.merokokYa">
                <VField>
                  <h1>Pengobatan</h1>
                  <VControl>
                    <VInput type="text" v-model="input.merokokPengobatan" />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <h1 style="font-weight: bold; margin-bottom: 1rem;">Alergi</h1>
            </div>
            <div class="columns is-multiline">
              <div class="column is-2 mb-3">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.alergiTidak" label="Tidak" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2 mb-3">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.alergiYa" label="Ya, bila Ya" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 mb-3" v-if="input.alergiYa">
                <VField>
                  <h1>Makanan</h1>
                  <VControl>
                    <VInput type="text" v-model="input.alergiMakanan" />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <h1 style="font-weight: bold; margin-bottom: 1rem;">Gastritis</h1>
            </div>
            <div class="columns is-multiline">
              <div class="column is-2 mb-3">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.gastritisTidak" label="Tidak" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2 mb-3">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.gastritisYa" label="Ya, bila Ya" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 mb-3" v-if="input.gastritisYa">
                <VField>
                  <h1>Pengobatan</h1>
                  <VControl>
                    <VInput type="text" v-model="input.gastritisMakanan" />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <h1 style="font-weight: bold; margin-bottom: 1rem;">Diabetes</h1>
            </div>
            <div class="columns is-multiline">
              <div class="column is-2 mb-3">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.diabetesTidak" label="Tidak" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2 mb-3">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.diabetesYa" label="Ya, bila Ya" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 mb-3" v-if="input.diabetesYa">
                <VField>
                  <h1>Pengobatan</h1>
                  <VControl>
                    <VInput type="text" v-model="input.diabetesPengobatan" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 mb-3" v-if="input.diabetesYa">
                <VField>
                  <h1>Obat yang sedang didapat</h1>
                  <VControl>
                    <VTextarea v-model="input.diabetesObatYangDidapat" placeholder="Obat yang sedang didapat" rows="2">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-6 mb-3">
                <VField>
                  <h1>Lain-lain</h1>
                  <VControl>
                    <VTextarea v-model="input.lainLain" placeholder="Lain-lain" rows="2">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </div>
      </Fieldset>
    </div>
    <div class="column is-12">
      <Fieldset class="p-fieldsets" legend="Objective" :toggleable="true">
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
          <div class="columns is-multiline p-3">
            <div class="column is-3">
              <div class=" columns is-multiline">
                <div class="column is-12" style="margin-top:0.5rem">
                  <span> TB : </span>
                </div>
                <div class="column is-12">
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" placeholder="TB" v-model="input.TB" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>CM</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-3">
              <div class=" columns is-multiline">
                <div class="column is-12" style="margin-top:0.5rem">
                  <span> BB : </span>
                </div>
                <div class="column is-12">
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" placeholder="BB" v-model="input.BB" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>Kg</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-3">
              <div class=" columns is-multiline">
                <div class="column is-12" style="margin-top:0.5rem">
                  <span> TD : </span>
                </div>
                <div class="column is-12">
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" placeholder="TD" v-model="input.TD" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>mmHG</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-3">
              <div class=" columns is-multiline">
                <div class="column is-12" style="margin-top:0.5rem">
                  <span> N : </span>
                </div>
                <div class="column is-12">
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" placeholder="N" v-model="input.N" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>x/mnt</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-3">
              <div class=" columns is-multiline">
                <div class="column is-12" style="margin-top:0.5rem">
                  <span> LN : </span>
                </div>
                <div class="column is-12">
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" placeholder="LN" v-model="input.LN" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static></VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-3">
              <div class=" columns is-multiline">
                <div class="column is-12" style="margin-top:0.5rem">
                  <span> Skor Nyeri : </span>
                </div>
                <div class="column is-12">
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Skor Nyeri" v-model="input.SkorNyeri" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static></VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-3">
              <div class=" columns is-multiline">
                <div class="column is-12" style="margin-top:0.5rem">
                  <span> S : </span>
                </div>
                <div class="column is-12">
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" placeholder="S" v-model="input.S" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static><sup>o</sup>C</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="columns is-multiline ml-3 mt-5">
          <div class="column is-3 mb-3">
            <h1 style="font-weight: bold; margin-bottom: 1rem;">Jalan Napas</h1>
          </div>
          <div class="column is-3 mb-3">
            <VField>
              <VControl>
                <VInput type="text" placeholder="Jalan Napas" v-model="input.jalanNapas" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="columns is-multiline ml-3 mt-2">
          <div class="column is-3 mb-3">
            <h1 style="font-weight: bold; margin-bottom: 1rem;">Mallampati</h1>
          </div>
          <div class="column is-3 mb-3">
            <VField>
              <VControl>
                <VInput type="text" placeholder="Malllampati" v-model="input.mallampati" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="columns is-multiline ml-3 mt-2">
          <div class="column is-3 mb-3">
            <h1 style="font-weight: bold; margin-bottom: 1rem;">Buka Mulut</h1>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.bukaMulutLebih3Jari" label="> 3 Jari" class="p-0" color="primary" square />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.bukaMulutkurang3Jari" label="< 3 Jari" class="p-0" color="primary" square />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="columns is-multiline ml-3 mt-2">
          <div class="column is-3 mb-3">
            <h1 style="font-weight: bold; margin-bottom: 1rem;">Gigi</h1>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.gigiKomplit" label="Komplit" class="p-0" color="primary" square />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.gigiGoyang" label="Goyang" class="p-0" color="primary" square />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.gigiPalsu" label="Gigi Palsu" class="p-0" color="primary" square />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="columns is-multiline ml-3 mt-2">
          <div class="column is-3 mb-3">
            <h1 style="font-weight: bold; margin-bottom: 1rem;">Leher</h1>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.leherMobile" label="Mobile" class="p-0" color="primary" square />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.leherTerbatas" label="Terbatas" class="p-0" color="primary" square />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.Trauma" label="Trauma" class="p-0" color="primary" square />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="columns is-multiline ml-3 mt-2">
          <div class="column is-3 mb-3">
            <h1 style="font-weight: bold; margin-bottom: 1rem;">Paru</h1>
          </div>
          <div class="column is-3 mb-3">
            <VField>
              <VControl>
                <VInput type="text" placeholder="Paru" v-model="input.paru" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="columns is-multiline ml-3 mt-2">
          <div class="column is-3 mb-3">
            <h1 style="font-weight: bold; margin-bottom: 1rem;">Jantung</h1>
          </div>
          <div class="column is-3 mb-3">
            <VField>
              <VControl>
                <VInput type="text" placeholder="Jantung" v-model="input.jantung" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="columns is-multiline ml-3 mt-2">
          <div class="column is-3 mb-3">
            <h1 style="font-weight: bold; margin-bottom: 1rem;">Abdomen</h1>
          </div>
          <div class="column is-3 mb-3">
            <VField>
              <VControl>
                <VInput type="text" placeholder="Abdomen" v-model="input.abdomen" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="columns is-multiline ml-3 mt-2">
          <div class="column is-3 mb-3">
            <h1 style="font-weight: bold; margin-bottom: 1rem;">Ekstremitass</h1>
          </div>
          <div class="column is-3 mb-3">
            <VField>
              <VControl>
                <VInput type="text" placeholder="Ekstremitass" v-model="input.eksterimitass" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="columns is-multiline ml-3 mt-2">
          <div class="column is-3 mb-3">
            <h1 style="font-weight: bold; margin-bottom: 1rem;">Lain-lain</h1>
          </div>
          <div class="column is-3 mb-3">
            <VField>
              <VControl>
                <VInput type="text" placeholder="Lain-lain" v-model="input.lainLainObjective" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column mt-3">
          <span class="label-apas">Pemeriksaan Penunjang</span>
        </div>
        <div class="columns is-multiline pl-5 pr-5">
          <div class="column is-3 mt-3">
            <VButton color="primary" class="w-100 btn-slim" light rounded outlined
              v-tooltip-prime.right="'Laboratorium'" @click="isLab = true"> Laboratorium </VButton>
          </div>
          <div class="column is-6">
            <VField class="pt-3">
              <VControl>
                <VTextarea v-model="input.pemeriksaanPenunjangLaboratorium" placeholder="Laboratorium" rows="2">
                </VTextarea>
              </VControl>
            </VField>
          </div>
        </div>
        <div class="columns is-multiline pl-5 pr-5">
          <div class="column is-3 mt-3">
            <span class="label-apas">EKG</span>
          </div>
          <div class="column is-6">
            <VField class="pt-3">
              <VControl>
                <VTextarea v-model="input.pemeriksaanPenunjangEKG" placeholder="EKG" rows="2">
                </VTextarea>
              </VControl>
            </VField>
          </div>
        </div>
        <div class="columns is-multiline pl-5 pr-5">
          <div class="column is-3 mt-3">
            <span class="label-apas">Rontgen</span>
          </div>
          <div class="column is-6">
            <VField class="pt-3">
              <VControl>
                <VTextarea v-model="input.pemeriksaanPenunjangRontgen" placeholder="Rontgen" rows="2">
                </VTextarea>
              </VControl>
            </VField>
          </div>
        </div>
        <div class="columns is-multiline pl-5 pr-5">
          <div class="column is-3 mt-3">
            <span class="label-apas">Lain-lain</span>
          </div>
          <div class="column is-6">
            <VField class="pt-3">
              <VControl>
                <VTextarea v-model="input.pemeriksaanPenunjangLainlain" placeholder="Lain-lain" rows="2">
                </VTextarea>
              </VControl>
            </VField>
          </div>
        </div>
        <div class="columns is-multiline pl-5 pr-5">
          <div class="column is-3 mt-3">
            <span class="label-apas">Hasil Konsultasi Bagian Lain</span>
          </div>
          <div class="column is-8">
            <VField class="pt-3">
              <VControl>
                <VTextarea v-model="input.hasilKonsultasiBagianLain" placeholder="Hasil Konsultasi Bagian Lain"
                  rows="2">
                </VTextarea>
              </VControl>
            </VField>
          </div>
        </div>
      </Fieldset>
    </div>

    <div class="column">
      <Fieldset :toggleable="true" legend="Assesment">
        <div class="column">
          <div class="columns is-multiline">
            <div class="column" v-for="(data) in analisaFisik">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label" color="primary"
                    square />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </Fieldset>
    </div>
    <div class="column">
      <Fieldset :toggleable="true" legend="Planning">
        <div class="columns is-multiline ml-3 mt-2">
          <div class="column is-3 mb-3">
            <h1 style="font-weight: bold; margin-bottom: 1rem;">Setuju/tidak setuju untuk dilakukan tindakan
              anestesi/sedasi</h1>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.setujuDilkukanAnestesiSetuju" label="Setuju" class="p-0" color="primary"
                  square />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.setujuDilkukanAnestesiTidakSetuju" label="Tidak Setuju" class="p-0"
                  color="primary" square />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="columns is-multiline ml-3 mt-2">
          <div class="column is-3 mb-3">
            <h1 style="font-weight: bold; margin-bottom: 1rem;">Preoperatif</h1>
          </div>
          <div class="column is-2 mb-3">
            <span class="label-apas">Puasa mulai jam :</span>
            <VDatePicker class="column is-7 p-0 mt-3" v-model="input.puasaMulaijam" color="green" mode="time" is24hr>
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl>
                    <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
          <div class="column is-2 mb-3">
            <span class="label-apas">Premedikasi :</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.premedikasi" placeholder="Premedikasi" />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <span class="label-apas">Lain-lain</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.premedikasiLainLain" placeholder="Lain-lain" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="columns is-multiline ml-3 mt-2">
          <div class="column is-3 mb-3">
            <h1 style="font-weight: bold; margin-bottom: 1rem;">Intraoperatif</h1>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.IntraoperatifGA" label="GA" class="p-0" color="primary" square />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.IntraoperatifRegional" label="Regional" class="p-0" color="primary" square />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.IntraoperatifKombinasi" label="Kombinasi" class="p-0" color="primary"
                  square />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.IntraoperatifSedasi" label="Sedasi" class="p-0" color="primary" square />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.IntraoperatifMAC" label="MAC" class="p-0" color="primary" square />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="columns is-multiline ml-3 mt-2">
          <div class="column is-3 mb-3">
            <h1 style="font-weight: bold; margin-bottom: 1rem;">Postoperatif</h1>
          </div>
          <div class="column is-3 mb-3">
            <span class="label-apas">Rencanan penanganan nyeri :</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.postoperatifRencanaPenangananNyeri"
                  placeholder="Rencanan Penanganan Nyeri" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3 mb-3">
            <span class="label-apas">Perawatan pasca operatif :</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.postoperatifPerawatanPascaOperatif"
                  placeholder="Perawatan pasca operatif" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="columns is-multiline ml-3 mt-2">
          <div class="column is-3 mb-3">
            <h1 style="font-weight: bold; margin-bottom: 1rem;">Surat Ijin Anastesi</h1>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.suratIjinAnastesiYa" label="Ya" class="p-0" color="primary" square />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.suratIjinAnastesiTidak" label="Tidak" class="p-0" color="primary" square />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="columns is-multiline ml-3 mt-2">
          <div class="column is-3 mb-3">
            <h1 style="font-weight: bold; margin-bottom: 1rem;">Edukasi</h1>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.edukasiYa" label="Ya" class="p-0" color="primary" square />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.edukasiTidak" label="Tidak" class="p-0" color="primary" square />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="columns is-multiline ml-3 mt-2">
          <div class="column is-3 mb-3">
            <h1 style="font-weight: bold; margin-bottom: 1rem;">Produk darah bila diperlukan</h1>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.produkDarahYa" label="Ya, Bila Ya" class="p-0" color="primary" square />
              </VControl>
            </VField>
          </div>
          <div class="column is-3 mb-3" v-if="input.produkDarahYa">
            <VField>
              <VControl>
                <VInput type="text" v-model="input.produkDarahKet" />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.produkDarahTidak" label="Tidak" class="p-0" color="primary" square />
              </VControl>
            </VField>
          </div>
        </div>
      </Fieldset>
    </div>

    <div class="column">
      <VCard>
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-6">
              <div class="column is-5">
                <span class="label-apas">Cirebon</span>
                <VDatePicker v-model="input.tglDibuat" mode="dateTime" style="width: 100%" trim-weeks
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField class="pt-3">
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </div>

              <div class="column is-7" style="text-align: center;">
                <img v-if="input.dokter"
                  :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.dokter.label ? input.dokter.label : input.dokter)">
                <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
              </div>
              <div class="column is-7">
                <span class="label-apas">Dokter</span>
                <VField class="pt-3">
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      @item-select="setTandaTanganDokter($event)" :loadingIcon="'pi pi-spinner'" :field="'label'"
                      placeholder="Cari Dokter..." />
                  </VControl>
                </VField>
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
import * as EMR from '../page-emr-plugins/assesment-pra-anestesi-sedasi'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import Dialog from 'primevue/dialog';
import OrderLab from './order-laboratorium.vue'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let statusAnestesi = ref(EMR.statusAnestesi())
let asesmenSedasi = ref(EMR.asesmenSedasi())
let fisikDanPenunjang = ref(EMR.fisikDanPenunjang())
let pemrksPenunjang = ref(EMR.pemrksPenunjang())
let analisaFisik = ref(EMR.analisaFisik())
let rencanaAnestesi = ref(EMR.rencanaAnestesi())
let alatKhusus = ref(EMR.alatKhusus())

const isLab: any = ref(false)
const d_Diagnosa: any = ref([])
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
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
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
  tglDanJam: new Date(),
  puasaMulaijam: new Date(),
  minumTerakhir: new Date(),
  tglDibuat: new Date()
})
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

const setTandaTanganDokter = async (e: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signatureDokter", element.ttd)
    } else {
      H.tandaTangan().set("signatureDokter", '')
    }
  })
}


const fetchDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa&query=${filter.query}&limit=10`)
  d_Diagnosa.value = response
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

const kembaliKeun = () => {
  window.history.back()
}

const tutupLabSetAutofill = async () => {
  isLab.value = false;
  setAutoFill()
}
const setAutoFill = async () => {
  input.value.tanggalWaktuRegistrasi = props.registrasi.tglregistrasi
  input.value.tanggalWaktuKunjuangan = new Date()
  input.value.tglPembuatan = new Date()
  input.value.umur = props.pasien.umur
  input.value.namaruangan = props.registrasi.namaruangan
  input.value.dokter = props.registrasi.dokter
  input.value.asnTidak = true
  input.value.merokokTidak = true
  input.value.alergiTidak = true
  input.value.gastritisTidak = true
  input.value.diabetesTidak = true

  let lab = ''
  let hasillabemr = ''
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
        input.value.pemeriksaanPenunjangLaboratorium = ref(lab)
      }
    })
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
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=VitalSign" + "&field=tekananDarah,tinggiBadan,beratBadan,nadi,suhu"
  ).then((response) => {
    if (response != null) {
      input.value.TD = response.tekananDarah
      input.value.TB = response.tinggiBadan
      input.value.BB = response.beratBadan
      input.value.N = response.nadi
      input.value.S = response.suhu
    }
  })
}
setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
.label-apas {
  font-weight: 500;
}

.p-fieldset-legend {
  margin-left: 15px;
}
</style>
