<template>
  <VCard custom="card-green" :class="[isaktif == true ? '' : 'nonaktif']">

    <div class="list-reg-nomobile">
      <div class="columns is-multiline ">
        <!-- <div class="column is-2 mt-4-min">
          <span class="title-emr" style="color: var(--white);">{{ kelompokUser == 'dokter' ?
            'Pasien' : 'Pasien' }}</span>
        </div> -->
        <div class="column is-4" style="margin-top: -13px; margin-left: 10px">
          <div class="search-menu rounded-pad">
            <div class="search-location" style="width: 100%">
              <!-- <i class="iconify" data-icon="feather:search" style="width: 50%; margin-left: -20px !important;"></i> -->
              <input type="text" placeholder="Cari Pasien" style="margin-left: -5px !important; width: 200% !important;"
              v-model="search" v-on:keyup.enter="$emit('SEARCH', search)"/>
            </div>
          </div>
        </div>
        <div class="column mt-4-min" style="margin-left: -10px;">
          <VCard class="rounded-pad" style="background-color: #B8F2E6;" v-tooltip.bubble="'Jumlah Pasien'">
            <div class="inner-list-item media-flex-center" @click="$emit('LOADPASIEN', '')">
              <VIconBox rounded :color="'success'" class="h-avatar">
                <!-- <i class="fas fa-user-check h-fs-1" aria-hidden="true"></i> -->
                <img alt="" src="/images/simrs/icon-pasien-emr.png" style="opacity:0.3s">
              </VIconBox>
              <div class="flex-meta is-light">
                <a href="#">{{ listPasienRJ.total ? listPasienRJ.total : 0 }}</a>
                <!-- <span>Pasien</span> -->
              </div>
            </div>
          </VCard>
        </div>
        <div class="column mt-4-min" style="margin-left: -10px">
          <VCard class="rounded-pad" style="background-color: #FAF3DD;" v-tooltip.bubble="'Dipanggil'">
            <div class="inner-list-item media-flex-center" @click="$emit('LOADPASIEN', 'Sudah Dipanggil')">
              <VIconBox rounded :color="'warning'" class="h-avatar">
                <!-- <i class="fas fa-phone h-fs-1" aria-hidden="true"></i> -->
                <img alt="" src="/images/simrs/icon-panggil-emr.png">
              </VIconBox>
              <div class="flex-meta is-light">
                <a href="#">{{ listPasienRJ.dipanggil ? listPasienRJ.dipanggil : 0 }}</a>
                <!-- <span>Dipanggil</span> -->
              </div>
            </div>
          </VCard>
        </div>
        <div class="column mt-4-min" style="margin-left: -15px">
          <VCard class="rounded-pad" style="background-color: #FFA69E;" v-tooltip.bubble="'Belum Dipanggil'">
            <div class="inner-list-item media-flex-center" @click="$emit('LOADPASIEN', 'Belum Dipanggil')">
              <VIconBox rounded :color="'danger'" class="h-avatar">
                <!-- <i class="fas fa-users h-fs-1" aria-hidden="true"></i> -->
                <img alt="" src="/images/simrs/icon-antrian-emr.png">
              </VIconBox>
              <div class="flex-meta is-light">
                <a href="#">{{ listPasienRJ.belumpanggil ? listPasienRJ.belumpanggil : 0 }}</a>
                <!-- <span>Antrian</span> -->
              </div>
            </div>
          </VCard>
        </div>
        <div class="column is-1 mt-4-min" style="margin-right: 10px">
          <VIconButton circle color="info" class="ml-2 is-pulled-right" icon="feather:refresh-cw" raised bold
            @click="$emit('LOADPASIEN', '')" :loading="loadingList"></VIconButton>
        </div>
        <div class="column is-12" v-if="listPasienRJ.data == undefined">
          <div class="columns is-multiline">
            <div class="column is-12 pt-0">
              <VField>
                <VPlaceload height="35px" class="mt-2 rad-20" />
                <VPlaceload height="35px" class="mt-2 rad-20" />
                <VPlaceload height="35px" class="mt-2 rad-20" />
              </VField>
            </div>
          </div>
        </div>
        <div class="column is-12 mt-4-min mb-0 pb-0" v-if="listPasienRJ.data != undefined">
          <VCardHead title="" class="rounded-pad" style="padding:10px 15px 15px 15px">
            <!-- <template #action>
                        <VIconButton circle icon="feather:refresh-cw" raised bold :loading="loadingList"
                            @click="$emit('refreshList')" v-tooltip.bubble="'Perbaharui Data EMR'">
                        </VIconButton>
                    </template> -->
            <div class="columns is-multiline  h-list">
              <div class="column is-12 project-files mb-1" v-if="listPasienRJ.data.length"
                v-for="items in listPasienRJ.data" :key="items">
                <div>
                  <!-- v-if="selectedRegistrasi && selectedRegistrasi.norec_pd != items.norec_pd" -->
                  <div class="file-box">
                    <VIconBox rounded :color="'green'" class="h-avatar" style="background-color: #228176;">
                      <img alt="" src="/images/simrs/icon-pasien-emr.png"
                        style="height: auto; width: unset; min-width: unset;">
                      <!-- <i class="fas fa-user-injured" aria-hidden="true"></i> -->
                    </VIconBox>
                    <div class="meta" v-tooltip.bubble="'No Antrian : ' + (items.noantrian)">
                      <span>{{ items.namapasien }} </span>
                      <span>
                        <i class="fas fa-sort-amount-down-alt mt-2" style=" color:black; font-size: 0.7rem;"
                          aria-hidden="true"></i>
                        <b style="background:rgba(226, 6, 6, 0.792); font-size:9pt;color:white;border-radius:2px;">&nbsp{{
                          items.noantrian }}&nbsp</b>
                        <i aria-hidden="true" class="dot-emr fas fa-circle"></i>
                        <b>{{ items.nocm }}</b>
                        <i aria-hidden="true" class="dot-emr fas fa-circle"></i>
                        <b>{{ items.namaruangan }}</b>
                        <i aria-hidden="true" class="dot-emr fas fa-circle"></i>
                        <b>{{ items.kelompokpasien }}</b>


                      </span>
                    </div>
                    <div class="is-right is-dots is-spaced dropdown end-action" style="width:0">

                    </div>
                    <VIconButton circle
                      :color="items.status == null || items.status == 'Belum Dipanggil' ? 'danger' : 'warning'"
                      class="mr-2 is-pulled-right"
                      :class="[items.status == null || items.status == 'Belum Dipanggil' ? '' : 'is-outlined']"
                      icon="feather:phone" raised bold @click="$emit('PANGGILPASIEN', items)"
                      v-tooltip.bubble.bottom="'Panggil'" :loading="items.loading">
                    </VIconButton>
                    <VIconButton circle color="primary" class="mr-2 is-pulled-right" icon="feather:arrow-right" raised
                      bold @click="$emit('PILIHPASIEN', items)" v-tooltip.bubble.bottom="'Pilih Pasien'">
                    </VIconButton>
                  </div>
                </div>
              </div>
              <div class="column is-12   text-center " v-else>
                <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt=""
                  style="width: 150px;" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt=""
                  style="width: 100px;" />

              </div>
            </div>
          </VCardHead>
        </div>
      </div>
    </div>



    <!-- VERSI MOBILE -->


    <div class=" list-reg-mobile">
      <div class="columns is-multiline">
        <div class="column is-3 mt-4-min">
          <VCard class="rounded-pad" style="background-color: #B8F2E6;" v-tooltip.bubble="'Jumlah Pasien'">
            <div class="inner-list-item media-flex-center" @click="$emit('LOADPASIEN', '')">
              <VIconBox rounded :color="'success'" class="h-avatar">
                <!-- <i class="fas fa-user-check h-fs-1" aria-hidden="true"></i> -->
                <img alt="" src="/images/simrs/icon-pasien-emr.png">
              </VIconBox>
              <div class="flex-meta is-light">
                <a href="#">{{ listPasienRJ.total ? listPasienRJ.total : 0 }}</a>
                <!-- <span>Pasien</span> -->
              </div>
            </div>
          </VCard>
        </div>
        <div class="column is-3 mt-4-min">
          <VCard class="rounded-pad" style="background-color: #FAF3DD;" v-tooltip.bubble="'Dipanggil'">
            <div class="inner-list-item media-flex-center" @click="$emit('LOADPASIEN', 'Sudah Dipanggil')">
              <VIconBox rounded :color="'warning'" class="h-avatar">
                <!-- <i class="fas fa-phone h-fs-1" aria-hidden="true"></i> -->
                <img alt="" src="/images/simrs/icon-panggil-emr.png">
              </VIconBox>
              <div class="flex-meta is-light">
                <a href="#">{{ listPasienRJ.dipanggil ? listPasienRJ.dipanggil : 0 }}</a>
                <!-- <span>Dipanggil</span> -->
              </div>
            </div>
          </VCard>
        </div>
        <div class="column is-3 mt-4-min">
          <VCard class="rounded-pad" style="background-color: #FFA69E;" v-tooltip.bubble="'Belum Dipanggil'">
            <div class="inner-list-item media-flex-center" @click="$emit('LOADPASIEN', 'Belum Dipanggil')">
              <VIconBox rounded :color="'danger'" class="h-avatar">
                <!-- <i class="fas fa-users h-fs-1" aria-hidden="true"></i> -->
                <img alt="" src="/images/simrs/icon-antrian-emr.png">
              </VIconBox>
              <div class="flex-meta is-light">
                <a href="#">{{ listPasienRJ.belumpanggil ? listPasienRJ.belumpanggil : 0 }}</a>
                <!-- <span>Antrian</span> -->
              </div>
            </div>
          </VCard>
        </div>
        <div class="column is-3 mt-4-min ">
          <VIconButton circle color="info" class="ml-2 is-pulled-right" icon="feather:refresh-cw" raised bold
            @click="$emit('LOADPASIEN', '')" :loading="loadingList"></VIconButton>
        </div>
        <div class="column is-12" v-if="listPasienRJ.data == undefined">
          <div class="columns is-multiline">
            <div class="column is-12 pt-0">
              <VField>
                <VPlaceload height="35px" class="mt-2 rad-20" />
                <VPlaceload height="35px" class="mt-2 rad-20" />
                <VPlaceload height="35px" class="mt-2 rad-20" />
              </VField>
            </div>
          </div>
        </div>
        <div class="column is-12 mt-4-min mb-0 pb-0" v-if="listPasienRJ.data != undefined">
          <VCardHead title="" class="rounded-pad" style="padding:10px 15px 15px 15px">
            <!-- <template #action>
                        <VIconButton circle icon="feather:refresh-cw" raised bold :loading="loadingList"
                            @click="$emit('refreshList')" v-tooltip.bubble="'Perbaharui Data EMR'">
                        </VIconButton>
                    </template> -->
            <div class="columns is-multiline  h-list">
              <div class="column is-12 project-files mb-1" v-if="listPasienRJ.data.length"
                v-for="items in listPasienRJ.data" :key="items">
                <div>
                  <!-- v-if="selectedRegistrasi && selectedRegistrasi.norec_pd != items.norec_pd" -->
                  <div class="file-box">
                    <VIconBox rounded :color="'green'" class="h-avatar" style="background-color: #228176;">
                      <img alt="" src="/images/simrs/icon-pasien-emr.png"
                        style="height: auto; width: unset; min-width: unset;">
                      <!-- <i class="fas fa-user-injured" aria-hidden="true"></i> -->
                    </VIconBox>
                    <div class="meta">
                      <span>{{ items.namapasien }} </span>
                      <span>
                        <b>{{ items.nocm }}</b>
                        <i aria-hidden="true" class="dot-emr fas fa-circle"></i>
                        <b>{{ items.namaruangan }}</b>
                        <i aria-hidden="true" class="dot-emr fas fa-circle"></i>
                        <b>{{ items.kelompokpasien }}</b>
                      </span>
                    </div>
                    <div class="is-right is-dots is-spaced dropdown end-action">

                    </div>
                    <VIconButton circle color="primary" class="mr-2 is-pulled-right" icon="feather:arrow-right" raised
                      bold @click="$emit('PILIHPASIEN', items)">
                    </VIconButton>
                  </div>
                </div>
              </div>
              <div class="column is-12   text-center " v-else>
                <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt=""
                  style="width: 150px;" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt=""
                  style="width: 100px;" />

              </div>
            </div>
          </VCardHead>
        </div>
      </div>
    </div>
  </VCard>
