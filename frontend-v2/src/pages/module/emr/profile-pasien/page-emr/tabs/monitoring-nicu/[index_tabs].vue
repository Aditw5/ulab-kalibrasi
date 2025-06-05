<template>
  <div >
    <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div v-if="isStuck" :class="[isStuck && 'is-stuck']" style="margin-top:50px; padding: 0px 0px !important;" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }} Halaman {{ route.params.index_tabs }}</h3>
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
            <h3> {{ props.FORM_NAME }} Halaman {{ route.params.index_tabs }}</h3>
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
      <div class="columns is-multiline">
        <div class="column is-3">
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
          <span class="label-icu">Tanggal Masuk</span>
          <VDatePicker v-model="input.tanggalMasuk" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
            <template #default="{ inputValue, inputEvents }">
              <VField class="pb-0 pt-3">
                <VControl icon="feather:calendar" fullwidth>
                  <VInput :value="inputValue" placeholder="Tanggal Masuk" v-on="inputEvents" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>
        <div class="column is-3">
          <span class="label-icu">Tanggal</span>
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
        <div class="column is-3">
          <span class="label-icu">Berat Badan </span>
          <VField class="pt-2">
            <VControl>
              <VInput type="text" v-model="input.beratBadan" />
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <span class="label-icu">Tinggi Badan</span>
          <VField class="pt-2">
            <VControl>
              <VInput type="text" v-model="input.tinggiBadan" />
            </VControl>
          </VField>
        </div>
        <div class="column is-6">
          <span class="label-icu">Diagnosa</span>
          <VField  class="pt-2">
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
      </div>
      <div class="columns is-multiline">
        <div class="column is-6">
          <span class="label-icu">Dines Pagi</span>
          <VField>
            <VTextarea rows="3" placeholder="Dines Pagi....." v-model="input.dinesPagi"></VTextarea>
          </VField>
        </div>
        <div class="column is-3">
          <span class="label-icu">Nama Perawat</span>
          <VField class="pt-3">
            <VControl class="prime-auto">
              <AutoComplete v-model="input.perawatPagi" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                :loadingIcon="'pi pi-spinner'" :field="'label'" />
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <span class="label-icu">Paraf</span><br>
          <img v-if="input.perawatPagi"
          :src="'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=' + (input.perawatPagi ? input.perawatPagi.label : '-')"><br>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-6">
          <span class="label-icu">Dines Sore</span>
          <VField>
            <VTextarea rows="3" placeholder="Dines Pagi....." v-model="input.dinesSore"></VTextarea>
          </VField>
        </div>
        <div class="column is-3">
          <span class="label-icu">Nama Perawat</span>
          <VField class="pt-3">
            <VControl class="prime-auto">
              <AutoComplete v-model="input.perawatSore" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                :loadingIcon="'pi pi-spinner'" :field="'label'" />
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <span class="label-icu">Paraf</span><br>
          <img v-if="input.perawatSore"
          :src="'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=' + (input.perawatSore ? input.perawatSore.label : '-')"><br>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-6">
          <span class="label-icu">Dines Malam</span>
          <VField>
            <VTextarea rows="3" placeholder="Dines Pagi....." v-model="input.dinesMalam"></VTextarea>
          </VField>
        </div>
        <div class="column is-3">
          <span class="label-icu">Nama Perawat</span>
          <VField class="pt-3">
            <VControl class="prime-auto">
              <AutoComplete v-model="input.perawatMalam" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                :loadingIcon="'pi pi-spinner'" :field="'label'" />
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <span class="label-icu">Paraf</span><br>
          <img v-if="input.perawatMalam"
          :src="'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=' + (input.perawatMalam ? input.perawatMalam.label : '-')"><br>
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
              <span class="label-icu">OKSIGEN</span>
              <div class="column pl-0 pr-0">
                <DataTable :value="tableOksigen" scrollable scrollHeight="500px" class="p-datatable-sm" showGridlines
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
              </div><br><br>
              <span class="label-icu">INTAKE</span>
              <div class="column pl-0 pr-0">
                <DataTable :value="tableIntake" scrollable scrollHeight="500px" class="p-datatable-sm" showGridlines
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
                        :rowEditor="true">
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
                  <Column style="width: 25%" v-for="(data, i) in waktu" class="font-bold"
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

            <span class="label-icu">LABORATORIUM</span><br>
              <VButton color="primary" raised icon="fas fa-plus" class="mt-3" @click="addNewItemLab"> Tambah LABORATORIUM</VButton>
              <div class="column pl-0 pr-0">
                <DataTable :value="labkritis" scrollable scrollHeight="500px" class="p-datatable-sm" showGridlines
                  editMode="cell" @cell-edit-complete="onCellEditComplete" tableClass="editable-cells-table"
                  tableStyle="min-width: 50rem">

                  <ColumnGroup type="header">
                    <Row>
                      <Column header="LABORATORIUM" style="min-width: 150px; text-align: center;" frozen :rowspan="3" />
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
              </div><br>

              <VButton color="primary" raised icon="fas fa-plus" class="mt-3" @click="addNewItemTherapi"> Tambah THERAPI</VButton>
              <div class="column pl-0 pr-0">
                <DataTable :value="therapi" scrollable scrollHeight="500px" class="p-datatable-sm" showGridlines
                  editMode="cell" @cell-edit-complete="onCellEditComplete" tableClass="editable-cells-table"
                  tableStyle="min-width: 50rem">

                  <ColumnGroup type="header">
                    <Row>
                      <Column header="THERAPI" style="min-width: 150px; text-align: center;" frozen :rowspan="3" />
                      <Column header="Action" style="min-width: 150px; text-align: center;" frozen :rowspan="3" />
                      <Column header="Waktu" style="min-width: 300px;" class="font-bold"
                        :colspan="listDescripWaktu.ketWaktu.length" />
                    </Row>
                    <Row>
                      <Column :header="data" style="min-width: 100px;" v-for="(data) in waktu" class="font-bold">
                      </Column>
                    </Row>
                  </ColumnGroup>

                  <Column field="namaTherapi" class="font-bold" style="min-width: 150px; text-align: center;" frozen>
                    <template #body="slotProps">
                      {{ slotProps.data.namaTherapi }}
                    </template>
                    <template #editor="{ data, field }">
                      <InputText v-model="data[field]" autofocus />
                    </template>
                  </Column>
                  <Column field="action" style="min-width: 150px; text-align: center;" frozen>
                    <template #body="slotProps">
                      <VIconButton class="mt-1" v-if="slotProps.data" type="button" raised circle icon="feather:trash"
                        @click="removeItemTherapi(slotProps.index)" color="danger">
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
              </div><br><br>

            <hr class="mt-2 ml-0 mr-0 mb-0" style="border-top-style: dashed;border-color: var(--fade-grey-dark-2);">
            <div class="column">
              <div class="columns is-multiline">
                <div class="column is-6">
                  <table class="tg">
                    <thead>
                      <tr>
                        <th style="text-align:center;font-weight: bold;font-size: 18px;" class="tg-0lax text-center"> 
                          Granding of State (Prechti & Beintema, 1964)
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>
                          <div class="column">
                            <div class="columns is-multiline">
                              <div class="column is-4">
                                <span class="label-icu">State 1 :</span>
                              </div>
                              <div class="column is-8">
                                <span class="label-icu">mata tertutup, respirasi reguler, tanpa gerekan</span>
                              </div>
                            </div>
                            <div class="columns is-multiline">
                              <div class="column is-4">
                                <span class="label-icu">State 2 :</span>
                              </div>
                              <div class="column is-8">
                                <span class="label-icu">mata tertutup, respirasi irreguler, pergerakan lemah</span>
                              </div>
                            </div>
                            <div class="columns is-multiline">
                              <div class="column is-4">
                                <span class="label-icu">State 3 :</span>
                              </div>
                              <div class="column is-8">
                                <span class="label-icu">mata terbuka, pergerakan lemah</span>
                              </div>
                            </div>
                            <div class="columns is-multiline">
                              <div class="column is-4">
                                <span class="label-icu">State 4 :</span>
                              </div>
                              <div class="column is-8">
                                <span class="label-icu">mata terbuka, pergerakan aktif, tidak menangis</span>
                              </div>
                            </div>
                            <div class="columns is-multiline">
                              <div class="column is-4">
                                <span class="label-icu">State 5 :</span>
                              </div>
                              <div class="column is-8">
                                <span class="label-icu">mata terbuka atau tertutup, menangis kuat</span>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="column is-6">
                  <table class="tg">
                    <thead>
                      <tr>
                        <th style="text-align:center;font-weight: bold;font-size: 18px;background-color: lightblue" class="tg-0lax text-center" colspan="4"> 
                         SKOR DOWNES
                        </th>
                      </tr>
                      <tr style="font-size: 15px;">
                          <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center" colspan="2">Pemeriksaan
                          </th>
                          <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">
                          </th>
                          <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Skor
                          </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                         <td rowspan="3" style="text-align: center; font-size: 12pt;">
                          Frekuensi nafas
                        </td>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                          &lt; 60/mnt
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          0
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skorA" :true-value="0" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                          60-80x/mnt
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          1
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skorB" :true-value="1" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                          >80/mnt
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          2
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skorC" :true-value="2" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                         <td rowspan="3" style="text-align: center; font-size: 12pt;">
                          Retraksi
                        </td>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                          Tidak Ada
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          0
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skorD" :true-value="0" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                         Ringan
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          1
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skorE" :true-value="1" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                          Berat
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          2
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skorF" :true-value="2" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                         <td rowspan="3" style="text-align: center; font-size: 12pt;">
                          Syanosis
                        </td>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                          Tidak Ada
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          0
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skorG" :true-value="0" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                        Hilang dengan O2
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          1
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skorH" :true-value="1" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                         Menetap
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          2
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skorI" :true-value="2" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                         <td rowspan="3" style="text-align: center; font-size: 12pt;">
                          Udara masuk
                        </td>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                          Ada
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          0
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skorJ" :true-value="0" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                        Penurunan Ringan 
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          1
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skorK" :true-value="1" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                         Tidak Ada
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          2
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skorL" :true-value="2" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                         <td rowspan="3" style="text-align: center; font-size: 12pt;">
                          Merintih
                        </td>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                          Tidak Ada
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          0
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skorM" :true-value="0" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                        Didengar dengan stetoskop
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          1
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skorN" :true-value="1" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                         Tendengar Tanpa Stetoskop
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          2
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skorO" :true-value="2" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="column is-6">
                </div>
                <div class="column is-6">
                  <div class="column">
                    <div class="columns is-multiline">
                      <div class="column is-4">
                        <span class="label-icu">1-3 : sesak nafas ringan</span>
                        <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.totalScore1" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                        </VField>
                      </div>
                      <div class="column is-4">
                        <span class="label-icu">4-6 : sesak nafas sedang</span>
                        <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.totalScore2" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                        </VField>
                      </div>
                      <div class="column is-4">
                        <span class="label-icu">>6 : sesak nafas berat</span>
                        <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.totalScore3" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div><br>

            <hr class="mt-2 ml-0 mr-0 mb-0" style="border-top-style: dashed;border-color: var(--fade-grey-dark-2);">
            <div class="column">
              <div class="columns is-multiline">
                <div class="column is-6">
                  <table class="tg">
                    <thead>
                      <tr>
                        <th style="text-align:center;font-weight: bold;font-size: 18px;background-color: lightcoral" class="tg-0lax text-center" colspan="4"> 
                         PENGKAJIAN NYERI
                        </th>
                      </tr>
                      <tr style="font-size: 15px;">
                          <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center" colspan="2">Kategori
                          </th>
                          <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Skor
                          </th>
                          <th style="text-align:center;font-weight: bold;" class="tg-0lax text-center">Nilai
                          </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                         <td rowspan="2" style="text-align: center; font-size: 12pt;">
                         Ekspresi Wajah
                        </td>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                          Relaksasi
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          0
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skor1" :true-value="0" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                          Meringis
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          1
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skor2" :true-value="1" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                         <td rowspan="3" style="text-align: center; font-size: 12pt;">
                            Menangis
                          </td>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                          Tidak Menangis
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          0
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skor3" :true-value="0" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                         Merengek
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          1
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skor4" :true-value="1" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                          Merengek Kuat
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          2
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skor5" :true-value="2" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                         <td rowspan="2" style="text-align: center; font-size: 12pt;">
                          Pola Nafas
                        </td>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                          Relaksasi
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          0
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skor6" :true-value="0" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                        Perubahan Pola Nafas
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          1
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skor7" :true-value="1" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                         <td rowspan="4" style="text-align: center; font-size: 12pt;">
                          Ekstremitas atas
                        </td>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                          Diikat
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          0
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skor8" :true-value="0" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                        Relaksasi 
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          1
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skor9" :true-value="1" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                         Fleksi
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          2
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skor10" :true-value="2" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                         Ekstensi
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          3
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skor11" :true-value="3" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                         <td rowspan="4" style="text-align: center; font-size: 12pt;">
                          Ekstrimitas Bawah 
                        </td>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                          Diikat
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          0
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skor12" :true-value="0" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                        Relaksasi
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          1
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skor13" :true-value="1" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                         Fleksi
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          2
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skor14" :true-value="2" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                         Ekstensi
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          3
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skor15" :true-value="3" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                         <td rowspan="3" style="text-align: center; font-size: 12pt;">
                          Tingkat Kesadaran
                        </td>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                          Tidur
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          0
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skor16" :true-value="0" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                        Sadar
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          1
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skor17" :true-value="1" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: center; font-size: 12pt; width: 400px !important;">
                         Rewel
                        </td>
                        <td style="text-align: center; font-size: 12pt;">
                          2
                        </td>
                        <td width="250px"
                          style="text-align: center;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.skor18" :true-value="2" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="column is-6">
                  <div class="column">
                    <div class="columns is-multiline">
                      <div class="column is-12">
                        <span class="label-icu">Interpretasi dan jumlah skor didapat :</span>
                      </div>
                    </div>
                    <div class="columns is-multiline">
                      <div class="column is-12">
                        <span class="label-icu">Skor 0 : tidak nyeri</span>
                        <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.totalScoreA" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <span class="label-icu">Skor &lt; 2 : tidak nyaman </span>
                        <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.totalScoreB" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <span class="label-icu"> Skor 2-4 : nyeri ringan-sedang</span>
                        <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.totalScoreC" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <span class="label-icu"> Skor 4-7 : nyeri sedang-berat</span>
                        <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.totalScoreD" class="p-0"
                                style="text-align: center;" />
                            </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
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
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount,} from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave, onBeforeRouteUpdate } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../../../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';   // optional
import Chart from 'primevue/chart';
import Row from 'primevue/row';
import Fieldset from 'primevue/fieldset';
import * as ICU from '../../../page-emr-plugins/monitoring-nicu'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import InputText from 'primevue/inputtext';
import { alatBantu } from '../../../page-emr-plugins/asesmen-awal-keperawatan-rawat-inap'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const route = useRoute()

