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
    <VButton type="button" class="m-4" outlined icon="feather:plus"
  @click="tambahCatatan" color="success" v-tooltip-prime.top="'Tambah '">Tambah
</VButton>
<Fieldset :legend="`Catatan Transfer ke ${data.no}`" :toggleable="true" v-for="(data, indexDetail) in input.details">
  <VCard>
    <div class="column is-offset-10 is-2">
      <VButton type="button" class="w-full" outlined icon="feather:trash"
        @click="hapusPersetujuan(indexDetail)" color="danger" v-tooltip-prime.top="'Tambah '">Hapus Baris
      </VButton>
    </div>
    <div class="column is-12">
      <VCard class="p-3">
        <div class="column is-12 px-2">
          <div class="columns is-multiline">
            <div class="column is-2">
              <p>Asal Pasien Dirawat :</p>
            </div>
            <div class="column is-10">
              <div class="columns is-multiline">
                <VField v-for="asal in asalPasien" :key="asal.value" class="p-0 m-0 pt-4 column is-2">
                  <VControl raw subcontrol>
                      <VCheckbox v-model="data.asalPasien" class="pt-1 pb-1 "
                          :true-value="asal.value" :label="asal.label" color="primary" square />
                  </VControl>
                </VField>
              </div>
              <VField class="pt-3" v-if="data.asalPasien == 'IRJ'">
                <VControl>
                  <VInput type="text" v-model="data.asalPasienPoli"/>
                </VControl>
              </VField>
              <VField class="pt-3" v-if="data.asalPasien == 'IRNA'">
                <VControl>
                  <VInput type="text" v-model="data.asalPasienIRNA"/>
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </VCard>
    </div>
    <div class="column is-12">
      <VCard class="p-3">
        <div class="column is-12 px-2">
          <div class="columns is-multiline">
            <div class="column is-2">
              <p>Kepada Yth Dokter :</p>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput type="text" class="input" placeholder="Dokter Rujukan" v-model="data.dokterRujukan" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <p>Di RS :</p>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput type="text" class="input" placeholder="RS Rujukan" v-model="data.RSRujukan" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <p>Alamat :</p>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VTextarea rows="2" placeholder="Alamat" v-model="data.alamat"></VTextarea>
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </VCard>
    </div>
    <div class="column is-12">
      <VCard class="p-3">
        <div class="column is-12 px-2">
          <div class="columns is-multiline">
            <div class="column is-12">
              <p>Nama dan Jabatan Kontak Person yang sudah dihubungi dan siap menerima pasien di RS Tujuan :</p>
            </div>
            <div class="column is-12">
              <VField>
                <VControl>
                  <VInput type="text" class="input" placeholder="Kontak Tujuan" v-model="data.kontakTujuan" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </VCard>
    </div>
    <div class="column is-12">
      <VCard class="p-3">
        <div class="column is-12 px-2">
          <div class="columns is-multiline">
            <div class="column is-12 p-0 px-3">
              <p>Dengan Hormat,</p>
            </div>
            <div class="column is-12 p-0 px-3">
              <p>Bersama ini kami kirim / rujuk pasien :</p>
            </div>
            <div class="column is-1"></div>
            <div class="column is-2">
              <p>Nama :</p>
            </div>
            <div class="column is-9">
              <VField>
                <VControl>
                  <VInput type="text" class="input" placeholder="Nama Pasien" v-model="data.namaPasien" />
                </VControl>
              </VField>
            </div>
            <div class="column is-1"></div>
            <div class="column is-2">
              <p>Jenis Kelamin :</p>
            </div>
            <div class="column is-9">
              <div class="columns is-multiline">
                <VField v-for="items in JenisKelamin" :key="items.value" style="padding:0px;">
                    <VControl raw subcontrol>
                        <VCheckbox v-model="data.jenisKelaminPasien" class="pt-1 pb-1 "
                            :true-value="items.label" :label="items.label" color="primary"
                            square />
                    </VControl>
                </VField>
              </div>
            </div>
            <div class="column is-1"></div>
            <div class="column is-2">
              <p>Tanggal Lahir :</p>
            </div>
            <div class="column is-9">
              <VField>
                <VControl>
                  <VInput type="text" class="input" placeholder="Tanggal Lahir" v-model="data.tanggalLahirPasien" />
                </VControl>
              </VField>
            </div>
            <div class="column is-1"></div>
            <div class="column is-2">
              <p>Alamat :</p>
            </div>
            <div class="column is-9">
              <VField>
                <VControl>
                  <VInput type="text" class="input" placeholder="Alamat" v-model="data.alamatPasien" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </VCard>
    </div>
    <div class="column is-12">
      <VCard class="p-3">
        <div class="column is-12 px-2">
          <div class="columns is-multiline">
            <div class="column is-12">
              <p>Alasan dirawat di RSD Gunung Jati :</p>
            </div>
            <div class="column is-12">
              <VField>
                <VControl>
                  <VInput type="text" class="input" placeholder="Alasan dirawat" v-model="data.alasanDirawat" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </VCard>
    </div>
    <div class="column is-12">
      <VCard class="p-3">
        <div class="column is-12 px-2">
          <div class="columns is-multiline">
            <div class="column is-12">
              <p>Diagnosis Utama :</p>
            </div>
            <div class="column is-12">
              <VField>
                <VControl>
                  <VInput type="text" class="input" placeholder="Diagnosis Utama" v-model="data.diagnosisUtama" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </VCard>
    </div>
    <div class="column is-12">
      <VCard class="p-3">
        <div class="column is-12 px-2">
          <div class="columns is-multiline">
            <div class="column is-12">
              <p>Diagnosis Sekunder :</p>
            </div>
            <div class="column is-12">
              <VField>
                <VControl>
                  <VInput type="text" class="input" placeholder="Diagnosis Sekunder" v-model="data.diagnosisSekunder" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </VCard>
    </div>
    <div class="column is-12">
      <VCard class="p-3">
        <div class="column is-12 px-2">
          <div class="columns is-multiline">
            <div class="column is-2">
              <p>Alasan dirujuk :</p>
            </div>
            <div class="column is-10">
              <div class="columns is-multiline">
                <VField v-for="alasan in alasanDirujuk" :key="alasan.value" class="p-0 m-0 pt-4 column is-2">
                  <VControl raw subcontrol>
                      <VCheckbox v-model="data.alasanDirujuk" class="pt-1 pb-1 "
                          :true-value="alasan.value" :label="alasan.label" color="primary" square />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="column is-2">
              <p>Keterangan :</p>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput type="text" class="input" placeholder="Keterangan Dirujuk" v-model="data.keteranganDirujuk" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </VCard>
    </div>
    <div class="column is-12">
      <VCard class="p-3">
        <div class="column is-12 px-2">
          <div class="columns is-multiline">
            <div class="column is-12 p-0">
              <p>Hasil Pemeriksaan selama di rawat (Pemeriksaan Fisik & Penunjang yang mendukung Diagnosis)</p>
            </div>
            <div class="column is-2">
              <p>Kondisi Pasien Saat Pindah : </p>
            </div>
            <div class="column is-6">
              <div class="columns is-multiline">
                <VField v-for="sadar in JenisKesadaran" :key="sadar.value" class="p-0 m-0 pt-4 column is-2">
                  <VControl raw subcontrol>
                      <VCheckbox v-model="data.jenisKesadaran" class="pt-1 pb-1 "
                          :true-value="sadar.value" :label="sadar.label" color="primary" square />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="column is-1">
              <VField>
                  <VControl>
                    <VInput type="text" placeholder="Nilai GCS" v-model="data.GCS"/>
                  </VControl>
                </VField>
            </div>
            <div class="column is-1">
              <VField>
                  <VControl>
                    <VInput type="text" placeholder="Nilai E" v-model="data.E"/>
                  </VControl>
                </VField>
            </div>
            <div class="column is-1">
              <VField>
                  <VControl>
                    <VInput type="text" placeholder="Nilai M" v-model="data.M"/>
                  </VControl>
                </VField>
            </div>
            <div class="column is-1">
              <VField>
                  <VControl>
                    <VInput type="text" placeholder="Nilai V" v-model="data.V"/>
                  </VControl>
                </VField>
            </div>
            <div class="column is-12">
              <div class="columns is-multiline p-3">
                <div class="column is-3" v-for="(data, i) in vitalSign ">
                  <div class=" columns is-multiline">
                    <div class="column is-12" style="margin-top:0.5rem">
                      <span> {{ data.label }} : </span>
                    </div>
                    <div class="column is-12">
                      <VPlaceloadText :lines="1" v-if="isLoadingVitalSign" />
                      <VField addons v-else>
                        <VControl>
                          <VInput type="text" class="input" :placeholder="data.label" v-model="input[data.model]" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>{{ data.addon }} </VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-3">
                  <div class=" columns is-multiline">
                    <div class="column is-12" style="margin-top:0.5rem">
                      <span> Skor EWS : </span>
                    </div>
                    <div class="column is-12">
                      <VField addons>
                        <VControl>
                          <VInput type="text" class="input" placeholder="Skor EWS" v-model="data.SkorEWS" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>EWS</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </VCard>
    </div>
    <div class="column is-12">
      <VCard class="p-3">
        <div class="column is-12 px-2">
          <div class="columns is-multiline">
            <div class="column is-12">Prosedur / Tindakan yang sudah dilakukan</div>
            <div class="column is-4">
              <span> Penggunaan Oksigen : </span>
              <VField>
                <VControl>
                  <VInput type="text" class="input" placeholder="Penggunaan Oksigen (L/mnt)" v-model="data.penggunaanOksien" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <span> Cairan Parental : </span>
              <VField>
                <VControl>
                  <VInput type="text" class="input" placeholder="Cairan Parental (cc/24 jam)" v-model="data.cairanParental" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <span> Transfusi : </span>
              <VField>
                <VControl>
                  <VInput type="text" class="input" placeholder="Transfusi (cc)" v-model="data.transfusi" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <span> Infus : </span>
              <VField>
                <VControl>
                  <VInput type="text" class="input" placeholder="Transfusi (cc)" v-model="data.infus" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
                <div class="column" style="overflow: auto;">
                  <table class="tg">
                    <thead>
                      <tr>
                        <td class="tg-0lax text-center">No</td>
                        <td class="tg-0lax text-center">Nama Obat</td>
                        <td class="tg-0lax text-center">Dosis</td>
                        <td class="tg-0lax text-center">Aksi</td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(dataObat, index) in data.detailObatDibawaPasien" :key="index">
                        <td>{{ dataObat.no }}</td>
                        <td width="35%">
                          <VField>
                            <VControl>
                              <AutoComplete v-model="dataObat.produk" :suggestions="d_produk"
                                @complete="fetchProduk($event)" :optionLabel="'namaproduk'" :dropdown="true"
                                :minLength="3" class="is-input" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                :field="'namaproduk'" placeholder="ketik untuk mencari..." />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl icon="feather:bookmark">
                              <VInput type="text" v-model="dataObat.dosis" placeholder="Dosis..." class="input" />
                            </VControl>
                          </VField>
                        </td>
                        <td class="td-fkprj" style="vertical-align: inherit;">
                          <div class="column">
                            <VIconButton type="button" raised circle icon="feather:plus"
                              @click="addNewItemObatDibawaPulang(indexDetail)" color="info" v-tooltip.bubble="'Tambah '">
                            </VIconButton>
                            <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                              @click="removeItemObatDibawaPulang(indexDetail, index)" color="danger">
                            </VIconButton>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
            </div>
            <div class="column is-12">
              <span> Tindakan : </span>
              <VField>
                <VControl>
                  <VTextarea rows="2" placeholder="Tindakan" v-model="data.tindakan"></VTextarea>
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </VCard>
    </div>
    <div class="column is-12">
      <VCard class="p-3">
        <div class="column is-12 p-0">
          <div class="column is-12" style="margin-top:0.5rem">
            <span> Penggunaan Cateter : </span>
          </div>
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="data.penggunaanCateter" true-value="Ada" label="Ada" class="p-0" color="primary"
                      square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="data.penggunaanCateter" true-value="Tidak Ada" label="Tidak" class="p-0" color="primary"
                      square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4" v-if="data.penggunaanCateter == 'Ada'">
                <span> Pemakaian ke- : </span>
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Pemakaian Infus" v-model="data.pemakaianInfus" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4" v-if="data.penggunaanCateter == 'Ada'">
                <VField label="Tanggal Waktu">
                  <VDatePicker v-model="data.tanggalWaktuCateter" mode="dateTime" style="width: 100%" trim-weeks
                    :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" readonly />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>
          </div>
        </div>
      </VCard>
    </div>
    <div class="column is-12">
      <VCard class="p-3">
        <div class="column is-12">
            <span> Diet : </span>
            <VField>
              <VTextarea rows="2" placeholder="Diet....." v-model="data.diet"></VTextarea>
            </VField>
          </div>
      </VCard>
    </div>
    <div class="column is-12">
      <VCard class="p-3">
        <div class="column is-12">
            <span> Mobilisasi : </span>
            <VField>
              <VTextarea rows="2" placeholder="Mobilisasi....." v-model="data.mobilisasi"></VTextarea>
            </VField>
          </div>
      </VCard>
    </div>
    <div class="column is-12">
      <VCard class="p-3">
        <div class="column is-12">
          <span> Edukasi : </span>
          <VField>
            <VTextarea rows="2" placeholder="Edukasi....." v-model="data.edukasi"></VTextarea>
          </VField>
          </div>
      </VCard>
    </div>
    <div class="column is-12">
      <VCard class="p-3">
        <div class="column is-12 px-2">
          <div class="columns is-multiline">
            <div class="column is-2">
              <p>Cara Transportasi :</p>
            </div>
            <div class="column is-10">
              <div class="columns is-multiline">
                <VField v-for="transportasi in caraTransportasi" :key="transportasi.value" class="p-0 m-0 pt-4 column is-4">
                  <VControl raw subcontrol>
                      <VCheckbox v-model="data.caraTransportasi" class="pt-1 pb-1 "
                          :true-value="transportasi.value" :label="transportasi.label" color="primary" square />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </div>
      </VCard>
    </div>
    <div class="column is-12">
      <VCard class="p-3">
        <div class="column is-12 px-2">
          <div class="columns is-multiline">
            <div class="column is-12">
              <p>Derajat Transfer Catatan Khusus / Keterangan Lain :</p>
            </div>
            <div class="column is-2">
              <p>Derajat :</p>
            </div>
            <div class="column is-10">
              <div class="columns is-multiline">
                <VField v-for="derajat in derajatTransfer" :key="derajat.value" class="p-0 m-0 pt-4 column is-1">
                  <VControl raw subcontrol>
                      <VCheckbox v-model="data.derajatTransfer" class="pt-1 pb-1 "
                          :true-value="derajat.value" :label="derajat.label" color="primary" square />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </div>
      </VCard>
    </div>

    <div class="columns is-multiline">
      <div class="column is-4" style="margin-left: auto;">
        <VCard>
          <h1 style="font-weight: bold; text-align: center"> Yang Membuat, DPJP </h1>
          <div class="mt-6" style="text-align: center">
            <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
            <img v-if="data.dokter"
                      :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (data.dokter ? data.dokter : '-')">
          </div>
          <div>
            <h1 class="p-0" style="font-weight: bold;">Dokter DPJP</h1>
            <VField>
              <VControl class="prime-auto">
                <AutoComplete v-model="data.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Dokter DPJP..." class="mt-2"
                  @item-select="setTandaTangan($event)" />
              </VControl>
            </VField>
          </div>
        </VCard>
      </div>
      <div class="column is-4" style="margin-left: auto;">
        <VCard>
          <h1 style="font-weight: bold;"> Diterima, tanggal/pkl : Perawat Yang Menerima </h1>
          <VField>
            <VDatePicker v-model="data.tglPerawatTerima" mode="dateTime" style="width: 100%" trim-weeks
              :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </VField>
          <div style="text-align: center">
            <!-- <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan> -->
            <img v-if="data.petugasPerawatTerima"
                :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (data.petugasPerawatTerima ? data.petugasPerawatTerima.label : '-')">
          </div>
          <div>
            <h1 class="p-0" style="font-weight: bold;">Petugas Perawat</h1>
            <VField>
              <VControl class="prime-auto">
                <AutoComplete v-model="data.petugasPerawatTerima" :suggestions="d_Pegawai"
                  @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pegawai..."
                  class="mt-2" @item-select="setTandaTangan($event)" />
              </VControl>
            </VField>
          </div>
        </VCard>
      </div>
      <div class="column is-4" style="margin-left: auto;">
        <VCard>
          <h1 style="font-weight: bold;"> Diantar, tanggal/pkl : Perawat Yang Menyerahkan </h1>
          <VField>
            <VDatePicker v-model="data.tglPerawatMenyerahkan" mode="dateTime" style="width: 100%" trim-weeks
              :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </VField>
          <div style="text-align: center">
            <!-- <TandaTangan :elemenID="'signature_3'" :width="'180'" :height="'180'"></TandaTangan> -->
            <img v-if="data.petugasPerawatMenyerahkan"
            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (data.petugasPerawatMenyerahkan ? data.petugasPerawatMenyerahkan.label : '-')">
          </div>
          <div>
            <h1 class="p-0" style="font-weight: bold;">Petugas Perawat</h1>
            <VField>
              <VControl class="prime-auto">
                <AutoComplete v-model="data.petugasPerawatMenyerahkan" :suggestions="d_Pegawai"
                  @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pegawai..."
                  class="mt-2" @item-select="setTandaTangan($event)" />
              </VControl>
            </VField>
          </div>
        </VCard>
      </div>
    </div>
  </VCard>
