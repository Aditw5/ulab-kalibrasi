<template>
  <VCard>
    <h3>Procedure</h3>
    <div class="columns is-multiline mt-2">
      <div class="column is-4">
        <VDatePicker v-model="item.filterDate" is-range color="pink" trim-weeks :max-date="new Date()">
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
      <div class="column is-2 mt-2">
        <VIconButton circle icon="feather:refresh-cw" raised bold @click="fecthData" :loading="isLoading"
          v-tooltip.bubble="'Cari'" class="mt-2-min">
        </VIconButton>
      </div>
      <div class="column is-12">
        <DataTable v-model:filters="filters" :value="dataSource" paginator :rows="10" dataKey="id" filterDisplay="row"
          :globalFilterFields="['subjectDisplay']" :class="`p-datatable-small`">
          <template #header>
            <div class="flex justify-content-end">
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText v-model="filters['global'].value" placeholder="Search" />
              </span>
            </div>
          </template>
          <template #empty style="text-align: center;"> No data found. </template>
          <Column v-for="col in column" :field="col.field" :header="col.title" :style="'width:' + col.width">
            <template #body="slotProps">
              {{ slotProps.data[col.field] }}
            </template>
          </Column>
        </DataTable>
      </div>
    </div>
  </VCard>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import InputText from 'primevue/inputtext';
import { FilterMatchMode } from 'primevue/api'
useHead({
  title: import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const isLoading: any = ref(false)
const item: any = reactive({
  filterDate: {
    start: new Date(),
    end: new Date()
  },
})
const dataSource = ref([])
const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },

});
const column: any = [
  {
    "field": "no",
    "title": "No",
    "width": "50px",
  }, 
  {
    "field": "categorycodingdisplay",
    "title": "Category",
    "width": "100px"
  },
  {
    "field": "codecodingcode",
    "title": "Coding Code",
    "width": "140px"
  },
  {
    "field": "codecodingdisplay",
    "title": "Coding display",
    "width": "140px"
  },
  {
    "field": "codecodingsystem",
    "title": "Coding System",
    "width": "140px"
  },
  {
    "field": "value",
    "title": "Value",
    "width": "140px"
  },
  {
    "field": "effectiveDateTime",
    "title": "Effective Date Time",
    "width": "100px"
  },
  {
    "field": "subjectdisplay",
    "title": "Patient ",
    "width": "150px"
  },
  {
    "field": "encounterdisplay",
    "title": "Encounter",
    "width": "250px"
  },
];
const fecthData = async () => {

  let dari = H.formatDate(item.filterDate.start, 'YYYY-MM-DD')
  let sampai = H.formatDate(item.filterDate.end, 'YYYY-MM-DD')

  isLoading.value = true

  const z = await useApi().get(`/bridging/satusehat/get-list?dari=${dari}&sampai=${sampai}&resourcetype=Procedure`)

  isLoading.value = false
  
  for (let x = 0; x < z.length; x++) {
    const element = z[x];
    element.no = x + 1

    element.categorycodingdisplay = element.category.coding[0].display
    element.codecodingcode = element.code.coding[0].code
    element.codecodingdisplay = element.code.coding[0].display
    element.codecodingsystem = element.code.coding[0].system

    element.subjectdisplay = element.subject.display
    element.encounterdisplay = element.encounter.display
  }
  dataSource.value = z

}
</script>

