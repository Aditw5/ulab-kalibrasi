<template>
  <ConfirmDialog />
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>Hasil Laboratorium Microbilogi</h3>
          </div>
          <div class="right">
            <div class="buttons">
              <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="backPage()">
                Kembali
              </VButton>
            </div>
          </div>
        </div>
      </div>

      <div class="form-body p-2">
        <div class="business-dashboard hr-dashboard">
          <div class="columns is-multiline">
            <div class="column is-12" v-if="!isLoadingPasien">
              <div class="block-header">
                <div class="left">
                  <div class="current-user">
                    <VAvatar size="medium" :picture="pasien.jeniskelamin == 'PEREMPUAN'
                      ? '/images/avatars/svg/vuero-4.svg'
                      : '/images/avatars/svg/vuero-1.svg'
                      " squared />
                    <h3>{{ pasien.namapasien }}</h3>
                  </div>
                </div>
                <div class="center">
                  <div class="columns">
                    <div class="column">
                      <h4 class="block-heading">No RM</h4>
                      <p class="block-text">{{ pasien.nocm }}</p>
                      <h4 class="block-heading">Tgl Lahir</h4>
                      <p class="block-text">{{ pasien.tgllahir }}</p>
                    </div>
                    <div class="column">
                      <h4 class="block-heading">NIK</h4>
                      <p class="block-text">{{ pasien.noidentitas }}</p>
                      <h4 class="block-heading">Kelamin</h4>
                      <p class="block-text">{{ pasien.jeniskelamin }}</p>
                    </div>
                  </div>
                </div>
                <div class="right">
                  <div class="columns">
                    <div class="column">
                      <h4 class="block-heading">No HP</h4>
                      <p class="block-text">{{ pasien.nohp }}</p>
                      <h4 class="block-heading">Alamat</h4>
                      <p class="block-text">{{ pasien.alamatlengkap }}</p>
                    </div>
                    <div class="column">
                      <h4 class="block-heading">Umur</h4>
                      <VTag color="orange" :label="pasien.umur" />
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="form-body p-2">

  </div>



  <VCard>

    <!-- <div class="card p-fluid"> -->
    <div class="form-body p-2">
      <div class="columns is-multiline">
        <div class="colum is-12 w-full">
          <!-- <VButton type="success" color="success" raised rounded icon="feather:send" class="is-pulled-right" v-if="dataSourceRiwayat.length !== 0" @click="kirimWA(item)">
            Kirim Wa
          </VButton> -->
          <VButton type="button" color="info" raised rounded icon="feather:printer" class="is-pulled-right" v-if="item.hasil = ! null" @click="printHasil(item)">
            Cetak
          </VButton>
        </div>
        <div class="column is-12">
          <DataTable :value="dataSourceRiwayat" class="p-datatable-sm" editMode="cell" v-model:expandedRowGroups="expandedRowGroups"
            expandableRowGroups rowGroupMode="subheader" groupRowsBy="Isolate"
            tableClass="editable-cells-table"
            breakpoint="960px" sortMode="multiple" currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
            showGridlines >
            <template #header>
                <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                    <span class="text-xl text-900 font-bold">Organisme</span>
                </div>
            </template>
            <template #empty> Hasil Lab Belum Tersedia. </template>
            <template #groupheader="slotProps">
              <span class="vertical-align-middle ml-2 font-bold line-height-3">{{ slotProps.data.Isolate }}</span>
            </template>
            <Column field="Isolate" header="Isolate"></Column>
            <Column field="BioNumber" header="BioNumber"></Column>
            <Column field="Method" header="Method"></Column>
            <Column field="Organism" header="Organism"></Column>
            <Column field="Speciment" header="Speciment"></Column>
            <Column field="Notes" header="Notes"></Column>
            <Column field="Conclusion" header="Conclusion"></Column>
          </DataTable>
        </div>
        <div class="column is-12">
          <DataTable :value="dataSourceRiwayat2" class="p-datatable-sm" editMode="cell" v-model:expandedRowGroups="expandedRowGroups2"
            expandableRowGroups rowGroupMode="subheader" groupRowsBy="Isolate"
            tableClass="editable-cells-table"
            breakpoint="960px" sortMode="multiple" currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
            showGridlines >
            <template #header>
                <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                    <span class="text-xl text-900 font-bold">Antibiotic</span>
                </div>
            </template>
            <template #empty> Hasil Lab Belum Tersedia. </template>
            <template #groupheader="slotProps">
              <span class="vertical-align-middle ml-2 font-bold line-height-3">{{ slotProps.data.Isolate }}</span>
            </template>
            <Column field="Isolate" header="Isolate"></Column>
            <Column field="Antibiotic" header="Antibiotic"></Column>
            <Column field="MicResult" header="MicResult"></Column>
            <Column field="DiscResult" header="DiscResult"></Column>
            <Column field="IntepretasiMic" header="IntepretasiMic"></Column>
            <Column field="IntepretasiDisc" header="IntepretasiDisc"></Column>
            <Column field="Intermediet" header="Intermediet"></Column>
            <Column field="Sensitive" header="Sensitive"></Column>
            <Column field="Resistance" header="Resistance"></Column>
            <Column field="Sequence" header="Sequence"></Column>
          </DataTable>
        </div>
      </div>
    </div>
  </VCard>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import Dropdown from 'primevue/dropdown'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Badge from 'primevue/badge';
