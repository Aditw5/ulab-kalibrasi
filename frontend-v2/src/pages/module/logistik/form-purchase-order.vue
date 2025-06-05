<template>
  <ConfirmDialog />
  <div>
    <div class="form-layout is-stacked">
      <div class="form-outer" style="margin-top: 15px">
        <div class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Form Purchase Order</h3>
            </div>
            <div class="right">
              <div class="buttons">
                <VButton icon="lnir lnir-arrow-left rem-100" @click="back()" light dark-outlined RouterLink>
                  Kembali
                </VButton>
                <!-- <VButton v-if="isLoadingUpdateBtn == true" rounded outlined placeload="40px" color="primary"
                                    raised> Button </VButton> -->
                <div>
                  <VButton v-if="nostruk != null" type="button" rounded outlined color="primary" raised
                    icon="feather:save" @click="saveDataPenerimaan(item)">
                    update
                  </VButton>
                  <VButton v-else type="button" :loading="isLoadBtnSave" rounded outlined color="primary" raised
                    icon="feather:save" @click="saveDataPenerimaan(item)">
                    Simpan
                  </VButton>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="form-body p-4">
          <div class="columns is-multiline p-3">
            <div class="column is-4">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Kelompok Barang</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.kelompokBarang" :options="d_kelompokBarang" optionLabel="label"
                    placeholder="Pilih Kelompok Barang" style="width: 100%;" :filter="true" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Unit Pengorder</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.d_unitPengorder" :options="d_unitPengorder" optionLabel="label"
                    placeholder="Pilih Unit Pengorder" style="width: 100%;" :filter="true" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Penanggung Jawab</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.penanggungJawab" :options="d_PenanggungJawab" optionLabel="label"
                    placeholder="Pilih Penanggung Jawab" style="width: 100%;" :filter="true" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="columns is-multiline pl-3 pr-3">
            <div class="column is-3">
              <VDatePicker v-model="item.tglPO" color="green" trim-weeks mode="date" :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VLabel class="required-field">Tanggal PO</VLabel>
                    <VControl icon="feather:calendar">
                      <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-5">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Unit Tujuan</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <AutoComplete v-model="item.unitTujuan" :suggestions="d_unitTujuan" @complete="fetchUnitTujuan($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" />
                </VControl>
              </VField>
            </div>
            <div class="column">
              <VDatePicker v-model="item.tglJatuhTempo" color="green" trim-weeks mode="date">
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VLabel class="required-field">Tanggal Jatuh Tempo</VLabel>
                    <VControl icon="feather:calendar">
                      <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </div>
          </div>
          <!-- <VTabs slider selected="penerimaan" :tabs="[
                        { label: 'Penerimaan', value: 'penerimaan' },
                        { label: 'Faktur', value: 'faktur' },
                        { label: 'Data PO', value: 'po' },
                    ]">
                        <template #tab="{ activeValue }">
                            <p v-if="activeValue === 'penerimaan'">
                            <div style="margin-top:2.8rem" v-if="penerimaanSuplier.loading">
                                <VPlaceloadWrap class="mt-5">
                                    <VPlaceload height="30px" width="40%" class="mx-2" />
                                    <VPlaceload height="30px" width="30%" class="mx-2" />
                                    <VPlaceload height="30px" width="40%" class="mx-2" />
                                </VPlaceloadWrap>
                                <VPlaceloadWrap class="mt-5">
                                    <VPlaceload height="30px" width="50%" class="mx-2" />
                                    <VPlaceload height="30px" width="30%" class="mx-2" />
                                    <VPlaceload height="30px" width="20%" class="mx-2" />
                                </VPlaceloadWrap>
                            </div>
                            <div class="column is-12" v-else>
                                <div class="columns">
                                    <div class="column is-4">
                                        <VDatePicker v-model="item.tglTerima" color="green" trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VLabel class="required-field">Tanggal Terima</VLabel>
                                                    <VControl icon="feather:calendar">
                                                        <VInput type="text" placeholder="Select a date" disabled
                                                            class="is-rounded" :value="inputValue" v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </div>
                                    <div class="column is-4">
                                        <VField class="is-rounded-select is-autocomplete-select">
                                            <VLabel class="required-field">Gudang</VLabel>
                                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                                <Dropdown v-model="item.gudang" :options="d_Gudang" optionLabel="label"
                                                    class="is-rounded" placeholder="Pilih Gudang" style="width: 100%;"
                                                    :filter="true" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField class="is-rounded-select is-autocomplete-select">
                                            <VLabel class="required-field">Pegawai Penerima</VLabel>
                                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                                <Dropdown v-model="item.penerimaan" :options="d_Pegawai" optionLabel="label"
                                                    class="is-rounded" placeholder="Pilih Pegawai Penerima"
                                                    style="width: 100%;" :filter="true" />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                                <div class="columns">
                                    <div class="column is-3 pt-1">
                                        <VField class="is-rounded-select is-autocomplete-select">
                                            <VLabel class="required-field">Kelompok Barang</VLabel>
                                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                                <Dropdown v-model="item.kelompokbarang" :options="d_kelompokBarang"
                                                    optionLabel="label" @change="getProduk(item)" class="is-rounded"
                                                    placeholder="Pilih Kelompok Barang" style="width: 100%;"
                                                    :filter="true" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3 pt-1">
                                        <VField class="is-rounded-select is-autocomplete-select">
                                            <VLabel class="required-field">Sumber Dana</VLabel>
                                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                                <Dropdown v-model="item.sumberdana" :options="d_SumberDana"
                                                    optionLabel="label" class="is-rounded" placeholder="Pilih Sumber Dana"
                                                    style="width: 100%;" :filter="true" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-6 pt-0" v-if="item.kaskecil === true">
                                        <div class="columns">
                                            <div class="column is-6">
                                                <VField>
                                                    <VLabel class="required-field">No Bukti</VLabel>
                                                    <VControl :loading="loadNBK">
                                                        <input v-model="item.noBuktiKK" type="text" class="input is-rounded"
                                                            placeholder="No Dokumen" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-6 p-0 ml-5" style="margin-top: 3rem;">
                                                <VField>
                                                    <VControl raw subcontrol>
                                                        <Checkbox v-model="item.noBKK" :binary="true"
                                                            @change="noBuktiKK(item.noBKK)" />
                                                        <label class="ml-2 ingredient1">Otomatis</label>
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="columns" v-if="item.kaskecil === true">
                                    <div class="column pt-1 is-3">
                                        <VDatePicker v-model="item.tanggalKK" color="green" trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VLabel class="required-field">Tanggal SPK</VLabel>
                                                    <VControl icon="feather:calendar">
                                                        <VInput type="text" placeholder="Select a date" class="is-rounded"
                                                            :value="inputValue" v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </div>
                                    <div class="column is-4 pt-1">
                                        <VField class="is-rounded-select is-autocomplete-select">
                                            <VLabel class="required-field">Ruangan</VLabel>
                                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                                <Dropdown v-model="item.ruanganKK" :options="d_Gudang" optionLabel="label"
                                                    class="is-rounded" placeholder="Pilih data" style="width: 100%;"
                                                    :filter="true" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4 pt-1">
                                        <VField class="is-rounded-select is-autocomplete-select">
                                            <VLabel class="required-field">Pegawai</VLabel>
                                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                                <Dropdown v-model="item.pegawaiKK" :options="d_komit" optionLabel="label"
                                                    class="is-rounded" placeholder="Pilih data" style="width: 100%;"
                                                    :filter="true" />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>

                            </p>
                            <p v-else-if="activeValue === 'faktur'">
                            <div style="margin-top:2.8rem" v-if="penerimaanSuplier.loading">
                                <VPlaceloadWrap class="mt-5">
                                    <VPlaceload height="30px" width="40%" class="mx-2" />
                                    <VPlaceload height="30px" width="30%" class="mx-2" />
                                    <VPlaceload height="30px" width="40%" class="mx-2" />
                                </VPlaceloadWrap>
                                <VPlaceloadWrap class="mt-5">
                                    <VPlaceload height="30px" width="60%" class="mx-2" />
                                    <VPlaceload height="30px" width="30%" class="mx-2" />
                                </VPlaceloadWrap>
                            </div>
                            <div class="column is-12" v-else>
                                <div class="columns">
                                    <div class="column is-2">
                                        <VDatePicker v-model="item.tglFaktur" color="green" trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VLabel class="required-field">Tanggal Faktur</VLabel>
                                                    <VControl icon="feather:calendar">
                                                        <VInput type="text" placeholder="Select a date" class="is-rounded"
                                                            :value="inputValue" v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </div>
                                    <div class="column is-5">
                                        <VField>
                                            <VLabel class="required-field">No Faktur</VLabel>
                                            <VControl>
                                                <input v-model="item.noFaktur" type="text" class="input is-rounded"
                                                    placeholder="No Dokumen" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-2 p-0" style="margin-top: 2rem;">
                                        <VField>
                                            <VControl raw subcontrol>
                                                <VCheckbox label="otomatis" @change="noSurat(item)" v-model="item.noOtom"
                                                    color="info" square />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column p-0" style="margin-top: 2rem;">
                                        <VField>
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="item.nothingFaktur" label="Belum ada no faktur"
                                                    @change="noSurat(item)" color="info" square />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                                <div class="columns">
                                    <div class="column is-5">
                                        <VField class="is-rounded-select is-autocomplete-select">
                                            <VLabel class="required-field">Nama Supplier</VLabel>
                                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                                <Dropdown v-model="item.supplier" :options="d_suplier" optionLabel="label"
                                                    class="is-rounded" placeholder="Pilih data" style="width: 100%;"
                                                    :filter="true" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VDatePicker v-model="item.tglTempo" color="green" trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VLabel>Tanggal Jatuh Tempo</VLabel>
                                                    <VControl icon="feather:calendar">
                                                        <VInput type="text" placeholder="Select a date" class="is-rounded"
                                                            :value="inputValue" v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </div>
                                </div>
                            </div>
                            </p>

                            <p v-else-if="activeValue === 'po'">
                            <div style="margin-top:2.8rem" v-if="penerimaanSuplier.loading">
                                <VPlaceloadWrap class="mt-5">
                                    <VPlaceload height="30px" width="40%" class="mx-2" />
                                    <VPlaceload height="30px" width="60%" class="mx-2" />
                                </VPlaceloadWrap>
                                <VPlaceloadWrap class="mt-5">
                                    <VPlaceload height="30px" width="60%" class="mx-2" />
                                    <VPlaceload height="30px" width="40%" class="mx-2" />
                                </VPlaceloadWrap>
                            </div>
                            <div class="column is-12" v-else>
                                <div class="columns">
                                    <div class="column is-3">
                                        <VDatePicker v-model="item.tanggalPo" color="green" trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VLabel>Tanggal</VLabel>
                                                    <VControl icon="feather:calendar">
                                                        <VInput type="text" placeholder="Select a date" class="is-rounded"
                                                            :value="inputValue" v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </div>
                                    <div class="column is-5">
                                        <VField>
                                            <VLabel>No Usulan</VLabel>
                                            <VControl>
                                                <input v-model="item.nousulan" type="text" class="input is-rounded"
                                                    placeholder="No Usulan..." />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                                <div class="columns">
                                    <div class="column is-5 pt-0">
                                        <VField>
                                            <VLabel>Nama Pengadaan</VLabel>
                                            <VControl>
                                                <input v-model="item.namapengadaan" type="text" class="input is-rounded"
                                                    placeholder="Nama Pengadaan..." />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-7 pt-0">
                                        <VField>
                                            <VLabel>No Kontrak</VLabel>
                                            <VControl>
                                                <input v-model="item.nokontrak" type="text" class="input is-rounded"
                                                    placeholder="No Kontrak" />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>

                            </p>

                        </template>
                    </VTabs> -->
        </div>
      </div>

      <div class="column is-12 p-0 mt-5">
        <VCard>
          <div class="column is-2 p-0 pb-2" style="margin-left: auto">
            <VButton type="button" icon="feather:x-circle" @click="modalTambah(item)"
              class="is-fullwidth is-outlined is-primary mt-4" rounded raised>
              Tambah
            </VButton>
          </div>
          <DataTable :value="penerimaanSuplier" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
            :loading="penerimaanSuplier.loading" class="p-datatable-sm"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
            <Column field="no" header="No"></Column>
            <Column field="namaproduk" header="Nama Produk"></Column>
            <Column field="satuan" header="Satuan"></Column>
            <Column field="jumlah" header="Qty"></Column>
            <!-- <Column field="qtyTerpakai" header="Qty Terpakai"></Column> -->
            <Column field="hargasatuan" header="Harga Satuan"></Column>
            <Column field="subtotal" header="Sub Total"></Column>
            <Column field="hargadiskon" header="Harga Diskon"></Column>
            <Column field="nilaippn" header="PPN"></Column>
            <Column field="totalall" header="Total"></Column>
            <Column field="nobatch" header="No Batch"></Column>
            <Column field="tglkadaluarsa" header="TGL Kadaluarsa"></Column>
            <!-- <Column field="status" header="Tgl Kadaluarsa"></Column> -->
            <Column :exportable="false" header="#" style="text-align: center">
              <template #body="slotProps">
                <VDropdown icon="feather:more-vertical" spaced right>
                  <template #content>
                    <a role="menuitem" class="dropdown-item is-media" @click="editDataSupplier(slotProps.data)">
                      <div class="icon">
                        <i class="iconify" data-icon="feather:edit" aria-hidden="true"></i>
                      </div>
                      <div class="meta">
                        <span>Edit</span>
                        <span>Untuk merubah data </span>
                      </div>
                    </a>
                    <a role="menuitem" style="background:#ED1C24" class="dropdown-item is-media"
                      @click="dialogConfirm(slotProps.data)">
                      <div class="icon">
                        <i aria-hidden="true" style="color:whitesmoke" class="lnil lnil-trash-can-alt"></i>
                      </div>
                      <div class="meta">
                        <span style="color:whitesmoke">Remove</span>
                        <span style="color:whitesmoke">Hapus Data dari Daftar</span>
                      </div>
                    </a>
                  </template>
                </VDropdown>
                <!-- <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined
                                    raised v-tooltip.top="'Edit'" @click="editDataSupplier(slotProps.data)">
                                </VIconButton> -->
              </template>
            </Column>
          </DataTable>
        </VCard>
      </div>

      <div class="column is-12">
        <div class="content">
          <div class="is-divider" data-content="Total Keseluruhan" />
        </div>
      </div>

      <div class="column is-12 p-0">
        <div class="columns is-multiline">
          <div class="column is-3">
            <VCardCustom :style="'padding:5px 25px'">
              <div class="label-status primary">
                <i aria-hidden="true" class="fas fa-circle"></i>
                <span class="ml-1">SUB TOTAL</span>
              </div>
              <!-- <small>{{ item.subtotal }}</small> -->
              <small class="text-bold-custom h-100">{{
                H.formatRp(item.totalsub, 'Rp.')
              }}</small>
            </VCardCustom>
          </div>
          <div class="column is-3">
            <VCardCustom :style="'padding:5px 25px'">
              <div class="label-status info">
                <i aria-hidden="true" class="fas fa-circle"></i>
                <span class="ml-1">DISKON</span>
              </div>
              <small class="text-bold-custom h-100">{{
                H.formatRp(item.discount, 'Rp.')
              }}</small>
            </VCardCustom>
          </div>
          <div class="column is-3">
            <VCardCustom :style="'padding:5px 25px'">
              <div class="label-status danger">
                <i aria-hidden="true" class="fas fa-circle"></i>
                <span class="ml-1">PPN</span>
              </div>
              <small class="text-bold-custom h-100">{{
                H.formatRp(item.ppnTotal, 'Rp.')
              }}</small>
            </VCardCustom>
          </div>
          <div class="column is-3">
            <VCardCustom :style="'padding:5px 25px'">
              <div class="label-status" color="danger">
                <i aria-hidden="true" class="fas fa-circle"></i>
                <span class="ml-1">TOTAL</span>
              </div>
              <small class="text-bold-custom h-100">{{
                H.formatRp(item.totalall, 'Rp.')
              }}</small>
            </VCardCustom>
          </div>
        </div>
      </div>

      <div class="content">
        <div class="is-divider" />
      </div>
    </div>
  </div>

  <VModal :open="modalInput" size="large" actions="center" @close="modalInput = false">
    <template #content>
      <div class="column">
        <div class="columns is-multiline">
          <div class="column is-3">
            <VDatePicker v-model="item.tglKebutuhan" color="green" trim-weeks mode="date">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VLabel class="required-field">Tanggal Kebutuhan</VLabel>
                  <VControl icon="feather:calendar">
                    <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
          <div class="column is-6">
            <VField class="is-rounded-select is-autocomplete-select">
              <VLabel class="required-field">Produk</VLabel>
              <VControl icon="feather:search" fullwidth class="prime-auto-select">
                <AutoComplete v-model="item.produk" :suggestions="d_Produk" @complete="produk($event)"
                  :optionLabel="'namaproduk'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  @change="getSatuan(item.produk)" :loadingIcon="'pi pi-spinner'" :field="'namaproduk'" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField class="is-rounded-select is-autocomplete-select">
              <VLabel class="required-field">Satuan</VLabel>
              <VControl icon="feather:search" fullwidth class="prime-auto-select">
                <Dropdown v-model="item.satuan" :options="d_Satuan" optionLabel="label" style="width: 100%;"
                  :filter="true" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column">
        <div class="columns is-multiline">
          <div class="column is-2">
            <VField label="Jumlah">
              <VControl>
                <input v-model="item.jumlah" type="text" class="input" placeholder="Jumlah" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField label="Harga">
              <VControl>
                <input v-model="item.hargasatuan" type="text" disabled class="input" placeholder="Harga" />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <VField label="Diskon %">
              <VControl>
                <input v-model="item.persendiscount" type="text" class="input" placeholder="Harga" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField label="Harga Diskon">
              <VControl>
                <input v-model="item.hargaDiskon" type="text" disabled class="input" placeholder="Harga" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column">
        <div class="columns is-multiline">
          <div class="column is-2">
            <VField label="PPN %">
              <VControl>
                <input v-model="item.persenPPN" type="text" class="input" placeholder="PPN %" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField label="PPN">
              <VControl>
                <input v-model="item.nilaiPPN" type="text" disabled class="input" placeholder="PPN" />
              </VControl>
            </VField>
          </div>
          <div class="column is-6">
            <VField label="Sub Total">
              <VControl>
                <input v-model="item.subTotal" type="text" class="input" placeholder="Sub Total" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column">
        <VField label="Spesifikasi">
          <VControl>
            <input v-model="item.spesifikasi" type="text" class="input" />
          </VControl>
        </VField>
      </div>
    </template>
    <template #action>
      <VButton color="primary" raised>Confirm</VButton>
    </template>
  </VModal>

  <!--
  <Dialog v-model:visible="modalInput" modal header="Detail Penerimaan Barang">
    <form class="modal-form">
      <div class="column">
        <div class="columns">
          <div class="column is-5">
            <VField class="is-rounded-select is-autocomplete-select">
              <VLabel class="required-field">Nama Produk</VLabel>
              <VControl icon="feather:search" fullwidth class="prime-auto-select">
                <Dropdown v-model="item.produk" :options="d_Produk" optionLabel="label" @change="getConversi(item)"
                  class="is-rounded" placeholder="Pilih Produk" style="width: 100%;" :filter="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField class="is-rounded-select is-autocomplete-select">
              <VLabel class="required-field">Satuan</VLabel>
              <VControl icon="feather:search" fullwidth class="prime-auto-select">
                <Dropdown v-model="item.satuan" :options="d_Satuan" optionLabel="label" @change="getKonversi(item)"
                  class="is-rounded" placeholder="Pilih data" style="width: 100%;" :filter="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <VField>
              <VLabel class="required-field">Konversi</VLabel>
              <VControl>
                <input v-model="item.konversi" type="number" class="input is-rounded" placeholder="Konversi" />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <VField>
              <VLabel class="required-field">Jumlah</VLabel>
              <VControl>
                <input v-model="item.jumlah" type="number" class="input is-rounded" placeholder="QTY" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column">
        <div class="columns">
          <div class="column is-4">
            <VField>
              <VLabel class="required-field">Harga Satuan</VLabel>
              <VControl>
                <input v-model="item.hargasatuan" type="number" class="input is-rounded" placeholder="Harga Satuan" />
              </VControl>
            </VField>
          </div>

          <div class="column is-3">
            <VField label="Jumlah Diskon(%)">
              <VControl>
                <input v-model="item.persendiscount" type="number" class="input is-rounded" placeholder="Jumlah Diskon" />
              </VControl>
            </VField>
          </div>

          <div class="column is-5">
            <VField label="Harga Diskon">
              <VControl>
                <input v-model="item.hargadiskon" type="text" disabled class="input is-rounded"
                  placeholder="Harga Diskon" />
              </VControl>
            </VField>
          </div>

        </div>
      </div>
      <div class="column">
        <div class="columns">
          <div class="column is-3">
            <VField label="PPN(%)">
              <VControl>
                <input v-model="item.persenppn" type="number" class="input is-rounded" placeholder="PPN(%)" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField label="PPN">
              <VControl>
                <input v-model="item.nilaippn" disabled type="text" class="input is-rounded" placeholder="PPN" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VDatePicker v-model="item.tglkadaluarsa" color="green">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VLabel>Tanggal Kadaluarsa</VLabel>
                  <VControl icon="feather:calendar">
                    <VInput type="text" placeholder="Select a date" class="is-rounded" :value="inputValue"
                      v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
          <div class="column is-3">
            <VField label="No Batch">
              <VControl>
                <input v-model="item.nobatch" type="text" class="input is-rounded" placeholder="No Batch" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column pt-0">

        <div class="columns">
          <div class="column is-5">
            <VField label="Keterangan">
              <VControl>
                <input v-model="item.keterangan" type="text" class="input is-rounded" placeholder="Keterangan" />
              </VControl>
            </VField>
          </div>

          <div class="column is-4">
            <VField label="Sub Total">
              <VControl>
                <input v-model="item.subtotal" type="float" class="input is-rounded" placeholder="Sub Total" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
    </form>

    <template #footer>
      <VButton raised class="mr-3" @click="clear()">Batal</VButton>
      <VButton v-if="item.no" icon="feather:plus" @click="updateDataSupplier(item)" color="primary" raised>Update
      </VButton>
      <VButton v-else icon="feather:plus" @click="addDataSupplier(item)" color="primary" raised>Simpan
      </VButton>
    </template>
  </Dialog> -->
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useConfirm } from 'primevue/useconfirm'
import ConfirmDialog from 'primevue/confirmdialog'
import moment from 'moment'
import Checkbox from 'primevue/checkbox';
import AutoComplete from 'primevue/autocomplete';
import * as H from '/@src/utils/appHelper'
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable'
import Dropdown from 'primevue/dropdown'
import Column from 'primevue/column'
useHead({
  title: 'Penerimaan Barang Supplier - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)

const isLoadingPasien: any = ref(false)
const confirm = useConfirm()
const router = useRouter();
const route = useRoute()

const item: any = ref({
  tglPO: new Date(),
  tglKebutuhan: new Date()
})

const d_Pegawai = ref([])
const d_SumberDana = ref([])
const d_kelompokBarang = ref([])
const d_unitPengorder = ref([])
const d_unitTujuan = ref([])
const d_PenanggungJawab = ref([])
const d_komit = ref([])
const d_Gudang = ref([])
const d_suplier = ref([])
const d_Produk = ref([])
const d_Satuan: any = ref([])
const colors: any = ref(Object.keys(useThemeColors()))
const dataProdukDetail: any = ref([])
const modalInput = ref(false)
const listColor: any = ref([])
const nostruk: any = ref()
const noBukti: any = ref()
let penerimaanSuplier: any = ref([])
let isLoadingBtn: any = ref(false)
let isLoadBtnSave: any = ref(false)
let loadNBK: any = ref(false)
let isLoadProduk: any = ref(false)

for (let i = 0; i < colors.value.length; i++) {
  const element = colors.value[i]
  if (i <= 9 && element != 'primary') listColor.value.push(element)
}
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})

