<!-- <route lang="yaml">
meta:
  requiresAuth: true
</route> -->
<template>
  <ConfirmDialog />
  <div class="columns">
    <div class="column is-12 form-layout is-stacked">
      <div class=" form-outer">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>{{ TITLE_PAGE }}</h3>
            </div>
            <div class="right">
              <div class="buttons">
                <VButton icon="lnir lnir-arrow-left rem-100" class="btn-orderBarang"
                  :to="{ name: 'module-logistik-daftar-order-barang' }" light dark-outlined>
                  Batal
                </VButton>
                <VButton icon="feather:save" type="submit" color="primary" raised @click="save()" class="btn-orderBarang"
                  :loading="isSimpan">
                  Simpan
                </VButton>
              </div>
            </div>
          </div>
        </div>
        <div class="form-body">
          <div class="column is-12">
            <VCard>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <VDatePicker v-model="item.tglorder" color="green" trim-weeks mode="dateTime" :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VLabel>Tanggal</VLabel>
                        <VControl icon="feather:calendar">
                          <VInput type="text" placeholder="Select a date" class="is-rounded" :value="inputValue"
                            v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </div>

                <div class="column is-4">
                  <VField class="is-rounded-select is-autocomplete-select">
                    <VLabelText>Ruangan Pemesan</VLabelText>
                    <VControl icon="feather:search" :loading="isLoading">
                      <Multiselect mode="single" v-model="item.ruanganPengirim" :options="d_ruangan"
                        placeholder="Pilih data" :searchable="true" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField class="is-rounded-select is-autocomplete-select">
                    <VLabelText>Ruangan Tujuan</VLabelText>
                    <VControl icon="feather:search" :loading="isLoading">
                      <Multiselect mode="single" v-model="item.ruanganTujuan" :options="d_rutu" placeholder="Pilih data"
                        :searchable="true" :disabled="disabledRuangan" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField class="is-rounded-select is-autocomplete-select">
                    <VLabelText>Jenis Order</VLabelText>
                    <VControl icon="feather:search">
                      <Multiselect mode="single" v-model="item.jenisKirim" :options="d_jenisKirim"
                        placeholder="Pilih data" :searchable="true" :disabled="disabledJenis" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField>
                    <VLabelText>Keterangan</VLabelText>
                    <VControl>
                      <input v-model="item.keterangan" type="text" class="input is-rounded" placeholder="keterangan..." />
                    </VControl>
                  </VField>
                </div>
              </div>
            </VCard>
          </div>

          <div class="column is-12">
            <VCard>
              <div class="columns is-multiline">

                <div class="column is-12">
                  <Toolbar class="mb-4">
                    <template #start>
                      <VButton icon="feather:plus" color="info" raised class="btn-orderBarang" @click="addPopUp()">
                        Tambah
                      </VButton>
                    </template>
                  </Toolbar>

                  <DataTable :value="dataSource" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                    <Column :exportable="false" header="#" style="width:8rem">
                      <template #body="slotProps">
                        <Button icon="pi pi-pencil" class="p-button-rounded p-button-warning mr-2"
                          @click="editRow(slotProps.data)" />
                        <Button icon="pi pi-trash" class="p-button-rounded p-button-danger"
                          @click="hapusRow(slotProps.data)" />
                      </template>
                    </Column>

                    <Column field="no" header="No"></Column>
                    <Column field="namaproduk" header="Produk" :sortable="true"></Column>
                    <Column field="satuanstandar" header="Satuan"></Column>
                    <Column field="nilaikonversi" header="Konversi"></Column>
                    <Column field="stock" header="Stok"></Column>
                    <Column field="jumlah" header="Jumlah"></Column>

                    <template #paginatorstart>
                      <Button type="button" icon="pi pi-refresh" class="p-button-text" />
                    </template>
                    <template #paginatorend>
                      <Button type="button" icon="pi pi-cloud" class="p-button-text" />
                    </template>

                  </DataTable>
                </div>
              </div>
            </VCard>
          </div>
        </div>
      </div>
    </div>

    <!-- </div> -->

    <VModal :open="modalInput" title="Add Barang" size="large" actions="right" @close="modalInput = false">
      <template #content>
        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-6">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel>Produk</VLabel>
                <VControl icon="feather:search">
                  <AutoComplete v-model="item.produk" :suggestions="d_produk" @complete="fetchProduk($event)"
                    :optionLabel="'namaproduk'" :dropdown="true" :minLength="3" class="is-rounded" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'namaproduk'" placeholder="ketik untuk mencari..."
                    @item-select="changeProduk(item.produk)" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel>Satuan</VLabel>
                <VControl icon="feather:search">
                  <Dropdown v-model="item.satuan" :options="d_satuan" :optionLabel="'label'" class="is-rounded"
                    placeholder="Pilih data" style="width: 100%;" showClear :filter="true"
                    @change="changeSatuan(item.satuan)" />
                </VControl>
              </VField>
            </div>
            <!-- <div class="column is-6">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel>Produk</VLabel>
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.produk" :options="d_produk" placeholder="Pilih data"
                    :searchable="true" @select="changeProduk(item.produk)" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel>Satuan</VLabel>
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.satuan" :options="d_satuan" placeholder="Pilih data"
                    :searchable="true" @select="changeSatuan(item.satuan)" />
                </VControl>
              </VField>
            </div> -->
            <div class="column is-4">
              <VField>
                <VLabel>Konversi</VLabel>
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.nilaiKonversi" placeholder="konversi" class="is-rounded" disabled />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VLabel>Stok</VLabel>
                <VControl icon="feather:bookmark" :loading="isLoading">
                  <VInput type="text" v-model="item.stok" placeholder="Stok" class="is-rounded" disabled />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VLabel>Jumlah</VLabel>
                <VControl icon="feather:bookmark">
                  <VInput type="number" v-model="item.jumlah" placeholder="Jumlah" class="is-rounded" />
                </VControl>
              </VField>
            </div>
          </div>
        </form>
      </template>
      <template #action>
        <VButton icon="feather:plus" @click="tambah()" color="primary" raised>Tambah</VButton>
      </template>
    </VModal>

    <VModal :open="modalStokProduk" title="Informasi produk yang tersedia" size="medium" actions="right"
      @close="modalStokProduk = false">
      <template #content>
        <form class="modal-form">
          <DataTable :value="dataSourceStok" class="p-datatable-sm" responsiveLayout="stack" breakpoint="960px"
            showGridlines sortMode="multiple">
            <Column field="no" header="No"></Column>
            <Column field="namaruangan" header="Ruangan" :sortable="true"></Column>
            <Column field="qtyproduk" header="Stok"></Column>
          </DataTable>
        </form>
      </template>
    </VModal>

    <VModal :open="modalTandaTangan" title="Tanda Tangan" size="medium" actions="right" @close="modalStokProduk = false">
      <template #content>
        <div class="column">
          <div class="columns is-mltiline">
            <div class="column is-6 pt-0">
              <Fieldset :toggleable="true" legend="Mengetahui">
                <div class="column is-12 p-0">
                  <VField class="is-rounded-select_Z  pt-3" v-slot="{ id }" label="Jabatan">
                    <VControl fullwidth class="prime-auto ">
                      <AutoComplete v-model="item.mJabatan" :suggestions="d_Jabatan" @complete="fetchJabatan($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12 p-0">
                  <VField class="is-rounded-select_Z  pt-3" v-slot="{ id }" label="Pegawai">
                    <VControl fullwidth class="prime-auto ">
                      <AutoComplete v-model="item.mPegawai" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                    </VControl>
                  </VField>
                </div>
              </Fieldset>
            </div>
            <div class="column is-6 pt-0">
              <Fieldset :toggleable="true" legend="Yang Meminta">
                <div class="column is-12 p-0">
                  <VField class="is-rounded-select_Z  pt-3" v-slot="{ id }" label="Jabatan">
                    <VControl fullwidth class="prime-auto ">
                      <AutoComplete v-model="item.tJabatan" :suggestions="d_Jabatan" @complete="fetchJabatan($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12 p-0">
                  <VField class="is-rounded-select_Z  pt-3" v-slot="{ id }" label="Pegawai">
                    <VControl fullwidth class="prime-auto ">
                      <AutoComplete v-model="item.tPegawai" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                    </VControl>
                  </VField>
                </div>
              </Fieldset>
            </div>
          </div>
          <div class="columns is-mltiline">
            <div class="column is-6 pt-0">
              <Fieldset :toggleable="true" legend="Yang Menyerahkan">
                <div class="column is-12 p-0">
                  <VField class="is-rounded-select_Z  pt-3" v-slot="{ id }" label="Jabatan">
                    <VControl fullwidth class="prime-auto ">
                      <AutoComplete v-model="item.hJabatan" :suggestions="d_Jabatan" @complete="fetchJabatan($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12 p-0">
                  <VField class="is-rounded-select_Z  pt-3" v-slot="{ id }" label="Pegawai">
                    <VControl fullwidth class="prime-auto ">
                      <AutoComplete v-model="item.hPegawai" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                    </VControl>
                  </VField>
                </div>
              </Fieldset>
            </div>
            <div class="column is-6 pt-0">
              <Fieldset :toggleable="true" legend="Yang Menerima">
                <div class="column is-12 p-0">
                  <VField class="is-rounded-select_Z  pt-3" v-slot="{ id }" label="Jabatan">
                    <VControl fullwidth class="prime-auto ">
                      <AutoComplete v-model="item.aJabatan" :suggestions="d_Jabatan" @complete="fetchJabatan($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12 p-0">
                  <VField class="pt-3" v-slot="{ id }" label="Pegawai">
                    <VControl class="prime-auto ">
                      <AutoComplete v-model="item.aPegawai" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                        :optionLabel="'label'" :dropdown="true" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                        :field="'label'" placeholder="ketik untuk mencari..." />
                    </VControl>
                  </VField>
                </div>
              </Fieldset>
            </div>
          </div>
        </div>

      </template>
      <template #action>
        <VButton icon="feather:plus" @click="tambah()" color="primary" :disabled="isReady" raised>Tambah</VButton>
      </template>
    </VModal>

  </div>
