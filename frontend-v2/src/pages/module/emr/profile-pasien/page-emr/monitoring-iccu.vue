<template>
 
  <div >
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3> {{ props.FORM_NAME }}</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
            </div>
          </div>
        </div>

      </div>
    </div>

    <div class="column">
      <div class="columns is-multiline">
        <div class="column is-2">
          <span class="label-icu">No RM</span>
          <VField class="pt-2">
            <VControl>
              <VInput type="text" v-model="input.norm" />
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <span class="label-icu">Nama Pasien</span>
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="input.namaPasien" />
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <span class="label-icu">Tanggal Registrasi</span>
          <VDatePicker v-model="input.tanggal" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
            <template #default="{ inputValue, inputEvents }">
              <VField class="pb-0 pt-3">
                <VControl icon="feather:calendar" fullwidth>
                  <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>
        <div class="column is-2">
          <span class="label-icu">Berat Badan </span>
          <VField class="pt-2">
            <VControl>
              <VInput type="text" v-model="input.beratBadan" />
            </VControl>
          </VField>
        </div>
        <div class="column is-2">
          <span class="label-icu">Tinggi Badan</span>
          <VField class="pt-2">
            <VControl>
              <VInput type="text" v-model="input.tinggiBadan" />
            </VControl>
          </VField>
        </div>
        <div class="column is-4">
          <span class="label-icu">Perawat Pagi</span>
          <VField class="pt-3">
            <VControl class="prime-auto">
              <AutoComplete v-model="input.perawatPagi" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                :loadingIcon="'pi pi-spinner'" :field="'label'" />
            </VControl>
          </VField>
        </div>
        <div class="column is-4">
          <span class="label-icu">Perawat Siang</span>
          <VField class="pt-3">
            <VControl class="prime-auto">
              <AutoComplete v-model="input.perawatSiang" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                :loadingIcon="'pi pi-spinner'" :field="'label'" />
            </VControl>
          </VField>
        </div>
        <div class="column is-4">
          <span class="label-icu">Perawat Malam</span>
          <VField class="pt-3">
            <VControl class="prime-auto">
              <AutoComplete v-model="input.perawatMalam" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                :loadingIcon="'pi pi-spinner'" :field="'label'" />
            </VControl>
          </VField>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-6">
          <span class="label-icu">Diagnosa</span>
          <VField>
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
              placeholder="ketik untuk mencari..."
            />
          </VField>
        </div>
        <div class="column is-6">
          <span class="label-icu">Dokter</span>
          <VField>
            <AutoComplete
              v-model="input.dokter"
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
          </VField>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-2">
          <span class="label-icu">RAMSAY Score</span>
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="input.ramsayScore" />
            </VControl>
          </VField>
        </div>
        <div class="column is-2">
          <span class="label-icu">RASS Score</span>
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="input.rassScore" />
            </VControl>
          </VField>
        </div>
        <div class="column is-2">
          <span class="label-icu">SOFA Score</span>
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="input.sofaScore" />
            </VControl>
          </VField>
        </div>
        <div class="column is-2">
          <span class="label-icu">APACHE Score</span>
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="input.apacheScore" />
            </VControl>
          </VField>
        </div>
        <div class="column is-2">
          <span class="label-icu">CPIS Score</span>
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="input.cpisScore" />
            </VControl>
          </VField>
        </div>
      </div>
    </div>

    

    <div class="column">
      <VTabs slider selected="data" :tabs="[
        { label: 'Data', value: 'data' },
        { label: 'Chart', value: 'chart' },
      ]">
        <template #tab="{ activeValue }">

          <p v-if="activeValue === 'data'">
            <DataTable :value="description" scrollable scrollHeight="1500px" class="p-datatable-sm" showGridlines
              editMode="cell" @cell-edit-complete="onCellEditComplete" tableClass="editable-cells-table"
              tableStyle="min-width: 50rem" groupRowsBy="group" rowGroupMode="subheader">

              <ColumnGroup type="header">
                <Row>
                  <Column header="Deskripsi" style="min-width: 150px; text-align: center;" frozen :rowspan="2" />
                  <Column header="Waktu" style="min-width: 300px;" class="font-bold" :colspan="waktu.length" />
                </Row>
                <Row>
                  <Column :header="data" style="min-width: 100px;" v-for="(data) in waktu" class="font-bold"
                    :rowEditor="true">
                  </Column>
                </Row>
              </ColumnGroup>

              <Column field="deskripsi" style="min-width: 150px; text-align: center;" frozen />
              <Column style="width: 25%" v-for="(data, i) in waktu" class="font-bold" :field="data">
                <template #body="slotProps">
                  {{ slotProps.data[data] }}
                </template>
                <template #editor="{ data, field }">
                  <InputText v-model="data[field]" autofocus />
                </template>
              </Column>
              <template #groupheader="slotProps">
                <div class="font-bold">
                  <span class="label-icu">{{ slotProps.data.group }}</span>
                </div>
              </template>
            </DataTable>
      
            <div class="column pl-0 pr-0 mt-5">
              <span class="label-icu">INTAKE</span>
              <div class="column pl-0 pr-0">
                <DataTable :value="tableIntake" scrollable scrollHeight="500px" class="p-datatable-sm" showGridlines
                  editMode="cell" @cell-edit-complete="onCellEditComplete" tableClass="editable-cells-table"
                  tableStyle="min-width: 50rem" groupRowsBy="group" rowGroupMode="subheader">

                  <ColumnGroup type="header">
                    <Row>
                      <Column header="Deskripsi" style="min-width: 150px; text-align: center;" frozen :rowspan="3" />
                      <Column header="Waktu" style="min-width: 300px;" class="font-bold"
                        :colspan="listDescripWaktu.ketWaktu.length" />
                    </Row>
                    <Row>
                      <Column :header="data" style="min-width: 100px;" v-for="(data) in waktu" class="font-bold"
                        :colspan="2">
                      </Column>
                    </Row>
                    <Row>
                      <Column :header="data" style="min-width: 100px;" v-for="(data) in listDescripWaktu.ketWaktu"
                        class="font-bold" :rowEditor="true">
                      </Column>
                    </Row>
                  </ColumnGroup>

                  <Column field="deskripsi" style="min-width: 150px; text-align: center;" frozen />

                  <Column style="width: 25%" v-for="(data, i) in listDescripWaktu.ketWaktu" class="font-bold"
                    :field="data + '_' + i">
                    <template #body="slotProps">
                      {{ slotProps.data[data + '_' + i] }}
                    </template>
                    <template #editor="{ data, field, }">
                      <InputText v-model="data[field]" autofocus />
                    </template>
                  </Column>
                  <template #groupheader="slotProps">
                    <div class="font-bold">
                      <span class="label-icu">{{ slotProps.data.group }}</span>
                    </div>
                  </template>
                </DataTable>
              </div><br><br>
              <span class="label-icu">PARENTERAL</span><br>
              <VButton color="primary" raised icon="fas fa-plus" class="mt-3" @click="addNewItemParenteral"> Tambah PARENTERAL </VButton>
              <div class="column pl-0 pr-0">
                <DataTable :value="parenteral" scrollable scrollHeight="500px" class="p-datatable-sm" showGridlines
                  editMode="cell" @cell-edit-complete="onCellEditComplete" tableClass="editable-cells-table"
                  tableStyle="min-width: 50rem">

                  <ColumnGroup type="header">
                    <Row>
                      <Column header="PARENTERAL" style="min-width: 150px; text-align: center;" frozen :rowspan="3" />
                      <Column header="Action" style="min-width: 150px; text-align: center;" frozen :rowspan="3" />
                      <Column header="Waktu" style="min-width: 300px;" class="font-bold"
                        :colspan="listDescripWaktu.ketWaktu.length" />
                    </Row>
                    <Row>
                      <Column :header="data" style="min-width: 100px;" v-for="(data) in waktu" class="font-bold"
                        :colspan="2">
                      </Column>
                    </Row>
                    <Row>
                      <Column :header="data" style="min-width: 100px;" v-for="(data) in listDescripWaktu.ketWaktu"
                        class="font-bold">
                      </Column>
                    </Row>
                  </ColumnGroup>

                  <Column field="namaParenteral" class="font-bold" style="min-width: 150px; text-align: center;" frozen>
                    <template #body="slotProps">
                      {{ slotProps.data.namaParenteral }}
                    </template>
                    <template #editor="{ data, field }">
                      <InputText v-model="data[field]" autofocus />
                    </template>
                  </Column>
                  <Column field="action" style="min-width: 150px; text-align: center;" frozen>
                    <template #body="slotProps">
                      <VIconButton class="mt-1" v-if="slotProps.data" type="button" raised circle icon="feather:trash"
                        @click="removeItemParenteral(slotProps.index)" color="danger">
                      </VIconButton>
                    </template>
                  </Column>
                  <Column style="width: 25%" v-for="(data, i) in listDescripWaktu.ketWaktu" class="font-bold"
                    :field="data + '_' + i">
                    <template #body="slotProps">
                      {{ slotProps.data[data + '_' + i] }}
                    </template>
                    <template #editor="{ data, field, }">
                      <InputText v-model="data[field]" autofocus />
                    </template>
                  </Column>
                  <!-- <ColumnGroup type="footer">
                    <Row>
                      <Column footer="Total" style="padding: 0.3rem 0.3rem 0 0.3rem" :colspan="2" />
                      <Column v-for="(data, i) in listDescripWaktu.descWaktu" :key="i" :field="data + '_' + i" style="min-width: 100px;" class="font-bold" :colspan="2">
                        <template #body="slotProps">
                          {{ slotProps.data[data + '_' + i] }}
                        </template>
                        <template #editor="{ data, field }">
                          <InputText v-model="data[field]" autofocus />
                        </template>
                      </Column>
                    </Row>
                  </ColumnGroup> -->
                </DataTable>
              </div>
            </div>

            <DataTable :value="output" scrollable scrollHeight="1500px" class="p-datatable-sm" showGridlines
              editMode="cell" @cell-edit-complete="onCellEditComplete" tableClass="editable-cells-table"
              tableStyle="min-width: 50rem" groupRowsBy="group" rowGroupMode="subheader">

              <ColumnGroup type="header">
                <Row>
                  <Column header="Deskripsi" style="min-width: 150px; text-align: center;" frozen :rowspan="2" />
                  <Column header="Waktu" style="min-width: 300px;" class="font-bold" :colspan="waktu.length" />
                </Row>
                <Row>
                  <Column :header="data" style="min-width: 100px;" v-for="(data) in waktu" class="font-bold"
                    :rowEditor="true">
                  </Column>
                </Row>
              </ColumnGroup>

              <Column field="deskripsi" style="min-width: 150px; text-align: center;" frozen />
              <Column style="width: 25%" v-for="(data, i) in waktu" class="font-bold" :field="data">
                <template #body="slotProps">
                  {{ slotProps.data[data] }}
                </template>
                <template #editor="{ data, field }">
                  <InputText v-model="data[field]" autofocus />
                </template>
              </Column>
              <template #groupheader="slotProps">
                <div class="font-bold">
                  <span class="label-icu">{{ slotProps.data.group }}</span>
                </div>
              </template>
            </DataTable><br><br>

            <span class="label-icu">LABORATORIUM KRITIS</span><br>
              <VButton color="primary" raised icon="fas fa-plus" class="mt-3" @click="addNewItemLab"> Tambah LABORATORIUM KRITIS </VButton>
              <div class="column pl-0 pr-0">
                <DataTable :value="labkritis" scrollable scrollHeight="500px" class="p-datatable-sm" showGridlines
                  editMode="cell" @cell-edit-complete="onCellEditComplete" tableClass="editable-cells-table"
                  tableStyle="min-width: 50rem">

                  <ColumnGroup type="header">
                    <Row>
                      <Column header="LABORATORIUM KRITIS" style="min-width: 150px; text-align: center;" frozen :rowspan="3" />
                      <Column header="Action" style="min-width: 150px; text-align: center;" frozen :rowspan="3" />
                      <Column header="Waktu" style="min-width: 300px;" class="font-bold"
                        :colspan="listDescripWaktu.ketWaktu.length" />
                    </Row>
                    <Row>
                      <Column :header="data" style="min-width: 100px;" v-for="(data) in waktu" class="font-bold">
                      </Column>
                    </Row>
                  </ColumnGroup>

                  <Column field="namaLabKritis" class="font-bold" style="min-width: 150px; text-align: center;" frozen>
                    <template #body="slotProps">
                      {{ slotProps.data.namaLabKritis }}
                    </template>
                    <template #editor="{ data, field }">
                      <InputText v-model="data[field]" autofocus />
                    </template>
                  </Column>
                  <Column field="action" style="min-width: 150px; text-align: center;" frozen>
                    <template #body="slotProps">
                      <VIconButton class="mt-1" v-if="slotProps.data" type="button" raised circle icon="feather:trash"
                        @click="removeItemLab(slotProps.index)" color="danger">
                      </VIconButton>
                    </template>
                  </Column>
                  <Column style="width: 25%" v-for="(data, i) in waktu" class="font-bold"
                    :field="data + '_' + i">
                    <template #body="slotProps">
                      {{ slotProps.data[data + '_' + i] }}
                    </template>
                    <template #editor="{ data, field, }">
                      <InputText v-model="data[field]" autofocus />
                    </template>
                  </Column>
                </DataTable>
              </div>

              <div class="column pl-0 pr-0 mt-5">
                <span class="label-icu">PENGOBATAN</span>
                <hr class="mt-2 ml-0 mr-0 mb-0" style="border-top-style: dashed;border-color: var(--fade-grey-dark-2);">
                <VButton color="primary" raised icon="fas fa-plus" class="mt-3" @click="addNewItem"> Tambah PENGOBATAN </VButton>

                <div class="column pl-0 pr-0">
                  <DataTable :value="medikasi" scrollable scrollHeight="500px" class="p-datatable-sm" showGridlines
                    editMode="cell" @cell-edit-complete="onCellEditComplete" tableClass="editable-cells-table"
                    tableStyle="min-width: 50rem">

                    <ColumnGroup type="header">
                      <Row>
                        <Column header="Nama Dokter Pemberi Instruksi" style="min-width: 150px; text-align: center;" frozen :rowspan="3" />
                        <Column header="Nama Obat, Kekuatan dan Bentuk Sediaan" style="min-width: 150px; text-align: center;" frozen :rowspan="3" />
                        <Column header="Aturan Pakai dan Rute Pemberian" style="min-width: 150px; text-align: center;" frozen :rowspan="3" />
                        <Column header="Tanggal Mulai dan Paraf Dokter" style="min-width: 150px; text-align: center;" frozen :rowspan="3" />
                        <Column header="Tanggal Stop dan Paraf Dokter" style="min-width: 150px; text-align: center;" frozen :rowspan="3" />
                        <Column header="Verifikasi Apoteker" style="min-width: 150px; text-align: center;" frozen :rowspan="3" />
                        <Column header="Action" style="min-width: 150px; text-align: center;" frozen :rowspan="3" />
                        <Column header="Waktu" style="min-width: 300px;" class="font-bold"
                          :colspan="listDescripWaktu.ketWaktu.length" />
                      </Row>
                      <Row>
                        <Column :header="data" style="min-width: 100px;" v-for="(data) in waktu" class="font-bold"></Column>
                      </Row>
                    </ColumnGroup>

                    <Column field="namaDokter" class="font-bold" style="min-width: 150px; text-align: center;" frozen>
                      <template #body="slotProps">
                        {{ slotProps.data.namaDokter.label }}
                      </template>
                      <template #editor="{ data, field }">
                        <VField class="pt-3">
                          <VControl class="prime-auto">
                            <AutoComplete v-model="data[field]" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                              :loadingIcon="'pi pi-spinner'" :field="'label'"  placeholder="Nama Dokter"/>
                          </VControl>
                        </VField>
                      </template>
                    </Column>
                    <Column field="namaObat" class="font-bold" style="min-width: 150px; text-align: center;" frozen>
                      <template #body="slotProps">
                        {{ slotProps.data.namaObat.namaproduk }}
                      </template>
                      <template #editor="{ data, field }">
                        <VField>
                          <VControl>
                            <AutoComplete v-model="data[field]" :suggestions="d_produk" @complete="fetchProduk($event)"
                              :optionLabel="'namaproduk'" :dropdown="true" :minLength="3" class="is-input"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'namaproduk'"
                              placeholder="ketik untuk mencari..." />
                          </VControl>
                        </VField>
                      </template>
                    </Column>
                    <Column field="aturanPakai" class="font-bold" style="min-width: 150px; text-align: center;" frozen>
                      <template #body="slotProps">
                        {{ slotProps.data.aturanPakai }}
                      </template>
                      <template #editor="{ data, field }">
                        <InputText v-model="data[field]" autofocus />
                      </template>
                    </Column>
                    <Column field="tanggalMulai" class="font-bold" style="min-width: 150px; text-align: center;" frozen>
                      <template #body="slotProps">
                        {{ slotProps.data.tanggalMulai }}
                      </template>
                      <template #editor="{ data, field }">
                        <InputText v-model="data[field]" autofocus />
                      </template>
                    </Column>
                    <Column field="tanggalStop" class="font-bold" style="min-width: 150px; text-align: center;" frozen>
                      <template #body="slotProps">
                        {{ slotProps.data.tanggalStop }}
                      </template>
                      <template #editor="{ data, field }">
                        <InputText v-model="data[field]" autofocus />
                      </template>
                    </Column>
                    <Column field="verifikasiApoteker" class="font-bold" style="min-width: 150px; text-align: center;" frozen>
                      <template #body="slotProps">
                        {{ slotProps.data.verifikasiApoteker }}
                      </template>
                      <template #editor="{ data, field }">
                        <InputText v-model="data[field]" autofocus />
                      </template>
                    </Column>
                    <Column field="action" style="min-width: 150px; text-align: center;" frozen>
                      <template #body="slotProps">
                        <VIconButton class="mt-1" v-if="slotProps.data" type="button" raised circle icon="feather:trash"
                          @click="removeItem(slotProps.index)" color="danger">
                        </VIconButton>
                      </template>
                    </Column>
                    <Column style="width: 25%" v-for="(data, i) in waktu" class="font-bold"
                      :field="data + '_' + i">
                      <template #body="slotProps">
                        {{ slotProps.data[data + '_' + i] }}
                      </template>
                      <template #editor="{ data, field, }">
                        <InputText v-model="data[field]" autofocus />
                      </template>
                    </Column>
                  </DataTable>
                </div>
              </div><br><br>

              <span class="label-icu">FORM HANDOVER</span>
              <hr class="mt-2 ml-0 mr-0 mb-0" style="border-top-style: dashed;border-color: var(--fade-grey-dark-2);">
              <div class="column" style="overflow: auto;">
                <table class="tg">
                  <thead>
                    <tr>
                      <td class="tg-0lax text-center">No</td>
                      <td class="tg-0lax text-center">PAGI</td>
                      <td class="tg-0lax text-center">SIANG</td>
                      <td class="tg-0lax text-center">MALAM</td>
                      <td class="tg-0lax text-center">#</td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(data, index) in input.detailObatPerawatanIGD" :key="index">
                      <td width="5%">{{ data.no }}</td>
                      <td width="30%">
                        <div class="column is-12">
                          <span class="bold-text"> KESADARAN : </span>
                          <VField>
                            <VInput type="text" class="input" placeholder="Kesadaran" v-model="data.KesadaranPagi"/>
                          </VField>
                        </div>
                        <span class="bold-text"> GCS : </span>
                        <div class="columns">
                          <div class="column is-1">
                            <h1 class="mb-3 emr" style="text-align: center">No</h1>
                          </div>
                          <div class="column is-one-quarter" style="text-align: center">
                            <h1 class="mb-3 emr">Parameter</h1>
                          </div>
                          <div class="column is-5" style="text-align: center">
                            <h1 class="mb-3 emr">Nilai</h1>
                          </div>
                        </div>
                        <div class="columns pb-4" v-for="kesadaranItemPagi in kesadaran" :key="kesadaranItemPagi.no">
                          <div class="column is-1 pt-0" style="text-align: center">
                            <h1 class="mb-3 emr">{{ kesadaranItemPagi.no }}</h1>
                          </div>
                          <div class="column is-one-quarter pt-0" style="text-align: center">
                            <h1 class="mb-3 emr">{{ kesadaranItemPagi.parameter }}</h1>
                          </div>
                          <div class="column is-5" style="text-align: end">
                            <div class="columns is-multiline pb-5">
                              <div class="column is-4 pt-0" v-for="(pilihanPagi, pilihanPagiIndex) in kesadaranItemPagi.nilai" :key="pilihanPagiIndex">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="pilihanPagi.poin"
                                      v-model="input[pilihanPagi.model]"
                                      :label="pilihanPagi.poin"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="column is-12">
                          <span class="bold-text"> Oksigenasi : </span>
                          <VField>
                            <VInput type="text" class="input" placeholder="Oksigenasi" v-model="data.oksigenasiPagi"/>
                          </VField>
                        </div>
                        <span class="bold-text"> Cairan : </span><br>
                        <span class="bold-text"> Perifer Line : </span>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Infus : </span>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.InfusTakaPagi" label="Taka" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.InfusTakaPagi" label="Taki" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.InfusKakaPagi" label="Kaka" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.InfusKakiPagi" label="Kaki" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Transfusi : </span>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.transfusiTakaPagi" label="Taka" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.transfusiTakaPagi" label="Taki" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.transfusiKakaPagi" label="Kaka" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.transfusiKakiPagi" label="Kaki" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <span class="bold-text">Central Line : </span>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">CVC : </span>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.cvcLekaPagi" label="LeKa" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.cvcLekiPagi" label="LeKi" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.cvcDakaPagi" label="DaKa" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.cvcDakiPagi" label="DaKi" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">CDL : </span>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.cdlLekaPagi" label="LeKa" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.cdlLekiPagi" label="LeKi" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.cdlDakaPagi" label="DaKa" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.cdlDakiPagi" label="DaKi" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <span class="bold-text">Makan/Minum : </span>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField label="Susu :">
                              <VInput type="text" class="input" placeholder="Susu" v-model="data.susuPagi"/>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField label="Air Putih :">
                              <VInput type="text" class="input" placeholder="Air Putih" v-model="data.airPutihPagi"/>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField label="Lain-lain :">
                              <VInput type="text" class="input" placeholder="Lain-lain" v-model="data.lainLainMinumPagi"/>
                            </VField>
                          </div>
                        </div>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Toileting : </span>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.toiletingBabPagi" label="BAB" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.toiletingBakPagi" label="BAK" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.toiletingBabPagi" label="NGT" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.toiletingCateterPagi" label="Cateter" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Mobilitas : </span>
                          </div>
                          <div class="column is-4">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.mobilitasMiringKananPagi" label="Miring Kanan" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-4">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.mobilitasMiringKiriPagi" label="Miring Kiri" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField label="Lain-lain :">
                              <VInput type="text" class="input" placeholder="Lain-lain" v-model="data.mobilitasLainLainPagi"/>
                            </VField>
                          </div>
                        </div>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Personal Hygiene : </span>
                          </div>
                          <div class="column is-4">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.personalHygieneMandiPagi" label="Mandi Pagi" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-4">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.personalHygieneSikatGigiPagi" label="Sikat Gigi" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField label="Lain-lain :">
                              <VInput type="text" class="input" placeholder="Lain-lain" v-model="data.mobilitasLainLainPagi"/>
                            </VField>
                          </div>
                        </div>
                        <span class="bold-text"> Integritas Kulit : </span>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Riwayat di RS</span>
                          </div>
                          <div class="column is-4">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.riwayatRSTidakAdaPagi" label="Lesi Tidak Ada" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-4">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.riwayatRSAdaPagi" label="Ada" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Riwayat di Rumah</span>
                          </div>
                          <div class="column is-4">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.riwayatRumahTidakAdaPagi" label="Lesi Tidak Ada" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-4">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.riwayatRumahAdaPagi" label="Ada" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <span class="bold-text">Restrain Fisik : </span>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Lokasi</span>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.lokasiTakaPagi" label="Taka" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.lokasiTakiPagi" label="Taki" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.lokasiKakaPagi" label="Kaka" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.lokasiKakiPagi" label="Kaki" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Evaluasi</span>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.evaluasi1JamPagi" label="1 Jam" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.evaluasi2JamPagi" label="2 Jam" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.evaluasi4JamPagi" label="4 Jam" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Lesi</span>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.lesiTidakAdaPagi" label="Tidak Ada" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.lesiAdaPagi" label="Ada" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <span class="bold-text">Perawatan : </span>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanEttPagi" label="ETT" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanTubingPagi" label="Tubing Venti" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanTcPagi" label="TC" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanNgtPagi" label="NGT" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanDCPagi" label="DC" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanCvcPagi" label="CVC" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanCdlPagi" label="SDL" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanArteriPagi" label="Arteri Line" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanIvLinePagi" label="IV Line Perifer" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanColostomiPagi" label="Colostomi" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanDrainPagi" label="DRAIN" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <span class="bold-text">Resiko Jatuh : </span>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.resikoJatuhMorsePagi" label="Skala Morse" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.resikoJatuhSydneyPagi" label="Sydney : Rendah" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.resikoJatuhSedangPagi" label="Sedang" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.resikoJatuhTinggiPagi" label="Tinggi" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="column is-12">
                          <span class="bold-text"> Informasi Tambahan : </span>
                            <VField>
                            <VTextarea rows="3" placeholder="Informasi Tambahan....." v-model="data.informasiTambahanPagi"
                            ></VTextarea>
                          </VField>
                        </div>
                        <div class="column is-12">
                          <span class="bold-text"> Rekomendasi : </span>
                            <VField>
                            <VTextarea rows="3" placeholder="Rekomendasi....." v-model="data.rekomendasiPagi"
                            ></VTextarea>
                          </VField>
                        </div>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-6 text-center">
                            <h3 class="has-text-centered" style="font-size:12pt;font-weight: bold;">Pemberi Operan Pagi</h3><br>
                              <VField>
                                <img v-if="data.pemberiOperanPagi"
                                  :src="'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=' + (data.pemberiOperanPagi ? data.pemberiOperanPagi.label : '-')"><br>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="data.pemberiOperanPagi" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pemberi Operan Pagi..." class="mt-2"/>
                                </VControl>
                              </VField>
                          </div>
                          <div class="column is-6 text-center">
                            <h3 class="has-text-centered" style="font-size:12pt;font-weight: bold;">Penerima Operan Pagi</h3><br>
                              <VField>
                                <img v-if="data.penerimaOperanPagi"
                                  :src="'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=' + (data.penerimaOperanPagi ? data.penerimaOperanPagi.label : '-')"><br>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="data.penerimaOperanPagi" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pemberi Operan Pagi..." class="mt-2"/>
                                </VControl>
                              </VField>
                          </div>
                        </div>
                      </td>
                      <td width="30%">
                        <div class="column is-12">
                          <span class="bold-text"> KESADARAN : </span>
                          <VField>
                            <VInput type="text" class="input" placeholder="Kesadaran" v-model="data.KesadaranSiang"/>
                          </VField>
                        </div>
                        <span class="bold-text"> GCS : </span>
                        <div class="columns">
                          <div class="column is-1">
                            <h1 class="mb-3 emr" style="text-align: center">No</h1>
                          </div>
                          <div class="column is-one-quarter" style="text-align: center">
                            <h1 class="mb-3 emr">Parameter</h1>
                          </div>
                          <div class="column is-5" style="text-align: center">
                            <h1 class="mb-3 emr">Nilai</h1>
                          </div>
                        </div>
                        <div class="columns pb-4">
                          <div class="column is-1 pt-0" style="text-align: center">
                            <h1 class="mb-3 emr">1</h1>
                          </div>
                          <div class="column is-one-quarter pt-0" style="text-align: center">
                            <h1 class="mb-3 emr">E</h1>
                          </div>
                          <div class="column is-5" style="text-align: end">
                            <div class="columns is-multiline pb-5">
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="1"
                                      v-model="data.E1"
                                      label="1"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="2"
                                      v-model="data.E2"
                                      label="2"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="3"
                                      v-model="data.E3"
                                      label="3"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="4"
                                      v-model="data.E4"
                                      label="4"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="columns pb-4">
                          <div class="column is-1 pt-0" style="text-align: center">
                            <h1 class="mb-3 emr">2</h1>
                          </div>
                          <div class="column is-one-quarter pt-0" style="text-align: center">
                            <h1 class="mb-3 emr">M</h1>
                          </div>
                          <div class="column is-5" style="text-align: end">
                            <div class="columns is-multiline pb-5">
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="1"
                                      v-model="data.M1"
                                      label="1"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="2"
                                      v-model="data.M2"
                                      label="2"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="3"
                                      v-model="data.M3"
                                      label="3"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="4"
                                      v-model="data.M4"
                                      label="4"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="5"
                                      v-model="data.M5"
                                      label="5"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="6"
                                      v-model="data.M6"
                                      label="6"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="columns pb-4">
                          <div class="column is-1 pt-0" style="text-align: center">
                            <h1 class="mb-3 emr">3</h1>
                          </div>
                          <div class="column is-one-quarter pt-0" style="text-align: center">
                            <h1 class="mb-3 emr">V</h1>
                          </div>
                          <div class="column is-5" style="text-align: end">
                            <div class="columns is-multiline pb-5">
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="5"
                                      v-model="data.V5"
                                      label="5"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="4"
                                      v-model="data.V4"
                                      label="4"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="3"
                                      v-model="data.V3"
                                      label="3"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="2"
                                      v-model="data.V2"
                                      label="2"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="1"
                                      v-model="data.V1"
                                      label="1"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="column is-12">
                          <span class="bold-text"> Oksigenasi : </span>
                          <VField>
                            <VInput type="text" class="input" placeholder="Oksigenasi" v-model="data.oksigenasiSiang"/>
                          </VField>
                        </div>
                        <span class="bold-text"> Cairan : </span><br>
                        <span class="bold-text"> Perifer Line : </span>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Infus : </span>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.InfusTakaSiang" label="Taka" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.InfusTakaSiang" label="Taki" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.InfusKakaSiang" label="Kaka" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.InfusKakiSiang" label="Kaki" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Transfusi : </span>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.transfusiTakaSiang" label="Taka" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.transfusiTakaSiang" label="Taki" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.transfusiKakaSiang" label="Kaka" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.transfusiKakiSiang" label="Kaki" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <span class="bold-text">Central Line : </span>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">CVC : </span>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.cvcLekaSiang" label="LeKa" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.cvcLekiSiang" label="LeKi" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.cvcDakaSiang" label="DaKa" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.cvcDakiSiang" label="DaKi" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">CDL : </span>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.cdlLekaSiang" label="LeKa" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.cdlLekiSiang" label="LeKi" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.cdlDakaSiang" label="DaKa" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.cdlDakiSiang" label="DaKi" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <span class="bold-text">Makan/Minum : </span>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField label="Susu :">
                              <VInput type="text" class="input" placeholder="Susu" v-model="data.susuSiang"/>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField label="Air Putih :">
                              <VInput type="text" class="input" placeholder="Air Putih" v-model="data.airPutihSiang"/>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField label="Lain-lain :">
                              <VInput type="text" class="input" placeholder="Lain-lain" v-model="data.lainLainMinumSiang"/>
                            </VField>
                          </div>
                        </div>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Toileting : </span>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.toiletingBabSiang" label="BAB" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.toiletingBakSiang" label="BAK" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.toiletingBabSiang" label="NGT" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.toiletingCateterSiang" label="Cateter" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Mobilitas : </span>
                          </div>
                          <div class="column is-4">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.mobilitasMiringKananSiang" label="Miring Kanan" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-4">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.mobilitasMiringKiriSiang" label="Miring Kiri" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField label="Lain-lain :">
                              <VInput type="text" class="input" placeholder="Lain-lain" v-model="data.mobilitasLainLainSiang"/>
                            </VField>
                          </div>
                        </div>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Personal Hygiene : </span>
                          </div>
                          <div class="column is-4">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.personalHygieneMandiSiang" label="Mandi Siang" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-4">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.personalHygieneSikatGigiSiang" label="Sikat Gigi" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField label="Lain-lain :">
                              <VInput type="text" class="input" placeholder="Lain-lain" v-model="data.mobilitasLainLainSiang"/>
                            </VField>
                          </div>
                        </div>
                        <span class="bold-text"> Integritas Kulit : </span>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Riwayat di RS</span>
                          </div>
                          <div class="column is-4">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.riwayatRSTidakAdaSiang" label="Lesi Tidak Ada" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-4">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.riwayatRSAdaSiang" label="Ada" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Riwayat di Rumah</span>
                          </div>
                          <div class="column is-4">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.riwayatRumahTidakAdaSiang" label="Lesi Tidak Ada" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-4">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.riwayatRumahAdaSiang" label="Ada" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <span class="bold-text">Restrain Fisik : </span>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Lokasi</span>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.lokasiTakaSiang" label="Taka" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.lokasiTakiSiang" label="Taki" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.lokasiKakaSiang" label="Kaka" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.lokasiKakiSiang" label="Kaki" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Evaluasi</span>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.evaluasi1JamSiang" label="1 Jam" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.evaluasi2JamSiang" label="2 Jam" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.evaluasi4JamSiang" label="4 Jam" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Lesi</span>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.lesiTidakAdaSiang" label="Tidak Ada" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.lesiAdaSiang" label="Ada" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <span class="bold-text">Perawatan : </span>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanEttSiang" label="ETT" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanTubingSiang" label="Tubing Venti" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanTcSiang" label="TC" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanNgtSiang" label="NGT" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanDCSiang" label="DC" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanCvcSiang" label="CVC" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanCdlSiang" label="SDL" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanArteriSiang" label="Arteri Line" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanIvLineSiang" label="IV Line Perifer" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanColostomiSiang" label="Colostomi" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanDrainSiang" label="DRAIN" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <span class="bold-text">Resiko Jatuh : </span>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.resikoJatuhMorseSiang" label="Skala Morse" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.resikoJatuhSydneySiang" label="Sydney : Rendah" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.resikoJatuhSedangSiang" label="Sedang" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.resikoJatuhTinggiSiang" label="Tinggi" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="column is-12">
                          <span class="bold-text"> Informasi Tambahan : </span>
                            <VField>
                            <VTextarea rows="3" placeholder="Informasi Tambahan....." v-model="data.informasiTambahanSiang"
                            ></VTextarea>
                          </VField>
                        </div>
                        <div class="column is-12">
                          <span class="bold-text"> Rekomendasi : </span>
                            <VField>
                            <VTextarea rows="3" placeholder="Rekomendasi....." v-model="data.rekomendasiSiang"
                            ></VTextarea>
                          </VField>
                        </div>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-6 text-center">
                            <h3 class="has-text-centered" style="font-size:12pt;font-weight: bold;">Pemberi Operan Siang</h3><br>
                              <VField>
                                <img v-if="data.pemberiOperanSiang"
                                  :src="'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=' + (data.pemberiOperanSiang ? data.pemberiOperanSiang.label : '-')"><br>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="data.pemberiOperanSiang" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pemberi Operan Siang..." class="mt-2"/>
                                </VControl>
                              </VField>
                          </div>
                          <div class="column is-6 text-center">
                            <h3 class="has-text-centered" style="font-size:12pt;font-weight: bold;">Penerima Operan Siang</h3><br>
                              <VField>
                                <img v-if="data.penerimaOperanSiang"
                                  :src="'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=' + (data.penerimaOperanSiang ? data.penerimaOperanSiang.label : '-')"><br>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="data.penerimaOperanSiang" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pemberi Operan Siang..." class="mt-2"/>
                                </VControl>
                              </VField>
                          </div>
                        </div>
                      </td>
                      <td width="30%">
                        <div class="column is-12">
                          <span class="bold-text"> KESADARAN : </span>
                          <VField>
                            <VInput type="text" class="input" placeholder="Kesadaran" v-model="data.KesadaranMalam"/>
                          </VField>
                        </div>
                        <span class="bold-text"> GCS : </span>
                        <div class="columns">
                          <div class="column is-1">
                            <h1 class="mb-3 emr" style="text-align: center">No</h1>
                          </div>
                          <div class="column is-one-quarter" style="text-align: center">
                            <h1 class="mb-3 emr">Parameter</h1>
                          </div>
                          <div class="column is-5" style="text-align: center">
                            <h1 class="mb-3 emr">Nilai</h1>
                          </div>
                        </div>
                        <div class="columns pb-4">
                          <div class="column is-1 pt-0" style="text-align: center">
                            <h1 class="mb-3 emr">1</h1>
                          </div>
                          <div class="column is-one-quarter pt-0" style="text-align: center">
                            <h1 class="mb-3 emr">E</h1>
                          </div>
                          <div class="column is-5" style="text-align: end">
                            <div class="columns is-multiline pb-5">
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="1"
                                      v-model="data.EMalam1"
                                      label="1"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="2"
                                      v-model="data.EMalam2"
                                      label="2"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="3"
                                      v-model="data.EMalam3"
                                      label="3"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="4"
                                      v-model="data.EMalam4"
                                      label="4"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="columns pb-4">
                          <div class="column is-1 pt-0" style="text-align: center">
                            <h1 class="mb-3 emr">2</h1>
                          </div>
                          <div class="column is-one-quarter pt-0" style="text-align: center">
                            <h1 class="mb-3 emr">M</h1>
                          </div>
                          <div class="column is-5" style="text-align: end">
                            <div class="columns is-multiline pb-5">
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="1"
                                      v-model="data.MMalam1"
                                      label="1"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="2"
                                      v-model="data.MMalam2"
                                      label="2"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="3"
                                      v-model="data.MMalam3"
                                      label="3"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="4"
                                      v-model="data.MMalam4"
                                      label="4"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="5"
                                      v-model="data.MMalam5"
                                      label="5"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="6"
                                      v-model="data.MMalam6"
                                      label="6"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="columns pb-4">
                          <div class="column is-1 pt-0" style="text-align: center">
                            <h1 class="mb-3 emr">3</h1>
                          </div>
                          <div class="column is-one-quarter pt-0" style="text-align: center">
                            <h1 class="mb-3 emr">V</h1>
                          </div>
                          <div class="column is-5" style="text-align: end">
                            <div class="columns is-multiline pb-5">
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="5"
                                      v-model="data.VMalam5"
                                      label="5"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="4"
                                      v-model="data.VMalam4"
                                      label="4"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="3"
                                      v-model="data.VMalam3"
                                      label="3"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="2"
                                      v-model="data.VMalam2"
                                      label="2"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4 pt-0">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox
                                      class="p-0"
                                      :true-value="1"
                                      v-model="data.VMalam1"
                                      label="1"
                                      color="primary"
                                    />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="column is-12">
                          <span class="bold-text"> Oksigenasi : </span>
                          <VField>
                            <VInput type="text" class="input" placeholder="Oksigenasi" v-model="data.oksigenasiMalam"/>
                          </VField>
                        </div>
                        <span class="bold-text"> Cairan : </span><br>
                        <span class="bold-text"> Perifer Line : </span>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Infus : </span>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.InfusTakaMalam" label="Taka" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.InfusTakaMalam" label="Taki" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.InfusKakaMalam" label="Kaka" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.InfusKakiMalam" label="Kaki" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Transfusi : </span>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.transfusiTakaMalam" label="Taka" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.transfusiTakaMalam" label="Taki" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.transfusiKakaMalam" label="Kaka" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.transfusiKakiMalam" label="Kaki" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <span class="bold-text">Central Line : </span>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">CVC : </span>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.cvcLekaMalam" label="LeKa" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.cvcLekiMalam" label="LeKi" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.cvcDakaMalam" label="DaKa" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.cvcDakiMalam" label="DaKi" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">CDL : </span>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.cdlLekaMalam" label="LeKa" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.cdlLekiMalam" label="LeKi" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.cdlDakaMalam" label="DaKa" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.cdlDakiMalam" label="DaKi" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <span class="bold-text">Makan/Minum : </span>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField label="Susu :">
                              <VInput type="text" class="input" placeholder="Susu" v-model="data.susuMalam"/>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField label="Air Putih :">
                              <VInput type="text" class="input" placeholder="Air Putih" v-model="data.airPutihMalam"/>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField label="Lain-lain :">
                              <VInput type="text" class="input" placeholder="Lain-lain" v-model="data.lainLainMinumMalam"/>
                            </VField>
                          </div>
                        </div>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Toileting : </span>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.toiletingBabMalam" label="BAB" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.toiletingBakMalam" label="BAK" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.toiletingBabMalam" label="NGT" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.toiletingCateterMalam" label="Cateter" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Mobilitas : </span>
                          </div>
                          <div class="column is-4">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.mobilitasMiringKananMalam" label="Miring Kanan" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-4">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.mobilitasMiringKiriMalam" label="Miring Kiri" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField label="Lain-lain :">
                              <VInput type="text" class="input" placeholder="Lain-lain" v-model="data.mobilitasLainLainMalam"/>
                            </VField>
                          </div>
                        </div>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Personal Hygiene : </span>
                          </div>
                          <div class="column is-4">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.personalHygieneMandiMalam" label="Mandi Malam" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-4">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.personalHygieneSikatGigiMalam" label="Sikat Gigi" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField label="Lain-lain :">
                              <VInput type="text" class="input" placeholder="Lain-lain" v-model="data.mobilitasLainLainMalam"/>
                            </VField>
                          </div>
                        </div>
                        <span class="bold-text"> Integritas Kulit : </span>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Riwayat di RS</span>
                          </div>
                          <div class="column is-4">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.riwayatRSTidakAdaMalam" label="Lesi Tidak Ada" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-4">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.riwayatRSAdaMalam" label="Ada" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Riwayat di Rumah</span>
                          </div>
                          <div class="column is-4">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.riwayatRumahTidakAdaMalam" label="Lesi Tidak Ada" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-4">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.riwayatRumahAdaMalam" label="Ada" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <span class="bold-text">Restrain Fisik : </span>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Lokasi</span>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.lokasiTakaMalam" label="Taka" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.lokasiTakiMalam" label="Taki" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.lokasiKakaMalam" label="Kaka" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.lokasiKakiMalam" label="Kaki" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Evaluasi</span>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.evaluasi1JamMalam" label="1 Jam" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.evaluasi2JamMalam" label="2 Jam" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.evaluasi4JamMalam" label="4 Jam" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                            <span class="bold-text">Lesi</span>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.lesiTidakAdaMalam" label="Tidak Ada" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.lesiAdaMalam" label="Ada" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <span class="bold-text">Perawatan : </span>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanEttMalam" label="ETT" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanTubingMalam" label="Tubing Venti" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanTcMalam" label="TC" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanNgtMalam" label="NGT" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanDCMalam" label="DC" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanCvcMalam" label="CVC" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanCdlMalam" label="SDL" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanArteriMalam" label="Arteri Line" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanIvLineMalam" label="IV Line Perifer" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanColostomiMalam" label="Colostomi" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.perawatanDrainMalam" label="DRAIN" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <span class="bold-text">Resiko Jatuh : </span>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.resikoJatuhMorseMalam" label="Skala Morse" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.resikoJatuhSydneyMalam" label="Sydney : Rendah" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.resikoJatuhSedangMalam" label="Sedang" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" v-model="data.resikoJatuhTinggiMalam" label="Tinggi" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="column is-12">
                          <span class="bold-text"> Informasi Tambahan : </span>
                            <VField>
                            <VTextarea rows="3" placeholder="Informasi Tambahan....." v-model="data.informasiTambahanMalam"
                            ></VTextarea>
                          </VField>
                        </div>
                        <div class="column is-12">
                          <span class="bold-text"> Rekomendasi : </span>
                            <VField>
                            <VTextarea rows="3" placeholder="Rekomendasi....." v-model="data.rekomendasiMalam"
                            ></VTextarea>
                          </VField>
                        </div>
                        <div class="columns is-multiline pb-5">
                          <div class="column is-6 text-center">
                            <h3 class="has-text-centered" style="font-size:12pt;font-weight: bold;">Pemberi Operan Malam</h3><br>
                              <VField>
                                <img v-if="data.pemberiOperanMalam"
                                  :src="'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=' + (data.pemberiOperanMalam ? data.pemberiOperanMalam.label : '-')"><br>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="data.pemberiOperanMalam" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pemberi Operan Malam..." class="mt-2"/>
                                </VControl>
                              </VField>
                          </div>
                          <div class="column is-6 text-center">
                            <h3 class="has-text-centered" style="font-size:12pt;font-weight: bold;">Penerima Operan Malam</h3><br>
                              <VField>
                                <img v-if="data.penerimaOperanMalam"
                                  :src="'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=' + (data.penerimaOperanMalam ? data.penerimaOperanMalam.label : '-')"><br>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="data.penerimaOperanMalam" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pemberi Operan Malam..." class="mt-2"/>
                                </VControl>
                              </VField>
                          </div>
                        </div>
                      </td>
                      <td width="5%" class="td-fkprj" style="vertical-align: inherit;">
                        <div class="column">
                          <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItemObatRawatIGD()"
                            color="info" v-tooltip.bubble="'Tambah '">
                          </VIconButton>
                          <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                            @click="removeItemObatRawatIGD(index)" color="danger">
                          </VIconButton>
                        </div>
                      </td>
                    </tr>
                  </tbody>

                </table>
              </div><br><br>

            <table class="tg">
              <thead>
                <tr>
                  <th rowspan="2" style="text-align:center;font-weight: bold;font-size: 18px;" class="tg-0lax text-center"> 
                     Shift
                  </th>
                  <th colspan="10" style="text-align:center;font-weight: bold;font-size: 18px;" class="tg-0lax text-center"> 
                      CEKLIS BUNDLES VAP
                      <div class="column is-6">
                      <span class="label-icu">Tanggal Pemasangan Ventilator</span>
                      <VDatePicker v-model="input.tanggalCeklisVap" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                          <VField class="pb-0 pt-3">
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </div>
                  </th>
                </tr>
                <tr style="font-size: 15px;">
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Posisi Kepala 30° - 40°
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Oral hygiene 4 - 6 jam 
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Sikat gigi setiap 12 jam 
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Suction manajemen sekresi
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Penggunaan APD saaat reksresi
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Pengkajian setiap hari  terhadap sedasi dan extubasi
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Control cuff Pressure
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Jarak tempat tidur lebih dari 2 meter
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Propilaktik DVT
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Propilaktik peptic ulcer
                    </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>P</td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesVapPKepala" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesOralPHygiene" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesVapPSikatGigi" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesVapPSuction" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesVapPPenggunaanApd" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesVapPPengkajianSetiaphari" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesVapPControl" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesVapPJaraktempatTidur" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesVapPPropliatikDvt" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesVapPPropliatikpeptic" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>S</td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesVapSKepala" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesOralPHygiene" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesVapSSikatGigi" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesVapSSuction" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesVapSPenggunaanApd" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesVapSPengkajianSetiaphari" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesVapSControl" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesVapSJaraktempatTidur" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesVapSPropliatikDvt" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesVapSPropliatikpeptic" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>M</td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesVapMKepala" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesOralPHygiene" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesVapMSikatGigi" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesVapMSuction" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesVapMPenggunaanApd" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesVapMPengkajianSetiaphari" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesVapMControl" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesVapMJaraktempatTidur" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesVapMPropliatikDvt" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.bundlesVapMPropliatikpeptic" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table><br><br>

            <table class="tg">
              <thead>
                <tr>
                  <th rowspan="2" style="text-align:center;font-weight: bold;font-size: 18px;" class="tg-0lax text-center"> 
                     Shift
                  </th>
                  <th colspan="11" style="text-align:center;font-weight: bold;font-size: 18px;" class="tg-0lax text-center"> 
                      CEKLIS BUNDLES ISK
                      <div class="column is-6">
                      <span class="label-icu">Tanggal Pemasangan DC</span>
                      <VDatePicker v-model="input.tanggalCeklisDc" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                          <VField class="pb-0 pt-3">
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </div>
                  </th>
                </tr>
                <tr style="font-size: 15px;">
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Pemasangan Sesuai indikasi
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Apakah menggunakan APD yang tepat 
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Pemasangan menggunakan alat steril
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Hand Hygiene
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Pembersihan dari meatus Uretha ke pangkal cateter dengan menggunakan naci atau aqua 
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Kateter Sesegeramungkin dilepas dalam 48 jam pertama kecuali ada indikasi medis tertentu
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Pengisian balon seusai pentunjuk produk
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Fiksasi kateter dengan pleter
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">labeling pada cateter 
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Urine bag menggantung tidak menyentuh lantai 
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Jika kateter terpasang > 48 jam, monitoring setiap hari menjadi : demam > 38° C Hipotermi > 36° C Nyeri Suprafubis Terdapat Plak putih pada urin/Biobim                 
                    </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>P</td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapPPemasanganSesuai" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapPMenggunakanApdTepat" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapPPemasanganMenggunakanSteril" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapPHadnHygiene" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapPPembersihanMeatus" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapPKateterSesegeraMungkinDilepas" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapPPengisianBalon" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapPFiksasiKateter" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapPLabeleingKateter" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapPUrineBag" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapPJikaKateterterpasangLebih48jam" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>S</td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapSPemasanganSesuai" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapSMenggunakanApdTepat" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapSPemasanganMenggunakanSteril" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapSHadnHygiene" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapSPembersihanMeatus" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapSKateterSesegeraMungkinDilepas" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapSPengisianBalon" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapSFiksasiKateter" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapSLabeleingKateter" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapSUrineBag" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapSJikaKateterterpasangLebih48jam" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>M</td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapMPemasanganSesuai" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapMMenggunakanApdTepat" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapMPemasanganMenggunakanSteril" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapMHadnHygiene" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapMPembersihanMeatus" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapMKateterSesegeraMungkinDilepas" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapMPengisianBalon" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapMFiksasiKateter" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapMLabeleingKateter" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapMUrineBag" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iskVapMJikaKateterterpasangLebih48jam" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table><br><br>

            <table class="tg">
              <thead>
                <tr>
                  <th rowspan="2" style="text-align:center;font-weight: bold;font-size: 18px;" class="tg-0lax text-center"> 
                     Shift
                  </th>
                  <th colspan="11" style="text-align:center;font-weight: bold;font-size: 18px;" class="tg-0lax text-center"> 
                      CEKLIS BUNDLES IADP
                      <div class="column is-6">
                      <span class="label-icu">Tanggal Pemasangan Kateter</span>
                      <VDatePicker v-model="input.tanggalCeklisIadp" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                          <VField class="pb-0 pt-3">
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </div>
                  </th>
                </tr>
                <tr style="font-size: 15px;">
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Melakukan Hand Hygiene
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Menggunakan APD lengkap dan sarung tangan steril
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Area pemasangan diberi duk steril yang luas
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Pembersihan kulit area pemasangan dengan chlorhexxidine 2% atau 4%
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Alat yang digunakan steril  
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Lokasi pemasangan sesuai 
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Labeling pada kateter 
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Set Infuse diganti setiap 72 jam
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Slang Infuse bekas pemberian lemak dan protein diganti setiap 24 jam 
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Perawatan lokasi insersi setiap hari jika kotor 
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Port injeksi di alcoholise sebelum injeksi                 
                    </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>P</td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapPMelakukanHandhygiene" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapPMenggunakanApdLengkap" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapPAreaPemasangan" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapPPembersihanKulit" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapPAlatyangDigunakanSteril" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapPLokasiPemsanganSesuai" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapPLabelingKateter" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapPSetInfuse" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapPSlangInfuse" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapPPerawatanLokasi" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapPPortinjeksi" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>S</td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapSMelakukanHandhygiene" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapSMenggunakanApdLengkap" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapSAreaPemasangan" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapSPembersihanKulit" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapSAlatyangDigunakanSteril" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapSLokasiPemsanganSesuai" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapSLabelingKateter" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapSSetInfuse" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapSSlangInfuse" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapSPerawatanLokasi" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapSPortinjeksi" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>M</td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapMMelakukanHandhygiene" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapMMenggunakanApdLengkap" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapMAreaPemasangan" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapMPembersihanKulit" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapMAlatyangDigunakanSteril" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapMLokasiPemsanganSesuai" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapMLabelingKateter" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapMSetInfuse" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapMSlangInfuse" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapMPerawatanLokasi" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.iadpVapMPortinjeksi" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table><br><br>

            <table class="tg">
              <thead>
                <tr>
                  <th rowspan="2" style="text-align:center;font-weight: bold;font-size: 18px;" class="tg-0lax text-center"> 
                     Shift
                  </th>
                  <th colspan="11" style="text-align:center;font-weight: bold;font-size: 18px;" class="tg-0lax text-center"> 
                      CEKLIS PENCEGAHAN IDO
                      <div class="column is-6">
                      <span class="label-icu">Tanggal Pemasangan Kateter</span>
                      <VDatePicker v-model="input.tanggalCeklisIdo" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                          <VField class="pb-0 pt-3">
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </div>
                  </th>
                </tr>
                <tr style="font-size: 15px;">
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Tidak Melakukan pencukuran rambut kecuali bila menggangu jalannya operasi
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Pencukuran dilakukan sesegera mungkin sebelum tindakan operasi 
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Penggunaan propilaksis Antibiotika satu jam sebelum insisi 
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Gula darah terkontrol sebelum operasi dibawah 200 ml/dl
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Intra Operasi (Poin 1-9) di kamar operasi  
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Temperatur tubuh dalam kondisi normal sebelum operasi 
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Kondisi sore dan pagi menggunakan antiseptik 
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Pemberian nutrisi yang sesuai 
                    </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>P</td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.idoPTidakMelakukanPencukuran" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.idoPPencukuranDilakukan" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.idoPPenggunaanPropilaksis" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.idoPGulaDarahTerkontrol" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.idoPIntroOperasi" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.idoPtemperatuTubuh" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.idoPMandiSoredanPagi" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.idoPPemberianNutrisi" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>S</td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.idoSTidakMelakukanPencukuran" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.idoSPencukuranDilakukan" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.idoSPenggunaanPropilaksis" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.idoSGulaDarahTerkontrol" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.idoSIntroOperasi" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.idoStemperatuTubuh" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.idoSMandiSoredanPagi" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.idoSPemberianNutrisi" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>M</td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.idoMTidakMelakukanPencukuran" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.idoMPencukuranDilakukan" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.idoMPenggunaanPropilaksis" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.idoMGulaDarahTerkontrol" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.idoMIntroOperasi" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.idoMtemperatuTubuh" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.idoMMandiSoredanPagi" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VCheckbox class="p-0" v-model="input.idoMPemberianNutrisi" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table><br><br>

            <table class="tg">
              <thead>
                <tr>
                  <th rowspan="2" style="text-align:center;font-weight: bold;font-size: 18px;" class="tg-0lax text-center"> 
                     TINDAKAN
                  </th>
                  <th colspan="3" style="text-align:center;font-weight: bold;font-size: 18px;" class="tg-0lax text-center"> 
                      PEMASANGAN KE
                  </th>
                  <th rowspan="2" style="text-align:center;font-weight: bold;font-size: 18px;" class="tg-0lax text-center"> 
                     ALASAN
                  </th>
                  <th colspan="3" style="text-align:center;font-weight: bold;font-size: 18px;" class="tg-0lax text-center"> 
                      HARI KE
                  </th>
                </tr>
                <tr style="font-size: 15px;">
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">P
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">S
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">M 
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">P
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">S
                    </th>
                    <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">M 
                    </th>
                </tr>
              </thead>
              <tbody>
               <tr>
                  <td>ETT</td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.ettPPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.ettSPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.ettMPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.ettAlasan" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.ettPhariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.ettShariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.ettMhariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
               </tr>
               <tr>
                  <td>TUBING VENTI</td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.tubingVentiPPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.tubingVentiSPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.tubingVentiMPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.tubingVentiAlasan" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.tubingVentiPhariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.tubingVentiShariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.tubingVentiMhariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>TC</td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.tcPPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.tcSPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.tcMPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.tcAlasan" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.tcPhariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.tcShariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.tcMhariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>NGT</td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.ngtPPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.ngtSPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.ngtMPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.ngtAlasan" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.ngtPhariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.ngtShariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.ngtMhariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>IV LINE PERIFER</td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.ivLinePPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.ivLineSPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.ivLineMPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.ivLineAlasan" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.ivLinePhariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.ivLineShariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.ivLineMhariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>DC</td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.dcPPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.dcSPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.dcMPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.dcAlasan" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.dcPhariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.dcShariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.dcMhariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>CVC</td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.cvcPPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.cvcSPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.cvcMPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.cvcAlasan" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.cvcPhariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.cvcShariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.cvcMhariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>CDL</td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.cdlPPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.cdlSPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.cdlMPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.cdlAlasan" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.cdlPhariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.cdlShariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.cdlMhariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>DRAIN</td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.drainPPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.drainSPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.drainMPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.drainAlasan" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.drainPhariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.drainShariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.drainMhariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>COLOSTOMI</td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.colostomiPPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.colostomiSPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.colostomiMPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.colostomiAlasan" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.colostomiPhariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.colostomiShariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.colostomiMhariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>ARTERI LINE</td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.arteriLinePPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.arteriLineSPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.arteriLineMPemasanganKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.arteriLineAlasan" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.arteriLinePhariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.arteriLineShariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column is-12">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.arteriLineMhariKe" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>

          <div class="column pl-0 pr-0 mt-5">
            <hr class="mt-2 ml-0 mr-0 mb-0" style="border-top-style: dashed;border-color: var(--fade-grey-dark-2);">
            <div class="columns is-multiline pt-5">
              <div class="column is-4">
              </div>
              <div class="column is-4 text-center">
                <h3 class="has-text-centered" style="font-size:12pt;font-weight: bold;">Verifikasi DPJP</h3><br>
                  <VField>
                    <img v-if="input.dokterDPJP"
                      :src="'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=' + (input.dokterDPJP ? input.dokterDPJP.label : '-')"><br>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.dokterDPJP" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Verifikasi DPJP..." class="mt-2"/>
                    </VControl>
                  </VField>
              </div>
              <div class="column is-4">
              </div>
            </div>
          </div>
          </p>



          <p v-else-if="activeValue === 'chart'">
            <div class="column is-12">
              <VCard style="border-radius: 16px;">
                <highcharts :options="chartOptions2"></highcharts>
                <!-- <Chart type="line" :data="chartData" :options="chartOptions" :height="100" class="h-30rem" /> -->
              </VCard>
            </div>


          </p>

        </template>
      </VTabs>
    </div>
  </div>

  <VModal is="form" :open="formTambahAlat" title="Form " size="small" actions="right" @close="formTambahAlat = false">
    <template #content>
      <div class="modal-form">
        <div class="field">
          <label>Nama Alat</label>
          <div class="control">
            <input type="text" class="input" placeholder="Nama Alat" v-model="item.namaAlat" />
          </div>
        </div>
        <div class="field">
          <label>Pasang</label>
          <div class="control">
            <VDatePicker class="pt-3" v-model="item.pasang" color="green" trim-weeks mode="date" :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }" class="pb-0">
                <VField>
                  <VControl icon="feather:calendar">
                    <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                      class="is-rounded_Z" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
        </div>
        <div class="field">
          <label>Cabut</label>
          <div class="control">
            <VDatePicker class="pt-3" v-model="item.cabut" color="green" trim-weeks mode="date" :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }" class="pb-0">
                <VField>
                  <VControl icon="feather:calendar">
                    <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                      class="is-rounded_Z" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
        </div>
        <div class="field">
          <label>Hari Ke</label>
          <div class="control">
            <input type="text" class="input" v-model="item.hariKe" />
          </div>
        </div>
      </div>
    </template>
    <template #action>
      <VButton type="submit" color="primary" raised @click="addtoSourceAlat(item)">Simpan</VButton>
    </template>
  </VModal>
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
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';   // optional
import Chart from 'primevue/chart';
import Row from 'primevue/row';
import Fieldset from 'primevue/fieldset';
import * as ICU from '../page-emr-plugins/monitoring-iccu'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import InputText from 'primevue/inputtext';
import { alatBantu } from '../page-emr-plugins/asesmen-awal-keperawatan-rawat-inap'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let waktu = ref(ICU.waktu())
let listDescripWaktu = ref(ICU.listDescripWaktu())
let description = ref(ICU.dataTableDescrip())
let output = ref(ICU.dataOutput())
let statusRespirasi = ref(ICU.statusRespirasi())
let hemodinamik = ref(ICU.Hemodinamik())
let tableBalansCairan = ref(ICU.tableBalansCairan())
let tableIntake = ref(ICU.tableIntake())
let titleMore = ref(ICU.titleMore())

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
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const isLoadingHolder: any = ref(false)
const medikasi = ref([]);
const parenteral = ref([]);
const labkritis = ref([]);
const d_Obat: any = ref([])
const d_Diagnosa: any = ref([])
const d_AlatBantu: any = ref([])
const d_CRT: any = ref([])
const d_Dokter: any = ref([])
const d_Pegawai: any = ref([])
const formTambahAlat: any = ref(false)
const sourceData = ref();
const showFormInput: any = ref(false)
const dataSourceAlatInvasif: any = ref([])
const item: any = reactive({})
const filter: any = ref('')
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const d_produk: any = ref([])
const input: any = ref({
  tanggal: new Date(),  
  tanggalCeklisVap: new Date(),  
  tanggalCeklisDc: new Date(),  
  tanggalCeklisIadp: new Date(),  
  tanggalCeklisIdo: new Date(),  
  tglVerifikasi: new Date(),  
  detailObatPerawatanIGD: [{ no: 1 }],
})

