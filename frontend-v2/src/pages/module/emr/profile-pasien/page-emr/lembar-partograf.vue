<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div v-if="isStuck" :class="[isStuck && 'is-stuck']" style="margin-top:50px; padding: 0px 0px !important;" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" 
:isDisableSimpanButton="props.pasien.isDisableSimpanButton"
:isLoading="isLoading" @simpan="simpan"
              @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>
      <div v-if="!isStuck" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" 
:isDisableSimpanButton="props.pasien.isDisableSimpanButton"
:isLoading="isLoading" @simpan="simpan"
              @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="column">
    <VCard>
     
      <div class="column is-12">
        <VCard class="p-3">
          <div class="column is-12 px-2">
            <div class="columns is-multiline">
              <div class="column is-2">
                <div class=" columns is-multiline">
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> Jam Datang : </span>
                  </div>
                  <div class="column is-12">
                    <VField>
                      <VControl>
                        <VInput v-model="input.jamdatang" type="time" placeholder="Jam" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-2">
                <div class=" columns is-multiline">
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> G : </span>
                  </div>
                  <div class="column is-12">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="G" v-model="input.ge" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-2">
                <div class=" columns is-multiline">
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> P : </span>
                  </div>
                  <div class="column is-12">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="P" v-model="input.pe" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-2">
                <div class=" columns is-multiline">
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> A : </span>
                  </div>
                  <div class="column is-12">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="A" v-model="input.aa" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class=" columns is-multiline">
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> Tanggal : </span>
                  </div>
                  <div class="column is-12">
                    <VDatePicker v-model="input.tglgpa" color="green" mode="dateTime" is24hr>
                        <template #default="{ inputValue, inputEvents }">
                            <VField>
                                <VControl icon="feather:clock">
                                    <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                                </VControl>
                            </VField>
                        </template>
                    </VDatePicker>
                  </div>
                </div>
              </div>
              <div class="column is-6">
                <div class=" columns is-multiline">
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> Mules Sejak Jam : </span>
                  </div>
                  <div class="column is-12">
                    <VDatePicker v-model="input.tglmules" color="green" mode="dateTime" is24hr>
                        <template #default="{ inputValue, inputEvents }">
                            <VField>
                                <VControl icon="feather:clock">
                                    <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                                </VControl>
                            </VField>
                        </template>
                    </VDatePicker>
                  </div>
                </div>
              </div>
              <div class="column is-6">
                <div class=" columns is-multiline">
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> Ketuban Pecah Sejak Jam : </span>
                  </div>
                  <div class="column is-12">
                    <VDatePicker v-model="input.tglketubanpecah" color="green" mode="dateTime" is24hr>
                        <template #default="{ inputValue, inputEvents }">
                            <VField>
                                <VControl icon="feather:clock">
                                    <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                                </VControl>
                            </VField>
                        </template>
                    </VDatePicker>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </VCard>
      </div>

      <div class="column is-12" style="width:100%; overflow-x: scroll;">
        <Fieldset :toggleable="true" legend="Grafik Partograf">
          <div class="column is-12 p-2">
            <div class="columns is-multiline pl-3">
                <div class="column" style="overflow: auto;">
                    <table class="tg">
                        <thead>
                            <tr>
                                <th width="10%" class="col-stuck">Waktu</th>
                                <th v-for="index in jumlahIndex">
                                    <VField>
                                        <VControl>
                                            <VInput v-model="input['waktu_' + index]" type="time"
                                                placeholder="Pick an hour" />
                                        </VControl>
                                    </VField>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="col-stuck bg-colatas">
                                    <span>Suhu</span>
                                </td>
                                <td v-for="index in jumlahIndex">
                                    <VField>
                                        <VControl>
                                            <VInput v-model="input['suhu_' + index]" type="text" />
                                        </VControl>
                                    </VField>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-stuck bg-colatas">
                                    <span>Pernafasan</span>
                                </td>
                                <td v-for="index in jumlahIndex">
                                    <VField>
                                        <VControl>
                                            <VInput v-model="input['pernafasan_' + index]" type="text" />
                                        </VControl>
                                    </VField>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-stuck bg-colatas">
                                    <span>Nadi</span>
                                </td>
                                <td v-for="index in jumlahIndex">
                                    <VField>
                                        <VControl>
                                            <VInput v-model="input['nadi_' + index]" type="text" />
                                        </VControl>
                                    </VField>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-stuck bg-colatas">
                                    <span>Tekanan Darah</span>
                                </td>
                                <td v-for="index in jumlahIndex">
                                    <VField>
                                        <VControl>
                                            <VInput v-model="input['tekananDarah_' + index]" type="text" />
                                        </VControl>
                                    </VField>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-stuck bg-colatas">
                                    <span>Saturasi02</span>
                                </td>
                                <td v-for="index in jumlahIndex">
                                    <VField>
                                        <VControl>
                                            <VInput v-model="input['saturasi02_' + index]" type="text" />
                                        </VControl>
                                    </VField>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
             </div>
          </div>
        </Fieldset>
      </div>

      <div class="column is-12">
          <VCard>
              <h1 style="font-weight: bold;" class="text-center">Grafik Tanda Vital</h1>
              <div class="column">
                  <div class="column is-12">
                      <VCard style="border-radius: 16px;">
                          <highcharts :options="chartOptions1"></highcharts>
                      </VCard>
                  </div>
              </div>
          </VCard>
      </div>
      <div class="column is-12">
          <VCard>
              <h1 style="font-weight: bold;" class="text-center">Grafik Tekanan Darah</h1>
              <div class="column">
                  <div class="column is-12">
                      <VCard style="border-radius: 16px;">
                          <highcharts :options="chartOptions2"></highcharts>
                      </VCard>
                  </div>
              </div>
          </VCard>
      </div>
      
      <div class="container">
        <div class="columns">
            <div class="columns is-multiline p-3">
              <div class="column is-6">
                <div :style="{
                  backgroundImage: 'url(' + MARKINGSITE + ')',
                  textAlign: 'center',
                  zIndex: 9999,
                  backgroundRepeat: 'no-repeat',
                  backgroundPosition: 'center',
                  width: '1000px',
                  height: '600px',
                }">
                  <canvas id="markingsite" height="600" width="1000"></canvas>
                </div>
                <VButton type="button" rounded outlined color="danger" raised icon="feather:trash"
                  @click="clearCanvas('markingsite')"> Clear
                </VButton>
              </div>
            </div>
        </div>
      </div>

      <div class="column is-12" style="width:100%; overflow-x: scroll;">
          <Fieldset :toggleable="true" legend="Catatan Persalinan">
              <div class="column is-12 p-2">
                  <div class="columns is-multiline pl-3">
                      <div class="column is-12">
                          <div class="columns is-multiline">
                              <div class="column is-6">
                                  <VDatePicker v-model="input.tglCatatanPersalinan" color="green" mode="dateTime" is24hr>
                                      <template #default="{ inputValue, inputEvents }">
                                          <VField>
                                              <VControl icon="feather:clock">
                                                  <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                                              </VControl>
                                          </VField>
                                      </template>
                                  </VDatePicker>
                                  <VField label="Nama Bidan">
                                    <VField>
                                      <VControl class="prime-auto">
                                        <AutoComplete v-model="input.namaBidan" :suggestions="d_Pegawai"
                                          @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                          :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Bidan..." />
                                      </VControl>
                                    </VField>
                                  </VField>
                              </div>
                          </div>

                          <div class="columns is-multiline mb-5">
                            <div class="column is-12 P-0">
                              <div class="column is-12" style="margin-top: 0.5rem">
                                <span class="bold-text"> Tempat Persalinan :</span>
                              </div>
                              <div class="column is-12">
                                <div class="columns is-multiline">
                                  <div class="column is-2" v-for="(data, i) in tempatPersalinan">
                                    <VField>
                                      <VControl raw subcontrol>
                                        <VCheckbox
                                          v-model="input.tempatPersalinan"
                                          :true-value="data.value"
                                          :label="data.label"
                                          class="p-0"
                                          color="primary"
                                          square
                                        />
                                      </VControl>
                                    </VField>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="columns is-multiline">
                              <div class="column is-12">
                                  <VField label="Alamat Tempat Persalinan">
                                      <VControl>
                                          <VInput v-model="input.alamatTempatPersalinan" type="text" />
                                      </VControl>
                                  </VField>
                              </div>
                          </div>

                          <div class="columns is-multiline mb-5">
                            <div class="column is-12 P-0">
                              <div class="column is-12" style="margin-top: 0.5rem">
                                <span class="bold-text"> Catatan :</span>
                              </div>
                              <div class="column is-12">
                                <div class="columns is-multiline">
                                  <div class="column is-2" v-for="(data, i) in catatan">
                                    <VField>
                                      <VControl raw subcontrol>
                                        <VCheckbox
                                          v-model="input.catatan"
                                          :true-value="data.value"
                                          :label="data.label"
                                          class="p-0"
                                          color="primary"
                                          square
                                        />
                                      </VControl>
                                    </VField>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="columns is-multiline">
                              <div class="column is-6">
                                  <VField label="Alasan Merujuk">
                                      <VControl>
                                          <VTextarea v-model="input.alasanMerujuk" />
                                      </VControl>
                                  </VField>
                              </div>
                              <div class="column is-6">
                                  <VField label="Tempat Rujukan">
                                      <VControl>
                                          <VInput v-model="input.tempatRujukan" type="text" />
                                      </VControl>
                                  </VField>
                              </div>
                          </div>

                          <div class="columns is-multiline mb-5">
                            <div class="column is-12 P-0">
                              <div class="column is-12" style="margin-top: 0.5rem">
                                <span class="bold-text"> Pendamping pada Saat Merujuk :</span>
                              </div>
                              <div class="column is-12">
                                <div class="columns is-multiline">
                                  <div class="column is-2" v-for="(data, i) in pendamping">
                                    <VField>
                                      <VControl raw subcontrol>
                                        <VCheckbox
                                          v-model="input.pendamping"
                                          :true-value="data.value"
                                          :label="data.label"
                                          class="p-0"
                                          color="primary"
                                          square
                                        />
                                      </VControl>
                                    </VField>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="columns is-multiline mb-5">
                            <div class="column is-12 P-0">
                              <div class="column is-12" style="margin-top: 0.5rem">
                                <span class="bold-text"> Masalah dalam Kehamilan / Persalinan :</span>
                              </div>
                              <div class="column is-12">
                                <div class="columns is-multiline">
                                  <div class="column is-2" v-for="(data, i) in masalahKehamilan">
                                    <VField>
                                      <VControl raw subcontrol>
                                        <VCheckbox
                                          v-model="input.masalahKehamilan"
                                          :true-value="data.value"
                                          :label="data.label"
                                          class="p-0"
                                          color="primary"
                                          square
                                        />
                                      </VControl>
                                    </VField>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <h4>KALA I</h4>
                          <div class="columns is-multiline mb-5">
                            <div class="column is-12 P-0">
                              <div class="column is-12" style="margin-top: 0.5rem">
                                <span class="bold-text"> Partogram melewati garis waspada :</span>
                              </div>
                              <div class="column is-12">
                                <div class="columns is-multiline">
                                  <div class="column is-2" v-for="(data, i) in partogram">
                                    <VField>
                                      <VControl raw subcontrol>
                                        <VCheckbox
                                          v-model="input.partogram"
                                          :true-value="data.value"
                                          :label="data.label"
                                          class="p-0"
                                          color="primary"
                                          square
                                        />
                                      </VControl>
                                    </VField>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="columns is-multiline">
                            <div class="column is-12">
                              <VField label="Masalah Lain, Sebutkan">
                                <VControl>
                                  <VTextarea v-model="input.masalahLainKalaI" />
                                </VControl>
                              </VField>
                            </div>
                            <div class="column is-12">
                              <VField label="Penatalaksanaan Masalah Tersebut">
                                <VControl>
                                  <VTextarea v-model="input.penatalaksanaanKalaI" />
                                </VControl>
                              </VField>
                            </div>
                            <div class="column is-12">
                              <VField label="Hasilnya">
                                <VControl>
                                  <VTextarea v-model="input.hasilKalaI" />
                                </VControl>
                              </VField>
                            </div>
                          </div>

                          <h4>KALA II</h4>
                          <div class="columns is-multiline mb-5">
                            <div class="column is-12 P-0">
                              <div class="column is-12" style="margin-top: 0.5rem">
                                <span class="bold-text"> Episiotomi :</span>
                              </div>
                              <div class="column is-12">
                                <div class="columns is-multiline">
                                  <div class="column is-2" v-for="(data, i) in partogram">
                                    <VField>
                                      <VControl raw subcontrol>
                                        <VCheckbox
                                          v-model="input.episiotomi"
                                          :true-value="data.value"
                                          :label="data.label"
                                          class="p-0"
                                          color="primary"
                                          square
                                        />
                                      </VControl>
                                    </VField>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="columns is-multiline mb-5">
                            <div class="column is-12 P-0">
                              <div class="column is-12" style="margin-top: 0.5rem">
                                <span class="bold-text"> Pendamping pada Saat Persalinan :</span>
                              </div>
                              <div class="column is-12">
                                <div class="columns is-multiline">
                                  <div class="column is-2" v-for="(data, i) in pendamping">
                                    <VField>
                                      <VControl raw subcontrol>
                                        <VCheckbox
                                          v-model="input.pendampingPersalinan"
                                          :true-value="data.value"
                                          :label="data.label"
                                          class="p-0"
                                          color="primary"
                                          square
                                        />
                                      </VControl>
                                    </VField>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="columns is-multiline mb-5">
                            <div class="column is-12 P-0">
                              <div class="column is-12" style="margin-top: 0.5rem">
                                <span class="bold-text"> Gawat Janin :</span>
                              </div>
                              <div class="column is-12">
                                <div class="columns is-multiline">
                                  <div class="column is-2" v-for="(data, i) in partogram">
                                    <VField>
                                      <VControl raw subcontrol>
                                        <VCheckbox
                                          v-model="input.gawatJanin"
                                          :true-value="data.value"
                                          :label="data.label"
                                          class="p-0"
                                          color="primary"
                                          square
                                        />
                                      </VControl>
                                    </VField>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <VField label="Tindakan yang Dilakukan">
                            <VControl>
                              <VTextarea v-model="input.tindakanGawatJanin" />
                            </VControl>
                          </VField>
                          <VField label="Pemantauan DJJ (Hasil)">
                            <VControl>
                              <VTextarea v-model="input.pemantauanDJJ" />
                            </VControl>
                          </VField>

                          <div class="columns is-multiline mb-5">
                            <div class="column is-12 P-0">
                              <div class="column is-12" style="margin-top: 0.5rem">
                                <span class="bold-text"> Distosia Bahu :</span>
                              </div>
                              <div class="column is-12">
                                <div class="columns is-multiline">
                                  <div class="column is-2" v-for="(data, i) in partogram">
                                    <VField>
                                      <VControl raw subcontrol>
                                        <VCheckbox
                                          v-model="input.distosiaBahu"
                                          :true-value="data.value"
                                          :label="data.label"
                                          class="p-0"
                                          color="primary"
                                          square
                                        />
                                      </VControl>
                                    </VField>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <VField label="Tindakan yang Dilakukan">
                            <VControl>
                              <VTextarea v-model="input.tindakanDistosiaBahu" />
                            </VControl>
                          </VField>

                          <div class="columns is-multiline">
                            <div class="column is-12">
                              <VField label="Masalah Lain, Penatalaksanaan Masalah Tersebut, dan Hasilnya">
                                <VControl>
                                  <VTextarea v-model="input.masalahLainKalaII" />
                                </VControl>
                              </VField>
                            </div>
                          </div>

                          <h4>KALA III</h4>
                          
                          <div class="columns is-multiline mb-5">
                            <div class="column is-12 P-0">
                              <div class="column is-12" style="margin-top: 0.5rem">
                                <span class="bold-text"> Inisiasi Menyusu Dini :</span>
                              </div>
                              <div class="column is-12">
                                <div class="columns is-multiline">
                                  <div class="column is-2" v-for="(data, i) in partogram">
                                    <VField>
                                      <VControl raw subcontrol>
                                        <VCheckbox
                                          v-model="input.inisiasiMenyusuDini"
                                          :true-value="data.value"
                                          :label="data.label"
                                          class="p-0"
                                          color="primary"
                                          square
                                        />
                                      </VControl>
                                    </VField>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <VControl>
                            <VTextarea v-model="input.alasanTidakIMD" placeholder="Alasan Tidak IMD" />
                          </VControl>

                          <div class="columns is-multiline">
                            <div class="column is-12">
                              <VField label="Lama Kala III (menit)">
                                <VControl>
                                  <VInput v-model="input.lamaKalaIII" type="number" placeholder="Lama Kala III (menit)" />
                                </VControl>
                              </VField>
                            </div>
                          </div>

                          <div class="columns is-multiline mb-5">
                            <div class="column is-12 P-0">
                              <div class="column is-12" style="margin-top: 0.5rem">
                                <span class="bold-text"> Pemberian Oksitosin 10 IU secara IM :</span>
                              </div>
                              <div class="column is-12">
                                <div class="columns is-multiline">
                                  <div class="column is-2" v-for="(data, i) in partogram">
                                    <VField>
                                      <VControl raw subcontrol>
                                        <VCheckbox
                                          v-model="input.pemberianOksitosinIM"
                                          :true-value="data.value"
                                          :label="data.label"
                                          class="p-0"
                                          color="primary"
                                          square
                                        />
                                      </VControl>
                                    </VField>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <VControl>
                            <VInput v-model="input.waktuPemberianOksitosinIM" type="number" placeholder="Waktu (menit sesudah persalinan)" />
                            <VTextarea v-model="input.alasanTidakOksitosin" placeholder="Alasan Tidak Pemberian Oksitosin" />
                          </VControl>

                          <div class="columns is-multiline">
                            <div class="column is-12">
                              <VField label="Penjepitan Tali Pusat">
                                <VControl>
                                  <VInput v-model="input.penjepitanTaliPusat" type="number" placeholder="Menit setelah bayi lahir" />
                                </VControl>
                              </VField>
                            </div>
                          </div>

                          <div class="columns is-multiline mb-5">
                            <div class="column is-12 P-0">
                              <div class="column is-12" style="margin-top: 0.5rem">
                                <span class="bold-text"> Pemberian Ulang Oksitosin (2x) :</span>
                              </div>
                              <div class="column is-12">
                                <div class="columns is-multiline">
                                  <div class="column is-2" v-for="(data, i) in partogram">
                                    <VField>
                                      <VControl raw subcontrol>
                                        <VCheckbox
                                          v-model="input.pemberianUlangOksitosin"
                                          :true-value="data.value"
                                          :label="data.label"
                                          class="p-0"
                                          color="primary"
                                          square
                                        />
                                      </VControl>
                                    </VField>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <VControl>
                            <VTextarea v-model="input.alasanPemberianUlangOksitosin" placeholder="Alasan Pemberian Ulang Oksitosin" />
                          </VControl>

                          <div class="columns is-multiline mb-5">
                            <div class="column is-12 P-0">
                              <div class="column is-12" style="margin-top: 0.5rem">
                                <span class="bold-text"> Penegangan Tali Pusat Terkendali :</span>
                              </div>
                              <div class="column is-12">
                                <div class="columns is-multiline">
                                  <div class="column is-2" v-for="(data, i) in partogram">
                                    <VField>
                                      <VControl raw subcontrol>
                                        <VCheckbox
                                          v-model="input.peneganganTaliPusat"
                                          :true-value="data.value"
                                          :label="data.label"
                                          class="p-0"
                                          color="primary"
                                          square
                                        />
                                      </VControl>
                                    </VField>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="columns is-multiline mb-5">
                            <div class="column is-12 P-0">
                              <div class="column is-12" style="margin-top: 0.5rem">
                                <span class="bold-text"> Massase Fundus Uteri :</span>
                              </div>
                              <div class="column is-12">
                                <div class="columns is-multiline">
                                  <div class="column is-2" v-for="(data, i) in partogram">
                                    <VField>
                                      <VControl raw subcontrol>
                                        <VCheckbox
                                          v-model="input.massaseFundusUteri"
                                          :true-value="data.value"
                                          :label="data.label"
                                          class="p-0"
                                          color="primary"
                                          square
                                        />
                                      </VControl>
                                    </VField>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <VControl>
                            <VTextarea v-model="input.alasanTidakMassaseFundus" placeholder="Alasan Tidak Massase Fundus Uteri" />
                          </VControl>

                          <div class="columns is-multiline mb-5">
                            <div class="column is-12 P-0">
                              <div class="column is-12" style="margin-top: 0.5rem">
                                <span class="bold-text"> Plasenta Lahir Lengkap (intact) :</span>
                              </div>
                              <div class="column is-12">
                                <div class="columns is-multiline">
                                  <div class="column is-2" v-for="(data, i) in partogram">
                                    <VField>
                                      <VControl raw subcontrol>
                                        <VCheckbox
                                          v-model="input.plasentaLengkap"
                                          :true-value="data.value"
                                          :label="data.label"
                                          class="p-0"
                                          color="primary"
                                          square
                                        />
                                      </VControl>
                                    </VField>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <VField label="Jika Tidak Lengkap, Tindakan yang Dilakukan">
                            <VControl>
                              <VTextarea v-model="input.tindakanPlasentaTidakLengkapA" placeholder="Tindakan a" />
                              <VTextarea v-model="input.tindakanPlasentaTidakLengkapB" placeholder="Tindakan b" />
                            </VControl>
                          </VField>

                          <div class="columns is-multiline mb-5">
                            <div class="column is-12 P-0">
                              <div class="column is-12" style="margin-top: 0.5rem">
                                <span class="bold-text"> Plasenta Tidak Lahir :</span>
                              </div>
                              <div class="column is-12">
                                <div class="columns is-multiline">
                                  <div class="column is-2" v-for="(data, i) in partogram">
                                    <VField>
                                      <VControl raw subcontrol>
                                        <VCheckbox
                                          v-model="input.plasentaTidakLahir"
                                          :true-value="data.value"
                                          :label="data.label"
                                          class="p-0"
                                          color="primary"
                                          square
                                        />
                                      </VControl>
                                    </VField>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <VControl>
                            <VTextarea v-model="input.tindakanPlasentaTidakLahir" placeholder="Tindakan Jika Plasenta Tidak Lahir" />
                          </VControl>

                          <div class="columns is-multiline mb-5">
                            <div class="column is-12 P-0">
                              <div class="column is-12" style="margin-top: 0.5rem">
                                <span class="bold-text"> Laserasi :</span>
                              </div>
                              <div class="column is-12">
                                <div class="columns is-multiline">
                                  <div class="column is-2" v-for="(data, i) in partogram">
                                    <VField>
                                      <VControl raw subcontrol>
                                        <VCheckbox
                                          v-model="input.laserasi"
                                          :true-value="data.value"
                                          :label="data.label"
                                          class="p-0"
                                          color="primary"
                                          square
                                        />
                                      </VControl>
                                    </VField>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <VControl>
                            <VTextarea v-model="input.lokasiLaserasi" placeholder="Lokasi Laserasi" />
                          </VControl>

                          <div class="columns is-multiline mb-5">
                            <div class="column is-12 P-0">
                              <div class="column is-12" style="margin-top: 0.5rem">
                                <span class="bold-text"> Jika Laserasi Perineum, Derajat 1/2/3/4 :</span>
                                <h4>Tindakan</h4>
                              </div>
                              <div class="column is-12">
                                <div class="columns is-multiline">
                                  <div class="column is-2" v-for="(data, i) in partogram">
                                    <VField>
                                      <VControl raw subcontrol>
                                        <VCheckbox
                                          v-model="input.tindakanLaserasi"
                                          :true-value="data.value"
                                          :label="data.label"
                                          class="p-0"
                                          color="primary"
                                          square
                                        />
                                      </VControl>
                                    </VField>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <VControl>
                            <VTextarea v-model="input.alasanTidakDijahit" placeholder="Alasan Tidak Dijahit" />
                          </VControl>

                          <div class="columns is-multiline mb-5">
                            <div class="column is-12 P-0">
                              <div class="column is-12" style="margin-top: 0.5rem">
                                <span class="bold-text"> Atonia Uteri :</span>
                              </div>
                              <div class="column is-12">
                                <div class="columns is-multiline">
                                  <div class="column is-2" v-for="(data, i) in partogram">
                                    <VField>
                                      <VControl raw subcontrol>
                                        <VCheckbox
                                          v-model="input.atoniaUteri"
                                          :true-value="data.value"
                                          :label="data.label"
                                          class="p-0"
                                          color="primary"
                                          square
                                        />
                                      </VControl>
                                    </VField>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <VControl>
                            <VTextarea v-model="input.tindakanAtoniaUteri" placeholder="Tindakan Jika Atonia Uteri" />
                          </VControl>

                          <div class="columns is-multiline">
                            <div class="column is-12">
                              <VField label="Jumlah Darah yang Keluar / Perdarahan">
                                <VControl>
                                  <VInput v-model="input.jumlahDarahKeluar" type="number" placeholder="Jumlah Darah (ml)" />
                                </VControl>
                              </VField>
                            </div>
                          </div>

                          <div class="columns is-multiline">
                            <div class="column is-12">
                              <VField label="Masalah dan Penatalaksanaan Masalah, Hasilnya">
                                <VControl>
                                  <VTextarea v-model="input.masalahDanPenatalaksanaan" placeholder="Masalah dan Penatalaksanaan" />
                                </VControl>
                              </VField>
                            </div>
                          </div>

                          <h4>KALA IV</h4>
                          <div class="column is-12">
                            <VField label="Kondisi Ibu">
                              <VControl>
                                <VInput v-model="input.kondisiIbu" placeholder="Masukkan kondisi ibu" />
                              </VControl>
                            </VField>
                            <VField label="TD">
                              <VControl>
                                <VInput v-model="input.td" type="number" placeholder="mmHg" />
                              </VControl>
                            </VField>
                            <VField label="Nadi">
                              <VControl>
                                <VInput v-model="input.nadi" type="number" placeholder="x/mnt" />
                              </VControl>
                            </VField>
                            <VField label="Napas">
                              <VControl>
                                <VInput v-model="input.napas" type="number" placeholder="x/mnt" />
                              </VControl>
                            </VField>
                            <VField label="Masalah dan Penatalaksanaan Masalah">
                              <VControl>
                                <VTextarea v-model="input.masalahPenatalaksanaan" placeholder="Deskripsikan masalah dan penatalaksanaan" />
                              </VControl>
                            </VField>
                          </div>

                          <h4>Bayi Baru Lahir</h4>
                          <div class="column is-12">
                            <VField label="Berat Badan">
                              <VControl>
                                <VInput v-model="input.beratBadan" type="number" placeholder="Gram" />
                              </VControl>
                            </VField>
                            <VField label="Panjang Badan">
                              <VControl>
                                <VInput v-model="input.panjangBadan" type="number" placeholder="Cm" />
                              </VControl>
                            </VField>
                            <VField label="Jenis Kelamin">
                              <VControl>
                                <VInput v-model="input.jenKelamin" type="text" placeholder="Jenis Kelamin" />
                              </VControl>
                            </VField>
                            <VField label="Penilaian Bayi Baru Lahir">
                              <VControl>
                                <VInput v-model="input.penilaianBaruLahir" type="text" placeholder="Penilaian Bayi Baru Lahir" />
                              </VControl>
                            </VField>
                          
                            <div class="columns is-multiline mb-5">
                              <div class="column is-12 P-0">
                                <div class="column is-12" style="margin-top: 0.5rem">
                                  <span class="bold-text"> Kondisi Bayi :</span>
                                </div>
                                <div class="column is-12">
                                  <div class="columns is-multiline">
                                    <div class="column is-2" v-for="(data, i) in kondisiBayi">
                                      <VField>
                                        <VControl raw subcontrol>
                                          <VCheckbox
                                            v-model="input.kondisiBayi"
                                            :true-value="data.value"
                                            :label="data.label"
                                            class="p-0"
                                            color="primary"
                                            square
                                          />
                                        </VControl>
                                      </VField>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="columns is-multiline mb-5">
                              <div class="column is-12 P-0">
                                <div class="column is-12" style="margin-top: 0.5rem">
                                  <span class="bold-text"> Tindakan Bayi :</span>
                                </div>
                                <div class="column is-12">
                                  <div class="columns is-multiline">
                                    <div class="column is-2" v-for="(data, i) in tindakanBayi">
                                      <VField>
                                        <VControl raw subcontrol>
                                          <VCheckbox
                                            v-model="input.tindakanBayi"
                                            :true-value="data.value"
                                            :label="data.label"
                                            class="p-0"
                                            color="primary"
                                            square
                                          />
                                        </VControl>
                                      </VField>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                           
                            <VField label="Cacat Bawaan">
                              <VTextarea v-model="input.cacatBawaan" placeholder="Sebutkan jika ada" />
                            </VField>
                            <VField label="Hipotermi">
                              <VTextarea v-model="input.hipotermiTindakan" placeholder="Tindakan" />
                            </VField>
                            <VField label="Masalah Lain">
                              <VTextarea v-model="input.masalahLain" placeholder="Sebutkan jika ada" />
                            </VField>
                            <VField label="Hasil">
                              <VTextarea v-model="input.hasil" placeholder="Masukkan hasil" />
                            </VField>
                          </div>      

                      </div>
                  </div>
              </div>
          </Fieldset>
      </div>

      <div class="column is-12" style="width:100%; overflow-x: scroll;">
        <Fieldset :toggleable="true" legend="Tabel Pemantauan Kala IV">
          <div class="column is-12 p-2">
            <div class="columns is-multiline">
              <div class="column">
                <table class="tg" style="table-layout: fixed; width: 100%;">
                  <thead>
                    <tr>
                      <th style="width: 100px !important;">Jam Ke</th>
                      <th style="width: 150px !important;">Waktu</th>
                      <th style="width: 150px !important;">Tekanan Darah</th>
                      <th style="width: 150px !important;">Nadi</th>
                      <th style="width: 150px !important;">Suhu</th>
                      <th style="width: 150px !important;">Tinggi Fundus Uteri</th>
                      <th style="width: 150px !important;">Kontraksi Uterus</th>
                      <th style="width: 150px !important;">Kandung Kemih</th>
                      <th style="width: 200px !important;">Darah Keluar</th>
                      <th style="width: 50px !important;">#</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(data, index) in input.detailObservasi" :key="index">
                      <td>{{ index + 1 }}</td>
                      <td>
                        <VField>
                          <VControl>
                            <VInput v-model="data.jam" type="time" placeholder="Waktu" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl>
                            <VInput v-model="data.tekanaDarah" type="text" placeholder="Tekanan Darah" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl>
                            <VInput v-model="data.nadi" type="text" placeholder="Nadi" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl>
                            <VInput v-model="data.suhu" type="text" placeholder="Suhu" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl>
                            <VInput v-model="data.fundusUteri" type="text" placeholder="Tinggi Fundus Uteri" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl>
                            <VInput v-model="data.kontraksiUterus" type="text" placeholder="Kontraksi Uterus" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl>
                            <VInput v-model="data.kandungKemih" type="text" placeholder="Kandung Kemih" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl>
                            <VInput v-model="data.darahKeluar" type="text" placeholder="Darah Keluar" />
                          </VControl>
                        </VField>
                      </td>
                     
                      <td>
                        <div>
                          <VIconButton 
                            type="button" 
                            raised 
                            circle 
                            icon="feather:plus" 
                            @click="addRow()" 
                            color="info" 
                            v-tooltip.bubble="'Tambah Baris'">
                          </VIconButton>
                          <VIconButton 
                            class="mt-1" 
                            v-if="index > 0" 
                            type="button" 
                            raised 
                            circle 
                            icon="feather:trash" 
                            @click="removeRow(index)" 
                            color="danger">
                          </VIconButton>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>

      <br>
      <div class="columns is-multiline">
        <div class="column is-4" style="margin-left: auto;">
          <VCard>
            <h1 style="font-weight: bold; text-align: center"> Perawat </h1>
            <div class="mt-6">
              <img v-if="input.ahliObgyn"
                  :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.ahliObgyn.label ? input.ahliObgyn.label : input.ahliObgyn)">
            </div>
            <div class="mt-2">
              <VField>
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.ahliObgyn" :suggestions="d_Pegawai"
                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Perawat..." />
                </VControl>
              </VField>
            </div>
          </VCard>
        </div>
      </div>

    </VCard>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