let waktu = ref(ICU.waktu())
let listDescripWaktu = ref(ICU.listDescripWaktu())
let description = ref(ICU.dataTableDescrip())
let output = ref(ICU.dataOutput())
let statusRespirasi = ref(ICU.statusRespirasi())
let hemodinamik = ref(ICU.Hemodinamik())
let tableBalansCairan = ref(ICU.tableBalansCairan())
let tableIntake = ref(ICU.tableIntake())
let tableOksigen = ref(ICU.tableOksigen())
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
const isStuck = computed(() => { return y.value >= 20 })
const isLoading: any = ref(false)
const isLoadingHolder: any = ref(false)
const medikasi = ref([]);
const parenteral = ref([]);
const labkritis = ref([]);
const therapi = ref([]);
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
const COLLECTION: any = ref("MonitoringNICU") //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const d_produk: any = ref([])
const input: any = ref({
  tanggalMasuk: new Date(),  
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

// const filteredList = computed(() => {
//   if (!filter.value) {
//     // return listVital.value
//   }

//   return listVital.value.filter((items: any) => {
//     return (
//       items.user_input.namalengkap.match(new RegExp(filter.value, 'i'))
//     )
//   })
// })

const add = () => {
  showFormInput.value = true
}

const loadRiwayat = async () => {
  isLoadingHolder.value = true
  await useApi()
    .get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&index_tabs=${route.params.index_tabs}`
    ).then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan
        description.value = response[0].monitoringICU
        output.value = response[0].output
        medikasi.value = response[0].medikasi
        parenteral.value = response[0].parenteral
        labkritis.value = response[0].labkritis
        therapi.value = response[0].therapi
        dataSourceAlatInvasif.value = response[0].pemasanganAlat
        tableBalansCairan.value = response[0].balansCairan
        tableIntake.value = response[0].intake
        tableOksigen.value = response[0].Oksigen
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
  object.therapi = therapi.value
  object.balansCairan = tableBalansCairan.value
  object.intake = tableIntake.value
  object.Oksigen = tableOksigen.value
  object.pemasanganAlat = dataSourceAlatInvasif.value
  if (route.params.index_tabs) {
  object.index_tabs = Array.isArray(route.params.index_tabs)
    ? parseInt(route.params.index_tabs[0]) 
    : parseInt(route.params.index_tabs);   
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

const addNewItemTherapi = () => {
  therapi.value.push({
    namaTherapi: "",
    dosis: "",
    no: therapi.value.length + 1,
  });
}

const removeItemTherapi = (index: any) => {
  therapi.value.splice(index, 1)
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

onBeforeMount(async () => {
  try {
    await loadRiwayat()
    let rouutename = String(route.name) + '-' + route.params.index_tabs;
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${rouutename}`)
    if (cache) input.value = cache
  } catch (error) {
    console.error('Error mount cache TAB EMR:', error)
  }
})