</template>

<script setup lang="ts">

import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { reactive, ref, computed, defineComponent, watch } from 'vue'
// import {
//   ref,
//   computed,
//   defineComponent,
//   watch,
//   nextTick,
//   onMounted,
//   reactive,
//   watchEffect
// } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { formatRp } from '/@src/utils/appHelper'
import { useToaster } from '/@src/composable/toaster'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useConfirm } from 'primevue/useconfirm'
import PrimeVue from 'primevue/config';
import Fieldset from 'primevue/fieldset';
import ConfirmDialog from 'primevue/confirmdialog'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar'
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';
import AutoComplete from 'primevue/autocomplete';
// import { useCurrencyInput } from 'vue-currency-input'
import moment from 'moment'
const TITLE_PAGE = 'Order Barang'
useHead({
  title: `${TITLE_PAGE} - Transmedic`,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
let NOREC_ORDER: any = useRoute().query.norec as string
let NOREC_KIRIM: any = useRoute().query.norec_kirim as string
const modalInput = ref(false)

let item: any = reactive({
  header: {},
  totalAll: 0,
  jumlah: 0,
  tglorder: new Date(),
})
const confirm = useConfirm();
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})

const d_ruangan: any = ref([])
const d_rutu: any = ref([])
const d_produk: any = ref([])
const d_satuan: any = ref([])
const d_jenisKirim: any = ref([])
const d_Jabatan: any = ref([])
const d_Pegawai: any = ref([])
const d_satuanResep: any = ref([])
const dataSource: any = ref([])
const data2: any = ref([])
const norecSPD: any = ref('')
const norecTerima: any = ref('')
const isLoading: any = ref(false)
const isSimpan: any = ref(false)
const isMerge: any = ref(false)
const modalStokProduk: any = ref(false)
const modalTandaTangan: any = ref(false)
const tarifJasa: any = ref(0)
const hrg1: any = ref(0)
const hrgsdk: any = ref(0)
const dataProdukDetail: any = ref([])
const disabledRuangan: any = ref(false)
const disabledJenis: any = ref(false)
const isReady = ref(false)
const dataSelected: any = ref({})
const dataSourceStok: any = ref([])
const isEdit: any = ref(false)
const isLoadingData: any = ref(false)


