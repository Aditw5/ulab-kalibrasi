<template>
  <ConfirmDialog/>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div v-if="isStuck" :class="[isStuck && 'is-stuck']" style="margin-top:50px; padding: 0px 0px !important;" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3> {{ props.FORM_NAME }}</h3>
            </div>
          </div>
        </div>
        <div v-if="!isStuck" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3> {{ props.FORM_NAME }}</h3>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="columns is-multiline p-2">
      <div class="column is-12">
        <VCard>
          <div class="columns is-multiline">
            <div class="column is-12">
              <div class="column is-12">
                <Fieldset :toggleable="true" legend="Dokumen Tanda Tangan Elektronik">
                    <VCard style="margin-top: 1rem">
                        <div class="timeline-wrapper" v-if="d_Dokumen.length > 0">
                            <div class="timeline-wrapper-inner">
                                <div class="timeline-container">
                                    <div class="timeline-item is-unread" v-for="(items, index) in d_Dokumen" :key="items.norec">
                                    <div class="date">
                                        <span>{{ H.formatDateIndo(items.created_at) }}</span>
                                    </div>
                                    <div :class="'dot is-' + listColor[index + 1]"></div>
                                        <div class="content-wrap is-grey">
                                            <div class="content-box">
                                                <div class="box-text" style="width: 70%">
                                                    <div class="meta-text">
                                                        <table style="width: 100%; margin-top: 10px">
                                                            <tr>
                                                                <td class="font-labels" width="38%">Nama Dokumen</td>
                                                                <td class="font-labels">:</td>
                                                                <td class="font-values" width="60%">
                                                                    {{ items.namadokumen }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="font-labels" width="18%">No. EMR</td>
                                                                <td class="font-labels">:</td>
                                                                <td class="font-values" width="80%">{{ items.noemr }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="font-labels" width="18%">Petugas</td>
                                                                <td class="font-labels">:</td>
                                                                <td class="font-values" width="80%">{{ items.namalengkap }}</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <b style="margin-right: 2.5rem; margin-top: -0.3rem;"> {{ items.status }}</b>
                                                </div>
                                                <div class="box-end" style="width: 30%">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-12">
                                                            <VTag :label="items.status" color="warning" class="is-pulled-right" rounded />
                                                        </div>
                                                        <div class="column is-6">
                                                            <VIconButton v-tooltip.bottom.right="'PDF SIGN'" icon="feather:edit" @click="PDFSIGN(items)"
                                                                color="warning" raised circle class="mr-2">
                                                            </VIconButton>
                                                            <VIconButton v-tooltip.bottom.right="'Verify PDF'" icon="feather:arrow-right"
                                                                @click="verifyPDF(items)" color="info" raised circle  class="mr-2" :disabled="items.status === 'Belum SIGN PDF'">
                                                            </VIconButton>
                                                            <VIconButton v-tooltip.bottom.right="'Hapus Dokumen TTE'" icon="feather:trash" :disabled="items.status === 'Belum Verif'"
                                                                @click="dialogConfirm(items)" color="danger" raised circle  class="mr-2">
                                                            </VIconButton>
                                                            <VIconButton v-tooltip.bottom.right="'Cetak'" icon="feather:printer"
                                                                @click="lihatDok(items)" color="primary" raised circle  class="mr-2">
                                                            </VIconButton>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </VCard>
                </Fieldset>
              </div>
            </div>
          </div>
          <!-- MODAL TTE -->
          <Dialog v-model:visible="modalInputTTE" modal header="Sign Document" :style="{ width: '30vw' }">
              <div class="columns is-multiline p-1">
                <div class="column is-12">
                  <VField label="Nama Dokumen" class="is-rounded-select_Z">
                    <VControl class="is-flex" icon="fa:folder">
                      <VInput  type="text" v-model="item.namadokumen" placeholder="Nama Dokumen..." class="" disabled />
                    </VControl>
                  </VField>
                  <VField label="Dokter Penanda Tangan" class="is-rounded-select_Z is-autocomplete-select" v-slot="{ id }" disabled >
                    <VControl icon="fa:user-md" fullwidth class="prime-auto">
                      <AutoComplete v-model="item.dokterTTE" :suggestions="d_Dokter" :optionLabel="'label'" :dropdown="true" :minLength="3"  :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'"
                        :field="'label'"
                        placeholder="ketik untuk mencari..."
                        disabled
                      />
                    </VControl>
                  </VField>
                  <VField label="Nik" class="is-rounded-select_Z">
                    <VControl class="is-flex" icon="fa:envelope">
                      <VInput type="text" v-model="item.nik" placeholder="Nik..." class="" autocomplete="off"/>
                    </VControl>
                  </VField>
                  <VField label="Passphrase" class="is-rounded-select_Z">
                    <VControl class="is-flex" icon="fa:lock">
                      <VInput :type="showPassword ? 'text' : 'password'" v-model="item.passphrase" placeholder="Key Phrase..." class="passphrase-input" autocomplete="off" style=" width: 500px;"/>
                        <VButton :icon="showPassword ? 'pi pi-eye' : 'pi pi-eye-slash'" class="pr-2 ml-1 centered-icon text-xl"  @click="togglePasswordVisibility"/>
                      <!-- <VButton type="button" color="primary" outlined raised icon="feather:user" @click="cekUserTTE()">
                        Cek User
                      </VButton> -->
                    </VControl>
                  </VField>
                </div>
              </div>
              <template #footer>
                <VButton icon="lnir lnir-arrow-left rem-100" rounded outlined @click="modalInputTTE = false" class="mr-2">
                  Kembali
                </VButton>
                <VButton type="button" rounded outlined color="primary" :loading="isLoading" raised icon="feather:save" @click="saveUpdateTTE()">
                  TTE
                </VButton>
              </template>
            </Dialog>
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
  import AutoComplete from 'primevue/autocomplete';
  import Fieldset from 'primevue/fieldset';
  import Dialog from 'primevue/dialog';
  import ConfirmDialog from 'primevue/confirmdialog'
  import ButtonEmr from '../page-emr-plugins/button-emr.vue'
  import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
  import { useConfirm } from 'primevue/useconfirm'
  import { useViewWrapper } from '/@src/stores/viewWrapper'
  import { useThemeColors } from '/@src/composable/useThemeColors'
  import { useUserSession } from '/@src/stores/userSession'
  
  const confirm = useConfirm()
  let ID_PASIEN = useRoute().query.nocmfk as string
  let NOREC_PD = useRoute().query.norec_pd as string
  let norec_emr = useRoute().query.norec_emr as string
  
  const props = withDefaults(
      defineProps<{
          pasien?: any
          registrasi?: any
          FORM_NAME?: string
      FORM_URL?: string
      COLLECTION?: string
    }>(),
    {
      pasien: {},
      registrasi: {},
      FORM_NAME: '',
      FORM_URL: '',
      COLLECTION: '',
    }
  )
  let listColor: any = ref(Object.keys(useThemeColors()))
  const { y } = useWindowScroll()
  const isStuck = computed(() => { return y.value >= 20 })
  const isLoading: any = ref(false)
  const d_Dokumen: any = ref([])
  const d_Pegawai: any = ref([])
  const d_Dokter: any = ref([])
  const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
  })
  const COLLECTION: any = ref(props.COLLECTION) //table mongodb
  const NOREC_EMRPASIEN: any = ref('')
  const input: any = ref({})
  const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
