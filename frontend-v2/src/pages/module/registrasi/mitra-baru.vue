<template>
    <div class="page-content-inner">
        <div class="is-navbar">
            <div class="form-layout">
                <div class="form-outer">
                    <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                        <div class="form-header-inner">
                            <div class="left">
                                <h3>Mitra</h3>
                            </div>
                            <div class="right">
                                <div class="buttons">
                                    <VButton icon="lnir lnir-arrow-left rem-100"
                                        :to="{ name: 'module-registrasi-pasien-lama' }" light dark-outlined>
                                        Cancel
                                    </VButton>
                                    <VButton type="button" icon="feather:save" :loading="isLoading" color="primary"
                                        raised @click="savePasien()" v-if="!isRegistrasi"> Save
                                    </VButton>
                                    <VButton type="button" icon="feather:arrow-right-circle" :loading="isLoading"
                                        color="info" raised @click="registrasiPasien()"
                                        v-if="isRegistrasi && (kelompokUser != 'laboratorium' && kelompokUser != 'radiologi')">
                                        Registrasi
                                    </VButton>
                                    <VButton type="button" icon="feather:arrow-right-circle" :loading="isLoading"
                                        color="info" raised @click="registrasiLab()"
                                        v-if="isRegistrasi && (kelompokUser == 'laboratorium' || kelompokUser == 'radiologi')">
                                        Registrasi
                                    </VButton>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <!--Fieldset-->
                        <div class="form-fieldset">
                            <div class="fieldset-heading">
                                <h4>Informasi Mitra</h4>
                            </div>
                            <div class="columns is-multiline">
                                <div class="column is-12 text-center">
                                    <VField>
                                        <VControl>
                                            <VFilePond v-if="files.length" v-bind:files="files" class="profile-filepond"
                                                name="profile_filepond" :chunk-retry-delays="[500, 1000, 3000]"
                                                label-idle="<i class='lnil lnil-cloud-upload'></i>"
                                                :accepted-file-types="['image/png', 'image/jpeg', 'image/gif']"
                                                :image-preview-height="140" :image-resize-target-width="140"
                                                :image-resize-target-height="140" image-crop-aspect-ratio="1:1"
                                                style-panel-layout="compact circle"
                                                style-load-indicator-position="center bottom"
                                                style-progress-indicator-position="right bottom"
                                                style-button-remove-item-position="left bottom"
                                                style-button-process-item-position="right bottom" @addfile="onAddFile"
                                                @removefile="onRemoveFile" />
                                            <VFilePond v-else class="profile-filepond" name="profile_filepond"
                                                :chunk-retry-delays="[500, 1000, 3000]"
                                                label-idle="<i class='lnil lnil-cloud-upload'></i>"
                                                :accepted-file-types="['image/png', 'image/jpeg', 'image/gif']"
                                                :image-preview-height="140" :image-resize-target-width="140"
                                                :image-resize-target-height="140" image-crop-aspect-ratio="1:1"
                                                style-panel-layout="compact circle"
                                                style-load-indicator-position="center bottom"
                                                style-progress-indicator-position="right bottom"
                                                style-button-remove-item-position="left bottom"
                                                style-button-process-item-position="right bottom" @addfile="onAddFile"
                                                @removefile="onRemoveFile" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VLabel class="required-field">Nama Perusahaan</VLabel>
                                        <VControl icon="feather:user">
                                            <VInput type="text" v-model="item.namaperusahaan" placeholder="Nama Pasien"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VLabel>No HP/Ponsel Penanggung Jawab</VLabel>
                                        <VControl icon="feather:phone">
                                            <VInput type="text" v-model="item.nohp" placeholder="No HP"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <!--Fieldset-->
                        <div class="form-fieldset">
                            <div class="fieldset-heading">
                                <h4>Informasi Alamat </h4>
                            </div>

                            <div class="columns is-multiline">
                                <div class="column is-9">
                                    <VField>
                                        <VLabel class="required-field">Alamat Lengkap</VLabel>
                                        <VControl>
                                            <VTextarea v-model="item.alamat" rows="1" placeholder="Alamat Lengkap">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-3">
                                    <VField>
                                        <VLabel class="required-field">RT / RW</VLabel>
                                        <VControl>
                                            <VInput type="text" v-model="item.rtrw" placeholder="RT / RW"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-6">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel class="required-field">Provinsi</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.provinsi" :options="d_Provinsi"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }"
                                                @select="changeProvinsi(item.provinsi)" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6" v-if="item.provinsi">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel class="required-field">Kota Kabupaten</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.kotaKabupaten"
                                                :options="d_KotaKabupaten" placeholder="Pilih data" :searchable="true"
                                                :attrs="{ id }" :loading="isLoading"
                                                @select="changeKota(item.kotaKabupaten)" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6" v-if="item.kotaKabupaten">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel class="required-field">Kecamatan</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.kecamatan" :options="d_Kecamatan"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }"
                                                :loading="isLoading" @select="changeKecamatan(item.kecamatan)" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6" v-if="item.kecamatan">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Kelurahan</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.desaKelurahan"
                                                :options="d_Kelurahan" placeholder="Pilih data" :searchable="true"
                                                :attrs="{ id }" :loading="isLoading"
                                                @select="changeDesa(item.desaKelurahan)" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6" v-if="item.kecamatan">
                                    <VField>
                                        <VLabel>Kode Pos </VLabel>
                                        <VControl icon="feather:airplay" :loading="isLoadingKodePos">
                                            <VInput type="text" v-model="item.kodePos" placeholder="Kode Pos"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-12">
                                    <VField>
                                        <VLabel>Email </VLabel>
                                        <VControl icon="feather:mail">
                                            <VInput type="email" v-model="item.email" placeholder="Email"
                                                inputmode="email" class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
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
import { useUserSession } from '/@src/stores/userSession'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Calendar from 'primevue/calendar';
useHead({
    title: 'Mitra - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
let ID_PASIEN = useRoute().query.id as string
let ID_PASIEN_SET = ref()
const date = ref(new Date())
const item: any = reactive({})
let d_JK: any = ref([])
let d_Agama: any = ref([])
let d_GolonganDarah: any = ref([])
let d_StatusPerkawinan: any = ref([])
let d_Pendidikan: any = ref([])
let d_Pekerjaan: any = ref([])
let d_Etnis: any = ref([])
let d_HubunganPasien: any = ref([])
let d_Kebangsaan: any = ref([])
let d_Negara: any = ref([])
let d_Kelurahan: any = ref([])
let d_Kecamatan: any = ref([])
let d_KotaKabupaten: any = ref([])
let d_Provinsi: any = ref([])
let isLoading = ref(false)
let isInfoTambahan = ref(false)
let isLoadingKodePos = ref(false)
let isLoadingNIK = ref(false)
let isDisabled = ref(false)
let isLoadingBPJS = ref(false)
let isPenanggungJawab = ref(false)
let isRegistrasi = ref(false)
let isPenunjang = ref(false)
let isKanker = ref(false)
const files = ref([])
const fileFoto: any = ref(null)
const { y } = useWindowScroll()
const router = useRouter()
const route = useRoute()
const isStuck = computed(() => {
    return y.value > 30
})
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser

async function listDropdown() {

    const response = await useApi().get(
        `/registrasi/list-mitra-dropdown`)
    d_JK.value = []
    for (let x = 0; x < response.jk.length; x++) {
        const element = response.jk[x];
        if (element.jeniskelamin != '-') {
            d_JK.value.push(element)
        }
    }
    d_Agama.value = response.agama.map((e: any) => { return { label: e.agama, value: e.id, default: e } })
    d_GolonganDarah.value = response.golongandarah.map((e: any) => { return { label: e.golongandarah, value: e.id, default: e } })
    // d_HubunganPasien.value = response.hubunganpasien.map((e: any) => { return { label: e.hubungankeluarga, value: e.id } })
    d_StatusPerkawinan.value = response.statusperkawinan.map((e: any) => { return { label: e.statusperkawinan, value: e.id, default: e } })
    d_Pendidikan.value = response.pendidikan.map((e: any) => { return { label: e.pendidikan, value: e.id, default: e } })
    d_Pekerjaan.value = response.pekerjaan.map((e: any) => { return { label: e.pekerjaan, value: e.id, default: e } })
    // d_Etnis.value = response.etnis.map((e: any) => { return { label: e.suku, value: e.id , default: e} })
    // d_Kebangsaan.value = response.kebangsaan.map((e: any) => { return { label: e.name, value: e.id, default: e } })
    d_Negara.value = response.negara.map((e: any) => { return { label: e.namanegara, value: e.id, default: e } })
    // d_KotaKabupaten.value = response.kotakabupaten.map((e: any) => { return { label: e.namakotakabupaten, value: e } })
    d_Provinsi.value = response.provinsi.map((e: any) => { return { label: e.namapropinsi, value: e.id, default: e } })

    for (let x = 0; x < response.negara.length; x++) {
        const element = response.negara[x];
        if (element.namanegara.toLowerCase() == 'indonesia') {
            item.negara = element.id
            break
        }
    }

}

const blobToBase64 = (blob: any) => {
    return new Promise((resolve, _) => {
        const reader = new FileReader();
        reader.onloadend = () => resolve(reader.result);
        reader.readAsDataURL(blob);
    });
}
async function savePasien() {
    // if (!item.nik) { H.alert('warning', 'NIK harus di isi'); return }
    // if (!item.namapasien) { H.alert('warning', 'Nama harus di isi'); return }
    // if (!item.tempatlahir) { H.alert('warning', 'Tempat Lahir harus di isi'); return }
    // if (!item.nohp) { H.alert('warning', 'Nomor HP harus diisi'); return }
    // if (item.nohp.length < 10) { H.alert('warning', 'No Handphone tidak sesuai'); return }
    // if (!item.tgllahir) { H.alert('warning', 'Tgl Lahir harus di isi'); return }
    // if (!item.jenisKelamin) { H.alert('warning', 'Jenis Kelamin harus di isi'); return }
    // if (!item.agama) { H.alert('warning', 'Agama harus di isi'); return }
    // // if (!item.nohp) { H.alert('warning', 'No HP harus di isi'); return }
    // if (!item.alamat) { H.alert('warning', 'Alamat Lenglap harus di isi'); return }
    // if (!item.rtrw) { H.alert('warning', 'RT / RW harus di isi'); return }

    item.progress = cekProggress()
    let json = {
        'pasien': {
            'id': ID_PASIEN ? ID_PASIEN : '',
            'namaperusahaan': item.namaperusahaan,
            'nohp': item.nohp,
            'email': item.email != undefined ? item.email : null,
            'alamat': item.alamat != undefined ? item.alamat : null,
            'objectnegarafk': item.negara != undefined ? item.negara : null,
            'progress': item.progress ? item.progress : 0,
        },
        'alamat': {
            'alamatlengkap': item.alamat,
            'rtrw': item.rtrw,
            'objectpropinsifk': item.provinsi != undefined ? item.provinsi : null,
            'objectkotakabupatenfk': item.kotaKabupaten != undefined ? item.kotaKabupaten : null,
            'objectkecamatanfk': item.kecamatan != undefined ? item.kecamatan : null,
            'objectdesakelurahanfk': item.desaKelurahan != undefined ? item.desaKelurahan : null,
            'kodepos': item.kodePos ? item.kodePos : null,
        }
    }
    isLoading.value = true
    isRegistrasi.value = false
    await useApi().post(
        `/registrasi/save-mitra`, json).then(async (response: any) => {

            if (fileFoto.value != null) {
                const formData = new FormData()
                formData.append('id', response.data.id)
                formData.append('file', fileFoto.value)
                useApi().postNoMessage('/registrasi/save-pasien-foto', formData)
            }
            isLoading.value = false
            ID_PASIEN = response.data.id
            ID_PASIEN_SET.value = response.data.id
            isRegistrasi.value = true
            if (!ID_PASIEN) {
                registrasiPasien()
            }

        }).catch((e: any) => {
            isLoading.value = false
            console.clear()
            console.log(e)
        })
}

async function changeProvinsi(event: any) {
    d_KotaKabupaten.value = []
    let query = event == '' ? '' : event;
    isLoading.value = true

    const response = await useApi().get(
        `/registrasi/kotakabupaten?provfk=${query}`)
    isLoading.value = false

    d_KotaKabupaten.value = response.kotakabupaten.map((e: any) => { return { label: e.namakotakabupaten, value: e.id, default: e } })

}
async function changeKota(event: any) {
    d_Kecamatan.value = []
    let query = event == '' ? '' : event;
    isLoading.value = true

    const response = await useApi().get(
        `/registrasi/kecamatan?kotafk=${query}`)
    isLoading.value = false

    d_Kecamatan.value = response.kecamatan.map((e: any) => { return { label: e.namakecamatan, value: e.id, default: e } })

}
async function changeKecamatan(event: any) {
    d_Kelurahan.value = []
    let query = event == '' ? '' : event;
    isLoading.value = true

    const response = await useApi().get(
        `/registrasi/desakelurahan?kecfk=${query}`)
    isLoading.value = false

    d_Kelurahan.value = response.desa.map((e: any) => { return { label: e.namadesakelurahan, value: e.id, default: e } })

}
function changeDesa(event: any) {
    item.kodePos = event.kodepos
}
function cekProggress() {
    var countALL = 0
    var data = 0
    if (item.namaperusahaan) { data = data + 1 }
    countALL = countALL + 1
    if (item.nohp) { data = data + 1 }
    countALL = countALL + 1
    if (item.email) { data = data + 1 }
    countALL = countALL + 1
    if (item.negara) { data = data + 1 }
    countALL = countALL + 1
    if (item.alamat) { data = data + 1 }
    countALL = countALL + 1
    if (item.provinsi) { data = data + 1 }
    countALL = countALL + 1
    if (item.kotaKabupaten) { data = data + 1 }
    countALL = countALL + 1
    if (item.kecamatan) { data = data + 1 }
    countALL = countALL + 1
    if (item.desaKelurahan) { data = data + 1 }
    countALL = countALL + 1
    if (item.kodePos) { data = data + 1 }


    return data / countALL * 100
}
function resetForm() {

}
function registrasiPasien() {
    if (route.query.noreservasi) {
        router.push({
            name: 'module-registrasi-registrasi-ruangan',
            query: {
                nocmfk: ID_PASIEN_SET.value,
                noreservasi: route.query.noreservasi,
                norec_online: route.query.norec_online,
                tanggalreservasi: route.query.tanggalreservasi,
                ruangan: route.query.ruangan,
                dokter: route.query.dokter,
                dokter_name: route.query.dokter_name,
                kelompok: route.query.kelompok,
                statuspasien: "BARU",
            },
        })
    } else {
        router.push({
            name: 'module-registrasi-registrasi-ruangan',
            query: {
                nocmfk: ID_PASIEN_SET.value,
                statuspasien: "BARU",
            },
        })
    }

}


function registrasiLab() {
    router.push({
        name: 'module-registrasi-registrasi-ruangan-lab',
        query: {
            nocmfk: ID_PASIEN_SET.value,
        },
    })
}

const onAddFile = (error: any, fileInfo: any) => {
    if (error) {
        console.error(error)
        return
    }

    const _file = fileInfo.file as File
    if (_file) {
        fileFoto.value = _file
    }
}

const onRemoveFile = (error: any, fileInfo: any) => {
    if (error) {
        console.error(error)
        return
    }

    console.log(fileInfo)

    fileFoto.value = null
}
listDropdown()

// watch(
//   () => item.nik,
//   () => {
//     if(item.nik.toString().length > 16){
//        H.alert('')
//     }
// })

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.form-layout {
    max-width: 840px;
    margin: 0 auto;
}

.form-fieldset {
    padding: 20px 0;
    max-width: 580px;
    margin: 0 auto;
}
</style>