const getStokProduk = async (e: any) => {
  await useApi().get(`dashboard/logistik/get-informasi-stok?produkfk=${e}`).then((response) => {
    modalStokProduk.value = true
    response.infostok.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataSourceStok.value = response.infostok
  })
}


const dialogConfirm = (e: any) => {
  confirm.require({
    message: 'Tampilkan Informasi Produk ?',
    header: 'Stok Tidak Tersedia',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      getStokProduk(e)
    },
    reject: () => { },
  })
}



const onInit = async () => {
  loadDrop()
}

const loadDrop = async () => {
  isLoading.value = true
  const response = await useApi().get(`/logistik/list-order-cbo`)
  d_produk.value = response.produk.map((e: any) => { return { label: e.namaproduk, value: e } })
  d_ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id } })
  d_jenisKirim.value = response.jenis.map((e: any) => { return { label: e.jenis, value: e.id } })
  d_satuanResep.value = response.satuanresep.map((e: any) => { return { label: e.satuanresep, value: e } })
  d_rutu.value = response.rutu.map((e: any) => { return { label: e.namaruangan, value: e.id } })
  disabledRuangan.value = false;
  disabledJenis.value = false;
  if (NOREC_ORDER != undefined) {
    loadEdit()
  }
  item.keterangan = ''
  isLoading.value = false
}
const loadEdit = async () => {
  if (NOREC_ORDER != '') {
    isEdit.value = true
    isSimpan.value = true;
    isLoading.value = true
    const response = await useApi().get(`/logistik/get-detail-order?norec=${NOREC_ORDER}`)
    isLoading.value = false
    isSimpan.value = false;
    isEdit.value = false
    disabledRuangan.value = true;
    let headerKirim = response.head
    let detailKirim = response.detail


    item.qtyproduk
    item.jenisKirim = response.head.jeniskirimfk
    item.ruanganPengirim = response.head.objectruanganasalfk
    item.ruanganTujuan = response.head.objectruangantujuanfk
    item.keterangan = headerKirim.keteranganorder
    item.tglorder = new Date(headerKirim.tglorder);

    data2.value = detailKirim
    dataSource.value = data2.value
    isLoadingData.value = false
  } else {

    isSimpan.value = true;
    isLoading.value = true
    const response = await useApi().get(`/logistik/get-detail-order?norec=${NOREC_ORDER}`)
    isSimpan.value = false;
    isLoading.value = false
    isEdit.value = false
    disabledRuangan.value = true;

    let headerKirim = response.head
    let detailKirim = response.detail
    item.namaruangan = { id: headerKirim.objectruanganasalfk, namaruangan: headerKirim.namaruangan }
    item.namaruangan2 = { id: headerKirim.objectruangantujuanfk, namaruangan2: headerKirim.namaruangan2 }
    item.tglorder = new Date(headerKirim.tglorder);

    data2.value = detailKirim
    dataSource.value = data2.value
  }
}
// }

