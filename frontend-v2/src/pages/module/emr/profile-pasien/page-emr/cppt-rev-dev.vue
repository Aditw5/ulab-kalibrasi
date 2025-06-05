<template>
  <FloatingButtonSave
    @click="simpanReal"
    :loading="isLoading"
    v-if="
      !props.isDisableSimpanButton && isFloating &&
      (H.formatDate(props.registrasi.tglregistrasi, 'YYYY-MM-DD') >=
        H.formatDate(new Date(), 'YYYY-MM-DD') ||
        props.registrasi.objectdepartemenfk != 18)
    "
  />
  <FloatingButtonPreview
    @click="simpan('')"
    :loading="isLoading"
    v-if="
      !props.isDisableSimpanButton && isFloating &&
      (H.formatDate(props.registrasi.tglregistrasi, 'YYYY-MM-DD') >=
        H.formatDate(new Date(), 'YYYY-MM-DD') ||
        props.registrasi.objectdepartemenfk != 18)
    "
  />
  <FloatingButtonResepPulang
    @click="OpenResepPulang"
    v-if="isDokter == true && props.registrasi.objectdepartemenfk == 16"
  />
  <FloatingButtonResep @click="OpenResep" v-if="isDokter == true" />
  <FloatingButtonKonsul @click="isKonsul = true" v-if="isDokter == true" />
  <FloatingButtonRadiologi @click="isRad = true" v-if="isDokter == true" />
  <FloatingButtonLaborat @click="isLab = true" v-if="isDokter == true" />
  <FloatingButtonRujukan @click="isRujukan = true" v-if="isDokter == true" />
  <FloatingButtonKontrol @click="isKontrol = true" v-if="isDokter == true" />
  <div class="form-layout is-stacked-2" style="width: 100%; max-width: none">
    <div class="form-outer" style="margin-top: 15px">
      <div class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>{{ props.FORM_NAME }} <span v-if="isStuck"></span></h3>
          </div>
          <div class="right">
            <div class="buttons">
              <VButton
                icon="lnir lnir-arrow-left rem-100"
                light
                dark-outlined
                @click="kembaliKeun"
              >
                Kembali
              </VButton>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="columns is-multiline p-2">
    <div class="column is-2 ml-5 mb-5-min">
      <VControl>
        <VSwitchBlock v-model="isCPPTOLD" label="HISTORY" color="success" />
      </VControl>
    </div>
    <div class="column is-8" style="margin-top: 1.8rem" v-if="isloadingLAMPAU">
      <VProgress size="tiny" color="info" />
    </div>
    <div class="column is-12">
      <VCard>
        <div class="columns is-multiline mt-3">
          <div class="column is-2">
            <VField
              label="DPJP Utama"
              class="is-rounded-select_Z is-autocomplete-select"
              v-slot="{ id }"
            >
              <VControl icon="fa:user-md" fullwidth class="prime-auto">
                <AutoComplete
                  v-model="input.dpjpUtama"
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
          <div class="column is-1 mt-5">
            <VControl>
              <VSwitchBlock v-model="isPerawat" label="PERAWAT" color="info" />
            </VControl>
          </div>
          <div class="column is-1 mt-5">
            <VControl>
              <VSwitchBlock v-model="isDokter" label="DOKTER" color="danger" />
            </VControl>
          </div>
          <div class="column is-1 mt-5">
            <VControl>
              <VSwitchBlock v-model="isGizi" label="ADIME" color="warning" />
            </VControl>
          </div>
          <div class="column is-2 mt-5">
            <VControl>
              <VSwitchBlock v-model="isProfesi" label="PROFESI LAIN" />
            </VControl>
          </div>
          <div class="column is-1 mt-5">
            <VIconButton
              type="button"
              raised
              circle
              icon="feather:filter"
              @click="clear()"
              outlined
              color="info"
              v-tooltip-prime.right="'Clear Filter'"
            >
            </VIconButton>
          </div>
          <div class="column is-12">
            <VField
              label="Dokter Rawat Bersama"
              class="is-rounded-select_Z is-autocomplete-select"
              v-slot="{ id }"
            >
              <Chip class="p-2" style="font-size: 11pt">{{
                props.registrasi.dokterBersama
              }}</Chip>
              <Chip class="p-2 ml-2" v-for="dokter in props.registrasi.dokterTambahan">{{
                dokter.namalengkap
              }}</Chip>
            </VField>
          </div>
          <div class="column is-12" style="overflow-y: auto">
            <table class="tg table-tg">
              <tbody v-for="(item, index) in dataSourcefiltered" :key="index">
                <tr
                  v-if="item.flag != 'gizi'"
                  :style="[
                    item.flag == 'dokter'
                      ? 'background-color: var(--danger--light-color);'
                      : item.flag == 'perawat'
                      ? 'background-color: var(--info--light-color);'
                      : '',
                  ]"
                >
                  <td style="width: 100%">
                    <div class="column is-12" v-if="item.flag == 'dokter'">
                      <div class="columns is-multiline">
                        <div class="column is-2">
                          <VButton
                            color="info"
                            class="w-100 btn-slim"
                            light
                            rounded
                            outlined
                            v-tooltip-prime.right="'Resep'"
                            @click="OpenResep"
                          >
                            Resep
                          </VButton>
                        </div>
                        <div class="column is-2">
                          <VButton
                            color="info"
                            class="w-100 btn-slim"
                            light
                            rounded
                            outlined
                            v-tooltip-prime.right="'Resep Pulang'"
                            @click="OpenResepPulang"
                          >
                            Resep Pulang</VButton
                          >
                        </div>
                        <div class="column is-2">
                          <VButton
                            color="success"
                            class="w-100 btn-slim"
                            light
                            rounded
                            outlined
                            v-tooltip-prime.right="'Konsul'"
                            @click="isKonsul = true"
                          >
                            Konsul
                          </VButton>
                        </div>
                        <div class="column is-2">
                          <VButton
                            color="warning"
                            class="w-100 btn-slim"
                            light
                            rounded
                            outlined
                            v-tooltip-prime.right="'Radiologi'"
                            @click="isRad = true"
                          >
                            Radiologi
                          </VButton>
                        </div>
                        <div class="column is-2">
                          <VButton
                            color="primary"
                            class="w-100 btn-slim"
                            light
                            rounded
                            outlined
                            v-tooltip-prime.right="'Laboratorium'"
                            @click="isLab = true"
                          >
                            Laboratorium
                          </VButton>
                        </div>
                        <div class="column is-2">
                          <VButton
                            color="primary"
                            class="w-100 btn-slim"
                            light
                            rounded
                            outlined
                            v-tooltip-prime.right="'Rujukan External'"
                            @click="isRujukan = true"
                          >
                            Rujukan
                          </VButton>
                        </div>
                        <div class="column is-2">
                          <VButton
                            color="primary"
                            class="w-100 btn-slim"
                            light
                            rounded
                            outlined
                            v-tooltip-prime.right="'Perjanjian/Kontrol'"
                            @click="isKontrol = true"
                          >
                            Perjanjian/Kontrol
                          </VButton>
                        </div>
                      </div>
                    </div>
                    <table class="tg">
                      <tr>
                        <td
                          width="5%"
                          style="
                            font-size: 22pt;
                            width: 5%;
                            font-weight: bold;
                            vertical-align: middle;
                          "
                        >
                          S
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VTextarea
                                v-model="item.S"
                                rows="5"
                                placeholder="Subjective..."
                                :disabled="item.S2"
                              >
                              </VTextarea>
                            </VControl>
                          </VField>
                        </td>
                        <td v-if="item.flag == 'dokter'">
                          <VIconButton
                            color="danger"
                            outlined
                            circle
                            icon="lnil lnil-trash-can-alt-1"
                            v-if="
                              H.formatDate(
                                props.registrasi.tglregistrasi,
                                'YYYY-MM-DD'
                              ) >= H.formatDate(new Date(), 'YYYY-MM-DD') ||
                              props.registrasi.objectdepartemenfk != 18
                            "
                            @click="clearItem(index, 'S')"
                            style="float: right"
                            v-tooltip-prime.top="'Hapus'"
                          >
                          </VIconButton>
                        </td>
                      </tr>
                      <tr>
                        <td
                          width="5%"
                          style="
                            font-size: 22pt;
                            width: 5%;
                            font-weight: bold;
                            vertical-align: middle;
                          "
                        >
                          O
                        </td>
                        <td v-if="item.flag == 'dokter'">
                          <div class="columns is-multiline">
                            <div class="column is-11">
                              <VField>
                                <VControl>
                                  <VTextarea
                                    v-model="item.O"
                                    rows="5"
                                    placeholder="Objective..."
                                    :disabled="item.O2"
                                  >
                                  </VTextarea>
                                </VControl>
                              </VField>
                            </div>
                            <div class="column is-1" style="align-self: center">
                              <VIconButton
                                type="button"
                                raised
                                circle
                                icon="fas fa-copy"
                                v-tooltip-prime.bottom="'Tetapkan Data Penunjang'"
                                @click="show"
                                color="warning"
                              ></VIconButton>
                            </div>
                          </div>
                        </td>
                        <td v-else>
                          <VField>
                            <VControl>
                              <VTextarea
                                v-model="item.O"
                                rows="5"
                                placeholder="Objective..."
                                :disabled="item.O2"
                              >
                              </VTextarea>
                            </VControl>
                          </VField>
                        </td>
                        <td v-if="item.flag == 'dokter'">
                          <VIconButton
                            color="danger"
                            outlined
                            circle
                            icon="lnil lnil-trash-can-alt-1"
                            v-if="
                              H.formatDate(
                                props.registrasi.tglregistrasi,
                                'YYYY-MM-DD'
                              ) >= H.formatDate(new Date(), 'YYYY-MM-DD') ||
                              props.registrasi.objectdepartemenfk != 18
                            "
                            @click="clearItem(index, 'O')"
                            style="float: right"
                            v-tooltip-prime.top="'Hapus'"
                          >
                          </VIconButton>
                        </td>
                      </tr>
                      <tr>
                        <td
                          width="5%"
                          style="
                            font-size: 22pt;
                            width: 5%;
                            font-weight: bold;
                            vertical-align: middle;
                          "
                        >
                          A
                        </td>
                        <td v-if="item.flag == 'profesi lain'">
                          <VField>
                            <VControl>
                              <VTextarea
                                v-model="item.A"
                                rows="5"
                                placeholder="Analysis..."
                                :disabled="item.A2"
                              >
                              </VTextarea>
                            </VControl>
                          </VField>
                        </td>
                        <td v-if="item.flag == 'dokter'">
                          <div class="columns is-multiline">
                            <div class="column is-12">
                              <span style="font-size: 9pt; font-weight: bold"
                                >Diagnosis ICD 10</span
                              >
                              <div style="overflow-y: auto" class="mt-1">
                                <table class="tg" style="width: 100% !important">
                                  <thead>
                                    <tr>
                                      <th
                                        class="td-fkprj"
                                        rowspan="2"
                                        style="
                                          vertical-align: inherit;
                                          text-align: center;
                                          font-size: 9pt;
                                        "
                                      >
                                        #
                                      </th>
                                      <th
                                        class="td-fkprj"
                                        width="23%"
                                        style="
                                          vertical-align: inherit;
                                          text-align: center;
                                          font-size: 9pt;
                                        "
                                      >
                                        Jenis
                                      </th>
                                      <th
                                        class="td-fkprj"
                                        width="25%"
                                        style="
                                          vertical-align: inherit;
                                          text-align: center;
                                          font-size: 9pt;
                                        "
                                      >
                                        Diagnosa Dokter
                                      </th>
                                      <th
                                        class="td-fkprj"
                                        style="
                                          vertical-align: inherit;
                                          text-align: center;
                                          font-size: 9pt;
                                        "
                                      >
                                        ICD 10
                                      </th>
                                    </tr>
                                  </thead>
                                  <tbody
                                    v-for="(itemsss, index3) in item.diagnosaDokter"
                                    v-if="item.diagnosaDokter.length === 0"
                                    :key="index3"
                                  >
                                    <tr>
                                      <td class="tg-0lax" style="vertical-align: inherit">
                                        <div class="column p-0">
                                          <div
                                            class="columns is-12 is-multiline is-flex"
                                            v-if="
                                              H.formatDate(
                                                props.registrasi.tglregistrasi,
                                                'YYYY-MM-DD'
                                              ) >=
                                                H.formatDate(new Date(), 'YYYY-MM-DD') ||
                                              props.registrasi.objectdepartemenfk != 18
                                            "
                                          >
                                            <div class="column is-4">
                                              <VIconButton
                                                type="button"
                                                circle
                                                icon="feather:plus"
                                                @click=""
                                                outlined
                                                color="info"
                                              >
                                              </VIconButton>
                                            </div>
                                            <div class="column is-4">
                                              <VIconButton
                                                type="button"
                                                circle
                                                outlined
                                                icon="feather:trash"
                                                @click="
                                                  removeDiagnosaDok(itemsss, index3)
                                                "
                                                color="danger"
                                              >
                                              </VIconButton>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                      <td class="tg-0lax">
                                        <div class="column p-0">
                                          <VField>
                                            <VControl class="prime-auto">
                                              <AutoComplete
                                                v-model="itemsss.jenisDiagnosa"
                                                :suggestions="d_JenisDiagnosa"
                                                @complete="fetchJenisDiagnosa($event)"
                                                :optionLabel="'label'"
                                                :dropdown="true"
                                                :minLength="3"
                                                :appendTo="'body'"
                                                :loadingIcon="'pi pi-spinner'"
                                                :field="'label'"
                                                placeholder=" Jenis ..."
                                                class=""
                                              />
                                            </VControl>
                                          </VField>
                                        </div>
                                      </td>
                                      <td class="tg-0lax">
                                        <div class="column p-0">
                                          <VField>
                                            <VControl>
                                              <VInput
                                                type="text"
                                                v-model="itemsss.norecDiagnosa"
                                                disabled
                                                style="display: none"
                                                placeholder="Diagnosa Dokter"
                                              />
                                              <VInput
                                                type="text"
                                                v-model="itemsss.keterangan"
                                                placeholder="Diagnosa Dokter"
                                              />
                                            </VControl>
                                          </VField>
                                        </div>
                                      </td>
                                      <td class="tg-0lax">
                                        <div class="column p-0">
                                          <VField>
                                            <VControl class="prime-auto">
                                              <AutoComplete
                                                v-model="itemsss.diagnosaa"
                                                :suggestions="d_Diagnosa"
                                                @complete="fetchDiagnosa($event)"
                                                :optionLabel="'label'"
                                                :dropdown="true"
                                                :minLength="3"
                                                :appendTo="'body'"
                                                :loadingIcon="'pi pi-spinner'"
                                                :field="'label'"
                                                placeholder=" ICD 10 ..."
                                                class=""
                                              />
                                            </VControl>
                                          </VField>
                                        </div>
                                      </td>
                                    </tr>
                                  </tbody>
                                  <tbody
                                    v-for="(itemsss, index3) in item.diagnosaDokter"
                                    :key="'not_empty_' + index3"
                                    v-else
                                  >
                                    <tr>
                                      <td class="tg-0lax" style="vertical-align: inherit">
                                        <div class="column p-0">
                                          <div
                                            class="columns is-multiline"
                                            v-if="
                                              H.formatDate(
                                                props.registrasi.tglregistrasi,
                                                'YYYY-MM-DD'
                                              ) >=
                                                H.formatDate(new Date(), 'YYYY-MM-DD') ||
                                              props.registrasi.objectdepartemenfk != 18
                                            "
                                          >
                                            <div class="column is-4">
                                              <VIconButton
                                                type="button"
                                                v-if="isFloating"
                                                raised
                                                circle
                                                icon="feather:plus"
                                                :loading="itemsss.isLoadBtnDiagnosaDokter"
                                                @click="addDiagnosaDok(itemsss)"
                                                outlined
                                                color="info"
                                              >
                                              </VIconButton>
                                              <VIconButton
                                                type="button"
                                                v-if="isFloating == false"
                                                disabled
                                                raised
                                                circle
                                                icon="feather:plus"
                                                :loading="itemsss.isLoadBtnDiagnosaDokter"
                                                outlined
                                                color="info"
                                              >
                                              </VIconButton>
                                            </div>
                                            <div class="column is-4">
                                              <VIconButton
                                                type="button"
                                                v-if="isFloating"
                                                raised
                                                circle
                                                outlined
                                                icon="feather:trash"
                                                :loading="itemsss.isLoadBtnDiagnosaDokter"
                                                @click="
                                                  removeDiagnosaDok(itemsss, index3)
                                                "
                                                color="danger"
                                              >
                                              </VIconButton>
                                              <VIconButton
                                                type="button"
                                                v-if="isFloating == false"
                                                disabled
                                                raised
                                                circle
                                                outlined
                                                icon="feather:trash"
                                                :loading="itemsss.isLoadBtnDiagnosaDokter"
                                                color="danger"
                                              >
                                              </VIconButton>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                      <td class="tg-0lax">
                                        <div class="column p-0">
                                          <VField>
                                            <VControl class="prime-auto">
                                              <AutoComplete
                                                v-model="itemsss.jenisDiagnosa"
                                                :suggestions="d_JenisDiagnosa"
                                                @complete="fetchJenisDiagnosa($event)"
                                                :optionLabel="'label'"
                                                :dropdown="true"
                                                :minLength="3"
                                                :appendTo="'body'"
                                                :loadingIcon="'pi pi-spinner'"
                                                :field="'label'"
                                                placeholder=" Jenis ..."
                                                class=""
                                              />
                                            </VControl>
                                          </VField>
                                        </div>
                                      </td>
                                      <td class="tg-0lax">
                                        <div class="column p-0">
                                          <VField>
                                            <VControl>
                                              <VInput
                                                type="text"
                                                v-model="itemsss.norecDiagnosa"
                                                disabled
                                                style="display: none"
                                                placeholder="Diagnosa Dokter"
                                              />
                                              <VInput
                                                type="text"
                                                v-model="itemsss.keterangan"
                                                placeholder="Diagnosa Dokter"
                                              />
                                            </VControl>
                                          </VField>
                                        </div>
                                      </td>
                                      <td class="tg-0lax">
                                        <div class="column p-0">
                                          <VField>
                                            <VControl class="prime-auto">
                                              <AutoComplete
                                                v-model="itemsss.diagnosaa"
                                                :suggestions="d_Diagnosa"
                                                @complete="fetchDiagnosa($event)"
                                                :optionLabel="'label'"
                                                :dropdown="true"
                                                :minLength="3"
                                                :appendTo="'body'"
                                                :loadingIcon="'pi pi-spinner'"
                                                :field="'label'"
                                                placeholder=" ICD 10 ..."
                                                class=""
                                              />
                                            </VControl>
                                          </VField>
                                        </div>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                            <div class="column is-12">
                              <span style="font-size: 9pt; font-weight: bold"
                                >Diagnosis ICD 9</span
                              >
                              <div style="overflow-y: auto" class="mt-1">
                                <table class="tg" width="100%">
                                  <thead>
                                    <tr>
                                      <th
                                        class="td-fkprj"
                                        rowspan="2"
                                        style="
                                          vertical-align: inherit;
                                          text-align: center;
                                          font-size: 9pt;
                                        "
                                      >
                                        #
                                      </th>

                                      <th
                                        class="td-fkprj"
                                        width="40%"
                                        style="
                                          vertical-align: inherit;
                                          text-align: center;
                                          font-size: 9pt;
                                        "
                                      >
                                        Keterangan
                                      </th>
                                      <th
                                        class="td-fkprj"
                                        style="
                                          vertical-align: inherit;
                                          text-align: center;
                                          font-size: 9pt;
                                        "
                                      >
                                        ICD 9
                                      </th>
                                    </tr>
                                  </thead>
                                  <tbody
                                    v-for="(itemsss, index3) in item.diagnosaDokter9"
                                    :key="index3"
                                  >
                                    <tr>
                                      <td class="tg-0lax" style="vertical-align: inherit">
                                        <div class="column p-0">
                                          <div
                                            class="columns is-multiline"
                                            v-if="
                                              H.formatDate(
                                                props.registrasi.tglregistrasi,
                                                'YYYY-MM-DD'
                                              ) >=
                                                H.formatDate(new Date(), 'YYYY-MM-DD') ||
                                              props.registrasi.objectdepartemenfk != 18
                                            "
                                          >
                                            <div class="column is-4">
                                              <VIconButton
                                                v-if="isFloating"
                                                type="button"
                                                raised
                                                circle
                                                icon="feather:plus"
                                                :loading="
                                                  itemsss.isLoadBtnDiagnosaDokter9
                                                "
                                                @click="addDiagnosaDok9(itemsss)"
                                                outlined
                                                color="info"
                                              >
                                              </VIconButton>
                                              <VIconButton
                                                v-if="isFloating == false"
                                                disabled
                                                type="button"
                                                raised
                                                circle
                                                icon="feather:plus"
                                                :loading="
                                                  itemsss.isLoadBtnDiagnosaDokter9
                                                "
                                                outlined
                                                color="info"
                                              >
                                              </VIconButton>
                                            </div>
                                            <div class="column is-4">
                                              <VIconButton
                                                v-if="isFloating"
                                                type="button"
                                                raised
                                                circle
                                                outlined
                                                icon="feather:trash"
                                                :loading="
                                                  itemsss.isLoadBtnDiagnosaDokter9
                                                "
                                                @click="
                                                  removeDiagnosaDok9(itemsss, index3)
                                                "
                                                color="danger"
                                              >
                                              </VIconButton>
                                              <VIconButton
                                                v-if="isFloating == false"
                                                disabled
                                                type="button"
                                                raised
                                                circle
                                                outlined
                                                icon="feather:trash"
                                                :loading="
                                                  itemsss.isLoadBtnDiagnosaDokter9
                                                "
                                                color="danger"
                                              >
                                              </VIconButton>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                      <td class="tg-0lax">
                                        <div class="column p-0">
                                          <VField>
                                            <VControl>
                                              <VInput
                                                type="text"
                                                v-model="itemsss.norecDiagnosa9"
                                                disabled
                                                style="display: none"
                                                placeholder="Diagnosa Dokter"
                                              />
                                              <VInput
                                                type="text"
                                                v-model="itemsss.keterangan"
                                                placeholder="Diagnosa Dokter"
                                              />
                                            </VControl>
                                          </VField>
                                        </div>
                                      </td>
                                      <td class="tg-0lax">
                                        <div class="column p-0">
                                          <VField>
                                            <VControl class="prime-auto">
                                              <AutoComplete
                                                v-model="itemsss.diagnosaa"
                                                :suggestions="d_Diagnosa9"
                                                @complete="fetchDiagnosa9($event)"
                                                :optionLabel="'label'"
                                                :dropdown="true"
                                                :minLength="3"
                                                :appendTo="'body'"
                                                :loadingIcon="'pi pi-spinner'"
                                                :field="'label'"
                                                placeholder=" ICD 9 ..."
                                                class=""
                                              />
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
                        </td>
                        <td v-if="item.flag == 'perawat'">
                          <div class="column">
                            <span style="font-size: 11pt; font-weight: bold"
                              >Diagnosis Keperawatan</span
                            >
                            <VField>
                              <VControl>
                                <VTextarea
                                  v-model="item.AFreeText"
                                  rows="5"
                                  placeholder="Diagnosis Keperawatan"
                                  :disabled="item.AFreeText2s"
                                ></VTextarea>
                              </VControl>
                            </VField>
                            <div class="mt-1">
                              <table class="tg">
                                <thead>
                                  <tr>
                                    <th
                                      class="td-fkprj"
                                      width="50%"
                                      style="vertical-align: inherit; text-align: center"
                                    >
                                      Diagnosa Keperawatan
                                    </th>
                                  </tr>
                                </thead>
                                <tbody
                                  v-for="(item2, index2) in item.diagnosaKep"
                                  :key="index2"
                                >
                                  <tr>
                                    <td class="tg-0lax">
                                      <div class="column p-1">
                                        <VField>
                                          <VControl class="prime-auto">
                                            <AutoComplete
                                              v-model="item2.diagnosaKeperawatan"
                                              :suggestions="d_DiagnosaKeperawatan"
                                              @complete="fetchDiagnosaKeperawatan($event)"
                                              :optionLabel="'label'"
                                              :dropdown="true"
                                              :minLength="3"
                                              :appendTo="'body'"
                                              :loadingIcon="'pi pi-spinner'"
                                              :field="'label'"
                                              placeholder="Cari Diagnosa Keperawatan ..."
                                              class="mt-2"
                                            />
                                          </VControl>
                                        </VField>
                                      </div>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td
                          width="5%"
                          style="
                            font-size: 22pt;
                            width: 5%;
                            font-weight: bold;
                            vertical-align: middle;
                          "
                        >
                          P
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VTextarea
                                v-model="item.P"
                                rows="5"
                                placeholder="Planning..."
                                :disabled="item.P2"
                              >
                              </VTextarea>
                            </VControl>
                          </VField>
                          <div class="column" v-if="item.flag == 'perawat'">
                            <span style="font-size: 11pt; font-weight: bold"
                              >Tujuan Kriteria (SLKI)
                            </span>
                            <div class="mt-1">
                              <table class="tg">
                                <thead>
                                  <tr>
                                    <th
                                      class="td-fkprj"
                                      width="50%"
                                      style="vertical-align: inherit; text-align: center"
                                    >
                                      Tujuan Keperawatan & Intervensi
                                    </th>
                                  </tr>
                                </thead>
                                <tbody
                                  v-for="(item2, index2) in item.tujuanKep"
                                  :key="index2"
                                >
                                  <tr>
                                    <td class="tg-0lax">
                                      <div class="column p-1">
                                        <VField>
                                          <VControl class="prime-auto">
                                            <AutoComplete
                                              v-model="item2.tujuanKeperawatan"
                                              :suggestions="d_TujuanKeperawatan"
                                              @complete="fetchTujuan($event)"
                                              @item-select="
                                                getIDTujuanKeper(item2.tujuanKeperawatan)
                                              "
                                              :optionLabel="'label'"
                                              :dropdown="true"
                                              :minLength="3"
                                              :appendTo="'body'"
                                              :loadingIcon="'pi pi-spinner'"
                                              :field="'label'"
                                              placeholder="Cari Tujuan Keperawatan ..."
                                              class="mt-2"
                                            />
                                          </VControl>
                                        </VField>
                                      </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td class="tg-0lax">
                                      <div class="column p-1">
                                        <VField>
                                          <VControl class="prime-auto">
                                            <AutoComplete
                                              v-model="item2.intervensiKeperawatan"
                                              :suggestions="d_IntervensiKeperawatan"
                                              @complete="fetchIntervensi()"
                                              :optionLabel="'label'"
                                              :dropdown="true"
                                              :appendTo="'body'"
                                              :loadingIcon="'pi pi-spinner'"
                                              :field="'label'"
                                              placeholder="Cari Intervensi"
                                              class="mt-2"
                                            />
                                          </VControl>
                                        </VField>
                                      </div>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </td>
                        <td v-if="item.flag == 'dokter'">
                          <VIconButton
                            color="danger"
                            outlined
                            circle
                            icon="lnil lnil-trash-can-alt-1"
                            @click="clearItem(index, 'P')"
                            style="float: right"
                            v-tooltip-prime.top="'Hapus'"
                          >
                          </VIconButton>
                        </td>
                      </tr>
                      <tr v-if="item.flag == 'perawat'">
                        <td
                          width="5%"
                          style="
                            font-size: 22pt;
                            width: 5%;
                            font-weight: bold;
                            vertical-align: middle;
                          "
                        >
                          S
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VTextarea
                                v-model="item.SSbar"
                                rows="5"
                                placeholder="Situation"
                                :disabled="item.S2Sbar"
                                style="padding-left: 30px"
                              >
                              </VTextarea>
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr v-if="item.flag == 'perawat'">
                        <td
                          width="5%"
                          style="
                            font-size: 22pt;
                            width: 5%;
                            font-weight: bold;
                            vertical-align: middle;
                          "
                        >
                          B
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VTextarea
                                v-model="item.BSbar"
                                rows="5"
                                placeholder="Background"
                                :disabled="item.B2Sbar"
                                style="padding-left: 30px"
                              >
                              </VTextarea>
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr v-if="item.flag == 'perawat'">
                        <td
                          width="5%"
                          style="
                            font-size: 22pt;
                            width: 5%;
                            font-weight: bold;
                            vertical-align: middle;
                          "
                        >
                          A
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VTextarea
                                v-model="item.ASbar"
                                rows="5"
                                placeholder="Assessment"
                                :disabled="item.A2Sbar"
                                style="padding-left: 30px"
                              >
                              </VTextarea>
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr v-if="item.flag == 'perawat'">
                        <td
                          width="5%"
                          style="
                            font-size: 22pt;
                            width: 5%;
                            font-weight: bold;
                            vertical-align: middle;
                          "
                        >
                          R
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VTextarea
                                v-model="item.RSbar"
                                rows="5"
                                placeholder="Recommendation"
                                :disabled="item.R2Sbar"
                                style="padding-left: 30px"
                              >
                              </VTextarea>
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                    </table>
                    <div class="columns is-multiline mt-3">
                      <div class="column is-4">
                        <h3
                          class="has-text-centered"
                          style="font-size: 12pt; font-weight: bold"
                        >
                          Instruksi PPA
                        </h3>
                        <VField>
                          <VControl>
                            <VTextarea
                              class="mt-3"
                              v-model="item.intruksiPPA"
                              rows="10"
                              placeholder="Intruksi PPA..."
                              :disabled="item.intruksiPPA2"
                            >
                            </VTextarea>
                          </VControl>
                        </VField>
                        <td style="width: 15%">
                          <VField>
                            <VControl class="prime-auto">
                              <Calendar
                                v-model="item.tgl"
                                selectionMode="single"
                                :manualInput="true"
                                class="w-100"
                                :showIcon="true"
                                showTime
                                hourFormat="24"
                                :date-format="'yy-mm-dd'"
                                :disabled="item.tgl2"
                              />
                            </VControl>
                          </VField>
                          <VField
                            class="is-rounded-select_Z is-autocomplete-select"
                            v-slot="{ id }"
                            v-if="item.flag == 'dokter'"
                          >
                            <VControl icon="fa:stethoscope" fullwidth class="prime-auto">
                              <AutoComplete
                                v-model="item.tenagaMedis"
                                :suggestions="d_Dokter"
                                @complete="fetchDokter($event)"
                                :optionLabel="'label'"
                                :dropdown="true"
                                :minLength="3"
                                :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'"
                                :field="'label'"
                                placeholder="Tenaga Medis..."
                                :disabled="item.tenagaMedis2"
                              />
                            </VControl>
                          </VField>
                          <VField
                            class="is-rounded-select_Z is-autocomplete-select"
                            v-slot="{ id }"
                            v-else
                          >
                            <VControl icon="fa:stethoscope" fullwidth class="prime-auto">
                              <AutoComplete
                                v-model="item.tenagaMedis"
                                :suggestions="d_Pegawai"
                                @complete="fetchPegawai($event)"
                                :optionLabel="'label'"
                                :dropdown="true"
                                :minLength="3"
                                :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'"
                                :field="'label'"
                                placeholder="Tenaga Medis..."
                                :disabled="item.tenagaMedis2"
                              />
                            </VControl>
                          </VField>
                        </td>
                      </div>
                      <div class="column is-4 text-center">
                        <h3
                          class="has-text-centered"
                          style="font-size: 12pt; font-weight: bold"
                        >
                          Verifikasi DPJP
                        </h3>
                        <VField>
                          <img
                            v-if="item.dokterDPJP && isAfterSave"
                            :src="
                              'https://api.qrserver.com/v1/create-qr-code/?size=90x90&data=' +
                              (item.dokterDPJP ? item.dokterDPJP.label : '-')
                            "
                          /><br v-if="item.dokterDPJP && isAfterSave" />
                          <VControl class="prime-auto">
                            <VTextarea
                              v-model="item.keteranganVerifikasiDPJP"
                              rows="3"
                              placeholder="Keterangan..."
                              :disabled="item.keteranganVerifikasiDPJP2"
                            >
                            </VTextarea>
                            <Calendar
                              v-model="item.tglVerifikasi"
                              selectionMode="single"
                              :manualInput="true"
                              class="w-100 mt-2"
                              :showIcon="true"
                              showTime
                              hourFormat="24"
                              :date-format="'yy-mm-dd'"
                              placeholder="yy-mm-dd HH:mm"
                              :disabled="item.tglVerifikasi2"
                            />
                            <AutoComplete
                              v-model="item.dokterDPJP"
                              :suggestions="d_Dokter"
                              @complete="fetchDokter($event)"
                              :optionLabel="'label'"
                              :dropdown="true"
                              :minLength="3"
                              :appendTo="'body'"
                              :loadingIcon="'pi pi-spinner'"
                              :field="'label'"
                              placeholder="Verifikasi DPJP..."
                              class="mt-2"
                              :disabled="item.dokterDPJP2"
                            />
                          </VControl>
                        </VField>
                      </div>
                      <div
                        class="column is-4 text-center"
                        v-if="
                          H.formatDate(props.registrasi.tglregistrasi, 'YYYY-MM-DD') >=
                            H.formatDate(new Date(), 'YYYY-MM-DD') ||
                          props.registrasi.objectdepartemenfk != 18
                        "
                      >
                        <h3
                          class="has-text-centered"
                          style="font-size: 12pt; font-weight: bold"
                        >
                          Aksi
                        </h3>
                        <div class="column is-12">
                          <div class="columns is-multiline">
                            <div class="column is-4">
                              <VButton
                                v-if="isFloating"
                                type="button"
                                rounded
                                outlined
                                icon="feather:save"
                                :loading="isLoading"
                                @click="simpanReal"
                                color="success"
                                v-tooltip-prime.top="'Simpan '"
                                >Simpan
                              </VButton>
                              <VButton
                                v-if="isFloating == false"
                                disabled
                                type="button"
                                rounded
                                outlined
                                icon="feather:save"
                                :loading="isLoading"
                                color="success"
                                v-tooltip-prime.top="'Simpan '"
                                >Simpan
                              </VButton>
                            </div>
                            <div class="column is-4">
                              <VButton
                                type="button"
                                v-if="isFloating"
                                rounded
                                outlined
                                color="warning"
                                raised
                                icon="feather:save"
                                :loading="isLoading"
                                @click="simpan('')"
                                v-tooltip-prime.top="'Preview '"
                              >
                                Preview
                              </VButton>
                              <VButton
                                type="button"
                                v-if="isFloating == false"
                                disabled
                                rounded
                                outlined
                                color="warning"
                                raised
                                icon="feather:save"
                                :loading="isLoading"
                                v-tooltip-prime.top="'Preview '"
                              >
                                Preview
                              </VButton>
                            </div>
                            <div class="column is-4">
                              <VButton
                                type="button"
                                rounded
                                outlined
                                icon="feather:plus"
                                @click="addNewItem(item)"
                                color="info"
                                v-tooltip-prime.top="'Tambah Baris '"
                                >Tambah
                              </VButton>
                            </div>
                            <div class="column is-12">
                              <VButton
                                v-if="item.no > 1"
                                type="button"
                                rounded
                                outlined
                                raised
                                circle
                                icon="feather:trash"
                                @click="removeItem(index)"
                                color="danger"
                                v-tooltip-prime.top="'Hapus Baris '"
                                >Hapus
                              </VButton>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
                <tr
                  v-if="item.flag == 'gizi'"
                  :style="[
                    item.flag == 'dokter'
                      ? 'background-color: var(--danger--light-color);'
                      : item.flag == 'perawat'
                      ? 'background-color: var(--info--light-color);'
                      : '',
                  ]"
                >
                  <td>
                    <table class="tg">
                      <tr>
                        <td
                          width="5%"
                          style="
                            font-size: 22pt;
                            width: 5%;
                            font-weight: bold;
                            vertical-align: middle;
                          "
                        >
                          A
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VTextarea
                                v-model="item.AGizi"
                                rows="5"
                                placeholder="Assesment..."
                                :disabled="item.S2"
                                style="padding-left: 30px"
                              >
                              </VTextarea>
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td
                          width="5%"
                          style="
                            font-size: 22pt;
                            width: 5%;
                            font-weight: bold;
                            vertical-align: middle;
                          "
                        >
                          D
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VTextarea
                                v-model="item.DGizi"
                                rows="5"
                                placeholder="Diagnosis..."
                                :disabled="item.O2"
                                style="padding-left: 30px"
                              >
                              </VTextarea>
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td
                          width="5%"
                          style="
                            font-size: 22pt;
                            width: 5%;
                            font-weight: bold;
                            vertical-align: middle;
                          "
                        >
                          I
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VTextarea
                                v-model="item.IGizi"
                                rows="5"
                                placeholder="Intervensi..."
                                :disabled="item.A2"
                                style="padding-left: 30px"
                              >
                              </VTextarea>
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td
                          width="5%"
                          style="
                            font-size: 22pt;
                            width: 5%;
                            font-weight: bold;
                            vertical-align: middle;
                          "
                        >
                          M
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VTextarea
                                v-model="item.MGizi"
                                rows="5"
                                placeholder="Monitoring..."
                                :disabled="item.P2"
                                style="padding-left: 30px"
                              >
                              </VTextarea>
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td
                          width="5%"
                          style="
                            font-size: 22pt;
                            width: 5%;
                            font-weight: bold;
                            vertical-align: middle;
                          "
                        >
                          E
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VTextarea
                                v-model="item.EGizi"
                                rows="5"
                                placeholder="Evaluasi..."
                                :disabled="item.P2"
                                style="padding-left: 30px"
                              >
                              </VTextarea>
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                    </table>
                    <div class="columns is-multiline">
                      <div class="column is-4">
                        <h3
                          class="has-text-centered"
                          style="font-size: 12pt; font-weight: bold"
                        >
                          Instruksi PPA
                        </h3>
                        <VField>
                          <VControl>
                            <VTextarea
                              v-model="item.intruksiPPA"
                              rows="10"
                              placeholder="Intruksi PPA..."
                              :disabled="item.intruksiPPA2"
                            >
                            </VTextarea>
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-4 text-center">
                        <h3
                          class="has-text-centered"
                          style="font-size: 12pt; font-weight: bold"
                        >
                          Verifikasi DPJP
                        </h3>
                        <VField>
                          <img
                            v-if="item.dokterDPJP && isAfterSave"
                            :src="
                              'https://api.qrserver.com/v1/create-qr-code/?size=70x70&data=' +
                              (item.dokterDPJP ? item.dokterDPJP.label : '-')
                            "
                          /><br v-if="item.dokterDPJP && isAfterSave" />
                          <VControl class="prime-auto">
                            <VTextarea
                              v-model="item.keteranganVerifikasiDPJP"
                              rows="5"
                              placeholder="Keterangan..."
                              :disabled="item.keteranganVerifikasiDPJP2"
                            >
                            </VTextarea>
                            <Calendar
                              v-model="item.tglVerifikasi"
                              selectionMode="single"
                              :manualInput="true"
                              class="w-100 mt-2"
                              :showIcon="true"
                              showTime
                              hourFormat="24"
                              :date-format="'yy-mm-dd'"
                              placeholder="yy-mm-dd HH:mm"
                              :locale="calendarLocale"
                              :disabled="item.tglVerifikasi2"
                            />
                            <AutoComplete
                              v-model="item.dokterDPJP"
                              :suggestions="d_Dokter"
                              @complete="fetchDokter($event)"
                              :optionLabel="'label'"
                              :dropdown="true"
                              :minLength="3"
                              :appendTo="'body'"
                              :loadingIcon="'pi pi-spinner'"
                              :field="'label'"
                              placeholder="Verifikasi DPJP..."
                              class="mt-2"
                              :disabled="item.dokterDPJP2"
                            />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-4 text-center">
                        <h3
                          class="has-text-centered"
                          style="font-size: 12pt; font-weight: bold"
                        >
                          Aksi
                        </h3>
                        <div class="columns is-multiline">
                          <div class="column is-4">
                            <VButton
                              v-if="isFloating"
                              type="button"
                              rounded
                              outlined
                              icon="feather:save"
                              :loading="isLoading"
                              @click="simpanReal"
                              color="success"
                              v-tooltip-prime.top="'Simpan '"
                              >Simpan
                            </VButton>
                          </div>
                          <div class="column is-4">
                            <VButton
                              v-if="isFloating"
                              type="button"
                              rounded
                              outlined
                              color="warning"
                              raised
                              icon="feather:save"
                              :loading="isLoading"
                              @click="simpan('')"
                              v-tooltip-prime.top="'Preview '"
                            >
                              Preview
                            </VButton>
                          </div>
                          <div class="column is-4">
                            <VButton
                              type="button"
                              rounded
                              outlined
                              icon="feather:plus"
                              @click="addNewItem(item)"
                              color="info"
                              v-tooltip-prime.top="'Tambah Baris '"
                              >Tambah
                            </VButton>
                          </div>
                          <div class="column is-12">
                            <VButton
                              v-if="item.no > 1"
                              type="button"
                              rounded
                              outlined
                              raised
                              circle
                              icon="feather:trash"
                              @click="removeItem(index)"
                              color="danger"
                              v-tooltip-prime.top="'Hapus Baris '"
                              >Hapus
                            </VButton>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
                <tr
                  :style="[
                    item.flag == 'dokter'
                      ? 'background-color: var(--danger--light-color);'
                      : item.flag == 'perawat'
                      ? 'background-color: var(--info--light-color);'
                      : '',
                  ]"
                >
                  <td colspan="5" style="text-align: center">
                    <VTag
                      class="mr-1 mb-1"
                      :color="
                        item.flag == 'dokter'
                          ? 'danger'
                          : item.flag == 'perawat'
                          ? 'info'
                          : 'light'
                      "
                      :label="item.flag"
                    />
                  </td>
                </tr>
              </tbody>
              <div class="column is-12" v-if="isOpenResep">
                <div class="columns is-multiline">
                  <h3 style="font-size: 22pt; font-weight: bold" @click="goToUp">
                    Farmasi
                  </h3>
                  <VIconButton
                    class="ml-2 mt-2"
                    color="danger"
                    outlined
                    circle
                    icon="lucide:triangle"
                    @click="goToUp"
                    v-tooltip-prime.top="'Go Up'"
                  >
                  </VIconButton>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <OrderResep :pasien="props.pasien" :registrasi="props.registrasi" />
                  </div>
                </div>
              </div>
              <div class="column is-12" v-if="isOpenResepPulang">
                <div class="columns is-multiline">
                  <h3 style="font-size: 22pt; font-weight: bold" @click="goToUp">
                    Farmasi (Resep Pulang)
                  </h3>
                  <VIconButton
                    class="ml-2 mt-2"
                    color="danger"
                    outlined
                    circle
                    icon="lucide:triangle"
                    @click="goToUp"
                    v-tooltip-prime.top="'Go Up'"
                  >
                  </VIconButton>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <OrderResepPulang
                      :pasien="props.pasien"
                      :registrasi="props.registrasi"
                    />
                  </div>
                </div>
              </div>
            </table>
          </div>
        </div>
      </VCard>
    </div>
  </div>

  <Dialog v-model:visible="isLab" modal header="Order Laboratorim">
    <OrderLab
      :pasien="props.pasien"
      :registrasi="props.registrasi"
      @close-modal-lab="isLab = false"
    />
    <template #footer>
      <VButton
        icon="lnir lnir-arrow-left rem-100"
        light
        dark-outlined
        @click="isLab = false"
      >
        Tutup
      </VButton>
    </template>
  </Dialog>
  <Dialog v-model:visible="isRujukan" modal header="Rujukan External">
    <Rujukan
      :pasien="props.pasien"
      :registrasi="props.registrasi"
      @close-modal-rujukan="isRujukan = false"
    />
    <template #footer>
      <VButton
        icon="lnir lnir-arrow-left rem-100"
        light
        dark-outlined
        @click="isRujukan = false"
      >
        Tutup
      </VButton>
    </template>
  </Dialog>
  <Dialog v-model:visible="isKontrol" modal header="Perjanjian/Kontrol">
    <Kontrol
      :pasien="props.pasien"
      :registrasi="props.registrasi"
      @close-modal-skdp="isKontrol = false"
    />
    <template #footer>
      <VButton
        icon="lnir lnir-arrow-left rem-100"
        light
        dark-outlined
        @click="isKontrol = false"
      >
        Tutup
      </VButton>
    </template>
  </Dialog>
  <Dialog v-model:visible="isRad" modal header="Order Radiologi">
    <OrderRad
      :pasien="props.pasien"
      :registrasi="props.registrasi"
      @close-modal-rad="isRad = false"
    />
    <template #footer>
      <VButton
        icon="lnir lnir-arrow-left rem-100"
        light
        dark-outlined
        @click="isRad = false"
      >
        Tutup
      </VButton>
    </template>
  </Dialog>
  <Dialog v-model:visible="isKonsul" modal header="Konsultasi">
    <Konsul
      :pasien="props.pasien"
      :registrasi="props.registrasi"
      @close-modal-konsul="isKonsul = false"
    />
    <template #footer>
      <VButton
        icon="lnir lnir-arrow-left rem-100"
        light
        dark-outlined
        @click="isKonsul = false"
      >
        Tutup
      </VButton>
    </template>
  </Dialog>
  <Dialog v-model:visible="isResep" modal header="Order Resep" :style="{ width: '80vw' }">
    <OrderResep
      :pasien="props.pasien"
      :registrasi="props.registrasi"
      @berhasilSimpan="tutupModalisResep"
    />
    <template #footer>
      <VButton
        icon="lnir lnir-arrow-left rem-100"
        light
        dark-outlined
        @click="isResep = false"
      >
        Tutup
      </VButton>
    </template>
  </Dialog>
  <Dialog
    v-model:visible="isResepPulang"
    modal
    header="Order Resep"
    :style="{ width: '80vw' }"
  >
    <OrderResepPulang
      :pasien="props.pasien"
      :registrasi="props.registrasi"
      @berhasilSimpan="tutupModalisResep"
    />
    <template #footer>
      <VButton
        icon="lnir lnir-arrow-left rem-100"
        light
        dark-outlined
        @click="isResep = false"
      >
        Tutup
      </VButton>
    </template>
  </Dialog>
  <Dialog
    v-model:visible="isCPPTOLD"
    modal
    header="Riwayat CPPT"
    :style="{ width: '80vw' }"
  >
    <div class="columns is-multiline">
      <div class="column is-12" v-if="isloadingLAMPAU">
        <div class="flex-list-inner mb-2 mt-5">
          <div class="flex-table-item grid-item mb-1" v-for="key in 5" :key="key">
            <VPlaceloadWrap>
              <VPlaceloadAvatar size="small" />
              <VPlaceloadText last-line-width="60%" class="mx-2" />
              <VPlaceload class="mx-2" disabled />
              <VPlaceload class="mx-2 h-hidden-tablet-p" />
              <VPlaceload class="mx-2 h-hidden-tablet-p" />
              <VPlaceload class="mx-2" />
            </VPlaceloadWrap>
          </div>
        </div>
      </div>
      <div class="column is-12" v-else-if="!isloadingLAMPAU">
        <div class="flex-list-inner" v-if="input2.length === 0">
          <VPlaceholderSection :title="H.assets().notFound" class="my-6">
            <template #image>
              <img
                class="light-image"
                :src="H.assets().iconNotFound_rev"
                alt=""
                style="width: 100px"
              />
              <img
                class="dark-image"
                src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                alt=""
                style="width: 100px"
              />
            </template>
          </VPlaceholderSection>
        </div>
        <VCard class="tg-card" v-if="input2.length">
          <div class="columns is-multiline mt-3">
            <div class="column is-12 CPPT_HEIGHT">
              <table class="tg">
                <thead></thead>
                <tbody v-for="(itemss, index) in input2" :key="index">
                  <tr style="background: #d6d4d4">
                    <td>
                      <VIconButton
                        circle
                        icon="feather:chevron-down"
                        raised
                        bold
                        v-tooltip-prime.top="' Collapsed'"
                        v-if="itemss.show"
                        @click="itemss.show = false"
                      >
                      </VIconButton>
                      <VIconButton
                        circle
                        icon="feather:chevron-right"
                        raised
                        bold
                        v-tooltip-prime.top="' Expand'"
                        v-if="!itemss.show"
                        @click="itemss.show = true"
                      >
                      </VIconButton>
                    </td>
                    <td>
                      <span class="text-normal-1 bold">
                        <b>
                          {{ itemss.registrasi ? itemss.registrasi.noregistrasi : '' }} -
                          {{
                            itemss.registrasi
                              ? H.formatDateIndo(itemss.registrasi.tglregistrasi)
                              : ''
                          }} </b
                        ><br /><b>
                          {{ itemss.registrasi ? itemss.registrasi.namaruangan : '' }}
                          - {{ itemss.registrasi ? itemss.registrasi.dokter : '' }}
                        </b>
                      </span>
                    </td>
                    <td>
                      <span class="text-normal-1 bold" v-if="itemss.noemr == ''">
                        <VTag
                          :color="'danger'"
                          class="is-pulled-right"
                          :label="'SIMRS LAMA'"
                          rounded
                          outlined
                        />
                      </span>
                    </td>
                  </tr>
                  <tr v-if="itemss.show">
                    <td colspan="3" style="size: 100%">
                      <table class="tg">
                        <tr>
                          <th class="tg-0lax text-center font-bold">Tanggal/Jam</th>
                          <th class="tg-0lax text-center">
                            Catatan Perkembangan Pasien Terintegrasi
                          </th>
                          <th class="tg-0lax text-center">Intruksi PPA</th>
                          <th class="tg-0lax text-center">Verifikasi DPJP</th>
                          <th class="tg-0lax text-center" style="width: 7%">#</th>
                        </tr>
                        <tr>
                          <td colspan="5">
                            <div class="columns is-multiline">
                              <div class="column is-3">
                                <VButton
                                  color="warning"
                                  class="w-100 btn-slim"
                                  light
                                  rounded
                                  v-tooltip-prime.right="'Radiologi'"
                                  :loading="itemss.isLoadingRad"
                                  @click="hasilRad(itemss.registrasi.norec_pd, itemss)"
                                >
                                  Radiologi
                                </VButton>
                              </div>
                              <div class="column is-3">
                                <VButton
                                  color="danger"
                                  class="w-100 btn-slim"
                                  light
                                  rounded
                                  v-tooltip-prime.right="'Laboratorium'"
                                  :loading="itemss.isLoading"
                                  @click="hasilLab(itemss.registrasi.norec_pd, itemss)"
                                >
                                  Laboratorium
                                </VButton>
                              </div>
                              <div class="column is-3">
                                <VButton
                                  color="info"
                                  class="w-100 btn-slim"
                                  light
                                  rounded
                                  v-tooltip-prime.right="'PA'"
                                  :loading="itemss.isLoadingPA"
                                  @click="hasilLabPA(itemss.registrasi.norec_pd, itemss)"
                                >
                                  PA
                                </VButton>
                              </div>
                              <div class="column is-3">
                                <VButton
                                  color="success"
                                  class="w-100 btn-slim"
                                  light
                                  rounded
                                  v-tooltip-prime.right="'Berkas Pasien'"
                                  :loading="itemss.isLoadingBerkas"
                                  @click="
                                    berkasPasien(itemss.registrasi.norec_pd, itemss)
                                  "
                                >
                                  Lain-lain
                                </VButton>
                              </div>
                            </div>
                          </td>
                        </tr>
                      </table>
                      <table class="tg">
                        <tr v-for="(item, index2) in itemss.details" :key="index2">
                          <td style="width: 15%">
                            <span class="mb-2">{{ item.tgl }}</span
                            ><br />
                            <span>{{
                              item.tenagaMedis ? item.tenagaMedis.label : '-'
                            }}</span>
                          </td>
                          <td>
                            <table class="tg">
                              <tr>
                                <td
                                  width="5%"
                                  style="
                                    width: 5%;
                                    font-weight: bold;
                                    vertical-align: middle;
                                    font-size: 18px;
                                  "
                                >
                                  S
                                </td>
                                <td>
                                  {{ item.S ?? '' }}
                                </td>
                                <td width="5%" v-if="item.flag == 'dokter'">
                                  <VIconButton
                                    circle
                                    icon="feather:copy"
                                    color="primary"
                                    raised
                                    bold
                                    outlined
                                    @click="copyS(itemss.details[0].S)"
                                    class="ml-1"
                                    v-tooltip-prime.top="'Copy '"
                                  >
                                  </VIconButton>
                                </td>
                                <td width="5%" v-if="item.flag !== 'dokter'">
                                  <VIconButton
                                    circle
                                    icon="feather:copy"
                                    color="primary"
                                    raised
                                    bold
                                    outlined
                                    @click="copyToClipboardS(item.S)"
                                    class="ml-1"
                                    v-tooltip-prime.top="'Copy'"
                                  >
                                  </VIconButton>
                                </td>
                              </tr>
                              <tr>
                                <td
                                  width="5%"
                                  style="
                                    width: 5%;
                                    font-weight: bold;
                                    vertical-align: middle;
                                    font-size: 18px;
                                  "
                                >
                                  O
                                </td>
                                <td>
                                  {{ item.O ?? '' }}
                                </td>
                                <td width="5%" v-if="item.flag == 'dokter'">
                                  <VIconButton
                                    circle
                                    icon="feather:copy"
                                    color="primary"
                                    raised
                                    bold
                                    outlined
                                    @click="copyO(itemss.details[0].O)"
                                    class="ml-1"
                                    v-tooltip-prime.top="'Copy '"
                                  >
                                  </VIconButton>
                                </td>
                                <td width="5%" v-if="item.flag !== 'dokter'">
                                  <VIconButton
                                    circle
                                    icon="feather:copy"
                                    color="primary"
                                    raised
                                    bold
                                    outlined
                                    @click="copyToClipboardO(item.O)"
                                    class="ml-1"
                                    v-tooltip-prime.top="'Copy'"
                                  >
                                  </VIconButton>
                                </td>
                              </tr>
                              <tr>
                                <td
                                  width="5%"
                                  style="
                                    width: 5%;
                                    font-weight: bold;
                                    vertical-align: middle;
                                    font-size: 18px;
                                  "
                                >
                                  A
                                </td>
                                <td v-if="item.flag == 'profesi lain'">
                                  {{ item.A ?? '' }}
                                </td>
                                <td v-if="item.flag == 'dokter'">
                                  <div class="columns is-multiline">
                                    <div class="column is-12">
                                      <span style="font-size: 9pt; font-weight: bold"
                                        >Diagnosis ICD 10</span
                                      >
                                      <div style="overflow-y: auto" class="mt-1">
                                        <table class="tg" style="width: 100% !important">
                                          <thead>
                                            <tr>
                                              <th
                                                class="td-fkprj"
                                                width="23%"
                                                style="
                                                  vertical-align: inherit;
                                                  text-align: center;
                                                  font-size: 9pt;
                                                "
                                              >
                                                Jenis
                                              </th>
                                              <th
                                                class="td-fkprj"
                                                width="25%"
                                                style="
                                                  vertical-align: inherit;
                                                  text-align: center;
                                                  font-size: 9pt;
                                                "
                                              >
                                                Diagnosa Dokter
                                              </th>
                                              <th
                                                class="td-fkprj"
                                                style="
                                                  vertical-align: inherit;
                                                  text-align: center;
                                                  font-size: 9pt;
                                                "
                                              >
                                                ICD 10
                                              </th>
                                              <th
                                                class="td-fkprj"
                                                style="
                                                  vertical-align: inherit;
                                                  text-align: center;
                                                  font-size: 9pt;
                                                "
                                              >
                                                <VIconButton
                                                  circle
                                                  icon="feather:copy"
                                                  color="primary"
                                                  raised
                                                  bold
                                                  outlined
                                                  @click="
                                                    copyDiagnosa(
                                                      itemss.details[0].diagnosaDokter
                                                    )
                                                  "
                                                  class="ml-1"
                                                  v-tooltip-prime.top="'Copy Diagnosa '"
                                                >
                                                </VIconButton>
                                              </th>
                                            </tr>
                                          </thead>
                                          <tbody
                                            v-for="(
                                              itemsss, index3
                                            ) in item.diagnosaDokter"
                                            :key="index3"
                                          >
                                            <tr v-if="itemsss.jenisDiagnosa">
                                              <td class="tg-0lax">
                                                <div class="column p-1">
                                                  {{
                                                    itemsss.jenisDiagnosa
                                                      ? itemsss.jenisDiagnosa.label
                                                      : ''
                                                  }}
                                                </div>
                                              </td>
                                              <td class="tg-0lax">
                                                <div class="column pt-3 pb-0">
                                                  {{ itemsss.keterangan ?? '' }}
                                                </div>
                                              </td>
                                              <td class="tg-0lax">
                                                <div class="column p-1">
                                                  {{
                                                    itemsss.diagnosaa
                                                      ? itemsss.diagnosaa.label
                                                      : ''
                                                  }}
                                                </div>
                                              </td>
                                              <td width="5%">
                                                <VIconButton
                                                  circle
                                                  icon="feather:copy"
                                                  color="primary"
                                                  raised
                                                  bold
                                                  outlined
                                                  @click="
                                                    copyItemDiagnosa(itemsss, 'ICD10')
                                                  "
                                                  class="ml-1"
                                                  v-tooltip-prime.top="'Copy Diagnosa '"
                                                >
                                                </VIconButton>
                                              </td>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </div>
                                    </div>
                                    <div class="column is-12">
                                      <span style="font-size: 9pt; font-weight: bold"
                                        >Diagnosis ICD 9</span
                                      >
                                      <div style="overflow-y: auto" class="mt-1">
                                        <table class="tg" width="100%">
                                          <thead>
                                            <tr>
                                              <th
                                                class="td-fkprj"
                                                width="40%"
                                                style="
                                                  vertical-align: inherit;
                                                  text-align: center;
                                                  font-size: 9pt;
                                                "
                                              >
                                                Keterangan
                                              </th>
                                              <th
                                                class="td-fkprj"
                                                style="
                                                  vertical-align: inherit;
                                                  text-align: center;
                                                  font-size: 9pt;
                                                "
                                              >
                                                ICD 9
                                              </th>
                                              <th
                                                class="td-fkprj"
                                                style="
                                                  vertical-align: inherit;
                                                  text-align: center;
                                                  font-size: 9pt;
                                                "
                                              >
                                                <VIconButton
                                                  circle
                                                  icon="feather:copy"
                                                  color="primary"
                                                  raised
                                                  bold
                                                  outlined
                                                  @click="
                                                    copyDiagnosa9(
                                                      itemss.details[0].diagnosaDokter9
                                                    )
                                                  "
                                                  class="ml-1"
                                                  v-tooltip-prime.top="'Copy Diagnosa '"
                                                >
                                                </VIconButton>
                                              </th>
                                            </tr>
                                          </thead>
                                          <tbody
                                            v-for="(
                                              itemsss, index3
                                            ) in item.diagnosaDokter9"
                                            :key="index3"
                                          >
                                            <tr>
                                              <td class="tg-0lax">
                                                <div class="column pt-3 pb-0">
                                                  {{ itemsss.keterangan ?? '' }}
                                                </div>
                                              </td>
                                              <td class="tg-0lax">
                                                <div class="column p-1">
                                                  {{
                                                    itemsss.diagnosaa
                                                      ? itemsss.diagnosaa.label
                                                      : ''
                                                  }}
                                                </div>
                                              </td>
                                              <td class="tg-0lax">
                                                <div class="column p-1">
                                                  <VIconButton
                                                    circle
                                                    icon="feather:copy"
                                                    color="primary"
                                                    raised
                                                    bold
                                                    outlined
                                                    @click="
                                                      copyItemDiagnosa9(itemsss, 'ICD9')
                                                    "
                                                    class="ml-1"
                                                    v-tooltip-prime.top="'Copy Diagnosa '"
                                                  >
                                                  </VIconButton>
                                                </div>
                                              </td>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </div>
                                    </div>
                                  </div>
                                </td>
                                <td v-if="item.flag == 'perawat'">
                                  <div class="column">
                                    <span style="font-size: 11pt; font-weight: bold"
                                      >Diagnosis Keperawatan</span
                                    >
                                    <div class="mt-1">
                                      <table class="tg">
                                        <thead>
                                          <tr>
                                            <th
                                              class="td-fkprj"
                                              width="50%"
                                              style="
                                                vertical-align: inherit;
                                                text-align: center;
                                              "
                                            >
                                              Diagnosa Keperawatan
                                            </th>
                                          </tr>
                                        </thead>
                                        <tbody
                                          v-for="(item2, index2) in item.diagnosaKep"
                                          :key="index2"
                                        >
                                          <tr>
                                            <td class="tg-0lax">
                                              <div class="column p-1">
                                                {{
                                                  item2.diagnosaKeperawatan
                                                    ? item2.diagnosaKeperawatan.label
                                                    : '-'
                                                }}
                                              </div>
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </td>
                                <td width="5%" v-if="item.flag == 'profesi lain'">
                                  <VIconButton
                                    circle
                                    icon="feather:copy"
                                    color="primary"
                                    raised
                                    bold
                                    outlined
                                    @click="copyToClipboard(item.A)"
                                    class="ml-1"
                                    v-tooltip-prime.top="'Copy '"
                                  >
                                  </VIconButton>
                                </td>
                                <td
                                  width="5%"
                                  style="vertical-align: middle"
                                  v-if="item.flag == 'dokter'"
                                >
                                  <VIconButton
                                    circle
                                    icon="feather:copy"
                                    color="info"
                                    raised
                                    bold
                                    outlined
                                    @click="copyDiagnosaAssesment(itemss.details[0])"
                                    class="ml-1"
                                    v-tooltip-prime.top="'Copy Assesment'"
                                  >
                                  </VIconButton>
                                </td>
                              </tr>
                              <tr>
                                <td
                                  width="5%"
                                  style="
                                    width: 5%;
                                    font-weight: bold;
                                    vertical-align: middle;
                                    font-size: 18px;
                                  "
                                >
                                  P
                                </td>
                                <td>
                                  {{ item.P ?? '' }}

                                  <div class="column" v-if="item.flag == 'perawat'">
                                    <span style="font-size: 11pt; font-weight: bold"
                                      >Tujuan Kriteria (SLKI)
                                    </span>
                                    <div class="mt-1">
                                      <table class="tg">
                                        <thead>
                                          <tr>
                                            <th
                                              class="td-fkprj"
                                              width="50%"
                                              style="
                                                vertical-align: inherit;
                                                text-align: center;
                                              "
                                            >
                                              Tujuan Keperawatan
                                            </th>
                                          </tr>
                                        </thead>
                                        <tbody
                                          v-for="(item2, index2) in item.tujuanKep"
                                          :key="index2"
                                        >
                                          <tr>
                                            <td class="tg-0lax">
                                              <div class="column p-1">
                                                {{
                                                  item2.tujuanKeperawatan
                                                    ? item2.tujuanKeperawatan.label
                                                    : '-'
                                                }}
                                              </div>
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </td>
                                <td width="5%" v-if="item.flag == 'dokter'">
                                  <VIconButton
                                    circle
                                    icon="feather:copy"
                                    color="primary"
                                    raised
                                    bold
                                    outlined
                                    @click="copyP(itemss.details[0].P)"
                                    class="ml-1"
                                    v-tooltip-prime.top="'Copy '"
                                  >
                                  </VIconButton>
                                </td>
                                <td width="5%" v-if="item.flag !== 'dokter'">
                                  <VIconButton
                                    circle
                                    icon="feather:copy"
                                    color="primary"
                                    raised
                                    bold
                                    outlined
                                    @click="copyToClipboardP(item.P)"
                                    class="ml-1"
                                    v-tooltip-prime.top="'Copy'"
                                  >
                                  </VIconButton>
                                </td>
                              </tr>
                            </table>
                          </td>
                          <td style="width: 15%">
                            {{ item.intruksiPPA ?? '' }}
                          </td>

                          <td style="width: 15%" class="text-center">
                            <img
                              v-if="item.dokterDPJP"
                              :src="
                                'https://api.qrserver.com/v1/create-qr-code/?size=70x70&data=' +
                                (item.dokterDPJP ? item.dokterDPJP.label : '-')
                              "
                            /><br />

                            <span> {{ item.keteranganVerifikasiDPJP ?? '' }}</span> <br />
                            <span> {{ item.tglVerifikasi ?? '' }}</span> <br />
                            <span>
                              {{ item.dokterDPJP ? item.dokterDPJP.label : '' }}</span
                            >
                            <br />
                          </td>
                          <td style="width: 7%; vertical-align: text-top">
                            <VIconButton
                              circle
                              icon="feather:copy"
                              color="primary"
                              raised
                              bold
                              @click="copy(itemss.details[0])"
                              class="ml-1"
                              v-tooltip-prime.top="'Copy SEMUA'"
                            >
                            </VIconButton>
                          </td>
                        </tr>
                      </table>
                      <p
                        style="font-size: 18px; margin-top: 12px; margin-bottom: 6px"
                        v-if="itemss.riwayatResep.length > 0"
                      >
                        Order Resep
                      </p>
                      <table class="tg table-tg" v-if="itemss.riwayatResep.length > 0">
                        <thead>
                          <tr>
                            <td class="tg-0lax text-center">Tanggal/Jam</td>
                            <td class="tg-0lax text-center">Nama Produk</td>
                            <td class="tg-0lax text-center">Aturan Pakai</td>
                            <td class="tg-0lax text-center">Jumlah</td>
                          </tr>
                        </thead>
                        <tbody v-for="(resep, i) in itemss.riwayatResep">
                          <tr>
                            <td style="width: 15%">
                              <span class="mb-2">{{ resep.tglorder }}</span
                              ><br />
                            </td>
                            <td style="width: 26%">
                              <span class="mb-2">{{ resep.namaproduk }}</span
                              ><br />
                            </td>
                            <td style="width: 10%; text-align: center">
                              <span class="mb-2">{{ resep.aturanpakai }}</span
                              ><br />
                            </td>
                            <td style="width: 10%; text-align: center">
                              <span class="mb-2">{{ resep.jumlah }}</span
                              ><br />
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </VCard>
      </div>
    </div>
    <template #footer>
      <VButton
        icon="lnir lnir-arrow-left rem-100"
        light
        dark-outlined
        @click="isCPPTOLD = false"
      >
        Tutup
      </VButton>
    </template>
  </Dialog>
  <Dialog
    v-model:visible="isHasilLab"
    modal
    header="Laboratorium"
    :style="{ width: '40vw' }"
  >
    <HasilLabPreview :laboratorium="item.lab" :laboratorium_GROUP="item.lab_GROUP" />
  </Dialog>
  <Dialog
    v-model:visible="isHasilRad"
    modal
    header="Radiologi"
    :style="{ width: '40vw' }"
  >
    <HasilRadPreview :data="item.radiologi" />
  </Dialog>
  <Dialog v-model:visible="isBerkas" modal header="Berkas" :style="{ width: '40vw' }">
    <BerkasPasienView :data="item.berkas" :hide="true" @lihat="lihatBerkas" />
  </Dialog>
  <Dialog v-model:visible="isPA" modal header="PA" :style="{ width: '40vw' }">
    <VCard
      v-for="items in item.patologi"
      class="mt-1"
      v-if="item.patologi.length"
      style="background-color: var(--info--light-color)"
    >
      <div class="columns is-multiline">
        <div class="column is-12">
          <h3 class="title is-6 mb-2">
            {{ items.namaproduk }}
            <VTag
              :color="'info'"
              class="is-pulled-right"
              :label="H.formatDateIndoSimple(items.tanggalpemeriksaan)"
              rounded
            />
          </h3>
        </div>
        <div class="column is-12">
          <TStatusPojokKanan
            :title="'Makroskopik'"
            :subtitle="items.makroskopik"
            class="inbox-widget-2 w-100"
          />
          <TStatusPojokKanan
            :title="'Mikroskopik'"
            :subtitle="items.mikroskopik"
            class="inbox-widget-2"
          />
          <TStatusPojokKanan
            :title="'Kesimpulan'"
            :subtitle="items.kesimpulan"
            class="inbox-widget-2"
          />
          <TStatusPojokKanan
            :title="'Anjuran'"
            :subtitle="items.anjuran"
            class="inbox-widget-2"
          />
        </div>
      </div>
    </VCard>
    <VPlaceholderPage
      v-else
      :title="H.assets().notFound"
      :subtitle="H.assets().notFoundSubtitle"
      larger
    >
      <template #image>
        <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
        <img
          class="dark-image"
          src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
          alt=""
        />
      </template>
    </VPlaceholderPage>
  </Dialog>
  <Dialog
    v-model:visible="isDialogSave"
    modal
    header="Preview"
    :style="{ width: '100vw' }"
  >
    <CpptPreviewRev
      :cppt="input.details"
      :show_resep="true"
      :norec_pd="NOREC_PD"
      :nocmfk="ID_PASIEN"
      :nocm="NOCM"
      :noregistrasi="NOREGISTRASI"
    />
    <template #footer>
      <VButton
        icon="lnir lnir-arrow-left rem-100"
        light
        dark-outlined
        @click="isDialogSave = false"
      >
        Tutup
      </VButton>
      <VButton
        type="button"
        rounded
        outlined
        color="primary"
        class="ml-2"
        raised
        icon="feather:save"
        :loading="isLoading"
        @click="simpanPreview"
      >
        Simpan
      </VButton>
    </template>
  </Dialog>
  <Dialog
    v-model:visible="showData"
    maximizable
    modal
    header="Hasil Laboratorium"
    :style="{ width: '80rem' }"
    :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
  >
    <div class="columns is-multiline">
      <div class="column is-6">
        <VCard class="bt-color">
          <h3 style="font-weight: 800">Pemeriksaan Penunjang</h3>
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
          <DataTable
            dataKey="no"
            :value="sourceDataLab"
            v-model:selection="selectedResep"
            class="p-datatable-sm"
            :paginator="true"
            :rows="10"
            scrollable
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack"
            breakpoint="960px"
            sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
            showGridlines
          >
            <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
            <Column
              field="no"
              header="No"
              :sortable="true"
              style="min-width: 50px"
            ></Column>
            <Column
              field="nama_pemeriksaan"
              header="Layanan"
              frozen
              :sortable="true"
              style="min-width: 200px"
            >
            </Column>
            <Column
              field="hasil"
              header="Hasil"
              :sortable="true"
              style="min-width: 100px"
            ></Column>
            <Column
              field="normal"
              header="Nilai Normal"
              :sortable="true"
              style="min-width: 100px"
            ></Column>
            <!-- <Column field="jumlahobat" header="Jumlah Obat" :sortable="true" style="min-width: 200px"></Column> -->
            <Column
              field="tgl_hasil"
              header="tanggal"
              :sortable="true"
              style="min-width: 200px"
            ></Column>
          </DataTable>

          <div class="column is-12">
            <VButton
              color="primary"
              raised
              @click="tambahHasilLab(selectedResep)"
              outlined
              :loading="isLoadTmb"
              ><i class="fas fa-plus mr-3 p-0" aria-hidden="true"></i>Tambah</VButton
            >
          </div>
        </VCard>
      </div>
    </div>
    <template #footer>
      <VButton
        color="primary"
        raised
        @click="simpanHasilPenunjang(item.sourcePemeriksaanPenunjang)"
        ><i class="fas fa-save mr-3" aria-hidden="true"></i> Simpan</VButton
      >
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
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete'
import Dropdown from 'primevue/dropdown'
import Calendar from 'primevue/calendar'
import Dialog from 'primevue/dialog'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import { v4 as uuidv4 } from 'uuid'
import sleep from '/@src/utils/sleep'
import OrderLab from './order-laboratorium.vue'
import OrderRad from './order-radiologi.vue'
import OrderResep from './order-resep.vue'
import OrderResepPulang from './order-resep-pulang.vue'
import Konsul from './konsultasi.vue'
import FloatingButtonSave from './float-button-save-cppt.vue'
import FloatingButtonPreview from './float-button-preview-cppt.vue'
import FloatingButtonResep from './float-button-resep-cppt.vue'
import FloatingButtonResepPulang from './float-button-resep-pulang-cppt.vue'
import FloatingButtonKonsul from './float-button-konsul-cppt.vue'
import FloatingButtonRadiologi from './float-button-radiologi-cppt.vue'
import FloatingButtonLaborat from './float-button-laborat-cppt.vue'
import FloatingButtonRujukan from './float-button-rujukan-cppt.vue'
import FloatingButtonKontrol from './float-button-kontrol-cppt.vue'
import HasilLabPreview from './hasil-lab-preview.vue'
import HasilRadPreview from './hasil-rad-preview.vue'
import BerkasPasienView from './berkas-pasien-preview.vue'
import TStatusPojokKanan from '../t-status-pojok-kanan.vue'
import CpptPreviewRev from './cppt-preview-rev.vue'
import Rujukan from '/@src/pages/module/integrasi-sistem/rujukan.vue'
import Kontrol from '/@src/pages/module/emr/profile-pasien/page-emr/perjanjian-kontrol.vue'
import Chip from 'primevue/chip'
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const props = withDefaults(
  defineProps<{
    pasien?: any
    registrasi?: any
    isDisableSimpanButton?: any
    FORM_NAME?: string
    FORM_URL?: string
    COLLECTION?: string
  }>(),
  {
    pasien: {},
    registrasi: {},
    isDisableSimpanButton: false,
    FORM_NAME: '',
    FORM_URL: '',
    COLLECTION: '',
  }
)
let NOCM = props.pasien.nocm
let NOREGISTRASI = props.registrasi.noregistrasi
let hasilPenunjangs: any = ref([])
const router = useRouter()
const isFloating: any = ref(true)
const isResep: any = ref(false)
const isOpenResep: any = ref(false)
const isOpenResepPulang: any = ref(false)
const isLab: any = ref(false)
const isRujukan: any = ref(false)
const isKontrol: any = ref(false)
const isRad: any = ref(false)
const isPA: any = ref(false)
const isKonsul: any = ref(false)
const isBerkas: any = ref(false)
const isDokter: any = ref(false)
const isPerawat: any = ref(false)
const isProfesi: any = ref(false)
const isGizi: any = ref(false)
const isSbar: any = ref(false)
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value >= 20
})
const isLoading: any = ref(false)
const isloadingLAMPAU: any = ref(false)
const isCPPTOLD: any = ref(false)
const isHasilLab: any = ref(false)
const isHasilRad: any = ref(false)
const showData: any = ref(false)
const selectedResep = ref()
const isLoadTmb: any = ref(false)
const scrollContainer = ref(null)
const tujuanKeper = ref('')
const rowGroupLAB: any = ref({})
const d_DiagnosaKeperawatan: any = ref([])
const d_JenisDiagnosa: any = ref([])
const d_Diagnosa: any = ref([])
const d_Diagnosa9: any = ref([])
const d_Diagnosa10: any = ref([])
const d_CopyDiagnosa: any = ref([])
const d_TujuanKeperawatan: any = ref([])
const d_IntervensiKeperawatan: any = ref([])
const sourceDataLab = ref([])
const isAfterSave: any = ref(false)
const isDialogSave: any = ref(false)
const showPreviewResep: any = ref(false)
const userLogin = useUserSession().getUser()
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  selectedMenu: [false],
  filter: '',
  IGizi: '',
  lab: [],
  lab_GROUP: [],
  radiologi: [],
  patologi: [],
})
const pegawaiId = useUserSession().getUser().pegawai.id
const COLLECTION: any = ref('CatatanPerkembanganPasienTerintegrasi')
const NOREC_EMRPASIEN: any = ref(norec_emr ? norec_emr : '')
const array_dokter: any = ref({
  uuid: uuidv4(),
  no: 1,
  tgl: new Date(),
  tglVerifikasi: new Date(),
  flag: 'dokter',
  diagnosaDokter: [
    {
      no: 1,
    },
  ],
  diagnosaDokter9: [
    {
      no: 1,
    },
  ],
})
const array_perawat: any = ref({
  uuid: uuidv4(),
  no: 1,
  tgl: new Date(),
  tglVerifikasi: new Date(),
  flag: 'perawat',
  diagnosaKep: [
    {
      no: 1,
    },
  ],
  tujuanKep: [
    {
      no: 1,
    },
  ],
})
const array_profesi: any = ref({
  uuid: uuidv4(),
  no: 1,
  tgl: new Date(),
  tglVerifikasi: new Date(),
  flag: 'profesi lain',
})
const array_gizi: any = ref({
  uuid: uuidv4(),
  no: 1,
  tgl: new Date(),
  tglVerifikasi: new Date(),
  flag: 'gizi',
})
const array_sbar: any = ref({
  uuid: uuidv4(),
  no: 1,
  tgl: new Date(),
  tglVerifikasi: new Date(),
  flag: 'sbar',
})
const input: any = ref({
  details: [
    array_dokter.value,
    array_perawat.value,
    array_sbar.value,
    array_profesi.value,
    array_gizi.value,
  ],
})
const input2: any = ref([])
const riwayatResep: any = ref([])
const d_Dokter: any = ref([])
const d_Pegawai: any = ref([])
const listDiagnosa: any = ref([])
const listDiagnosa9: any = ref([])
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const tutupModalisResep = async () => {
  isResep.value = false
  isResepPulang.value = false
}
const pasteResep = () => {
  const resep = localStorage.getItem('resep') // Mengambil resep dari localStorage
  if (resep) {
    item.value.IGizi = resep // Tempelkan resep ke dalam item.IGizi
  } else {
    console.log('Tidak ada resep di localStorage')
  }
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
  })
  await useApi()
    .get(
      `/laboratorium/hasil-lab?noregistrasi=${props.registrasi.noregistrasi}&nourut=${nourut}&noorder=${noorder}`
    )
    .then((response) => {
      response.forEach((element) => {
        sementara = [...new Set([...sementara, element.hasillab])]
      })
      sementara.forEach((element) => {
        item.sourcePemeriksaanPenunjang += element
      })
      isLoadTmb.value = false
    })

  hasilPenunjangs.value.forEach((element) => {
    item.sourcePemeriksaanPenunjang += element
  })
}