</Fieldset>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as EMR from '../page-emr-plugins/pengkajian-awal-pj'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let vitalSign = ref(EMR.vitalSign())

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
const isLoading: any = ref(false)
const isLoadingVitalSign: any = ref(false)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const d_Ruangan: any = ref([])
const d_produk: any = ref([])
const d_Kelas: any = ref([])
const d_Diagnosa: any = ref([])
const isLoadingTT: any = ref(false)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const input: any = ref({
  details : [{
    no: 1,
    detailObatDibawaPasien: [{ no: 1 }]
  }]
})
const asalPasien: any = ref([
    { label: 'IRJ Poliklinik', value: 'IRJ' },
    { label: 'IBS', value: 'IBS' },
    { label: 'IGD', value: 'IGD' },
    { label: 'IRNA', value: 'IRNA' }
])
const alasanDirujuk: any = ref([
    { label: 'Ruangan Rawat Penuh', value: 'RawatPenuh' },
    { label: 'Perlu Fasilitias lebih baik', value: 'FasilitasBaik' },
    { label: 'Permintaan Sendiri', value: 'PermintaanSendiri' },
    { label: 'Kasus Polisi', value: 'KasusPolisi' },
    { label: 'Pulang APS', value: 'PulangAPS' }
])
const JenisKelamin: any = ref([
    { label: 'Laki - Laki', value: 'Laki - Laki' },
    { label: 'Perempuan', value: 'Perempuan' }
])
const JenisKesadaran: any = ref([
    { label: 'CM', value: 'CM' },
    { label: 'Apatis', value: 'Apatis' },
    { label: 'Delirium', value: 'Delirium' },
    { label: 'Sopor', value: 'Sopor' },
    { label: 'GCS', value: 'GCS' },
])
const derajatTransfer: any = ref([
    { label: '0', value: '0' },
    { label: '0,5', value: '0,5' },
    { label: '1', value: '1' },
    { label: '2', value: '2' },
    { label: '3', value: '3' },
])
const caraTransportasi: any = ref([
    { label: 'Kendaraan Umum', value: 'Kendaraan Umum' },
    { label: 'Kendaraan Pribadi', value: 'Kendaraan Pribadi' },
    { label: 'Ambulance RSD Gunung Jati', value: 'Ambulance RSD Gunung Jati' },
    { label: 'Ambulance 118', value: 'Ambulance 118' },
    { label: 'Ambulance 119', value: 'Ambulance 119' },
])
const COLLECTION: any = ref('catatanPasienPindahExternal') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = async () => {
  // if (NOREC_EMRPASIEN.value == '') return
  await useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
    if (response.length) {
      if(response[0].details){
        input.value = response[0]
      }else{
        input.value.details = []
        response[0].no = 1
        input.value.details.push(response[0])
        console.log(input.value.details);
      }
        if (response[0].tandaTanganDokter) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganDokter)
        }
        if (response[0].tandaTanganPerawatTerima) {
          H.tandaTangan().set("signature_2", response[0].tandaTanganPerawatTerima)
        }
        if (response[0].tandaTanganPerawatSerah) {
          H.tandaTangan().set("signature_3", response[0].tandaTanganPerawatSerah)
        }
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }
    })
}

