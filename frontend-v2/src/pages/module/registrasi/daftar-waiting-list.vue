
<template>
    <ConfirmDialog/>
    <VCard>
      <div class="column">
        <h3 class="title is-5 mb-2 mr-1">Daftar Waiting List </h3>
      </div>
      <div class="columns  all-projects m-3 mt-0">
        <div class="columns is-multiline projects-card-grid">
          <div class="column is-8">
            <div class="flex-list-inner mb-4" v-if="ds_PASIEN.loading">
              <div class="flex-table-item grid-item mb-4" v-for="key in 5" :key="key">
                <VFlexTableCell :column="{ grow: true, media: true }">
                  <VPlaceloadAvatar size="medium" />
                  <VPlaceloadText :lines="2" width="30%" last-line-width="20%" class="mx-2" />
                </VFlexTableCell>
                <VFlexTableCell>
                  <VPlaceload width="100%" height="70px" class="mx-1 mt-2" />
                </VFlexTableCell>
                <VFlexTableCell>
                  <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                </VFlexTableCell>
                <VFlexTableCell :column="{ align: 'end' }">
                  <VPlaceload width="10%" class="mx-1" />
                </VFlexTableCell>
              </div>
            </div>
            <div class="flex-list-inner" v-else-if="ds_PASIEN.data.length === 0">
              <VCard>
                <VPlaceholderSection title="Data Tidak Ditemukan" subtitle="Silakan Pilih Tanggal Pasien Pulang."
                  class="my-6">
                  <template #image>
                    <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt="" />
                    <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                  </template>
                </VPlaceholderSection>
              </VCard>
            </div>
            <div v-else>
              <div class="grid-item mb-4" v-for="(items, i) in ds_PASIEN.data" :key="items.id">
                <div class="top-section">
                  <div class="head">
                    <div class="title-wrap">
                      <div class="columns">
                        <div class="column is-3">
                          <VAvatar size="small" :color="listColor[i]" :initials="items.initials" />
                        </div>
                        <div class="column is-12 mr-3">
                          <h3>{{ items.namapasien }}</h3>
                          <p>{{ items.nocm + (items.jeniskelamin == 'PEREMPUAN' ? ' (P)' : ' (L)')}}</p>
                        </div>
                      </div>
                    </div>
                    <!-- <ProjectCardDropdown /> -->
                  </div>
                  <div class="body">
                    <div class="columns">
                      <div class="column">
                        <h4 class="heading">Noregistrasi</h4>
                        <p class="fs-075">{{ items.noregistrasi }}</p>
                        <p class="fs-075">{{ H.formatDateIndoSimple(items.tglregistrasi) }}</p>
                      </div>
                      <div class="column">
                          <h4 class="heading"> Asal </h4>
                          <p class="fs-075"> {{ items.namaruangan }}</p>
                        <h4 class="heading"> Tujuan </h4>
                        <p class="fs-075"> {{ items.ruangantujuan }}</p>
                      </div>
                      <div class="column">
                        <h4 class="heading">Tipe</h4>
                        <p class="fs-075">{{ items.kelompokpasien ? items.kelompokpasien : '-' }}</p>
                        <p class="fs-075">{{ items.namarekanan }}</p>
                      </div>
                      <div class="column">
                        <h4 class="heading">Status</h4>
                        <VTag :color="items.statusschedule == 'Waiting List' ? 'warning' : 'info'" :label="items.statusschedule" />
                      </div>
                    </div>
                  </div>
                </div>
                <div class="bottom-section">
                  <div class="foot-block">
                    <div class="developers">
                      <VButton type="button" icon="fas fa-stethoscope" class="is-fullwidth mr-3" color="success" outlined raised
                        @click="emrOpen(items)">EMR</VButton>
                      <VButton type="button" icon="feather:arrow-right" class="is-fullwidth mr-3" color="danger" outlined raised
                        @click="mutasiPasien(items)">Mutasi</VButton>
                    </div>
                  </div>
                </div>
  
              </div>
            </div>
              <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
                  :total-items="totalData" :max-links-displayed="5">
                  <template #before-pagination>
                  </template>
                  <template #before-navigation>
                    <VFlex class="mr-4 mt-1" column-gap="1rem">
                      <VField>
                      </VField>
                      <VField>
                        <VControl>
                          <div class="select is-rounded">
                            <select v-model="currentPage.limit">
                              <option :value="1">1 results per page</option>
                              <option :value="5">5 results per page</option>
                              <option :value="10">10 results per page</option>
                              <option :value="15">15 results per page</option>
                              <option :value="25">25 results per page</option>
                              <option :value="50">50 results per page</option>
                            </select>
                          </div>
                        </VControl>
                      </VField>
                    </VFlex>
                  </template>
              </VFlexPagination>
          </div>
          <div class="column is-4">
            <div class="columns is-multiline">
              <div class="column is-12">
                <VField>
                  <VControl icon="feather:search">
                    <input v-model="item.namapasien" v-on:keyup.enter="filter()" type="text" class="input is-rounded"
                      placeholder="Filter Nama..." />
                  </VControl>
                </VField>
              </div>
              <div class="column is-6">
                <h3 class="title is-5 mb-2 mr-1">Filters </h3>
              </div>
              <div class="column is-6">
                <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined raised> Clear
                  All </a>
              </div>
              <div class="column is-12 pb-0">
                <VField>
                  <VLabel> Tanggal Registrasi </VLabel>
                  <VDatePicker v-model="item.periode" is-range color="pink" trim-weeks>
                    <template #default="{ inputValue, inputEvents }">
                      <VField addons>
                        <VControl icon="feather:calendar">
                          <VInput :value="inputValue.start" v-on="inputEvents.start" />
                        </VControl>
                        <VControl>
                          <VButton static icon="feather:arrow-right" />
                        </VControl>
                        <VControl subcontrol icon="feather:calendar">
                          <VInput :value="inputValue.end" v-on="inputEvents.end" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
              <div class="column is-12 pt-0">
                <VField class="is-autocomplete-select pt-3" v-slot="{ id }" label="Ruangan Asal">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.ruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik Nama Ruangan" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12 pt-0">
                <VField class="is-autocomplete-select pt-3" v-slot="{ id }" label="Ruangan Tujuan">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.ruangantujuan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik Nama Ruangan" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12 pt-0">
                <VField class="is-autocomplete-select pt-3" v-slot="{ id }" label="Status">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.status" :suggestions="d_Status" @complete="fetchStatus()"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">
                <VButton @click="filter()" :loading="ds_PASIEN.loading" type="button" icon="feather:search"
                  class="is-fullwidth mr-3" color="info" raised> Apply Filters
                </VButton>
              </div>
            </div>
  
          </div>
        </div>
      </div>
    </VCard>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import * as H from '/@src/utils/appHelper'
