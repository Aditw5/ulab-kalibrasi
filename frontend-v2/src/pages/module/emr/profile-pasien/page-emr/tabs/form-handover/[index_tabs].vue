<template>
  <ConfirmDialog group="headless">
    <template #container="{ message, acceptCallback, rejectCallback }">
      <div class="box has-text-centered">
        <div class="is-flex is-justify-content-center is-align-items-center mb-4 -mt-3">
          <span class="icon is-large has-text-primary">
            <i class="pi pi-question fa-3x"></i>
          </span>
        </div>
        <h2 class="title is-5">{{ message.header }}</h2>
        <p class="content">{{ message.message }}</p>
        <div class="buttons is-centered mt-4">
          <button class="button is-primary" @click="acceptCallback">
            {{ message.acceptLabel }}
          </button>
          <button class="button is-light" @click="rejectCallback">
            {{ message.rejectLabel }}
          </button>
        </div>
      </div>
    </template>
  </ConfirmDialog>
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
            <h3>{{ props.FORM_NAME }} Halaman {{ route.params.index_tabs }}</h3>
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
            <h3>{{ props.FORM_NAME }} Halaman {{ route.params.index_tabs }}</h3>
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

  <div class="columns is-12 mt-3">
    <VCard>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> No. RM :</span>
          </div>
          <div class="column is-10">
            <VField>
              <VControl class="prime-auto">
                <VInput
                  type="text"
                  class="input"
                  placeholder="No. RM"
                  v-model="input.noRM"
                />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Hari / Tanggal :</span>
          </div>
          <div class="column is-10">
            <VField>
              <VControl class="prime-auto">
                <VInput
                  type="text"
                  class="input"
                  placeholder="Nama"
                  v-model="input.tanggalHandover"
                />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Nama :</span>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="Nama"
                  v-model="input.nama"
                />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Tanggal Lahir :</span>
          </div>
          <div class="column is-10">
            <VField>
              <VControl class="prime-auto">
                <VInput
                  type="text"
                  class="input"
                  placeholder="Umur/Tanggal Lahir"
                  v-model="input.umurTanggalLahir"
                />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Jenis Kelamin :</span>
          </div>
          <div class="column is-10">
            <VField>
              <VControl class="prime-auto">
                <VInput
                  type="text"
                  class="input"
                  placeholder="Jenis Kelamin"
                  v-model="input.jenisKelamin"
                />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
    </VCard>
  </div>

  <div class="columns is-12 mt-3">
    <Fieldset legend="Pagi" :toggleable="true">
      <VCard>
        <div class="column is-12 p-0">
          <div class="is-flex columns is-multiline">
            <div class="column is-12" style="margin-top: 0.5rem">
              <VButton
                v-if="!input.verifPagi"
                color="danger"
                icon="lucide:shield-alert"
                raised
                rounded
                class="mt-3 mb-3 pulse"
              >
                Belum Verifikasi
              </VButton>
              <VButton
                v-if="input.verifPagi"
                color="primary"
                icon="lucide:qr-code"
                raised
                rounded
                @click = "showQR('pagi')"
                class="mt-3 mb-3 pulse"
              >
                Sudah Verifikasi Handover
              </VButton>
            </div>
          </div>
        </div>
        <div class="column is-12 p-0">
          <div class="is-flex columns is-multiline">
            <div class="column is-12" style="margin-top: 0.5rem">
              <b> Situation :</b>
            </div>
            <div class="column is-12">
              <VField>
                <VControl class="prime-auto">
                  <VTextarea
                    rows="2"
                    placeholder="Situtation"
                    v-model="input.situationPagi"
                  ></VTextarea>
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column is-12 p-0">
          <div class="is-flex columns is-multiline">
            <div class="column is-12" style="margin-top: 0.5rem">
              <b> Background </b>
            </div>
            <div class="column is-2" style="margin-top: 0.5rem">
              <span> Dx/ Medis </span>
            </div>
            <div class="column is-10">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Diagnosa Medis"
                    v-model="input.diagnosaPagi"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="margin-top: 0.5rem">
              <span> DPJP </span>
            </div>
            <div class="column is-10">
              <VField>
                <VControl class="prime-auto">
                  <AutoComplete
                    v-model="input.dpjpPagi"
                    :suggestions="d_Dokter"
                    @complete="fetchDokter($event)"
                    :optionLabel="'label'"
                    :dropdown="true"
                    :minLength="3"
                    :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'"
                    :field="'label'"
                    placeholder="Dokter"
                    class="mt-2"
                  />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column is-12 p-0">
          <div class="is-flex columns is-multiline">
            <div class="column is-12" style="margin-top: 0.5rem">
              <b> Asesmen :</b>
            </div>
            <div class="column is-12">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Asesmen"
                    v-model="input.asesmenPagi"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="margin-top: 0.5rem">
              <span> Kesadaran :</span>
            </div>
            <div class="column is-4">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Asesmen"
                    v-model="input.kesadaranPagi"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="margin-top: 0.5rem">
              <span> GCS :</span>
            </div>
            <div class="column is-4">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Asesmen"
                    v-model="input.GCSPagi"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-12" style="margin-top: 0.5rem">
              <span> Tanda Vital :</span>
            </div>
            <div class="column is-1" style="margin-top: 0.5rem">
              <span> TD :</span>
            </div>
            <div class="column is-3">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="TD (mmHg)"
                    v-model="input.TDPagi"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-1" style="margin-top: 0.5rem">
              <span> HR :</span>
            </div>
            <div class="column is-3">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="HR (x/mnt)"
                    v-model="input.HRPagi"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-1" style="margin-top: 0.5rem">
              <span> Suhu :</span>
            </div>
            <div class="column is-3">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Suhu (C)"
                    v-model="input.SuhuPagi"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-1" style="margin-top: 0.5rem">
              <span> Nyeri :</span>
            </div>
            <div class="column is-3">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Nyeri"
                    v-model="input.NyeriPagi"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-1" style="margin-top: 0.5rem">
              <span> Oksigen :</span>
            </div>
            <div class="column is-3">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Oksigen (L/mnt)"
                    v-model="input.OksigenPagi"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-1" style="margin-top: 0.5rem">
              <span> Infus :</span>
            </div>
            <div class="column is-3">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Infus (tts/mnt)"
                    v-model="input.InfusPagi"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-1" style="margin-top: 0.5rem">
              <span> Transfusi :</span>
            </div>
            <div class="column is-3">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Transfusi (tts/mnt)"
                    v-model="input.TransfusiPagi"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-1" style="margin-top: 0.5rem">
              <span> Kateter :</span>
            </div>
            <div class="column is-3">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox
                    v-model="input.kateterPagi"
                    true-value="Ya"
                    label="Ya"
                    class="p-0"
                    color="primary"
                    square
                  />
                </VControl>
              </VField>
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox
                    v-model="input.kateterPagi"
                    true-value="Tidak"
                    label="Tidak"
                    class="p-0"
                    color="primary"
                    square
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-1" style="margin-top: 0.5rem">
              <span> NGT :</span>
            </div>
            <div class="column is-3">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox
                    v-model="input.NGTPagi"
                    true-value="Ya"
                    label="Ya"
                    class="p-0"
                    color="primary"
                    square
                  />
                </VControl>
              </VField>
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox
                    v-model="input.NGTPagi"
                    true-value="Tidak"
                    label="Tidak"
                    class="p-0"
                    color="primary"
                    square
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="margin-top: 0.5rem">
              <span> Makan/minum </span>
            </div>
            <div class="column is-10">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Makan/minum"
                    v-model="input.makanMinumPagi"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="margin-top: 0.5rem">
              <span> Toileting </span>
            </div>
            <div class="column is-10">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Toileting"
                    v-model="input.toiletingPagi"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="margin-top: 0.5rem">
              <span> Aktifitas/Gerak </span>
            </div>
            <div class="column is-10">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Aktifitas/Gerak"
                    v-model="input.aktifitasPagi"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="margin-top: 0.5rem">
              <span> Skor Jatuh </span>
            </div>
            <div class="column is-10">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Skor Jatuh"
                    v-model="input.skorJatuhPagi"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="margin-top: 0.5rem">
              <span> Informasi Tambahan </span>
            </div>
            <div class="column is-10">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Informasi Tambahan"
                    v-model="input.informasiTambahanPagi"
                  />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column is-12 p-0">
          <div class="is-flex columns is-multiline">
            <div class="column is-12" style="margin-top: 0.5rem">
              <b> Recommendation :</b>
            </div>
            <div class="column is-12">
              <VField>
                <VControl class="prime-auto">
                  <VTextarea
                    rows="2"
                    placeholder="Recommendation"
                    v-model="input.recommendationPagi"
                  ></VTextarea>
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column is-12 p-0">
          <div class="columns is-multiline">
            <div class="column is-6">
              <div style="text-align: center">
                <!-- <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan> -->
                <img
                  v-if="input.pemberiOperanPagi"
                  :src="
                    'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' +
                    (input.pemberiOperanPagi ? input.pemberiOperanPagi.label : '-')
                  "
                />
              </div>
              <div>
                <h1 class="p-0" style="font-weight: bold">Pemberian Operan</h1>
                <VField>
                  <VControl class="prime-auto">
                    <AutoComplete
                      v-model="input.pemberiOperanPagi"
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
            </div>
            <div class="column is-6">
              <div style="text-align: center">
                <!-- <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan> -->
                <img
                  v-if="input.penerimaOperanPagi"
                  :src="
                    'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' +
                    (input.penerimaOperanPagi ? input.penerimaOperanPagi.label : '-')
                  "
                />
              </div>
              <div>
                <h1 class="p-0" style="font-weight: bold">Penerima Operan</h1>
                <VField>
                  <VControl class="prime-auto">
                    <AutoComplete
                      v-model="input.penerimaOperanPagi"
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
            </div>
          </div>
        </div>
        <VButton
          v-if="!input.verifPagi"
          color="primary"
          icon="lucide:circle-check-big"
          raised
          rounded
          @click="verifHandover('pagi')"
          class="mt-3 mb-3"
        >
          Verifikasi Shift Pagi
        </VButton>
      </VCard>
    </Fieldset>
  </div>

  <div class="columns is-12 mt-3">
    <Fieldset legend="Siang" :toggleable="true">
      <VCard>
        <VButton
          v-if="!input.verifSiang"
          color="danger"
          icon="lucide:shield-alert"
          raised
          rounded
          class="mt-3 mb-3 pulse"
        >
          Belum Verifikasi
        </VButton>
        <VButton
                v-if="input.verifSiang"
                color="primary"
                icon="lucide:qr-code"
                raised
                rounded
                class="mt-3 mb-3 pulse"
                @click = "showQR('siang')"
              >
                Sudah Verifikasi Handover
              </VButton>
        <div class="column is-12 p-0">
          <div class="is-flex columns is-multiline">
            <div class="column is-12" style="margin-top: 0.5rem">
              <b> Situation :</b>
            </div>
            <div class="column is-12">
              <VField>
                <VControl class="prime-auto">
                  <VTextarea
                    rows="2"
                    placeholder="Situtation"
                    v-model="input.situationSiang"
                  ></VTextarea>
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column is-12 p-0">
          <div class="is-flex columns is-multiline">
            <div class="column is-12" style="margin-top: 0.5rem">
              <b> Background </b>
            </div>
            <div class="column is-2" style="margin-top: 0.5rem">
              <span> Dx/ Medis </span>
            </div>
            <div class="column is-10">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Diagnosa Medis"
                    v-model="input.diagnosaSiang"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="margin-top: 0.5rem">
              <span> DPJP </span>
            </div>
            <div class="column is-10">
              <VField>
                <VControl class="prime-auto">
                  <AutoComplete
                    v-model="input.dpjpSiang"
                    :suggestions="d_Dokter"
                    @complete="fetchDokter($event)"
                    :optionLabel="'label'"
                    :dropdown="true"
                    :minLength="3"
                    :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'"
                    :field="'label'"
                    placeholder="Dokter"
                    class="mt-2"
                  />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column is-12 p-0">
          <div class="is-flex columns is-multiline">
            <div class="column is-12" style="margin-top: 0.5rem">
              <b> Asesmen :</b>
            </div>
            <div class="column is-12">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Asesmen"
                    v-model="input.asesmenSiang"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="margin-top: 0.5rem">
              <span> Kesadaran :</span>
            </div>
            <div class="column is-4">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Asesmen"
                    v-model="input.kesadaranSiang"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="margin-top: 0.5rem">
              <span> GCS :</span>
            </div>
            <div class="column is-4">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Asesmen"
                    v-model="input.GCSSiang"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-12" style="margin-top: 0.5rem">
              <span> Tanda Vital :</span>
            </div>
            <div class="column is-1" style="margin-top: 0.5rem">
              <span> TD :</span>
            </div>
            <div class="column is-3">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="TD (mmHg)"
                    v-model="input.TDSiang"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-1" style="margin-top: 0.5rem">
              <span> HR :</span>
            </div>
            <div class="column is-3">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="HR (x/mnt)"
                    v-model="input.HRSiang"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-1" style="margin-top: 0.5rem">
              <span> Suhu :</span>
            </div>
            <div class="column is-3">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Suhu (C)"
                    v-model="input.SuhuSiang"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-1" style="margin-top: 0.5rem">
              <span> Nyeri :</span>
            </div>
            <div class="column is-3">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Nyeri"
                    v-model="input.NyeriSiang"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-1" style="margin-top: 0.5rem">
              <span> Oksigen :</span>
            </div>
            <div class="column is-3">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Oksigen (L/mnt)"
                    v-model="input.OksigenSiang"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-1" style="margin-top: 0.5rem">
              <span> Infus :</span>
            </div>
            <div class="column is-3">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Infus (tts/mnt)"
                    v-model="input.InfusSiang"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-1" style="margin-top: 0.5rem">
              <span> Transfusi :</span>
            </div>
            <div class="column is-3">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Transfusi (tts/mnt)"
                    v-model="input.TransfusiSiang"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-1" style="margin-top: 0.5rem">
              <span> Kateter :</span>
            </div>
            <div class="column is-3">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox
                    v-model="input.kateterSiang"
                    true-value="Ya"
                    label="Ya"
                    class="p-0"
                    color="primary"
                    square
                  />
                </VControl>
              </VField>
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox
                    v-model="input.kateterSiang"
                    true-value="Tidak"
                    label="Tidak"
                    class="p-0"
                    color="primary"
                    square
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-1" style="margin-top: 0.5rem">
              <span> NGT :</span>
            </div>
            <div class="column is-3">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox
                    v-model="input.NGTSiang"
                    true-value="Ya"
                    label="Ya"
                    class="p-0"
                    color="primary"
                    square
                  />
                </VControl>
              </VField>
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox
                    v-model="input.NGTSiang"
                    true-value="Tidak"
                    label="Tidak"
                    class="p-0"
                    color="primary"
                    square
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="margin-top: 0.5rem">
              <span> Makan/minum </span>
            </div>
            <div class="column is-10">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Makan/minum"
                    v-model="input.makanMinumSiang"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="margin-top: 0.5rem">
              <span> Toileting </span>
            </div>
            <div class="column is-10">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Toileting"
                    v-model="input.toiletingSiang"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="margin-top: 0.5rem">
              <span> Aktifitas/Gerak </span>
            </div>
            <div class="column is-10">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Aktifitas/Gerak"
                    v-model="input.aktifitasSiang"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="margin-top: 0.5rem">
              <span> Skor Jatuh </span>
            </div>
            <div class="column is-10">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Skor Jatuh"
                    v-model="input.skorJatuhSiang"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="margin-top: 0.5rem">
              <span> Informasi Tambahan </span>
            </div>
            <div class="column is-10">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Informasi Tambahan"
                    v-model="input.informasiTambahanSiang"
                  />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column is-12 p-0">
          <div class="is-flex columns is-multiline">
            <div class="column is-12" style="margin-top: 0.5rem">
              <b> Recommendation :</b>
            </div>
            <div class="column is-12">
              <VField>
                <VControl class="prime-auto">
                  <VTextarea
                    rows="2"
                    placeholder="Recommendation"
                    v-model="input.recommendationSiang"
                  ></VTextarea>
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column is-12 p-0">
          <div class="columns is-multiline">
            <div class="column is-6">
              <div style="text-align: center">
                <!-- <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan> -->
                <img
                  v-if="input.pemberiOperanSiang"
                  :src="
                    'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' +
                    (input.pemberiOperanSiang ? input.pemberiOperanSiang.label : '-')
                  "
                />
              </div>
              <div>
                <h1 class="p-0" style="font-weight: bold">Pemberian Operan</h1>
                <VField>
                  <VControl class="prime-auto">
                    <AutoComplete
                      v-model="input.pemberiOperanSiang"
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
            </div>
            <div class="column is-6">
              <div style="text-align: center">
                <!-- <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan> -->
                <img
                  v-if="input.penerimaOperanSiang"
                  :src="
                    'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' +
                    (input.penerimaOperanSiang ? input.penerimaOperanSiang.label : '-')
                  "
                />
              </div>
              <div>
                <h1 class="p-0" style="font-weight: bold">Penerima Operan</h1>
                <VField>
                  <VControl class="prime-auto">
                    <AutoComplete
                      v-model="input.penerimaOperanSiang"
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
            </div>
          </div>
        </div>
        <VButton
          v-if="!input.verifSiang"
          color="primary"
          icon="lucide:circle-check-big"
          raised
          rounded
          @click="verifHandover('siang')"
          class="mt-3 mb-3"
        >
          Verifikasi Shift Siang
        </VButton>
      </VCard>
    </Fieldset>
  </div>

  <div class="columns is-12 mt-3">
    <Fieldset legend="Sore" :toggleable="true">
      <VCard>
        <VButton
          v-if="!input.verifMalam"
          color="danger"
          icon="lucide:shield-alert"
          raised
          rounded
          class="mt-3 mb-3 pulse"
        >
          Belum Verifikasi
        </VButton>
        <VButton
                v-if="input.verifMalam"
                color="primary"
                icon="lucide:qr-code"
                raised
                rounded
                class="mt-3 mb-3 pulse"
                @click = "showQR('malam')"
              >
                Sudah Verifikasi Handover
              </VButton>
        <div class="column is-12 p-0">
          <div class="is-flex columns is-multiline">
            <div class="column is-12" style="margin-top: 0.5rem">
              <b> Situation :</b>
            </div>
            <div class="column is-12">
              <VField>
                <VControl class="prime-auto">
                  <VTextarea
                    rows="2"
                    placeholder="Situtation"
                    v-model="input.situationSore"
                  ></VTextarea>
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column is-12 p-0">
          <div class="is-flex columns is-multiline">
            <div class="column is-12" style="margin-top: 0.5rem">
              <b> Background </b>
            </div>
            <div class="column is-2" style="margin-top: 0.5rem">
              <span> Dx/ Medis </span>
            </div>
            <div class="column is-10">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Diagnosa Medis"
                    v-model="input.diagnosaSore"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="margin-top: 0.5rem">
              <span> DPJP </span>
            </div>
            <div class="column is-10">
              <VField>
                <VControl class="prime-auto">
                  <AutoComplete
                    v-model="input.dpjpSore"
                    :suggestions="d_Dokter"
                    @complete="fetchDokter($event)"
                    :optionLabel="'label'"
                    :dropdown="true"
                    :minLength="3"
                    :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'"
                    :field="'label'"
                    placeholder="Dokter"
                    class="mt-2"
                  />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column is-12 p-0">
          <div class="is-flex columns is-multiline">
            <div class="column is-12" style="margin-top: 0.5rem">
              <b> Asesmen :</b>
            </div>
            <div class="column is-12">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Asesmen"
                    v-model="input.asesmenSore"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="margin-top: 0.5rem">
              <span> Kesadaran :</span>
            </div>
            <div class="column is-4">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Asesmen"
                    v-model="input.kesadaranSore"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="margin-top: 0.5rem">
              <span> GCS :</span>
            </div>
            <div class="column is-4">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Asesmen"
                    v-model="input.GCSSore"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-12" style="margin-top: 0.5rem">
              <span> Tanda Vital :</span>
            </div>
            <div class="column is-1" style="margin-top: 0.5rem">
              <span> TD :</span>
            </div>
            <div class="column is-3">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="TD (mmHg)"
                    v-model="input.TDSore"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-1" style="margin-top: 0.5rem">
              <span> HR :</span>
            </div>
            <div class="column is-3">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="HR (x/mnt)"
                    v-model="input.HRSore"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-1" style="margin-top: 0.5rem">
              <span> Suhu :</span>
            </div>
            <div class="column is-3">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Suhu (C)"
                    v-model="input.SuhuSore"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-1" style="margin-top: 0.5rem">
              <span> Nyeri :</span>
            </div>
            <div class="column is-3">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Nyeri"
                    v-model="input.NyeriSore"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-1" style="margin-top: 0.5rem">
              <span> Oksigen :</span>
            </div>
            <div class="column is-3">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Oksigen (L/mnt)"
                    v-model="input.OksigenSore"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-1" style="margin-top: 0.5rem">
              <span> Infus :</span>
            </div>
            <div class="column is-3">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Infus (tts/mnt)"
                    v-model="input.InfusSore"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-1" style="margin-top: 0.5rem">
              <span> Transfusi :</span>
            </div>
            <div class="column is-3">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Transfusi (tts/mnt)"
                    v-model="input.TransfusiSore"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-1" style="margin-top: 0.5rem">
              <span> Kateter :</span>
            </div>
            <div class="column is-3">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox
                    v-model="input.kateterSore"
                    true-value="Ya"
                    label="Ya"
                    class="p-0"
                    color="primary"
                    square
                  />
                </VControl>
              </VField>
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox
                    v-model="input.kateterSore"
                    true-value="Tidak"
                    label="Tidak"
                    class="p-0"
                    color="primary"
                    square
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-1" style="margin-top: 0.5rem">
              <span> NGT :</span>
            </div>
            <div class="column is-3">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox
                    v-model="input.NGTSore"
                    true-value="Ya"
                    label="Ya"
                    class="p-0"
                    color="primary"
                    square
                  />
                </VControl>
              </VField>
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox
                    v-model="input.NGTSore"
                    true-value="Tidak"
                    label="Tidak"
                    class="p-0"
                    color="primary"
                    square
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="margin-top: 0.5rem">
              <span> Makan/minum </span>
            </div>
            <div class="column is-10">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Makan/minum"
                    v-model="input.makanMinumSore"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="margin-top: 0.5rem">
              <span> Toileting </span>
            </div>
            <div class="column is-10">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Toileting"
                    v-model="input.toiletingSore"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="margin-top: 0.5rem">
              <span> Aktifitas/Gerak </span>
            </div>
            <div class="column is-10">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Aktifitas/Gerak"
                    v-model="input.aktifitasSore"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="margin-top: 0.5rem">
              <span> Skor Jatuh </span>
            </div>
            <div class="column is-10">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Skor Jatuh"
                    v-model="input.skorJatuhSore"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="margin-top: 0.5rem">
              <span> Informasi Tambahan </span>
            </div>
            <div class="column is-10">
              <VField>
                <VControl class="prime-auto">
                  <VInput
                    type="text"
                    class="input"
                    placeholder="Informasi Tambahan"
                    v-model="input.informasiTambahanSore"
                  />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column is-12 p-0">
          <div class="is-flex columns is-multiline">
            <div class="column is-12" style="margin-top: 0.5rem">
              <b> Recommendation :</b>
            </div>
            <div class="column is-12">
              <VField>
                <VControl class="prime-auto">
                  <VTextarea
                    rows="2"
                    placeholder="Recommendation"
                    v-model="input.recommendationSore"
                  ></VTextarea>
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column is-12 p-0">
          <div class="columns is-multiline">
            <div class="column is-6">
              <div style="text-align: center">
                <!-- <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan> -->
                <img
                  v-if="input.pemberiOperanSore"
                  :src="
                    'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' +
                    (input.pemberiOperanSore ? input.pemberiOperanSore.label : '-')
                  "
                />
              </div>
              <div>
                <h1 class="p-0" style="font-weight: bold">Pemberian Operan</h1>
                <VField>
                  <VControl class="prime-auto">
                    <AutoComplete
                      v-model="input.pemberiOperanSore"
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
            </div>
            <div class="column is-6">
              <div style="text-align: center">
                <!-- <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan> -->
                <img
                  v-if="input.penerimaOperanSore"
                  :src="
                    'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' +
                    (input.penerimaOperanSore ? input.penerimaOperanSore.label : '-')
                  "
                />
              </div>
              <div>
                <h1 class="p-0" style="font-weight: bold">Penerima Operan</h1>
                <VField>
                  <VControl class="prime-auto">
                    <AutoComplete
                      v-model="input.penerimaOperanSore"
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
            </div>
          </div>
        </div>
        <VButton
          v-if="!input.verifMalam"
          color="primary"
          icon="lucide:circle-check-big"
          raised
          rounded
          @click="verifHandover('malam')"
          class="mt-3 mb-3"
        >
          Verifikasi Shift Malam
        </VButton>
      </VCard>
    </Fieldset>
  </div>
  <Dialog v-model:visible="isQR" modal :header="dataQR.titleQR">
      <div class="columns is-multiline">
        <div class="column is-12">
          <img
            :src="`https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${dataQR.isiQR}`"
          />
        </div>
      </div>
    </Dialog>
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
  onBeforeMount,
} from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave, onBeforeRouteUpdate } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../../../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete'
import Fieldset from 'primevue/fieldset'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import Divider from 'primevue/divider'
import Calendar from 'primevue/calendar'
import Dialog from 'primevue/dialog';
import { useConfirm } from 'primevue/useconfirm'
import ConfirmDialog from 'primevue/confirmdialog'
import * as EMR from '../../../page-emr-plugins/cek-list-edukasi-pasca-kateterisasi'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const route = useRoute()
const confirm = useConfirm()
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
const port = window.location.port
const isDev = port ? true : false