const SourceHasilLab = async () => {
  await useApi()
    .get(`/laboratorium/source-hasil-lab?noregistrasi=${props.registrasi.noregistrasi}`)
    .then((response) => {
      response.forEach((element: any, i: any) => {
        element.no = i + 1
      })
      sourceDataLab.value = response
    })
}

const getHasilRad = async () => {
  await useApi()
    .get(
      `/emr/get-hasil-radiologi?noregistrasi=${props.registrasi.noregistrasi}&norec_pd=${props.registrasi.norec_pd}`
    )
    .then((response: any) => {
      if (response.length > 0) {
        // input.value.details[0].O = ''
        response.forEach((element) => {
          if (sourceDataLab.value.length == 0) {
            input.value.details[0].O += element.hasilexpertise.replace(/#&#/g, '\n')
          }
          hasilPenunjangs.value.push(element.hasilexpertise.replace(/#&#/g, '\n'))
        })
      }
    })
}

const show = () => {
  showData.value = true
}

const simpanHasilPenunjang = (e: any) => {
  // input.value.details[0].O = ''
  if (!e) {
    H.alert('error', 'Hasil Penunjang Kosong')
    return
  }

  // input.value.details[0].O += e'\n'
  input.value.details[0].O += '\n' + e
  showData.value = false
}

const OpenResep = async () => {
  isOpenResep.value = true
  isOpenResepPulang.value = false
  await new Promise((resolve) => setTimeout(resolve, 100))

  window.scrollTo({
    top: document.body.scrollHeight,
    behavior: 'smooth',
  })
}
const OpenResepPulang = async () => {
  isOpenResepPulang.value = true
  isOpenResep.value = false
  await new Promise((resolve) => setTimeout(resolve, 100))

  window.scrollTo({
    top: document.body.scrollHeight,
    behavior: 'smooth',
  })
}
const loadRiwayat = async () => {
  isAfterSave.value = false
  isloadingLAMPAU.value = true

  await setAutoFill()

  useApi()
    .get(
      `/emr/get-emr-cppt?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    )
    .then((response: any) => {
      isloadingLAMPAU.value = false
      if (response.length > 0) {
        let perawat = []
        let dokter = []
        isAfterSave.value = true
        response[0].details.forEach((element) => {
          if (element.flag == 'perawat') {
            perawat.push(element)
            if (!element.tenagaMedis) {
              element.tenagaMedis = {
                label: useUserSession().getUser().pegawai.namaLengkap,
                value: useUserSession().getUser().pegawai.id,
              }
            }
          }
          if (element.flag == 'dokter') {
            if (element.diagnosaDokter.length === 0) {
              element.diagnosaDokter.push({
                no: 1,
              })
            }
            if (!element.tenagaMedis) {
              if (useUserSession().getUser().kelompokUser.kelompokUser == 'dokter') {
                element.tenagaMedis = {
                  label: useUserSession().getUser().pegawai.namaLengkap,
                  value: useUserSession().getUser().pegawai.id,
                }
              }
            }
            // element.tenagaMedis = { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk }
            dokter.push(element)
          }
          element.dokterDPJP = {
            label: props.registrasi.dokter,
            value: props.registrasi.objectpegawaifk,
          }
        })
        for (let x = 0; x < response[0].details.length; x++) {
          const element = response[0].details[x]
          element.tgl = H.formatDate(new Date(element.tgl), 'YYYY-MM-DD HH:mm')
          element.tglVerifikasi = H.formatDate(
            new Date(element.tglVerifikasi),
            'YYYY-MM-DD HH:mm'
          )

          if (element.flag == 'perawat') {
            dokter.forEach((item) => {
              if (item.no == element.no) {
                if (!item.S) {
                  item.S = element.S
                }
                if (!item.O) {
                  item.O = element.O
                }
                if (!item.P) {
                  item.P = element.P
                }
              }
            })
            if (element.tujuanKep == undefined || element.tujuanKep.length == 0) {
              element.tujuanKep = [
                {
                  no: 1,
                },
              ]
            }
            if (element.diagnosaKep == undefined || element.diagnosaKep.length == 0) {
              element.diagnosaKep = [
                {
                  no: 1,
                },
              ]
            }
          }
        }
        input.value = response[0]
        input.value.dpjpUtama = props.registrasi.dokter
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        addItem(item.filter)
      }
    })
  await useApi()
    .get(
      'emr/auto-fill?norec_pd=' +
        props.registrasi.norec_pd +
        '&collection=resumeMedis' +
        '&field=statusSelesai'
    )
    .then((response) => {
      if (response == null) {
        fetchJenisDiagnosa('')
        fetchDiagnosaX()
        fetchDiagnosaIX()
      }
    })
}

const clearItem = (index: any, element: string) => {
  input.value.details[index][element] = ''
}

const addDiagnosaDok = async (data: any) => {
  try {
    data.isLoadBtnDiagnosaDokter = true
    if (input.value?.details?.length > 0 && d_JenisDiagnosa.value?.length > 0) {
      input.value.details.forEach((element: any) => {
        element.diagnosaDokter.push({
          no: element.diagnosaDokter.length + 1,
          jenisDiagnosa: {
            label: d_JenisDiagnosa.value[1].label,
            value: d_JenisDiagnosa.value[1].value,
          },
        })
      })
    } else {
      console.error("Data 'details' atau 'd_JenisDiagnosa' tidak ditemukan.")
    }
  } catch (error) {
    console.error('Terjadi kesalahan saat menambahkan diagnosa dokter:', error)
  } finally {
    data.isLoadBtnDiagnosaDokter = false
  }
}

const removeDiagnosaDok = async (data: any, index: any) => {
  if (data.norecDiagnosa) {
    data.isLoadBtnDiagnosaDokter = true
    await useApi()
      .post(`/diagnosa/delete-diagnosa-x`, { norec: data.norecDiagnosa })
      .then((response: any) => {
        data.isLoadBtnDiagnosaDokter = false
        input.value.details.forEach((element: any) => {
          element.diagnosaDokter.splice(index, 1)
          if (element.diagnosaDokter.length === 0) {
            element.diagnosaDokter.push({
              no: element.diagnosaDokter.length + 1,
              jenisDiagnosa: {
                label: d_JenisDiagnosa.value[0].label,
                value: d_JenisDiagnosa.value[0].value,
              },
            })
          }
        })
      })
      .catch((e: any) => {
        data.isLoadBtnDiagnosaDokter = false
        console.error(e)
      })
    fetchDiagnosaX()
  } else {
    input.value.details.forEach((element: any) => {
      element.diagnosaDokter.splice(index, 1)
      if (element.diagnosaDokter.length === 0) {
        element.diagnosaDokter.push({
          no: element.diagnosaDokter.length + 1,
          jenisDiagnosa: {
            label: d_JenisDiagnosa.value[0].label,
            value: d_JenisDiagnosa.value[0].value,
          },
        })
      }
    })
  }
}

const addDiagnosaDok9 = async (data: any) => {
  try {
    data.isLoadBtnDiagnosaDokter9 = true
    if (input.value?.details && input.value.details.length > 0) {
      input.value.details.forEach((element: any) => {
        if (!Array.isArray(element.diagnosaDokter9)) {
          element.diagnosaDokter9 = []
        }
        element.diagnosaDokter9.push({
          no: element.diagnosaDokter9.length + 1,
        })
      })
    } else {
      console.error('Data tidak ditemukan.')
    }
  } catch (error) {
    console.error('Error while adding diagnosis:', error)
  } finally {
    data.isLoadBtnDiagnosaDokter9 = false
  }
}

const removeDiagnosaDok9 = async (data: any, index: any) => {
  if (data.norecDiagnosa9) {
    data.isLoadBtnDiagnosaDokter9 = true
    await useApi()
      .post(`/diagnosa/delete-diagnosa-ix`, { norec: data.norecDiagnosa9 })
      .then((response: any) => {
        data.isLoadBtnDiagnosaDokter9 = false
        input.value.details.forEach((element: any) => {
          element.diagnosaDokter9.splice(index, 1)
          if (element.diagnosaDokter9.length === 0) {
            element.diagnosaDokter9.push({
              no: element.diagnosaDokter9.length + 1,
            })
          }
        })
      })
      .catch((e: any) => {})
    fetchDiagnosaIX()
  } else {
    input.value.details.forEach((element: any) => {
      element.diagnosaDokter9.splice(index, 1)
      if (element.diagnosaDokter9.length === 0) {
        element.diagnosaDokter9.push({
          no: element.diagnosaDokter9.length + 1,
        })
      }
    })
  }
}
const fetchDiagnosaIX = async () => {
  await useApi()
    .get(
      '/diagnosa/riwayat-diagnosa-ix-cppt?noregistrasi=' + props.registrasi.noregistrasi
    )
    .then((response) => {
      listDiagnosa9.value = response.data
      for (let x = 0; x < input.value.details.length; x++) {
        const element = input.value.details[x]
        if (response.data.length > 0) {
          element.diagnosaDokter9 = []
          for (let z = 0; z < response.data.length; z++) {
            const element2 = response.data[z]
            element.diagnosaDokter9.push({
              no: z + 1,
              keterangan: element2.keterangantindakan,
              norecDiagnosa9: element2.norec,
              diagnosaa:
                element2.namadiagnosatindakan && element2.id
                  ? {
                      label:
                        element2.kddiagnosatindakan + '-' + element2.namadiagnosatindakan,
                      value: element2.id,
                    }
                  : '',
            })
          }
        }
      }
    })
}

const loadRiwayatOld = async () => {
  isloadingLAMPAU.value = true
  let paramsPD = ``
  useApi()
    .get(`/emr/get-emr-cppt?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
    .then(async (response: any) => {
      isloadingLAMPAU.value = false
      if (response.length) {
        let dataOLD = []
        for (let x = 0; x < response.length; x++) {
          const element = response[x]
          element.show = false
          if (element.registrasi.norec_pd != props.registrasi.norec_pd) {
            for (let y = 0; y < element.details.length; y++) {
              const element2 = element.details[y]
              element2.tgl = H.formatDate(new Date(element2.tgl), 'YYYY-MM-DD HH:mm')
              element2.tglVerifikasi = H.formatDate(
                new Date(element2.tglVerifikasi),
                'YYYY-MM-DD HH:mm'
              )
            }
            element.riwayatResep = []
            dataOLD.push(element)
          }
        }
        input2.value.sort(
          (a, b) =>
            new Date(b.registrasi.tglregistrasi) - new Date(a.registrasi.tglregistrasi)
        )
        input2.value = dataOLD
        console.log(dataOLD)
      }
      let lokal = true
      if (window.location.host.indexOf('192.168') > -1) {
        lokal = true
      }

      isloadingLAMPAU.value = true
      isloadingLAMPAU.value = false
      let no = input2.value.length + 1
      input2.value.sort((objA, objB) => {
        const dateA = new Date(objA.registrasi.tglregistrasi)
        const dateB = new Date(objB.registrasi.tglregistrasi)
        return dateB - dateA
      })
    })
}
const simpan = (e: any) => {
  isDialogSave.value = true
}

const simpanPreview = async () => {
  let ID = input.value.id ? input.value.id : ''
  let jenisDiag = true
  let object: any = {}
  if (input.value.details) {
    for (let x = 0; x < input.value.details.length; x++) {
      const element = input.value.details[x]
      if (element.tgl2 != undefined) delete element.tgl2
      if (element.SOAP2 != undefined) delete element.SOAP2
      if (element.intruksiPPA2 != undefined) delete element.intruksiPPA2
      if (element.keteranganVerifikasiDPJP2 != undefined)
        delete element.keteranganVerifikasiDPJP2
      if (element.tglVerifikasi2 != undefined) delete element.tglVerifikasi2
      if (element.dokterDPJP2 != undefined) delete element.dokterDPJP2
      if (element.tenagaMedis2 != undefined) delete element.tenagaMedis2
      if (
        element.flag == 'dokter' &&
        useUserSession().getUser().kelompokUser.kelompokUser == 'dokter'
      ) {
        if (element.diagnosaDokter.length) {
          for (let y = 0; y < element.diagnosaDokter.length; y++) {
            const element2 = element.diagnosaDokter[y]
            let isDiagnosaSaved = false
            for (let z = 0; z < listDiagnosa.value.length; z++) {
              const elementDiag = listDiagnosa.value[z]
              if (
                elementDiag.norec === element2.norecDiagnosa &&
                (elementDiag.jenisdiagnosa != element2.jenisDiagnosa.label ||
                  elementDiag.objectdiagnosafk != element2.diagnosaa.value ||
                  element2.keterangan != elementDiag.keterangan)
              ) {
                await saveDiagnosaDok(element2)
                isDiagnosaSaved = true
                break
              }
            }
            if (!isDiagnosaSaved) {
              if (
                element2.norecDiagnosa === undefined ||
                element2.norecDiagnosa === '' ||
                element2.norecDiagnosa === null
              ) {
                if (
                  (element2.hasOwnProperty('keterangan') && element2.keterangan !== '') ||
                  (element2.hasOwnProperty('diagnosaa') &&
                    element2.diagnosaa.hasOwnProperty('value'))
                ) {
                  await saveDiagnosaDok(element2)
                }
              }
            }
          }
        }
        if (element.diagnosaDokter9.length) {
          for (let y = 0; y < element.diagnosaDokter9.length; y++) {
            const element2 = element.diagnosaDokter9[y]
            let isDiagnosa9Saved = false
            for (let z = 0; z < listDiagnosa9.value.length; z++) {
              const elementDiag9 = listDiagnosa9.value[z]
              if (
                elementDiag9.norec === element2.norecDiagnosa9 &&
                (elementDiag9.objectdiagnosatindakanfk != element2.diagnosaa.value ||
                  element2.keterangan != elementDiag9.keterangantindakan)
              ) {
                await saveDiagnosaDok9(element2)
                isDiagnosa9Saved = true
                break
              }
            }
            if (!isDiagnosa9Saved) {
              if (
                element2.norecDiagnosa9 == undefined ||
                element2.norecDiagnosa9 === '' ||
                element2.norecDiagnosa9 === null
              ) {
                if (
                  (element2.hasOwnProperty('keterangan') && element2.keterangan !== '') ||
                  element2.hasOwnProperty('diagnosaa')
                ) {
                  await saveDiagnosaDok9(element2)
                }
              }
            }
          }
        }
      }
    }
  }

  object = input.value
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
  try {
    const response = await useApi().post(`/emr/simpan-emr-cppt`, json)
    isLoading.value = false
    isDialogSave.value = false

    NOREC_EMRPASIEN.value = response.norec_emr
    input.value.id = response.id
    dashboard()
  } catch (e) {
    isLoading.value = false
    console.error('Error while saving EMR:', e)
  }
}

const dashboard = () => {
  if (props.registrasi.objectdepartemenfk == 18) {
    router.push({
      name: 'module-dashboard-rawat-jalan',
      query: {
        statuspanggil: 'Belum Dipanggil',
      },
    })
  } else if (props.registrasi.objectdepartemenfk == 16) {
    router.push({
      name: 'module-dashboard-rawat-inap',
    })
  } else if (props.registrasi.objectdepartemenfk == 9) {
    router.push({
      name: 'module-dashboard-igd',
    })
  }
}

const goToUp = () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth',
  })
}

const saveDiagnosaDok = async (data: any) => {
  let objSave = {
    diagnosapasien: {
      norec: data.norecDiagnosa ? data.norecDiagnosa : '',
      noregistrasifk: props.registrasi.norec_apd,
      ketdiagnosis: data.keterangan ? data.keterangan : '',
      iskasusbaru: null,
      iskasuslama: null,
    },
    detaildiagnosapasien: {
      objectdiagnosafk: data.diagnosaa ? data.diagnosaa.value : null,
      tglinputdiagnosa: H.formatDate(new Date(), 'YYYY-MM-DD HH:mm:ss'),
      objectjenisdiagnosafk: data.jenisDiagnosa ? data.jenisDiagnosa.value : null,
      noregistrasi: props.registrasi.noregistrasi,
    },
    pasien: {
      nocm: props.pasien.nocm,
      namapasien: props.pasien.namapasien,
      noregistrasi: props.registrasi.noregistrasi,
    },
  }

  try {
    isLoading.value = true
    const response = await useApi().postNoMessage(`/diagnosa/save-diagnosa`, objSave)
    data.norecDiagnosa = response.norec
    // fetchDiagnosaX();
  } catch (error) {
    console.error('Error while saving diagnosis:', error)
    isLoading.value = false
  } finally {
    data.isLoadBtnDiagnosaDokter = false
    isLoading.value = false
  }
}

const saveDiagnosaDok9 = async (data: any) => {
  let query = ''
  if (data) {
    let diagnosa = data.diagnosaa ? data.diagnosaa.label : ''
    let parts = diagnosa.split('-')
    query = parts[1] ? parts[1].toLowerCase() : parts
  }
  let diagnosaBaru = { value: null }
  try {
    const response = await useApi().get(
      `/diagnosa/diagnosa-ix-paging?name=${query}&limit=10`
    )
    if (response.diagnosatindakan && response.diagnosatindakan.length > 0) {
      const firstElement = response.diagnosatindakan[0]
      diagnosaBaru = { value: firstElement.id }
    } else {
      return
    }
  } catch (error) {
    console.error('Error fetching data:', error)
    return
  }
  let objSave = {
    diagnosapasien: {
      norec: data.norecDiagnosa9 ? data.norecDiagnosa9 : '',
      noregistrasifk: props.registrasi.norec_apd,
      tglregistrasi: props.registrasi.tglregistrasi,
      keterangantindakan: data.keterangan ? data.keterangan : null,
    },
    detaildiagnosapasien: {
      objectdiagnosatindakanfk: diagnosaBaru.value ? diagnosaBaru.value : null,
      tglinputdiagnosa: H.formatDate(new Date(), 'YYYY-MM-DD HH:mm:ss'),
      noregistrasi: props.registrasi.noregistrasi,
    },
  }
  try {
    const responseSave = await useApi().postNoMessage(
      `/diagnosa/save-diagnosa-ix`,
      objSave
    )
    data.norecDiagnosa9 = responseSave.norec
  } catch (error) {
    console.error('Error while saving diagnosis:', error)
    isLoading.value = false
  } finally {
    data.isLoadBtnDiagnosaDokter9 = false
    isLoading.value = false
  }
}

const simpanReal = async () => {
  isFloating.value = false
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}
  if (input.value.details) {
    for (let x = 0; x < input.value.details.length; x++) {
      const element = input.value.details[x]
      if (element.tgl2 != undefined) delete element.tgl2
      if (element.SOAP2 != undefined) delete element.SOAP2
      if (element.intruksiPPA2 != undefined) delete element.intruksiPPA2
      if (element.keteranganVerifikasiDPJP2 != undefined)
        delete element.keteranganVerifikasiDPJP2
      if (element.tglVerifikasi2 != undefined) delete element.tglVerifikasi2
      if (element.dokterDPJP2 != undefined) delete element.dokterDPJP2
      if (element.tenagaMedis2 != undefined) delete element.tenagaMedis2
      if (
        element.flag == 'dokter' &&
        useUserSession().getUser().kelompokUser.kelompokUser == 'dokter'
      ) {
        if (element.diagnosaDokter.length) {
          for (let y = 0; y < element.diagnosaDokter.length; y++) {
            const element2 = element.diagnosaDokter[y]
            let isDiagnosaSaved = false
            for (let z = 0; z < listDiagnosa.value.length; z++) {
              const elementDiag = listDiagnosa.value[z]
              if (
                elementDiag.norec === element2.norecDiagnosa &&
                (elementDiag.jenisdiagnosa != element2.jenisDiagnosa.label ||
                  elementDiag.objectdiagnosafk != element2.diagnosaa.value ||
                  element2.keterangan != elementDiag.keterangan)
              ) {
                await saveDiagnosaDok(element2)
                isDiagnosaSaved = true
                break
              }
            }
            if (!isDiagnosaSaved) {
              if (
                element2.norecDiagnosa === undefined ||
                element2.norecDiagnosa === '' ||
                element2.norecDiagnosa === null
              ) {
                if (
                  (element2.hasOwnProperty('keterangan') && element2.keterangan !== '') ||
                  (element2.hasOwnProperty('diagnosaa') &&
                    element2.diagnosaa.hasOwnProperty('value'))
                ) {
                  await saveDiagnosaDok(element2)
                }
              }
            }
          }
        }
        if (element.diagnosaDokter9.length) {
          for (let y = 0; y < element.diagnosaDokter9.length; y++) {
            const element2 = element.diagnosaDokter9[y]
            let isDiagnosa9Saved = false
            for (let z = 0; z < listDiagnosa9.value.length; z++) {
              const elementDiag9 = listDiagnosa9.value[z]
              if (
                elementDiag9.norec === element2.norecDiagnosa9 &&
                (elementDiag9.objectdiagnosatindakanfk != element2.diagnosaa.value ||
                  element2.keterangan != elementDiag9.keterangantindakan)
              ) {
                await saveDiagnosaDok9(element2)
                isDiagnosa9Saved = true
                break
              }
            }
            if (!isDiagnosa9Saved) {
              if (
                element2.norecDiagnosa9 == undefined ||
                element2.norecDiagnosa9 === '' ||
                element2.norecDiagnosa9 === null
              ) {
                if (
                  (element2.hasOwnProperty('keterangan') && element2.keterangan !== '') ||
                  element2.hasOwnProperty('diagnosaa')
                ) {
                  await saveDiagnosaDok9(element2)
                }
              }
            }
          }
        }
      }
    }
  }

  object = input.value
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
    .post(`/emr/simpan-emr-cppt`, json)
    .then((response: any) => {
      isLoading.value = false
      isDialogSave.value = false
      loadRiwayat()
      isFloating.value = true

      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}

const kembaliKeun = () => {
  window.history.back()
}
const fetchDiagnosaKeperawatan = async (filter: any) => {
  await useApi()
    .get(`/emr/list-diagnosa-keperawatan?query=${filter.query}`)
    .then((response) => {
      d_DiagnosaKeperawatan.value = response.diagnosaKeperawatan.map((e: any) => {
        return { value: e.id, label: e.diagnosakep, default: e }
      })
    })
}
const fetchTujuan = async (filter: any) => {
  const idDiagnosa = input.value.details[1].diagnosaKep[0].diagnosaKeperawatan.value ?? ''
  await useApi()
    .get(
      `/emr/list-tujuan-keperawatan?query=${filter.query}&objectdiagnosakepfk=${idDiagnosa}`
    )
    .then((response: any) => {
      d_TujuanKeperawatan.value = response.tujuanPerawat.map((e: any) => {
        return { value: e.id, label: e.tujuankep, default: e }
      })
    })
}

const fetchIntervensi = async (keperawatanfk: any) => {
  let keperawatan = keperawatanfk ? keperawatanfk.value : ''
  await useApi()
    .get(`/emr/list-intervensi?keperawatanfk=${tujuanKeper.value}`)
    .then((response: any) => {
      d_IntervensiKeperawatan.value = response.intervensi.map((e: any) => {
        return { value: e.id, label: e.name }
      })
    })
}

const fetchDokter = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  )
  d_Dokter.value = response
}

