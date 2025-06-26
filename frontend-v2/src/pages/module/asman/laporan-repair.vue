<template>
  <div class="column">
    <div class="business-dashboard hr-dashboard">
      <div class="columns is-multiline">
        <div class="column is-12 p-0">
          <div class="block-header">
            <div class="left column is-6 p-0">
              <div class="current-user">
                <h3>{{ item.namaproduk }}</h3>
              </div>
            </div>
            <div class="left column is-6 p-0">
              <div>
                <div>
                  <h4 class="block-heading">Merk</h4>
                  <p class="block-hext">{{ item.namamerk }}</p>
                  <h4 class="block-heading">Tipe</h4>
                  <p class="block-hext">{{ item.namatipe }}</p>
                </div>
              </div>
            </div>
            <div class="center column is-6 p-0">
              <div>
                <div>
                  <h4 class="block-heading">S/N</h4>
                  <p class="block-hext">{{ item.namaserialnumber }}</p>
                  <h4 class="block-heading">No Order</h4>
                  <p class="block-hext">{{ item.noorderalat }}</p>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <div class="column is-12">
    <TabView class="tabview-custom " :scrollable="true" @tab-click="klikTab($event)">
      <TabPanel>
        <template #header>
          <i class="fas fa-tools mr-2" aria-hidden="true"></i>
          <span>Laporan Repair</span>
          <Badge :value="totalData" v-if="totalData > 0" severity="danger" class="ml-2" />
        </template>
        <VCard>
          <div class="column is-12">
            <div class="columns is-multiline" style="text-align:center">
              <div class="column is-12">
                <h3 class="title is-5 mb-2 mr-1">Data Laporan Repair </h3>
              </div>
            </div>
            <DataTable :value="dataSourceHasilLaporanRepair" :paginator="true" :rows="10"
              :rowsPerPageOptions="[5, 10, 25]" class="p-datatable-customers p-datatable-sm" filterDisplay="menu"
              paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
              responsiveLayout="stack" breakpoint="960px" sortMode="multiple" showGridlines
              currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" :loading="isLoading">
              <template #header>
                <div class="columns is-multiline is-vcentered is-mobile pt-0 pb-0" style="gap: 0.25rem;">
                  <div class="column is-narrow" v-if="dataSourceHasilLaporanRepair.length > 0">
                    <VButtons style="justify-content: space-between;">
                      <VButton color="primary" @click="cetakLaporanRepair()" outlined icon="feather:printer">
                        Cetak Laporan
                      </VButton>
                    </VButtons>
                  </div>
                  <div class="column is-narrow" v-if="dataSourceHasilLaporanRepair.length > 0">
                    <VButton color="info" @click="setujuiLaporan()" outlined icon="feather:save"
                      :loading="isLoadingSave">
                      Setujui Laporan
                    </VButton>
                  </div>
                </div>
              </template>
              <Column field="no" header="No" />
              <Column field="penanganan" header="Penanganan" />
              <Column field="status" header="Status" />
              <Column field="sparepart" header="Sparepart" />
              <Column header="Foto Repair" style="text-align:center; min-width: 150px" row-clickable>
                <template #body="slotProps">
                  <div
                    :style="'background-image:url(' + 'https://ulabumro.id:8000/berkas-laporan-repair/' + slotProps.data.fotoalatrepair + ')'"
                    style="text-align: center; background-position: center; width: 400px; height: 300px;">
                  </div>
                </template>
              </Column>
              <Column field="created_at" header="Tanggal Isi" />
              <Column field="petugasrepair" header="Pengisi" />
              <Column header="Aksi" style="text-align:center; min-width: 150px" row-clickable>
                <template #body="slotProps">
                  <VIconButton color="danger" outlined circle icon="fas fa-trash" @click="hapusLaporan(slotProps.data.fotoalatrepair)"
                    :loading="isLoadingSave" />
                </template>
              </Column>
            </DataTable>
          </div>
        </VCard>
      </TabPanel>
    </TabView>
  </div>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router';
import { ref, computed, watch, reactive } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { useViewWrapper } from '/@src/stores/viewWrapper';
import { useHead } from '@vueuse/head';
import * as H from '/@src/utils/appHelper';
import { useApi } from '/@src/composable/useApi';
import moment from 'moment';
import { useUserSession } from '/@src/stores/userSession'
import * as XLSX from "xlsx";
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';