const d_Edukasi = ref(EMR.cekListEdukasiPascaKateterisasi())
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value >= 20
})
const isLoading: any = ref(false)
const d_Dokter: any = ref([])
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const isQR: any = ref(false)
const dataQR: any = ref({})
const loadData: any = ref(true)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
})
const COLLECTION: any = ref('formHandover') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tanggalMRS: new Date(),
  tanggalMNICU: new Date(),
  tanggalKNICU: new Date(),
  tanggalVerif: new Date(),
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
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

const verifHandover = (shift) => {
  console.log(input.value);
  confirm.require({
    group: 'headless',
    message: `Apakah Anda yakin memverifikasi Handover shift ${shift}?`,
    header: 'Konfirmasi Verifikasi Handover',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-primary ml-2',
    rejectClass: 'p-button-warning ml-2',
    accept: () => {
      if (shift == 'pagi') {
        input.value.verifPagi = true
        input.value.tglVerifPagi = H.formatDate(new Date(),'YYYY-MM-DD HH:mm')
        input.value.pegawaiVerifPagi = {
          id: useUserSession().getUser().pegawai.id,
          namalengkap: useUserSession().getUser().pegawai.namaLengkap,
        }
      } else if (shift == 'siang') {
        input.value.verifSiang = true
        input.value.tglVerifSiang = H.formatDate(new Date(),'YYYY-MM-DD HH:mm')
        input.value.pegawaiVerifSiang = {
          id: useUserSession().getUser().pegawai.id,
          namalengkap: useUserSession().getUser().pegawai.namaLengkap,
        }
      } else {
        input.value.verifMalam = true
        input.value.tglVerifMalam = H.formatDate(new Date(),'YYYY-MM-DD HH:mm')
        input.value.pegawaiVerifMalam = {
          id: useUserSession().getUser().pegawai.id,
          namalengkap: useUserSession().getUser().pegawai.namaLengkap,
        }
      }
      simpan()
    },
    reject: () => {},
    acceptLabel: 'Verif',
    rejectLabel: 'Tidak',
  })
}

