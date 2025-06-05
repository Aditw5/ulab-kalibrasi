
<template>
  <div class="page-content-inner">
    <div class="is-navbar">
      <div class="form-layout">
        <div class="form-outer">
          <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
            <div class="form-header-inner">
              <div class="left">
                <h3>Pasien Meninggal</h3>
              </div>
              <div class="right">
                <div class="buttons">
                  <VButton icon="lnir lnir-arrow-left rem-100"
                    :to="{ name: 'module-registrasi-daftar-pembatalan-pasien' }" light dark-outlined>
                    Cancel
                  </VButton>
                  <VButton type="button" icon="feather:search" :loading="isLoading" color="success" raised
                    @click="cariRiwayat()"> Cari
                  </VButton>
                </div>
              </div>
            </div>
          </div>
          <div class="form-body">
            <!--Fieldset-->
            <div class="form-fieldset">
              <div class="fieldset-heading">
                <h1 style="font-weight: bold; margin-bottom: 1rem;">Filter Pencarian</h1>
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <div class="columns is-multiline mb-3 mt-2">
                        <div class="column is-6">
                          <label style=" margin-bottom: 2rem;">Tanggal Awal
                          </label>
                          <VDatePicker v-model="input.periode" is-range color="pink" trim-weeks class="pt-2">
                            <template #default="{ inputValue, inputEvents }">
                              <VField addons>
                                <VControl icon="feather:calendar">
                                  <VInput :value="inputValue.start" class="input-calendar" v-on="inputEvents.start" />
                                </VControl>
                                <VControl>
                                  <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                                </VControl>
                                <VControl icon="feather:calendar">
                                  <VInput :value="inputValue.end" class="input-calendar" v-on="inputEvents.end" />
                                </VControl>
                              </VField>
                            </template>
                          </VDatePicker>
                        </div>
                        <div class="column is-6">
                          <label style=" margin-bottom: 0.5rem;">No Registrasi
                          </label>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" class="mt-3" v-model="input.noReq"></VInput>
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-6">
                          <label style=" margin-bottom: 0.5rem;">No Rm
                          </label>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" class="mt-3" v-model="input.noCm"></VInput>
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-6">
                          <label style=" margin-bottom: 0.5rem;">Nama Pasien
                          </label>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" class="mt-3" v-model="input.namaPasien">
                              </VInput>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-12">
                      <div class="columns is-multiline">

                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
            <!--Fieldset-->
            <div class="form-fieldset">
              <div class="fieldset-heading mb-5">
                <h1 style="font-weight: bold; margin-bottom: 1rem;">Daftar Pasien Meninggal</h1>
              </div>
              <div class="column" v-if="isLoading">
                <VPlaceloadWrap v-for="data in 10">
                  <VPlaceload class="mx-2 mb-3" />
                </VPlaceloadWrap>
              </div>
              <div class="column" v-else>
                <VPlaceholderPage v-if="d_ListPasien.length == 0" title="Data Tidak di Temukan."
                  subtitle="Silakan gunakan filter lain" larger>
                  <template #image>
                    <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
                    <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
                  </template>
                </VPlaceholderPage>
                <div v-else>
                  <div class="columns is-multiline mt-5">
                    <div class="column is-12">
                      <DataTable :value="d_ListPasien" class="p-datatable-sm" :loading="isLoading" :paginator="true"
                        :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
                        <Column field="nosuratmeninggal" header="No Surat"></Column>
                        <Column field="tglmeninggal" frozen :sortable="true" header="Tgl Meninggal"></Column>
                        <Column field="noregistrasi" header="No Reg"></Column>
                        <Column field="nocm" header="No Rm"></Column>
                        <Column field="namapasien" header="Nama Pasien"></Column>
                        <Column field="penyebabkematian" header="Penyebab Kematian"></Column>
                        <Column field="namaruangan" header="Ruangan Terakhir"></Column>
                      </DataTable>
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
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import moment from 'moment'
const input: any = ref({
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
})
let isLoading = ref(false)
const d_ListPasien: any = ref([])
const dataSourceICD9 = ref([])
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})


useHead({
  title: 'Daftar Pasien Meninggal - ' + import.meta.env.VITE_PROJECT,
})
async function cariRiwayat() {
  let object: any = {}

  object = input.value

  isLoading.value = true;
  let startDate = moment(input.value.periode.start).format('YYYY-MM-DD');
  let endDate = moment(input.value.periode.end).format('YYYY-MM-DD');
  const noReg = object.noReg ? object.noReg : "";
  const noCm = object.noCm ? object.noCm : "";
  const namaPasien = object.namaPasien ? object.namaPasien : "";
  useApi().get(
    `/resgistrasi/get-daftar-pasien-meninggal?tglAwal=${startDate}&tglAkhir=${endDate}&noReg=${noReg}&noCm=${noCm}&namaPasien=${namaPasien}`).then((response: any) => {
      d_ListPasien.value = response.data
      isLoading.value = false
    }).catch((e: any) => {
      isLoading.value = false
    })
}
const setAutoFill = () => {
  input.value.tglAwal = new Date()
  input.value.tglAkhir = new Date()
}

const convertDate = (date) => {
  const dateObject = new Date(date);
  const day = dateObject.getDate().toString().padStart(2, '0');
  const month = (dateObject.getMonth() + 1).toString().padStart(2, '0');
  const year = dateObject.getFullYear();
  const formattedDate = `${year}-${month}-${day}`;
  return formattedDate;
}
cariRiwayat();
setAutoFill();
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.form-layout {
  max-width: 1300px;
  margin: 0 auto;
}

.form-fieldset {
  padding: 10px 0;
  max-width: 100%;
  margin: 0 auto;
}

.table-pi {
  width: 1400px;
  border: 1px solid #929090;
}

.table-scroll {
  overflow-x: scroll;
}

.date {
  background-color: #9b9b9b;
  color: #fff;
}
</style>