const jumlahIndex = ref(15)
import * as EMR from '../page-emr-plugins/lembar-partograf'
import $ from "jquery";
import sleep from '/@src/utils/sleep'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let tempatPersalinan = ref(EMR.tempatPersalinan())
let catatan = ref(EMR.catatan())
let pendamping = ref(EMR.pendamping())
let masalahKehamilan = ref(EMR.masalahKehamilan())
let partogram = ref(EMR.partogram())
let kondisiBayi = ref(EMR.kondisiBayi())
let tindakanBayi = ref(EMR.tindakanBayi())

const props = withDefaults(
  defineProps<{
    pasien?: any
    registrasi?: any
    diagnosis?: any
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

const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value >= 20 })
const MARKINGSITE: any = ref('')
const isLoading: any = ref(false)
const isLoadingVitalSign: any = ref(false)
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const d_Ruangan: any = ref([])
const sourceDataLab = ref([])
const showData: any = ref(false)
const d_Kelas: any = ref([])
const d_Diagnosa: any = ref([])
const isLoadingTT: any = ref(false)
const item: any = reactive({

  NOREC_PD: (NOREC_PD !== undefined ? NOREC_PD : '') || '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})

const input: any = ref({
    detailObservasi: [{ no: 1 }],
});


let chartOptions1 = reactive({
    chart: {
        type: 'spline',
    },
    title: {
        text: ''
    },
    credits: {
        enabled: false
    },
    xAxis: {
        categories: []
    },
    yAxis: {
        title: {
            text: 'Jumlah'
        }
    },
    legend: { enabled: true },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        },
        spline: {
            dataLabels: {
                enabled: true,
                // style: {
                //   fontSize: '20px'
                // },
            },
            enableMouseTracking: false
        }
    },
    series: []
});
let chartOptions2 = reactive({
    chart: {
        type: 'spline',
    },
    title: {
        text: ''
    },
    credits: {
        enabled: false
    },
    xAxis: {
        categories: []
    },
    yAxis: {
        title: {
            text: 'Jumlah'
        }
    },
    legend: { enabled: true },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        },
        spline: {
            dataLabels: {
                enabled: true,
                // style: {
                //   fontSize: '20px'
                // },
            },
            enableMouseTracking: false
        }
    },
    series: []
});