const fetchPegawai = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  )
  d_Pegawai.value = response
}

const addNewItem = (e: any) => {
  let push: any = {}

  if (e.flag == 'dokter') {
    let diagnosaDokter = []
    let diagnosaDokter9 = []
    input.value.details.forEach((data, index) => {
      if (!data.norecDiagnosa && data.flag == 'dokter')
        diagnosaDokter = data.diagnosaDokter
      if (!data.norecDiagnosa9 && data.flag == 'dokter')
        diagnosaDokter9 = data.diagnosaDokter9
    })
    push = {
      uuid: uuidv4(),
      no: input.value.details[input.value.details.length - 1].no + 1,
      tgl: new Date(),
      tglVerifikasi: new Date(),
      flag: 'dokter',
      diagnosaDokter: [{ no: 1 }],
      diagnosaDokter9: [{ no: 1 }],
      tenagaMedis: {
        label: useUserSession().getUser().pegawai.namaLengkap,
        value: useUserSession().getUser().pegawai.id,
      },
    }
  }
  if (e.flag == 'perawat') {
    push = {
      uuid: uuidv4(),
      no: input.value.details[input.value.details.length - 1].no + 1,
      tgl: new Date(),
      tglVerifikasi: new Date(),
      flag: 'perawat',
      diagnosaKep: [
        {
          no: 1,
        },
      ],
      tujuanKep: [
        {
          no: 1,
        },
      ],
    }
  }
  if (e.flag == 'profesi lain') {
    push = {
      uuid: uuidv4(),
      no: input.value.details[input.value.details.length - 1].no + 1,
      tgl: new Date(),
      tglVerifikasi: new Date(),
      flag: 'profesi lain',
    }
  }
  if (e.flag == 'gizi') {
    push = {
      uuid: uuidv4(),
      no: input.value.details[input.value.details.length - 1].no + 1,
      tgl: new Date(),
      tglVerifikasi: new Date(),
      flag: 'gizi',
    }
  }
  if (e.flag == 'sbar') {
    push = {
      uuid: uuidv4(),
      no: input.value.details[input.value.details.length - 1].no + 1,
      tgl: new Date(),
      tglVerifikasi: new Date(),
      flag: 'sbar',
    }
  }
  input.value.details.push(push)
}
const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}
const setAutoFill = async () => {
  input.value.dpjpUtama = props.registrasi.dokter
  input.value.details.forEach((element: any) => {
    if (props.registrasi.objectpegawaifk)
      element.dokterDPJP = {
        label: props.registrasi.dokter,
        value: props.registrasi.objectpegawaifk,
      }
    if (useUserSession().getUser().kelompokUser.kelompokUser != 'dokter') {
      if (element.flag != 'dokter') {
        element.tenagaMedis = {
          label: useUserSession().getUser().pegawai.namaLengkap,
          value: useUserSession().getUser().pegawai.id,
        }
      } else {
        element.tenagaMedis = {
          label: props.registrasi.dokter,
          value: props.registrasi.objectpegawaifk,
        }
      }
    }
    if (useUserSession().getUser().kelompokUser.kelompokUser == 'dokter') {
      element.tenagaMedis = {
        label: useUserSession().getUser().pegawai.namaLengkap,
        value: useUserSession().getUser().pegawai.id,
      }
    }
  })
  await useApi()
    .get(
      'emr/auto-fill?norec_pd=' +
        props.registrasi.norec_pd +
        '&collection=VitalSign' +
        '&field=tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi,beratBadan,SPO2'
    )
    .then((response) => {
      if (response == null) return
      let data = ''
      console.log(input.value)
      data += response.suhu ? `     Suhu : ${response.suhu} °C\n` : ''
      data += response.nadi ? `     Nadi : ${response.nadi} x/mnt\n` : ''
      data += response.pernapasan
        ? `     Pernafasan : ${response.pernapasan} x/mnt\n`
        : ''
      data += response.tekananDarah
        ? `     Tekanan Darah : ${response.tekananDarah} mmHg\n`
        : ''
      data += response.tinggiBadan
        ? `     Tinggi Badan : ${response.tinggiBadan} Cm\n`
        : ''
      data += response.beratBadan ? `     Berat Badan : ${response.beratBadan} Kg\n` : ''
      data += response.SPO2 ? `     SPO2 : ${response.SPO2} %\n` : ''
      data += response.IMT ? `     IMT : ${response.IMT}\n` : ''

      input.value.details[2].O = data != '' ? `${data}` : ''
      input.value.details[1].O = data != '' ? `${data}` : ''
      input.value.details[0].O = data != '' ? `${data}` : ''
      input.value.details[4].IGizi = data != '' ? `${data}` : ''
    })
  try {
    const storedResep = localStorage.getItem('resep')
    console.log('resep:', storedResep) // Debugging clipboard
    if (storedResep) {
      console.log('Resep dari localStorage:', storedResep)

      // Masukkan resep ke dalam field yang sesuai
      // input.value.details[0].intruksiPPA = storedResep // Field O
      input.value.details[4].IGizi = storedResep // Field IGizi atau lainnya
    } else {
      console.warn('Resep tidak ditemukan di localStorage.')
    }

    // Ambil data dari clipboard (fallback)
    const clipboardText = await navigator.clipboard.readText()
    if (clipboardText) {
      console.log('Clipboard Text:', clipboardText)

      // Masukkan data clipboard ke field jika belum ada data di localStorage
      if (!storedResep) {
        // input.value.details[0].intruksiPPA = clipboardText
        input.value.details[4].IGizi = clipboardText
      }
    }
  } catch (error) {
    console.error('Gagal membaca data untuk autofill:', error)
  }
  let idDiagKep = 0
  let idTujuanKep = 0
  let idIntervensiKep = 0
  let idPlanKep = 0
  await useApi()
    .get(
      'emr/auto-fill?norec_pd=' +
        props.registrasi.norec_pd +
        '&collection=pengajianAwalRawatJalan' +
        '&field=alasanKunjunagn,riwayatPenyakitDahulu,riwayatObat,riwayatAlergi,diagnosaKeperawatn,tujuanKeperawatan,intervensiKeperawatan,implementasiKeperawatan,diagnosaKeperawatn'
    )
    .then((response) => {
      if (response != null) {
        let data = ''
        data += response.alasanKunjunagn
          ? `     Alasan Kunjungan : ${response.alasanKunjunagn}\n`
          : ''
        data += response.riwayatPenyakitDahulu
          ? `     Riwayat Penyakit : ${response.riwayatPenyakitDahulu}\n`
          : ''
        data += response.riwayatObat
          ? `     Riwayat Obat : ${response.riwayatObat}\n`
          : ''
        data += response.riwayatAlergi
          ? `     Riwayat Alergi : ${response.riwayatAlergi}\n`
          : ''
        input.value.details[1].S = data != '' ? `${data}` : ''
        idDiagKep = response.diagnosaKeperawatn
        idTujuanKep = response.tujuanKeperawatan
        idIntervensiKep = response.intervensiKeperawatan
        idPlanKep = response.implementasiKeperawatan
      }
    })
  await useApi()
    .get(
      'emr/auto-fill?norec_pd=' +
        props.registrasi.norec_pd +
        '&collection=PengkajianAwalGiziRawatInap' +
        '&field=diagnosisGizi'
    )
    .then((response) => {
      if (response != null) {
        input.value.details[4].DGizi = response.diagnosisGizi
          ? response.diagnosisGizi.value
          : ''
      }
    })
  if (idDiagKep != 0) {
    await useApi()
      .get('/emr/list-diagnosa-keperawatan')
      .then((response) => {
        let index = 0
        input.value.details[1].diagnosaKep = []
        response.diagnosaKeperawatan.forEach((element: any) => {
          if (element.kodediagnosakep == idDiagKep) {
            input.value.details[1].diagnosaKep.push({
              no: index + 1,
              diagnosaKeperawatan: { value: element.id, label: element.diagnosakep },
            })
          }
        })
      })
  }

  if (idTujuanKep != 0) {
    await useApi()
      .get('/emr/list-tujuan-keperawatan')
      .then((response) => {
        input.value.details[1].tujuanKep = []
        let index = 0
        response.tujuanPerawat.forEach((element) => {
          if (element.id == idTujuanKep) {
            let intervensiKep
            useApi()
              .get(`/emr/list-intervensi`)
              .then((response) => {
                response.intervensi.forEach((element2) => {
                  if (element2.id == idIntervensiKep) {
                    input.value.details[1].tujuanKep.push({
                      no: index + 1,
                      tujuanKeperawatan: { value: element.id, label: element.tujuankep },
                      intervensiKeperawatan: { value: element.id, label: element2.name },
                    })
                  }
                })
              })
          }
        })
      })
  }
  if (idPlanKep != 0) {
    await useApi()
      .get(`/emr/list-implementasi`)
      .then((response: any) => {
        response.implementasi.forEach((element) => {
          if (element.id == idPlanKep) {
            input.value.details[1].P = element.name
          }
        })
      })
  }
}
const fetchDiagnosaX = async () => {
  await useApi()
    .get('diagnosa/riwayat-diagnosa-x-cppt?noregistrasi=' + props.registrasi.noregistrasi)
    .then((response) => {
      listDiagnosa.value = response.data
      for (let x = 0; x < input.value.details.length; x++) {
        const element = input.value.details[x]
        element.diagnosaDokter = []
        if (
          !response.data ||
          response.data.length === 0 ||
          response.data === null ||
          response.data === '' ||
          (Array.isArray(response.data) && response.data.length === 0)
        ) {
          element.diagnosaDokter.push({
            no: 1,
            jenisDiagnosa: {
              label: d_JenisDiagnosa.value[0].label,
              value: d_JenisDiagnosa.value[0].value,
            },
          })
        } else {
          for (let z = 0; z < response.data.length; z++) {
            const element2 = response.data[z]
            element.diagnosaDokter.push({
              no: z + 1,
              keterangan: element2.keterangan,
              norecDiagnosa: element2.norec,
              jenisDiagnosa:
                element2.jenisdiagnosa && element2.objectjenisdiagnosafk
                  ? {
                      label: element2.jenisdiagnosa,
                      value: element2.objectjenisdiagnosafk,
                    }
                  : z == 0
                  ? {
                      label: d_JenisDiagnosa.value[0].label,
                      value: d_JenisDiagnosa.value[0].value,
                    }
                  : {
                      label: d_JenisDiagnosa.value[1].label,
                      value: d_JenisDiagnosa.value[1].value,
                    },
              diagnosaa:
                element2.namadiagnosa && element2.id
                  ? {
                      label: element2.kddiagnosa + '-' + element2.namadiagnosa,
                      value: element2.id,
                    }
                  : '',
              isLoadBtnDiagnosaDokter: false,
            })
          }
        }
      }
    })
}
const setValueDisabled = () => {
  if (input.value.details) {
    for (let x = 0; x < input.value.details.length; x++) {
      const element = input.value.details[x]
      if (element.tenagaMedis) {
        if (element.tenagaMedis.value != pegawaiId) {
          element.tgl2 = true
          element.SOAP2 = true
          element.intruksiPPA2 = true
          element.tenagaMedis2 = true
        }
      }
      if (element.dokterDPJP) {
        if (element.dokterDPJP.value != pegawaiId) {
          element.keteranganVerifikasiDPJP2 = true
          element.dokterDPJP2 = element.dokterDPJP ? true : false
          element.tglVerifikasi2 = element.tglVerifikasi ? true : false
        }
      }
    }
  }
}