const addPopUp = () => {
  if (!item.ruanganTujuan) {
    H.alert('error', 'Ruangan Tujuan Tidak Boleh Kosong')
    return
  }
  if (!item.jenisKirim) {
    H.alert('warning', 'Jenis Order Tidak Boleh Kosong')
    return
  }
  clearInput()
  isLoading.value = false
  modalInput.value = true
}

const tambah = () => {
  if (!item.produk.id) {
    useToaster().error('Produk harus di isi')
    return
  }
  if (!item.jumlah || item.jumlah == 0) {
    useToaster().error('Jumlah harus di isi')
    return
  }

  console.log(norecSPD.value);
  console.log(item.stok);

  if (item.stok == 0) {
    useToaster().error('Stok tidak ada')
    return
  }

  if (norecSPD.value == '') {
    useToaster().error('Stok tidak ada')
    return
  }


  if (!item.satuan) {
    useToaster().error('Satuan harus di isi')
    return
  }

  let nomor = 0
  if (data2.value.length == 0) {
    nomor = 1
  } else {
    nomor = data2.value.length + 1
  }

  // var qtyOK: any = 0;
  var qtyCetak = 0;
  var total = 0;
  var jumlahreal = 0;

  let jmlbulat = 0;
  let jml = 0;

  jmlbulat = item.jumlahbulat;
  jml = item.jumlah;
  isLoading.value = true
  disabledRuangan.value = true;
  var data: any = {};
  if (item.no != undefined) {
    for (let x = 0; x < data2.value.length; x++) {
      const element = data2.value[x];
      if (element.no == item.no) {
        data.no = item.no
        data.hargajual = String(item.hargaSatuan)
        data.stock = String(item.stok)
        data.harganetto = String(item.hargaSatuan)
        data.nostrukterimafk = norecTerima.value
        data.norec_spd = norecSPD.value
        data.ruanganfk = item.ruanganPengirim.id ? item.ruanganPengirim.id : element.ruanganfk
        data.asalprodukfk = item.asal.id
        data.asalproduk = item.asal.asalproduk
        data.produkfk = item.produk.id
        data.namaproduk = item.produk.namaproduk
        data.nilaikonversi = item.nilaiKonversi
        data.satuanstandarfk = item.satuan.ssid ?  item.satuan.ssid : element.satuanstandarfk
        data.satuanstandar = item.satuan.satuanstandar ? item.satuan.satuanstandar : element.satuanstandar
        data.satuanviewfk = item.satuan.ssid ?  item.satuan.ssid : element.satuanstandarfk
        data.satuanview = item.satuan.satuanstandar ? item.satuan.satuanstandar : element.satuanstandarfk
        data.jmlstok = String(item.stok)
        data.jumlah = item.jumlah
        data.qtyorder = 0
        data.hargasatuan = String(item.hargaSatuan)
        data.hargadiscount = String(item.hargadiskon)
        data.total = item.total
        data2.value[x] = data;
      }
    }

  } else {
    data = {
      no: nomor,
      hargajual: String(item.hargaSatuan),
      stock: String(item.stok),
      harganetto: String(item.hargaSatuan),
      nostrukterimafk: norecTerima.value,
      norec_spd: norecSPD.value,
      ruanganfk: item.ruanganPengirim.id,
      asalprodukfk: item.asal.id,
      asalproduk: item.asal.asalproduk,
      produkfk: item.produk.id,
      namaproduk: item.produk.namaproduk,
      nilaikonversi: item.nilaiKonversi,
      satuanstandarfk:item.satuan.value.ssid,
      satuanstandar: item.satuan.value.satuanstandar,
      satuanviewfk:item.satuan.value.ssid,
      satuanview: item.satuan.value.satuanstandar,
      jmlstok: String(item.stok),
      jumlah: item.jumlah,
      qtyorder: 0,
      hargasatuan: String(item.hargaSatuan),
      hargadiscount: String(item.hargadiskon),
      total: item.total
    }
    data2.value.push(data)

  }
  dataSource.value = data2.value
  isLoading.value = false
  clearInput()
}

