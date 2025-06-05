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
    <VCard>
      <div class="column is-12">
        <VCard class="p-3">
          <div class="column is-12 px-2">
            <h1>1. ASSESSMENT</h1>
            <div class="columns is-multiline">
              <div class="column is-3">
                <div class=" columns is-multiline">
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> BB : </span>
                  </div>
                  <div class="column is-12">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="BB" v-model="input.bb" />
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
                    <span> TB : </span>
                  </div>
                  <div class="column is-12">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="TB" v-model="input.tb" />
                      </VControl>
                      <VControl style="70px" class="field-addon-body">
                        <VButton static>cm</VButton>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-3">
                <div class=" columns is-multiline">
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> LILA : </span>
                  </div>
                  <div class="column is-12">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="LILA" v-model="input.lila" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>cm</VButton>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-3">
                <div class=" columns is-multiline">
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> TILUT : </span>
                  </div>
                  <div class="column is-12">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="TILUT" v-model="input.tilut" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>cm</VButton>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-3">
                <div class=" columns is-multiline">
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> IMT : </span>
                  </div>
                  <div class="column is-12">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="IMT" v-model="input.imt" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Kg/m<sup>2</sup></VButton>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-3">
                <div class=" columns is-multiline">
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> BB/U : </span>
                  </div>
                  <div class="column is-12">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="BB/U" v-model="input.bbperU" />
                      </VControl>
                      <VControl style="70px" class="field-addon-body">
                        <VButton static>SD</VButton>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-3">
                <div class=" columns is-multiline">
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> TB/U : </span>
                  </div>
                  <div class="column is-12">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="TB/U" v-model="input.tbperU" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>SD</VButton>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-3">
                <div class=" columns is-multiline">
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> BB/TBST : </span>
                  </div>
                  <div class="column is-12">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="TILUT" v-model="input.bbperTBST" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>SD</VButton>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-3">
                <div class=" columns is-multiline">
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> GIZI : </span>
                  </div>
                  <div class="column is-12">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="GIZI" v-model="input.gizi" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>AKG</VButton>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-11">
                <span> Biokimia : </span>
                <VField>
                  <VTextarea rows="3" placeholder="Biokimia....." v-model="input.biokimia"></VTextarea>
                </VField>
              </div>
              <div class="column is-1" style="align-self: center;">
              <VIconButton type="button" raised circle icon="fas fa-copy" v-tooltip-prime.bottom="'Tetapkan Data'"
                :disabled="sourceDataLab.length == 0 ? true : false" @click="show" color="warning"></VIconButton>
            </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-3">
                <div class=" columns is-multiline">
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> TD : </span>
                  </div>
                  <div class="column is-12">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="TD" v-model="input.td" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>mmHg</VButton>
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
                        <VInput type="text" class="input" placeholder="N" v-model="input.n" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>x/menit</VButton>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-3">
                <div class=" columns is-multiline">
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> RR : </span>
                  </div>
                  <div class="column is-12">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="RR" v-model="input.rr" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>x/menit</VButton>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-3">
                <div class=" columns is-multiline">
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> Suhu : </span>
                  </div>
                  <div class="column is-12">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Suhu" v-model="input.suhu" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static><sup>2</sup>C</VButton>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns is-multiline mb-5">
              <div class="column is-12">
                <span>Keluhan</span>
                <VField class="mt-2">
                  <VTextarea rows="3" placeholder="Keluhan......" v-model="input.keluhan"></VTextarea>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline mb-5">
              <div class="column is-12 P-0">
                <div class="column is-12">
                  <span> Kebiasaaan Pola Makan : </span>
                </div>
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-3">
                      <VField>
                        <span> Makananan dari luar : </span>
                      </VField>
                    </div>
                    <div class="column is-1">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.makananDariLuarYa" label="Ya" class="p-0" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-1">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.makananDariLuarTidak" label="Tidak" class="p-0" color="primary"
                            square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-5" v-if="input.makananDariLuarYa">
                      <label>Jika Ya, Sebutkan..</label>
                      <VField class="mt-2">
                        <VTextarea rows="2" placeholder="...." v-model="input.makananDariLuarYaSebutkan">
                        </VTextarea>
                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-3">
                      <VField>
                        <span> Alergi Makanan : </span>
                      </VField>
                    </div>
                    <div class="column is-1">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.alergiMakananYa" label="Ya" class="p-0" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-1">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.alergiMakananTidak" label="Tidak" class="p-0" color="primary"
                            square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-5" v-if="input.alergiMakananYa">
                      <label>Jika Ya, Sebutkan..</label>
                      <VField class="mt-2">
                        <VTextarea rows="2" placeholder="...." v-model="input.alergiMakananYaSebutkan">
                        </VTextarea>
                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-3">
                      <VField>
                        <span> Interaksi Obat Dan Makanan : </span>
                      </VField>
                    </div>
                    <div class="column is-1">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.interaksiObatDanMakananYa" label="Ya" class="p-0" color="primary"
                            square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-1">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.interaksiObatDanMakananTidak" label="Tidak" class="p-0"
                            color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-5" v-if="input.interaksiObatDanMakananYa">
                      <label>Jika Ya, Sebutkan..</label>
                      <VField class="mt-2">
                        <VTextarea rows="2" placeholder="...." v-model="input.interaksiObatDanMakananYaSebutkan">
                        </VTextarea>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </VCard>
      </div>

      <!-- <div class="column is-12">
        <VCard class="p-3">
          <h1>2. DIAGNOSIS GIZI</h1>
          <div class="column is-12 px-2">
            <div class="columns is-multiline mb-5">
              <div class="column is-12">
                <VField>
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.diagnosisGizi" :suggestions="d_DiagnosaGizi"
                    @complete="fetchDiagnosaGizi($event)" :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Diagnosa Gizi" />
                </VControl>
              </VField>
              </div>
            </div>
          </div>
        </VCard>
      </div> -->

      <div class="column is-12">
        <VCard class="p-3">
          <h1>2. DIAGNOSIS GIZI</h1>
          <div class="column is-9 px-2">
            <div class="columns is-multiline mb-5">
              <div v-for="(diagnosis, index) in diagnosisGiziList" :key="index" class="column is-9">
                <div class="columns is-vcentered">
                  <div class="column is-9">
                    <VField>
                      <VControl class="prime-auto">
                        <AutoComplete
                          v-model="diagnosis.value"
                          :suggestions="d_DiagnosaGizi"
                          @complete="fetchDiagnosaGizi($event)"
                          :optionLabel="'label'"
                          :dropdown="true"
                          :minLength="3"
                          :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'"
                          :field="'label'"
                          placeholder="Diagnosa Gizi"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div v-if="diagnosisGiziList.length > 1" class="column is-1">
                    <button class="button is-danger" @click="removeDiagnosisGizi(index)">
                      -
                    </button>
                  </div>
                  <div class="column is-12">
                    <button class="button is-primary" @click="addDiagnosisGizi" :disabled="diagnosisGiziList.length >= 4">
                      +
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </VCard>
      </div>

      <div class="column is-12">
        <VCard class="p-3">
          <h1>3. INTERVENSI</h1>
          <div class="column is-12 px-2">
            <!-- <div class="columns is-multiline mb-5">
              <div class="column is-4">
                <span>Diberikan Diet :</span>
              </div>
              <div class="column is-4">
                <VField>
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.dietGizi" :suggestions="d_Diet"
                    @complete="fetchDiet($event)" :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Jenis Diet" />
                </VControl>
              </VField>
              </div>
            </div> -->
            <div class="columns is-multiline mb-5">
              <div class="column is-4">
                <span>Diberikan Diet :</span>
              </div>
              <div class="column is-4">
                <div v-for="(diet, index) in diets" :key="index" class="columns is-vcentered mb-2">
                  <div class="column is-10">
                    <VField>
                      <VControl class="prime-auto">
                        <AutoComplete
                          v-model="diet.value"
                          :suggestions="d_Diet"
                          @complete="fetchDiet($event)"
                          :optionLabel="'label'"
                          :dropdown="true"
                          :minLength="3"
                          :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'"
                          :field="'label'"
                          placeholder="Jenis Diet"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2" v-if="diets.length > 1">
                    <button class="button is-danger" @click="removeDiet(index)">
                      -
                    </button>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <button
                  class="button is-primary"
                  @click="addDiet"
                  :disabled="diets.length >= 4"
                >
                  +
                </button>
              </div>
            </div>

            <div class="columns is-multiline mb-5">
              <div class="column is-4">
                <span>Dengan E :</span>
              </div>
              <div class="column is-4">
                <VField addons>
                  <VControl>
                    <VInput type="text" class="E" placeholder="E" v-model="input.denganE" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>kkal</VButton>
                  </VControl>
                </VField>
              </div>
            </div>
            <!-- <div class="columns is-multiline mb-5">
              <div class="column is-4">
                <span>Bentuk Makanan :</span>
              </div>
              <div class="column is-4">
                <VField>
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.bentukMakanan" :suggestions="d_BentukMakanan"
                    @complete="fetchBentukMakanan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Bentuk Makanan" />
                </VControl>
              </VField>
              </div>
            </div> -->
            <div class="columns is-multiline mb-5">
              <div class="column is-4">
                <span>Bentuk Makanan :</span>
              </div>
              <div class="column is-4">
                <div v-for="(bentuk, index) in bentukMakananList" :key="index" class="columns is-vcentered mb-2">
                  <div class="column is-10">
                    <VField>
                      <VControl class="prime-auto">
                        <AutoComplete
                          v-model="bentuk.value"
                          :suggestions="d_BentukMakanan"
                          @complete="fetchBentukMakanan($event)"
                          :optionLabel="'label'"
                          :dropdown="true"
                          :minLength="3"
                          :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'"
                          :field="'label'"
                          placeholder="Bentuk Makanan"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2" v-if="bentukMakananList.length > 1">
                    <button class="button is-danger" @click="removeBentukMakanan(index)">
                      -
                    </button>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <button class="button is-primary" @click="addBentukMakanan" :disabled="bentukMakananList.length >= 4">
                  +
                </button>
              </div>
            </div>
            <div class="columns is-multiline mb-5">
              <div class="column is-4">
                <span>Cara Pemeberian :</span>
              </div>
              <div class="column is-4">
                <VField>
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.CaraPemberian" :suggestions="d_CaraPemberian"
                    @complete="fetchCaraPemberian($event)" :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cara Pemberian" :disabled="isLocked" />
                </VControl>
              </VField>
              </div>
            </div>
            <div class="columns is-multiline mb-5">
              <div class="column is-4">
                <span>Frekuensi :</span>
              </div>
              <div class="column is-4">
                <VField class="mt-2">
                  <VInput type="text" class="input" placeholder="Frekuensi" v-model="input.frekuensi" />
                </VField>
              </div>
            </div>
          </div>
        </VCard>
      </div>

      <div class="column is-12">
        <VCard class="p-3">
          <h1>4. MONITORING DAN EVALUASI</h1>
          <div class="column is-12 px-2">
            <div class="columns is-multiline mb-5">
              <div class="column is-12">
                <VField class="mt-2">
                  <VTextarea rows="2" placeholder="Monitoring dan Evaluasi....." v-model="input.monitoringEvaluasi"></VTextarea>
                </VField>
              </div>
            </div>
          </div>
        </VCard>
      </div>

      <div class="columns is-multiline">
        <div class="column is-4" style="margin-left: auto;">
          <VCard>
            <h1 style="font-weight: bold; text-align: center"> Ahli Gizi </h1>
            <div class="mt-6">
              <img v-if="input.ahliGizi"
                  :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.ahliGizi.label ? input.ahliGizi.label : input.ahliGizi)">
                    <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
            </div>
            <div class="mt-2">
              <VField>
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.ahliGizi" :suggestions="d_Pegawai"
                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Pemberi Informasi..." />
                </VControl>
              </VField>
            </div>
          </VCard>
        </div>
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
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import * as EMR from '../page-emr-plugins/pengkajian-awal-gizi-rawat-inap'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

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