const pasteFromClipboard = (e: any) => {
  input.value.details.forEach((element) => {
    if (element.flag == e.flag) {
      navigator.clipboard
        .readText()
        .then(function (clipboardText) {
          e.S = clipboardText
        })
        .catch(function (err) {
          H.alert('warning', 'Klik Allow untuk izin paste')
        })
    }
  })
}
const copyToClipboardS = (e: any) => {
  dataSourcefiltered.value[0].S = e
  isCPPTOLD.value = false
}

const copyToClipboardO = (e: any) => {
  dataSourcefiltered.value[0].O = e
  isCPPTOLD.value = false
}

const copyToClipboardP = (e: any) => {
  dataSourcefiltered.value[0].P = e
  isCPPTOLD.value = false
}

const copyToClipboard = (e: any) => {
  console.log(e)
  dataSourcefiltered.value[0].S = e.S
  dataSourcefiltered.value[0].O = e.O
  console.log(dataSourcefiltered.value[0].S)
  const textArea = document.createElement('textarea')
  textArea.value = e
  textArea.style.position = 'fixed'
  textArea.style.top = '0'
  textArea.style.left = '0'
  textArea.style.opacity = 0

  document.body.appendChild(textArea)
  textArea.select()

  try {
    const successful = document.execCommand('copy')
    if (successful) {
      H.alert('info', 'Text copied to clipboard')
    } else {
      H.alert('error', 'Failed to copy text')
    }
  } catch (err) {
    console.error('Unable to copy text: ', err)
  }

  document.body.removeChild(textArea)
}

