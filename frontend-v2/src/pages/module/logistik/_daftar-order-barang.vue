

<template>
  <ConfirmDialog />
  <VCard>
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Daftar Order Barang </h3>
    </div>
    <VButton color="info" RouterLink :to="{ name: 'module-logistik-order-barang' }" raised style="margin-left: 40rem;"
      class="btn-daftarOB" icon="fas fa-plus">Order Barang</VButton>
    <div class="business-dashboard flights-dashboard">
      <div class="columns">
        <div class="column is-8">

          <div class="flights" v-for="(items, i) in ds_Barang" :key="items.id">
            <!--Flight-->

            <a class="flight-card">

              <VTag :label="items.status" color="primary" />


              <div class="start">
                <span>{{ items.namaruanganasal }}</span>
                <span>Ruang Asal</span>

              </div>

              <div class="route">
                <div class="departure"></div>
                <div class="line" :data-content="H.formatDateIndo(items.tglorder)"></div>


                <div class="arrival" style="transform: none;">
                  <i aria-hidden="true" class="fas fa-dolly"></i>
                </div>
              </div>

              <div class="end">

                <span>{{ items.namaruangantujuan }}</span>
                <span>Ruang Tujuan</span>
              </div>

            </a>
            <div class="flights-summary-wrapper" style="margin-top: -2.5rem;">
              <div class="columns is-flex-tablet-p">
                <div class="column is-12">
                  <a class="flight-summary">
                    <div class="collapse-icon is-clickable" @click="items.isExpand = true" v-if="!items.isExpand">
                      <VIcon icon="feather:chevron-down" />
                    </div>
                    <div class="collapse-icon  is-clickable mr-1 " open @click="items.isExpand = false"
                      v-if="items.isExpand">
                      <VIcon icon="feather:chevron-up" />
                    </div>

                    <div class="column is-2 meta">
                      <span>{{ items.jeniskirim }}</span>
                      <span>Jenis Order</span>
                    </div>
                    <div class="column is-3 meta ml-0">
                      <span>{{ items.noorder }}</span>
                      <span>Nomor Order</span>
                    </div>
                    <div class="column is-3 meta ml-0">
                      <span>{{ items.status == 'Kirim Order Barang' && items.statusorder == 'Belum Kirim' ? 'Belum Terima' : items.statusorder }}</span>
                      <span>Status Order</span>
                    </div>
                    <div class="column meta pr-0 pl-0 mr-0 ml-0">
                      <VButtons style="justify-content:center">
                        <VIconButton v-tooltip.bottom.right="'Kirim Barang'" v-if="items.status == 'Terima Order Barang'"
                          icon="feather:arrow-right" color="info" raised circle class="mr-2" @click="kirimOrder(items)"
                          :disabled="items.statusorder == 'Belum Kirim' || items.statusorder == 'Batal Kirim' ? false : true">
                        </VIconButton>
                        <VIconButton v-tooltip.bottom.right="'Cetak Bukti'" icon="feather:printer" color="primary" raised
                        circle class="mr-2" @click="cetakBuktiKirim(items)">
                       </VIconButton>
                        <VIconButton v-tooltip.bottom.left="'Edit Kirim Barang'" icon="feather:edit" color="warning" v-if="items.status == 'Terima Order Barang'"
                          raised circle class="mr-2" :disabled="items.statusorder == 'Sudah Kirim'  ? false : true"
                          @click="editKirimBarang(items)">
                        </VIconButton>
                        <VIconButton v-tooltip.bottom.left="'Edit Order Barang'" icon="feather:edit" color="warning" v-else
                          raised circle class="mr-2"
                          @click="editOrder(items)" :disabled="items.statusorder == 'Belum Kirim' ? false : true">
                        </VIconButton>
                        <!-- <VIconButton v-tooltip.top-right="'Batal Kirim Barang'" icon="feather:trash" color="danger" raised v-if="items.status == 'Terima Order Barang'"
                          circle class="mr-2" @click="batalKirimBarang(items)">
                        </VIconButton> -->
                        <VIconButton v-tooltip.top-right="'Batal Kirim Barang'" icon="feather:trash" color="danger" raised v-if="items.status == 'Terima Order Barang'"
                          circle class="mr-2" @click="DialogConfirm(items,'batal kirim')" :disabled="items.statusorder == 'Sudah Kirim'  ? false : true" :loading="isLoading">
                        </VIconButton>
                        <VIconButton v-tooltip.top-right="'Hapus Order Barang'" icon="feather:trash" color="danger" raised v-else
                          circle class="mr-2" @click="DialogConfirm(items,'hapus order')" :loading="isLoading" :disabled="items.statusorder == 'Terverifikasi' || items.statusorder == 'Sudah Kirim' ? true : false">
                        </VIconButton>
                       
                        <VIconButton v-tooltip.bottom.right="'Verifikasi'"
                          v-if="items.statusorder != 'Belum Kirim' && items.status == 'Kirim Order Barang'"
                          color="warning" outlined circle icon="fas fa-check" @click="verifOrder(items)"
                          :disabled="items.statusorder == 'Terverifikasi' || items.statusorder == 'Batal Kirim' ? true : false" />
                      </VButtons>
                    </div>

                  </a>
                </div>
              </div>
              <div class="columns is-flex-tablet-p" style="margin-top: -2.5rem;" v-if="items.isExpand">
                <div class="column is-12">
                  <a class="flight-summary">
                    <div class="content-wrap is-grey is-fullwidth" style="width: 100%;">
                      <VCard custom="card-green" class="mt-1">
                        <div class="columns is-multiline " v-for="(itemsDet, index2)  in items.details" :key="index2">
                          <div class="column is-8">
                            <VField>
                              <VLabelText>Nama Produk
                              </VLabelText>
                              <label>{{ itemsDet.namaproduk }} |
                                {{ itemsDet.satuanstandar }}
                              </label>
                            </VField>
                          </div>
                          <div class="column is-2">
                            <VField>
                              <VLabelText>Qty Order</VLabelText>
                              <label>{{ itemsDet.qtyproduk }}
                              </label>
                            </VField>
                          </div>
                          <div class="column is-2">
                            <VField>
                              <VLabelText>Qty Konfirmasi </VLabelText>
                              <label>{{ itemsDet.qtyprodukkonfirmasi }}
                              </label>
                            </VField>
                          </div>
                        </div>
                      </VCard>

                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>



        <div class="column is-4" style="margin-top : -3rem">
          <Vcard>
            <div class="columns is-multiline">
              <div class="column is-12">
                <VField>
                  <VControl icon="feather:search">
                    <input v-model="item.noorder" v-on:keyup.enter="filter()" type="text" class="input is-rounded"
                      placeholder="Cari Nomor Order..." />
                  </VControl>
                </VField>
              </div>
              <div class="column is-6">
                <h3 class="title is-5 mb-2 mr-1">Filter </h3>
              </div>
              <div class="column is-6">
                <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined raised> Clear
                  All </a>
              </div>
              <div class="column is-12">
                <VField>
                  <VLabel>Tanggal Order Barang</VLabel>
                  <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField addons>
                        <VControl icon="feather:calendar">
                          <VInput :value="inputValue.start" v-on="inputEvents.start" />
                        </VControl>
                        <VControl>
                          <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                        </VControl>
                        <VControl icon="feather:calendar">
                          <VInput :value="inputValue.end" v-on="inputEvents.end" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
              <div class="column is-12">
                <VField class="is-autocomplete-select" v-slot="{ id }">
                  <VLabel>Produk</VLabel>
                  <VControl icon="feather:search">
                    <Multiselect mode="single" v-model="item.produkfk" :options="d_produk" placeholder="Pilih data"
                      :searchable="true" :attrs="{ id }" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">
                <VField class="is-autocomplete-select" v-slot="{ id }">
                  <VLabel>Ruangan Asal</VLabel>
                  <VControl icon="feather:search">
                    <Multiselect mode="single" v-model="item.ruanganasalfk" :options="d_rutu" placeholder="Pilih data"
                      :searchable="true" :attrs="{ id }" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">
                <VField class="is-autocomplete-select" v-slot="{ id }">
                  <VLabel>Ruangan Tujuan</VLabel>
                  <VControl icon="feather:search">
                    <Multiselect mode="single" v-model="item.ruangantujuanfk" :options="d_ruangan" placeholder="Pilih data"
                      :searchable="true" :attrs="{ id }" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">
                <VButton @click="filter()" :loading="ds_Barang.loading" type="button" icon="feather:search"
                  class="is-fullwidth mr-3" color="info" raised> Cari Data
                </VButton>
              </div>
            </div>
          </Vcard>
        </div>


      </div>
    </div>
  </VCard>
