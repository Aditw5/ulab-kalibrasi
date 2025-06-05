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
        <Fieldset class="p-fieldsets" legend="DATA IDENTITAS" :toggleable="true">
          <VCard class="p-3">          
            <div class="column is-12 px-2">
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VField>
                    <h1 style="font-weight: bold; margin-bottom: 0.4rem">Tanggal Masuk</h1>
                    <VDatePicker
                      v-model="input.tanggalMasuk"
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
                <div class="column is-6">
                  <VField>
                    <h1 style="font-weight: bold; margin-bottom: 0.4rem">Diagnosa Medis</h1>
                    <AutoComplete
                      v-model="input.namadiagnosa"
                      :suggestions="d_Diagnosa"
                      @complete="fetchDiagnosa($event)"
                      :optionLabel="'label'"
                      :dropdown="true"
                      :minLength="3"
                      :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'"
                      :field="'label'"
                      placeholder="Ketik untuk mencari"
                    />
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">Tujuan Tindakan</h1>
              <div class="columns is-multiline">
                <div style="margin-top: 0.5rem" class="column is-2" v-for="(data, i) in tujuanTindakan">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.tujuanTindakan"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4" v-if="input.tujuanTindakan == 'LainLain'">
                  <VField>
                    <VControl label="Lain-lain">
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Lain-lain"
                        v-model="input.tujuanTindakanLain"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">Status Fungsional</h1>
              <div class="columns is-multiline">
                <div style="margin-top: 0.5rem" class="column is-2" v-for="(data, i) in statusFungsionalCathlab">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.statusFungsionalCathlab"
                        :true-value="data.value"
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
          </VCard>
        </Fieldset>
      </div>

      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="ASESMEN PRA TINDAKAN" :toggleable="true">
          <VCard class="p-3">          
            <div class="column is-12 px-2">
              <div class="columns is-multiline">
                <div class="column is-12">
                  <VField>
                    <h1 style="font-weight: bold; margin-bottom: 0.4rem">Keluhan Utama</h1>
                    <VTextarea rows="2" placeholder="Keluhan Utama" v-model="input.keluhanUtama"></VTextarea>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">Status Psikologis</h1>
              <div class="columns is-multiline">
                <div style="margin-top: 0.5rem" class="column is-2" v-for="(data, i) in statusPsikologis">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.statusPsikologis"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4" v-if="input.statusPsikologis == 'LainLain'">
                  <VField>
                    <VControl label="Lain-Lain">
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Lain-lain, sebutkan "
                        v-model="input.statusPsikologisLain"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">Riwayat Penyakit Dahulu</h1>
              <div class="columns is-multiline">
                <div style="margin-top: 0.5rem" class="column is-2" v-for="(data, i) in riwayatPenyakitDahuluCathlab">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.riwayatPenyakitDahuluCathlab"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4" v-if="input.riwayatPenyakitDahuluCathlab == 'LainLain'">
                  <VField>
                    <VControl label="Lain-Lain">
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Lain-lain"
                        v-model="input.riwayatPenyakitDahuluCathlabLain"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>              
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">Sistem Pernafasan</h1>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <h1 style="margin-bottom: -1rem">Warna Sputum</h1>
                </div>
                <div class="column is-6">
                  <h1 style="margin-bottom: -1rem">Konsistensi Sputum</h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl label="Lain-Lain">
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Warna Sputum"
                        v-model="input.warnaSputum"
                      />
                    </VControl>
                  </VField>
                </div>
                <div style="margin-top: 0.5rem" class="column is-2" v-for="(data, i) in konsistensiSputum">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.konsistensiSputum"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">Sistem Pencernaan</h1>
              <div class="columns is-multiline">
                <div class="column is-1">
                  <h1 style="margin-bottom: -1rem">Muntah Darah</h1>
                </div>
                <div class="column is-1" v-for="(data, i) in muntahDarah">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.muntahDarah"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline" v-if="input.muntahDarah == 'Ya'">
                <div class="column is-1">
                  <h1 style="margin-bottom: -1rem">Muntah Darah</h1>
                </div>
                <div class="column" v-for="(data, i) in muntahDarahYa" :key="i" :class="data.column">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.muntahDarahYa"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">Sistem Perkemihan</h1>
              <div class="columns is-multiline">
                <div class="column is-1">
                  <h1 style="margin-top: 0.5rem">Urin / 24 Jam</h1>
                </div>
                <div class="column is-3">
                  <VField addons>
                    <VControl expanded>
                      <VInput placeholder="Urin / 24 Jam" class="input"
                        v-model="input.urin24Jam"></VInput>
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>cc</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">Riwayat Pengobatan</h1>
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h1 style="margin-top: 0.5rem">Double antiplatelet</h1>
                </div>
                <div style="margin-top: 0.5rem" class="column" v-for="(data, i) in doubleAntiplatelet" :key="i" :class="data.column">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.doubleAntiplatelet"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="input.doubleAntiplatelet == 'Tidak'">
                  <VField>
                    <VControl label="Lain-Lain">
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Lama Penggunaan"
                        v-model="input.doubleAntiplateletLamaPenggunaan"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h1 style="margin-top: 0.5rem">Beta bloker</h1>
                </div>
                <div style="margin-top: 0.5rem" class="column" v-for="(data, i) in betaBloker" :key="i" :class="data.column">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.betaBloker"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="input.betaBloker == 'Tidak'">
                  <VField>
                    <VControl label="Lain-Lain">
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Lama Penggunaan"
                        v-model="input.betaBlokerLamaPenggunaan"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h1 style="margin-top: 0.5rem">Simarc</h1>
                </div>
                <div style="margin-top: 0.5rem" class="column" v-for="(data, i) in simarc" :key="i" :class="data.column">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.simarc"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="input.simarc == 'Tidak'">
                  <VField>
                    <VControl label="Lain-Lain">
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Lama Penggunaan"
                        v-model="input.simarcLamaPenggunaan"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">Riwayat Alergi</h1>
              <div class="columns is-multiline">
                <div style="margin-top: 0.5rem" class="column is-1" v-for="(data, i) in riwayatAlergi">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.riwayatAlergi"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="input.riwayatAlergi == 'Ya'">
                  <VField>
                    <VControl label="Lain-Lain">
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Sebutkan"
                        v-model="input.riwayatAlergiSebutkan"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">Tanda-tanda Vital</h1>
              <div class="columns is-multiline">
                <div class="column is-1">
                  <h1 style="margin-top: 0.5rem">Tekanan Darah</h1>
                </div>
                <div class="column is-3">
                  <VField addons>
                    <VControl expanded>
                      <VInput placeholder="Tekanan Darah" class="input"
                        v-model="input.tekananDarah"></VInput>
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>mmHg</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1 has-text-right">
                  <h1 style="margin-top: 0.5rem">Nadi</h1>
                </div>
                <div class="column is-3">
                  <VField addons>
                    <VControl expanded>
                      <VInput placeholder="Nadi" class="input"
                        v-model="input.nadi"></VInput>
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>x/mnt</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1 has-text-right">
                  <h1 style="margin-top: 0.5rem">Saturasi</h1>
                </div>
                <div class="column is-3">
                  <VField addons>
                    <VControl expanded>
                      <VInput placeholder="Saturasi" class="input"
                        v-model="input.saturasi"></VInput>
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>%</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 style="margin-top: 0.5rem">Pernapasan</h1>
                </div>
                <div class="column is-3">
                  <VField addons>
                    <VControl expanded>
                      <VInput placeholder="Pernapasan" class="input"
                        v-model="input.pernapasan"></VInput>
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>x/mnt</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1 has-text-right">
                  <h1 style="margin-top: 0.5rem">Suhu</h1>
                </div>
                <div class="column is-3">
                  <VField addons>
                    <VControl expanded>
                      <VInput placeholder="Suhu" class="input"
                        v-model="input.suhu"></VInput>
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>°C</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">Tes Allen Kanan/Kiri</h1>
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h1 style="margin-bottom: -1rem">Radialis kanan, kondisi</h1>
                </div>
                <div class="column" v-for="(data, i) in radialisKanan" :key="i" :class="data.column">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.radialisKanan"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2 has-text-right">
                  <h1 style="margin-bottom: -1rem">Radialis kiri, kondisi</h1>
                </div>
                <div class="column" v-for="(data, i) in radialisKiri" :key="i" :class="data.column">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.radialisKiri"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">Arteri Dorsalis Pedis</h1>
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h1 style="margin-bottom: -1rem">Pedis kanan, kondisi</h1>
                </div>
                <div class="column" v-for="(data, i) in pedisKanan" :key="i" :class="data.column">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.pedisKanan"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2 has-text-right">
                  <h1 style="margin-bottom: -1rem">Pedis kiri, kondisi</h1>
                </div>
                <div class="column" v-for="(data, i) in pedisKiri" :key="i" :class="data.column">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.pedisKiri"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-1">
                  <h1 style="font-weight: bold; margin-top: 0.5rem">Berat Badan</h1>
                </div>
                <div class="column is-3">
                  <VField addons>
                    <VControl expanded>
                      <VInput placeholder="Berat Badan" class="input"
                        v-model="input.beratBadan"></VInput>
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>Kg</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1 has-text-right">
                  <h1 style="font-weight: bold; margin-top: 0.5rem">Tinggi Badan</h1>
                </div>
                <div class="column is-3">
                  <VField addons>
                    <VControl expanded>
                      <VInput placeholder="Tinggi Badan" class="input"
                        v-model="input.tinggiBadan"></VInput>
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>Cm</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">Keluhan Nyeri</h1>
              <div class="columns is-multiline">
                <div style="margin-top: 0.5rem" class="column is-1" v-for="(data, i) in keluhanNyeri">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.keluhanNyeri"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="input.keluhanNyeri == 'Ya'">
                  <VField>
                    <VControl label="Lokasi">
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Lokasi"
                        v-model="input.keluhanNyeriLokasi"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h1 style="margin-top: 0.5rem">Pencetus Nyeri (P)</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Pencetus Nyeri (P)"
                        v-model="input.pencetusNyeri"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2 has-text-right">
                  <h1 style="margin-top: 0.5rem">Skala (S)</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Skala (S)"
                        v-model="input.skala"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 style="margin-top: 0.5rem">Kualitas (Q)</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Kualitas (Q)"
                        v-model="input.kualitas"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2 has-text-right">
                  <h1 style="margin-top: 0.5rem">Lamanya (T)</h1>
                </div>
                <div class="column is-4">
                  <VField addons>
                    <VControl expanded>
                      <VInput placeholder="Lamanya (T)" class="input"
                        v-model="input.lamanya"></VInput>
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>menit</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h1 style="margin-top: 0.5rem">Penjalaran (R)</h1>
                </div>
                <div style="margin-top: 0.5rem" class="column is-1" v-for="(data, i) in penjalaranNyeri">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.penjalaranNyeri"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="input.penjalaranNyeri == 'Ya'">
                  <VField>
                    <VControl label="Ke">
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Ke"
                        v-model="input.penjalaranNyeriKe"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">Kebutuhan Edukasi</h1>
              <div class="columns is-multiline">
                <div style="margin-top: 0.5rem" class="column" v-for="(data, i) in kebutuhanEdukasi" :key="i" :class="data.column">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.kebutuhanEdukasi"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4" v-if="input.kebutuhanEdukasi == 'LainLain'">
                  <VField>
                    <VControl label="Lain-Lain">
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Lain-lain"
                        v-model="input.kebutuhanEdukasiLain"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">Laboratorium</h1>
              <div class="columns is-multiline">
                <div class="column is-1">
                  <h1 style="margin-top: 0.5rem">Hb</h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Hb"
                        v-model="input.Hb"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1 has-text-right">
                  <h1 style="margin-top: 0.5rem">Ur</h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Ur"
                        v-model="input.Ur"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1 has-text-right">
                  <h1 style="margin-top: 0.5rem">Anti HCV</h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Anti HCV"
                        v-model="input.antiHCV"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1 has-text-right">
                  <h1 style="margin-top: 0.5rem">Ht</h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Ht"
                        v-model="input.Ht"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 style="margin-top: 0.5rem">Cr</h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Cr"
                        v-model="input.Cr"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1 has-text-right">
                  <h1 style="margin-top: 0.5rem">PT/INR</h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="PT/INR"
                        v-model="input.ptINR"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1 has-text-right">
                  <h1 style="margin-top: 0.5rem">Leukosit</h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Leukosit"
                        v-model="input.leukosit"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1 has-text-right">
                  <h1 style="margin-top: 0.5rem">HbSAg</h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="HbSAg"
                        v-model="input.hbSAg"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 style="margin-top: 0.5rem">PT/APTT</h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="PT/APTT"
                        v-model="input.ptAPTT"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1 has-text-right">
                  <h1 style="margin-top: 0.5rem">Na</h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Na"
                        v-model="input.Na"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1 has-text-right">
                  <h1 style="margin-top: 0.5rem">K</h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="K"
                        v-model="input.K"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1 has-text-right">
                  <h1 style="margin-top: 0.5rem">GDS</h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="GDS"
                        v-model="input.GDS"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">Skrining Jatuh</h1>
              <div class="columns is-multiline">
                <div class="column is-1">
                  <h1 style="margin-top: 0.5rem">Skor</h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Skor"
                        v-model="input.skorJatuh"
                      />
                    </VControl>
                  </VField>
                </div>
                <div style="margin-top: 0.5rem" class="column is-2" v-for="(data, i) in risikoJatuh">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.risikoJatuh"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">Hasil Echo</h1>
              <div class="columns is-multiline">
                <div style="margin-top: 0.5rem" class="column is-1" v-for="(data, i) in hasilEcho">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.hasilEcho"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="input.hasilEcho == 'Ada'">
                  <VField>
                    <VControl label="Kesan">
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Kesan"
                        v-model="input.hasilEchoKesan"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">MASALAH KEPERAWATAN</h1>
              <div class="columns is-multiline">
                <div style="margin-top: 0.5rem" class="column" v-for="(data, i) in masalahKeperawatan" :key="i" :class="data.column">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.masalahKeperawatan"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4" v-if="input.masalahKeperawatan == 'LainLain'">
                  <VField>
                    <VControl label="......">
                      <VInput
                        type="text"
                        class="input"
                        placeholder="......"
                        v-model="input.masalahKeperawatanLain"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">RENCANA TINDAKAN KEPERAWATAN (MANDIRI)</h1>
              <div class="columns is-multiline">
                <div style="margin-top: 0.5rem" class="column" v-for="(data, i) in rencanaTindakanKeperawatan" :key="i" :class="data.column">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.rencanaTindakanKeperawatan"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4" v-if="input.rencanaTindakanKeperawatan == 'LainLain'">
                  <VField>
                    <VControl label="......">
                      <VInput
                        type="text"
                        class="input"
                        placeholder="......"
                        v-model="input.rencanaTindakanKeperawatanLain"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </VCard>
        </Fieldset>
      </div>      
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="OBSERVASI INTRA DAN PASKA TINDAKAN" :toggleable="true">
          <VCard class="p-3">
            <div class="column is-12 px-2" style="overflow: auto;">
            <h1 style="font-weight: bold; margin-bottom: 0.4rem">Tanda Vital</h1>
              <table class="tg">
                <thead>
                  <tr>
                    <th class="tg-0lax text-center">No</th>
                    <th class="tg-0lax text-center">Jam</th>
                    <th class="tg-0lax text-center">BP</th>
                    <th class="tg-0lax text-center">N</th>
                    <th class="tg-0lax text-center">P</th>
                    <th class="tg-0lax text-center">S</th>
                    <th class="tg-0lax text-center">Saturasi Oksigen</th>
                    <th class="tg-0lax text-center">Pulsasi Distal</th>
                    <th class="tg-0lax text-center">Reflek Menelan</th>
                    <th class="tg-0lax text-center">#</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(data, index) in input.tandaVitalObservasiIntradanPaskaTindakan" :key="index">
                    <td>{{ data.no }}</td>
                    <td>
                      <VField>
                        <VControl>
                          <VInput
                            type="time"
                            v-model="data.jamTandaVital"
                            placeholder="00:00"
                            step="60" 
                            min="00:00"
                            max="23:59"
                          />
                        </VControl>
                      </VField>
                    </td>
                    <td width="15%">
                      <VField addons>
                        <VControl expanded>
                            <VInput type="text" class="input" placeholder="BP" v-model="data.BPTandaVital1" :tabindex="3"  />
                        </VControl>
                        <VControl expanded>
                          <h1 style="font-weight: bold; font-size: 1.5rem; margin-left: 0.4rem; margin-right: 0.4rem;">/</h1>
                        </VControl>
                        <VControl expanded>
                            <VInput type="text" class="input" placeholder="BP" v-model="data.BPTandaVital2" :tabindex="3"  />
                        </VControl>
                      </VField>
                    </td>
                    <td>
                      <VField addons>
                        <VControl expanded>
                            <VInput type="text" class="input" placeholder="N" v-model="data.NTandaVital" :tabindex="3"  />
                        </VControl>
                      </VField>
                    </td>
                    <td>
                      <VField addons>
                        <VControl expanded>
                            <VInput type="text" class="input" placeholder="P" v-model="data.PTandaVital" :tabindex="3"  />
                        </VControl>
                      </VField>
                    </td>
                    <td>
                      <VField addons>
                        <VControl expanded>
                            <VInput type="text" class="input" placeholder="S" v-model="data.STandaVital" :tabindex="3"  />
                        </VControl>
                      </VField>
                    </td>
                    <td>
                      <VField addons>
                        <VControl expanded>
                            <VInput type="text" class="input" placeholder="Saturasi Oksigen" v-model="data.SOTandaVital" :tabindex="3"  />
                        </VControl>
                      </VField>
                    </td>
                    <td>
                      <VField addons>
                        <VControl expanded>
                            <VInput type="text" class="input" placeholder="Pulsasi Distal" v-model="data.PDTandaVital" :tabindex="3"  />
                        </VControl>
                      </VField>
                    </td>
                    <td>
                      <VField addons>
                        <VControl expanded>
                            <VInput type="text" class="input" placeholder="Reflek Menelan" v-model="data.RMTandaVital" :tabindex="3"  />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-fkprj" style="vertical-align: inherit;">
                      <div class="column">
                        <VIconButton type="button" raised circle icon="feather:plus"
                          @click="addNewTandaVitalObservasiIntradanPaskaTindakan()" color="info" v-tooltip.bubble="'Tambah'">
                        </VIconButton>
                        <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                          @click="removeTandaVitalObservasiIntradanPaskaTindakan(index)" color="danger">
                        </VIconButton>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="column is-12 px-2">
              <div class="columns is-multiline">
                <div class="column is-4">
                  <VField>
                    <h1 style="font-weight: bold; margin-bottom: 0.4rem">
                      Pasien Tiba di RR Jam
                    </h1>
                    <VDatePicker
                      v-model="input.jamPasienTiba"
                      color="green"
                      mode="time"
                      is24hr
                      :min-time="'00:00'"
                    >
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VControl icon="feather:clock">
                            <VInput
                              :value="inputValue || '00:00'"
                              v-on="inputEvents"
                              placeholder="00:00"
                            />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField>
                    <h1 style="font-weight: bold; margin-bottom: 0.4rem">Lokasi pungsi/sayatan</h1>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Lokasi pungsi/sayatan"
                        v-model="input.lokasiPungsiSayatan"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField>
                    <h1 style="font-weight: bold; margin-bottom: 0.4rem">Jumlah</h1>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Jumlah"
                        v-model="input.jumlahPungsiSayatan"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">Terpasang Sheath</h1>
              <div class="columns is-multiline">
                <div style="margin-top: 0.5rem" class="column is-1" v-for="(data, i) in terpasangSheath">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.terpasangSheath"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="input.terpasangSheath == 'Ya'">
                  <VField>
                    <VControl label="Lokasi">
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Lokasi"
                        v-model="input.terpasangSheathLokasi"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">Pulsasi A. Radialis</h1>
              <div class="columns is-multiline">
                <div class="column is-1">
                  <h1 style="margin-bottom: -1rem">Kanan</h1>
                </div>
                <div class="column" v-for="(data, i) in PulsasiARadialisKanan" :key="i" :class="data.column">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.PulsasiARadialisKanan"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1 has-text-right">
                  <h1 style="margin-bottom: -1rem">Kiri</h1>
                </div>
                <div class="column" v-for="(data, i) in PulsasiARadialisKiri" :key="i" :class="data.column">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.PulsasiARadialisKiri"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">Pulsasi A. Dorsalis Pedis</h1>
              <div class="columns is-multiline">
                <div class="column is-1">
                  <h1 style="margin-bottom: -1rem">Kanan</h1>
                </div>
                <div class="column" v-for="(data, i) in PulsasiADorsalisKanan" :key="i" :class="data.column">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.PulsasiADorsalisKanan"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1 has-text-right">
                  <h1 style="margin-bottom: -1rem">Kiri</h1>
                </div>
                <div class="column" v-for="(data, i) in PulsasiADorsalisKiri" :key="i" :class="data.column">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.PulsasiADorsalisKiri"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">Alat yang terpasang</h1>
              <div class="columns is-multiline">
                <div style="margin-top: 0.5rem" class="column" v-for="(data, i) in alatYangTerpasang" :key="i" :class="data.column">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.alatYangTerpasang"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4" v-if="input.alatYangTerpasang == 'LainLain'">
                  <VField>
                    <VControl label="Lain-lain">
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Lain-lain"
                        v-model="input.alatYangTerpasangLain"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <h1 style="font-weight: bold; margin-bottom: -0.5rem">Jenis Anestesi</h1>
                </div>
                <div class="column is-9">
                  <h1 style="font-weight: bold; margin-bottom: -0.5rem">Dosis Pemberian</h1>
                </div>
              </div>
              <div class="columns is-multiline">
                <div style="margin-top: 0.5rem" class="column is-1" v-for="(data, i) in jenisAnestesi">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.jenisAnestesi"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                </div>            
                <div class="column is-1">
                  <h1 style="margin-top: 0.5rem">Pre prosedur</h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Pre prosedur"
                        v-model="input.preProsedurAnestesi"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 style="margin-top: 0.5rem">Intra prosedur</h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Intra prosedur"
                        v-model="input.intraProsedurAnestesi"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 style="margin-top: 0.5rem">Post prosedur</h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Post prosedur"
                        v-model="input.postProsedurAnestesi"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <VField>
                    <h1 style="font-weight: bold; margin-bottom: 0.4rem">Sedasi</h1>
                    <VTextarea rows="1" placeholder="Sedasi" v-model="input.sedasi"></VTextarea>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <h1 style="font-weight: bold; margin-bottom: -0.5rem">Antikoagulan</h1>
                </div>
                <div class="column is-9">
                  <h1 style="font-weight: bold; margin-bottom: -0.5rem">Dosis Pemberian</h1>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Antikoagulan"
                        v-model="input.Antikoagulan"
                      />
                    </VControl>
                  </VField>
                </div>           
                <div class="column is-1">
                  <h1 style="margin-top: 0.5rem">Pre prosedur</h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Pre prosedur"
                        v-model="input.preProsedurAntikoagulan"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 style="margin-top: 0.5rem">Intra prosedur</h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Intra prosedur"
                        v-model="input.intraProsedurAntikoagulan"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 style="margin-top: 0.5rem">Post prosedur</h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Post prosedur"
                        v-model="input.postProsedurAntikoagulan"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">Hematoma</h1>
              <div class="columns is-multiline">
                <div style="margin-top: 0.5rem" class="column is-1" v-for="(data, i) in hematoma">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.hematoma"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="input.hematoma == 'Ya'">
                  <VField>
                    <VControl label="Lokasi">
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Lokasi"
                        v-model="input.hematomaLokasi"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="input.hematoma == 'Ya'">
                  <VField>
                    <VControl label="Ukuran">
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Ukuran"
                        v-model="input.hematomaUkuran"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">Laserasi</h1>
              <div class="columns is-multiline">
                <div style="margin-top: 0.5rem" class="column is-1" v-for="(data, i) in laserasi">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.laserasi"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="input.laserasi == 'Ya'">
                  <VField>
                    <VControl label="Lokasi">
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Lokasi"
                        v-model="input.laserasiLokasi"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="input.laserasi == 'Ya'">
                  <VField>
                    <VControl label="Ukuran">
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Ukuran"
                        v-model="input.laserasiUkuran"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">Reaksi Alergi</h1>
              <div class="columns is-multiline">
                <div style="margin-top: 0.5rem" class="column is-1" v-for="(data, i) in reaksiAlergi">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.reaksiAlergi"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="input.reaksiAlergi == 'Ya'">
                  <VField>
                    <VControl label="..........">
                      <VInput
                        type="text"
                        class="input"
                        placeholder="........."
                        v-model="input.reaksiAlergiYa"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">Keseimbangan Cairan (Intake)</h1>
              <div class="columns is-multiline">
                <div class="column is-1">
                  <h1 style="margin-top: 0.5rem">Minum</h1>
                </div>
                <div class="column is-2">
                  <VField addons>
                    <VControl expanded>
                      <VInput placeholder="Minum" class="input"
                        v-model="input.keseimbanganCairanMinum"></VInput>
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>ml</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1 has-text-right">
                  <h1 style="margin-top: 0.5rem">IV Line</h1>
                </div>
                <div class="column is-2">
                  <VField addons>
                    <VControl expanded>
                      <VInput placeholder="IV Line" class="input"
                        v-model="input.keseimbanganCairanIVLine"></VInput>
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>ml</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 style="margin-top: 0.5rem">Perdarahan</h1>
                </div>
                <div class="column is-2">
                  <VField addons>
                    <VControl expanded>
                      <VInput placeholder="Perdarahan" class="input"
                        v-model="input.keseimbanganCairanPerdarahan"></VInput>
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>ml</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 style="margin-top: 0.5rem">Urine</h1>
                </div>
                <div class="column is-2">
                  <VField addons>
                    <VControl expanded>
                      <VInput placeholder="Urine" class="input"
                        v-model="input.keseimbanganCairanUrine"></VInput>
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>ml</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">Instruksi Paska Tindakan</h1>
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h1 style="margin-top: 0.5rem">Immobilisasi sampai jam </h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder=".........."
                        v-model="input.jamImmobilisasi"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 style="margin-top: 0.5rem">(6-7 jam paska anestesi spinal)</h1>
                </div>
                <div class="column is-1 has-text-right">
                  <h1 style="margin-top: 0.5rem">EKG </h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="EKG"
                        v-model="input.EKG"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1 has-text-right">
                  <h1 style="margin-top: 0.5rem">Foto Rontgen </h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Foto Rontgen"
                        v-model="input.fotoRontgen"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 style="margin-top: 0.5rem">Antibiotik </h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Antibiotik"
                        v-model="input.antibiotik"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1 has-text-right">
                  <h1 style="margin-top: 0.5rem">Dosis </h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Dosis"
                        v-model="input.dosis"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1 has-text-right">
                  <h1 style="margin-top: 0.5rem">Hidrasi </h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        placeholder="Hidrasi"
                        v-model="input.hidrasi"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">MASALAH KEPERAWATAN</h1>
              <div class="columns is-multiline">
                <div style="margin-top: 0.5rem" class="column" v-for="(data, i) in masalahKeperawatanObservasi" :key="i" :class="data.column">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.masalahKeperawatanObservasi"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4" v-if="input.masalahKeperawatanObservasi == 'LainLain'">
                  <VField>
                    <VControl label="......">
                      <VInput
                        type="text"
                        class="input"
                        placeholder="......"
                        v-model="input.masalahKeperawatanObservasiLain"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">RENCANA TINDAKAN KEPERAWATAN (MANDIRI)</h1>
              <div class="columns is-multiline">
                <div style="margin-top: 0.5rem" class="column" v-for="(data, i) in rencanaTindakanKeperawatanObservasi" :key="i" :class="data.column">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.rencanaTindakanKeperawatanObservasi"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4" v-if="input.rencanaTindakanKeperawatanObservasi == 'LainLain'">
                  <VField>
                    <VControl label="......">
                      <VInput
                        type="text"
                        class="input"
                        placeholder="......"
                        v-model="input.rencanaTindakanKeperawatanObservasiLain"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold; margin-bottom: 0.4rem">Keputusan Rawat</h1>
              <div class="columns is-multiline">
                <div style="margin-top: 0.5rem" class="column is-1" v-for="(data, i) in keputusanRawat">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox
                        v-model="input.keputusanRawat"
                        :true-value="data.value"
                        :label="data.label" 
                        class="p-0"
                        color="primary"
                        square
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="input.keputusanRawat == 'Dirawat'">
                  <VField>
                    <VControl label="..........">
                      <VInput
                        type="text"
                        class="input"
                        placeholder="........."
                        v-model="input.keputusanRawatTempat"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </VCard>
        </Fieldset>
      </div>
      
      <div class="column is-4" style="margin-left: auto">
        <VCard>
          <h1 style="font-weight: bold">Keputusan rawat:</h1>
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
          </div>
          <div>
            <h1 class="p-0" style="font-weight: bold;">Yang Mengobservasi</h1>
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
import * as EMR from '../page-emr-plugins/pengkajian-awal-pi'
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
let vitalSign = ref(EMR.vitalSign())
let vitalSignBayi = ref(EMR.vitalSignBayi())
let caraMasukRS = ref(EMR.caraMasukRS())
let tibaRS = ref(EMR.tibaRS())
let macamTrauma = ref(EMR.macamTrauma())
let tujuanTindakan = ref(EMR.tujuanTindakan())
let statusFungsionalCathlab = ref(EMR.statusFungsionalCathlab())
let statusPsikologis = ref(EMR.statusPsikologis())
let riwayatPenyakitDahuluCathlab = ref(EMR.riwayatPenyakitDahuluCathlab())
let konsistensiSputum = ref(EMR.konsistensiSputum())
let muntahDarah = ref(EMR.muntahDarah())
let muntahDarahYa = ref(EMR.muntahDarahYa())
let doubleAntiplatelet = ref(EMR.doubleAntiplatelet())
let betaBloker = ref(EMR.betaBloker())
let simarc = ref(EMR.simarc())
let riwayatAlergi = ref(EMR.riwayatAlergi())
let radialisKanan = ref(EMR.radialisKanan())
let radialisKiri = ref(EMR.radialisKiri())
let pedisKanan = ref(EMR.pedisKanan())
let pedisKiri = ref(EMR.pedisKiri())
let keluhanNyeri = ref(EMR.keluhanNyeri())
let penjalaranNyeri = ref(EMR.penjalaranNyeri())
let kebutuhanEdukasi = ref(EMR.kebutuhanEdukasi())
let risikoJatuh = ref(EMR.risikoJatuh())
let hasilEcho = ref(EMR.hasilEcho())
let masalahKeperawatan = ref(EMR.masalahKeperawatan())
let rencanaTindakanKeperawatan = ref(EMR.rencanaTindakanKeperawatan())
let terpasangSheath = ref(EMR.terpasangSheath())
let PulsasiARadialisKanan = ref(EMR.PulsasiARadialisKanan())
let PulsasiARadialisKiri = ref(EMR.PulsasiARadialisKiri())
let PulsasiADorsalisKanan = ref(EMR.PulsasiADorsalisKanan())
let PulsasiADorsalisKiri = ref(EMR.PulsasiADorsalisKiri())
let alatYangTerpasang = ref(EMR.alatYangTerpasang())
let jenisAnestesi = ref(EMR.jenisAnestesi())
let hematoma = ref(EMR.hematoma())
let laserasi = ref(EMR.laserasi())
let reaksiAlergi = ref(EMR.reaksiAlergi())
let keputusanRawat = ref(EMR.keputusanRawat())
let masalahKeperawatanObservasi = ref(EMR.masalahKeperawatanObservasi())
let rencanaTindakanKeperawatanObservasi = ref(EMR.rencanaTindakanKeperawatanObservasi())



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
let pernafasan = ref(EMR.pernafasan())
let nafsuMakan = ref(EMR.nafsuMakan())
let parameter = ref(EMR.parameter())
let polaMakanAnak = ref(EMR.polaMakanAnak())
let makananYangDiberikan = ref(EMR.makananYangDiberikan())
let kebiasaanSebelumTidur = ref(EMR.kebiasaanSebelumTidur())
let aktivitasBermain = ref(EMR.aktivitasBermain())
let riwayatPenyakitKeluarga = ref(EMR.riwayatPenyakitKeluarga())
let pernapasan = ref(EMR.pernapasan())
let sirkulasiCairan = ref(EMR.sirkulasiCairan())
let penglihatan = ref(EMR.penglihatan())
let pendengaran = ref(EMR.pendengaran())
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
let eliminasi = ref(EMR.eliminasi())
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
const COLLECTION: any = ref('AsesmenAwalKeperawatanTindakanInvasifNonBedahCathlab')
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    jamPasienTiba: new Date(),
    jamTandaVital: new Date(),
    tandaVitalObservasiIntradanPaskaTindakan: [{ no: 1 }],
})
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