const scrollToBottom = () => {
  if (scrollContainer.value) {
    scrollContainer.value.scrollTop = scrollContainer.value.scrollHeight
  }
}
const filterList = (e: any) => {}
const dataSourcefiltered = computed(() => {
  if (!item.filter) {
    return input.value.details
  }

  return input.value.details.filter((items) => {
    return items.flag.match(new RegExp(item.filter, 'i'))
  })
})

const copyS = (e) => {
  dataSourcefiltered.value[0].S = e
  isCPPTOLD.value = false
}

const copyO = (e) => {
  dataSourcefiltered.value[0].O = e
  isCPPTOLD.value = false
}

const copyP = (e) => {
  dataSourcefiltered.value[0].P = e
  isCPPTOLD.value = false
}

const copyDiagnosa = (e) => {
  const copiedDiagnosa = e
  copiedDiagnosa.forEach((element, index) => {
    const newDiagnosa = {
      ...element,
      norecDiagnosa: null,
      isCopy: true,
    }
    const emptyColumnIndex = input.value.details[0]?.diagnosaDokter.findIndex(
      (diagnosa) => !diagnosa.keterangan && !diagnosa.diagnosaa
    )
    if (emptyColumnIndex !== -1) {
      if (input.value.details[0]?.diagnosaDokter instanceof Array) {
        input.value.details[0].diagnosaDokter[emptyColumnIndex] = newDiagnosa
      }
    } else {
      if (input.value.details[0]?.diagnosaDokter instanceof Array) {
        input.value.details[0].diagnosaDokter.push(newDiagnosa)
      }
    }
  })
  isCPPTOLD.value = false
}

