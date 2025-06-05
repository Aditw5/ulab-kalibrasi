<template>
  <div class="column is-12">
    <VCard style="padding-bottom: 0px">
      <div class="column c-title pt-2 mb-5">
        <label class="title-page">Pencarian</label>
      </div>
      <div class="column is-12">
        <div class="columns">
          <div class="column is-4">
            <VField label="Tanggal Struk" style="margin-bottom: 6px;" />
            <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
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
          </div>
          <div class="column is-3">
            <VField class="is-autocomplete-select" label="Kelompok User">
              <VControl icon="feather:search">
                <Multiselect mode="single" v-model="item.kelompok" :options="d_KelompokUser" placeholder="Kelompok User"
                  :searchable="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField label="Nama User">
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.nama" placeholder="Nama User" />
              </VControl>
            </VField>
          </div>
          <div class="column btn-search">
            <VButton type="button" icon="feather:search" :loading="isLoading" @click="cariRiwayat()">
              Cari Data
            </VButton>
          </div>

        </div>
      </div>
    </VCard>
  </div>

  <div class="column is-12">
    <VCard>
      <div class="column c-title pt-2 mb-0">
        <div class="columns p-2">
          <div class="column is-10">
            <label class="title-page">Laporan Sensus Rawat Inap</label>
            <label for="">Laporan Sensus Rawat Inap</label>
          </div>
        </div>
      </div>
      <div class="column is-12 mt-5">
        <div class="column" v-if="isLoading">
          <VPlaceloadWrap v-for="data in 10">
            <VPlaceload class="mx-2 mb-3" />
          </VPlaceloadWrap>
        </div>
        <div class="column" v-else>
          <VPlaceholderPage v-if="d_Log.length == 0" title="Data Tidak di Temukan." subtitle="Silakan gunakan filter lain"
            larger>
            <template #image>
              <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
              <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
            </template>
          </VPlaceholderPage>
          <div v-else>
            <div class="column mt-5">
              <div class="table-scroll">
                <DataTable :value="d_Log" :rows="5" :rowsPerPageOptions="[5, 10, 15]" :loading="isLoading"
                  class="p-datatable-sm" responsiveLayout="stack" breakpoint="960px" selectionMode="single"
                  sortMode="multiple" showGridlines
                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                  paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                  <Column field="no" header="No" style="width:15%"></Column>
                  <Column field="kelompok" header="Nama Pasien"></Column>
                  <Column field="username" header="Nomer Rekam Medis"></Column>
                  <Column field="namalengkap" header="Umur"></Column>
                  <Column field="tanggal" header="Jenis Kelamin"></Column>
                  <Column field="jenis" header="Diagnosa"></Column>
                  <Column field="jenis" header="Tanggal Keluar"></Column>
                  <Column field="jenis" header="Tanggal Masuk"></Column>
                </DataTable>
              </div>
            </div>
          </div>
        </div>
      </div>
    </VCard>
  </div>
</template>
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import moment from 'moment'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'



import { useViewWrapper } from '/@src/stores/viewWrapper'
import { watch } from 'vue';

let isLoading = ref(false)
const d_Log: any = ref([])
const d_KelompokUser: any = ref([]);
const input: any = ref({})
const expandedRows = ref();



useHead({
  title: 'Laporan Sensus Rawat Inap - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)

const item: any = ref({
  aktif: true,
  isKK: false,
  filterTgl: reactive({
    start: new Date(),
    end: new Date(),
  }),
})

async function cariRiwayat() {
  isLoading.value = true;
  let startDate = moment(item.value.filterTgl.start).format('YYYY-MM-DD');
  let endDate = moment(item.value.filterTgl.end).format('YYYY-MM-DD');
  let kelompokuser = item.value.kelompok ?? "";
  let nama = item.value.nama ?? ""
  useApi().get(
    `/pelayanan/get-laporan-sensus-rawat-inap?tglAwal=${startDate}&tglAkhir=${endDate}&kelompok=${kelompokuser}&nama=${nama}`).then((response: any) => {
      d_Log.value = response
      response.data.forEach((element: any, i: any) => {
        element.no = i + 1

      });
      d_Log.value = response.data
      isLoading.value = false
    }).catch((e: any) => {
      isLoading.value = false
    })

}

const getListKelompokUser = async () => {
  const response = await useApi().get(`/emr/dropdown/kelompokuser_s?select=id,kelompokuser`)
  d_KelompokUser.value = response;
}
cariRiwayat();
getListKelompokUser();

</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';

.title-page {
  position: relative;
  font-size: 17px;
  display: block;
  margin-bottom: 3px;
  margin-top: 8px;
  font-weight: 600;
}

.btn-search {
  display: flex;
  align-items: center;
  margin-top: 14px;
}
</style>
