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

  <div class="column is-12">
    <VCard>
      <h3 class="title is-5 mb-2">Bagian Identitas</h3>

      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-1" style="margin-top: 0.5rem">
            <span> Nama Pasien: </span>
          </div>
          <div class="column is-5">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="Nama Pasien... "
                  v-model="input.namaPasien"
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-1" style="margin-top: 0.5rem">
            <span> No CM: </span>
          </div>
          <div class="column is-2">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="No CM Pasien"
                  v-model="input.noCMPasien"
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-1" style="margin-top: 0.5rem">
            <span> No Registrasi: </span>
          </div>
          <div class="column is-2">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="No Registrasi Pasien... "
                  v-model="input.noregistrasiPasien"
                />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-1" style="margin-top: 0.5rem">
            <span> Umur: </span>
          </div>
          <div class="column is-2">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="Umur Pasien... "
                  v-model="input.umurPasien"
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-1" style="margin-top: 0.5rem">
            <span> Tgl Lahir: </span>
          </div>
          <div class="column is-2">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="Tanggal Lahir... "
                  v-model="input.tglLahirPasien"
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-1" style="margin-top: 0.5rem">
            <span> Pekerjaan: </span>
          </div>
          <div class="column is-5">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="Pekerjaan Pasien"
                  v-model="input.pekerjaanPasien"
                />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-1" style="margin-top: 0.5rem">
            <span> Jenis Kelamin: </span>
          </div>
          <div class="column is-5">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="Jenis Kelamin Pasien... "
                  v-model="input.jkPasien"
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-1" style="margin-top: 0.5rem">
            <span> Agama: </span>
          </div>
          <div class="column is-5">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="Agama Pasien... "
                  v-model="input.agamaPasien"
                />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-1" style="margin-top: 0.5rem">
            <span> Nama Ayah: </span>
          </div>
          <div class="column is-5">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="Nama Ayah Pasien... "
                  v-model="input.namaAyahPasien"
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-1" style="margin-top: 0.5rem">
            <span> Pendidikan: </span>
          </div>
          <div class="column is-5">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="Pendidikan Pasien... "
                  v-model="input.pendidikanPasien"
                />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-1" style="margin-top: 0.5rem">
            <span> Nama Ibu: </span>
          </div>
          <div class="column is-5">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="Nama Ibu Pasien... "
                  v-model="input.namaIbuPasien"
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Cara Masuk RS: </span>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.caraMasukPasien"
                  label="Darurat"
                  class="p-0"
                  color="primary"
                  true-value="Darurat"
                  square
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.caraMasukPasien"
                  label="Biasa"
                  class="p-0"
                  color="primary"
                  true-value="Biasa"
                  square
                />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Nama Istri/Suami: </span>
          </div>
          <div class="column is-4">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="Nama Istri/Suami Pasien... "
                  v-model="input.namaIstriSuamiPasien"
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Kasus Polisi: </span>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.kasusPolisi"
                  label="Ya"
                  class="p-0"
                  color="primary"
                  true-value="Ya"
                  square
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.kasusPolisi"
                  label="Tidak"
                  class="p-0"
                  color="primary"
                  true-value="Tidak"
                  square
                />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Status Perkawinan: </span>
          </div>
          <div class="column is-4">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="Status Perkawinan Pasien... "
                  v-model="input.statusPerkawinanPasien"
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Peserta PHBS/Askes: </span>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.pesertaPHBS"
                  label="Ya"
                  class="p-0"
                  color="primary"
                  true-value="Ya"
                  square
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.pesertaPHBS"
                  label="Tidak"
                  class="p-0"
                  color="primary"
                  true-value="Tidak"
                  square
                />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Alamat: </span>
          </div>
          <div class="column is-4">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="Alamat Pasien... "
                  v-model="input.alamatPasien"
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Dirujuk oleh: </span>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.dirujukOleh"
                  label="Rumah Sakit"
                  class="p-0"
                  color="primary"
                  true-value="Rumah Sakit"
                  square
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.dirujukOleh"
                  label="Puskesmas"
                  class="p-0"
                  color="primary"
                  true-value="Puskesmas"
                  square
                />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Kampung: </span>
          </div>
          <div class="column is-4">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="Kampung Pasien... "
                  v-model="input.kampungPasien"
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.dirujukOleh"
                  label="Dokter"
                  class="p-0"
                  color="primary"
                  true-value="Dokter"
                  square
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.dirujukOleh"
                  label="Paramedis"
                  class="p-0"
                  color="primary"
                  true-value="Paramedis"
                  square
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.dirujukOleh"
                  label="Kemauan Sendiri"
                  class="p-0"
                  color="primary"
                  true-value="Kemauan Sendiri"
                  square
                />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Desa: </span>
          </div>
          <div class="column is-4">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="Desa Pasien... "
                  v-model="input.desaPasien"
                />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Kota: </span>
          </div>
          <div class="column is-4">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="Kota Pasien... "
                  v-model="input.kotaPasien"
                />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Nama Penanggung Jawab Pasien: </span>
          </div>
          <div class="column is-4">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="Nama Penanggung Jawab Pasien... "
                  v-model="input.penanggungjawabPasien"
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Tanggal Masuk: </span>
          </div>
          <div class="column is-4">
            <VField>
                  <VDatePicker v-model="input.tglMasukPasien" mode="dateTime" style="width: 100%" trim-weeks>
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
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Alamat: </span>
          </div>
          <div class="column is-4">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="Alamat Penanggung Jawab Pasien... "
                  v-model="input.alamatPenanggungJawabPasien"
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Tanggal Keluar: </span>
          </div>
          <div class="column is-4">
            <VField>
                  <VDatePicker v-model="input.tglKeluarPasien" mode="dateTime" style="width: 100%" trim-weeks>
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
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Desa: </span>
          </div>
          <div class="column is-4">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="Desa Penanggung Jawab Pasien... "
                  v-model="input.desaPenanggungJawabPasien"
                />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Kecamatan: </span>
          </div>
          <div class="column is-4">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="Kecamatan Penanggung Jawab Pasien... "
                  v-model="input.kecamatanPenanggungJawabPasien"
                />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Kota: </span>
          </div>
          <div class="column is-4">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="Kota Penanggung Jawab Pasien... "
                  v-model="input.kotaPenanggungJawabPasien"
                />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Ruangan: </span>
          </div>
          <div class="column is-4">
            <VField>
              <AutoComplete
                v-model="input.ruanganRawat"
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
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Tanggal Meninggal: </span>
          </div>
          <div class="column is-4">
            <VField>
                  <VDatePicker v-model="input.tglMeninggalPasien" mode="dateTime" style="width: 100%" trim-weeks>
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
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Kelas: </span>
          </div>
          <div class="column is-4">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="Kelas Pasien... "
                  v-model="input.kelasPasien"
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Lama Rawat: </span>
          </div>
          <div class="column is-4">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="Lama Rawat Pasien... "
                  v-model="input.lamaRawatPasien"
                />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> UPP: </span>
          </div>
          <div class="column is-4">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="UPP Pasien... "
                  v-model="input.uppPasien"
                />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Nama Kepala Ruangan: </span>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <AutoComplete
                  v-model="input.kepalaRuangan"
                  :suggestions="d_Pegawai"
                  @complete="fetchPegawai($event)"
                  :optionLabel="'label'"
                  :dropdown="true"
                  :minLength="3"
                  :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'"
                  :field="'label'"
                  placeholder="Kepala Ruangan"
                />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12 p-0">
        <h1 class="my-3">Diagnosa Masuk</h1>
        <table width="100%" style="border: 1px solid">
          <thead>
            <tr class="tr-fkprj">
              <th
                class="td-fkprj"
                width="2%"
                style="vertical-align: inherit; text-align: center"
              >
                NO
              </th>
              <th
                class="td-fkprj"
                width="50%"
                style="vertical-align: inherit; text-align: center"
              >
                Diangosa ICD 10
              </th>
              <th
                class="td-fkprj"
                rowspan="2"
                style="vertical-align: inherit; text-align: center"
                width="7%"
              >
                Aksi
              </th>
            </tr>
          </thead>
          <tbody v-for="(items, index) in input.diagnosaICD10Masuk" :key="index">
            <tr class="tr-fkprj">
              <td class="td-fkprj" style="vertical-align: inherit; text-align: center">
                {{ items.no }}
              </td>
              <td class="td-fkprj">
                <div class="columns p-1 is-12">
                  <VField class="column is-12">
                    <VControl class="prime-auto">
                      <AutoComplete
                        v-model="items.ICD10DiagnosaMasuk"
                        :suggestions="d_Diagnosa"
                        @complete="fetchDiagnosa($event)"
                        :optionLabel="'label'"
                        :dropdown="true"
                        :minLength="3"
                        :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'"
                        :field="'label'"
                        placeholder="Diagnosa ICD 10 ..."
                      />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td class="td-fkprj" style="vertical-align: inherit">
                <div class="column p-0">
                  <VButtons style="justify-content: space-around">
                    <VIconButton
                      type="button"
                      raised
                      circle
                      icon="feather:plus"
                      @click="addNewDiagnosaicd10masuk(items)"
                      color="info"
                    >
                    </VIconButton>
                    <VIconButton
                      class="mt-1"
                      v-if="index > 0"
                      type="button"
                      raised
                      circle
                      icon="feather:trash"
                      @click="removeItemDiagnosaIcd10masuk(index)"
                      color="danger"
                    >
                    </VIconButton>
                  </VButtons>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="column is-12 p-0">
        <h1 class="my-3">Diagnosa Akhir</h1>
        <table width="100%" style="border: 1px solid">
          <thead>
            <tr class="tr-fkprj">
              <th
                class="td-fkprj"
                width="2%"
                style="vertical-align: inherit; text-align: center"
              >
                NO
              </th>
              <th
                class="td-fkprj"
                width="50%"
                style="vertical-align: inherit; text-align: center"
              >
                Diangosa ICD 10
              </th>
              <th
                class="td-fkprj"
                rowspan="2"
                style="vertical-align: inherit; text-align: center"
                width="7%"
              >
                Aksi
              </th>
            </tr>
          </thead>
          <tbody v-for="(items, index) in input.diagnosaICD10Akhir" :key="index">
            <tr class="tr-fkprj">
              <td class="td-fkprj" style="vertical-align: inherit; text-align: center">
                {{ items.no }}
              </td>
              <td class="td-fkprj">
                <div class="columns p-1 is-12">
                  <VField class="column is-12">
                    <VControl class="prime-auto">
                      <AutoComplete
                        v-model="items.ICD10DiagnosaAkhir"
                        :suggestions="d_Diagnosa"
                        @complete="fetchDiagnosa($event)"
                        :optionLabel="'label'"
                        :dropdown="true"
                        :minLength="3"
                        :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'"
                        :field="'label'"
                        placeholder="Diagnosa ICD 10 ..."
                      />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td class="td-fkprj" style="vertical-align: inherit">
                <div class="column p-0">
                  <VButtons style="justify-content: space-around">
                    <VIconButton
                      type="button"
                      raised
                      circle
                      icon="feather:plus"
                      @click="addNewDiagnosaicd10akhir(items)"
                      color="info"
                    >
                    </VIconButton>
                    <VIconButton
                      class="mt-1"
                      v-if="index > 0"
                      type="button"
                      raised
                      circle
                      icon="feather:trash"
                      @click="removeItemDiagnosaIcd10akhir(index)"
                      color="danger"
                    >
                    </VIconButton>
                  </VButtons>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="column is-12 p-0">
        <h1 class="my-3">Nama Operasi Biopsi</h1>
        <table width="100%" style="border: 1px solid">
          <thead>
            <tr class="tr-fkprj">
              <th
                class="td-fkprj"
                width="2%"
                style="vertical-align: inherit; text-align: center"
              >
                NO
              </th>
              <th
                class="td-fkprj"
                width="50%"
                style="vertical-align: inherit; text-align: center"
              >
                Nama Operasi
              </th>
              <th
                class="td-fkprj"
                rowspan="2"
                style="vertical-align: inherit; text-align: center"
                width="7%"
              >
                Aksi
              </th>
            </tr>
          </thead>
          <tbody v-for="(items, index) in input.namaBiopsi" :key="index">
            <tr class="tr-fkprj">
              <td class="td-fkprj" style="vertical-align: inherit; text-align: center">
                {{ items.no }}
              </td>
              <td class="td-fkprj">
                <div class="columns p-1 is-12">
                  <VField class="column is-12">
                    <VControl class="prime-auto">
                      <AutoComplete
                        v-model="items.namaTindakanBiopsi"
                        :suggestions="d_Tindakan"
                        @complete="fetchTindakan($event)"
                        :optionLabel="'label'"
                        :dropdown="true"
                        :minLength="3"
                        :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'"
                        :field="'label'"
                        placeholder="Nama Tindakan"
                      />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td class="td-fkprj" style="vertical-align: inherit">
                <div class="column p-0">
                  <VButtons style="justify-content: space-around">
                    <VIconButton
                      type="button"
                      raised
                      circle
                      icon="feather:plus"
                      @click="addNewBiopsi(items)"
                      color="info"
                    >
                    </VIconButton>
                    <VIconButton
                      class="mt-1"
                      v-if="index > 0"
                      type="button"
                      raised
                      circle
                      icon="feather:trash"
                      @click="removeItemBiopsi(index)"
                      color="danger"
                    >
                    </VIconButton>
                  </VButtons>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Keadaan Keluar: </span>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.keadaanKeluar"
                  label="Sembuh"
                  class="p-0"
                  color="primary"
                  true-value="Sembuh"
                  square
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.keadaanKeluar"
                  label="Berobat Jalan"
                  class="p-0"
                  color="primary"
                  true-value="Berobat Jalan"
                  square
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-1 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.keadaanKeluar"
                  label="Cacat"
                  class="p-0"
                  color="primary"
                  true-value="Cacat"
                  square
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.keadaanKeluar"
                  label="Meninggal <48 Jam"
                  class="p-0"
                  color="primary"
                  true-value="Meninggal <48 Jam"
                  square
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.keadaanKeluar"
                  label="Meninggal >48 Jam"
                  class="p-0"
                  color="primary"
                  true-value="Meninggal >48 Jam"
                  square
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.keadaanKeluar"
                  label="Lain-lain"
                  class="p-0"
                  color="primary"
                  true-value="Lain-lain"
                  square
                />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Cara Keluar: </span>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.caraKeluar"
                  label="Izin Dokter"
                  class="p-0"
                  color="primary"
                  true-value="Izin Dokter"
                  square
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.caraKeluar"
                  label="Permintaan Sendiri"
                  class="p-0"
                  color="primary"
                  true-value="Permintaan Sendiri"
                  square
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-3 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.caraKeluar"
                  label="Dirujuk ke RS Lain"
                  class="p-0"
                  color="primary"
                  true-value="Dirujuk ke RS Lain"
                  square
                />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> Catatan Khusus: </span>
          </div>
          <div class="column is-1 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.catatanKhusus"
                  label="Alergi"
                  class="p-0"
                  color="primary"
                  true-value="Alergi"
                  square
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.catatanKhusus"
                  label="Transfusi"
                  class="p-0"
                  color="primary"
                  true-value="Transfusi"
                  square
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.catatanKhusus"
                  label="Gol Darah"
                  class="p-0"
                  color="primary"
                  true-value="Gol Darah"
                  square
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.catatanKhusus"
                  label="Penyakit Berat"
                  class="p-0"
                  color="primary"
                  true-value="Penyakit Berat"
                  square
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.catatanKhusus"
                  label="Penyakit Menular"
                  class="p-0"
                  color="primary"
                  true-value="Penyakit Menular"
                  square
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mb-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.catatanKhusus"
                  label="Lain-lain"
                  class="p-0"
                  color="primary"
                  true-value="Lain-lain"
                  square
                />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top: 0.5rem">
            <span> DPJP: </span>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <AutoComplete
                  v-model="input.dpjpPasien"
                  :suggestions="d_Dokter"
                  @complete="fetchDokter($event)"
                  :optionLabel="'label'"
                  :dropdown="true"
                  :minLength="3"
                  :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'"
                  :field="'label'"
                  placeholder="Dokter DPJP"
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
// import Fieldset from 'primevue/fieldset';
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/laporan-operasi'