const copyDiagnosa9 = async (e: any) => {
  const copiedDiagnosa9 = e

  for (const element of copiedDiagnosa9) {
    let query = ''
    if (element.diagnosaa) {
      const diagnosa = element.diagnosaa.label
      const parts = diagnosa.split('-')
      query = parts[1] ? parts[1].toLowerCase() : parts
    }

    try {
      const response = await useApi().get(
        `/diagnosa/diagnosa-ix-paging?name=${query}&limit=10`
      )
      if (response.diagnosatindakan && response.diagnosatindakan.length > 0) {
        const firstElement = response.diagnosatindakan[0]
        const label = `${firstElement.kddiagnosatindakan} - ${firstElement.namadiagnosatindakan}`

        let newDiagnosa = {
          keterangan: element.keterangan,
          diagnosaa: { label: label, value: firstElement.id },
          norecDiagnosa9: null,
        }

        const emptyColumnIndex = input.value.details[0]?.diagnosaDokter9.findIndex(
          (diagnosa: any) => !diagnosa.keterangan && !diagnosa.diagnosaa
        )

        if (emptyColumnIndex !== -1) {
          if (input.value.details[0]?.diagnosaDokter9 instanceof Array) {
            input.value.details[0].diagnosaDokter9[emptyColumnIndex] = newDiagnosa
          }
        } else {
          if (input.value.details[0]?.diagnosaDokter9 instanceof Array) {
            input.value.details[0].diagnosaDokter9.push(newDiagnosa)
          }
        }
      }
    } catch (error) {
      console.error('Error fetching data:', error)
    }
  }
  isCPPTOLD.value = false
}

