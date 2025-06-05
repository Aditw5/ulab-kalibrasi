<template>
  <div class="buttons">
    <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="emit('kembaliKeun')">
      Kembali
    </VButton>
    <!-- <VButton type="button" rounded outlined color="success" raised icon="lnir lnir-whatsapp"
      :disabled="NOREC_EMRPASIEN.length == 0 || (!props.isSelesai && props.registrasi?.objectdepartemenfk == 16)" @click="kirimWAResume()" v-if="!props.isHideCetak && isAllowedUrl"> Kirim WA
    </VButton> -->
    <VButton type="button" rounded outlined color="success" raised icon="lnir lnir-whatsapp"
      @click="kirimWAResume()" v-if="(props.isWA === true && props.registrasi.objectdepartemenfk == 16 && isAllowedUrl)"> Kirim WA
    </VButton>
    <VButton type="button" rounded outlined color="warning" raised icon="lnir lnir-printer"
      @click="print" v-if="!props.isHideCetak"> Cetak
    </VButton>
    <VButton type="button" rounded outlined color="info" raised icon="lnir lnir-printer"
      :disabled="NOREC_EMRPASIEN.length == 0 || (!props.isSelesai && props.registrasi?.objectdepartemenfk == 16)" @click="$emit('tte')" v-if="!props.isHideCetak"> TTE
    </VButton>
    <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="props.isLoading"
      @click="$emit('simpan')" v-if="!props.isDisableSimpanButton"> Simpan
    </VButton>
  </div>
</template>
<script setup lang="ts">
import { ref, reactive, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { flexRadialChartStripesOptions } from '/@src/data/widgets/charts/flexRadialChartStripesChart'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useToast } from 'primevue/usetoast'
import routes from '~pages'

const route = useRoute()
const isLoadingPop: any = ref(false)
const modalExpertise = ref(false)
const toast = useToast() // Inisialisasi toast
const emit = defineEmits<{
  (e: 'simpan'): void,
  (e: 'tte'): void,
  // (e: 'print'): void,
  (e: 'kembaliKeun'): void,
}>()
const props = withDefaults(
  defineProps<{
    isLoading?: boolean
    NOREC_EMRPASIEN?: any
    COLLECTION?: any
    registrasi?: any
    isDisableSimpanButton?: any
    isHideCetak?: boolean
    isSelesai?: boolean
    isWA?: boolean
  }>(),
  {
    isLoading: false,
    NOREC_EMRPASIEN: null,
    isHideCetak: false,
    // isSelesai: false,
    isWA: false,
  }
)

console.log(props)
const print = async () => {
  H.printBlade(`emr/cetak/${props.COLLECTION}?pdf=true&emrpasienfk=${props.NOREC_EMRPASIEN}&final=${props.isSelesai}`)
}

const isAllowedUrl = computed(() => {
  // Ubah '/module/emr/profile-pasien/page-emr/resume-medis' sama url yg boleh
  return route.path === '/module/emr/profile-pasien/page-emr/resume-medis'
})

const kirimWAResume = () => {
  console.log(props.NOREC_EMRPASIEN)
  let objKirim = {
    norecemr: props.NOREC_EMRPASIEN,
  }
  isLoadingPop.value = true
  useApi().post(`/emr/kirim-wa-resume`, objKirim).then(() => {
    isLoadingPop.value = true
    modalExpertise.value = false
  })
}
</script>