useHead({
  title: 'Laporan Repair - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const MARKINGSITE: any = ref('')
let NOREC_DETAIL = useRoute().query.norec_detail as string
const isLoadingSave = ref(false)
let loadSearch: any = ref(false)
const dataSource: any = ref([])
const dataSourceHasilLaporanRepair: any = ref([])
const item: any = ref({
  tglkalibrasi: new Date(),
  detailLaporanRepair: [{
    no: 1
  }]
})
const router = useRouter()

const hapusLaporan = async (e: any) => {
  let norec = e.norec;
  isLoadingSave.value = true;
  try {
    await useApi().post(`penyelia/hapus-laporan-repair`, { norec: norec });
    fetchData()
    isLoadingSave.value = false;
  } catch (error) {
    console.error('Error fetching data:', error);
  } finally {
    isLoadingSave.value = false;
  }
}

const setujuiLaporan = async () => {
  let json = {
    'verif': {
      'norec': NOREC_DETAIL,
    }
  }
  isLoadingSave.value = true
  await useApi().post('/asman/save-setujui-laporan-repair', json).then((r) => {
    isLoadingSave.value = false
    toDashboard()
  }).catch((error: any) => {
    isLoadingSave.value = false
    console.error('Error saat menyimpan', error);
    if (error.response) {

      H.alert('error', `Kesalahan: ${error.response.status} - ${error.response.data.message || 'Gagal menyimpan berkas mitra'}`);
    } else if (error.request) {

      H.alert('error', 'Tidak ada respons dari server. Silakan coba lagi.');
    } else {

      H.alert('error', `Terjadi kesalahan: ${error.message}`);
    }
  })
}

const toDashboard = () => {
  router.push({
    name: 'module-dashboard-asman',
  })
}

const fetchData = async () => {
  loadSearch.value = true
  await useApi().get(`asman/get-laporan-repair?norecdetail=${NOREC_DETAIL}`).then((response: any) => {
    response.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataSourceHasilLaporanRepair.value = response
  }).catch((err) => {
    dataSourceHasilLaporanRepair.value = []
  })
  loadSearch.value = false
}

const detailOrder = async () => {
  const response = await useApi().get(`/asman/detail-alat-repair?norec_pd=${NOREC_DETAIL}`)
  const data = response.data

  item.value.namaproduk = data.namaproduk
  item.value.namamerk = data.namamerk
  item.value.namatipe = data.namatipe
  item.value.namaserialnumber = data.namaserialnumber
  item.value.noorderalat = data.noorderalat
  item.value.keterangan = data.keterangan
  item.value.namafile = data.namafile
  item.value.kesimpulan = data.kesimpulanrepair
  MARKINGSITE.value = 'https://ulabumro.id:8000/berkas-mitra/' + data.namafile
}


const cetakLaporanRepair = () => {
  H.printBlade(`asman/cetak-laporan-repair?pdf=true&norec=${dataSourceHasilLaporanRepair.value[0].norecregis}&norec_detail=${NOREC_DETAIL}`);
}

detailOrder()
fetchData()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/dashboard/bedah.scss';

tabs-wrapper.is-slider .tabs,
.tabs-wrapper-alt.is-slider .tabs {
  position: relative;
  background: var(--fade-grey-light-2);
  border: 1px solid var(--fade-grey);
  max-width: 400px;
  height: 35px;
  border-bottom: none;

}

.tb-order .text-value {
  font-family: var(--font-alt);
  color: var(--dark-text);
  font-weight: 400;
  font-size: 12px;
}

.user-grid-v2 {
  .columns {
    margin-left: -0.5rem !important;
    margin-right: -0.5rem !important;
    margin-top: -0.5rem !important;
  }

  .column {
    padding: 0.5rem !important;
  }

  .grid-item {
    @include vuero-s-card;

    text-align: center;

    >.v-avatar {
      display: block;
      margin: 0 auto 4px;
    }

    h3 {
      font-family: var(--font-alt);
      font-size: 0.95rem;
      font-weight: 600;
      color: var(--dark-text);
    }

    p {
      font-size: 0.85rem;
    }

    .people {
      display: flex;
      justify-content: center;
      padding: 8px 0 30px;

      .v-avatar {
        margin: 0 4px;
      }
    }

    .buttons {
      display: flex;
      justify-content: space-between;

      .button {
        width: calc(50% - 4px);
        color: var(--light-text);

        &:hover,
        &:focus {
          border-color: var(--fade-grey-dark-4);
          color: var(--primary);
          box-shadow: var(--light-box-shadow);
        }
      }
    }
  }

  .grid-item-wrap {
    border: 1px solid var(--fade-grey-dark-3);
    border-radius: var(--radius-large);
    transition: all 0.3s; // transition-all test

    .grid-item-head {
      background: #fafafa;
      border-radius: var(--radius-large) 6px 0 0;
      padding: 20px;

      .flex-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;

        .meta {
          span {
            display: flex;

            &:first-child {
              font-family: var(--font-alt);
              font-weight: 600;
              font-size: 0.85rem;
              color: white;
            }

            &:nth-child(2) {
              font-size: 0.8rem;
              color: white;
            }
          }
        }

        .status-icon {
          height: 28px;
          width: 28px;
          min-width: 28px;
          border-radius: var(--radius-rounded);
          border: 1px solid var(--fade-grey-dark-3);
          display: flex;
          align-items: center;
          justify-content: center;

          &.is-success {
            background: var(--success);
            border-color: var(--success);
            color: var(--white);
          }

          &.is-warning {
            background: var(--orange);
            border-color: var(--orange);
            color: var(--white);
          }

          &.is-danger {
            background: var(--danger);
            border-color: var(--danger);
            color: var(--white);
          }

          i {
            font-size: 8px;
          }
        }
      }

      .buttons {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0;

        .button,
        .v-button {
          width: calc(50% - 4px);
          color: var(--light-text);
          margin-bottom: 0;

          &:hover,
          &:focus {
            border-color: var(--fade-grey-dark-4);
            color: var(--primary);
            box-shadow: var(--light-box-shadow);
          }
        }
      }
    }

    .grid-item {
      border-top-left-radius: 0;
      border-top-right-radius: 0;
      border: none;
    }
  }
}

.is-dark {
  .user-grid {
    .grid-item {
      @include vuero-card--dark;
    }
  }

  .user-grid-v2 {
    .grid-item-wrap {
      border-color: var(--dark-sidebar-light-12);

      .grid-item-head {
        background: var(--dark-sidebar-light-4);
      }
    }
  }
}

.user-grid-v2 .grid-item-wrap .grid-item-head.is-registrasi {
  background: var(--success) !important
}

.user-grid-v2 .grid-item-wrap .grid-item-head {
  padding: 10px;
}

.search-menu-rad {
  height: 56px !important;
  white-space: nowrap;
  display: flex;
  flex-shrink: 0;
  align-items: center;
  background-color: white;
  border-radius: 8px;
  width: 100%;
  padding-left: 0.75rem;

  >div:not(:last-of-type) {
    border-right: 1px solid var(--search-border-color);
  }

  .search-bar {
    height: 55px;
    width: 100%;
    position: relative;
    display: flex;
    align-items: center;
    padding-right: 1.5rem;

    .field {
      width: 100%;
    }

    .multiselect-tags {
      padding-left: 2.5rem;
    }
  }

  .search-location-rad,
  .search-job,
  .search-salary {
    display: flex;
    align-items: center;
    width: 50%;
    font-size: 14px;
    font-weight: 500;
    padding: 0 25px;
    height: 100%;
    font-family: var(--font);

    input {
      width: 100%;
      height: 90%;
      display: block;
      font-family: var(--font);
      color: var(--input-color);
      background-color: transparent;
      border: none;
    }

    svg {
      margin-right: 0.5rem;
      width: 18px;
      color: var(--primary);
      flex-shrink: 0;
    }
  }

  .search-button-rad {
    background-color: var(--primary);
    min-width: 100px;
    height: 56px !important;
    border: none;
    font-weight: 500;
    font-family: var(--font);
    padding: 0 1rem;
    border-radius: 0 0.75rem 0.75rem 0;
    color: white;
    cursor: pointer;
    margin-left: auto;
  }
}

.search-widget {
  flex: 1;
  display: inline-block;
  width: 100%;
  padding: 10px;
  background-color: var(--white);
  border-radius: 16px;
  border: 1px solid var(--fade-grey-dark-3);
  transition: all 0.3s;
}
</style>