const saveDataPenerimaan = async (e: any) => {

  if (!item.value.tglTerima) {
    useToaster().error('Tanggal Terima Tidak Boleh Kosong')
    return
  }
  if (!item.value.gudang) {
    useToaster().error('Gudang Tidak Boleh Kosong')
    return
  }
  if (!item.value.penerimaan) {
    useToaster().error('Pegawai Penerima Tidak Boleh Kosong')
    return
  }
  if (!item.value.kelompokbarang) {
    useToaster().error('kelompok Barang Tidak Boleh Kosong')
    return
  }
  if (!item.value.sumberdana) {
    useToaster().error('Sumber Dana Tidak Boleh Kosong')
    return
  }

  if (item.value.sumberdana == 7) {
    if (!item.value.noBuktiKK) {
      useToaster().error('No Bukti Tidak Boleh Kosong')
      return
    }
    if (!item.value.tanggalKK) {
      useToaster().error('Tanggal Kas Kecil Tidak Boleh Kosong')
      return
    }
    if (!item.value.ruanganKK) {
      useToaster().error('Ruangan Kas Kecil Tidak Boleh Kosong')
      return
    }
    if (!item.value.pegawaiKK) {
      useToaster().error('pegawai Kas Kecil Tidak Boleh Kosong')
      return
    }
  }

  if (!item.value.tglFaktur) {
    useToaster().error('Tanggal Faktur Tidak Boleh Kosong')
    return
  }
  if (!item.value.noFaktur) {
    useToaster().error('No Faktur Tidak Boleh Kosong')
    return
  }
  if (!item.value.supplier) {
    useToaster().error('Supplier Tidak Boleh Kosong')
    return
  }

  if (penerimaanSuplier.value.length == 0) {
    useToaster().error('Data Yang Disimpan Tidak Tersedia')
    return
  }

  isLoadBtnSave.value = true
  const objSave = {
    details: penerimaanSuplier.value,
    struk: {
      nosppb: '',
      nostruk: nostruk.value == null ? '' : nostruk.value,
      norec: route.query.norec ? route.query.norec : '',
      norecsppb: '',
      norecrealisasi: item.value.norecrealisasi ? item.value.norecrealisasi : '',
      objectmataanggaranfk: '',
      norecOrder: null,
      jenissusulan: 'Medis',
      jenissusulanfk: 2,
      noorder: item.value.nosppb ? item.value.nosppb : '-',
      noBuktiKK: item.value.noBuktiKK ? item.value.noBuktiKK : '',
      pegawaiKK: item.value.pegawaiKK ? item.value.pegawaiKK.value.id : null,
      ruanganfkKK: item.value.ruanganKK ? item.value.ruanganKK.value : '',
      ruanganfk: item.value.gudang.value,
      asalproduk: item.value.sumberdana.value,
      pegawaikomit: item.value.pembuatkomit ? item.value.pembuatkomit.namalengkap : '',
      namapegawaipenerima: item.value.penerimaan.value.namalengkap,
      namarekanan: item.value.supplier.label,
      namapengadaan: item.value.namapengadaan ? item.value.namapengadaan : '',
      ketTerima: item.value.ketTerima ? item.value.ketTerima : '',
      nokontrak: item.value.nokontrak ? item.value.nokontrak : '',
      pegawaimenerimafk: item.value.penerimaan.value.id,
      pegawaikomitfk: item.value.pembuatkomit ? item.value.pembuatkomit.id : null,
      rekananfk: item.value.supplier.value.id,
      nofaktur: item.value.noFaktur,
      kelompokprodukfk: item.value.kelompokbarang.value,
      nousulan: item.value.nousulan ? item.value.nousulan : '',
      qtyproduk: penerimaanSuplier.value.length,
      tglSppb: null,
      tglKK: item.value.tanggalKK
        ? H.formatDate(item.value.tanggalKK, 'YYYY-MM-DDTHH:mm:ss')
        : null,
      tglTempo: item.value.tglTempo
        ? H.formatDate(item.value.tglTempo, 'YYYY-MM-DDTHH:mm:ss')
        : null,
      tglfaktur: H.formatDate(item.value.tglFaktur, 'YYYY-MM-DDTHH:mm:ss'),
      tglstruk: H.formatDate(item.value.tglTerima, 'YYYY-MM-DDTHH:mm:ss'),
      tglorder: item.value.tanggalPo ? H.formatDate(item.value.tanggalPo, 'YYYY-MM-DDTHH:mm:ss') : null,
      tglkontrak: H.formatDate(item.value.tanggalPo, 'YYYY-MM-DDTHH:mm:ss'),
      tglrealisasi: H.formatDate(item.value.tglFaktur, 'YYYY-MM-DDTHH:mm:ss'),
      totaldiscount: item.value.discount ? item.value.discount : 0,
      totalhargasatuan: item.value.totalsub,
      totalharusdibayar: item.value.totalall,
      totalppn: item.value.ppnTotal ? item.value.ppnTotal : 0,
    },
  }
  console.log(objSave)
  await useApi()
    .post('/logistik/penerimaan-barang/save-penerimaan-suplier', objSave)
    .then((response) => {
      isLoadBtnSave.value = false
    })
    .catch((err) => {
      isLoadBtnSave.value = false
      console.log(err)
    })
}