</template>


<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useConfirm } from 'primevue/useconfirm'
import ConfirmDialog from 'primevue/confirmdialog'
import * as H from '/@src/utils/appHelper'
import moment from 'moment'
import { async } from '@firebase/util'
useHead({
  title: 'Daftar Order Barang - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)
const item: any = ref({
  filterTgl: reactive({
    start: new Date(),
    end: new Date(),
  })
})

let listKelompokPasien: any = ref([])

let ds_Barang: any = ref([])
let listColor: any = ref(Object.keys(useThemeColors()))
let isLoading: any = ref(false)
const router = useRouter()
const confirm = useConfirm()
const route = useRoute()
const d_ruangan: any = ref([])
const d_rutu: any = ref([])
const d_produk: any = ref([])
const d_satuan: any = ref([])
const d_jenisKirim: any = ref([])
const d_satuanResep: any = ref([])
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const currentPage: any = ref({
  limit: 5,
  rows: 50
})

currentPage.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})
watch(currentPage.value, () => {
  fetchOrder()
})

const fetchOrder = async () => {
  ds_Barang.value.loading = true

  let noorder = ''
  if (item.value.noorder) {
    noorder = `&noorder=${item.value.noorder}`
  }
  let ruanganasalfk = ''
  if (item.value.ruanganasalfk) {
    ruanganasalfk = `&ruanganasalfk=${item.value.ruanganasalfk}`
  }
  let ruangantujuanfk = ''
  if (item.value.ruangantujuanfk) {
    ruangantujuanfk = `&ruangantujuanfk=${item.value.ruangantujuanfk}`
  }
  let produkfk = ''
  if (item.value.produkfk) {
    produkfk = `&produkfk=${item.value.produkfk}`
  }
  let dari = ''
  if (item.value.filterTgl.start) {
    dari = `?tglAwal=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}`
  }
  let sampai = ''
  if (item.value.filterTgl.end) {
    sampai = `&tglAkhir=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}`
  }
  await useApi().get(
    `/logistik/get-order-barang${dari}${sampai}${noorder}${produkfk}${ruanganasalfk}${ruangantujuanfk}`).then((response) => {
      response.daftar.forEach((element: any, i: any) => {
        element.namaproduk = element.details
        element.jumlah = element.details

      });
      ds_Barang.value.loading = false
      ds_Barang.value = response.daftar
    })
}