const chartHigh = (e: any) => {
    let labels = []
    let seriesNadi = []
    let seriesSuhu = []
    let seriesPernafasan = []
    let seriesSaturasi = []
    // let seriesSaturasi =[]
    for (let x = 0; x < jumlahIndex.value; x++) {
        if (e['suhu_' + x.toString()] != undefined) {
            seriesSuhu.push(parseFloat(e['suhu_' + x.toString()]))
        }
        if (e['nadi_' + x.toString()] != undefined) {
            seriesNadi.push(parseFloat(e['nadi_' + x.toString()]))
        }
        if (e['pernafasan_' + x.toString()] != undefined) {
            seriesPernafasan.push(parseFloat(e['pernafasan_' + x.toString()]))
        }
        if (e['saturasi02_' + x.toString()] != undefined) {
            seriesSaturasi.push(parseFloat(e['saturasi02_' + x.toString()]))
        }
    }

    chartOptions1.xAxis.categories = labels
    chartOptions1.series =
        [{
            name: 'Suhu',
            color: 'red',
            lineWidth: 4,
            marker: {
                radius: 4
            },
            data: seriesSuhu
        }, {
            name: 'Nadi',
            color: 'blue',
            data: seriesNadi
        }, {
            name: 'Pernafasan',
            data: seriesPernafasan
        },
        {
            name: 'Saturasi02',
            data: seriesSaturasi
        },],
        console.log(chartOptions2)
}