const tambahCatatan = () => {
  input.value.details.push({
    no: input.value.details.length + 1,
    detailObatDibawaPasien: [{ no: 1 }],
    tanggalWaktuRegistrasi : props.registrasi.tglregistrasi,
    tanggalWaktuKunjuangan : new Date(),
    tglPembuatan : new Date(),
    umur : props.pasien.umur,
    namaruangan : props.registrasi.namaruangan,
    namakelas : props.registrasi.namakelas,
    dokter : props.registrasi.dokter,
    // namadiagnosa = props.diagnosis.namadiagnosa

  })
  useApi().get(
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=VitalSign" + "&field=beratBadan,tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi"
  ).then((response) => {
    if(response != null){
      input.value.details[input.value.details.length - 1].beratBadan = response.beratBadan
      input.value.details[input.value.details.length - 1].tinggiBadan = response.tinggiBadan
      input.value.details[input.value.details.length - 1].IMT = response.IMT
      input.value.details[input.value.details.length - 1].lingkarPerut = response.lingkarPerut
      input.value.details[input.value.details.length - 1].tekananDarah = response.tekananDarah
      input.value.details[input.value.details.length - 1].pernapasan = response.pernapasan
      input.value.details[input.value.details.length - 1].suhu = response.suhu
      input.value.details[input.value.details.length - 1].nadi = response.nadi
    }
    isLoadingVitalSign.value =false;
  })
}