const copyDiagnosaAssesment = async (e: any) => {
  // ICD 10
  const copiedDiagnosa = e.diagnosaDokter
  copiedDiagnosa.forEach((element, index) => {
    const newDiagnosa = {
      ...element,
      norecDiagnosa: null,
    }
    const emptyColumnIndex = input.value.details[0]?.diagnosaDokter.findIndex(
      (diagnosa) => !diagnosa.keterangan && !diagnosa.diagnosaa
    )
    if (emptyColumnIndex !== -1) {
      if (input.value.details[0]?.diagnosaDokter instanceof Array) {
        input.value.details[0].diagnosaDokter[emptyColumnIndex] = newDiagnosa
      }
    } else {
      if (input.value.details[0]?.diagnosaDokter instanceof Array) {
        input.value.details[0].diagnosaDokter.push(newDiagnosa)
      }
    }
  })
  // ICD 9
  const copiedDiagnosa9 = e.diagnosaDokter9
  console.log(copiedDiagnosa9)
  for (const element of copiedDiagnosa9) {
    let query = ''
    if (element.diagnosaa) {
      const diagnosa = element.diagnosaa.label
      const parts = diagnosa.split('-')
      query = parts[1] ? parts[1].toLowerCase() : parts
    }
    try {
      const response = await useApi().get(
        `/diagnosa/diagnosa-ix-paging?name=${query}&limit=10`
      )
      if (response.diagnosatindakan && response.diagnosatindakan.length > 0) {
        const firstElement = response.diagnosatindakan[0]
        const label = `${firstElement.kddiagnosatindakan} - ${firstElement.namadiagnosatindakan}`

        let newDiagnosa = {
          keterangan: element.keterangan,
          diagnosaa: { label: label, value: firstElement.id },
          norecDiagnosa9: null,
        }

        const emptyColumnIndex = input.value.details[0]?.diagnosaDokter9.findIndex(
          (diagnosa: any) => !diagnosa.keterangan && !diagnosa.diagnosaa
        )

        if (emptyColumnIndex !== -1) {
          if (input.value.details[0]?.diagnosaDokter9 instanceof Array) {
            input.value.details[0].diagnosaDokter9[emptyColumnIndex] = newDiagnosa
          }
        } else {
          if (input.value.details[0]?.diagnosaDokter9 instanceof Array) {
            input.value.details[0].diagnosaDokter9.push(newDiagnosa)
          }
        }
      }
    } catch (error) {
      console.error('Error fetching data:', error)
    }
  }
  isCPPTOLD.value = false
}

const copy = async (e: any) => {
  dataSourcefiltered.value[0].O = e.O
  dataSourcefiltered.value[0].S = e.S
  dataSourcefiltered.value[0].P = e.P
  // ICD 10
  const copiedDiagnosa = e.diagnosaDokter
  copiedDiagnosa.forEach((element, index) => {
    const newDiagnosa = {
      ...element,
      norecDiagnosa: null,
    }
    const emptyColumnIndex = input.value.details[0]?.diagnosaDokter.findIndex(
      (diagnosa) => !diagnosa.keterangan && !diagnosa.diagnosaa
    )
    if (emptyColumnIndex !== -1) {
      if (input.value.details[0]?.diagnosaDokter instanceof Array) {
        input.value.details[0].diagnosaDokter[emptyColumnIndex] = newDiagnosa
      }
    } else {
      if (input.value.details[0]?.diagnosaDokter instanceof Array) {
        input.value.details[0].diagnosaDokter.push(newDiagnosa)
      }
    }
  })
  // ICD 9
  const copiedDiagnosa9 = e.diagnosaDokter9
  for (const element of copiedDiagnosa9) {
    let query = ''
    if (element.diagnosaa) {
      const diagnosa = element.diagnosaa.label
      const parts = diagnosa.split('-')
      query = parts[1] ? parts[1].toLowerCase() : parts
    }

    try {
      const response = await useApi().get(
        `/diagnosa/diagnosa-ix-paging?name=${query}&limit=10`
      )
      if (response.diagnosatindakan && response.diagnosatindakan.length > 0) {
        const firstElement = response.diagnosatindakan[0]
        const label = `${firstElement.kddiagnosatindakan} - ${firstElement.namadiagnosatindakan}`

        let newDiagnosa = {
          keterangan: element.keterangan,
          diagnosaa: { label: label, value: firstElement.id },
          norecDiagnosa9: null,
        }

        const emptyColumnIndex = input.value.details[0]?.diagnosaDokter9.findIndex(
          (diagnosa: any) => !diagnosa.keterangan && !diagnosa.diagnosaa
        )

        if (emptyColumnIndex !== -1) {
          if (input.value.details[0]?.diagnosaDokter9 instanceof Array) {
            input.value.details[0].diagnosaDokter9[emptyColumnIndex] = newDiagnosa
          }
        } else {
          if (input.value.details[0]?.diagnosaDokter9 instanceof Array) {
            input.value.details[0].diagnosaDokter9.push(newDiagnosa)
          }
        }
      }
    } catch (error) {
      console.error('Error fetching data:', error)
    }
  }
  isCPPTOLD.value = false
}

const paste = (e: any, index: any) => {
  if (localStorage.getItem('cppt_copy') != null) {
    let data = JSON.parse(localStorage.getItem('cppt_copy'))
    data.tgl = new Date()
    input.value.details[index] = data
    H.alert('info', 'Berhasil diterapkan')
  } else {
    H.alert('error', 'Belum ada data yang dicopy')
  }
}
const hasilLab = async (norec_pd: any, e: any) => {
  e.isLoading = true
  useApi()
    .get(`/emr/hasil-lab?norec_pd=${norec_pd}&islab=true`)
    .then((response: any) => {
      e.isLoading = false
      item.lab = setHasilLab(response.laboratorium)
      item.lab_GROUP = updateGroupLAB()

      isHasilLab.value = true
    })
}
const hasilRad = async (norec_pd: any, e: any) => {
  e.isLoadingRad = true
  useApi()
    .get(`/emr/hasil-lab?norec_pd=${norec_pd}&israd=true`)
    .then((response: any) => {
      e.isLoadingRad = false
      item.radiologi = setHasilRad(response.radiologi)
      isHasilRad.value = true
    })
}
const berkasPasien = async (norec_pd: any, e: any) => {
  e.isLoadingBerkas = true
  useApi()
    .get(
      `/emr/berkas-pasien?nocm=${e.pasien.nocm}&noregistrasi=${e.registrasi.noregistrasi}`
    )
    .then((response: any) => {
      e.isLoadingBerkas = false
      item.berkas = response.data
      isBerkas.value = true
    })
}
const hasilLabPA = async (norec_pd: any, e: any) => {
  e.isLoadingPA = true
  useApi()
    .get(`/emr/hasil-lab-pa?norec_pd=${norec_pd}`)
    .then((response: any) => {
      e.isLoadingPA = false
      item.patologi = response
      isPA.value = true
    })
}
const setHasilRad = (e: any) => {
  for (let x = 0; x < e.length; x++) {
    const element = e[x]
    element.isdetail = false
    if (element.expertise != null) {
      element.isdetail = true
    }
  }
  return e
}
const setHasilLab = (e: any) => {
  for (let x = 0; x < e.length; x++) {
    const element = e[x]
    element.isdetail = false
    if (element.hasil_lab.length > 0) {
      element.isdetail = true
    }
  }
  return e
}
const updateGroupLAB = () => {
  rowGroupLAB.value = {}

  if (item.lab.length) {
    for (let x = 0; x < item.lab.length; x++) {
      const element = item.lab[x]
      for (let i = 0; i < element.hasil_lab.length; i++) {
        let rowData = element.hasil_lab[i]
        let treatment_name = rowData.treatment_name

        if (i == 0) {
          rowGroupLAB.value[treatment_name] = { index: 0, size: 1 }
        } else {
          let previousRowData = element.hasil_lab[i - 1]
          let previousRowGroup = previousRowData.treatment_name
          if (treatment_name === previousRowGroup)
            rowGroupLAB.value[treatment_name].size++
          else rowGroupLAB.value[treatment_name] = { index: i, size: 1 }
        }
      }
    }
  }
  return rowGroupLAB.value
}

const fetchDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/diagnosa/diagnosa-x-paging?name=${filter.query}&limit=10`
  )

  d_Diagnosa.value = response.diagnosa.map((item: any) => {
    return { value: item.id, label: item.kddiagnosa + ' - ' + item.namadiagnosa }
  })
}

const getIDTujuanKeper = (e: any) => {
  tujuanKeper.value = e.value
}

const fetchDiagnosa9 = async (filter: any) => {
  const response = await useApi().get(
    `/diagnosa/diagnosa-ix-paging?name=${filter.query}&limit=10`
  )

  d_Diagnosa9.value = response.diagnosatindakan.map((item: any) => {
    return {
      value: item.id,
      label: item.kddiagnosatindakan + ' - ' + item.namadiagnosatindakan,
      default: item,
    }
  })
}
const fetchDiagnosa10 = async (filter: any) => {
  let query = ''
  if (filter) {
    query = filter.query.toLowerCase()
  }

  const response = await useApi().get(
    `/diagnosa/diagnosa-x-paging?name=${query}&limit=10`
  )
  d_Diagnosa10.value = response.diagnosa
}
const fetchJenisDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/jenisdiagnosa_m?select=id,jenisdiagnosa&param_search=jenisdiagnosa&query=${
      filter.query ? filter.query : ''
    }&limit=10`
  )
  d_JenisDiagnosa.value = response
}
const lihatBerkas = async (e: any) => {
  H.openFile('berkaspasien/' + e.nocm + '/' + e.namafile)
}
const copyItemDiagnosa = (item: any, type: string) => {
  delete d_CopyDiagnosa.value
  let query = ''
  let jenisDiagnosa = item.jenisDiagnosa ? item.jenisDiagnosa.label : ''

  if (item) {
    let diagnosa = item.diagnosaa ? item.diagnosaa.label : ''
    let parts
    type == 'ICD10' ? (parts = diagnosa.split('-')) : (parts = diagnosa.split('-'))
    query = parts[1] ? parts[1].toLowerCase() : parts
  }
  if (type == 'ICD10') {
    try {
      useApi()
        .get(`/diagnosa/diagnosa-x-paging?name=${query}&limit=10`)
        .then((response) => {
          if (response.diagnosa && response.diagnosa.length > 0) {
            const firstElement = response.diagnosa[0]
            const label = `${firstElement.kddiagnosa} - ${firstElement.namadiagnosa}`

            let diagnosaObj = {
              jenisDiagnosa: { label: jenisDiagnosa, value: item.jenisDiagnosa.value },
              keterangan: item.keterangan,
              diagnosaa: { label: label, value: firstElement.id },
            }
            d_CopyDiagnosa.value = diagnosaObj
            localStorage.setItem('copiedDiagnosa', JSON.stringify(d_CopyDiagnosa.value))
            H.alert('info', 'Text copied to clipboard')
            const copiedDiagnosa = d_CopyDiagnosa.value
            const newDiagnosa = {
              ...copiedDiagnosa,
              norecDiagnosa: null,
              isCopy: true,
            }
            const emptyColumnIndex = input.value.details[0]?.diagnosaDokter.findIndex(
              (diagnosa) => !diagnosa.keterangan && !diagnosa.diagnosaa
            )
            if (emptyColumnIndex !== -1) {
              if (input.value.details[0]?.diagnosaDokter instanceof Array) {
                input.value.details[0].diagnosaDokter[emptyColumnIndex] = newDiagnosa
              }
            } else {
              if (input.value.details[0]?.diagnosaDokter instanceof Array) {
                input.value.details[0].diagnosaDokter.push(newDiagnosa)
              }
            }
          } else {
            H.alert('error', 'Failed to copy text')
          }
        })
        .catch((error) => {
          console.error('Error fetching data:', error)
        })
    } catch (error) {
      console.error('Error in try block:', error)
    }
  } else {
    try {
      useApi()
        .get(`/diagnosa/diagnosa-ix-paging?name=${query}&limit=10`)
        .then((response) => {
          if (response.diagnosatindakan && response.diagnosatindakan.length > 0) {
            const firstElement = response.diagnosatindakan[0]
            const label = `${firstElement.kddiagnosatindakan} - ${firstElement.namadiagnosatindakan}`

            let diagnosaObj = {
              keterangan: item.keterangan,
              diagnosaa: { label: label, value: firstElement.id },
            }
            d_CopyDiagnosa.value = diagnosaObj
            H.alert('info', 'Text copied to clipboard')
          } else {
            H.alert('error', 'Failed to copy text')
          }
        })
        .catch((error) => {
          console.error('Error fetching data:', error)
        })
    } catch (error) {
      console.error('Error in try block:', error)
    }
  }
}
const copyItemDiagnosa9 = (item: any, type: string) => {
  delete d_CopyDiagnosa.value
  let query = ''

  if (item) {
    let diagnosa = item.diagnosaa ? item.diagnosaa.label : ''
    let parts
    type == 'ICD9' ? (parts = diagnosa.split('-')) : (parts = diagnosa.split('-'))
    query = parts[1] ? parts[1].toLowerCase() : parts
  }
  if (type == 'ICD9') {
    try {
      useApi()
        .get(`/diagnosa/diagnosa-ix-paging?name=${query}&limit=10`)
        .then((response) => {
          if (response.diagnosatindakan && response.diagnosatindakan.length > 0) {
            const firstElement = response.diagnosatindakan[0]
            const label = `${firstElement.kddiagnosatindakan} - ${firstElement.namadiagnosatindakan}`

            let diagnosaObj = {
              keterangan: item.keterangan,
              diagnosaa: { label: label, value: firstElement.id },
            }
            d_CopyDiagnosa.value = diagnosaObj
            localStorage.setItem('copiedDiagnosa', JSON.stringify(d_CopyDiagnosa.value))
            H.alert('info', 'Text copied to clipboard')
            const copiedDiagnosa9 = d_CopyDiagnosa.value
            const newDiagnosa = {
              ...copiedDiagnosa9,
              norecDiagnosa9: null,
            }
            const emptyColumnIndex = input.value.details[0]?.diagnosaDokter9.findIndex(
              (diagnosa) => !diagnosa.keterangan && !diagnosa.diagnosaa
            )
            if (emptyColumnIndex !== -1) {
              if (input.value.details[0]?.diagnosaDokter9 instanceof Array) {
                input.value.details[0].diagnosaDokter9[emptyColumnIndex] = newDiagnosa
              }
            } else {
              if (input.value.details[0]?.diagnosaDokter9 instanceof Array) {
                input.value.details[0].diagnosaDokter9.push(newDiagnosa)
              }
            }
            isCPPTOLD.value = false
          } else {
            H.alert('error', 'Failed to copy text')
          }
        })
        .catch((error) => {
          console.error('Error fetching data:', error)
        })
    } catch (error) {
      console.error('Error in try block:', error)
    }
  }
}
const copyDOKTERA = (item: any) => {
  d_CopyDiagnosa.value = []
  item.diagnosaDokter.forEach((element) => {
    if (element.jenisDiagnosa != undefined) {
      let diagnosaObj = {
        jenisDiagnosa: {
          label: element.jenisDiagnosa ? element.jenisDiagnosa.label : null,
          value: element.jenisDiagnosa ? element.jenisDiagnosa.value : null,
        },
        keterangan: element ? element.keterangan : null,
        diagnosaa: {
          label: element.diagnosaa ? element.diagnosaa.label : null,
          value: element.diagnosaa ? element.diagnosaa.value : null,
        },
        type: 'ICD10',
      }
      d_CopyDiagnosa.value.push(diagnosaObj)
      localStorage.setItem('copiedDiagnosa', JSON.stringify(d_CopyDiagnosa.value))
    }
  })
  H.alert('info', 'Text copied to clipboard')
}
function generateNewId() {
  return uuidv4()
}