const chartHigh2 = (e: any) => {
    let labels = []
    let seriesDiastolik = []
    let seriesSistolik = []

    // let seriesSaturasi =[]
    for (let x = 0; x < jumlahIndex.value; x++) {
        if (e['tekananDarah_' + x.toString()] != undefined) {
            let inputString = e['tekananDarah_' + x.toString()];
            let resultArray = inputString.split('/').map(Number);
            seriesSistolik.push(parseFloat(resultArray[0]))
            seriesDiastolik.push(parseFloat(resultArray[1]))
        }
    }

    chartOptions2.xAxis.categories = labels
    chartOptions2.series =
        [{
            name: 'Sistolik',
            color: 'red',
            lineWidth: 4,
            marker: {
                radius: 4
            },
            data: seriesSistolik
        }, {
            name: 'Diastolik',
            color: 'blue',
            data: seriesDiastolik
        },],
        console.log(chartOptions2)
}

const COLLECTION: any = ref('lembarPartograf') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const isLocked = ref(false)
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
  MARKINGSITE.value = '/images/emr/pembukaan.jpg';
}

const loadRiwayat = async () => {
  // Lakukan pengambilan data riwayat dari API
  await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    .then((response: any) => {
      if (response.length) {
        // Mengatur nilai input.value jika perlu
        input.value = response[0] // Sesuaikan sesuai struktur respons API Anda
        if (response[0].anatomiTubuh) {
          let sigCanvas: any = document.getElementById('markingsite');
          if (sigCanvas) {

            let context = sigCanvas.getContext("2d");
            context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
            let imagess = response[0].anatomiTubuh
            let background = new Image();
            background.src = imagess
            background.onload = function () {
              context.drawImage(background, 0, 0, sigCanvas.width, sigCanvas.height);
            }
          }
        }
        if (response[0].tandaTanganAhliGizi) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganAhliGizi)
        }
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        chartHigh(response[0])
        chartHigh2(response[0])
      }
    })
}