import { useToaster } from '/@src/composable/toaster'
import Dialog from 'primevue/dialog';
import AutoComplete from 'primevue/autocomplete';
import Dropdown from 'primevue/dropdown';

import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import moment from 'moment'
useHead({
    title: 'Daftar Pasien Penunjang - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    const item: any = ref({
    periode: reactive({
        start: new Date(),
        end: new Date(),
    })
})
const confirm = useConfirm();

let listKelompokPasien: any = ref([])

let ds_PASIEN: any = ref([])
const totalData: any = ref(0)
let listColor: any = ref(Object.keys(useThemeColors()))
const router = useRouter()
const route = useRoute()
const { y } = useWindowScroll()
const d_Ruangan = ref([])
const d_Status = ref([{}])
const isLoadBtnSave = ref(false)
const modalChangeDate = ref(false)
const isStuck = computed(() => {
    return y.value > 30
})
const currentPage: any = ref({
    limit: 5,
    rows: 50
})

currentPage.value.page = computed(() => {
    try {
        return Number.parseInt(route.query.page as string) || 1
    } catch { }
    return 1
})


const fetchData = async () => {
    let limit: any = currentPage.value.limit
    let offset: any = route.query.page ? route.query.page : 1
    offset = (offset * limit) - limit
    let page: any = route.query.page ? route.query.page : 1
    ds_PASIEN.value.loading = true
    let tglAwal = `?tglAwal=${moment(item.value.periode.start).format('YYYY-MM-DD')}`
    let tglAkhir = `&tglAkhir=${moment(item.value.periode.end).format('YYYY-MM-DD')}`
    let ruangan = item.value.ruangan ? `&ruanganfk=${item.value.ruangan.value}` : ''
    let ruangantujuan = item.value.ruangantujuan ? `&ruangantujuanfk=${item.value.ruangantujuan.value}` : ''
    let status = item.value.status ? `&statusschedule=${item.value.status.value}` : ''
    let nama = item.value.namapasien ? `&namapasien=${item.value.namapasien}` : ''

    await useApi().get(`registrasi/get-daftar-waiting-list${tglAwal}${tglAkhir}${ruangan}${ruangantujuan}${status}${nama}&limit=${limit}&offset=${offset}`).then((response) => {
        response.data.forEach((element:any) => {
            let ini = element.namapasien.split(' ')
            let init = element.namapasien.substr(0, 1)
            if (ini.length > 1) {
                init = init + ini[1].substr(0, 1)
            }
            element.initials = init
        });
        ds_PASIEN.value = response
        ds_PASIEN.value.loading = false
        totalData.value =response.total
        // set page to 1
        route.query.page = 1
    })
}