const modalTambah = (e: any) => {
  modalInput.value = true
}

const updateDataSupplier = (e: any) => {
  if (!e.satuan) {
    useToaster().error('Satuan Produk Tidak Boleh Kosong')
    return
  }
  if (!e.jumlah) {
    useToaster().error('Jumlah Tidak Boleh Kosong')
    return
  }

  let data: any = {}
  penerimaanSuplier.value.forEach((element: any, i: any) => {
    if (element.no == e.no) {
      data.no = element.no
      data.produk = e.produk
      data.asalproduk = e.sumberdana
      data.produkfk = e.produk.value.id
      data.namaproduk = e.produk.value.namaproduk
      data.satuan = e.satuan.label
      data.ssid = e.satuan.value.ssid
      data.satuanstandarfk = e.satuan.value.ssid
      data.listSatuan = e.satuan
      data.nilaikonversi = e.konversi
      data.jumlah = e.jumlah
      data.jumlahdipakai = 0
      data.sisa = e.jumlah
      data.hargasatuan = e.hargasatuan
      data.persendiscount = e.persendiscount ? e.persendiscount : '0'
      data.hargadiskon = e.hargadiskon ? e.hargadiskon : '0'
      data.nobatch = e.nobatch ? e.nobatch : '-'
      data.keterangan = e.keterangan ? e.keterangan : ''
      data.persenppn = e.persenppn
      data.nilaippn = e.nilaippn ? e.nilaippn : '0'
      data.subtotal = parseFloat(e.hargasatuan) * parseFloat(e.jumlah)
      data.totalall = e.subtotal
      data.tglkadaluarsa = e.tglkadaluarsa ? H.formatDate(e.tglkadaluarsa, 'YYYY-MM-DDTHH:mm:ss') : null
      penerimaanSuplier.value[i] = data
    }
  })
  count()
  modalInput.value = false
}