const addNewTandaVitalObservasiIntradanPaskaTindakan = () => {
  input.value.tandaVitalObservasiIntradanPaskaTindakan.push({
    no: input.value.tandaVitalObservasiIntradanPaskaTindakan[input.value.tandaVitalObservasiIntradanPaskaTindakan.length - 1].no + 1,
  });
}
const removeTandaVitalObservasiIntradanPaskaTindakan = (index: any) => {
  input.value.tandaVitalObservasiIntradanPaskaTindakan.splice(index, 1)
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
// const markignSite = () => {
//   let sigCanvas: any = document.getElementById("markingsite");
//   // sigCanvas.height = 500
//   // sigCanvas.width = 500
//   let context = sigCanvas.getContext("2d");
//   context.strokeStyle = "red";
//   context.lineJoin = "round";
//   context.lineWidth = 2;
//   let is_touch_device = 'ontouchstart' in document.documentElement;

//   if (is_touch_device) {

//     let drawer: any = {
//       isDrawing: false,
//       touchstart: function (coors: any) {
//         context.beginPath();
//         context.moveTo(coors.x, coors.y);
//         this.isDrawing = true;
//       },
//       touchmove: function (coors: any) {
//         if (this.isDrawing) {
//           context.lineTo(coors.x, coors.y);
//           context.stroke();
//         }
//       },
//       touchend: function (coors: any) {
//         if (this.isDrawing) {
//           this.touchmove(coors);
//           this.isDrawing = false;
//         }
//       }
//     };


//     function draw(event: any) {
//       let coors = {
//         x: event.targetTouches[0].pageX,
//         y: event.targetTouches[0].pageY
//       };

//       let obj = sigCanvas;

//       if (obj.offsetParent) {

//         do {
//           coors.x -= obj.offsetLeft;
//           coors.y -= obj.offsetTop;
//         }

//         while ((obj = obj.offsetParent) != null);
//       }


//       drawer[event.type](coors);
//     }


//     sigCanvas.addEventListener('touchstart', draw, false);
//     sigCanvas.addEventListener('touchmove', draw, false);
//     sigCanvas.addEventListener('touchend', draw, false);


//     sigCanvas.addEventListener('touchmove', function (event: any) {
//       event.preventDefault();

//     }, false);
//   } else {

//     $("#markingsite").mousedown(function (mouseEvent: any) {

//       let position = getPosition(mouseEvent, sigCanvas);
//       context.moveTo(position.X, position.Y);
//       context.beginPath();
//       $(this).mousemove(function (mouseEvent: any) {
//         drawLine(mouseEvent, sigCanvas, context);
//       }).mouseup(function (mouseEvent: any) {
//         finishDrawing(mouseEvent, sigCanvas, context);
//       }).mouseout(function (mouseEvent: any) {
//         finishDrawing(mouseEvent, sigCanvas, context);
//       });
//     });

//   }
// }

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
  input.value.tanggalMasuk = props.registrasi.tglmasuk
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
        '&field=beratBadan,tinggiBadan,IMT,lingkarPerut,tekananDarah,nafas,suhu,nadi'
    )
    .then((response) => {
      if (response != null) {
        input.value.beratBadan = response.beratBadan
        input.value.tinggiBadan = response.tinggiBadan
        input.value.IMT = response.IMT
        input.value.lingkarPerut = response.lingkarPerut
        input.value.tekananDarah = response.tekananDarah
        input.value.nafas = response.nafas
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
  // markignSite();
}
performAction();
setView()
setAutoFill()
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

.bold-text {
  font-weight: bold;
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

<style scoped lang="scss">
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
</style>