const show = () => {
  showData.value = true
}

const SourceHasilLab = async () => {
  // Initialize sourceDataLab.value as empty only if necessary
  sourceDataLab.value = [];

  // Fetch first set of lab results (new data)
  await useApi()
    .get(`/bridging/penunjang/get-hasil-new-registrasi?norec_apd=${item.NOREC_APD}&nocmfk=${ID_PASIEN}&noregistrasi=${props.registrasi.noregistrasi}&nocm=${props.pasien.nocm}`)
    .then((response) => {
      if (response.length > 0) {
        const formattedResponse = response.flatMap((group) =>
          group
            .filter((item) => !item.IsHeader) // Only take non-header items
            .map((item) => ({
              nama_pemeriksaan: item.TestName,
              tgl_hasil: item.ValidateOn,
              normal: item.RefRange,
              no_order: item.OrderTestId,
              no_urut: parseInt(item.DispSequence, 10),
              hasil: item.ResultValue,
            }))
        );

        // Append formatted response to sourceDataLab.value
        sourceDataLab.value = sourceDataLab.value.concat(formattedResponse);
      }
    });

  // Fetch second set of lab results (existing data)
  await useApi()
    .get(`/laboratorium/source-hasil-lab?noregistrasi=${props.registrasi.noregistrasi}`)
    .then((response) => {
      const formattedExistingResponse = response.map((element) => ({
        ...element,
      }));

      // Append response to sourceDataLab.value without overwriting
      sourceDataLab.value = sourceDataLab.value.concat(formattedExistingResponse);
    });

  // Sort combined data by `tgl_hasil`
  sourceDataLab.value.sort((a, b) => new Date(a.tgl_hasil) - new Date(b.tgl_hasil));

  // Reassign sequential numbering after sorting
  sourceDataLab.value = sourceDataLab.value.map((item, index) => ({
    ...item,
    no: index + 1, // Assign new sequential number
  }));
};