const batalKirimBarang = async (e: any) => {

  let objSave = {
    norec_so : e.norec,
    ruangantujuanfk : e.ruangantujuanfk,
    ruanganasalfk : e.ruanganasalfk,
    namaruanganasal : e.namaruanganasal,
    namaruangantujuan : e.namaruangantujuan,
    details : e.details
  } 
  isLoading.value = true
  await useApi().post('/logistik/batal-kirim-order-barang',objSave).then((response)=>{
    isLoading.value = false
     fetchOrder()
  }).catch((e)=>{
    isLoading.value = false
  })
}
const hapusItems = async (e: any) => {
  useApi().post(
    `/logistik/hapus-order-barang`, { norec: e.norec }).then((response: any) => {
      isLoading.value = false
      fetchOrder()
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const DialogConfirm = (e: any,info:any) => {
  let message = info == 'batal kirim' ? 'Apakah Anda yakin akan membatalkan kirim barang ini ?' : 'Apakah Anda yakin menghapus data ini ?'
  let konfirm = info == 'batal kirim' ? 'Konfirmasi Batal Kirim' : 'Konfirmasi Hapus Order'
  confirm.require({
    message: message,
    header: konfirm,
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      // e.loadBtnHapus.value = true
      if(info == 'batal kirim'){
        batalKirimBarang(e)
      }else{
         hapusItems(e)
      }
      //  e.loadBtnHapus.value = true

    },
    reject: () => { },
  })
}

const editOrder = (e: any) => {
  router.push({
    name: 'module-logistik-order-barang',
    query: {
      norec: e.norec,
    },
  })
}

const editKirimBarang = (e: any) => {
   router.push({
    name: 'module-logistik-distribusi-barang',
    query: {
      iseditkirim : true,
      norec_order: e.norec,
    },
  })
}

const kirimOrder = (e: any) => {
  if (e.statusorder == 'Sudah Kirim') {
    H.alert('error', 'Barang Sudah Dikirim')
    return
  }
  router.push({
    name: 'module-logistik-distribusi-barang',
    query: {
      norec_order: e.norec,
    },
  })
}

const verifOrder = (e: any) => {
  router.push({
    name: 'module-logistik-verifikasi-pengiriman-barang',
    query: {
      norec_order: e.norec,
    },
  })
}

const loadDrop = async () => {
  const response = await useApi().get(
    `/logistik/list-order-cbo`)
  // d_produk.value = response.produk.map((e: any) => { return { label: e.namaproduk, value: e.id } })
  d_ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id } })
  d_jenisKirim.value = response.jenis.map((e: any) => { return { label: e.jenis, value: e.id } })
  d_satuanResep.value = response.satuanresep.map((e: any) => { return { label: e.satuanresep, value: e } })
  d_rutu.value = response.rutu.map((e: any) => { return { label: e.namaruangan, value: e.id } })
}

const cetakBuktiKirim = (e: any) => {
  H.printBlade(`logistik/cetak-bukti-kirim?norec=${e.norec}&noorder=${e.noorder}&jenis=order`)
}

const clearFilter = () => {
  delete item.value.noorder
  delete item.value.ruangantujuanfk
  delete item.value.produkfk
  delete item.value.ruanganasalfk

  fetchOrder()
}
const filter = () => {
  fetchOrder()
}

fetchOrder()
loadDrop()

</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/custom/config';
@import '/@src/scss/module/logistik/orderbarang.scss';

.btn-daftarOB {
  padding: 8px 22px !important;
  height: 38px !important;
  line-height: 1.1 !important;
  font-size: 0.95rem !important;
  font-family: var(--font) !important;
  transition: all 0.3s !important;
}</style>