onBeforeRouteLeave((to, from, next) => {
  try {
    let rouutename = String(from?.name) + '-' + (Array.isArray(route.params.index_tabs) ? route.params.index_tabs[0] : route.params.index_tabs);
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
      ; (input.value.tanggalMasuk = new Date()),  
        (input.value.tanggal = new Date()),  
        (input.value.tanggalCeklisVap = new Date()),  
        (input.value.tanggalCeklisDc = new Date()),  
        (input.value.tanggalCeklisIadp = new Date()),  
        (input.value.tanggalCeklisIdo = new Date()),  
        (input.value.tglVerifikasi = new Date())  
    setAutoFill()
    loadRiwayat()
    let rouutename = String(route.name) + '-' + route.params.index_tabs;
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${rouutename}`)
    if (cache) {
      input.value = cache
    }
  }
)
watch(
  () => input.value,
  (newValue, oldValue) => {
    let rouutename = String(route.name) + '-' + route.params.index_tabs;
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

const calculateTotalScore = () => {
  let totalScore = 0;
  totalScore =
    (input.value.skorA || 0) +
    (input.value.skorB || 0) +
    (input.value.skorC || 0) +
    (input.value.skorD || 0) +
    (input.value.skorE || 0) +
    (input.value.skorF || 0) +
    (input.value.skorG || 0) +
    (input.value.skorH || 0) +
    (input.value.skorI || 0) +
    (input.value.skorJ || 0) +
    (input.value.skorK || 0) +
    (input.value.skorL || 0) +
    (input.value.skorM || 0) +
    (input.value.skorN || 0) +
    (input.value.skorO || 0);

  input.value.totalScore1 = false;
  input.value.totalScore2 = false;
  input.value.totalScore3 = false;

  if (totalScore >= 1 && totalScore <= 3) {
    input.value.totalScore1 = true; 
  } else if (totalScore >= 4 && totalScore <= 6) {
    input.value.totalScore2 = true; 
  } else if (totalScore > 6) {
    input.value.totalScore3 = true; 
  }
};

const calculateTotalScorePengkajianNyeri = () => {
  let totalScorePengkajian = 0;

  totalScorePengkajian =
    (input.value.skor1 || 0) +
    (input.value.skor2 || 0) +
    (input.value.skor3 || 0) +
    (input.value.skor4 || 0) +
    (input.value.skor5 || 0) +
    (input.value.skor6 || 0) +
    (input.value.skor7 || 0) +
    (input.value.skor8 || 0) +
    (input.value.skor9 || 0) +
    (input.value.skor10 || 0) +
    (input.value.skor11 || 0) +  
    (input.value.skor12 || 0) +
    (input.value.skor13 || 0) +
    (input.value.skor14 || 0) +
    (input.value.skor15 || 0) +
    (input.value.skor16 || 0) +
    (input.value.skor17 || 0) +
    (input.value.skor18 || 0);

  input.value.totalScoreA = false;
  input.value.totalScoreB = false;
  input.value.totalScoreC = false;
  input.value.totalScoreD = false;

  if (totalScorePengkajian === 0) {
    input.value.totalScoreA = true;
  } else if (totalScorePengkajian > 0 && totalScorePengkajian < 2) {
    input.value.totalScoreB = true;
  } else if (totalScorePengkajian >= 2 && totalScorePengkajian <= 4) {
    input.value.totalScoreC = true;
  } else if (totalScorePengkajian > 4 && totalScorePengkajian <= 7) {
    input.value.totalScoreD = true;
  }
};



watch(
  () => [input.value.skorA, input.value.skorB, input.value.skorC, input.value.skorD, input.value.skorE, input.value.skorF,  input.value.skorG,
    input.value.skorH,  input.value.skorI, input.value.skorJ, input.value.skorK, input.value.skorL, input.value.skorM, input.value.skorN, input.value.skorO],
  () => {
    calculateTotalScore();
  },
  { deep: true }
);

watch(
  () => [input.value.skor1, input.value.skor2, input.value.skor3, input.value.skor4, input.value.skor5, input.value.skor6,  input.value.skor7,
    input.value.skor8,  input.value.skor9, input.value.skor10, input.value.skor11, input.value.skor12, input.value.skor13, input.value.skor14,
    input.value.skor15, input.value.skor16, input.value.skor17, input.value.skor18],
  () => {
    calculateTotalScorePengkajianNyeri();
  },
  { deep: true }
);

setAutoFill()
setView()
loadRiwayat()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
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