</template>

<script setup lang="ts">
import { useUserSession } from '/@src/stores/userSession'
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
const emit = defineEmits<{
  (e: 'refreshList'): void,
  (e: 'LOADPASIEN'): void,
  (e: 'SEARCH'): string,
  (e: 'PILIHPASIEN', value: any): void,
  (e: 'PANGGILPASIEN', value: any): void,
}>()
const props = withDefaults(
  defineProps<{
    isaktif?: boolean
    loadingList?: boolean
    listPasienRJ?: any
    selectedRegistrasi?: any
  }>(),
  {
    isaktif: true,
    loadingList: false,
    listPasienRJ: {},
    selectedRegistrasi: {},
  }
)

</script>
<style lang="scss">
.r-card.rounded-pad {
  padding: 0;
  border-radius: 20px;
}

.s-card.rounded-pad {
  padding: 0;
  border-radius: 20px;
}

.dot-emr {
  color: #6EC79F !important;
  font-size: 0.5rem !important;
}

.h-list {
  // height: 90px;

  height: 122px;
  overflow-y: auto;
  display: block !important;
}

.h-avatar {
  width: 30px;
  min-width: 30px;
  height: 30px;
}

.h-fs-1 {
  font-size: 1rem !important;
}

.media-flex-center .flex-meta {
  line-height: 1 !important;
}

