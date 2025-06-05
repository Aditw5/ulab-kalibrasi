<template>
  <div class="buttons">
    <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="emit('kembaliKeun')">
      Kembali
    </VButton>
    <VButton type="button" rounded outlined color="success" raised icon="lnir lnir-whatsapp"
      :disabled="NOREC_EMRPASIEN.length == 0 || (!props.isSelesai && props.registrasi?.objectdepartemenfk == 18)" @click="kirimWA()" v-if="!props.isHideCetak"> Kirim WA
    </VButton>
    <VButton type="button" rounded outlined color="warning" raised icon="lnir lnir-printer"
      :disabled="NOREC_EMRPASIEN.length == 0 || (!props.isSelesai && props.registrasi?.objectdepartemenfk == 18)" @click="print" v-if="!props.isHideCetak"> Cetak
    </VButton>
    <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="props.isLoading"
      @click="$emit('simpan')"> Simpan
    </VButton>
  </div>
</template>
<script setup lang="ts">
import { ref, reactive, computed, watch } from 'vue'
import { flexRadialChartStripesOptions } from '/@src/data/widgets/charts/flexRadialChartStripesChart'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useToast } from 'primevue/usetoast'

const isLoadingPop: any = ref(false)
const modalExpertise = ref(false)
const toast = useToast() // Inisialisasi toast

const emit = defineEmits<{
  (e: 'simpan'): void,
  // (e: 'print'): void,
  (e: 'kembaliKeun'): void,
}>()
const props = withDefaults(
  defineProps<{
    isLoading?: boolean
    NOREC_EMRPASIEN?: any
    COLLECTION?: any
    registrasi?: any
    isHideCetak?: boolean
    isSelesai?: boolean
  }>(),
  {
    isLoading: false,
    NOREC_EMRPASIEN: null,
    isHideCetak: false,
    // isSelesai: false,
  }
)

console.log(props.registrasi)
const print = async () => {
    H.printBlade(`emr/cetak/${props.COLLECTION}?pdf=true&emrpasienfk=${props.NOREC_EMRPASIEN}`)
}

// const kirimWAResume = () => {
//   console.log(props.NOREC_EMRPASIEN)
//   let objKirim = {
//     norecemr: props.COLLECTION,
//   }
//   isLoadingPop.value = true
//   useApi().post(`/emr/kirim-wa-resume`, objKirim).then(() => {
//     isLoadingPop.value = false
//     modalExpertise.value = false
//   })
// }
const kirimWA = () => {
  console.log(props.NOREC_EMRPASIEN)
  let objKirim = {
    norecemr: props.NOREC_EMRPASIEN,
  }
  isLoadingPop.value = true
  useApi().post(`/emr/kirim-wa-resume`, objKirim).then(() => {
    isLoadingPop.value = false
    modalExpertise.value = false
  })
}
</script>