const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value >= 20 })
const isLoading: any = ref(false)
const isLoadingVitalSign: any = ref(false)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const d_Ruangan: any = ref([])
const d_Diet: any = ref([])
const diets = ref([{ value: '' }])
const d_BentukMakanan: any = ref([])
const bentukMakananList = ref([{ value: '' }])
const d_CaraPemberian: any = ref([])
const d_DiagnosaGizi: any = ref([])
const diagnosisGiziList = ref([{ value: '' }])
const sourceDataLab = ref([])
const selectedResep = ref();
const showData: any = ref(false)
const isLoadTmb: any = ref(false)
const d_produk: any = ref([])
const d_Kelas: any = ref([])
const d_Diagnosa: any = ref([])
const isLoadingTT: any = ref(false)
const item: any = reactive({

  NOREC_PD: (NOREC_PD !== undefined ? NOREC_PD : '') || '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})

const input: any = ref({
  detailObatDibawaPasien: [{ no: 1 }],
  bb: '',          // Berat Badan (Kg)
  tb: '',          // Tinggi Badan (Cm)
  tgllahir: '',    // Tanggal Lahir (format YYYY-MM-DD)
  umur: '',        // Umur dalam tahun
  imt: '',         // IMT (Kg/m²)
  bbperU: '',      // BB/U (SD)
  tbperU: '',      // TB/U (SD)
  bbperTBST: '',   // BB/TBST (SD)
  gizi: ''         // Status Gizi
});

// Fungsi untuk menghitung umur dari tanggal lahir
const calculateUmur = (tglLahir) => {
  const today = new Date()
  const birthDate = new Date(tglLahir)
  let age = today.getFullYear() - birthDate.getFullYear()
  const monthDifference = today.getMonth() - birthDate.getMonth()

  // Jika bulan sekarang lebih kecil atau sama dengan bulan kelahiran tapi harinya lebih kecil, kurangi umur setahun
  if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
    age--
  }
  input.value.umur = age
}

// Fungsi untuk menghitung IMT
const calculateIMT = () => {
  const beratBadan = parseFloat(input.value.bb);
  const tinggiBadan = parseFloat(input.value.tb) / 100; // Konversi cm ke meter
  if (beratBadan > 0 && tinggiBadan > 0) {
    input.value.imt = (beratBadan / (tinggiBadan * tinggiBadan)).toFixed(2);
  } else {
    input.value.imt = '';
  }
};

// Fungsi untuk menghitung BB/U (Berat Badan per Usia) - Placeholder
const calculateBBperU = () => {
  const beratBadan = parseFloat(input.value.bb);
  const umur = parseFloat(input.value.umur);

  // Contoh sederhana untuk menghitung BB/U, sesuaikan dengan z-score
  if (umur < 18) {
    input.value.bbperU = (beratBadan / 10).toFixed(2); // Placeholder: sesuaikan
  } else {
    input.value.bbperU = ''; // Tidak relevan untuk dewasa
  }
};

// Fungsi untuk menghitung TB/U (Tinggi Badan per Usia) - Placeholder
const calculateTBperU = () => {
  const tinggiBadan = parseFloat(input.value.tb);
  const umur = parseFloat(input.value.umur);

  // Contoh sederhana untuk menghitung TB/U, sesuaikan dengan z-score
  if (umur < 18) {
    input.value.tbperU = (tinggiBadan / 50).toFixed(2); // Placeholder: sesuaikan
  } else {
    input.value.tbperU = ''; // Tidak relevan untuk dewasa
  }
};

// Fungsi untuk menghitung BB/TBST (Berat Badan per Tinggi Badan Standar) - Placeholder
const calculateBBperTBST = () => {
  const beratBadan = parseFloat(input.value.bb);
  const tinggiBadan = parseFloat(input.value.tb);

  // Contoh sederhana untuk menghitung BB/TBST, sesuaikan dengan standar yang relevan
  if (beratBadan > 0 && tinggiBadan > 0) {
    input.value.bbperTBST = (beratBadan / tinggiBadan).toFixed(2); // Placeholder
  } else {
    input.value.bbperTBST = '';
  }
};

// Fungsi untuk menentukan status gizi berdasarkan IMT dan umur
const calculateGizi = () => {
  const imt = parseFloat(input.value.imt);
  const umur = parseFloat(input.value.umur);
  const bbperU = parseFloat(input.value.bbperU);
  const tbperU = parseFloat(input.value.tbperU);
  const bbperTBST = parseFloat(input.value.bbperTBST);

  if (umur >= 18) {
    // Penentuan status gizi untuk orang dewasa (> 18 tahun) berdasarkan IMT
    if (imt < 18.5) {
      input.value.gizi = 'Gizi Kurang';
    } else if (imt >= 18.5 && imt <= 24.9) {
      input.value.gizi = 'Gizi Baik';
    } else if (imt >= 25 && imt <= 29.9) {
      input.value.gizi = 'Gizi Lebih';
    } else if (imt >= 30) {
      input.value.gizi = 'Obesitas';
    }
  } else {
    // Penentuan status gizi untuk anak-anak/remaja (< 18 tahun) berdasarkan BB/U, TB/U, BB/TBST, dan z-score
    // Gunakan placeholder atau nilai aktual dari standar z-score WHO
    if (bbperU < 2 && tbperU < 2 && bbperTBST < 2) {
      input.value.gizi = 'Gizi Baik';  // Contoh status gizi untuk z-score
    } else if (bbperU >= 2 || tbperU >= 2) {
      input.value.gizi = 'Gizi Lebih';
    } else if (bbperU < -2 || tbperU < -2) {
      input.value.gizi = 'Gizi Kurang';
    }
  }
};

// Watcher untuk memonitor perubahan pada input.bb dan input.tb serta menghitung umur dari tanggal lahir
watch([() => input.value.bb, () => input.value.tb, () => props.pasien.tgllahir], () => {
  calculateUmur(props.pasien.tgllahir) // Hitung umur berdasarkan tanggal lahir
  calculateIMT()                        // Hitung IMT
  calculateBBperU()                     // Hitung BB/U
  calculateTBperU()                     // Hitung TB/U
  calculateBBperTBST()                  // Hitung BB/TBST
  calculateGizi()                       // Tentukan status gizi
})

const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const isLocked = ref(false)
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
// const loadRiwayat = async () => {
//   // if (NOREC_EMRPASIEN.value == '') return
//   await useApi().get(
//     `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
//       if (response.length) {
//         input.value = response[0]
//         if (response[0].tandaTanganAhliGizi) {
//           H.tandaTangan().set("signature_1", response[0].tandaTanganAhliGizi)
//         }
//         if (NOREC_EMRPASIEN.value == '') {
//           NOREC_EMRPASIEN.value = response[0].emrpasienfk
//         }
//       }
//     })
// }

const loadRiwayat = async () => {
  // Lakukan pengambilan data riwayat dari API
  await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    .then((response: any) => {
      if (response.length) {
        // Mengisi kembali diagnosisGiziList dari respons API
        diagnosisGiziList.value = response[0].diagnosisGizi.map((diagnosis: any) => ({
          value: diagnosis
        }))
        // Mengisi kembali diets dari respons API
        diets.value = response[0].diets.map((diet: any) => ({
          value: diet
        }))
         // Mengisi kembali bentukMakananList dari respons API
         bentukMakananList.value = response[0].bentukMakanan.map((bentuk: any) => ({
          value: bentuk
        }))
        // Mengatur nilai input.value jika perlu
        input.value = response[0] // Sesuaikan sesuai struktur respons API Anda
        if (response[0].tandaTanganAhliGizi) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganAhliGizi)
        }
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }
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

const fetchProduk = async (filter: any) => {
  const response = await useApi().get(
    `/farmasi/dropdown-obat?namaproduk=${filter.query}&limit=10`)
  d_produk.value = response

}

const show = () => {
  showData.value = true
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

  input.value.biokimia += e
  showData.value = false

}

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const fetchDiagnosaGizi = async (filter: any) => {
  d_DiagnosaGizi.value = EMR.diagnosaGizi()
}

const addDiagnosisGizi = () => {
  if (diagnosisGiziList.value.length < 4) {
    diagnosisGiziList.value.push({ value: '' })
  }
}

const removeDiagnosisGizi = (index: number) => {
  if (diagnosisGiziList.value.length > 1) {
    diagnosisGiziList.value.splice(index, 1)
  }
}

const fetchDiet = async (filter: any) => {
  d_Diet.value = EMR.Diet()
}

const addDiet = () => {
  if (diets.value.length < 4) {
    diets.value.push({ value: '' })
  }
}

const removeDiet = (index: number) => {
  if (diets.value.length > 1) {
    diets.value.splice(index, 1)
  }
}

const fetchBentukMakanan = async (filter: any) => {
  d_BentukMakanan.value = EMR.BentukMakanan()
}

const addBentukMakanan = () => {
  if (bentukMakananList.value.length < 4) {
    bentukMakananList.value.push({ value: '' })
  }
}

const removeBentukMakanan = (index: number) => {
  if (bentukMakananList.value.length > 1) {
    bentukMakananList.value.splice(index, 1)
  }
}

const fetchCaraPemberian = async (filter: any) => {
  d_CaraPemberian.value = EMR.CaraPemberian()
}


const setTandaTangan = async (e: any) => {
  const response = await useApi().get(
    `/emr/tanda-tangan/${e.value.value}`)
  if (response != null) {
    H.tandaTangan().set("signature_1", response.ttd)
    input.value.tandaTanganAhliGizi = response.ttd
  } else {
    H.tandaTangan().set("signature_1", '')
  }
}

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}

const fetchDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/diagnosa_m?select=id,namadiagnosa&param_search=namadiagnosa&query=${filter.query}&limit=10`)
  d_Diagnosa.value = response
}

const fetchKelas = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/kelas_m?select=id,namakelas&param_search=namakelas&query=${filter.query}&limit=10`)
  d_Kelas.value = response
}

