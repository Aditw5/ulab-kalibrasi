<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
            <div v-if="isStuck" :class="[isStuck && 'is-stuck']" style="margin-top:50px; padding: 0px 0px !important;" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ props.FORM_NAME }}</h3>
                    </div>
                    <div class="right">
                        <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan"
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
                        <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan"
                        @kembaliKeun="kembaliKeun"></ButtonEmr>
                    </div>
                </div>
            </div>

    </div>
  </div>
  <!-- pemeriksaan luar -->
  <div class="columns is-12">
    <VCard>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="PEMERIKSAAN LUAR" :toggleable="true" style="overflow: scroll">
          <div class="columns is-multiline p-3" style="width: 100% !important;">

            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-12" style="margin-top: 0.5rem">
                  <span>1. Tutup/Bungkus mayat dari luar ke dalam :</span>
                </div>
              </div>
            </div>
            <div class="column is-12">
              <VField>
                <VControl>
                  <VTextarea
                    class="input"
                    placeholder="... "
                    v-model="input.pemeriksaanLuar1"
                    rows="2"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-12" style="margin-top: 0.5rem">
                  <span>2. Benda di samping mayat :</span>
                </div>
              </div>
            </div>
            <div class="column is-12">
              <VField>
                <VControl>
                  <VTextarea
                    class="input"
                    placeholder="... "
                    v-model="input.pemeriksaanLuar2"
                    rows="2"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-12" style="margin-top: 0.5rem">
                  <span>3. Perhiasan yang dipakai oleh mayat :</span>
                </div>
              </div>
            </div>
            <div class="column is-12">
              <VField>
                <VControl>
                  <VTextarea
                    class="input"
                    placeholder="... "
                    v-model="input.pemeriksaanLuar3"
                    rows="2"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-12" style="margin-top: 0.5rem">
                  <span>4. Mayat menggunakan pakaian :
                  (Sebutkan dengan lengkap, jenis pakaian, warna dasar, corak, robekan, urutan dari luar ke dalam)
                  </span>
                </div>
              </div>
            </div>
            <div class="column is-12">
              <VField>
                <VControl>
                  <VTextarea
                    class="input"
                    placeholder="... "
                    v-model="input.pemeriksaanLuar4"
                    rows="2"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top: 0.5rem">
                  <span>5. - Kaku mayat terdapat pada :</span>
                </div>
                <div class="column is-2" style="margin-top: 0.5rem">
                  <VField>
                    <VControl>
                      <VTextarea
                        class="input"
                        placeholder="... "
                        v-model="input.pemeriksaanLuar5"
                        rows="1"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1" style="margin-top: 0.5rem">
                  <VField>
                    <VControl>
                      <VCheckbox class="p-0" v-model="input.pemeriksaanLuar5Lengkap" label="Belum Lengkap" true-value="Belum"
                        color="primary" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1" style="margin-top: 0.5rem">
                  <VField>
                    <VControl>
                      <VCheckbox class="p-0" v-model="input.pemeriksaanLuar5Lengkap" label="Sudah Lengkap" true-value="Sudah"
                        color="primary" />
                    </VControl>
                  </VField>
                </div>
                
                <div class="column is-1" style="margin-top: 0.5rem">
                  <VField>
                    <VControl>
                      <VCheckbox class="p-0" v-model="input.pemeriksaanLuar5dilawan" label="Mudah Dilawan" true-value="Mudah"
                        color="primary" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1" style="margin-top: 0.5rem">
                  <VField>
                    <VControl>
                      <VCheckbox class="p-0" v-model="input.pemeriksaanLuar5dilawan" label="Sukar Dilawan" true-value="Sukar"
                        color="primary" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top: 0.5rem">
                  <span>&nbsp;&nbsp;&nbsp;- Lebam mayat terdapat pada :</span>
                </div>
                <div class="column is-4" style="margin-top: 0.5rem">
                  <VField>
                    <VControl>
                      <VTextarea
                        class="input"
                        placeholder="... "
                        v-model="input.pemeriksaanLuar5Lebam"
                        rows="1"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top: 0.5rem">
                  <span>&nbsp;&nbsp;&nbsp;- Warna :</span>
                </div>
                <div class="column is-4" style="margin-top: 0.5rem">
                  <VField>
                    <VControl>
                      <VTextarea
                        class="input"
                        placeholder="... "
                        v-model="input.pemeriksaanLuar5Warna"
                        rows="1"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" style="margin-top: 0.5rem">
                  <VField>
                    <VControl>
                      <VCheckbox class="p-0" v-model="input.pemeriksaanLuar5Penekanan" label="Hilang" true-value="hilang"
                        color="primary" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" style="margin-top: 0.5rem">
                  <VField>
                    <VControl>
                      <VCheckbox class="p-0" v-model="input.pemeriksaanLuar5Penekanan" label="Tidak Hilang Pada Penekanan" true-value="Tidak"
                        color="primary" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top: 0.5rem">
                  <span>&nbsp;&nbsp;&nbsp;- Kulit perut kanan bawah :</span>
                </div>
                <div class="column is-2" style="margin-top: 0.5rem">
                  <VField>
                    <VControl>
                      <VCheckbox class="p-0" v-model="input.pemeriksaanLuar5Kulit" label="Sudah Tampak warna biru kehijauan" true-value="Sudah"
                        color="primary" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" style="margin-top: 0.5rem">
                  <VField>
                    <VControl>
                      <VCheckbox class="p-0" v-model="input.pemeriksaanLuar5Kulit" label="Belum Tampak warna biru kehijauan" true-value="Belum"
                        color="primary" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top: 0.5rem">
                  <span>&nbsp;&nbsp;&nbsp;- Keterangan lain :</span>
                </div>
                <div class="column is-4" style="margin-top: 0.5rem">
                  <VField>
                    <VControl>
                      <VTextarea
                        class="input"
                        placeholder="... "
                        v-model="input.pemeriksaanLuar5Lain"
                        rows="1"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top: 0.5rem">
                  <span>6. - Mayat adalah seorang :</span>
                </div>
                <div class="column is-2" style="margin-top: 0.5rem">
                  <VField>
                    <VControl>
                      <VCheckbox class="p-0" v-model="input.pemeriksaanLuar6" label="Laki-Laki" true-value="Laki-laki"
                        color="primary" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" style="margin-top: 0.5rem">
                  <VField>
                    <VControl>
                      <VCheckbox class="p-0" v-model="input.pemeriksaanLuar6" label="Perempuan" true-value="Perempuan"
                        color="primary" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top: 0.5rem">
                  <span>&nbsp;&nbsp;&nbsp;- Berumur sekitar :</span>
                </div>
                <div class="column is-1" style="margin-top: 0.5rem">
                  <VField>
                    <VControl>
                      <VTextarea
                        class="input"
                        placeholder="... "
                        v-model="input.pemeriksaanLuar6Thn"
                        rows="1"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1" style="margin-top: 0.5rem">
                  <span>Tahun</span>
                </div>
                <div class="column is-1" style="margin-top: 0.5rem">
                  <VField>
                    <VControl>
                      <VTextarea
                        class="input"
                        placeholder="... "
                        v-model="input.pemeriksaanLuar6Bln"
                        rows="1"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1" style="margin-top: 0.5rem">
                  <span>Bulan</span>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top: 0.5rem">
                  <span>&nbsp;&nbsp;&nbsp;- Warna Kulit :</span>
                </div>
                <div class="column is-2" style="margin-top: 0.5rem">
                  <VField>
                    <VControl>
                      <VTextarea
                        class="input"
                        placeholder="... "
                        v-model="input.pemeriksaanLuar6Warna"
                        rows="1"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top: 0.5rem">
                  <span>&nbsp;&nbsp;&nbsp;- Gizi :</span>
                </div>
                <div class="column is-1" style="margin-top: 0.5rem">
                  <VField>
                    <VControl>
                      <VCheckbox class="p-0" v-model="input.pemeriksaanLuar6Gizi" label="Baik" true-value="Baik"
                        color="primary" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1" style="margin-top: 0.5rem">
                  <VField>
                    <VControl>
                      <VCheckbox class="p-0" v-model="input.pemeriksaanLuar6Gizi" label="Cukup" true-value="Cukup"
                        color="primary" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1" style="margin-top: 0.5rem">
                  <VField>
                    <VControl>
                      <VCheckbox class="p-0" v-model="input.pemeriksaanLuar6Gizi" label="Kurang" true-value="Kurang"
                        color="primary" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top: 0.5rem">
                  <span>&nbsp;&nbsp;&nbsp;- Zakar :</span>
                </div>
                <div class="column is-2" style="margin-top: 0.5rem">
                  <VField>
                    <VControl>
                      <VCheckbox class="p-0" v-model="input.pemeriksaanLuar6Zakar" label="Sunat" true-value="Sunat"
                        color="primary" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" style="margin-top: 0.5rem">
                  <VField>
                    <VControl>
                      <VCheckbox class="p-0" v-model="input.pemeriksaanLuar6Zakar" label="Tidak Disunat" true-value="Tidak"
                        color="primary" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top: 0.5rem">
                  <span>&nbsp;&nbsp;&nbsp;- Panjang Badan :</span>
                </div>
                <div class="column is-1" style="margin-top: 0.5rem">
                  <VField>
                    <VControl>
                      <VTextarea
                        class="input"
                        placeholder="... "
                        v-model="input.pemeriksaanLuar6PB"
                        rows="1"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1" style="margin-top: 0.5rem">
                  <span>Cm</span>
                </div>
                <div class="column is-2" style="margin-top: 0.5rem">
                  <span>&nbsp;&nbsp;&nbsp;- Berat Badan :</span>
                </div>
                <div class="column is-1" style="margin-top: 0.5rem">
                  <VField>
                    <VControl>
                      <VTextarea
                        class="input"
                        placeholder="... "
                        v-model="input.pemeriksaanLuar6BB"
                        rows="1"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1" style="margin-top: 0.5rem">
                  <span>Kg</span>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-12" style="margin-top: 0.5rem">
                  <span>7. Identifikasi Khusus :
                  (Kelainan bawaan, cacat tubuh, jaringan parut luka, tattoo dll)
                  </span>
                </div>
              </div>
            </div>
            <div class="column is-12">
              <VField>
                <VControl>
                  <VTextarea
                    class="input"
                    placeholder="... "
                    v-model="input.pemeriksaanLuar7"
                    rows="2"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top: 0.5rem">
                  <span>8. - Rambut-rambut :</span>
                </div>
                <div class="column is-2" style="margin-top: 0.5rem">
                  <VField>
                    <VControl>
                      <VCheckbox class="p-0" v-model="input.pemeriksaanLuar8Rambut" label="Mudah" true-value="Mudah"
                        color="primary" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" style="margin-top: 0.5rem">
                  <VField>
                    <VControl>
                      <VCheckbox class="p-0" v-model="input.pemeriksaanLuar8Rambut" label="Tidak Mudah" true-value="Tidak"
                        color="primary" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1" style="margin-top: 0.5rem">
                  <span>Dicabut</span>
                </div>
              </div>
              <div class="column is-12 p-0">
                <div class="is-flex">
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <span>&nbsp;&nbsp;&nbsp;- Rambut kepala warna :</span>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar8WarnaRambut"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar8LebatRambut" label="Tumbuh Lebat" true-value="Lebat"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar8LebatRambut" label="Jarang" true-value="Jarang"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <span>, Panjang</span>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar8PanjangRambut"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <span>Cm</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="column is-12 p-0">
                <div class="is-flex">
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <span>&nbsp;&nbsp;&nbsp;- Alis mata warna :</span>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar8WarnaRambutAlis"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar8LebatAlis" label="Tumbuh Lebat" true-value="Lebat"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar8LebatAlis" label="Jarang" true-value="Jarang"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <span>, Panjang</span>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar8PanjangAlis"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <span>Cm</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="column is-12 p-0">
                <div class="is-flex">
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <span>&nbsp;&nbsp;&nbsp;- Bulu mata warna :</span>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar8WarnaRambutBulu"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar8LebatBulu" label="Tumbuh Lebat" true-value="Lebat"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar8LebatBulu" label="Jarang" true-value="Jarang"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <span>, Panjang</span>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar8PanjangBulu"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <span>Cm</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="column is-12 p-0">
                <div class="is-flex">
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <span>&nbsp;&nbsp;&nbsp;- Kumis warna :</span>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar8WarnaRambutKumis"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar8LebatKumis" label="Tumbuh Lebat" true-value="Lebat"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar8LebatKumis" label="Jarang" true-value="Jarang"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <span>, Panjang</span>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar8PanjangKumis"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <span>Cm</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="column is-12 p-0">
                <div class="is-flex">
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <span>&nbsp;&nbsp;&nbsp;- Jenggot warna :</span>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar8WarnaRambutJenggot"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar8LebatJenggot" label="Tumbuh Lebat" true-value="Lebat"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar8LebatJenggot" label="Jarang" true-value="Jarang"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <span>, Panjang</span>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar8PanjangJenggot"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <span>Cm</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="column is-12 p-0">
                <div class="is-flex">
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <span>&nbsp;&nbsp;&nbsp;- Rambut Ketiak warna :</span>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar8WarnaRambutKetiak"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar8LebatKetiak" label="Tumbuh Lebat" true-value="Lebat"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar8LebatKetiak" label="Jarang" true-value="Jarang"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <span>, Panjang</span>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar8PanjangKetiak"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <span>Cm</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="column is-12 p-0">
                <div class="is-flex">
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <span>&nbsp;&nbsp;&nbsp;- Rambut Kemaluan warna :</span>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar8WarnaRambutKemaluan"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar8LebatKemaluan" label="Tumbuh Lebat" true-value="Lebat"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar8LebatKemaluan" label="Jarang" true-value="Jarang"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <span>, Panjang</span>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar8PanjangKemaluan"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <span>Cm</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="column is-12 p-0">
                <div class="is-flex">
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <span>&nbsp;&nbsp;&nbsp;- Rambut Tungkai warna :</span>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar8WarnaRambutTungkai"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar8LebatTungkai" label="Tumbuh Lebat" true-value="Lebat"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar8LebatTungkai" label="Jarang" true-value="Jarang"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <span>, Panjang</span>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar8PanjangTungkai"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <span>Cm</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="column is-12 p-0">
                <div class="is-flex">
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <span>9.	Kepala bentuk :</span>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar9KepalaBentuk" label="Simetris" true-value="Simetris"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar9KepalaBentuk" label="Asimetris :" true-value="Asimetris"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="column is-12">
                <div class="columns is-multiline p-3" style="width: 100% !important;">
                  <table class="tg table-tg" style="width: 100% !important;">
                    <tbody>
                      <tr style="height: 18px;">
                        <td style="width: 21.3855%; height: 18px;">Mata</td>
                        <td style="width: 7.11932%; height: 18px; border-right-style: hidden;">Mata Kanan</td>
                        <td style="width: 8.90244%; height: 18px;">
                          <VField>
                            <VControl>
                              <VCheckbox class="p-0" v-model="input.pemeriksaanLuar9MataKanan" label="Tertutup" true-value="Tertutup"
                                color="primary" />
                            </VControl>
                          </VField>
                        </td>
                        <td style="width: 10.2743%; height: 18px; border-right-style: hidden;">
                          <VField>
                            <VControl>
                              <VCheckbox class="p-0" v-model="input.pemeriksaanLuar9MataKanan" label="Terbuka :" true-value="Terbuka"
                                color="primary" />
                            </VControl>
                          </VField>
                        </td>
                        <td style="width: 8.49106%; height: 18px; border-right-style: hidden;">
                          <VField>
                            <VControl>
                              <VInput type="text" class="input" placeholder="..." v-model="input.pemeriksaanLuar9MataKananMM" />
                            </VControl>
                          </VField>
                        </td>
                        <td style="width: 5.61042%; height: 18px;">mm</td>
                        <td style="width: 7.3937%; height: 18px; border-right-style: hidden;">Mata Kiri</td>
                        <td style="width: 8.90244%; height: 18px; border-right-style: hidden;">
                          <VField>
                            <VControl>
                              <VCheckbox class="p-0" v-model="input.pemeriksaanLuar9MataKiri" label="Tertutup" true-value="Tertutup"
                                color="primary" />
                            </VControl>
                          </VField>
                        </td>
                        <td style="width: 10.2743%; height: 18px; border-right-style: hidden;">
                          <VField>
                            <VControl>
                              <VCheckbox class="p-0" v-model="input.pemeriksaanLuar9MataKiri" label="Terbuka :" true-value="Terbuka"
                                color="primary" />
                            </VControl>
                          </VField>
                        </td>
                        <td style="width: 8.49106%; height: 18px; border-right-style: hidden;">
                          <VField>
                            <VControl>
                              <VInput type="text" class="input" placeholder="..." v-model="input.pemeriksaanLuar9MataKiriMM" />
                            </VControl>
                          </VField>
                        </td>
                        <td style="width: 5.68587%; height: 18px;">mm</td>
                      </tr>
                      <tr style="height: 54px;">
                        <td style="width: 21.3855%; height: 54px;">Selaput kelopak mata warna :</td>
                        <td style="width: 7.11932%; height: 54px;" colspan="5">
                          <VField>
                            <VControl>
                              <VTextarea
                                class="input"
                                placeholder="... "
                                v-model="input.pemeriksaanLuar9MataKananKelopak"
                                rows="1"
                              />
                            </VControl>
                          </VField>
                        </td>
                        <td style="width: 7.3937%; height: 54px;" colspan="5">
                          <VField>
                            <VControl>
                              <VTextarea
                                class="input"
                                placeholder="... "
                                v-model="input.pemeriksaanLuar9MataKiriKelopak"
                                rows="1"
                              />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr style="height: 36px;">
                        <td style="width: 21.3855%; height: 36px;">Selaput bola mata warna </td>
                        <td style="width: 7.11932%; height: 54px;" colspan="5">
                          <VField>
                            <VControl>
                              <VTextarea
                                class="input"
                                placeholder="... "
                                v-model="input.pemeriksaanLuar9MataKananBola"
                                rows="1"
                              />
                            </VControl>
                          </VField>
                        </td>
                        <td style="width: 7.3937%; height: 54px;" colspan="5">
                          <VField>
                            <VControl>
                              <VTextarea
                                class="input"
                                placeholder="... "
                                v-model="input.pemeriksaanLuar9MataKiriKBola"
                                rows="1"
                              />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr style="height: 47px;">
                        <td style="width: 21.3855%; height: 47px;">Selaput bening mata warna :</td>
                        <td style="width: 7.11932%; height: 54px;" colspan="5">
                          <VField>
                            <VControl>
                              <VTextarea
                                class="input"
                                placeholder="... "
                                v-model="input.pemeriksaanLuar9MataKananBening"
                                rows="1"
                              />
                            </VControl>
                          </VField>
                        </td>
                        <td style="width: 7.3937%; height: 54px;" colspan="5">
                          <VField>
                            <VControl>
                              <VTextarea
                                class="input"
                                placeholder="... "
                                v-model="input.pemeriksaanLuar9MataKiriKBening"
                                rows="1"
                              />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr style="height: 47px;">
                        <td style="width: 21.3855%; height: 47px;">Tirai mata warna :</td>
                        <td style="width: 7.11932%; height: 54px;" colspan="5">
                          <VField>
                            <VControl>
                              <VTextarea
                                class="input"
                                placeholder="... "
                                v-model="input.pemeriksaanLuar9MataKananTirai"
                                rows="1"
                              />
                            </VControl>
                          </VField>
                        </td>
                        <td style="width: 7.3937%; height: 54px;" colspan="5">
                          <VField>
                            <VControl>
                              <VTextarea
                                class="input"
                                placeholder="... "
                                v-model="input.pemeriksaanLuar9MataKiriKTirai"
                                rows="1"
                              />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr style="height: 18px;">
                        <td style="width: 21.3855%; height: 18px; border-bottom-style: hidden;">Teleng mata</td>
                        <td style="width: 7.11932%; height: 18px; border-bottom-style: hidden;" colspan="5">&nbsp;</td>
                        <td style="width: 7.3937%; height: 18px; border-bottom-style: hidden;" colspan="5">&nbsp;</td>
                      </tr>
                      <tr style="height: 18px;">
                        <td style="width: 21.3855%; height: 18px; border-bottom-style: hidden;">a. Bentuk :</td>
                        <td style="width: 7.11932%; height: 18px; border-bottom-style: hidden;" colspan="5">
                          <VField>
                              <VControl>
                                <VTextarea
                                  class="input"
                                  placeholder="... "
                                  v-model="input.pemeriksaanLuar9MataKananBentuk"
                                  rows="1"
                                />
                              </VControl>
                            </VField>
                        </td>
                        <td style="width: 7.3937%; height: 18px; border-bottom-style: hidden;" colspan="5">
                          <VField>
                              <VControl>
                                <VTextarea
                                  class="input"
                                  placeholder="... "
                                  v-model="input.pemeriksaanLuar9MataKiriBentuk"
                                  rows="1"
                                />
                              </VControl>
                            </VField>
                        </td>
                      </tr>
                      <tr>
                        <td style="width: 21.3855%;">b. Diameter :</td>
                        <td style="width: 7.11932%; height: 18px; border-bottom-style: hidden;" colspan="5">
                          <VField>
                              <VControl>
                                <VTextarea
                                  class="input"
                                  placeholder="... "
                                  v-model="input.pemeriksaanLuar9MataKananDiameter"
                                  rows="1"
                                />
                              </VControl>
                            </VField>
                        </td>
                        <td style="width: 7.3937%; height: 18px; border-bottom-style: hidden;" colspan="5">
                          <VField>
                              <VControl>
                                <VTextarea
                                  class="input"
                                  placeholder="... "
                                  v-model="input.pemeriksaanLuar9MataKiriDiameter"
                                  rows="1"
                                />
                              </VControl>
                            </VField>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="column is-12 p-0">
                <div class="is-flex">
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <span>10.	Hidung :</span>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar10Hidung" label="Simetris" true-value="Simetris"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar10Hidung" label="Asimetris :" true-value="Asimetris"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="column is-12 p-0">
                <div class="is-flex">
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Lubang kanan :</span>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar10HidungKanan" label="Tidak tampak kelainan" true-value="Tidak"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar10HidungKanan" label="Keluar" true-value="Keluar"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar10HidungKananKet"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="column is-12 p-0">
                <div class="is-flex">
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Lubang Kiri :</span>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar10HidungKiri" label="Tidak tampak kelainan" true-value="Tidak"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar10HidungKiri" label="Keluar" true-value="Keluar"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar10HidungKiriKet"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="column is-12 p-0">
                <div class="is-flex">
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Telinga :</span>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar10Telinga" label="Simetris" true-value="Simetris"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar10Telinga" label="Asimetris :" true-value="Asimetris"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="column is-12 p-0">
                <div class="is-flex">
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Lubang kanan :</span>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar10TelingaKanan" label="Tidak tampak kelainan" true-value="Tidak"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar10TelingaKanan" label="Keluar" true-value="Keluar"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar10TelingaKet"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="column is-12 p-0">
                <div class="is-flex">
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Lubang Kiri :</span>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar10TelingaKiri" label="Tidak tampak kelainan" true-value="Tidak"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar10TelingaKiri" label="Keluar" true-value="Keluar"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar10TelingaKiriKet"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="column is-12 p-0">
                <div class="is-flex">
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mulut :</span>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar10Mulut" label="Tertutup" true-value="Tertutup"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar10Mulut" label="Terbuka :" true-value="Terbuka"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar10MulutKet"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <span>Cm,    dari mulut</span>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar10MulutTampak" label="Tidak tampak kelainan" true-value="Tidak"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar10MulutTampak" label="Keluar" true-value="Keluar"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar10MulutTampakKet"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="column is-12 p-0">
                <div class="is-flex">
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bibir&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Warna</span>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar10BibirWarna"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <span>Tebal</span>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar10BibirTebal"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="column is-12 p-0">
                <div class="is-flex">
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lidah :</span>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar10Lidah" label="Menjulur" true-value="Menjulur"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar10Lidah" label="Tergigit" true-value="Tergigit"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar10LidahKet"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <span>cm dari permukaan gigi.</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="column is-12 p-0">
                <div class="is-flex">
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Gigi geligi : jumlah</span>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar10Gigi"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="column is-12 p-0">
                <div class="is-flex">
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tulang rahang :</span>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar10RahangKelainan" label="Tidak tampak kelainan" true-value="Tidak"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar10RahangKet"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="column is-12 p-0">
                <div class="is-flex" >
                  <div class="column is-5" style="margin-top: 0.5rem ; margin-left: 3.5rem " >
                    <p><u>Keterangan</u>: X = Tanggal / Copot</p>
                    <p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; O = Berlubang</p>
                    <p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; V = Tinggal akar</p>
                    <p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; # = Patah</p>
                    <p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Y = Belum / Tidak tumbuh</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="column is-12 p-0">
                <div class="is-flex" >
                  <div class="column is-5" style="margin-top: 0.5rem ; margin-left: 3.5rem " >
                    <p><u>&nbsp; 1.8&nbsp; 1.7&nbsp; 1.6&nbsp; 1.5&nbsp; 1.4&nbsp; 1.3&nbsp; 1.2&nbsp; 1.1&nbsp; :&nbsp; 2.1&nbsp; 2.2&nbsp; 2.3&nbsp; 2.4&nbsp; 2.5&nbsp; 2.6&nbsp; 2.7&nbsp; 2.8&nbsp; </u></p>
                    <p>&nbsp; 4.8&nbsp; 4.7&nbsp; 4.6&nbsp; 4.5&nbsp; 4.4&nbsp; 4.3&nbsp; 4.2&nbsp; 4.1&nbsp; :&nbsp; 3.1&nbsp; 3.2&nbsp; 3.3&nbsp; 3.4&nbsp; 3.5&nbsp; 3.6&nbsp; 3.7&nbsp; 3.8</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="column is-12 p-0">
                <div class="is-flex">
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <span>11.	Dari lubang kemaluan</span>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar11KemaluanKelainan" label="Tidak tampak kelainan" true-value="Tidak"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar11KemaluanKelainan" label="Keluar" true-value="Keluar"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar11KemaluanKelainanKet"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="column is-12 p-0">
                <div class="is-flex">
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dari lubang Pelepasan</span>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar11KemaluanPelepasanKelainan" label="Tidak tampak kelainan" true-value="Tidak"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VCheckbox class="p-0" v-model="input.pemeriksaanLuar11KemaluanPelepasanKelainan" label="Keluar" true-value="Keluar"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2" style="margin-top: 0.5rem">
                    <VField>
                      <VControl>
                        <VTextarea
                          class="input"
                          placeholder="... "
                          v-model="input.pemeriksaanLuar11KemaluanPelepasanKet"
                          rows="1"
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-12" style="margin-top: 0.5rem">
                  <span>12.	Luka-luka : 
                    ( Sebutkan secara sistematik: Regio, Koordinat, Jenis, Ukuran, Arah, Bentuk, Tepi, Resapan darah, Jembatan jaringan, Dalam, Dasar, Benda asing, sekitar dll )
                  </span>
                </div>
              </div>
            </div>
            <div class="column is-12">
              <VField>
                <VControl>
                  <VTextarea
                    class="input"
                    placeholder="... "
                    v-model="input.pemeriksaanLuar12"
                    rows="2"
                  />
                </VControl>
              </VField>
            </div>
            

          </div>
        </Fieldset>
      </div>
    </VCard>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { reactive, ref, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/asuhan-keperawatan-perioperatif'
import $ from "jquery";
import RadioButton from 'primevue/radiobutton';
import sleep from '/@src/utils/sleep'
import Calendar from 'primevue/calendar';




let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let kesadaran = ref(EMR.Kesadaran())
let statusEmosi = ref(EMR.statusEmosi())
let penilaianNyeri = ref(EMR.penilaianNyeri())
let bladder = ref(EMR.bladder())
let bowel = ref(EMR.bowel())
let bone = ref(EMR.bone())
let masalahPreOperatif = ref(EMR.masalahPreOperatif())
let masalahIntraOperatif = ref(EMR.masalahIntraOperatif())
let masalahPostOperatif = ref(EMR.masalahPostOperatif())
let rencanaTindakanPreOperatif = ref(EMR.rencanaTindakanPreOperatif())
let rencanaTindakanIntraOperatif = ref(EMR.rencanaTindakanIntraOperatif())
let rencanaTindakanPostOperatif = ref(EMR.rencanaTindakanPostOperatif())
let implementasiPreOperatif = ref(EMR.implementasiPreOperatif())
let implementasiIntraOperatif = ref(EMR.implementasiIntraOperatif())
let implementasiPostOperatif = ref(EMR.implementasiPostOperatif())
let evaluasiPreOperatif = ref(EMR.evaluasiPreOperatif())
let evaluasiIntraOperatif = ref(EMR.evaluasiIntraOperatif())
let evaluasiPostOperatif = ref(EMR.evaluasiPostOperatif())
// let perhitunganPerioperatif = ref(EMR.perhitunganPerioperatif())

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
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value >= 20 })
const isLoading: any = ref(false)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Perawat: any = ref([])
const d_Dokter: any = ref([])
const d_DiagnosaKeperawatan: any = ref([])
// const d_perhitunganPerioperatif = ref(EMR.perhitunganPerioperatif())
const isAktive = ref()
const jenisKelamin: any = ref('')
const MARKINGSITE: any = ref('')
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
// const COLLECTION: any = ref("AsuhanKeperawatanPerioperatif1") //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  diagnosaKeper: [{ no: 1 }],
  jamPreOperatif: new Date()
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
  jenisKelamin.value = props.pasien.jeniskelamin.toUpperCase()
  MARKINGSITE.value = '/images/simrs/' + (jenisKelamin.value == 'PEREMPUAN' ? 'cewek.png' : 'cowok.png')
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
        if (response[0].tandaTanganPerawat) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganPerawat)
        }
        if (response[0].tandaTanganPerawat) {
          H.tandaTangan().set("signature_2", response[0].tandaTanganPerawat)
        }
        if (response[0].tandaTanganPerawat) {
          H.tandaTangan().set("signature_3", response[0].tandaTanganPerawat)
        }
        if (response[0].tandaTanganPerawat) {
          H.tandaTangan().set("signature_4", response[0].tandaTanganPerawat)
        }
        if (response[0].tandaTanganPerawat) {
          H.tandaTangan().set("signature_5", response[0].tandaTanganPerawat)
        }
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  console.log(object)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': props.FORM_URL,
    'name_form': props.FORM_NAME,
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  isLoading.value = false
  useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
    }).catch((e: any) => {
      isLoading.value = false
      console.error('Error saving data:', e); // Log error untuk debugging
    })
}

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}