const listData = async () => {
  await useApi()
    .get('logistik/penerimaan-barang/get-data-combo')
    .then((response) => {
      d_komit.value = response.pembuatkomit.map((e: any) => {
        return { label: e.namalengkap, value: e }
      })
      d_PenanggungJawab.value = response.pegawai.map((e: any) => {
        return { label: e.namalengkap, value: e }
      })
      d_SumberDana.value = response.sumberdana.map((e: any) => {
        return { label: e.asalproduk, value: e.id }
      })
      d_kelompokBarang.value = response.kelompokbarang.map((e: any) => {
        return { label: e.kelompokproduk, value: e.id }
      })
      d_unitPengorder.value = response.ruangan.map((e: any) => {
        return { label: e.namaruangan, value: e.id }
      })
      d_suplier.value = response.suplier.map((e: any) => {
        return { label: e.namarekanan, value: e }
      })
    })
}

const produk = async (e:any) => {
  let search = e.query ? `?namaproduk=${e.query}` : ''
  await useApi().get(`logistik/get-combo-barang-logistik${search}`).then((response) => {
    d_Produk.value = response
  })
}

const getSatuan = (e: any) => {

  if (e.id != undefined) {
    if (e.konversisatuan) {
      if (e.konversisatuan.length != 0) {
        d_Satuan.value = e.konversisatuan.map((element: any) => {
          return { label: element.satuanstandar, value: e.ssid }
        })
        d_Satuan.value.forEach((data: any) => {
          if (data.value == e.ssid) {
            item.value.satuan = data
          }
        });
      } else {
        d_Satuan.value = [{ label: e.satuanstandar, value: e.ssid }]
        d_Satuan.value.forEach((data: any) => {
          if (data.value == e.ssid) {
            item.value.satuan = data
          }
        })
      }
      getHargaProduk(e.id)
    }
  }
}