const loadRiwayat = () => {
    useApi().get(`/bsre-esign/get-ttd-emr?norec_pd=${props.registrasi.norec_pd}`).then((response: any) => {
        if (response.data.length) {
            d_Dokumen.value = response.data
        } else {
            d_Dokumen.value = [];
        }
    })
    .catch((error) => {
        console.error('Error loading data:', error);
        d_Dokumen.value = [];
    });
    console.log('datasource', d_Dokumen.value)
  }

  const fetchPegawai = async (filter: any) => {
    await useApi().get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
      d_Pegawai.value = response
    })
  }
  
  
  const kembaliKeun = () => {
    window.history.back()
  }
  const setAutoFill = async () => {
  
  }

  // TTE START
  const modalSimpanDokumenUntukTTE: any = ref(false);
  const modalInputTTE: any = ref(false);
  const showPassword = ref(false);

  const modalPengajuanTTE = () => {
      item.dokterTTE = { label: H.pegawaiLogin().namalengkap, value: H.pegawaiLogin().id };
      modalSimpanDokumenUntukTTE.value = true;
  };
  const lihatDok = (e: any) => {
      H.openFile(e.file_bsre);
  };

  const PDFSIGN = async (e: any) => {
      console.log("data e", e)
      modalInputTTE.value = true;
      
      item.dokterTTE = { label: e.namalengkap, value:e.pegawai_id };
      item.norecDOK = e.norec || "";
      item.file = e.file || "";
      item.namadokumen = e.namadokumen || "";
      item.file = e.file || "";
  }

  // CEK STATUS USER
  const cekUserTTE = async () => {
      if (!item.dokterTTE) {
          H.alert("warning", "Dokter Harus Diisi");
          return;
      }
      if (!item.nik) {
          H.alert("error", "Nik Harus Diisi");
          return;
      }
      if (!item.passphrase) {
          H.alert("warning", "Passphrase Harus Diisi");
          return;
      }
      const objSave = {
          nik: item.nik,
          passphrase: item.passphrase,
          dokterTTE: item.dokterTTE.value,
      };
      const response = await useApi().postNoMessage("bsre-esign/cek-status-user", objSave);
      if (response.response.status_code == 1111) {
          H.alert("success", 'CEK STATUS USER BERHASIL!', "BSRE");
      } else {
          H.alert("warning", response.response.message, "BSRE");
      }
  };

  // PDF SIGN BSRE
  const saveUpdateTTE = async () => {
      if (!item.dokterTTE) {
          H.alert("error", "Dokter Harus Diisi");
          return;
      }
      if (!item.nik) {
          H.alert("error", "Nik Harus Diisi");
          return;
      }
      if (!item.passphrase) {
          H.alert("error", "Passphrase Harus Diisi");
          return;
      }
      const json = {
          norec: item.norecDOK,
          nik: item.nik,
          passphrase: item.passphrase,
          dokterTTE: item.dokterTTE.value,
      };
      isLoading.value = true;
      const response = await useApi().postNoMessage("bsre-esign/save-sign-pdf", json);
      isLoading.value = false;
      console.log('response', response)
      if (response.metaData.code !== 200) {
          H.alert("error", response.metaData.message);
      } else {
          H.alert("success", response.response.success);
          modalInputTTE.value = false;
      }
      isLoading.value = false;
  };
  const clearForm = () => {
      delete item.passphrase;
      delete item.nik;
  };
  const togglePasswordVisibility = () => {
      showPassword.value = !showPassword.value;
  };

  const dialogConfirm = (e: any) => {
    confirm.require({
      message: 'Apakah anda yakin menghapus data ini ?',
      header: 'Konfirmasi Hapus Data',
      icon: 'pi pi-info-circle',
      acceptClass: 'p-button-danger',
      accept: () => {
        lanjutHapus(e)
      },
      reject: () => { },
    })
  }
  const lanjutHapus = async (e) => {
    const json = {
      norec: e.norec,
    };
    await useApi().post("bsre-esign/hapus-dokumen", json);
    d_Dokumen.value = [];
    loadRiwayat()
  }

    // TTE END

  setView()
  setAutoFill()
  loadRiwayat()
  </script>
  
  <style lang="scss">
  .table-fro {
    width: 100%;
    border: 1px solid black;
  }
  
  .th-fro,
  .td-fro {
    padding: 7px;
    border: 1px solid black;
    vertical-align: inherit;
  }
  
  .setFRO-center {
    text-align: center !important;
  }
  
  .p-fieldset-legend {
    margin-left: 15px;
  }
  </style>
  