// const fetchDokter = async (filter: any) => {
//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
//   ).then((response) => {
//     d_Dokter.value = response
//   })
// }
const clearCanvas = (canvas: any) => {

  var sigCanvas: any = document.getElementById(canvas);
  var context = sigCanvas.getContext("2d");
  context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);

}
const skor = (e: any, i: any) => {

  let listSkor = listSkoringNyeri.value.detail

  listSkor.forEach((element: any) => {
    if (element.descNilai == e.descNilai) {
      input.value.skoringNyeri = e.descNilai
    }
  });
  isAktive.value = i

}
const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  await useApi().get(
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=VitalSign" + "&field=tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi,beratBadan,SPO2"
  ).then((response) => {
    if (response != null) {
      console.log(response)
      input.value.beratBadan = response.beratBadan
      input.value.tinggiBadan = response.tinggiBadan
    }
  })
  input.value.tglPembuatan = new Date()
}

const autoFillkeluhanUtama = () => {
  useApi().get("emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=pengkajianDokterIGD" + "&field=keluhanUtama"
  ).then((response) => {
    if (response != null) {
      input.value.keluhanUtama = response.keluhanUtama ? response.keluhanUtama : ''
    }
  })
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
const fetchPerawat = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Perawat.value = response
  })
}

const setTandaTangan = async (e: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signature_1", element.ttd)
    } else {
      H.tandaTangan().set("signature_1", '')
    }
  })
}