const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const filteredList = computed(() => {
  if (!filter.value) {
    // return listVital.value
  }

  return listVital.value.filter((items: any) => {
    return (
      items.user_input.namalengkap.match(new RegExp(filter.value, 'i'))
    )
  })
})

const add = () => {
  showFormInput.value = true
}

const loadRiwayat = () => {
  isLoadingHolder.value = true
  useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=MonitoringICCU&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan
        description.value = response[0].monitoringICU
        output.value = response[0].output
        medikasi.value = response[0].medikasi
        parenteral.value = response[0].parenteral
        labkritis.value = response[0].labkritis
        dataSourceAlatInvasif.value = response[0].pemasanganAlat
        tableBalansCairan.value = response[0].balansCairan
        tableIntake.value = response[0].Intake
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        chartHigh(response[0].monitoringICU)
      }
    })
  isLoadingHolder.value = false
}

const onCellEditComplete = (event: any) => {
  let { data, newValue, field, newData } = event;
  console.log(event)
  if (newValue != undefined) {
    data[field] = newValue
    // console.log(event)
  }

}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  object.monitoringICU = description.value
  object.output = output.value
  object.medikasi = medikasi.value
  object.parenteral = parenteral.value
  object.labkritis = labkritis.value
  object.balansCairan = tableBalansCairan.value
  object.intake = tableIntake.value
  object.pemasanganAlat = dataSourceAlatInvasif.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': "MonitoringICCU",
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
      loadRiwayat()
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const addNewItemObatRawatIGD = () => {
  input.value.detailObatPerawatanIGD.push({
    no: input.value.detailObatPerawatanIGD[input.value.detailObatPerawatanIGD.length - 1].no + 1,
  });
}
const removeItemObatRawatIGD = (index: any) => {
  input.value.detailObatPerawatanIGD.splice(index, 1)
}