const editRow = async (e: any) => {
  await fetchProduk({ query: e.namaproduk })

  item.no = e.no
  d_produk.value.forEach((element: any) => {
    if (element.id == e.produkfk) {
      item.produk = element
      return
    }
  });
  d_satuan.value.forEach(element => {
    if (element.value.ssid == e.satuanstandarfk) {
      item.satuan = element
      return
    }
  });
  dataSelected.value = e
  GETKONVERSI()
  modalInput.value = true
  // item.nilaiKonversi = e.nilaikonversi
}

const hapusRow = (e: any) => {
  for (var i = data2.value.length - 1; i >= 0; i--) {
    if (data2.value[i].no == e.no) {
      data2.value.splice(i, 1);
      // dataOK.value.splice(i, 1);
    }
  }
  dataSource.value = data2.value
  // dataGridKronis.value = dataOK.value

  clearInput()
}

const save = async () => {

  if (data2.value.length == 0) {
    useToaster().error('Produk belum di pilih')
    return
  }

  var Keterangan = 'Order Barang'
  if (item.keterangan != undefined || item.keterangan != '') {
    Keterangan = item.keterangan
  }

  for (var i = data2.value.length - 1; i >= 0; i--) {
    if (parseFloat(data2.value[i].jmlstok) < parseFloat(data2.value[i].jumlah)) {
      useToaster().error("Terdapat obat dengan jumlah melebihi STOK !! " + data2.value[i].namaproduk)
      return
    }
  }

  var strukorder = {
    objectruanganfk: item.ruanganPengirim,
    objectruangantujuanfk: item.ruanganTujuan,
    jenispermintaanfk: item.jenisKirim,
    keteranganorder: Keterangan,
    qtydetailjenisproduk: 0,
    qtyjenisproduk: data2.value.length,
    tglorder: moment(item.tglorder).format('YYYY-MM-DD HH:mm:ss'),
    totalhargasatuan: 0,
    norecorder: NOREC_ORDER ? NOREC_ORDER : '',
    noreckirim: NOREC_KIRIM ? NOREC_KIRIM : '',
  }
  var objSave =
  {
    strukorder: strukorder,
    details: data2.value
  }

  isSimpan.value = true
  await useApi().post(
    `/logistik/save-order-barang`, objSave).then((response: any) => {
      isSimpan.value = false
      window.history.back();
    }, (error) => {
      isSimpan.value = false
      // console.log(error)
    })


}