// const fetchDiagnosaKeperawatan = async (filter: any) => {
//   await useApi().get(`/emr/get-dropdown-diagnosa-keperawatan?search=${filter.query}`).then((response) => {
//     d_DiagnosaKeperawatan.value = response
//   })
// }
// const addNewDiagnosaKeper = () => {
//   input.value.diagnosaKeper.push({
//     no: input.value.diagnosaKeper[input.value.diagnosaKeper.length - 1].no + 1,
//   });
// }
// const removeItemDiagnosaKeper = (index: any) => {
//   input.value.diagnosaKeper.splice(index, 1)
// }
// watch(() => [
//   input.value.penurunanBB,
//   input.value.penurunanNafsuMakan,
//   jenisKelamin.value,
// ], () => {

//   let poin1 = input.value.penurunanBB ? parseInt(input.value.penurunanBB.poin) : 0
//   let poin2 = input.value.penurunanNafsuMakan ? parseInt(input.value.penurunanNafsuMakan.poin) : 0

//   const total = poin1 + poin2
//   input.value.totalNilaiPemeriksaanNutrisi = total

// })
// async function performAction() {
//   await sleep(1000);
//   markignSite();
// }
// performAction();
setView()
setAutoFill()
// autoFillkeluhanUtama()
loadRiwayat()
</script>

<style lang="scss" scoped>
.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 120%;
  background-color: var(--white);
  border-color: var(--fade-grey-dark-2) !important;
}

.table-tg {
    width: 100%;
    overflow: scroll;
  }

.tg-card {
  background-color: #feffed;
}

.is-dark {
  .tg {
    background-color: var(--dark-sidebar-light-6)
  }

  .tg-card {
    background-color: var(--dark-sidebar-light-6)
  }
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
  border-color: var(--fade-grey-dark-3) !important;
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: bold;
  text-transform: uppercase;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
  vertical-align: middle;
}

.tg .tg-0lax {
  text-align: left;
  vertical-align: top
}

.scroll-container-rev {
  height: 1000px;
  overflow: auto;
}

@media (max-width: 1144px) {

  .table-tg {
    width: 150%;
  }
}

.table-tg input {
    width: 80px; /* Memperkecil lebar input */
    height: 20px; /* Memperkecil tinggi input */
    font-size: 14px; /* Memperkecil ukuran teks dalam input */
    text-align: center; /* Menengahkan teks dalam input */
    padding: 2px;
    margin: 0 auto; /* Mengatur margin agar input berada di tengah */
    display: block; /* Membuat input menjadi block agar bisa rata tengah */
  }

</style>