const loadRiwayat = async () => {
  loadData.value = true
  await useApi()
    .get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&index_tabs=${route.params.index_tabs}`
    )
    .then((response: any) => {
      if (response.length) {
        input.value = response[0]
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }
    })
  loadData.value = false
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  input.value.tanggalMRS = H.formatDate(
    new Date(input.value.tanggalMRS),
    'YYYY-MM-DD HH:mm'
  )
  input.value.tanggalKNICU = H.formatDate(
    new Date(input.value.tanggalKNICU),
    'YYYY-MM-DD HH:mm'
  )
  input.value.tanggalMNICU = H.formatDate(
    new Date(input.value.tanggalMNICU),
    'YYYY-MM-DD HH:mm'
  )
  input.value.tanggalVerif = H.formatDate(
    new Date(input.value.tanggalVerif),
    'YYYY-MM-DD HH:mm'
  )
  if (route.params.index_tabs) {
    object.index_tabs = Array.isArray(route.params.index_tabs)
      ? parseInt(route.params.index_tabs[0])
      : parseInt(route.params.index_tabs)
  }

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
      loadRiwayat()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}
const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.nama = props.pasien.namapasien
  input.value.umurTanggalLahir = props.pasien.tgllahir
  input.value.noRM = props.pasien.nocm
  input.value.jenisKelamin = props.pasien.jeniskelamin
  input.value.ruangan = props.registrasi.namaruangan
  input.value.tanggalHandover = H.formatDateIndoNoTime(new Date())
}

const showQR = (data: any) => {
  let pemberiOperan = ''
  let penerimaOperan = ''
  let verifikatorDokumen = ''
  let tglVerifDokumen

  if(data == 'pagi'){
    pemberiOperan = input.value.pemberiOperanPagi ? input.value.pemberiOperanPagi.label : ''
    penerimaOperan = input.value.penerimaOperanPagi ? input.value.penerimaOperanPagi.label : ''
    verifikatorDokumen = input.value.pegawaiVerifPagi ? input.value.pegawaiVerifPagi.namalengkap : ''
    tglVerifDokumen = input.value.tglVerifPagi
  }else if(data == 'siang'){
    pemberiOperan = input.value.pemberiOperanSiang ? input.value.pemberiOperanSiang.label : ''
    penerimaOperan = input.value.penerimaOperanSiang ? input.value.penerimaOperanSiang.label : ''
    verifikatorDokumen = input.value.pegawaiVerifSiang ? input.value.pegawaiVerifSiang.namalengkap : ''
    tglVerifDokumen = input.value.tglVerifSiang
  }else{
    pemberiOperan = input.value.pemberiOperanMalam ? input.value.pemberiOperanMalam.label : ''
    penerimaOperan = input.value.penerimaOperanMalam ? input.value.penerimaOperanMalam.label : ''
    verifikatorDokumen = input.value.pegawaiVerifMalam ? input.value.pegawaiVerifMalam.namalengkap : ''
    tglVerifDokumen = input.value.tglVerifMalam
  }

  isQR.value = true
  dataQR.value.titleQR = 'QR Verifikasi Handover'
  dataQR.value.isiQR = `
  Nama Pasien: ${props.pasien.namapasien}
  Nama Dokumen: Form Handover
  Pemberi Operan: ${pemberiOperan}
  Penerima Operan: ${penerimaOperan}
  Tanggal Verifikasi: ${tglVerifDokumen}
  Nama Verifikator: ${verifikatorDokumen}
  `;
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
    input.value.tandaTanganDokter = response.ttd
  } else {
    H.tandaTangan().set('signature_1', '')
  }
}
onBeforeMount(async () => {
  try {
    await loadRiwayat()
    let rouutename = String(route.name) + '-' + route.params.index_tabs
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${rouutename}`)
    if (cache) input.value = cache
  } catch (error) {
    console.error('Error mount cache TAB EMR:', error)
  }
})

