<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div
        v-if="isStuck"
        :class="[isStuck && 'is-stuck']"
        style="margin-top: 50px; padding: 0px 0px !important"
        class="form-header stuck-header"
      >
        <div class="form-header-inner">
          <div class="left">
            <h3>{{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr
              :NOREC_EMRPASIEN="NOREC_EMRPASIEN"
              :COLLECTION="COLLECTION"
              :isLoading="isLoading"
:isDisableSimpanButton="props.pasien.isDisableSimpanButton"
              @simpan="simpan"
              @kembaliKeun="kembaliKeun"
            ></ButtonEmr>
          </div>
        </div>
      </div>
      <div v-if="!isStuck" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>{{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr
              :NOREC_EMRPASIEN="NOREC_EMRPASIEN"
              :COLLECTION="COLLECTION"
              :isLoading="isLoading"
:isDisableSimpanButton="props.pasien.isDisableSimpanButton"
              @simpan="simpan"
              @kembaliKeun="kembaliKeun"
            ></ButtonEmr>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="column">
    <VCard>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="A. ANAMNESIS" :toggleable="true">
          <div class="columns is-multiline" v-for="(data, index) in anamnesis">
            <div :class="'column p-3 is-' + data.column" v-if="data.type == 'textarea'">
              <p class="mb-2">{{ data.label }}</p>
              <VField>
                <VControl>
                  <VTextarea
                    v-model="input[data.model]"
                    rows="3"
                    :placeholder="data.placeholder"
                  >
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-12 p-3" v-if="data.type == 'checkbox'">
              <div class="column is-12" style="margin-top: 0.5rem">
                <span> {{ data.label }} : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(checkBox, check) in data.children">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox
                          v-model="input.statusGizi"
                          :true-value="checkBox.value"
                          :label="checkBox.label"
                          class="p-0"
                          color="primary"
                          square
                        />
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
        <Fieldset class="p-fieldsets" legend="B. PEMERIKSAAN FISIK" :toggleable="true">
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top: 0.5rem">
                <span class="bold-text"> KEADAAN UMUM :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in KeadaanUmum">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox
                          v-model="input[data.model]"
                          :label="data.label"
                          class="p-0"
                          color="primary"
                          square
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top: 0.5rem">
                <span class="bold-text"> KESADARAN :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in Kesadaran">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox
                          v-model="input[data.model]"
                          :label="data.label"
                          class="p-0"
                          color="primary"
                          square
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top: 0.5rem">
                <span class="bold-text"> KEPALA :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in kepala">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox
                          v-model="input.kepala"
                          :true-value="data.value"
                          :label="data.label"
                          class="p-0"
                          color="primary"
                          square
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput
                          type="text"
                          class="input"
                          placeholder="............... "
                          v-model="input.ketKepala"
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top: 0.5rem">
                <span class="bold-text"> MATA :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in mata">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox
                          v-model="input.mata"
                          :true-value="data.value"
                          :label="data.label"
                          class="p-0"
                          color="primary"
                          square
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput
                          type="text"
                          class="input"
                          placeholder="............... "
                          v-model="input.ketMata"
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top: 0.5rem">
                <span class="bold-text"> HIDUNG :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in hidung">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox
                          v-model="input.hidung"
                          :true-value="data.value"
                          :label="data.label"
                          class="p-0"
                          color="primary"
                          square
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput
                          type="text"
                          class="input"
                          placeholder="............... "
                          v-model="input.ketHidung"
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top: 0.5rem">
                <span class="bold-text"> GIGI & MULUT :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in gigiMulut">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox
                          v-model="input.gigiMulut"
                          :true-value="data.value"
                          :label="data.label"
                          class="p-0"
                          color="primary"
                          square
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput
                          type="text"
                          class="input"
                          placeholder="............... "
                          v-model="input.ketGigiMulut"
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top: 0.5rem">
                <span class="bold-text"> TENGGOROKAN :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in tenggorokan">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox
                          v-model="input.tenggorokan"
                          :true-value="data.value"
                          :label="data.label"
                          class="p-0"
                          color="primary"
                          square
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput
                          type="text"
                          class="input"
                          placeholder="............... "
                          v-model="input.ketTenggorokan"
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top: 0.5rem">
                <span class="bold-text"> TELINGA :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in telinga">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox
                          v-model="input.telinga"
                          :true-value="data.value"
                          :label="data.label"
                          class="p-0"
                          color="primary"
                          square
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput
                          type="text"
                          class="input"
                          placeholder="............... "
                          v-model="input.ketTelinga"
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top: 0.5rem">
                <span class="bold-text"> LEHER :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in leher">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox
                          v-model="input.leher"
                          :true-value="data.value"
                          :label="data.label"
                          class="p-0"
                          color="primary"
                          square
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput
                          type="text"
                          class="input"
                          placeholder="............... "
                          v-model="input.ketLeher"
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top: 0.5rem">
                <span class="bold-text"> THORAKS :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in thoraks">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox
                          v-model="input.thoraks"
                          :true-value="data.value"
                          :label="data.label"
                          class="p-0"
                          color="primary"
                          square
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput
                          type="text"
                          class="input"
                          placeholder="............... "
                          v-model="input.ketThoraks"
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top: 0.5rem">
                <span class="bold-text"> JANTUNG :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in jantung">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox
                          v-model="input.jantung"
                          :true-value="data.value"
                          :label="data.label"
                          class="p-0"
                          color="primary"
                          square
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput
                          type="text"
                          class="input"
                          placeholder="............... "
                          v-model="input.ketJantung"
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top: 0.5rem">
                <span class="bold-text"> PARU :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in paru">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox
                          v-model="input.paru"
                          :true-value="data.value"
                          :label="data.label"
                          class="p-0"
                          color="primary"
                          square
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput
                          type="text"
                          class="input"
                          placeholder="............... "
                          v-model="input.ketParu"
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top: 0.5rem">
                <span class="bold-text"> ABDOMEN :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in abdomen">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox
                          v-model="input.abdomen"
                          :true-value="data.value"
                          :label="data.label"
                          class="p-0"
                          color="primary"
                          square
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput
                          type="text"
                          class="input"
                          placeholder="............... "
                          v-model="input.ketAbdomen"
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top: 0.5rem">
                <span class="bold-text"> GENITALIA & ANUS :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in genitaliaAnus">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox
                          v-model="input.genitaliaAnus"
                          :true-value="data.value"
                          :label="data.label"
                          class="p-0"
                          color="primary"
                          square
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput
                          type="text"
                          class="input"
                          placeholder="............... "
                          v-model="input.ketGenitaliaAnus"
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top: 0.5rem">
                <span class="bold-text"> EKSTREMITAS :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in ekstremitas">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox
                          v-model="input.ekstremitas"
                          :true-value="data.value"
                          :label="data.label"
                          class="p-0"
                          color="primary"
                          square
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput
                          type="text"
                          class="input"
                          placeholder="............... "
                          v-model="input.ketEkstremitas"
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top: 0.5rem">
                <span class="bold-text"> KULIT :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in kulit">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox
                          v-model="input.kulit"
                          :true-value="data.value"
                          :label="data.label"
                          class="p-0"
                          color="primary"
                          square
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput
                          type="text"
                          class="input"
                          placeholder="............... "
                          v-model="input.ketKulit"
                        />
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
        <Fieldset
          class="p-fieldsets"
          legend="C. HASIL PEMERIKSAAN PENUNJANG"
          :toggleable="true"
        >
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12">
                <span> Laboratorium : </span>
                <VField>
                  <VTextarea
                    rows="2"
                    placeholder="Laboratorium....."
                    v-model="input.laboratorium"
                  ></VTextarea>
                </VField>
              </div>
              <div class="column is-12">
                <span> Radiologi : </span>
                <VField>
                  <VTextarea
                    rows="2"
                    placeholder="Radiologi......"
                    v-model="input.radiologi"
                  >
                  </VTextarea>
                </VField>
              </div>
              <div class="column is-12">
                <span> Penunjang Lain : </span>
                <VField>
                  <VTextarea
                    rows="2"
                    placeholder="Penunjang Lain......"
                    v-model="input.penunjangLain"
                  >
                  </VTextarea>
                </VField>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="D. DIAGNOSIS/ASSESMENT" :toggleable="true">
          <!-- <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12">
                <VField>
                  <VTextarea
                    rows="3"
                    placeholder="....."
                    v-model="input.diagnosisAssesment"
                  ></VTextarea>
                </VField>
              </div>
            </div>
          </div> -->
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
        <Fieldset
          class="p-fieldsets"
          legend="E. MASALAH MEDIS DAN KEPERAWATAN"
          :toggleable="true"
        >
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12">
                <VField>
                  <VTextarea
                    rows="3"
                    placeholder="....."
                    v-model="input.masalahMedisKeperawatan"
                  ></VTextarea>
                </VField>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset
          class="p-fieldsets"
          legend="F. TATA LAKSANA (EDUKASI, PEMERIKSAAN DIAGNOSTIK, TERAPI)"
          :toggleable="true"
        >
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12">
                <VField>
                  <VTextarea
                    rows="3"
                    placeholder="....."
                    v-model="input.tataLaksana"
                  ></VTextarea>
                </VField>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="G. INSTRUKSI" :toggleable="true">
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12">
                <VField>
                  <VTextarea
                    rows="3"
                    placeholder="....."
                    v-model="input.instruksi"
                  ></VTextarea>
                </VField>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="H. SASARAN" :toggleable="true">
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12">
                <VField>
                  <VTextarea
                    rows="3"
                    placeholder="....."
                    v-model="input.sasaran"
                  ></VTextarea>
                </VField>
              </div>
              <div class="column is-4">
                <span> Rencana Lamat Rawat : </span>
                <VField>
                  <VTextarea
                    rows="1"
                    placeholder="Rencana Lama Rawat......"
                    v-model="input.rencanaLamaRawat"
                  >
                  </VTextarea>
                </VField>
              </div>
              <div class="column is-4">
                <span> Rencana Tanggal Pulang : </span>
                <VField>
                  <VDatePicker
                    v-model="input.tanggalRencanaPulang"
                    mode="date"
                    style="width: 100%"
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
              <div class="column is-4 mt-5">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox
                      v-model="input.belumBisaDitetapkan"
                      true-value="belumBisaDitetapkan"
                      label="Belum Bisa Ditetapkan"
                      class="p-0"
                      color="primary"
                      square
                    />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-4">
            <h1 style="font-weight: bold">Tanggal dan Jam</h1>
            <VDatePicker
              v-model="input.tglPembuatan"
              class="pt-3"
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
          </div>
          <div class="column is-4 is-offset-2">
            <h1 style="font-weight: bold">Tanggal Edukasi</h1>
            <VDatePicker
              v-model="input.tglEdukasi"
              class="pt-3"
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
          </div>
        </div>
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
          <div class="column is-6">
            <img v-if="input.namapasien"
                  :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.namapasien ? input.namapasien : '') + ', ' + (input.tglPembuatan ? input.tglPembuatan : input.tglPembuatan)">
            <!-- <TandaTangan
              :elemenID="'signature_2'"
              :width="'180'"
              :height="'180'"
            ></TandaTangan> -->
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
          <div class="column is-4 is-offset-2">
            <h1 class="p-0" style="font-weight: bold">Pasien/Keluarga</h1>
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input prime-auto"
                  placeholder="Pasien/Keluarga"
                  v-model="input.namapasien"
                  :suggestions="d_Pasien"
                  @complete="fetchPasien($event)"
                />
              </VControl>
            </VField>
          </div>
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
import AutoComplete from 'primevue/autocomplete'
import Fieldset from 'primevue/fieldset'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/pengkajian-dokter-awal-ri'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let pengkajianUmum = ref(EMR.pengkajianUmum())
let anamnesis = ref(EMR.anamnesis())
let KeadaanUmum = ref(EMR.KeadaanUmum())
let Kesadaran = ref(EMR.Kesadaran())
let kepala = ref(EMR.kepala())
let mata = ref(EMR.mata())
let hidung = ref(EMR.hidung())
let gigiMulut = ref(EMR.gigiMulut())
let tenggorokan = ref(EMR.tenggorokan())
let telinga = ref(EMR.telinga())
let leher = ref(EMR.leher())
let thoraks = ref(EMR.thoraks())
let jantung = ref(EMR.jantung())
let paru = ref(EMR.paru())
let abdomen = ref(EMR.abdomen())
let genitaliaAnus = ref(EMR.genitaliaAnus())
let ekstremitas = ref(EMR.ekstremitas())
let kulit = ref(EMR.kulit())
let pemeriksaanFisik1 = ref(EMR.pemeriksaanFisik1())

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
const isStuck = computed(() => {
  return y.value >= 20
})
const isLoading: any = ref(false)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const d_JenisDiagnosa = ref([])
const d_DiagnosaTindakan = ref([])
const d_Diagnosa = ref([])
const d_Pasien: any = ref([])
const d_Ruangan: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  diagnosaDokter: [{
    no: 1,
  }],
  detailTindakan: [{
    no: 1
  }]
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = () => {
  // if (NOREC_EMRPASIEN.value == '') return
  useApi()
    .get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    )
    .then((response: any) => {
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
    })
    riwayatDiagnosa10()
    riwayatDiagnosa9()
}

const print = async () => {
  // await useApi().get(`emr/cetak?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  H.printBlade(
    `emr/cetak-pengkajian-dokter-ri?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
  )
}

const simpan = async () =>{
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.tandaTanganDokter = H.tandaTangan().get('signature_1')
  object.tandaTanganPasien = H.tandaTangan().get('signature_2')
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

  isLoading.value = true
  if (input.value.diagnosaDokter[0].jenisDiagnosa != undefined) {
    await useApi().post(`/diagnosa/save-more-diagnosa`, { 'diagnosis': input.value.diagnosaDokter, 'noregistrasifk': props.registrasi.norec_apd }).then((response: any) => {
    }).catch((e: any) => {
      console.log(e)
    })
  }

  if (input.value.detailTindakan[0].ketTindakanDokter != undefined) {
    await useApi().post('/diagnosa/save-more-diagnosa-ix', { 'diagnosaPerawat': input.value.detailTindakan, 'noregistrasifk': props.registrasi.norec_apd }).then((response: any) => {
    }).catch((e: any) => {
      console.log(e)
    })
  }
  useApi()
    .post(`/emr/simpan-emr`, json)
    .then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
      loadRiwayat()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
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
  }
// }

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

// const fetchPegawai = async (filter: any) => {

//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
//   ).then((response) => {
//     d_Pegawai.value = response
//   })
// }

const fetchJenisDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/jenisdiagnosa_m?select=id,jenisdiagnosa&param_search=jenisdiagnosa&query=${filter.query}&limit=10`)
  d_JenisDiagnosa.value = response
  console.log(response)
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
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    )
    .then((response) => {
      d_Dokter.value = response
    })
}
const setTandaTangan = async (e: any) => {
  const response = await useApi().get(`/emr/tanda-tangan/${e.value.value}`)
  if (response != null) {
    H.tandaTangan().set('signature_1', response.tandaTanganDokter)
    input.value.tandaTanganDokter = response.tandaTanganDokter
  } else {
    H.tandaTangan().set('signature_1', '')
  }
}
const fetchPasien = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/pasien_m?select=id,namapasien&param_search=namapasien&query=${filter.query}&limit=10`
  )
  d_Pasien.value = response
}

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

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.tglPembuatan = new Date()
  input.value.tglEdukasi = new Date()
  input.value.namapasien = props.pasien.namapasien
  await useApi()
    .get(
      'emr/auto-fill?norec_pd=' +
        props.registrasi.norec_pd +
        '&collection=pengkajianDokterGawatDarurat' +
        '&field=keluhanUtama,riwayatPenyakitSekarang,KesadaranCM,KesadaranApatis,KesadaranSomnolen,KesadaranSopor,KesadaranComa,keadaanUmumSehat,keadaanUmumRingan,keadaanUmumSedang,keadaanUmumBerat,fisikKepala,fisikLeher,fisikThoraks,fisikAbdomen,fisikEkstremitas,fisikLainnya,instruksi,fisikKepalaAbnormalLainKet,masalah,diagnosaICD10'
    )
    .then((response) => {
      if (response != null) {
        console.log(response)
        console.log(Kesadaran.value)
        let diagnosis = ''
        response.diagnosaICD10.forEach((element,index) => {
          diagnosis += element.keterangan
          if(index+1 < response.diagnosaICD10.length){
            diagnosis += ','
          }
        });
        input.value.KeluhanUtama = response.keluhanUtama ?? ''
        input.value.riwayatPenyakitSekarang = response.riwayatPenyakitSekarang ?? ''
        input.value.KesadaranCM = response.KesadaranCM ?? ''
        input.value.KesadaranApatis = response.KesadaranApatis ?? ''
        input.value.KesadaranSomnolen = response.KesadaranSomnolen ?? ''
        input.value.KesadaranSopor = response.KesadaranSopor ?? ''
        input.value.KesadaranKoma = response.KesadaranComa ?? ''
        input.value.keadaanUmumSehat = response.keadaanUmumSehat ?? ''
        input.value.keadaanUmumRingan = response.keadaanUmumRingan ?? ''
        input.value.keadaanUmumSedang = response.keadaanUmumSedang ?? ''
        input.value.keadaanUmumBerat = response.keadaanUmumBerat ?? ''
        input.value.kepala = response.fisikKepala ?? ''
        input.value.ketKepala = response.fisikKepalaAbnormalLainKet ?? ''
        input.value.leher = response.fisikLeher ?? ''
        input.value.thoraks = response.fisikThoraks ?? ''
        input.value.abdomen = response.fisikAbdomen ?? ''
        input.value.ekstremitas = response.fisikEkstremitas ?? ''
        input.value.lainnya = response.fisikLainnya ?? ''
        input.value.instruksi = response.instruksi ?? ''
        input.value.masalahMedisKeperawatan = response.masalah ?? ''
        input.value.diagnosisAssesment = diagnosis ?? ''
        input.value.riwayatALergi = response.alergiObat ?? ''
      }
    })
    await useApi().get(
    `/laboratorium/hasil-lab?noregistrasi=${props.registrasi.noregistrasi}`).then((response) => {
      if (response != null) {
        let hasillabemr = '';
        response.forEach(element => {
          hasillabemr += `${element.hasillab}`;
        });
        input.value.laboratorium = ref(hasillabemr);
      }
    });
  await useApi().get(
    `/emr/get-hasil-radiologi-emr?noregistrasi=${props.registrasi.noregistrasi}&norec_pd=${props.registrasi.norec_pd}`).then((response) => {
      if (response != null) {
        let hasilrad = '';
        response.forEach(element => {
          hasilrad += `${element.hasilexpertise}\n`;
        });
        input.value.radiologi = ref(hasilrad);
      }
    });
}
setView()
// setAutoFill()
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