.rad-20 {
  border-radius: 20px
}

.s-card.rounded-pad .card-inner {
  padding-top: 1rem;
}

.project-files .file-box .meta span:nth-child(2) {
  width: 100% !important;
}

.v-icon.is-success.h-avatar {
  background: #164239;
}

.v-icon.is-warning.h-avatar {
  background: #4E4425;
}

.v-icon.is-danger.h-avatar {
  background: #380D09;
}

.list-reg-mobile {
  display: none !important;
}

.card-green {
  height: 200px !important;
}

@media (max-width: 1144px) {

  .list-reg-mobile {
    display: unset !important;
  }

  .list-reg-nomobile {
    display: none !important;
  }
}
.search-menu {
      height: 34px !important;
      white-space: nowrap;
      display: flex;
      flex-shrink: 0;
      align-items: center;
      background-color: white;
      border-radius: 8px;
      width: 100%;
      padding-left: -2.95rem;
  
      >div:not(:last-of-type) {
        border-right: 1px solid var(--search-border-color);
      }
  
      .search-bar {
        height: 55px;
        width: 100%;
        position: relative;
        display: flex;
        align-items: center;
        padding-right: 1.5rem;
  
        .field {
          width: 100%;
        }
  
        .multiselect-tags {
          padding-left: 2.5rem;
        }
      }
  
      .search-location,
      .search-job,
      .search-salary {
        display: flex;
        align-items: center;
        width: 50%;
        font-size: 14px;
        font-weight: 500;
        padding: 0 25px;
        height: 100%;
        font-family: var(--font);
  
        input {
          width: 200% !important;
          height: 90%;
          display: block;
          font-family: var(--font);
          color: var(--input-color);
          background-color: transparent;
          border: none;
          margin-left: -15px !important;
        }
  
        svg {
          margin-right: 0.5rem;
          width: 18px;
          color: var(--primary);
          flex-shrink: 0;
        }
      }
  
      .search-button {
        background-color: var(--primary);
        min-width: 100px;
        height: 56px;
        border: none;
        font-weight: 500;
        font-family: var(--font);
        padding: 0 1rem;
        border-radius: 0 0.75rem 0.75rem 0;
        color: white;
        cursor: pointer;
        margin-left: auto;
      }
    }
  
    .search-widget {
      flex: 1;
      display: inline-block;
      width: 100%;
      padding: 12px;
      background-color: var(--white);
      border-radius: 16px;
      border: 1px solid var(--fade-grey-dark-3);
      transition: all 0.3s;
    }
  
    .feed-settings {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 20px 0;
  
      h3 {
        font-family: var(--font-alt);
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--dark-text);
      }
  
      .button {
        font-size: 0.8rem;
        border-radius: 8px;
        margin-right: 4px;
  
        &.is-selected {
          background: var(--primary);
          color: var(--white);
          border-color: var(--primary);
          box-shadow: var(--primary-box-shadow);
        }
      }
    }
  

</style>