onBeforeRouteLeave((to, from, next) => {
  try {
    let rouutename =
      String(from?.name) +
      '-' +
      (Array.isArray(route.params.index_tabs)
        ? route.params.index_tabs[0]
        : route.params.index_tabs)
    H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
  } catch (error) {
    console.error('Error leave cache TAB EMR:', error)
  }
  next()
})
watch(
  () => route.params.index_tabs,
  (newValue, oldValue) => {
    input.value = {}
    setAutoFill()
    loadRiwayat()
    let rouutename = String(route.name) + '-' + route.params.index_tabs
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${rouutename}`)
    if (cache) {
      input.value = cache
    }
  }
)
watch(
  () => input.value,
  (newValue, oldValue) => {
    let rouutename = String(route.name) + '-' + route.params.index_tabs
    H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
    let timeout = null
    if (timeout) {
      clearTimeout(timeout)
    }
    timeout = setTimeout(() => {
      H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, newValue)
    }, 500)
  },
  { deep: true }
)

setAutoFill()
setView()
loadRiwayat()
</script>
<style scoped lang="scss">
.tbl-kmNICU {
  width: 100%;
  border-collapse: collapse;
  font-size: 12pt;
}

.tbl-kmNICU tr,
.tbl-kmNICU th,
.tbl-kmNICU td {
  border: 1px solid black;
  padding: 5px;
}

/* originally authored by Nick Pettit - https://github.com/nickpettit/glide */

@keyframes pulse {
  from {
    transform: scale(1);
  }

  50% {
    transform: scale(1.05);
  }

  to {
    transform: scale(1);
  }
}

.pulse {
  animation-name: pulse;
  animation-duration: 1s;
  animation-timing-function: ease-in-out;
  animation-iteration-count: infinite; /* Ulang terus */
}
</style>