const hapusPersetujuan = (indexDetail: any) => {
  input.value.details.splice(indexDetail, 1)
}


const addNewItemObatDibawaPulang = (indexData: any) => {
  input.value.details[indexData].detailObatDibawaPasien.push({
    no: input.value.details[indexData].detailObatDibawaPasien.length + 1,
  });
}
const removeItemObatDibawaPulang = (indexDetail:any, index: any) => {
  input.value.details[indexDetail].detailObatDibawaPasien.splice(index, 1)
}

const fetchProduk = async (filter: any) => {
  const response = await useApi().get(
    `/farmasi/dropdown-obat?namaproduk=${filter.query}&limit=10`)
  d_produk.value = response

}

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}
const setTandaTangan = async (e: any) => {
  const response = await useApi().get(
    `/emr/tanda-tangan/${e.value.value}`)
  if (response != null) {
    H.tandaTangan().set("signature_1", response.ttd)
    input.value.tandaTanganDokter = response.ttd
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
  object.tandaTanganDokter = H.tandaTangan().get("signature_1")
  object.tandaTanganPerawatTerima = H.tandaTangan().get("signature_2")
  object.tandaTanganPerawatSerah = H.tandaTangan().get("signature_3")
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


let kesadaran = ref([
  {
    "no": 1,
    "parameter": "E",
    "nilai": [
      {
        "model": "kesadaranE",
        "poin": "1"
      },
      {
        "model": "kesadaranE",
        "poin": "2"
      },
      {
        "model": "kesadaranE",
        "poin": "3"
      },
      {
        "model": "kesadaranE",
        "poin": "4"
      },
    ]
  },
  {
    "no": 2,
    "parameter": "M",
    "nilai": [
      {
        "model": "kesadaranM",
        "poin": "1"
      },
      {
        "model": "kesadaranM",
        "poin": "2"
      },
      {
        "model": "kesadaranM",
        "poin": "3"
      },
      {
        "model": "kesadaranM",
        "poin": "4"
      },
      {
        "model": "kesadaranM",
        "poin": "5"
      },
      {
        "model": "kesadaranM",
        "poin": "6"
      },
    ]
  },
  {
    "no": 3,
    "parameter": "V",
    "nilai": [
      {
        "model": "kesadaranV",
        "poin": "5"
      },
      {
        "model": "kesadaranV",
        "poin": "4"
      },
      {
        "model": "kesadaranV",
        "poin": "3"
      },
      {
        "model": "kesadaranV",
        "poin": "2"
      },
      {
        "model": "kesadaranV",
        "poin": "1"
      },
    ]
  },
])

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.details.forEach(element => {
    element.tanggalWaktuRegistrasi = props.registrasi.tglregistrasi
    element.tanggalWaktuKunjuangan = new Date()
    element.tglPembuatan = new Date()
    element.umur = props.pasien.umur
    element.namaruangan = props.registrasi.namaruangan
    element.namakelas = props.registrasi.namakelas
    element.dokter = props.registrasi.dokter
    element.petugasPerawatTerima = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
    element.petugasPerawatMenyerahkan = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
    // element.namadiagnosa = props.diagnosis.namadiagnosa

    isLoadingVitalSign.value = true;
    useApi().get(
      "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
      "&collection=VitalSign" + "&field=beratBadan,tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi"
    ).then((response) => {
      if(response != null){
        element.beratBadan = response.beratBadan
        element.tinggiBadan = response.tinggiBadan
        element.IMT = response.IMT
        element.lingkarPerut = response.lingkarPerut
        element.tekananDarah = response.tekananDarah
        element.pernapasan = response.pernapasan
        element.suhu = response.suhu
        element.nadi = response.nadi
      }
      isLoadingVitalSign.value =false;
    })

    // diet
    useApi().get(
      "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
      "&collection=pengkajianDokterGawatDarurat" + "&field=rencanaTerapiDietKet,rencanaTerapiDiet"
    ).then((response) => {
      if(response != null) {
        element.diet = response.rencanaTerapiDietKet
      }
    })
  })
}
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
}</style>
