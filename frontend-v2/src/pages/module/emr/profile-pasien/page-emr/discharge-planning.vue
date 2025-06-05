<template>
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ props.FORM_NAME }}</h3>
                    </div>
                    <div class="right">
                        <div class="buttons">
                            <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                Kembali
                            </VButton>
                            <VButton type="button" rounded outlined color="warning" raised icon="lnir lnir-printer"
                                :disabled="!NOREC_EMRPASIEN ? true : false" @click="print()"> Cetak
                            </VButton>
                            <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                :loading="isLoading" @click="simpan()"> Simpan
                            </VButton>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="columns is-multiline p-2">
        <div class="column is-12">
            <VCard>
                <div class="columns is-multiline">
                    <div class="column is-5">
                        <h1 style="font-weight: bold;">Tanggal Masuk RS</h1>
                        <div class="columns pt-3 pb-0">
                            <div class="column is-12">
                                <VField>
                                    <VDatePicker v-model="input.tanggalKedatangan" mode="dateTime" style="width: 100%"
                                        trim-weeks :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" placeholder="Tanggal Masuk RS"
                                                        v-on="inputEvents" />
                                                </VControl>
                                            </VField>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </div>

                        </div>
                    </div>
                    <div class="column is-5">
                        <h1 style="font-weight: bold;">Tanggal Keluar RS</h1>
                        <div class="columns pt-3 pb-0">
                            <div class="column is-12">
                                <VField>
                                    <VDatePicker v-model="input.tanggalKeluar" mode="dateTime" style="width: 100%"
                                        trim-weeks :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" placeholder="Tanggal Keluar"
                                                        v-on="inputEvents" />
                                                </VControl>
                                            </VField>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </div>

                        </div>
                    </div>

                    <div class="column is-4">
                        <h1 style="font-weight: bold;">Diagnosa Masuk</h1>
                        <div class="columns pt-3 pb-0">
                            <div class="column is-12">
                                <VField>
                                    <VControl>
                                        <VInput type="text" v-model="input.diagnosaMasuk" placeholder="Diagnosa Masuk" />
                                    </VControl>
                                </VField>
                            </div>

                        </div>
                    </div>
                    <div class="column is-4">
                        <VField class="is-autocomplete-select" v-slot="{ id }">
                            <h1 class="mb-3 emr" style="font-weight: bold;">Diagnosa Keluar</h1>
                            <VControl icon="feather:search">
                                <AutoComplete v-model="input.diagnosa" :suggestions="d_diagnosa"
                                    @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'nama'"
                                    placeholder="Cari Diagnosa" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField class="is-autocomplete-select" v-slot="{ id }">
                            <h1 class="mb-3 emr" style="font-weight: bold;">Diagnosa Tambahan</h1>
                            <VControl icon="feather:search">
                                <AutoComplete v-model="input.diagnosa" :suggestions="d_diagnosa"
                                    @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'nama'"
                                    placeholder="Cari Diagnosa" />
                            </VControl>
                        </VField>
                    </div>
                </div>

                <div class="column is-12">
                    <Fieldset :toggleable="true" legend="A. Keadaan Saat Masuk">
                        <div class="column is-12 pt-0" v-for="(data) in keadaanMasuk">
                            <h1 class="emr pb-3" style="font-weight: bold;">{{ data.title }}</h1>
                            <div class="columns is-multiline">
                                <div class="column"
                                    :class="data.type == 'textarea' || data.type == 'select' ? ' is-6' : ' is-2'"
                                    v-for="(detail) in data.value">
                                    <VField v-if="data.type == 'textarea'">
                                        <h1 class="emr mb-3" style="font-weight: bold;">{{ detail.subTitle }}</h1>
                                        <VControl>
                                            <VTextarea v-model="input[detail.model]" rows="3"
                                                :placeholder="detail.subTitle + '...'">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                    <VField v-else-if="data.type == 'checkBox'" class="pb-4 pl-4">
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input[detail.model]" :true-value="detail.subTitle"
                                                :label="detail.subTitle" color="primary" class="p-0" style="color:black;" />
                                        </VControl>
                                    </VField>


                                </div>
                            </div>
                        </div>
                        <div class="column is-8 pt-2">
                            <VField>
                                    <VControl>
                                        <VInput type="text" v-model="input.keteranganAlergi" placeholder="Keterangan Alergi" />
                                    </VControl>
                                </VField>
                        </div>
                        <div class="column is-8 pt-2">
                            <h1 style="font-weight: bold; margin-top: 1rem;"> Resiko </h1>
                            <VField>
                                    <VControl>
                                        <VInput type="text" v-model="input.keteranganResiko" placeholder="Keterangan Resiko" />
                                    </VControl>
                                </VField>
                        </div>
                    </Fieldset>
                </div>

                <div class="column is-12">
                    <Fieldset :toggleable="true" legend="B. Keadaan Saat Keluar">
                        <div class="column is-12 pt-0" v-for="(data) in keadaanKeluar">
                            <h1 class="emr pb-3" style="font-weight: bold;">{{ data.title }}</h1>
                            <div class="columns is-multiline">
                                <div class="column"
                                    :class="data.type == 'textarea' || data.type == 'select' ? ' is-12' : ' is-3'"
                                    v-for="(detail) in data.value">
                                    <VField v-if="data.type == 'textarea'">
                                        <h1 class="emr mb-3" style="font-weight: bold;">{{ detail.subTitle }}</h1>
                                        <VControl>
                                            <VTextarea v-model="input[detail.model]" rows="3"
                                                :placeholder="detail.subTitle + '...'">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                    <VField v-else-if="data.type == 'textBox'" class="pb-4 pl-4">
                                        <h1 class="emr mb-3" style="font-weight: bold;">{{ detail.subTitle }}</h1>
                                        <VControl raw subcontrol>
                                            <VInput type="text" class="input" :placeholder="detail.subTitle + '...'"
                                                v-model="input[detail.model]" />
                                        </VControl>
                                    </VField>
                                    <VField v-else-if="data.type == 'checkBox'" class="pb-4 pl-4">
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input[detail.model]" :true-value="detail.subTitle"
                                                :label="detail.subTitle" color="primary" class="p-0" style="color:black;" />
                                        </VControl>
                                    </VField>

                                </div>
                            </div>
                        </div>
                        <div class="column is-12 pt-0" v-for="(data) in edukasiKeadaanKeluar">
                            <h1 class="emr pb-3" style="font-weight: bold;">{{ data.title }}</h1>
                            <div class="columns is-multiline">
                                <div class="column"
                                    :class="data.type == 'textarea' || data.type == 'select' ? ' is-12' : ' is-4'"
                                    v-for="(detail, i) in data.value">
                                    <VField v-if="data.type == 'textarea'">
                                        <h1 class="emr mb-3" style="font-weight: bold;">{{ detail.subTitle }}</h1>
                                        <VControl>
                                            <VTextarea v-model="input[detail.model + i]" rows="3"
                                                :placeholder="detail.subTitle + '...'">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                    <VField v-else-if="data.type == 'checkBox'" class="pb-0 pt-0 pl-4">
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input[detail.model + i]" :true-value="detail.subTitle"
                                                :label="detail.subTitle" color="primary" class="p-0" style="color:black;" />
                                        </VControl>
                                    </VField>
                                    <!-- <VField v-else-if="data.type == 'checkBox'" class="pb-0 pt-0 pl-4">
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input[detail.model + i]" :true-value="detail.subTitle"
                                                :label="detail.subTitle" color="primary" class="p-0" style="color:black;" />
                                        </VControl>
                                    </VField> -->

                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <h1 style="font-weight: bold;"> 5. Obat yang dilanjutkan</h1>

                            <div class="column is-12">
                                <table class="tg">
                                    <thead>

                                        <tr>
                                            <td class="tg-0lax">Nama Obat </td>
                                            <td class="tg-0lax">Frekuensi </td>
                                            <td class="tg-0lax">Sedian </td>
                                            <td class="tg-0lax">Keterangan</td>
                                            <td class="tg-0lax">#</td>

                                        </tr>
                                    </thead>
                                    <tbody v-for="(item, index) in input.details" :key="index">
                                        <tr>
                                            <td style="width:20%">
                                                <VField>
                                                    <VControl>
                                                        <VTextarea v-model="item.namaObat" rows="2"
                                                            placeholder="Nama Obat...">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>
                                            </td>

                                            <td style="width:20%">
                                                <VField>
                                                    <VControl>
                                                        <VTextarea v-model="item.frekuensi" rows="2"
                                                            placeholder="Frekuensi...">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>
                                            </td>
                                            <td style="width:20%">
                                                <VField>
                                                    <VControl>
                                                        <VTextarea v-model="item.sediaan" rows="2" placeholder="Sediaan...">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>
                                            </td>

                                            <td style="width:15%">
                                                <VField>
                                                    <VControl>
                                                        <VTextarea v-model="item.keterangan" rows="2"
                                                            placeholder="Keterangan...">
                                                        </VTextarea>
                                                    </VControl>

                                                </VField>
                                            </td>
                                            <td rowspan="2" style="width:7%;vertical-align: middle;">
                                                <div class="columns is-multiline">
                                                    <div class="column is-6">
                                                        <VIconButton type="button" raised circle icon="feather:plus"
                                                            @click="addNewItem()" color="info" v-tooltip.bubble="'Tambah '">
                                                        </VIconButton>
                                                    </div>
                                                    <div class="column is-6 ml-3-min">
                                                        <VIconButton v-if="index > 0" type="button" raised circle
                                                            icon="feather:trash" @click="removeItem(index)" color="danger">
                                                        </VIconButton>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>


                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </Fieldset>
                </div>
                <div class="column is-12">
                    <div class="columns is-multiline">
                <div class="column is-4">
                        <VField class="is-autocomplete-select" v-slot="{ id }">
                            <h1 class="mb-3 emr" style="font-weight: bold;">DPJP</h1>
                            <VControl icon="feather:search">
                                <AutoComplete v-model="input.dokter" :suggestions="d_dokter"
                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField class="is-autocomplete-select" v-slot="{ id }">
                            <h1 class="mb-3 emr" style="font-weight: bold;">Perawat</h1>
                            <VControl icon="feather:search">
                                <AutoComplete v-model="input.perawat" :suggestions="d_dokter"
                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari Perawat" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4 ">
                        <h1 class="mb-3 emr" style="font-weight: bold;">Nama Jelas Pasien/Keluarga</h1>
                           
                                <VField>
                                    <VControl>
                                        <VInput type="text" v-model="input.namaKeluarga" placeholder="Nama Keluarga" />
                                    </VControl>
                                </VField>
                         

                        </div>
                </div>
            </div>
            </VCard>
        </div>

    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import Fieldset from 'primevue/fieldset';