import InputText from 'primevue/inputtext';

useHead({
  title: 'Hasil Laboratorium - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let NOREC_APD = useRoute().query.norec_apd as string
let NOORDER = useRoute().query.noorder as string
let NOCM = useRoute().query.nocm as string
let pasien: any = ref({})
const confirm = useConfirm()
const isLoadingPasien: any = ref(false)
const isLoadingPop: any = ref(false)
const isLoadingTT = ref(false)
const isHasilBridgingNew = ref(false)

const dataSelect: any = ref({})
const expandedRowGroups = ref();
const expandedRowGroups2 = ref();
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: NOREC_APD != undefined ? NOREC_APD : '',
  tglorder: new Date(),
  totalAll: 0

})

const dataSourceRiwayat: any = ref([])
const dataSourceRiwayat2: any = ref([])
const dataSourceRiwayatKirimWA: any = ref('')
const modalHasilLab: any = ref(false)
const router = useRouter()
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
let listColor: any = ref(Object.keys(useThemeColors()))
const pasienByID = async (id: any) => {
  isLoadingPasien.value = true
  useApi()
    .get(`/dashboard/headerpasien?nocmfk=${id}&norec_pd=${item.NOREC_PD}&norec_apd=${item.NOREC_APD}`)
    .then((response: any) => {
      pasien.value = response.pasien
      item.noregistrasi = response.last_registrasi.noregistrasi
      item.NOREC_APD = response.last_registrasi.norec_apd
      item.RUANGAN_LAST = response.last_registrasi.objectruanganfk
      item.registrasi = response.last_registrasi
      isLoadingPasien.value = false
    })
}

console.log(item);

const printHasil = async (e: any) => {
  H.printBlade(`report/cetak-hasil-mikro?norec=${item.norecMikro}`);
}

const fetchHasilLab = async () => {
  dataSourceRiwayat.value = []
  expandedRowGroups.value = []
  dataSourceRiwayat2.value = []
  expandedRowGroups2.value = []
  dataSourceRiwayat.value.loading = true
  await useApi()
    // .get(`/laboratorium/get-hasil-bridging?norec_apd=${item.NOREC_APD}&nocmfk=${ID_PASIEN}&noorder=${NOORDER}`)
    .get(`/bridging/penunjang/get-hasil-microbiologi?norec_apd=${item.NOREC_APD}&nocmfk=${ID_PASIEN}&noorder=${NOORDER}&nocm=${NOCM}`)
    .then(async (response: any) => {
      var Organisme = []
      var Antibiotic = []
      response.forEach((element: any, i: any) => {
        let organisme = element.Organisme
        let antibiotic = element.Antibiotic

        organisme.forEach((element: any, i: any) => {
          Organisme.push(element)
        })

        antibiotic.forEach((element: any, i: any) => {
          Antibiotic.push(element)
        })
      });

      Organisme.forEach((element: any, i: any) => {
        element.no = i + 1
        expandedRowGroups.value.push(element.Isolate)
      });

      Antibiotic.forEach((element: any, i: any) => {
        element.no = i + 1
        expandedRowGroups2.value.push(element.Isolate)
      });

      dataSourceRiwayat.value = Organisme //response.data
      dataSourceRiwayat2.value = Antibiotic //response.data
      dataSourceRiwayat.value.loading = false
    })
}

const backPage = () => {
  window.history.back()
}
pasienByID(ID_PASIEN)
fetchHasilLab()
</script>

<style lang="scss" scoped>
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';

::v-deep(.editable-cells-table td.p-cell-editing) {
  padding-top: 0;
  padding-bottom: 0;
}