const changeProduk = (e: any) => {
  if (e != null) {
    GETKONVERSI()
  }
}

const GETKONVERSI = async () => {
  isLoading.value = true
  d_satuan.value = item.produk.konversisatuan
  if (item.produk.konversisatuan.length == 0) {
    d_satuan.value = [
      {
        label: item.produk.satuanstandar, value:
          { ssid: item.produk.ssid, satuanstandar: item.produk.satuanstandar }
      }]
  } else {
    d_satuan.value = item.produk.konversisatuan.map((e: any) => {
      return { label: e.satuanstandar, value: e }
    })
  }
  d_satuan.value.forEach(element => {
    if (item.produk.ssid == element.value.ssid) {
      item.satuan = element
    }
  });
  isReady.value = true
  isLoading.value = true
  item.nilaiKonversi = 1
  dataProdukDetail.value = []
  await useApi().get(
    '/farmasi/get-produkdetail?produkfk=' + item.produk.id +
    '&ruanganfk=' + item.ruanganTujuan).then(function (response: any) {
      if (response.detail.length > 0) {
        if (dataSelected.value.no != undefined) {
          item.jumlah = dataSelected.value.jumlah
          item.qtykonfirmasi = dataSelected.value.jumlah
          item.nilaiKonversi = dataSelected.value.nilaikonversi
        }
        dataProdukDetail.value = response.detail
        item.stok = response.jmlstok / item.nilaiKonversi
        if (response.kekuatan == undefined || response.kekuatan == 0) {
          response.kekuatan = 1
        }
        item.kekuatan = response.kekuatan
        item.sediaan = response.sediaan
        item.tglKadaluarsa = response.detail[0]
        setNorecSPD()
      } else {
        if(!isMerge.value){
          item.jumlah = 1
        }
        // dialogConfirm(item.produk.id)
        item.stok = 0
        item.hargaSatuan = 0
        item.hargadiskon = 0
        item.hargaNetto = 0
        item.total = 0
      }
    });
    isLoading.value = false
  isReady.value = false
}