import AutoComplete from 'primevue/autocomplete';
import * as EMR from '../page-emr-plugins/discharge-planning'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let keadaanMasuk = ref(EMR.keadaanMasuk())
let keadaanKeluar = ref(EMR.keadaanKeluar())
let edukasiKeadaanKeluar = ref(EMR.edukasiKeadaanKeluar())

const props = withDefaults(
    defineProps<{
        pasien?: any
        registrasi?: any
        FORM_NAME?: string
        FORM_URL?: string
    }>(),
    {
        pasien: {},
        registrasi: {},
        FORM_NAME: '',
        FORM_URL: '',
    }
)
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value >= 20 })
const isLoading: any = ref(false)
const d_diagnosa: any = ref([])
const d_dokter: any = ref([])
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const input: any = ref({
    details: [{
        no: 1,
        tgl: new Date(),
    }]
})
const COLLECTION: any = ref('dischargePlanning') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}

const loadRiwayat = () => {
    // if (NOREC_EMRPASIEN.value == '') return
    useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
            if (response.length) {
                input.value = response[0] //set ke inputan 
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }
            }
        })
}
// const fetchDiagnosa = async (filter: any) => {
//     const response = await useApi().get(
//         `/emr/dropdown/diagnosa_m?select=id,namadiagnosa&param_search=namadiagnosa&query=${filter.query}&limit=10`)
//     d_diagnosa.value = response
// }

