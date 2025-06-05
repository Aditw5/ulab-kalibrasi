<template>
  <div class="columns is-multiline">
    <div class="column " :class="[props.show_resep ? 'is-9' : 'is-12']">
      <div class="s-card mt-0 p-5" style=" border-top: 3px solid var(--green);">
        <h3 class="title is-5 mt-3-min">
          <span> CPPT</span>
        </h3>
        <div class="columns is-multiline">
          <div class="column is-12">
            <table class="tg">
              <thead class="tg">
                <tr>
                  <!-- <th class="tg-0lax text-center font-bold">Tanggal/Jam</th> -->
                  <th class="tg-0lax text-center">Catatan Perkembangan Pasien Terintegrasi</th>
                  <!-- <th class="tg-0lax text-center">Intruksi PPA</th> -->
                  <!-- <th class="tg-0lax text-center">Verifikasi DPJP</th> -->

                </tr>
              </thead>
              <tbody v-for="(item, index2) in props.cppt" :key="index2">
                <tr v-if="item.flag != 'gizi'"
                  :style="[item.flag == 'dokter' ? 'background-color: var(--danger--light-color);' : (item.flag == 'perawat' ? 'background-color: var(--info--light-color);' : '')]">

                  <!-- <td style="width:15%">
                    <span class="mb-2">{{ H.formatDate(item.tgl, 'YYYY-MM-DD HH:mm') }}</span><br>
                    <span>{{ item.tenagaMedis ? item.tenagaMedis.label : '-' }}</span>

                  </td> -->
                  <td>
                    <table class="tg">
                      <tr>
                        <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">S
                        </td>
                        <td>
                          {{ item.S ?? '' }}
                        </td>

                      </tr>
                      <tr>
                        <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">O
                        </td>
                        <td>
                          {{ item.O ?? '' }}
                        </td>

                      </tr>
                      <tr>
                        <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">A
                        </td>
                        <td v-if="item.flag == 'profesi lain'">
                          {{ item.A ?? '' }}
                        </td>
                        <td v-if="item.flag == 'dokter'">
                          <div class="columns is-multiline">
                            <div class="column is-12">
                              <span style="font-size:9pt;font-weight:bold">Diagnosis ICD 10</span>
                              <div style="overflow-y:auto;" class="mt-1">
                                <table class="tg" style="width:100% !important">
                                  <thead>
                                    <tr>

                                      <th class="td-fkprj" width="23%"
                                        style="vertical-align:inherit;text-align: center;font-size:9pt;">
                                        Jenis
                                      </th>
                                      <th class="td-fkprj" width="25%"
                                        style="vertical-align:inherit;text-align: center;font-size:9pt;">
                                        Diagnosa
                                        Dokter
                                      </th>
                                      <th class="td-fkprj"
                                        style="vertical-align:inherit;text-align: center;font-size:9pt;"> ICD
                                        10
                                      </th>
                                    </tr>
                                  </thead>
                                  <tbody v-for="(itemsss, index3) in item.diagnosaDokter" :key="index3">
                                    <tr >
                                      <td class="tg-0lax">
                                        <div class="column p-1">
                                          {{ itemsss.jenisDiagnosa ? itemsss.jenisDiagnosa.label : '' }}
                                        </div>
                                      </td>
                                      <td class="tg-0lax">
                                        <div class="column pt-3 pb-0">
                                          {{ itemsss.keterangan ?? '' }}

                                        </div>
                                      </td>
                                      <td class="tg-0lax">
                                        <div class="column p-1">
                                          {{ itemsss.diagnosaa ? itemsss.diagnosaa.label : '' }}

                                        </div>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                            <div class="column is-12">
                              <span style="font-size:9pt;font-weight:bold">Diagnosis ICD 9</span>
                              <div style="overflow-y:auto;" class="mt-1">
                                <table class="tg" width="100%">
                                  <thead>
                                    <tr>


                                      <th class="td-fkprj" width="40%"
                                        style="vertical-align:inherit;text-align: center;font-size:9pt;">
                                        Keterangan
                                      </th>
                                      <th class="td-fkprj"
                                        style="vertical-align:inherit;text-align: center;font-size:9pt;"> ICD
                                        9
                                      </th>
                                    </tr>
                                  </thead>
                                  <tbody v-for="(itemsss, index3) in item.diagnosaDokter9" :key="index3">
                                    <tr>

                                      <td class="tg-0lax">
                                        <div class="column pt-3 pb-0">
                                          {{ itemsss.keterangan ?? '' }}

                                        </div>
                                      </td>
                                      <td class="tg-0lax">
                                        <div class="column p-1">
                                          {{ itemsss.diagnosaa ? itemsss.diagnosaa.label : '' }}

                                        </div>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </td>
                        <td v-if="item.flag == 'perawat'">
                          <div class="column">
                            <span style="font-size:11pt;font-weight:bold">Diagnosis Keperawatan</span>
                            <div class="mt-1">
                              <table class="tg">
                                <thead>
                                  <tr>
                                    <th class="td-fkprj" width="50%" style="vertical-align:inherit;text-align: center;">
                                      Diagnosa
                                      Keperawatan
                                    </th>
                                  </tr>
                                </thead>
                                <tbody v-for="(item2, index2) in item.diagnosaKep" :key="index2">
                                  <tr>
                                    <td class="tg-0lax">
                                      <div class="column p-1">
                                        {{ item2.diagnosaKeperawatan ? item2.diagnosaKeperawatan.label : '-' }}
                                      </div>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </td>

                      </tr>
                      <tr>
                        <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">P
                        </td>
                        <td>
                          {{ item.P ?? '' }}

                          <div class="column" v-if="item.flag == 'perawat'">
                            <span style="font-size:11pt;font-weight:bold">Tujuan Kriteria (SLKI) </span>
                            <div class="mt-1">
                              <table class="tg">
                                <thead>
                                  <tr>

                                    <th class="td-fkprj" width="50%" style="vertical-align:inherit;text-align: center;">
                                      Tujuan Keperawatan & Intervensi
                                    </th>

                                  </tr>
                                </thead>
                                <tbody v-for="(item2, index2) in item.tujuanKep" :key="index2">
                                  <tr>
                                    <td class="tg-0lax">
                                      <div class="column p-1">
                                        {{ item2.tujuanKeperawatan ? item2.tujuanKeperawatan.label : '-' }}
                                      </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td class="tg-0lax">
                                      <div class="column p-1">
                                        {{ item2.intervensiKeperawatan ? item2.intervensiKeperawatan.label : '-' }}
                                      </div>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </td>

                      </tr>
                    </table>
                    <div class="columns is-multiline mt-3">
                        <div class="column is-6">
                          <h3 class="has-text-centered" style="font-size:12pt;font-weight: bold;">Instruksi PPA</h3>
                          <h4 class="has-text-centered" >{{ item.intruksiPPA ?? '' }}</h4>
                        </div>
                        <div class="column is-6 text-center">
                          <h3 class="has-text-centered" style="font-size:12pt;font-weight: bold;">Verifikasi DPJP</h3>
                          <img v-if="item.dokterDPJP"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=70x70&data=' + (item.dokterDPJP ? item.dokterDPJP.label : '-')"><br>

                          <span> {{ item.keteranganVerifikasiDPJP ?? '' }}</span> <br>
                          <span> {{ item.tglVerifikasi ? H.formatDate(item.tglVerifikasi, 'YYYY-MM-DD HH:mm') : '' }}</span>
                          <br>
                          <span> {{ item.dokterDPJP ? item.dokterDPJP.label : '' }}</span> <br>
                        </div>
                    </div>
                  </td>
                  <!-- <td style="width:15%">
                    {{ item.intruksiPPA ?? '' }}

                  </td> -->

                  <!-- <td style="width:15%" class="text-center">

                    <img v-if="item.dokterDPJP"
                      :src="'https://api.qrserver.com/v1/create-qr-code/?size=70x70&data=' + (item.dokterDPJP ? item.dokterDPJP.label : '-')"><br>

                    <span> {{ item.keteranganVerifikasiDPJP ?? '' }}</span> <br>
                    <span> {{ item.tglVerifikasi ? H.formatDate(item.tglVerifikasi, 'YYYY-MM-DD HH:mm') : '' }}</span>
                    <br>
                    <span> {{ item.dokterDPJP ? item.dokterDPJP.label : '' }}</span> <br>

                  </td> -->

                </tr>
                <tr v-if="item.flag == 'gizi'"
                  :style="[item.flag == 'dokter' ? 'background-color: var(--danger--light-color);' : (item.flag == 'perawat' ? 'background-color: var(--info--light-color);' : '')]">

                  <!-- <td style="width:15%">
                    <span class="mb-2">{{ H.formatDate(item.tgl, 'YYYY-MM-DD HH:mm') }}</span><br>
                    <span>{{ item.tenagaMedis ? item.tenagaMedis.label : '-' }}</span>

                  </td> -->
                  <td>
                    <table class="tg">
                      <tr>
                        <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">A
                        </td>
                        <td>
                          {{ item.AGizi ?? '' }}
                        </td>

                      </tr>
                      <tr>
                        <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">D
                        </td>
                        <td>
                          {{ item.DGizi ?? '' }}
                        </td>

                      </tr>
                      <tr>
                        <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">I
                        </td>
                        <td>
                          {{ item.IGizi ?? '' }}
                        </td>

                      </tr>
                      <tr>
                        <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">M
                        </td>
                        <td>
                          {{ item.MGizi ?? '' }}
                        </td>

                      </tr>
                      <tr>
                        <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">E
                        </td>
                        <td>
                          {{ item.EGizi ?? '' }}
                        </td>

                      </tr>
                    </table>
                    <div class="columns is-multiline mt-3">
                        <div class="column is-6">
                          <h3 class="has-text-centered" style="font-size:12pt;font-weight: bold;">Instruksi PPA</h3>
                          <h4 class="has-text-centered" >{{ item.intruksiPPA ?? '' }}</h4>
                        </div>
                        <div class="column is-6 text-center">
                          <h3 class="has-text-centered" style="font-size:12pt;font-weight: bold;">Verifikasi DPJP</h3>
                          <img v-if="item.dokterDPJP"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=70x70&data=' + (item.dokterDPJP ? item.dokterDPJP.label : '-')"><br>

                          <span> {{ item.keteranganVerifikasiDPJP ?? '' }}</span> <br>
                          <span> {{ item.tglVerifikasi ? H.formatDate(item.tglVerifikasi, 'YYYY-MM-DD HH:mm') : '' }}</span>
                          <br>
                          <span> {{ item.dokterDPJP ? item.dokterDPJP.label : '' }}</span> <br>
                        </div>
                    </div>
                  </td>
                  <!-- <td style="width:15%">
                    {{ item.intruksiPPA ?? '' }}

                  </td> -->

                  <!-- <td style="width:15%" class="text-center">

                    <img v-if="item.dokterDPJP"
                      :src="'https://api.qrserver.com/v1/create-qr-code/?size=70x70&data=' + (item.dokterDPJP ? item.dokterDPJP.label : '-')"><br>

                    <span> {{ item.keteranganVerifikasiDPJP ?? '' }}</span> <br>
                    <span> {{ item.tglVerifikasi ? H.formatDate(item.tglVerifikasi, 'YYYY-MM-DD HH:mm') : '' }}</span>
                    <br>
                    <span> {{ item.dokterDPJP ? item.dokterDPJP.label : '' }}</span> <br>

                  </td> -->

                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="column is-3">
      <div class="s-card mt-0 p-5" style=" border-top: 3px solid var(--danger);">
        <!-- <h3 class="title is-5 head-sep mt-3-min">
          <span> Resep</span>
        </h3> -->
        <div class="columns is-multiline">

          <!-- <div class="column is-12 mb-0" v-for="itemZ  in riwayatResep"> -->
          <div class="column is-12 mb-0 mt-2" v-if="riwayatResep.length">
            <h3 class="title is-5 mt-2-min ml-3">
              <span> Resep</span>
            </h3>
            <!-- <TWidgetListResep :title="itemZ.namaproduk" :namalengkap="itemZ.namalengkap" :subtitle="itemZ.jumlah" :subtitle1="itemZ.aturanpakai"
              :subtitle2="H.formatDateIndoSimpleNoDay(itemZ.tglorder)"
              :subtitle3="itemZ.simslama == true ? 'SIMRS LAMA' : ''" :color_sub_3="'danger'"
              class="inbox-widget-3 success mb-0" /> -->
              <div class="project-files">
                <div class="updates">
                  <div class="updates-list">
                      <div class="">
                        <div class="columns is-multiline">
                          <div class="column is-12 mb--15" v-for="itemZ  in riwayatResep">
                            <div class="space-b">
                              <div class="file-box" style="width:95%">
                                <img :src="'/images/icons/files/resep-' + itemZ.no + '.svg'" alt="" />
                                <div class="meta">
                                  <span>{{ itemZ.namaproduk }} </span>
                                  <span>
                                    <b>{{ itemZ.jumlah }}</b> Qty
                                    <!-- <i aria-hidden="true" class="fas fa-circle"></i>
                                    {{ H.formatRp(item.total, 'Rp.') }} -->

                                  </span>
                                </div>
                                <div class="is-right is-dots is-spaced dropdown end-action">
                                  <span> {{
                                    H.formatDateIndoSimpleNoDay(itemZ.tglorder)
                                  }}</span>
                                </div>
                              </div>
                              <!-- <div class="ml-2 mt-5">
                                <i aria-hidden="true" class="fas fa-check-circle" style="color:var(--success)"
                                  v-if="item.status == 'Selesai'"></i>
                                <i class="fas fa-pause-circle" aria-hidden="true" style="color:var(--warning)" v-else></i>
                              </div> -->
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
          <div class="column is-12 mb-0 mt-2" v-if="riwayatResep.length == 0">
            <h3 class="title is-5 mt-2 ml-3">
              <span> Resep</span>
            </h3>
              <div class="flex-list-inner  text-center">
                <VPlaceholderSection :title="H.assets().notFound" class="my-6">
                  <template #image>
                    <img class="light-image" :src="'/@src/assets/illustrations/placeholders/search-4-dark.svg'" alt=""
                      style="width: 100px;" />
                    <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt=""
                      style="width: 100px;" />
                  </template>
                </VPlaceholderSection>
              </div>
          </div>
          <div class="column is-12 mb-0">
            <h3 class="title is-5 mt-2-min ml-3">
              <span> Laboratorium</span>
            </h3>
            <TRiwayatOrderLab title="" straight class="list-widget-v3 mt-3-min"
                    :items="listRiwayat" @editItems="editItems"
                    @hasilItems="hasilItemsBrid" @hapusItems="DialogConfirm" squared
                    colored>
            </TRiwayatOrderLab>
          </div>
          <div class="column is-12 mb-0 mt-2">
            <h3 class="title is-5 mt-2-min ml-3">
              <span> Radiologi</span>
            </h3>
            <TRiwayatOrderRad title="" straight class="list-widget-v3"
                :items="listRiwayatRad" @editItems="editItems" @cetakItems="cetakExper"
                 @hapusItems="DialogConfirm" @hasilItems="hasilItems"
                @expertiseItems="expertiseItems" squared colored>
            </TRiwayatOrderRad>
          </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, PropType } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import TWidgetListResep from '../t-widget-list-resep.vue'