const getHargaProduk = async (e: any) => {
  await useApi().get(`logistik/get-harga-produk?produkfk=${e}`).then((response: any) => {
    item.value.hargasatuan = response.detail[0].harga
  })
}
const count = () => {

  let totalsub = 0
  let discount = 0
  let ppn = 0
  let total = 0
  penerimaanSuplier.value.forEach((element: any) => {
    totalsub = totalsub + parseFloat(element.subtotal)
    discount = element.hargadiskon === '' ? discount : discount + parseFloat(element.hargadiskon)
    ppn = element.nilaippn === '' ? ppn : ppn + parseFloat(element.nilaippn)
    total = totalsub - discount + ppn
  })
  item.value.totalsub = totalsub
  item.value.discount = discount
  item.value.ppnTotal = ppn
  item.value.totalall = total
}

const fetchUnitTujuan = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_unitTujuan.value = response
  })
}

watch(
  () => [
    item.value.persenppn,
    item.value.hargasatuan,
    item.value.persendiscount,
    item.value.jumlah,
    item.value.noBKK,
    item.value.sumberdana,
  ],
  () => {

    if (item.value.jumlah === undefined || item.value.jumlah === '') {
      item.value.subtotal = ''
    } else {
      const diskon: any = item.value.persendiscount == '' ? delete item.value.persendiscount : item.value.persendiscount / 100
      const nilaiDiskon: any = parseFloat(item.value.jumlah) * parseFloat(item.value.hargasatuan) * diskon
      const resultHarga: any = nilaiDiskon ? parseFloat(item.value.jumlah) * parseFloat(item.value.hargasatuan) - parseFloat(nilaiDiskon)
        : parseFloat(item.value.jumlah) * parseFloat(item.value.hargasatuan)
      const ppn: any = item.value.persenppn / 100
      const nilaippn = resultHarga * ppn
      item.value.hargadiskon = diskon ? nilaiDiskon : ''
      item.value.nilaippn = ppn ? nilaippn : ''
      item.value.subtotal = nilaippn ? parseFloat(resultHarga) + nilaippn : resultHarga
    }
  }
)

const back = () => {
  window.history.back()
}

listData()
// getProduk(item.value.kelompokbarang)

</script>


<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';

.border-style {
  border-style: solid;
  border-width: 1px;
  color: #0398e2;
  border-radius: 10px;
}

.p-dialog-content {
  overflow-y: unset;
}
</style>