.list-view-v1 {
  .list-view-item {
    @include vuero-r-card;

    margin-bottom: 16px;
    padding: 16px;

    .list-view-item-inner {
      display: flex;
      align-items: center;

      .meta-left {
        margin-left: 16px;

        h3 {
          font-family: var(--font-alt);
          color: var(--dark-text);
          font-weight: 600;
          font-size: 1rem;
          line-height: 1;
        }

        >span:not(.tag) {
          font-size: 0.9rem;
          color: var(--light-text);

          svg {
            height: 12px;
            width: 12px;
          }
        }
      }

      .meta-right {
        margin-left: auto;
        display: flex;
        justify-content: flex-end;
        align-items: center;

        .tags {
          margin-right: 30px;
          margin-bottom: 0;

          .tag {
            margin-bottom: 0;
          }
        }

        .stats {
          display: flex;
          align-items: center;
          margin-right: 30px;

          .stat {
            display: flex;
            align-items: center;
            flex-direction: column;
            text-align: center;
            color: var(--light-text);

            >span {
              font-family: var(--font);

              &:first-child {
                font-size: 1.2rem;
                font-weight: 600;
                color: var(--dark-text);
                line-height: 1.4;
              }

              &:nth-child(2) {
                text-transform: uppercase;
                font-family: var(--font-alt);
                font-size: 0.75rem;
              }
            }

            svg {
              height: 16px;
              width: 16px;
            }

            i {
              font-size: 1.4rem;
            }
          }

          .separator {
            height: 25px;
            width: 2px;
            border-right: 1px solid var(--fade-grey-dark-3);
            margin: 0 16px;
          }
        }

        .network {
          display: flex;
          justify-content: flex-end;
          align-items: center;
          min-width: 145px;

          >span {
            font-family: var(--font);
            font-size: 0.9rem;
            color: var(--light-text);
            margin-left: 6px;
          }
        }

        .dropdown {
          margin-left: 30px;
        }
      }
    }
  }
}

.is-dark {
  .list-view-v1 {
    .list-view-item {
      @include vuero-card--dark;

      .list-view-item-inner {
        .meta-left {
          h3 {
            color: var(--dark-dark-text) !important;
          }
        }

        .meta-right {
          .stats {
            .stat {
              span {
                &:first-child {
                  color: var(--dark-dark-text);
                }
              }
            }

            .separator {
              border-color: var(--dark-sidebar-light-16) !important;
            }
          }
        }
      }
    }
  }
}

