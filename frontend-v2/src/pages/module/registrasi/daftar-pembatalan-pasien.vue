
<template>
  <div class="page-content-inner">
    <div class="is-navbar">
      <div class="form-layout">
        <div class="form-outer">
          <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
            <div class="form-header-inner">
              <div class="left">
                <h3>Pembatalan Pasien</h3>
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
                        <div class="column is-4">
                          <label style=" margin-bottom: 2rem;">Periode
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
                        <div class="column is-4">
                          <label style=" margin-bottom: 0.5rem;">No Registrasi
                          </label>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" class="mt-3" v-model="input.noReq"></VInput>
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-4">
                          <label style=" margin-bottom: 0.5rem;">No Rm
                          </label>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" class="mt-3" v-model="input.noCm"></VInput>
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-4">
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
                  </div>
                </div>
              </div>

            </div>
            <!--Fieldset-->
            <div class="form-fieldset">
              <div class="fieldset-heading mb-5">
                <h1 style="font-weight: bold; margin-bottom: 1rem;">Daftar Pembatalan Pasien</h1>
              </div>

              <div class="columns is-multiline mt-5">
                <div class="column is-12">
                  <DataTable :value="d_Pembatalan" class="p-datatable-sm" :loading="isLoading" :paginator="true"
                    :rows="20" :rowsPerPageOptions="[20, 40, 60]" scrollable
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
                    <Column header="Tanggal">
                      <template #body="slotProps">
                        <span>{{ H.formatDateIndoSimple(slotProps.data.tanggalpembatalan) }}</span>
                      </template>
                    </Column>
                    <Column field="noregistrasi" header="No Reg"></Column>
                    <Column field="nocm" header="No Rm"></Column>
                    <Column field="nosep" header="No SEP"></Column>
                    <Column field="namapasien" header="Nama Pasien"></Column>
                    <Column field="namaruangan" header="Ruangan"></Column>
                    <Column field="namalengkap" header="Pegawai Pembatal"></Column>
                    <Column field="alasanpembatalan" header="Alasan Pembatalan"></Column>
                  </DataTable>
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
import Column from 'primevue/column';
import moment from 'moment'
const input: any = ref({
  tglAwal: new Date(),
  tglAkhir: new Date(),
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
})
let isLoading = ref(false)
const d_Pembatalan: any = ref([])
const dataSourceICD9 = ref([])
const date = ref(new Date())


useHead({
  title: 'Daftar Pembatalan Pasien - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

async function cariRiwayat() {
  let object: any = {};
  object = input.value;
  let startDate = moment(input.value.periode.start).format('YYYY-MM-DD');
  let endDate = moment(input.value.periode.end).format('YYYY-MM-DD');
  isLoading.value = true;
  useApi().get(
    `/resgistrasi/get-daftar-pasienbatal?tglAwal=${startDate}&tglAkhir=${endDate}&noReg=${object.noReq}&noCm=${object.noCm}&namaPasien=${object.namaPasien}`).then((response: any) => {
      d_Pembatalan.value = response.data
      isLoading.value = false

    }).catch((e: any) => {
      isLoading.value = false
    })
}
const setAutoFill = () => {
  input.value.tglAwal = new Date()
  input.value.tglAkhir = new Date()
}


setAutoFill();
cariRiwayat()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';


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