const d_Ruangan: any = ref([])
const d_Dokter: any = ref([])
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let laporan = ref(EMR.laporan())

const props = withDefaults(
  defineProps<{
    pasien?: any
    alamat?: any
    registrasi?: any
    FORM_NAME?: string
    FORM_URL?: string
    COLLECTION?: string
  }>(),
  {
    pasien: {},
    alamat: {},
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
const d_Pegawai: any = ref([])
const d_Diagnosa: any = ref([])
const d_Tindakan: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
})
const COLLECTION: any = ref('lembarKeluarMasuk') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const alamat = props.alamat[0]

const input: any = ref({
  diagnosaICD10Masuk: [{ no: 1 }],
  diagnosaICD10Akhir: [{ no: 1 }],
  namaBiopsi: [{ no: 1 }],
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
const fetchPegawai = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  )
  d_Pegawai.value = response
}

const fetchTindakan = (filter: any) => {
  let nama = filter ? `?filterTindakan=${filter.query}` : ''
  useApi().get(`dashboard/data-tindakan${nama}`).then((response) => {
    d_Tindakan.value = response.map(element => {
      return ({value: element.id, label: element.nama})
    })
  })
}

const addNewDiagnosaicd10masuk = (item) => {
  input.value.diagnosaICD10Masuk.push({
    no: input.value.diagnosaICD10Masuk[input.value.diagnosaICD10Masuk?.length - 1].no + 1,
  });
}
const removeItemDiagnosaIcd10masuk = (index: any) => {
  input.value.diagnosaICD10Masuk.splice(index, 1)
}
const addNewDiagnosaicd10akhir = (item) => {
  input.value.diagnosaICD10Akhir.push({
    no: input.value.diagnosaICD10Akhir[input.value.diagnosaICD10Akhir?.length - 1].no + 1,
  });
}
const removeItemDiagnosaIcd10akhir = (index: any) => {
  input.value.diagnosaICD10Akhir.splice(index, 1)
}
const addNewBiopsi = (item) => {
  input.value.namaBiopsi.push({
    no: input.value.namaBiopsi[input.value.namaBiopsi?.length - 1].no + 1,
  });
}
const removeItemBiopsi = (index: any) => {
  input.value.namaBiopsi.splice(index, 1)
}

const fetchDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/diagnosa/diagnosa-x-paging?name=${filter.query}&limit=10`)

  d_Diagnosa.value = response.diagnosa.map((item: any) => {
    return { value: item.id, label: item.kddiagnosa + ' - ' + item.namadiagnosa }
  })
}
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  )
  d_Ruangan.value = response
}
const loadRiwayat = () => {
  // if (NOREC_EMRPASIEN.value == '') return
  useApi()
    .get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    )
    .then((response: any) => {
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

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  console.log(alamat.namadesakelurahan)
  input.value.namaPasien = props.pasien.namapasien
  input.value.umurPasien = props.pasien.umur
  input.value.namaAyahPasien = props.pasien.namaayah
  input.value.penanggungjawabPasien = props.pasien.penanggungjawab
  input.value.namaAyahPasien = props.pasien.namaayah
  input.value.namaIstriSuamiPasien = props.pasien.namasuamiistri
  input.value.statusPerkawinanPasien = props.pasien.statusperkawinan
  input.value.namaIbuPasien = props.pasien.namaibu
  input.value.noCMPasien = props.pasien.nocm
  input.value.agamaPasien = props.pasien.agama
  input.value.tglLahirPasien = props.pasien.tgllahir
  input.value.pekerjaanPasien = props.pasien.pekerjaan
  input.value.pendidikanPasien = props.pasien.pendidikan
  input.value.jkPasien = props.pasien.jeniskelamin
  input.value.noregistrasiPasien = props.registrasi.noregistrasi
  input.value.alamatPasien = props.pasien.alamatlengkap
  input.value.tglMasukPasien = props.registrasi.tglregistrasi
  input.value.desaPasien = alamat.namadesakelurahan
  input.value.kotaPasien = alamat.namakotakabupaten

  await useApi().get(
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=pengkajianDokterGawatDarurat" + "&field=diagnosaICD10"
  ).then((response) => {
    if(response && response.diagnosaICD10?.length > 0){
      input.value.diagnosaICD10Masuk = response.diagnosaICD10
    }else{
      input.value.diagnosaICD10Masuk = [{no: 1}]
    }
  })
  useApi()
    .get('diagnosa/riwayat-diagnosa-x-cppt?noregistrasi=' + props.registrasi.noregistrasi)
    .then((response) => {
      if (response != null) {
        let analysis = ''
        let count = 1
        response.data.forEach((element) => {
          analysis += `${count}. ${element.kddiagnosa}, nama diagnosa = ${element.namadiagnosa}, jenis diagnosa = ${element.jenisdiagnosa}, keterangan = ${element.keterangan} \n`
          count++
        })
        analysis = analysis.slice(0, -2)
        input.value.A = ref(analysis)
      }
    })
}
setView()
setAutoFill()
loadRiwayat()
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
  padding: 10px 5px;
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

@media (max-width: 1144px) {
  .table-tg {
    width: 150%;
  }
}
</style>