@media only screen and (max-width: 767px) {
  .list-view-v1 {
    .list-view-item {
      .list-view-item-inner {
        position: relative;
        flex-direction: column;

        .v-avatar {
          margin-bottom: 10px;
        }

        .meta-left {
          margin-left: 0;
        }

        .meta-right {
          flex-direction: column;
          margin-left: 0;

          .tags {
            margin: 10px 0;
          }

          .stats {
            margin: 10px 0;
          }

          .network {
            margin: 10px 0 0;
            justify-content: center;

            >span {
              display: none;
            }
          }

          .dropdown {
            position: absolute;
            top: 0;
            right: 0;
            margin-left: 0;
          }
        }
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .list-view-v1 {
    display: flex;
    flex-wrap: wrap;

    .list-view-item {
      margin: 10px;
      width: calc(50% - 20px);

      .list-view-item-inner {
        position: relative;
        flex-direction: column;

        .v-avatar {
          margin-bottom: 10px;
        }

        .meta-left {
          margin-left: 0;
        }

        .meta-right {
          flex-direction: column;
          margin-left: 0;

          .tags {
            margin: 10px 0;
          }

          .stats {
            margin: 10px 0;
          }

          .network {
            margin: 10px 0 0;
            justify-content: center;

            >span {
              display: none;
            }
          }

          .dropdown {
            position: absolute;
            top: 0;
            right: 0;
            margin-left: 0;
          }
        }
      }
    }
  }
}


.is-dark .td-label {

  color: var(--dark-dark-text);
}

.list-widget {
  @include vuero-l-card;

  padding: 30px;

  &:not(:last-child) {
    margin-bottom: 1.5rem;
  }

  &.is-straight {
    @include vuero-s-card;
  }

  .widget-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 32px;
    margin-bottom: 10px;

    h3 {
      color: var(--dark-text);
      font-size: 1.1rem;
      font-weight: 500;
    }
  }

  .inner-list {
    padding: 10px 0;

    .inner-list-item {
      +.inner-list-item {
        margin-top: 24px;
      }
    }
  }
}

.is-dark {
  .list-widget {
    @include vuero-card--dark;
  }
}

.tb-order {
  color: var(--light-text-dark-10);
  font-family: var(--font);
  font-weight: 300;

  .text-value {
    font-family: var(--font-alt);
    color: var(--dark-text);
    font-weight: 600;
  }

  td {
    padding: 0 3px 0 0;
  }
}

.list-widget {
  .icon-timeline {
    .timeline-item {
      position: relative;
      display: flex;
      padding-bottom: 30px;

      &::after {
        content: '';
        position: absolute;
        top: 36px;
        left: 18px;
        width: 1px;
        height: calc(100% - 36px);
        border-left: 1px solid var(--fade-grey-dark-3);
      }

      .timeline-icon {
        position: relative;
        // height: 36px;
        height: auto;
        width: 36px;
        display: flex;
        justify-content: center;
        align-items: center;
        background: var(--white);
        border: 1px solid var(--fade-grey-dark-3);
        border-radius: var(--radius-rounded);
        color: var(--light-text);
        box-shadow: var(--light-box-shadow);

        &::after {
          content: '';
          position: absolute;
          top: 17px;
          left: 40px;
          width: 20px;
          height: 1px;
          border-top: 1px solid var(--fade-grey-dark-3);
        }

        &.is-squared {
          border-radius: 10px;

          img {
            border-radius: 10px;
          }
        }

        &.is-primary {
          background: var(--primary);
          border-color: var(--primary);
          box-shadow: var(--primary-box-shadow);

          svg {
            color: var(--smoke-white);
          }
        }

        &.is-info {
          background: var(--info);
          border-color: var(--info);
          box-shadow: var(--info-box-shadow);

          svg {
            color: var(--smoke-white);
          }
        }

        &.is-success {
          background: var(--success);
          border-color: var(--success);
          box-shadow: var(--success-box-shadow);

          svg {
            color: var(--smoke-white);
          }
        }

        &.is-orange {
          background: var(--orange);
          border-color: var(--orange);
          box-shadow: var(--orange-box-shadow);

          svg {
            color: var(--smoke-white);
          }
        }

        &.is-yellow {
          background: var(--yellow);
          border-color: var(--yellow);

          svg {
            color: var(--smoke-white);
          }
        }

        img {
          display: block;
          height: 28px;
          width: 28px;
          border-radius: var(--radius-rounded);
        }

        svg {
          height: 16px;
          width: 16px;
          stroke-width: 1.6px;
        }
      }

      .timeline-content {
        margin-left: 34px;
        line-height: 1.2;
        width: 100%;

        span {
          font-size: 0.85rem;
          color: var(--light-text);

        }

        .is-danger {
          span.icon {
            color: var(--danger--color-invert);
          }

        }

        .is-warning {
          span.icon {
            color: var(--warning--color-invert);
          }

        }

        span.td-label {
          color: var(--dark-text);
        }

        p {
          font-family: var(--font-alt);
          font-size: 0.95rem;
          font-weight: 500;
          color: var(--dark-text);
          width: 220px;
          white-space: nowrap;
          overflow: hidden !important;
          text-overflow: ellipsis;
        }
      }
    }
  }
}

.is-dark {
  .list-widget {
    .icon-timeline {
      .timeline-item {
        &::after {
          border-color: var(--dark-sidebar-light-12) !important;
        }

        .timeline-icon:not(.is-primary):not(.is-info):not(.is-success):not(.is-orange):not(.is-yellow) {
          background: var(--dark-sidebar-light-3) !important;
          border-color: var(--dark-sidebar-light-12) !important;
        }

        .timeline-icon {
          &::after {
            border-color: var(--dark-sidebar-light-12) !important;
          }

          &.is-primary {
            background: var(--primary);
            border-color: var(--primary);
            box-shadow: var(--primary-box-shadow);

            svg {
              color: var(--smoke-white);
            }
          }
        }

        .timeline-content {
          p {
            color: var(--dark-dark-text);
          }
        }
      }
    }
  }
}

.timeline-wrapper .timeline-wrapper-inner .timeline-container .timeline-item::before {
  display: none;
}

.tile-grid-v2 {
  .tile-grid-item {
    @include vuero-s-card;

    border-radius: 14px;
    padding: 16px;
    cursor: pointer;

    &:hover,
    &:focus {
      border-color: var(--primary);
      box-shadow: var(--light-box-shadow);
    }

    .tile-grid-item-inner {
      display: flex;
      align-items: center;

      >img {
        display: block;
        width: 50px;
        height: 50px;
        min-width: 50px;
      }

      .meta {
        margin-left: 10px;
        line-height: 1.4;

        span {
          display: block;
          font-family: var(--font);

          &:first-child {
            color: var(--dark-text);
            font-family: var(--font-alt);
            font-weight: 600;
            font-size: 0.8rem;
          }

          &:nth-child(2) {
            display: flex;
            align-items: center;

            span {
              display: inline-block;
              color: var(--light-text);
              font-size: 0.5rem;
              font-weight: 400;
            }

            .icon-separator {
              position: relative;
              font-size: 4px;
              color: var(--light-text);
              padding: 0 6px;
            }
          }
        }
      }

      .dropdown {
        margin-left: auto;
      }
    }
  }
}
</style>