const fetchPegawai = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
  d_Pegawai.value = response
}

const addRow = () => {
  input.value.detailObservasi.push({
    no: input.value.detailObservasi[input.value.detailObservasi.length - 1].no + 1,
  });
}
const removeRow = (index: any) => {
  input.value.detailObservasi.splice(index, 1)
}

const setTandaTangan = async (e: any) => {
  const response = await useApi().get(
    `/emr/tanda-tangan/${e.value.value}`)
  if (response != null) {
    H.tandaTangan().set("signature_1", response.ttd)
    input.value.tandaTanganAhliGizi = response.ttd
  } else {
    H.tandaTangan().set("signature_1", '')
  }
}

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}

const fetchDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/diagnosa_m?select=id,namadiagnosa&param_search=namadiagnosa&query=${filter.query}&limit=10`)
  d_Diagnosa.value = response
}

const fetchKelas = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/kelas_m?select=id,namakelas&param_search=namakelas&query=${filter.query}&limit=10`)
  d_Kelas.value = response
}

const fetchDokter = async (filter: any) => {
  const response = await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`)
  d_Dokter.value = response
}


const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  object.tandaTanganAhliGizi = H.tandaTangan().get("signature_1")
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let sigCanvas = document.getElementById("markingsite");
  if (sigCanvas) {
    let context = sigCanvas.getContext("2d");
    const dataURL = sigCanvas.toDataURL();
    input.value.anatomiTubuh = dataURL
    object.anatomiTubuh = dataURL
  }
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': props.FORM_URL,
    'name_form': props.FORM_NAME,
    'jenis_emr': 'asesmen_medis',
    'data': object
  }

  isLoading.value = true;
  useApi().post(`/emr/simpan-emr`, json)
    .then((response: any) => {
      isLoading.value = false;
      NOREC_EMRPASIEN.value = response.norec_emr;
      input.value.id = response.id;
      isLocked.value = true;
    })
    .catch((e: any) => {
      isLoading.value = false;
    })
}

const clearCanvas = (canvas: any) => {

var sigCanvas: any = document.getElementById(canvas);
var context = sigCanvas.getContext("2d");
context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);

}

const getPosition = (mouseEvent: any, sigCanvas: any) => {
  let rect = sigCanvas.getBoundingClientRect();
  return {
    X: mouseEvent.clientX - rect.left,
    Y: mouseEvent.clientY - rect.top
  };
}

const markignSite = () => {
  let sigCanvas: any = document.getElementById("markingsite");
  // sigCanvas.height = 500
  // sigCanvas.width = 500
  let context = sigCanvas.getContext("2d");
  context.strokeStyle = "red";
  context.lineJoin = "round";
  context.lineWidth = 2;
  let is_touch_device = 'ontouchstart' in document.documentElement;

  if (is_touch_device) {

    let drawer: any = {
      isDrawing: false,
      touchstart: function (coors: any) {
        context.beginPath();
        context.moveTo(coors.x, coors.y);
        this.isDrawing = true;
      },
      touchmove: function (coors: any) {
        if (this.isDrawing) {
          context.lineTo(coors.x, coors.y);
          context.stroke();
        }
      },
      touchend: function (coors: any) {
        if (this.isDrawing) {
          this.touchmove(coors);
          this.isDrawing = false;
        }
      }
    };


    function draw(event: any) {
      let coors = {
        x: event.targetTouches[0].pageX,
        y: event.targetTouches[0].pageY
      };

      let obj = sigCanvas;

      if (obj.offsetParent) {

        do {
          coors.x -= obj.offsetLeft;
          coors.y -= obj.offsetTop;
        }

        while ((obj = obj.offsetParent) != null);
      }


      drawer[event.type](coors);
    }


    sigCanvas.addEventListener('touchstart', draw, false);
    sigCanvas.addEventListener('touchmove', draw, false);
    sigCanvas.addEventListener('touchend', draw, false);


    sigCanvas.addEventListener('touchmove', function (event: any) {
      event.preventDefault();

    }, false);
  } else {

    $("#markingsite").mousedown(function (mouseEvent: any) {

      let position = getPosition(mouseEvent, sigCanvas);
      context.moveTo(position.X, position.Y);
      context.beginPath();
      $(this).mousemove(function (mouseEvent: any) {
        drawLine(mouseEvent, sigCanvas, context);
      }).mouseup(function (mouseEvent: any) {
        finishDrawing(mouseEvent, sigCanvas, context);
      }).mouseout(function (mouseEvent: any) {
        finishDrawing(mouseEvent, sigCanvas, context);
      });
    });

  }
}
const drawLine = (mouseEvent: any, sigCanvas: any, context: any) => {

  let position = getPosition(mouseEvent, sigCanvas);

  context.lineTo(position.X, position.Y);
  context.stroke();
}
const finishDrawing = (mouseEvent: any, sigCanvas: any, context: any) => {
  drawLine(mouseEvent, sigCanvas, context);

  context.closePath();
  $(sigCanvas).unbind("mousemove")
    .unbind("mouseup")
    .unbind("mouseout");
}

async function performAction() {
  await sleep(1000);
  markignSite();
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.tanggalWaktuRegistrasi = props.registrasi.tglregistrasi;
  input.value.tanggalWaktuKunjuangan = new Date();
  input.value.tglPembuatan = new Date();
  input.value.umur = props.pasien.umur;
  input.value.namaruangan = props.registrasi.namaruangan;
  input.value.namakelas = props.registrasi.namakelas;
  input.value.dokter = props.registrasi.dokter;

  isLoadingVitalSign.value = true;

  await useApi().get(
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=VitalSign" + "&field=beratBadan,tinggiBadan"
  ).then((response) => {
    if (response != null) {
      input.value.bb = response.beratBadan;
      input.value.tb = response.tinggiBadan;
    }
    isLoadingVitalSign.value = false;
  }).catch((error) => {
    console.error("Error fetching vital signs:", error);
    isLoadingVitalSign.value = false;
  })
  await useApi().get(
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=PengkajianAwalPasienGawatDarurat" + "&field=beratBadan,tinggiBadan"
  ).then((response) => {
    if (response != null) {
      input.value.bb = response.beratBadan;
      input.value.tb = response.tinggiBadan;
    }
    isLoadingPengkajianAwalPasienGawatDarurat.value = false;
  }).catch((error) => {
    console.error("Error fetching vital signs:", error);
    isLoadingPengkajianAwalPasienGawatDarurat.value = false;
  })
}
setView()
setAutoFill()
loadRiwayat()
await SourceHasilLab()
performAction()

onBeforeMount(async () => {
  try {
    await loadRiwayat()
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
    if (cache) input.value = cache
    loadData.value = false
  } catch (error) {
    console.error('Error mount cache TAB EMR:', error)
  }
})

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
  vertical-align: middle
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
  text-align: left;
  vertical-align: top;
  font-weight: bold;
}
</style>