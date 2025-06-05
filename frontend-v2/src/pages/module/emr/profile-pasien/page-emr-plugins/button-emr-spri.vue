<template>
  <div class="buttons">
    <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="emit('kembaliKeun')">
      Kembali
    </VButton>
    <VButton type="button" rounded outlined color="warning" raised icon="lnir lnir-printer"
      :disabled="NOREC_EMRPASIEN.length == 0" @click="print" v-if="!props.isHideCetak"> Cetak
    </VButton>
    <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="props.isLoading"
      @click="$emit('simpan')"> Simpan
    </VButton>
  </div>
</template>
<script setup lang="ts">
import * as H from '/@src/utils/appHelper'
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
    isHideCetak?: boolean
  }>(),
  {
    isLoading: false,
    NOREC_EMRPASIEN: null,
    isHideCetak: false,
  }
)
const print = async () => {
    H.printBlade(`emr/cetak-surat-pengantar-ranap/${props.COLLECTION}?pdf=true&emrpasienfk=${props.NOREC_EMRPASIEN}`)
}
</script>