const fetchDokter = async (filter: any) => {
  const response = await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`)
  d_Dokter.value = response
}


// const simpan = () => {
//   let ID = input.value.id ? input.value.id : ''

//   let object: any = {}

//   object = input.value
//   object.tandaTanganAhliGizi = H.tandaTangan().get("signature_1")
//   object.pasien = H.setObjectPasien(props.pasien)
//   object.registrasi = H.setObjectRegistrasi(props.registrasi)
//   let json = {
//     'id': ID,
//     'norec_emr': NOREC_EMRPASIEN.value,
//     'collection': COLLECTION.value,
//     'url_form': props.FORM_URL,
//     'name_form': props.FORM_NAME,
//     'jenis_emr': 'asesmen_medis',
//     'data': object
//   }
//   isLoading.value = true;
//   useApi().post(
//     `/emr/simpan-emr`, json).then((response: any) => {
//       isLoading.value = false;
//       NOREC_EMRPASIEN.value = response.norec_emr;
//       input.value.id = response.id;
//       isLocked.value = true;
//     }).catch((e: any) => {
//       isLoading.value = false;
//     })
// }

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  object.tandaTanganAhliGizi = H.tandaTangan().get("signature_1")
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)

  // Mengumpulkan nilai diagnosisGiziList ke dalam array
  object.diagnosisGizi = diagnosisGiziList.value.map((item: any) => item.value)
   // Mengumpulkan nilai diets ke dalam array
  object.diets = diets.value.map((item: any) => item.value)
   // Mengumpulkan nilai bentukMakananList ke dalam array
  object.bentukMakanan = bentukMakananList.value.map((item: any) => item.value)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': props.FORM_URL,
    'name_form': props.FORM_NAME,
    'jenis_emr': 'asesmen_medis',
    'data': object
  }

  isLoading.value = true;
  useApi().post(`/emr/simpan-emr`, json)
    .then((response: any) => {
      isLoading.value = false;
      NOREC_EMRPASIEN.value = response.norec_emr;
      input.value.id = response.id;
      isLocked.value = true;
    })
    .catch((e: any) => {
      isLoading.value = false;
    })
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
        "poin": "5"
      },
      {
        "model": "kesadaranV",
        "poin": "4"
      },
      {
        "model": "kesadaranV",
        "poin": "3"
      },
      {
        "model": "kesadaranV",
        "poin": "2"
      },
      {
        "model": "kesadaranV",
        "poin": "1"
      },
    ]
  },
])

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.tanggalWaktuRegistrasi = props.registrasi.tglregistrasi;
  input.value.tanggalWaktuKunjuangan = new Date();
  input.value.tglPembuatan = new Date();
  input.value.umur = props.pasien.umur;
  input.value.namaruangan = props.registrasi.namaruangan;
  input.value.namakelas = props.registrasi.namakelas;
  input.value.dokter = props.registrasi.dokter;

  isLoadingVitalSign.value = true;

  await useApi().get(
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=VitalSign" + "&field=beratBadan,tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi"
  ).then((response) => {
    if (response != null) {
      input.value.bb = response.beratBadan;
      input.value.tb = response.tinggiBadan;
      input.value.lingkarPerut = response.lingkarPerut;
      input.value.td = response.tekananDarah;
      input.value.rr = response.pernapasan;
      input.value.suhu = response.suhu;
      input.value.n = response.nadi;

      //Menghitung IMT
      const beratBadan = response.beratBadan;
      const tinggiBadanCm = response.tinggiBadan;
      const tinggiBadanM = tinggiBadanCm / 100;
      const imt = beratBadan / (tinggiBadanM * tinggiBadanM);

      // input.value.imt = imt.toFixed(2);
    }
    isLoadingVitalSign.value = false;
  }).catch((error) => {
    console.error("Error fetching vital signs:", error);
    isLoadingVitalSign.value = false;
  })
  await useApi().get(
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=PengkajianAwalPasienGawatDarurat" + "&field=beratBadan,tinggiBadan,IMT"
  ).then((response) => {
    if (response != null) {
      input.value.bb = response.beratBadan;
      input.value.tb = response.tinggiBadan;
      input.value.lingkarPerut = response.lingkarPerut;
      input.value.td = response.tekananDarah;
      input.value.rr = response.pernapasan;
      input.value.suhu = response.suhu;
      input.value.n = response.nadi;

      //Menghitung IMT
      const beratBadan = response.beratBadan;
      const tinggiBadanCm = response.tinggiBadan;
      const tinggiBadanM = tinggiBadanCm / 100;
      const imt = beratBadan / (tinggiBadanM * tinggiBadanM);

      // input.value.imt = imt.toFixed(2);
    }
    isLoadingPengkajianAwalPasienGawatDarurat.value = false;
  }).catch((error) => {
    console.error("Error fetching vital signs:", error);
    isLoadingPengkajianAwalPasienGawatDarurat.value = false;
  })
}
setView()
setAutoFill()
loadRiwayat()
await SourceHasilLab()
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