const pasteItemDiagnosa = async (index: number, flex: any, data: any) => {
  // console.log(data)
  const copiedDiagnosa = JSON.parse(localStorage.getItem('copiedDiagnosa'))
  if (flex == 'dokter') {
    const newDiagnosa = {
      ...copiedDiagnosa,
      no: input.value.details[index].diagnosaDokter.length + 1,
      // norecDiagnosa: generateNewId(),
      norecDiagnosa: null,
    }
    const emptyColumnIndex = input.value.details[0]?.diagnosaDokter.findIndex(
      (diagnosa) => !diagnosa.diagnosaa && !diagnosa.norecDiagnosa
    )
    if (emptyColumnIndex !== -1) {
      let objSave = {
        diagnosapasien: {
          norec: newDiagnosa.norecDiagnosa,
          noregistrasifk: props.registrasi.norec_apd,
          ketdiagnosis: newDiagnosa.keterangan ? newDiagnosa.keterangan : null,
          iskasusbaru: null,
          iskasuslama: null,
        },
        detaildiagnosapasien: {
          objectdiagnosafk: newDiagnosa.diagnosaa ? newDiagnosa.diagnosaa.value : null,
          tglinputdiagnosa: H.formatDate(new Date(), 'YYYY-MM-DD HH:mm:ss'),
          objectjenisdiagnosafk: newDiagnosa.jenisDiagnosa
            ? newDiagnosa.jenisDiagnosa.value
            : null,
          noregistrasi: props.registrasi.noregistrasi,
        },
        pasien: {
          nocm: props.pasien.nocm,
          namapasien: props.pasien.namapasien,
          noregistrasi: props.registrasi.noregistrasi,
        },
      }

      try {
        await useApi().post(`/diagnosa/save-diagnosa`, objSave)
        if (input.value.details[0]?.diagnosaDokter instanceof Array) {
          input.value.details[0].diagnosaDokter[emptyColumnIndex] = newDiagnosa
        }
      } catch (error) {
        console.error('Error while saving diagnosis:', error)
      }
    } else {
      // Tidak ada kolom kosong, menambahkan kolom baru
      let objSave = {
        diagnosapasien: {
          norec: newDiagnosa.norecDiagnosa,
          noregistrasifk: props.registrasi.norec_apd,
          ketdiagnosis: newDiagnosa.keterangan ? newDiagnosa.keterangan : null,
          iskasusbaru: null,
          iskasuslama: null,
        },
        detaildiagnosapasien: {
          objectdiagnosafk: newDiagnosa.diagnosaa ? newDiagnosa.diagnosaa.value : null,
          tglinputdiagnosa: H.formatDate(new Date(), 'YYYY-MM-DD HH:mm:ss'),
          objectjenisdiagnosafk: newDiagnosa.jenisDiagnosa
            ? newDiagnosa.jenisDiagnosa.value
            : null,
          noregistrasi: props.registrasi.noregistrasi,
        },
        pasien: {
          nocm: props.pasien.nocm,
          namapasien: props.pasien.namapasien,
          noregistrasi: props.registrasi.noregistrasi,
        },
      }

      try {
        await useApi().post(`/diagnosa/save-diagnosa`, objSave)
        if (input.value.details[0]?.diagnosaDokter instanceof Array) {
          input.value.details[0].diagnosaDokter.push(newDiagnosa)
        }
      } catch (error) {
        console.error('Error while saving diagnosis:', error)
      }
    }
  } else if (flex == 'all') {
    const copiedDiagnosa = JSON.parse(localStorage.getItem('copiedDiagnosa'))
    copiedDiagnosa.forEach((element) => {
      if (element.type == 'ICD10') {
        const newDiagnosa = {
          ...element,
          no: input.value.details[index].diagnosaDokter.length + 1,
          norecDiagnosa: null,
        }
        const emptyColumnIndex = input.value.details[0]?.diagnosaDokter.findIndex(
          (diagnosa) => !diagnosa.diagnosaa && !diagnosa.norecDiagnosa
        )
        if (emptyColumnIndex !== -1) {
          let objSave = {
            diagnosapasien: {
              norec: newDiagnosa.norecDiagnosa,
              noregistrasifk: props.registrasi.norec_apd,
              ketdiagnosis: newDiagnosa.keterangan ? newDiagnosa.keterangan : null,
              iskasusbaru: null,
              iskasuslama: null,
            },
            detaildiagnosapasien: {
              objectdiagnosafk: newDiagnosa.diagnosaa
                ? newDiagnosa.diagnosaa.value
                : null,
              tglinputdiagnosa: H.formatDate(new Date(), 'YYYY-MM-DD HH:mm:ss'),
              objectjenisdiagnosafk: newDiagnosa.jenisDiagnosa
                ? newDiagnosa.jenisDiagnosa.value
                : null,
              noregistrasi: props.registrasi.noregistrasi,
            },
            pasien: {
              nocm: props.pasien.nocm,
              namapasien: props.pasien.namapasien,
              noregistrasi: props.registrasi.noregistrasi,
            },
          }

          try {
            useApi().post(`/diagnosa/save-diagnosa`, objSave)
            if (input.value.details[0]?.diagnosaDokter instanceof Array) {
              input.value.details[0].diagnosaDokter[emptyColumnIndex] = newDiagnosa
            }
          } catch (error) {
            console.error('Error while saving diagnosis:', error)
          }
        } else {
          let objSave = {
            diagnosapasien: {
              norec: newDiagnosa.norecDiagnosa,
              noregistrasifk: props.registrasi.norec_apd,
              ketdiagnosis: newDiagnosa.keterangan ? newDiagnosa.keterangan : null,
              iskasusbaru: null,
              iskasuslama: null,
            },
            detaildiagnosapasien: {
              objectdiagnosafk: newDiagnosa.diagnosaa
                ? newDiagnosa.diagnosaa.value
                : null,
              tglinputdiagnosa: H.formatDate(new Date(), 'YYYY-MM-DD HH:mm:ss'),
              objectjenisdiagnosafk: newDiagnosa.jenisDiagnosa
                ? newDiagnosa.jenisDiagnosa.value
                : null,
              noregistrasi: props.registrasi.noregistrasi,
            },
            pasien: {
              nocm: props.pasien.nocm,
              namapasien: props.pasien.namapasien,
              noregistrasi: props.registrasi.noregistrasi,
            },
          }

          try {
            useApi().post(`/diagnosa/save-diagnosa`, objSave)
            if (input.value.details[0]?.diagnosaDokter instanceof Array) {
              input.value.details[0].diagnosaDokter.push(newDiagnosa)
            }
          } catch (error) {
            console.error('Error while saving diagnosis:', error)
          }
        }
      } else {
        const newDiagnosa = {
          ...element,
          no: input.value.details[index].diagnosaDokter9.length + 1,
          norecDiagnosa: null,
        }
        const emptyColumnIndex = input.value.details[0]?.diagnosaDokter9.findIndex(
          (diagnosa) => !diagnosa.diagnosaa && !diagnosa.norecDiagnosa9
        )
        if (emptyColumnIndex !== -1) {
          let objSave = {
            diagnosapasien: {
              norec: newDiagnosa.norecDiagnosa9,
              noregistrasifk: props.registrasi.norec_apd,
              tglregistrasi: props.registrasi.tglregistrasi,
              keterangantindakan: newDiagnosa.keterangan ? newDiagnosa.keterangan : null,
            },
            detaildiagnosapasien: {
              objectdiagnosatindakanfk: newDiagnosa.diagnosaa
                ? newDiagnosa.diagnosaa.value
                : null,
              tglinputdiagnosa: H.formatDate(new Date(), 'YYYY-MM-DD HH:mm:ss'),
              noregistrasi: props.registrasi.noregistrasi,
            },
          }
          try {
            useApi().post(`/diagnosa/save-diagnosa-ix`, objSave)
            if (input.value.details[0]?.diagnosaDokter9 instanceof Array) {
              input.value.details[0].diagnosaDokter9[emptyColumnIndex] = newDiagnosa
            }
          } catch (error) {
            console.error('Error while saving diagnosis:', error)
          }
        } else {
          let objSave = {
            diagnosapasien: {
              norec: newDiagnosa.norecDiagnosa9,
              noregistrasifk: props.registrasi.norec_apd,
              tglregistrasi: props.registrasi.tglregistrasi,
              keterangantindakan: newDiagnosa.keterangan ? newDiagnosa.keterangan : null,
            },
            detaildiagnosapasien: {
              objectdiagnosatindakanfk: newDiagnosa.diagnosaa
                ? newDiagnosa.diagnosaa.value
                : null,
              tglinputdiagnosa: H.formatDate(new Date(), 'YYYY-MM-DD HH:mm:ss'),
              noregistrasi: props.registrasi.noregistrasi,
            },
          }
          try {
            useApi().post(`/diagnosa/save-diagnosa-ix`, objSave)
            if (input.value.details[0]?.diagnosaDokter9 instanceof Array) {
              input.value.details[0].diagnosaDokter9.push(newDiagnosa)
            }
          } catch (error) {
            console.error('Error while saving diagnosis:', error)
          }
        }
      }
    })
  } else {
    const copiedDiagnosa = JSON.parse(localStorage.getItem('copiedDiagnosa'))
    const newDiagnosa = {
      ...copiedDiagnosa,
      no: input.value.details[index].diagnosaDokter9.length + 1,
      norecDiagnosa: null,
      // norecDiagnosa9: generateNewId()
    }
    const emptyColumnIndex = input.value.details[0]?.diagnosaDokter9.findIndex(
      (diagnosa) => !diagnosa.diagnosaa && !diagnosa.norecDiagnosa9
    )
    if (emptyColumnIndex !== -1) {
      let objSave = {
        diagnosapasien: {
          norec: newDiagnosa.norecDiagnosa9,
          noregistrasifk: props.registrasi.norec_apd,
          tglregistrasi: props.registrasi.tglregistrasi,
          keterangantindakan: newDiagnosa.keterangan ? newDiagnosa.keterangan : null,
        },
        detaildiagnosapasien: {
          objectdiagnosatindakanfk: newDiagnosa.diagnosaa
            ? newDiagnosa.diagnosaa.value
            : null,
          tglinputdiagnosa: H.formatDate(new Date(), 'YYYY-MM-DD HH:mm:ss'),
          noregistrasi: props.registrasi.noregistrasi,
        },
      }
      try {
        await useApi().post(`/diagnosa/save-diagnosa-ix`, objSave)
        if (input.value.details[0]?.diagnosaDokter9 instanceof Array) {
          input.value.details[0].diagnosaDokter9[emptyColumnIndex] = newDiagnosa
        }
      } catch (error) {
        console.error('Error while saving diagnosis:', error)
      }
    } else {
      let objSave = {
        diagnosapasien: {
          norec: newDiagnosa.norecDiagnosa9,
          noregistrasifk: props.registrasi.norec_apd,
          tglregistrasi: props.registrasi.tglregistrasi,
          keterangantindakan: newDiagnosa.keterangan ? newDiagnosa.keterangan : null,
        },
        detaildiagnosapasien: {
          objectdiagnosatindakanfk: newDiagnosa.diagnosaa
            ? newDiagnosa.diagnosaa.value
            : null,
          tglinputdiagnosa: H.formatDate(new Date(), 'YYYY-MM-DD HH:mm:ss'),
          noregistrasi: props.registrasi.noregistrasi,
        },
      }
      try {
        await useApi().post(`/diagnosa/save-diagnosa-ix`, objSave)
        if (input.value.details[0]?.diagnosaDokter9 instanceof Array) {
          input.value.details[0].diagnosaDokter9.push(newDiagnosa)
        }
      } catch (error) {
        console.error('Error while saving diagnosis:', error)
      }
      // input.value.details[0].diagnosaDokter9.push(newDiagnosa);
    }
  }
}

const clear = () => {
  item.filter = ''
  isPerawat.value = false
  isProfesi.value = false
  isDokter.value = false
  isGizi.value = false
  isSbar.value = false
}
watch(
  () => isCPPTOLD.value,
  () => {
    if (isCPPTOLD.value) {
      loadRiwayatOld()
    }
  }
)

watch(
  () => kelompokUser,
  (newKelompokUser, oldKelompokUser) => {
    if (newKelompokUser === 'dokter' && !isDokter.value) {
      item.filter = 'dokter'
      isDokter.value = true
      isPerawat.value = false
      isProfesi.value = false
      isGizi.value = false
    }
    if (newKelompokUser === 'perawat' && !isPerawat.value) {
      item.filter = 'perawat'
      isDokter.value = false
      isPerawat.value = true
      isProfesi.value = false
      isGizi.value = false
    }
    if (newKelompokUser === 'gizi' && !isGizi.value) {
      item.filter = 'gizi'
      isDokter.value = false
      isPerawat.value = false
      isProfesi.value = false
      isGizi.value = true
    }
    if (
      newKelompokUser !== 'perawat' &&
      newKelompokUser !== 'dokter' &&
      newKelompokUser !== 'gizi' &&
      !isProfesi.value
    ) {
      item.filter = 'profesi lain'
      isDokter.value = false
      isPerawat.value = false
      isProfesi.value = true
      isGizi.value = false
    }
  },
  { immediate: true }
)

watch(
  () => isPerawat.value,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      if (isPerawat.value) {
        item.filter = 'perawat'
        isDokter.value = false
        isProfesi.value = false
        isGizi.value = false
        isSbar.value = false
      }
    }
  }
)

watch(
  () => isDokter.value,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      if (isDokter.value) {
        item.filter = 'dokter'
        isPerawat.value = false
        isProfesi.value = false
        isGizi.value = false
        isSbar.value = false
      }
    }
  }
)

watch(
  () => isProfesi.value,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      if (isProfesi.value) {
        item.filter = 'profesi lain'
        isPerawat.value = false
        isDokter.value = false
        isGizi.value = false
        isSbar.value = false
      }
    }
  }
)

watch(
  () => isSbar.value,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      if (isSbar.value) {
        item.filter = 'sbar'
        isPerawat.value = false
        isDokter.value = false
        isGizi.value = false
        isProfesi.value = false
      }
    }
  }
)

watch(
  () => isGizi.value,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      if (isGizi.value) {
        item.filter = 'gizi'
        isPerawat.value = false
        isDokter.value = false
        isProfesi.value = false
        isSbar.value = false
      }
    }
  }
)

watch(
  () => [isGizi.value, isProfesi.value, isDokter.value, isPerawat.value, isSbar.value],
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      if (
        !isProfesi.value &&
        !isDokter.value &&
        !isPerawat.value &&
        !isGizi.value &&
        !isSbar.value
      ) {
        item.filter = ''
      }
    }
  }
)

watch(
  () => isResep.value,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      if (newValue) {
        showPreviewResep.value = true
      }
    }
  }
)

const addItem = (filter) => {
      const existing = input.value.details.some((item) => item.flag === filter)
      if (!existing) {
        const baseData = {
          uuid: uuidv4(),
          no:
            input.value.details.length > 0
              ? input.value.details[input.value.details.length - 1].no + 1
              : 1,
          tgl: new Date(),
          tglVerifikasi: new Date(),
          flag: filter
        }

        if (filter === 'dokter') {
          baseData.diagnosaDokter = [{ no: 1 }]
          baseData.diagnosaDokter9 = [{ no: 1 }]
          baseData.tenagaMedis = {
            label: useUserSession().getUser().pegawai.namaLengkap,
            value: useUserSession().getUser().pegawai.id
          }
        }

        input.value.details.push(baseData)
      }
    }

    watch(
      () => item.filter,
      (newFilter) => {
        if (newFilter) {
          addItem(newFilter)
        }
      }
    )

// watch(
//   () => item.filter,
//   (newFilter) => {
//     if (newFilter === 'profesi lain') {
//       const existing = input.value.details.some((item) => item.flag === 'profesi lain')
//       if (!existing) {
//         input.value.details.push({
//           uuid: uuidv4(),
//           no:
//             input.value.details.length > 0
//               ? input.value.details[input.value.details.length - 1].no + 1
//               : 1,
//           tgl: new Date(),
//           tglVerifikasi: new Date(),
//           flag: 'profesi lain',
//         })
//       }
//     }
//     if (newFilter === 'gizi') {
//       const existing = input.value.details.some((item) => item.flag === 'gizi')
//       if (!existing) {
//         input.value.details.push({
//           uuid: uuidv4(),
//           no:
//             input.value.details.length > 0
//               ? input.value.details[input.value.details.length - 1].no + 1
//               : 1,
//           tgl: new Date(),
//           tglVerifikasi: new Date(),
//           flag: 'gizi',
//         })
//       }
//     }
//     if (newFilter === 'dokter') {
//       const existing = input.value.details.some((item) => item.flag === 'dokter')
//       if (!existing) {
//         input.value.details.push({
//           uuid: uuidv4(),
//           no: input.value.details[input.value.details.length - 1].no + 1,
//           tgl: new Date(),
//           tglVerifikasi: new Date(),
//           flag: 'dokter',
//           diagnosaDokter: [{ no: 1 }],
//           diagnosaDokter9: [{ no: 1 }],
//           tenagaMedis: {
//             label: useUserSession().getUser().pegawai.namaLengkap,
//             value: useUserSession().getUser().pegawai.id,
//           },
//         })
//       }
//     }
//     if (newFilter === 'perawat') {
//       const existing = input.value.details.some((item) => item.flag === 'perawat')
//       if (!existing) {
//         input.value.details.push({
//           uuid: uuidv4(),
//           no:
//             input.value.details.length > 0
//               ? input.value.details[input.value.details.length - 1].no + 1
//               : 1,
//           tgl: new Date(),
//           tglVerifikasi: new Date(),
//           flag: 'perawat',
//         })
//       }
//     }
//     if (newFilter === 'sbar') {
//       const existing = input.value.details.some((item) => item.flag === 'sbar')
//       if (!existing) {
//         input.value.details.push({
//           uuid: uuidv4(),
//           no:
//             input.value.details.length > 0
//               ? input.value.details[input.value.details.length - 1].no + 1
//               : 1,
//           tgl: new Date(),
//           tglVerifikasi: new Date(),
//           flag: 'sbar',
//         })
//       }
//     }
//   }
// )

setView()
SourceHasilLab()
getHasilRad()
loadRiwayat()
// onMounted(() => {
//   pasteResep();
// });
// fetchDiagnosaX()
</script>

<style lang="scss">
.CPPT_HEIGHT {
  overflow: auto;
  height: 600px;
}

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  background-color: var(--white);
  border-color: var(--fade-grey-dark-2) !important;
}

.tg-card {
  background-color: #feffed;
}

.is-dark {
  .tg {
    background-color: var(--dark-sidebar-light-6);
  }

  .tg-card {
    background-color: var(--dark-sidebar-light-6);
  }
}

.tg td {
  border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 6px;
  word-break: normal;
}

.tg th {
  border-color: var(--fade-grey-dark-3) !important;
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
}

.scroll-container-rev {
  height: 1000px;
  overflow: auto;
}

.btn-cppt {
  -webkit-border-radius: 20px;
  -moz-border-radius: 20px;
  border-radius: 20px;
  cursor: pointer;
  font-size: 12px;
  font-weight: bolder;
  color: #005319;
  padding: 7px 20px;
  min-width: 90px;
  text-align: center;
  position: fixed;
  -webkit-transition-duration: 0.25s;
  -webkit-transition-duration: 0.25s;
  -moz-transition-duration: 0.25s;
  -o-transition-duration: 0.25s;
  transition-duration: 0.25s;
  -webkit-transition-property: background-color, -webkit-box-shadow;
  right: 0;
  -webkit-transition-property: background-color, box-shadow;
  -o-transition-property: background-color, box-shadow;
  transition-property: background-color, box-shadow;
  z-index: 2;
}

.btn-border-cppt {
  border: 2px solid #005319;
}

.btn-full-cppt {
  background-color: #a9ebbd;
}

@media (max-width: 1144px) {
  .table-tg {
    width: 150%;
  }
}

.pembungkus {
  height: 100% !important;
  border-top: 2px solid var(--blue-500);
}
</style>