const fetchDiagnosa = async (filter: any) => {
    let nama = filter.query ? `?name=${filter.query}` : ''
    const response = await useApi().get(`/emr/get-data-diagnosa${nama}`)
    d_diagnosa.value = response
}
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': props.FORM_URL,
        'name_form': props.FORM_NAME,
        'jenis_emr': 'asesmen_medis',
        'data': object
    }
    isLoading.value = true
    useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const kembaliKeun = () => {
    window.history.back()
}
const dokterDPJP = async () => {
    await useApi().get(`emr/get-dokter-dpjp?nocmfk=${ID_PASIEN}`).then((response) => {
        input.value.dokter = response.dokter
    })
}
const fetchDokter = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_dokter.value = response
}

const getDataExist = async () => {
    await useApi().get(
        `emr/get-data-exist?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    ).then((response) => {

        input.value.beratBadan = response.beratBadan
        input.value.tinggiBadan = response.tinggiBadan
        input.value.IMT = response.IMT
        input.value.lingkarPerut = response.lingkarPerut
        input.value.tekananDarah = response.tekananDarah
        input.value.pernapasan = response.pernapasan
        input.value.suhu = response.suhu
        input.value.nadi = response.nadi
    })
}
getDataExist()
const addNewItem = () => {
    input.value.details.push({
        no: input.value.details[input.value.details.length - 1].no + 1,
        tgl: new Date(),
    });
}
const removeItem = (index: any) => {
    input.value.details.splice(index, 1)
}

const print = async () => {
    H.printBlade(`emr/cetak/${COLLECTION.value}?emrpasienfk=${NOREC_EMRPASIEN.value}`)
}

setView()
loadRiwayat()
dokterDPJP()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';


.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
}

.tg td {
    border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg th {
    border-color: var(--fade-grey-dark-3);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: normal;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg .tg-0lax {
    text-align: center;
    vertical-align: top
}
</style>