const addNewItem = () => {
  medikasi.value.push({
    namaDokter: "",
    namaObat: "",
    aturanPakai: "",
    tanggalMulai: "",
    tanggalStop: "",
    verifikasiApoteker: "",
    no: medikasi.value.length + 1,
  });
}

const removeItem = (index: any) => {
  medikasi.value.splice(index, 1)
}

const addNewItemParenteral = () => {
  parenteral.value.push({
    namaParenteral: "",
    dosis: "",
    no: parenteral.value.length + 1,
  });
}

const removeItemParenteral = (index: any) => {
  parenteral.value.splice(index, 1)
}

const addNewItemLab = () => {
  labkritis.value.push({
    namaLabKritis: "",
    dosis: "",
    no: labkritis.value.length + 1,
  });
}

const removeItemLab = (index: any) => {
  labkritis.value.splice(index, 1)
}

const fetchProduk = async (filter: any) => {
  const response = await useApi().get(
    `/farmasi/dropdown-obat?namaproduk=${filter.query}&limit=10`)
  d_produk.value = response

}

const addtoSourceAlat = (e: any) => {
  if (!e.namaAlat) {
    return H.alert('error', 'Nama Alat Harus diisi')
  }
  if (!e.pasang) {
    return H.alert('error', 'Tanggal Pasang Harus diisi')
  }
  if (!e.cabut) {
    return H.alert('error', 'Tanggal Cabut Harus diisi')
  }
  if (!e.hariKe) {
    return H.alert('error', 'Hari Ke Harus diisi')
  }
  if (e.no) {
    dataSourceAlatInvasif.value.forEach((element: any) => {
      if (e.no == element.no) {
        element.alatInvasif = e.namaAlat
        element.tglPasang = e.pasang
        element.tglCabut = e.cabut
        element.hari = e.hariKe
      }
    });
  } else {
    dataSourceAlatInvasif.value.push({
      no: dataSourceAlatInvasif.value.length + 1,
      alatInvasif: e.namaAlat,
      tglPasang: e.pasang,
      tglCabut: e.cabut,
      hari: e.hariKe
    })
  }
  formTambahAlat.value = false
  clear()
}