const emrOpen = (e: any) => {
    H.cacheHelper().set('xxx_cache_menu', undefined)
    router.push({
        name: 'module-emr-profile-pasien',
        query: {
            nocmfk: e.nocmfk,
            norec_pd: e.norec_pd,
            norec_apd: e.norec_apd,
        }
    })
}
const mutasiPasien = (e: any) => {
    H.cacheHelper().set('xxx_cache_menu', undefined)
    router.push({
        name: 'module-registrasi-mutasi-pasien',
        query: {
            nocmfk: e.nocmfk,
            norec_pd: e.norec_pd,
        }
    })
}

function clearFilter() {
    delete item.value.qnama
    delete item.value.qnocm
    delete item.value.qnik
    delete item.value.qbpjs
    delete item.value.qalamat
    fetchData()
}
function filter() {
    fetchData()
}

const fetchRuangan = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Ruangan.value = response
    })
}


const fetchStatus = () => {
    let data = [{
        "label": "Waiting List",
        "value": "Waiting"
    },
    {
        "label": "Confirm",
        "value": "Confirm",
    }]
    d_Status.value = data
}


fetchData()
watch(currentPage.value, () => {
    fetchData()
})

</script>



<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.fs-075 {
    font-size: 0.9rem;
}

.is-navbar {
    .form-layout {
        margin-top: 30px;
    }
}

.form-layout {
// max-width: 740px;
margin: 0 auto;

&.is-separate {
    // max-width: 1040px;

    .form-outer {
    background: none;
    border: none;

    .form-body {
        display: flex;

        .form-section {
        flex-grow: 2;
        padding: 10px;
        width: 50%;

        .form-section-inner {
            @include vuero-s-card;

            padding: 40px;

            &.has-padding-bottom {
            padding-bottom: 60px;
            height: 100%;
            }

            >h3 {
            font-family: var(--font-alt);
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark-text);
            margin-bottom: 30px;
            }

            .columns {
            .column {
                padding-top: 0.25rem;
                padding-bottom: 0.25rem;
            }
            }

            .radio-boxes {
            display: flex;
            justify-content: space-between;
            margin-left: -8px;
            margin-right: -8px;

            .radio-box {
                position: relative;
                width: calc(50% - 16px);
                margin: 8px;

                &:focus-within {
                border-radius: 3px;
                outline-offset: var(--accessibility-focus-outline-offset);
                outline-width: var(--accessibility-focus-outline-width);
                outline-style: var(--accessibility-focus-outline-style);
                outline-color: var(--primary);
                }

                input {
                position: absolute;
                top: 0;
                left: 0;
                height: 100%;
                width: 100%;
                opacity: 0;
                cursor: pointer;

                &:checked {
                    +.radio-box-inner {
                    background: var(--primary);
                    border-color: var(--primary);
                    box-shadow: var(--primary-box-shadow);

                    .fee,
                    p {
                        color: var(--smoke-white);
                    }
                    }
                }
                }

                .radio-box-inner {
                background: var(--white);
                border: 1px solid var(--fade-grey-dark-3);
                text-align: center;
                border-radius: var(--radius);
                font-family: var(--font);
                font-weight: 600;
                font-size: 0.9rem;
                transition: color 0.3s, background-color 0.3s, border-color 0.3s,
                    height 0.3s, width 0.3s;
                padding: 30px 20px;

                .fee {
                    font-family: var(--font);
                    font-weight: 700;
                    color: var(--dark-text);
                    font-size: 2.4rem;
                    line-height: 1;

                    span {
                    &::after {
                        content: '$';
                        position: relative;
                        top: -10px;
                        font-size: 1.5rem;
                    }
                    }
                }

                p {
                    font-family: var(--font-alt);
                }
                }
            }
            }

            .control {
            >p {
                padding-top: 12px;

                >span {
                display: block;
                font-size: 0.9rem;

                span {
                    font-weight: 500;
                    color: var(--dark-text);
                }
                }
            }
            }
        }

        .form-section-outer {
            .checkboxes {
            padding: 16px 0;

            .checkbox {
                padding: 0;
                font-size: 0.9rem;
            }
            }

            .button-wrap {
            .button {
                min-height: 60px;
                font-size: 1.05rem;
                font-weight: 600;
                font-family: var(--font-alt);
            }
            }
        }
        }
    }
    }
}
}