const clearInput = () => {
  delete item.produk
  delete item.asal
  delete item.satuan
  delete item.no
  delete item.satuanresep
  delete dataSelected.value
  delete item.tglKadaluarsa
  item.qty = 1
  modalInput.value = false
  item.nilaiKonversi = 0
  item.stok = 0
  item.jumlah = 0
  item.jumlahbulat = item.jumlah

}

const setNorecSPD = async () => {

  if(!item.jumlah) return

  if (item.stok == 0) {
    item.jumlah = 0
    return;
  }

  var ada = false;
  for (var i = 0; i < dataProdukDetail.value.length; i++) {
    ada = false
    const element = dataProdukDetail.value[i]

    if (parseFloat(item.jumlah) * parseFloat(item.nilaiKonversi) <= parseFloat(element.qtyproduk)) {
      hrg1.value = Math.round(parseFloat(element.hargajual) * parseFloat(item.nilaiKonversi))
      hrgsdk.value = parseFloat(element.hargadiscount) * parseFloat(item.nilaiKonversi)
      item.hargaSatuan = hrg1.value
      item.hargaNetto = Math.round(parseFloat(element.harganetto) * parseFloat(item.nilaiKonversi))
      if (item.hargadiskon == 0) {
        item.hargadiskon = hrgsdk.value
      } else {
        hrgsdk.value = item.hargadiskon
      }
      item.total = (parseFloat(item.jumlahbulat) * (hrg1.value - hrgsdk.value)) + parseFloat(tarifJasa.value)
      norecTerima.value = element.norec
      norecSPD.value = element.norec_spd
      item.asal = { id: element.objectasalprodukfk, asalproduk: element.asalproduk }
      ada = true;
      break;
    }
  }
  if (ada == false) {
    item.hargaSatuan = 0
    item.hargadiskon = 0
    item.hargaNetto = 0
    item.total = 0

    norecSPD.value = ''
    norecTerima.value = ''
    isMerge.value = false
    if (dataProdukDetail.value.length > 1) {
      const objSave = {
        produkfk: dataProdukDetail.value[0].objectprodukfk,
        ruanganfk: dataProdukDetail.value[0].objectruanganfk
      }

      isSimpan.value = true;
      useApi().postNoMessage(
        '/farmasi/save-stock-merger', objSave).then(function (response: any) {
          isMerge.value = true
          GETKONVERSI()

          isSimpan.value = false
        })
    }
  }
  if (item.jumlah == 0) {
    item.hargaSatuan = 0
    item.hargaNetto = 0
  }
}

const changeSatuan = (e: any) => {
  // console.log(item.satuan)
  item.nilaiKonversi = item.satuan.value.nilaikonversi
}

const fetchJabatan = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/jabatan_m?select=id,namajabatan&param_search=namajabatan&query=${filter.query}&limit=20`
  ).then((response) => {
    d_Jabatan.value = response
  })
}

const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=20`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const fetchProduk = async (filter: any) => {
  const response = await useApi().get(`/logistik/distribusi-barang-produk?namaproduk=${filter.query}&limit=10`)
  d_produk.value = response.produk
}


function back() {
  window.history.back()
}
onInit()


watch(
  () => item.jumlah,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      console.log('cel')
      setNorecSPD()
    }
  }
)

watch(
  () => item.nilaiKonversi,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      if (item.stok > 0) {
        item.stok = parseFloat(item.stok) * (parseFloat(oldValue) / parseFloat(newValue))
        item.jumlahbulat = 0;
        item.jumlah = 0
        item.hargaSatuan = 0
        item.hargadiskon = 0
        item.hargaNetto = 0
        item.total = 0
      }
    }
  }
)


</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.form-layout .form-outer {
  border: 1px solid transparent;
  background-color: transparent;
}

.btn-orderBarang {
  padding: 8px 22px !important;
  height: 38px !important;
  line-height: 1.1 !important;
  font-size: 0.95rem !important;
  font-family: var(--font) !important;
  transition: all 0.3s !important;
}
</style>