import TRiwayatOrderLab from '../t-riwayat-order-lab-rev.vue'
import TRiwayatOrderRad from '../t-riwayat-order-rad-rev.vue'
const listColor2: any = ref(['primary', 'info', 'orange', 'yellow', 'success'])
const props = defineProps({
  cppt: {
    type: Array as PropType<any>,
  },
  show_resep: {
    type: Boolean as PropType<any>,
  },
  norec_pd: {
    type: String as PropType<any>,
  },
  nocmfk: {
    type: String as PropType<any>,
  },
  nocm: {
    type: String as PropType<any>,
  },
  noregistrasi: {
    type: String as PropType<any>,
  },
})
let NOREC_PD = useRoute().query.norec_pd as string
const riwayatResep: any = ref([]);
if (props.show_resep) {
  riwayatResep.value = [];
  useApi().get(`/farmasi/riwayat-order-resep-cppt?norec_pd=${NOREC_PD}`).then((responseXX: any) => {
    let nomor = 1;
    for (let x = 0; x < responseXX.length; x++) {
      const element = responseXX[x];
      for (let y = 0; y < element.details.length; y++) {
        const element2 = element.details[y];
        riwayatResep.value.push({
          'no': nomor++,
          'namaproduk': element2.namaproduk,
          'jumlah': element2.jumlah,
          'namalengkap': element2.namalengkap,
          'aturanpakai': element2.aturanpakai,
          'tglorder': element.tglorder,
          'simslama': false,
        });
      }
    }
  });
}
const listRiwayat = ref([]);
const loadRiwayat = () => {
    listRiwayat.value = []
    useApi().get(
        `/laboratorium/riwayat-order?nocmfk=${props.nocmfk}&norec_pd=${NOREC_PD}`).then((response: any) => {
            let z = 0
            for (let x = 0; x < response.length; x++) {
                const element = response[x];
                element.icon = 'lnir lnir-flask-alt'
                element.color = listColor2.value[z]
                element.tglorder = H.formatDateIndoSimple(new Date(element.tglorder))
                if (z > 4) {
                    z = 0
                }
                z++
            }
            listRiwayat.value = response
        })
}
const listRiwayatRad = ref([]);
const loadRiwayatRad = () => {
    listRiwayatRad.value = []
    // let nocm = props.pasien ? props.pasien.nocm : pasien.value.nocm
    // let noregistrasi = props.registrasi ? props.registrasi.noregistrasi : item.registrasi.noregistrasi
    useApi().get(
        `/radiologi/riwayat-order?nocmfk=${props.nocmfk}&nocm=${props.nocm}&norec_pd=${NOREC_PD}&noregistrasi=${props.noregistrasi}`).then((response: any) => {
            let z = 0
            for (let x = 0; x < response.length; x++) {
                const element = response[x];
                element.icon = 'fa fa-radiation'
                element.color = listColor2.value[z]
                element.tglorder = H.formatDateIndoSimple(new Date(element.tglorder))
                if (z > 4) {
                    z = 0
                }
                z++
            }
            listRiwayatRad.value = response
        })
}

loadRiwayat()
loadRiwayatRad()
// props.cppt = props.cppt[0][props.cppt.length]
</script>