const editItemAlat = (e: any) => {
  showFormPemasanganAlat(e)
  console.log(e)
}

const deleteItemAlat = (i: any) => {
  dataSourceAlatInvasif.value.splice(i, 1)
}

const clear = () => {
  delete item.namaAlat
  delete item.tglPasang
  delete item.tglCabut
  delete item.hariKe
}

const fetchAlatBantu = () => {
  d_AlatBantu.value = [{ label: 'NC' }, { label: 'SM' }, { label: 'RM' }, { label: 'NRM' }, { label: 'HFNC' }, { label: 'Ventilator' }, { label: 'NGT' }]
}

const fetchCRT = () => {
  d_CRT.value = [{ label: "< 3" }, { label: "> 3" }]
}

const fetchDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/diagnosa_m?select=id,namadiagnosa&param_search=namadiagnosa&query=${filter.query}&limit=10`
  )
  d_Diagnosa.value = response
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.norm = props.pasien.nocm
  input.value.namaPasien = props.pasien.namapasien
  input.value.dokterDPJP = props.registrasi.dokter
  await useApi()
    .get(
      'emr/auto-fill?norec_pd=' +
        props.registrasi.norec_pd +
        '&collection=VitalSign' +
        '&field=beratBadan,tinggiBadan'
    )
    .then((response) => {
      if (response != null) {
        input.value.beratBadan = response.beratBadan
        input.value.tinggiBadan = response.tinggiBadan
      }
    })
}

const showFormPemasanganAlat = (e: any) => {
  item.no = e.no ? e.no : ''
  item.namaAlat = e.alatInvasif ? e.alatInvasif : '',
    item.pasang = e.tglPasang ? e.tglPasang : '',
    item.cabut = e.tglCabut ? e.tglCabut : '',
    item.hariKe = e.hari ? e.hari : '',
    formTambahAlat.value = true
}


let chartOptions2 = reactive({
  chart: {
    type: 'spline',
  },
  title: {
    text: ''
  },
  credits: {
    enabled: false
  },
  xAxis: {
    categories: []
  },
  yAxis: {
    title: {
      text: 'Jumlah'
    }
  },
  legend: { enabled: true },
  plotOptions: {
    line: {
      dataLabels: {
        enabled: true
      },
      enableMouseTracking: false
    },
    spline: {
      dataLabels: {
        enabled: true,
        // style: {
        //   fontSize: '20px'
        // },
      },
      enableMouseTracking: false
    }
  },
  series: []
});



const chartHigh = (data: any) => {

  let seriesDiastolik:any = []
  let seriesSistolik:any = []
  let seriesNadi:any = []
  let seriesPernafasan:any = []
  let seriesSuhu:any = []
    
  data.forEach((element:any) => {
    if(element.deskripsi == 'NBP'){
        const valueTensi = Object.values((element));
        valueTensi.forEach((item:any)=>{
          if(item.includes("/")){
             let data2 = item.split('/');
             seriesSistolik.push(parseFloat(data2[0]))
             seriesDiastolik.push(parseFloat(data2[1]))
          }
        })
    }
    if(element.deskripsi == 'HR'){
        const valueNadi = Object.values((element));
        valueNadi.forEach((item:any)=>{
          if(!isNaN(item)){
            seriesNadi.push(parseFloat(item))
          }
        })
    }
    if(element.deskripsi == 'RR'){
        const valuePernapasan = Object.values((element));
        valuePernapasan.forEach((item:any)=>{
          if(!isNaN(item)){
            seriesPernafasan.push(parseFloat(item))
          }
        })
    }
    if(element.deskripsi == 'T'){
        const valueSuhu = Object.values((element));
        valueSuhu.forEach((item:any)=>{
          if(!isNaN(item)){
            seriesSuhu.push(parseFloat(item))
          }
        })
    }
  });
  chartOptions2.xAxis.categories = waktu.value
  chartOptions2.series = [
    {
      name: 'Sistolik',
      color: 'red',
      lineWidth: 3,
      data : seriesSistolik
    },
    {
      name: 'Diastolik',
      color: 'red',
      lineWidth: 3,
      data : seriesDiastolik
    },
    {
      name : 'Nadi',
      color : 'blue',
      lineWidth: 3,
      data : seriesNadi
    },
    {
      name : 'Pernapasan',
      color : 'green',
      lineWidth: 3,
      data : seriesPernafasan
    },
    {
      name : 'Suhu',
      color : 'orange',
      lineWidth: 3,
      data : seriesSuhu
    },
  ]


}

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

let kesadaranSiang = ref([
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

setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
// @import '/@src/scss/custom/timeline-css';
// @import '/@src/scss/custom/timeline-css';

// .p-column-header-content {
//   justify-content: space-evenly;
// }

// .p-fieldset-legend {
//   margin-left: 15px;
// }

// .p-rowgroup-header {
//   background: beige !important;
// }

.label-icu {
  font-weight: 500;
}

.list-widget {
    @include vuero-l-card;

    &.is-straight {
        @include vuero-s-card;
    }

    ul {
        li {
            &:not(:last-child) {
                margin-bottom: 12px;
            }

            a {
                font-family: var(--font);
                display: flex;
                justify-content: space-between;
                color: var(--light-text);

                &:hover,
                &:focus {
                    color: var(--primary);
                }
            }
        }
    }
}


.widget-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;

    .left {
        display: flex;
        align-items: center;
    }

    .center {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .right {
        display: flex;
        align-items: center;
        justify-content: flex-end;

        .tag {
            font-family: var(--font);
        }

        .right-icon {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 32px;
            width: 32px;
            min-width: 32px;
            border-radius: var(--radius-rounded);
            color: var(--light-text-light-12);
            transition: all 0.3s; // transition-all test

            &.has-indicator {
                &::after {
                    content: '';
                    position: absolute;
                    top: 3px;
                    right: 4px;
                    height: 10px;
                    width: 10px;
                    border-radius: var(--radius-rounded);
                    background: var(--secondary);
                    border: 1.8px solid var(--white);
                }
            }

            svg {
                height: 18px;
                width: 18px;
                transition: stroke 0.3s;
            }
        }
    }

    h3 {
        font-family: var(--font-alt);
        font-size: 0.9rem;
        color: var(--dark-text);
        font-weight: 600;

        &.is-bigger {
            font-size: 1rem;
        }
    }

    .action-icon {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 32px;
        width: 32px;
        min-width: 32px;
        border-radius: var(--radius-rounded);
        color: var(--light-text-light-12);
        transition: all 0.3s; // transition-all test

        svg {
            height: 18px;
            width: 18px;
            transition: stroke 0.3s;
        }
    }
}

.is-dark {
    .widget-toolbar {
        h3 {
            color: var(--dark-dark-text);
        }

        .right {
            .right-icon {
                &.has-indicator {
                    &::after {
                        border-color: var(--dark-sidebar-light-6);
                    }
                }
            }
        }
    }
}

small.is-tanggal {
    color: var(--light-text);
}

.is-dark-text {
    color: var(--light-text);
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