.is-dark {
.form-layout {
    &.is-separate {
    .form-outer {
        background: none !important;

        .form-body {
        .form-section {
            .form-section-inner {
            @include vuero-card--dark;

            >h3 {
                color: var(--dark-dark-text);
            }

            .radio-boxes {
                .radio-box {
                input:checked+.radio-box-inner {
                    background: var(--primary);
                    border-color: var(--primary);
                    box-shadow: var(--primary-box-shadow);

                    .fee,
                    p {
                    color: var(--smoke-white);
                    }
                }

                .radio-box-inner {
                    background: var(--dark-sidebar-light-2);
                    border-color: var(--dark-sidebar-light-12);

                    .fee {
                    color: var(--dark-dark-text);
                    }
                }
                }
            }
            }
        }
        }
    }
    }
}
}

@media only screen and (max-width: 767px) {
.form-layout {
    &.is-separate {
    .form-outer {
        .form-body {
        padding-left: 0;
        padding-right: 0;
        flex-direction: column;

        .form-section {
            width: 100%;

            .form-section-inner {
            padding: 30px;
            }
        }
        }
    }
    }
}
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
.form-layout {
    &.is-separate {
    .form-outer {
        .form-body {
        padding-left: 0;
        padding-right: 0;

        // flex-direction: column;

        .form-section {
            // width: 100%;

            .form-section-inner {
            padding: 30px;
            }
        }
        }
    }
    }
}
}

.all-projects {
.all-projects-header {
    display: flex;
    padding: 20px;
    background: var(--white);
    border: 1px solid var(--fade-grey-dark-3);
    border-radius: var(--radius-large);
    margin-bottom: 1.5rem;

    .header-item {
    width: 25%;
    border-right: 1px solid var(--fade-grey-dark-3);

    &:last-child {
        border-right: none;
    }

    .item-inner {
        text-align: center;

        .lnil,
        .lnir {
        font-size: 2.2rem;
        margin-bottom: 6px;
        color: var(--primary);
        }

        span {
        display: block;
        font-family: var(--font);
        font-weight: 600;
        font-size: 1.4rem;
        color: var(--dark-text);
        }

        p {
        font-family: var(--font-alt);
        }
    }
    }
}

.projects-card-grid {
    .grid-item {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 220px;
    padding: 20px;
    background: var(--white);
    border: 1px solid var(--fade-grey-dark-3);
    border-radius: var(--radius-large);

    .top-section {
        .head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;

        h3 {
            font-size: 1rem;
            font-family: var(--font-alt);
            color: var(--dark-text);
            font-weight: 600;
        }
        }

        .body {
        p {
            font-family: var(--font);
            color: var(--light-text);
        }
        }
    }

    .bottom-section {
        display: flex;

        .foot-block {
        margin-right: 30px;

        .heading {
            font-family: var(--font-alt);
            font-size: 0.75rem;
            color: var(--light-text-dark-22);
        }

        >p {
            padding-top: 5px;
        }

        .developers {
            display: flex;

            .v-avatar {
            margin-right: 6px;
            }
        }
        }
    }
    }
}
}

.heading {
font-family: var(--font-alt);
font-size: 0.75rem;
color: var(--light-text-dark-22);
}

.is-dark {
.all-projects {
    .all-projects-header {
    background: var(--dark-sidebar-light-6);
    border-color: var(--dark-sidebar-light-12);

    .header-item {
        border-color: var(--dark-sidebar-light-18);

        span {
        color: var(--dark-dark-text);
        }

        i {
        color: var(--primary) !important;
        }
    }
    }

    .projects-card-grid {
    .grid-item {
        background: var(--dark-sidebar-light-6);
        border-color: var(--dark-sidebar-light-12);

        .top-section {
        .head {
            h3 {
            color: var(--dark-dark-text);
            }
        }
        }

        .bottom-section {
        .foot-block {
            .heading {
            color: var(--light-text-dark-12);
            }
        }
        }
    }
    }
}
}
</style>
  