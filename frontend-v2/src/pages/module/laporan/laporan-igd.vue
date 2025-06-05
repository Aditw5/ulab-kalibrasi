
<template>
  <div class="column">
    <VCard>
      <TabView class="tabview-custom " :scrollable="true" @tab-click="klikTab($event)">
        <TabPanel>
          <div class="column is-12">
            <div class="search-widget">
              <div class="field">
                <div class="columns is-multiline">
                  <div class="column is-5 pt-0 pb-0">
                    <span>Periode</span>
                    <VDatePicker v-model="item.qFilterTgl" is-range color="pink" trim-weeks class="pt-2">
                      <template #default="{ inputValue, inputEvents }">
                        <VField addons>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.start" class="input-calendar" v-on="inputEvents.start" />
                          </VControl>
                          <VControl>
                            <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                          </VControl>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.end" class="input-calendar" v-on="inputEvents.end" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </div>
                  <div class="column is-3 pt-0 pb-0" style="margin-left: -11%">
                    <span>Ruangan IGD</span>
                    <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                      <VControl icon="feather:search">
                        <AutoComplete v-model="item.ruanganfkAsal" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 mt-5">
                    <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                      @click="fetchData()" :loading="isPlaceLoad">
                    </VIconButton>
                  </div>
                  <div style="margin-left: -21%" class="column is-4 mt-5 flex flex-wrap align-items-center justify-content-between gap-2">
                    <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcel()">
                      Export
                      to
                      Excel </VButton>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <template #header>
            <i class="fas fa-file mr-2" aria-hidden="true"></i>
            <span>IGD Asal Rujukan</span>
          </template>
          <div v-if="activeTab == 0">
            <div class="column" v-if="isPlaceLoad">
              <VPlaceloadWrap v-for="data in 25">
                <VPlaceload class="mx-2 mb-3" />
                <VPlaceload class="mx-2" />
              </VPlaceloadWrap>
            </div>
            <div class="column" v-else>
              <VPlaceholderPage v-if="dataSource.length == 0" title="Data Tidak di Temukan."
                subtitle="Silakan filter pencarian di tanggal lain" larger>
                <template #image>
                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
                </template>
              </VPlaceholderPage>

              <div v-else>
                <DataTable :value="dataSource" class="p-datatable-sm" :loading="isLoading" :paginator="true" :rows="10"
                  :rowsPerPageOptions="[5, 10, 25]" scrollable
                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                  responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                  <Column field="no" header="#" frozen></Column>
                  <Column field="asalrujukan" header="Asal Rujukan" frozen :sortable="true" style="min-width: 100px">
                  </Column>
                  <Column field="jumlah" header="Jumlah" :sortable="true" style="min-width: 200px"></Column>
                </DataTable>
              </div>
            </div>
          </div>
        </TabPanel>
        <TabPanel>
          <div class="column is-12">
            <div class="search-widget">
              <div class="field">
                <div class="columns is-multiline">
                  <div class="column is-5 pt-0 pb-0">
                    <span>Periode</span>
                    <VDatePicker v-model="item.qFilterTgl" is-range color="pink" trim-weeks class="pt-2">
                      <template #default="{ inputValue, inputEvents }">
                        <VField addons>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.start" class="input-calendar" v-on="inputEvents.start" />
                          </VControl>
                          <VControl>
                            <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                          </VControl>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.end" class="input-calendar" v-on="inputEvents.end" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </div>
                  <div class="column is-3 pt-0 pb-0" style="margin-left: -11%">
                    <span>Ruangan IGD</span>
                    <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                      <VControl icon="feather:search">
                        <AutoComplete v-model="item.ruanganfkCara" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 mt-5">
                    <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                      @click="fetchDataCaraBayar()" :loading="isPlaceLoad">
                    </VIconButton>
                  </div>
                  <div style="margin-left: -21%" class="column is-4 mt-5 flex flex-wrap align-items-center justify-content-between gap-2">
                    <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcelCaraBayar()">
                      Export
                      to
                      Excel </VButton>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <template #header>
            <i class="fas fa-file mr-2" aria-hidden="true"></i>
            <span>IGD Cara Bayar</span>
          </template>
          <div v-if="activeTab == 1">
            <div class="column" v-if="isPlaceLoad">
              <VPlaceloadWrap v-for="data in 25">
                <VPlaceload class="mx-2 mb-3" />
                <VPlaceload class="mx-2" />
              </VPlaceloadWrap>
            </div>
            <div class="column" v-else>
              <VPlaceholderPage v-if="dataSourceCaraBayar.length == 0" title="Data Tidak di Temukan."
                subtitle="Silakan filter pencarian di tanggal lain" larger>
                <template #image>
                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
                </template>
              </VPlaceholderPage>

              <div v-else>
                <DataTable :value="dataSourceCaraBayar" class="p-datatable-sm" :loading="isLoading" :paginator="true"
                  :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                  responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                  <Column field="no" header="#" frozen></Column>
                  <Column field="kelompokpasien" header="Kelompok Pasien" frozen :sortable="true"
                    style="min-width: 100px">
                  </Column>
                  <Column field="jumlah" header="Jumlah" :sortable="true" style="min-width: 200px"></Column>
                </DataTable>
              </div>
            </div>
          </div>
        </TabPanel>
        <TabPanel>
          <div class="column is-12">
            <div class="search-widget">
              <div class="field">
                <div class="columns is-multiline">
                  <div class="column is-5 pt-0 pb-0">
                    <span>Periode</span>
                    <VDatePicker v-model="item.qFilterTgl" is-range color="pink" trim-weeks class="pt-2">
                      <template #default="{ inputValue, inputEvents }">
                        <VField addons>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.start" class="input-calendar" v-on="inputEvents.start" />
                          </VControl>
                          <VControl>
                            <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                          </VControl>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.end" class="input-calendar" v-on="inputEvents.end" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </div>
                  <div class="column is-3 pt-0 pb-0" style="margin-left: -11%">
                    <span>Ruangan IGD</span>
                    <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                      <VControl icon="feather:search">
                        <AutoComplete v-model="item.ruanganfkPulang" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 mt-5">
                    <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                      @click="fetchDataCaraPulang()" :loading="isPlaceLoad">
                    </VIconButton>
                  </div>
                  <div style="margin-left: -21%" class="column is-4 mt-5 flex flex-wrap align-items-center justify-content-between gap-2">
                    <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcelCaraPulang()">
                      Export
                      to
                      Excel </VButton>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <template #header>
            <i class="fas fa-file mr-2" aria-hidden="true"></i>
            <span>IGD Cara Pulang</span>
          </template>
          <div v-if="activeTab == 2">
            <div class="column" v-if="isPlaceLoad">
              <VPlaceloadWrap v-for="data in 25">
                <VPlaceload class="mx-2 mb-3" />
                <VPlaceload class="mx-2" />
              </VPlaceloadWrap>
            </div>
            <div class="column" v-else>
              <VPlaceholderPage v-if="dataSourceCaraPulang.length == 0" title="Data Tidak di Temukan."
                subtitle="Silakan filter pencarian di tanggal lain" larger>
                <template #image>
                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
                </template>
              </VPlaceholderPage>

              <div v-else>
                <DataTable :value="dataSourceCaraPulang" class="p-datatable-sm" :loading="isLoading" :paginator="true"
                  :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                  responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                  <Column field="no" header="#" frozen></Column>
                  <Column field="statuspulang" header="Status Pulang" frozen :sortable="true"
                    style="min-width: 100px">
                  </Column>
                  <Column field="jumlah" header="Jumlah" :sortable="true" style="min-width: 200px"></Column>
                </DataTable>
              </div>
            </div>
          </div>
        </TabPanel>
        <TabPanel>
          <div class="column is-12">
            <div class="search-widget">
              <div class="field">
                <div class="columns is-multiline">
                  <div class="column is-5 pt-0 pb-0">
                    <span>Periode</span>
                    <VDatePicker v-model="item.qFilterTgl" is-range color="pink" trim-weeks class="pt-2">
                      <template #default="{ inputValue, inputEvents }">
                        <VField addons>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.start" class="input-calendar" v-on="inputEvents.start" />
                          </VControl>
                          <VControl>
                            <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                          </VControl>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.end" class="input-calendar" v-on="inputEvents.end" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </div>
                  <div class="column is-3 pt-0 pb-0" style="margin-left: -11%">
                    <span>Ruangan IGD</span>
                    <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                      <VControl icon="feather:search">
                        <AutoComplete v-model="item.ruanganfkDiagnosa" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 mt-5">
                    <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                      @click="fetchDataDiagnosa()" :loading="isPlaceLoad">
                    </VIconButton>
                  </div>
                  <div style="margin-left: -21%" class="column is-4 mt-5 flex flex-wrap align-items-center justify-content-between gap-2">
                    <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcelDiagnosa()">
                      Export
                      to
                      Excel </VButton>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <template #header>
            <i class="fas fa-file mr-2" aria-hidden="true"></i>
            <span>IGD Diagnosa</span>
          </template>
          <div v-if="activeTab == 3">
            <div class="column" v-if="isPlaceLoad">
              <VPlaceloadWrap v-for="data in 25">
                <VPlaceload class="mx-2 mb-3" />
                <VPlaceload class="mx-2" />
              </VPlaceloadWrap>
            </div>
            <div class="column" v-else>
              <VPlaceholderPage v-if="dataSourceDiagnosa.length == 0" title="Data Tidak di Temukan."
                subtitle="Silakan filter pencarian di tanggal lain" larger>
                <template #image>
                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
                </template>
              </VPlaceholderPage>

              <div v-else>
                <DataTable :value="dataSourceDiagnosa" class="p-datatable-sm" :loading="isLoading" :paginator="true"
                  :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                  responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                  <Column field="no" header="#" frozen></Column>
                  <Column field="kddiagnosa" header="KD Diagnosa" frozen :sortable="true"
                    style="min-width: 100px">
                  </Column>
                  <Column field="namadiagnosa" header="Nama Diagnosa" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="jumlah" header="Jumlah" :sortable="true" style="min-width: 200px"></Column>
                </DataTable>
              </div>
            </div>
          </div>
        </TabPanel>
        <TabPanel>
          <div class="column is-12">
            <div class="search-widget">
              <div class="field">
                <div class="columns is-multiline">
                  <div class="column is-5 pt-0 pb-0">
                    <span>Periode</span>
                    <VDatePicker v-model="item.qFilterTgl" is-range color="pink" trim-weeks class="pt-2">
                      <template #default="{ inputValue, inputEvents }">
                        <VField addons>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.start" class="input-calendar" v-on="inputEvents.start" />
                          </VControl>
                          <VControl>
                            <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                          </VControl>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.end" class="input-calendar" v-on="inputEvents.end" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </div>
                  <div class="column is-3 pt-0 pb-0" style="margin-left: -11%">
                    <span>Ruangan IGD</span>
                    <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                      <VControl icon="feather:search">
                        <AutoComplete v-model="item.ruanganfkDPJP" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 mt-5">
                    <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                      @click="fetchDataDpjp()" :loading="isPlaceLoad">
                    </VIconButton>
                  </div>
                  <div style="margin-left: -21%" class="column is-4 mt-5 flex flex-wrap align-items-center justify-content-between gap-2">
                    <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcelDpjp()">
                      Export
                      to
                      Excel </VButton>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <template #header>
            <i class="fas fa-file mr-2" aria-hidden="true"></i>
            <span>IGD DPJP</span>
          </template>
          <div v-if="activeTab == 4">
            <div class="column" v-if="isPlaceLoad">
              <VPlaceloadWrap v-for="data in 25">
                <VPlaceload class="mx-2 mb-3" />
                <VPlaceload class="mx-2" />
              </VPlaceloadWrap>
            </div>
            <div class="column" v-else>
              <VPlaceholderPage v-if="dataSourceDpjp.length == 0" title="Data Tidak di Temukan."
                subtitle="Silakan filter pencarian di tanggal lain" larger>
                <template #image>
                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
                </template>
              </VPlaceholderPage>

              <div v-else>
                <DataTable :value="dataSourceDpjp" class="p-datatable-sm" :loading="isLoading" :paginator="true"
                  :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                  responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                  <Column field="no" header="#" frozen></Column>
                  <Column field="tglregistrasi" header="Tanggal Registrasi" frozen :sortable="true"
                    style="min-width: 100px">
                  </Column>
                  <Column field="namapasien" header="Nama Pasien" frozen :sortable="true" style="min-width: 200px">
                    <template #body="slotProps">
                      <span>{{ maskNamaPasien(slotProps.data.namapasien) }}</span>
                    </template>
                  </Column>
                  <Column field="dokter" header="Dokter" :sortable="true" style="min-width: 200px"></Column>
                </DataTable>
              </div>
            </div>
          </div>
        </TabPanel>
        <TabPanel>
          <div class="column is-12">
            <div class="search-widget">
              <div class="field">
                <div class="columns is-multiline">
                  <div class="column is-5 pt-0 pb-0">
                    <span>Periode</span>
                    <VDatePicker v-model="item.qFilterTgl" is-range color="pink" trim-weeks class="pt-2">
                      <template #default="{ inputValue, inputEvents }">
                        <VField addons>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.start" class="input-calendar" v-on="inputEvents.start" />
                          </VControl>
                          <VControl>
                            <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                          </VControl>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.end" class="input-calendar" v-on="inputEvents.end" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </div>
                  <div class="column is-3 pt-0 pb-0" style="margin-left: -11%">
                    <span>Ruangan IGD</span>
                    <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                      <VControl icon="feather:search">
                        <AutoComplete v-model="item.ruanganfkJumlahDPJP" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 mt-5">
                    <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                      @click="fetchDataJumlahDpjp()" :loading="isPlaceLoad">
                    </VIconButton>
                  </div>
                  <div style="margin-left: -21%" class="column is-4 mt-5 flex flex-wrap align-items-center justify-content-between gap-2">
                    <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcelJumlahDpjp()">
                      Export
                      to
                      Excel </VButton>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <template #header>
            <i class="fas fa-file mr-2" aria-hidden="true"></i>
            <span>IGD JUMLAH DPJP</span>
          </template>
          <div v-if="activeTab == 5">
            <div class="column" v-if="isPlaceLoad">
              <VPlaceloadWrap v-for="data in 25">
                <VPlaceload class="mx-2 mb-3" />
                <VPlaceload class="mx-2" />
              </VPlaceloadWrap>
            </div>
            <div class="column" v-else>
              <VPlaceholderPage v-if="dataSourceJumlahDpjp.length == 0" title="Data Tidak di Temukan."
                subtitle="Silakan filter pencarian di tanggal lain" larger>
                <template #image>
                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
                </template>
              </VPlaceholderPage>

              <div v-else>
                <DataTable :value="dataSourceJumlahDpjp" class="p-datatable-sm" :loading="isLoading" :paginator="true"
                  :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                  responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                  <Column field="no" header="#" frozen></Column>
                  <Column field="dokter" header="Dokter" frozen :sortable="true"
                    style="min-width: 100px">
                  </Column>
                  <Column field="jumlah" header="Jumlah" :sortable="true" style="min-width: 200px"></Column>
                </DataTable>
              </div>
            </div>
          </div>
        </TabPanel>
        <TabPanel>
          <div class="column is-12">
            <div class="search-widget">
              <div class="field">
                <div class="columns is-multiline">
                  <div class="column is-5 pt-0 pb-0">
                    <span>Periode</span>
                    <VDatePicker v-model="item.qFilterTgl" is-range color="pink" trim-weeks class="pt-2">
                      <template #default="{ inputValue, inputEvents }">
                        <VField addons>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.start" class="input-calendar" v-on="inputEvents.start" />
                          </VControl>
                          <VControl>
                            <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                          </VControl>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.end" class="input-calendar" v-on="inputEvents.end" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </div>
                  <div class="column is-3 pt-0 pb-0" style="margin-left: -11%">
                    <span>Ruangan IGD</span>
                    <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                      <VControl icon="feather:search">
                        <AutoComplete v-model="item.ruanganfk" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 mt-5">
                    <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                      @click="fetchDataJenisKelamin()" :loading="isPlaceLoad">
                    </VIconButton>
                  </div>
                  <div style="margin-left: -21%" class="column is-4 mt-5 flex flex-wrap align-items-center justify-content-between gap-2">
                    <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcelJenisKelamin()">
                      Export
                      to
                      Excel </VButton>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <template #header>
            <i class="fas fa-file mr-2" aria-hidden="true"></i>
            <span>IGD JENIS KELAMIN</span>
          </template>
          <div v-if="activeTab == 6">
            <div class="column" v-if="isPlaceLoad">
              <VPlaceloadWrap v-for="data in 25">
                <VPlaceload class="mx-2 mb-3" />
                <VPlaceload class="mx-2" />
              </VPlaceloadWrap>
            </div>
            <div class="column" v-else>
              <VPlaceholderPage v-if="dataSourceJenisKelamin.length == 0" title="Data Tidak di Temukan."
                subtitle="Silakan filter pencarian di tanggal lain" larger>
                <template #image>
                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
                </template>
              </VPlaceholderPage>

              <div v-else>
                <DataTable :value="dataSourceJenisKelamin" class="p-datatable-sm" :loading="isLoading" :paginator="true"
                  :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                  responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                  <Column field="no" header="#" frozen></Column>
                  <Column field="jeniskelamin" header="Jenis Kelamin" frozen :sortable="true"
                    style="min-width: 100px">
                  </Column>
                  <Column field="jumlah" header="Jumlah" :sortable="true" style="min-width: 200px"></Column>
                </DataTable>
              </div>
            </div>
          </div>
        </TabPanel>
        <TabPanel>
          <div class="column is-12">
            <div class="search-widget">
              <div class="field">
                <div class="columns is-multiline">
                  <div class="column is-5 pt-0 pb-0">
                    <span>Periode</span>
                    <VDatePicker v-model="item.qFilterTgl" is-range color="pink" trim-weeks class="pt-2">
                      <template #default="{ inputValue, inputEvents }">
                        <VField addons>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.start" class="input-calendar" v-on="inputEvents.start" />
                          </VControl>
                          <VControl>
                            <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                          </VControl>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.end" class="input-calendar" v-on="inputEvents.end" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </div>
                  <div class="column is-3 pt-0 pb-0" style="margin-left: -11%">
                    <span>Ruangan IGD</span>
                    <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                      <VControl icon="feather:search">
                        <AutoComplete v-model="item.ruanganfkMutasi" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 mt-5">
                    <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                      @click="fetchDataDomisili()" :loading="isPlaceLoad">
                    </VIconButton>
                  </div>
                  <div style="margin-left: -21%" class="column is-4 mt-5 flex flex-wrap align-items-center justify-content-between gap-2">
                    <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcelDomisili()">
                      Export
                      to
                      Excel </VButton>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <template #header>
            <i class="fas fa-file mr-2" aria-hidden="true"></i>
            <span>IGD DOMISILI</span>
          </template>
          <div v-if="activeTab == 7">
            <div class="column" v-if="isPlaceLoad">
              <VPlaceloadWrap v-for="data in 25">
                <VPlaceload class="mx-2 mb-3" />
                <VPlaceload class="mx-2" />
              </VPlaceloadWrap>
            </div>
            <div class="column" v-else>
              <VPlaceholderPage v-if="dataSourceDomisili.length == 0" title="Data Tidak di Temukan."
                subtitle="Silakan filter pencarian di tanggal lain" larger>
                <template #image>
                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
                </template>
              </VPlaceholderPage>

              <div v-else>
                <DataTable :value="dataSourceDomisili" class="p-datatable-sm" :loading="isLoading" :paginator="true"
                  :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                  responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                  <Column field="no" header="#" frozen></Column>
                  <Column field="namakotakabupaten" header="Kota/Kabupaten" frozen :sortable="true"
                    style="min-width: 100px">
                  </Column>
                  <Column field="jumlah" header="Jumlah" :sortable="true" style="min-width: 200px"></Column>
                </DataTable>
              </div>
            </div>
          </div>
        </TabPanel>
        <TabPanel>
          <div class="column is-12">
            <div class="search-widget">
              <div class="field">
                <div class="columns is-multiline">
                  <div class="column is-5 pt-0 pb-0">
                    <span>Periode</span>
                    <VDatePicker v-model="item.qFilterTgl" is-range color="pink" trim-weeks class="pt-2">
                      <template #default="{ inputValue, inputEvents }">
                        <VField addons>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.start" class="input-calendar" v-on="inputEvents.start" />
                          </VControl>
                          <VControl>
                            <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                          </VControl>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.end" class="input-calendar" v-on="inputEvents.end" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </div>
                  <div class="column is-2 pt-0 pb-0" style="margin-left: -8%">
                    <span>Ruangan IGD</span>
                    <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                      <VControl icon="feather:search">
                        <AutoComplete v-model="item.ruanganfkIGD" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ruangan Rawat..."/>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2 pt-0 pb-0">
                    <span>Ruangan Mutasi</span>
                    <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                      <VControl icon="feather:search">
                        <AutoComplete v-model="item.ruanganfk" :suggestions="d_RuanganMutasi" @complete="fetchRuanganMutasi($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ruangan Rawat..."/>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 mt-5">
                    <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                      @click="fetchDataMutasi()" :loading="isPlaceLoad">
                    </VIconButton>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div  class="column is-4 mt-5 flex flex-wrap align-items-center justify-content-between gap-2">
                      <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcelMutasi()">
                        Export
                        to
                        Excel </VButton>
                    </div>
                </div>
              </div>
            </div>
          </div>
          <template #header>
            <i class="fas fa-file mr-2" aria-hidden="true"></i>
            <span>IGD MUTASI</span>
          </template>
          <div v-if="activeTab == 8">
            <div class="column" v-if="isPlaceLoad">
              <VPlaceloadWrap v-for="data in 25">
                <VPlaceload class="mx-2 mb-3" />
                <VPlaceload class="mx-2" />
              </VPlaceloadWrap>
            </div>
            <div class="column" v-else>
              <VPlaceholderPage v-if="dataSourceMutasi.length == 0" title="Data Tidak di Temukan."
                subtitle="Silakan filter pencarian di tanggal lain" larger>
                <template #image>
                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
                </template>
              </VPlaceholderPage>

              <div v-else>
                <DataTable :value="dataSourceMutasi" class="p-datatable-sm" :loading="isLoading" :paginator="true"
                  :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                  responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                  <Column field="no" header="#" frozen></Column>
                  <Column field="tglmasuk" header="Tanggal Mutasi" frozen :sortable="true"
                    style="min-width: 200px">
                  </Column>
                  <Column field="tglregistrasi" header="Tanggal Registrasi" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="namapasien" header="Nama Pasien" frozen :sortable="true" style="min-width: 200px">
                    <template #body="slotProps">
                      <span>{{ maskNamaPasien(slotProps.data.namapasien) }}</span>
                    </template>
                  </Column>
                  <Column field="nocm" header="NO CM" :sortable="true" style="min-width: 100px"></Column>
                  <Column field="noregistrasi" header="Noregistrasi" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="Ruangan_asal_IGD" header="Ruangan Asal IGD" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="Ruangan_Mutasi" header="Ruangan Mutasi" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="reportdisplay" header="Kamar" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="Dokter" header="Dokter" :sortable="true" style="min-width: 300px"></Column>
                  <Column field="namadesakelurahan" header="Desa/Kelurahan" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="namakecamatan" header="Kecamatan" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="namakotakabupaten" header="Kabupaten" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="nobpjs" header="No BPJS" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="alamatrmh" header="Alamat Rumah" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="NIK" header="NIK" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="namakelas" header="Kelas" :sortable="true" style="min-width: 200px"></Column>
                </DataTable>
              </div>
            </div>
          </div>
        </TabPanel>
        <TabPanel>
          <div class="column is-12">
            <div class="search-widget">
              <div class="field">
                <div class="columns is-multiline">
                  <div class="column is-5 pt-0 pb-0">
                    <span>Periode</span>
                    <VDatePicker v-model="item.qFilterTgl" is-range color="pink" trim-weeks class="pt-2">
                      <template #default="{ inputValue, inputEvents }">
                        <VField addons>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.start" class="input-calendar" v-on="inputEvents.start" />
                          </VControl>
                          <VControl>
                            <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                          </VControl>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.end" class="input-calendar" v-on="inputEvents.end" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </div>
                  <div class="column is-3 mt-5" style="margin-left: -11%">
                    <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                      @click="fetchDataDiagnosaPerpasien()" :loading="isPlaceLoad">
                    </VIconButton>
                  </div>
                  <div style="margin-left: -21%" class="column is-4 mt-5 flex flex-wrap align-items-center justify-content-between gap-2">
                    <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcelDiagnosaPerpasien()">
                      Export
                      to
                      Excel </VButton>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <template #header>
            <i class="fas fa-file mr-2" aria-hidden="true"></i>
            <span>IGD DIAGNOSA PERPASIEN</span>
          </template>
          <div v-if="activeTab == 9">
            <div class="column" v-if="isPlaceLoad">
              <VPlaceloadWrap v-for="data in 25">
                <VPlaceload class="mx-2 mb-3" />
                <VPlaceload class="mx-2" />
              </VPlaceloadWrap>
            </div>
            <div class="column" v-else>
              <VPlaceholderPage v-if="dataSourceDiagnosaPerpasien.length == 0" title="Data Tidak di Temukan."
                subtitle="Silakan filter pencarian di tanggal lain" larger>
                <template #image>
                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
                </template>
              </VPlaceholderPage>

              <div v-else>
                <DataTable :value="dataSourceDiagnosaPerpasien" class="p-datatable-sm" :loading="isLoading" :paginator="true"
                  :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                  responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                  <Column field="no" header="#" frozen></Column>
                  <Column field="noregistrasi" header="Noregistrasi" frozen :sortable="true"
                    style="min-width: 200px">
                  </Column>
                  <Column field="nocm" header="NO CM" :sortable="true" style="min-width: 100px"></Column>
                  <Column field="namapasien" header="Nama Pasien" frozen :sortable="true" style="min-width: 200px">
                    <template #body="slotProps">
                      <span>{{ maskNamaPasien(slotProps.data.namapasien) }}</span>
                    </template>
                  </Column>
                  <Column field="jeniskelamin" header="Jenis Kelamin" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="tgllahir" header="Tanggal Lahir" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="tglmasuk" header="Tanggal Masuk" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="statuspulang" header="Status Pulang" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="kondisipasien" header="Kondisi Pasien" :sortable="true" style="min-width: 300px"></Column>
                  <Column field="tglpulang" header="Tanggal Pulang" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="jenis_pasien" header="Jenis Pasien" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="namaruangan" header="Nama Ruangan" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="dokter" header="Dokter" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="kddiagnosatindakan" header="KD Diagnosa Tindakan" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="namadiagnosatindakan" header="Nama Diagnosa Tindakan" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="alamatlengkap" header="Alamat Lengkap" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="namakotakabupaten" header="Kota/Kabupaten" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="namakecamatan" header="Kecamatan" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="namadesakelurahan" header="Desa/Kelurahan" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="rtrw" header="RT/RW" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="namapropinsi" header="Provinsi" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="statuspasien" header="Status Pasien" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="id_dokter" header="ID Dokter" :sortable="true" style="min-width: 200px"></Column>
                </DataTable>
              </div>
            </div>
          </div>
        </TabPanel>
        <TabPanel>
          <div class="column is-12">
            <div class="search-widget">
              <div class="field">
                <div class="columns is-multiline">
                  <div class="column is-4 pt-0 pb-0">
                    <span>Periode</span>
                    <VDatePicker v-model="item.qFilterTgl" is-range color="pink" trim-weeks class="pt-2">
                      <template #default="{ inputValue, inputEvents }">
                        <VField addons>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.start" class="input-calendar" v-on="inputEvents.start" />
                          </VControl>
                          <VControl>
                            <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                          </VControl>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.end" class="input-calendar" v-on="inputEvents.end" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </div>
                  <div class="column pt-0 pb-0 is-4">
                    <span>Dokter</span>
                    <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                      <VControl icon="feather:search">
                        <AutoComplete v-model="item.dokterfk" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Dokter..."/>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0 pb-0">
                    <span>Ruangan</span>
                    <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                      <VControl icon="feather:search">
                        <AutoComplete v-model="item.ruanganfk" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ruangan Rawat..."/>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column pt-0 pb-0 is-4">
                    <span>Kelompok Pasien</span>
                    <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                      <VControl icon="feather:search">
                        <AutoComplete v-model="item.kelompokpasien" :suggestions="d_KelompokPasien" @complete="fetchKelompokPasien($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Kelompok Pasien..."/>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column pt-0 pb-0 is-4">
                    <span>Jenis Pasien</span>
                    <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                      <VControl icon="feather:search">
                        <AutoComplete v-model="item.jenispasien" :suggestions="d_JenisPasien" @complete="fetchJenisPasien($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Jenis Pasien..."/>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 mt-5">
                    <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                      @click="fetchDataIndexing()" :loading="isPlaceLoad">
                    </VIconButton>
                  </div>
                  <!-- <div style="margin-left: -21%" class="column is-4 mt-5 flex flex-wrap align-items-center justify-content-between gap-2">
                    <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcelDiagnosaPerpasien()">
                      Export
                      to
                      Excel </VButton>
                  </div> -->
                </div>
              </div>
            </div>
          </div>
          <template #header>
            <i class="fas fa-file mr-2" aria-hidden="true"></i>
            <span>INDEXING DIAGNOSA IGD</span>
          </template>
          <div v-if="activeTab == 10">
            <div class="column" v-if="isPlaceLoad">
              <VPlaceloadWrap v-for="data in 25">
                <VPlaceload class="mx-2 mb-3" />
                <VPlaceload class="mx-2" />
              </VPlaceloadWrap>
            </div>
            <div class="column" v-else>
              <VPlaceholderPage v-if="dataSourceIndexing.length == 0" title="Data Tidak di Temukan."
                subtitle="Silakan filter pencarian di tanggal lain" larger>
                <template #image>
                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
                </template>
              </VPlaceholderPage>

              <div v-else>
                <DataTable :value="dataSourceIndexing" class="p-datatable-sm" :loading="isLoading" :paginator="true" :rows="20"
                  :rowsPerPageOptions="[20, 40, 60]" scrollable
                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                  responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                  <template #header>
                    <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                      <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcelIndexing()"> Export
                        to
                        Excel </VButton>
                    </div>
                  </template>

                  <Column field="no" header="#" frozen></Column>
                  <Column field="namapasien" header="Nama Pasien" frozen :sortable="true" style="min-width: 200px">
                    <template #body="slotProps">
                      <span>{{ maskNamaPasien(slotProps.data.namapasien) }}</span>
                    </template>
                  </Column>
                  <Column field="nocm" header="No RM" :sortable="true" style="min-width: 100px"></Column>
                  <Column field="noregistrasi" header="No Registrasi" :sortable="true" style="min-width: 100px"></Column>
                  <Column field="jeniskelamin" header="Jenis Kelamin" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="tgllahir" header="Tanggal Lahir" :sortable="true" style="min-width: 200px">
                    <template #body="slotProps">
                      <span>{{ H.formatDateToLocalString(slotProps.data.tgllahir) }}</span>
                    </template>
                  </Column>
                  <Column field="tglregistrasi" header="Tanggal Masuk" :sortable="true" style="min-width: 200px">
                    <template #body="slotProps">
                      <span>{{ H.formatDateToLocalString(slotProps.data.tglmasuk) }}</span>
                    </template>
                  </Column>
                  <Column field="statuspulang" header="Status Pulang" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="tglpulang" header="Tanggal Pulang" :sortable="true" style="min-width: 200px">
                    <template #body="slotProps">
                      <span>{{ H.formatDateToLocalString(slotProps.data.tglpulang) }}</span>
                    </template>
                  </Column>
                  <Column field="kondisipulang" header="Kondisi Pulang" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="jenis_pasien" header="Jenis Pasien" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="dokter" header="Dokter" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="namaruanganasal" header="Ruangan Asal" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="namaruanganrawat" header="Ruangan Rawat" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="namakelas" header="Kelas Rawat" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="asalrujukan" header="Asal Rujukan" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="jenisdiagnosa" header="Jenis Diagnosa" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="kodediagnosa" header="Kode Diagnosa" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="namadiagnosa" header="Nama Diagnosa" :sortable="true" style="min-width: 200px"></Column>
                  <!-- <Column field="ketdiagnosis" header="Diagnosa Dokter" :sortable="true" style="min-width: 200px"></Column> -->
                  <Column field="namakotakabupaten" header="Asal Kota/Kabupaten" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="namakecamatan" header="Asal Kecamatan" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="namadesakelurahan" header="Asal Desa/Kelurahan" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="namapropinsi" header="Asal Provinsi" :sortable="true" style="min-width: 200px"></Column>

                  <Column field="statuspasien" header="Status" :sortable="true" style="min-width: 100px">
                    <template #body="slotProps">
                      <VTag class="ml-4" color="primary" rounded>{{ slotProps.data.statuspasien }}</VTag>
                      <!-- <span>{{ H.formatDateToLocalString(slotProps.data.tglregistrasi) }}</span> -->
                    </template>
                  </Column>
                </DataTable>
              </div>
            </div>
          </div>
        </TabPanel>
        <TabPanel>
          <div class="column is-12">
            <div class="search-widget">
              <div class="field">
                <div class="columns is-multiline">
                  <div class="column is-3 pt-0 pb-0">
                    <span>Periode</span>
                    <VDatePicker v-model="item.qFilterTgl" is-range color="pink" trim-weeks class="pt-2">
                      <template #default="{ inputValue, inputEvents }">
                        <VField addons>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.start" class="input-calendar" v-on="inputEvents.start" />
                          </VControl>
                          <VControl>
                            <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                          </VControl>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.end" class="input-calendar" v-on="inputEvents.end" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </div>
                  <!-- <div class="column pt-0 pb-0 is-3">
                    <span>Dokter</span>
                    <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                      <VControl icon="feather:search">
                        <AutoComplete v-model="item.dokterfk" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Dokter..."/>
                      </VControl>
                    </VField>
                  </div> -->
                  <div class="column is-3 pt-0 pb-0">
                    <span>Ruangan</span>
                    <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                      <VControl icon="feather:search">
                        <AutoComplete v-model="item.ruanganfk" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ruangan Rawat..."/>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1 mt-5">
                    <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                      @click="fetchDataPenandaPasien()" :loading="isPlaceLoad">
                    </VIconButton>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <template #header>
            <i class="fas fa-file mr-2" aria-hidden="true"></i>
            <span>PENANDA PASIEN</span>
          </template>
          <div v-if="activeTab == 11">
            <div class="column" v-if="isPlaceLoad">
              <VPlaceloadWrap v-for="data in 25">
                <VPlaceload class="mx-2 mb-3" />
                <VPlaceload class="mx-2" />
              </VPlaceloadWrap>
            </div>
            <div class="column" v-else>
              <VPlaceholderPage v-if="dataSourcePenandaPasien.length == 0" title="Data Tidak di Temukan."
                subtitle="Silakan filter pencarian di tanggal lain" larger>
                <template #image>
                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
                </template>
              </VPlaceholderPage>

              <div v-else>
                <DataTable :value="dataSourcePenandaPasien" class="p-datatable-sm" :loading="isLoading" :paginator="true" :rows="20"
                  :rowsPerPageOptions="[20, 40, 60]" scrollable
                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                  responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                  <template #header>
                    <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                      <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcelPenandaPasien()"> Export
                        to
                        Excel </VButton>
                    </div>
                  </template>

                  <Column field="no" header="#" frozen></Column>
                  <Column field="namapasien" header="Nama Pasien" frozen :sortable="true" style="min-width: 200px">
                    <template #body="slotProps">
                      <span>{{ maskNamaPasien(slotProps.data.namapasien) }}</span>
                    </template>
                  </Column>
                  <Column field="nocm" header="No RM" :sortable="true" style="min-width: 100px"></Column>
                  <Column field="noregistrasi" header="No Registrasi" :sortable="true" style="min-width: 100px"></Column>
                  <Column field="jeniskelamin" header="Jenis Kelamin" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="tgllahir" header="Tanggal Lahir" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="umur" header="Usia Pasien" :sortable="true" style="min-width: 200px"></Column>
                  <!-- <Column field="tgllahir" header="Tanggal Lahir" :sortable="true" style="min-width: 200px">
                    <template #body="slotProps">
                      <span>{{ H.formatDateToLocalString(slotProps.data.tgllahir) }}</span>
                    </template>
                  </Column>
                  <Column field="tglregistrasi" header="Tanggal Masuk" :sortable="true" style="min-width: 200px">
                    <template #body="slotProps">
                      <span>{{ H.formatDateToLocalString(slotProps.data.tglmasuk) }}</span>
                    </template>
                  </Column>
                  <Column field="statuspulang" header="Status Pulang" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="tglpulang" header="Tanggal Pulang" :sortable="true" style="min-width: 200px">
                    <template #body="slotProps">
                      <span>{{ H.formatDateToLocalString(slotProps.data.tglpulang) }}</span>
                    </template>
                  </Column>
                  <Column field="kondisipulang" header="Kondisi Pulang" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="jenis_pasien" header="Jenis Pasien" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="dokter" header="Dokter" :sortable="true" style="min-width: 200px"></Column> -->
                  <Column field="namaruanganasal" header="Ruangan Asal" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="namaruanganrawat" header="Ruangan Terakhir" :sortable="true" style="min-width: 200px"></Column>
                  <!-- <Column field="namakelas" header="Kelas Rawat" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="asalrujukan" header="Asal Rujukan" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="jenisdiagnosa" header="Jenis Diagnosa" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="kodediagnosa" header="Kode Diagnosa" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="namadiagnosa" header="Nama Diagnosa" :sortable="true" style="min-width: 200px"></Column> -->
                  <!-- <Column field="ketdiagnosis" header="Diagnosa Dokter" :sortable="true" style="min-width: 200px"></Column> -->
                  <!-- <Column field="namakotakabupaten" header="Asal Kota/Kabupaten" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="namakecamatan" header="Asal Kecamatan" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="namadesakelurahan" header="Asal Desa/Kelurahan" :sortable="true" style="min-width: 200px"></Column>
                  <Column field="namapropinsi" header="Asal Provinsi" :sortable="true" style="min-width: 200px"></Column> -->

                  <Column field="penanda" header="Penanda" :sortable="true" style="min-width: 100px">
                    <template #body="slotProps">
                      <VTag class="ml-4" color="warning" rounded v-if="slotProps.data.penanda">{{ slotProps.data.penanda }}</VTag>
                      <VTag class="ml-4" rounded v-if="!slotProps.data.penanda">Tidak Ada Penanda</VTag>
                    </template>
                  </Column>
                  <Column field="kategoriUsia" header="Kategori Usia" :sortable="true" style="min-width: 100px">
                    <template #body="slotProps">
                      <VTag class="ml-4" color="info" rounded v-if="slotProps.data.kategoriUsia">{{ slotProps.data.kategoriUsia }}</VTag>
                    </template>
                  </Column>
                </DataTable>
              </div>
            </div>
          </div>
        </TabPanel>
      </TabView>
    </VCard>
  </div>
</template>

<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive, onMounted } from 'vue'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete';
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { formatRp } from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import moment from 'moment'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Calendar from 'primevue/calendar';
import * as XLSX from "xlsx";
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
useHead({
  title: 'Laporan IGD - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const modalFilter: any = ref(false)
const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()
const total = ref(0)
const activeTab = ref(0);
const router = useRouter()
const modalInput = ref(false)
const periode = ref('');
const jumlahRuangan = ref('');
const item: any = ref({
  qFilterTgl: {
    start: new Date(),
    end: new Date()
  },
})

const klikTab = (e: any) => {
  activeTab.value = e.index
  if (activeTab.value == 0) {
    fetchData()
  }
}

const fetchDepartemen = async (filter: any) => {
  d_departemen.value = [{ value: '9', label: 'IGD' }, { value: '16', label: 'Rawat Inap' }, { value: '18', label: 'Rawat Jalan' },]
}

const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
const remakeData: any = ref([])
let dataSource: any = ref([])
let dataSourceIndexing: any = ref([])
let dataSourcePenandaPasien: any = ref([])
let dataSourceCaraBayar: any = ref([])
let dataSourceCaraPulang: any = ref([])
let dataSourceDiagnosa: any = ref([])
let dataSourceDpjp: any = ref([])
let dataSourceJumlahDpjp: any = ref([])
let dataSourceJenisKelamin: any = ref([])
let dataSourceDomisili: any = ref([])
let dataSourceMutasi: any = ref([])
let dataSourceDiagnosaPerpasien: any = ref([])
let d_departemen: any = ref([])
let d_Ruangan: any = ref([])
let d_RuanganMutasi: any = ref([])
let isLoading: any = ref(false)
let isPlaceLoad: any = ref(false)
let d_KelompokPasien: any = ref([])
let d_JenisPasien: any = ref([])
let d_Dokter: any = ref([])
const filters = ref('')

const fetchKelompokPasien = async (filter: any) => {
    await useApi().get(`emr/dropdown/kelompokpasien_m?select=id,kelompokpasien&param_search=kelompokpasien&query=${filter.query}&limit=10`
    ).then((response) => {
        d_KelompokPasien.value = response
    })
}

const fetchJenisPasien = async (filter: any) => {
    d_JenisPasien.value = [{value: 'LAMA', label:'LAMA'},{value: 'BARU', label:'BARU'},]
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const fetchRuangan = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&settingdatafix=objectdepartemenfk,idDepartemenIGD&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Ruangan.value = response
  })
}
const fetchRuanganMutasi = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_RuanganMutasi.value = response
  })
}

const fetchData = async () => {
  isPlaceLoad.value = true
  let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
  let ruanganfkAsal = item.value.ruanganfkAsal ? `&ruanganId=${item.value.ruanganfkAsal.value}` : ''

  const periodeText = `Periode : ${moment(item.value.qFilterTgl.start).format('DD/MM/YYYY HH:mm:ss')} s/d ${moment(item.value.qFilterTgl.end).format('DD/MM/YYYY HH:mm:ss')}`;
  periode.value = periodeText;

  await useApi().get(`/report/pelayanan/get-laporan-asal-rujukan-igd?${tglAwal}${tglAkhir}${ruanganfkAsal}`).then((response: any) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataSource.value = response.data
  })
  isPlaceLoad.value = false

}
const fetchDataCaraBayar = async () => {
  isPlaceLoad.value = true
  let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
  let ruanganfkCara = item.value.ruanganfkCara ? `&ruanganId=${item.value.ruanganfkCara.value}` : ''

  const periodeText = `Periode : ${moment(item.value.qFilterTgl.start).format('DD/MM/YYYY HH:mm:ss')} s/d ${moment(item.value.qFilterTgl.end).format('DD/MM/YYYY HH:mm:ss')}`;
  periode.value = periodeText;

  await useApi().get(`/report/pelayanan/get-laporan-cara-bayar-igd?${tglAwal}${tglAkhir}${ruanganfkCara}`).then((response: any) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataSourceCaraBayar.value = response.data
  })
  isPlaceLoad.value = false

}

const fetchDataCaraPulang = async () => {
  isPlaceLoad.value = true
  let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
  let ruanganfkPulang = item.value.ruanganfkPulang ? `&ruanganId=${item.value.ruanganfkPulang.value}` : ''

  const periodeText = `Periode : ${moment(item.value.qFilterTgl.start).format('DD/MM/YYYY HH:mm:ss')} s/d ${moment(item.value.qFilterTgl.end).format('DD/MM/YYYY HH:mm:ss')}`;
  periode.value = periodeText;

  await useApi().get(`/report/pelayanan/get-laporan-cara-pulang-igd?${tglAwal}${tglAkhir}${ruanganfkPulang}`).then((response: any) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
      // Ganti nilai statuspulang yang null dengan "Tidak Diketahui"
      if (element.statuspulang === null) {
        element.statuspulang = "Tidak Diketahui";
      }
    });
    dataSourceCaraPulang.value = response.data
    // console.log(response.data)
  })
  isPlaceLoad.value = false
}


const fetchDataDiagnosa = async () => {
  isPlaceLoad.value = true
  let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
  let ruanganfkDiagnosa = item.value.ruanganfkDiagnosa ? `&ruanganId=${item.value.ruanganfkDiagnosa.value}` : ''

  const periodeText = `Periode : ${moment(item.value.qFilterTgl.start).format('DD/MM/YYYY HH:mm:ss')} s/d ${moment(item.value.qFilterTgl.end).format('DD/MM/YYYY HH:mm:ss')}`;
  periode.value = periodeText;

  await useApi().get(`/report/pelayanan/get-laporan-diagnosa-igd?${tglAwal}${tglAkhir}${ruanganfkDiagnosa}`).then((response: any) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataSourceDiagnosa.value = response.data
  })
  isPlaceLoad.value = false

}

const fetchDataDpjp = async () => {
  isPlaceLoad.value = true
  let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
  let ruanganfkDPJP = item.value.ruanganfkDPJP ? `&ruanganId=${item.value.ruanganfkDPJP.value}` : ''

  const periodeText = `Periode : ${moment(item.value.qFilterTgl.start).format('DD/MM/YYYY HH:mm:ss')} s/d ${moment(item.value.qFilterTgl.end).format('DD/MM/YYYY HH:mm:ss')}`;
  periode.value = periodeText;

  await useApi().get(`/report/pelayanan/get-laporan-dpjp-igd?${tglAwal}${tglAkhir}${ruanganfkDPJP}`).then((response: any) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataSourceDpjp.value = response.data
  })
  isPlaceLoad.value = false

}

const fetchDataJumlahDpjp = async () => {
  isPlaceLoad.value = true
  let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
  let ruanganfkJumlahDPJP = item.value.ruanganfkJumlahDPJP ? `&ruanganId=${item.value.ruanganfkJumlahDPJP.value}` : ''

  const periodeText = `Periode : ${moment(item.value.qFilterTgl.start).format('DD/MM/YYYY HH:mm:ss')} s/d ${moment(item.value.qFilterTgl.end).format('DD/MM/YYYY HH:mm:ss')}`;
  periode.value = periodeText;

  await useApi().get(`/report/pelayanan/get-laporan-jumlah-dpjp-igd?${tglAwal}${tglAkhir}${ruanganfkJumlahDPJP}`).then((response: any) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataSourceJumlahDpjp.value = response.data
  })
  isPlaceLoad.value = false

}

const fetchDataJenisKelamin = async () => {
  isPlaceLoad.value = true
  let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
  let ruanganfk = item.value.ruanganfk ? `&ruanganId=${item.value.ruanganfk.value}` : ''

  const periodeText = `Periode : ${moment(item.value.qFilterTgl.start).format('DD/MM/YYYY HH:mm:ss')} s/d ${moment(item.value.qFilterTgl.end).format('DD/MM/YYYY HH:mm:ss')}`;
  periode.value = periodeText;

  await useApi().get(`/report/pelayanan/get-laporan-jeniskelamin-igd?${tglAwal}${tglAkhir}${ruanganfk}`).then((response: any) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataSourceJenisKelamin.value = response.data
  })
  isPlaceLoad.value = false

}

const fetchDataDomisili = async () => {
  isPlaceLoad.value = true
  let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
  let ruanganfkMutasi = item.value.ruanganfkMutasi ? `&ruanganId=${item.value.ruanganfkMutasi.value}` : ''

  const periodeText = `Periode : ${moment(item.value.qFilterTgl.start).format('DD/MM/YYYY HH:mm:ss')} s/d ${moment(item.value.qFilterTgl.end).format('DD/MM/YYYY HH:mm:ss')}`;
  periode.value = periodeText;

  await useApi().get(`/report/pelayanan/get-laporan-domisili-igd?${tglAwal}${tglAkhir}${ruanganfkMutasi}`).then((response: any) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataSourceDomisili.value = response.data
  })
  isPlaceLoad.value = false

}

const fetchDataMutasi = async () => {
  isPlaceLoad.value = true
  let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
  let ruanganfk = item.value.ruanganfk ? `&ruanganId=${item.value.ruanganfk.value}` : ''
  let ruanganfkIGD = item.value.ruanganfkIGD ? `&ruanganIdIGD=${item.value.ruanganfkIGD.value}` : ''

  const periodeText = `Periode : ${moment(item.value.qFilterTgl.start).format('DD/MM/YYYY HH:mm:ss')} s/d ${moment(item.value.qFilterTgl.end).format('DD/MM/YYYY HH:mm:ss')}`;
  periode.value = periodeText;

  await useApi().get(`/report/pelayanan/get-laporan-mutasi-igd?${tglAwal}${tglAkhir}${ruanganfk}${ruanganfkIGD}`).then((response: any) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataSourceMutasi.value = response.data
    jumlahRuangan.value = response.data.length
  })
  isPlaceLoad.value = false

}

const fetchDataDiagnosaPerpasien = async () => {
  isPlaceLoad.value = true
  let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')

  const periodeText = `Periode : ${moment(item.value.qFilterTgl.start).format('DD/MM/YYYY HH:mm:ss')} s/d ${moment(item.value.qFilterTgl.end).format('DD/MM/YYYY HH:mm:ss')}`;
  periode.value = periodeText;

  await useApi().get(`/report/pelayanan/get-laporan-diagnosa-perpasien-igd?${tglAwal}${tglAkhir}`).then((response: any) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataSourceDiagnosaPerpasien.value = response.data
  })
  isPlaceLoad.value = false

}

const fetchDataIndexing = async () => {
  isPlaceLoad.value = true
  let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
  let ruanganfk = item.value.ruanganfk ? `&ruanganId=${item.value.ruanganfk.value}` : ''
  let dokter = item.value.dokterfk ? `&dokter=${item.value.dokterfk.value}` : ''
  let nama = item.value.namaPasien ? `&nama=${item.value.namaPasien}` : ''
  let kelompokpasien = item.value.kelompokpasien ? `&kelompokpasien=${item.value.kelompokpasien.value}` : ''
  let statuspasien = item.value.jenispasien ? `&statuspasien=${item.value.jenispasien.value}` : ''

  const periodeText = `Periode : ${moment(item.value.qFilterTgl.start).format('DD/MM/YYYY HH:mm:ss')} s/d ${moment(item.value.qFilterTgl.end).format('DD/MM/YYYY HH:mm:ss')}`;
  periode.value = periodeText;

  await useApi().get(`pelayanan/get-laporan-indexing-gd?${tglAwal}${tglAkhir}${ruanganfk}${dokter}${nama}${kelompokpasien}${statuspasien}`).then((response: any) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataSourceIndexing.value = response.data
  })
  isPlaceLoad.value = false

}

const fetchDataPenandaPasien = async () => {
  isPlaceLoad.value = true
  let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
  let ruanganfk = item.value.ruanganfk ? `&ruanganId=${item.value.ruanganfk.value}` : ''
  let dokter = item.value.dokterfk ? `&dokter=${item.value.dokterfk.value}` : ''
  let nama = item.value.namaPasien ? `&nama=${item.value.namaPasien}` : ''
  let kelompokpasien = item.value.kelompokpasien ? `&kelompokpasien=${item.value.kelompokpasien.value}` : ''
  let statuspasien = item.value.jenispasien ? `&statuspasien=${item.value.jenispasien.value}` : ''

  const periodeText = `Periode : ${moment(item.value.qFilterTgl.start).format('DD/MM/YYYY HH:mm:ss')} s/d ${moment(item.value.qFilterTgl.end).format('DD/MM/YYYY HH:mm:ss')}`;
  periode.value = periodeText;

  await useApi().get(`pelayanan/get-laporan-penanda-pasien-igd?${tglAwal}${tglAkhir}${ruanganfk}${dokter}${nama}${kelompokpasien}${statuspasien}`).then((response: any) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataSourcePenandaPasien.value = response.data
  })
  isPlaceLoad.value = false

}

const exportExcel = () => {
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['PEMERINTAH KOTA CIREBON'],
    ['RSD GUNUNG JATI'],
    ['Jl. Kesambi No.56, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134 (0231) 206330'],
    [],
    ['LAPORAN ASAL RUJUKAN IGD'],
    [periode.value],
    [],
    [
      'No', 'Asal Rujukan', 'Jumlah'
    ],
    ...dataSource.value.map((e) => [
      e.no, e.asalrujukan, e.jumlah
    ]),
    [],
  ]);

  const columnWidths = [
    { wch: 14 },
    { wch: 25 },
    { wch: 25 },
  ];
  worksheet['!cols'] = columnWidths;

  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 0, c: 10 } };

  const mergeSubtitle1 = { s: { r: 1, c: 0 }, e: { r: 1, c: 10 } };
  const mergeSubtitle2 = { s: { r: 2, c: 0 }, e: { r: 2, c: 10 } };
  const mergeSubtitle4 = { s: { r: 4, c: 0 }, e: { r: 4, c: 10 } };
  const mergeSubtitle5 = { s: { r: 5, c: 0 }, e: { r: 5, c: 10 } };

  worksheet['!merges'] = [mergeTitle, mergeSubtitle1, mergeSubtitle2, mergeSubtitle4, mergeSubtitle5];
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, 'data');

  const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
  saveAsExcelFile(excelBuffer, 'products');
}

const exportExcelCaraBayar = () => {
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['PEMERINTAH KOTA CIREBON'],
    ['RSD GUNUNG JATI'],
    ['Jl. Kesambi No.56, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134 (0231) 206330'],
    [],
    ['LAPORAN CARA BAYAR IGD'],
    [periode.value],
    [],
    [
      'No', 'Kelompok Pasien', 'Jumlah'
    ],
    ...dataSourceCaraBayar.value.map((e) => [
      e.no, e.kelompokpasien, e.jumlah
    ]),
    [],
  ]);

  const columnWidths = [
    { wch: 14 },
    { wch: 25 },
    { wch: 25 },
  ];
  worksheet['!cols'] = columnWidths;

  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 0, c: 10 } };

  const mergeSubtitle1 = { s: { r: 1, c: 0 }, e: { r: 1, c: 10 } };
  const mergeSubtitle2 = { s: { r: 2, c: 0 }, e: { r: 2, c: 10 } };
  const mergeSubtitle4 = { s: { r: 4, c: 0 }, e: { r: 4, c: 10 } };
  const mergeSubtitle5 = { s: { r: 5, c: 0 }, e: { r: 5, c: 10 } };

  worksheet['!merges'] = [mergeTitle, mergeSubtitle1, mergeSubtitle2, mergeSubtitle4, mergeSubtitle5];
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, 'data');

  const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
  saveAsExcelCaraBayarFile(excelBuffer, 'products');
}

const exportExcelCaraPulang = () => {
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['PEMERINTAH KOTA CIREBON'],
    ['RSD GUNUNG JATI'],
    ['Jl. Kesambi No.56, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134 (0231) 206330'],
    [],
    ['LAPORAN CARA PULANG IGD'],
    [periode.value],
    [],
    [
      'No', 'Kelompok Pasien', 'Jumlah'
    ],
    ...dataSourceCaraPulang.value.map((e) => [
      e.no, e.statuspulang, e.jumlah
    ]),
    [],
  ]);

  const columnWidths = [
    { wch: 14 },
    { wch: 25 },
    { wch: 25 },
  ];
  worksheet['!cols'] = columnWidths;

  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 0, c: 10 } };

  const mergeSubtitle1 = { s: { r: 1, c: 0 }, e: { r: 1, c: 10 } };
  const mergeSubtitle2 = { s: { r: 2, c: 0 }, e: { r: 2, c: 10 } };
  const mergeSubtitle4 = { s: { r: 4, c: 0 }, e: { r: 4, c: 10 } };
  const mergeSubtitle5 = { s: { r: 5, c: 0 }, e: { r: 5, c: 10 } };

  worksheet['!merges'] = [mergeTitle, mergeSubtitle1, mergeSubtitle2, mergeSubtitle4, mergeSubtitle5];
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, 'data');

  const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
  saveAsExcelCaraPualangFile(excelBuffer, 'products');
}

const exportExcelDiagnosa = () => {
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['PEMERINTAH KOTA CIREBON'],
    ['RSD GUNUNG JATI'],
    ['Jl. Kesambi No.56, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134 (0231) 206330'],
    [],
    ['LAPORAN DIAGNOSA IGD'],
    [periode.value],
    [],
    [
      'No', 'KD Diagnosa', 'Nama Diagnosa', 'Jumlah'
    ],
    ...dataSourceDiagnosa.value.map((e) => [
      e.no, e.kddiagnosa, e.namadiagnosa, e.jumlah
    ]),
    [],
  ]);

  const columnWidths = [
    { wch: 14 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
  ];
  worksheet['!cols'] = columnWidths;

  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 0, c: 10 } };

  const mergeSubtitle1 = { s: { r: 1, c: 0 }, e: { r: 1, c: 10 } };
  const mergeSubtitle2 = { s: { r: 2, c: 0 }, e: { r: 2, c: 10 } };
  const mergeSubtitle4 = { s: { r: 4, c: 0 }, e: { r: 4, c: 10 } };
  const mergeSubtitle5 = { s: { r: 5, c: 0 }, e: { r: 5, c: 10 } };

  worksheet['!merges'] = [mergeTitle, mergeSubtitle1, mergeSubtitle2, mergeSubtitle4, mergeSubtitle5];
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, 'data');

  const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
  saveAsExcelDiagnosaFile(excelBuffer, 'products');
}

const exportExcelDpjp = () => {
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['PEMERINTAH KOTA CIREBON'],
    ['RSD GUNUNG JATI'],
    ['Jl. Kesambi No.56, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134 (0231) 206330'],
    [],
    ['LAPORAN DPJP IGD'],
    [periode.value],
    [],
    [
      'No', 'Tanggal Registrasi', 'Nama Pasien', 'Dokter'
    ],
    ...dataSourceDpjp.value.map((e) => [
      e.no, e.tglregistrasi, maskNamaPasien(e.namapasien), e.dokter
    ]),
    [],
  ]);

  const columnWidths = [
    { wch: 14 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
  ];
  worksheet['!cols'] = columnWidths;

  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 0, c: 21 } };

  const mergeSubtitle1 = { s: { r: 1, c: 0 }, e: { r: 1, c: 21 } };
  const mergeSubtitle2 = { s: { r: 2, c: 0 }, e: { r: 2, c: 21 } };
  const mergeSubtitle4 = { s: { r: 4, c: 0 }, e: { r: 4, c: 21 } };
  const mergeSubtitle5 = { s: { r: 5, c: 0 }, e: { r: 5, c: 21 } };

  worksheet['!merges'] = [mergeTitle, mergeSubtitle1, mergeSubtitle2, mergeSubtitle4, mergeSubtitle5];
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, 'data');

  const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
  saveAsExcelDpjpFile(excelBuffer, 'products');
}

const exportExcelJumlahDpjp = () => {
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['PEMERINTAH KOTA CIREBON'],
    ['RSD GUNUNG JATI'],
    ['Jl. Kesambi No.56, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134 (0231) 206330'],
    [],
    ['LAPORAN JUMLAH DPJP IGD'],
    [periode.value],
    [],
    [
      'No', 'Dokter', 'Jumlah'
    ],
    ...dataSourceJumlahDpjp.value.map((e) => [
      e.no, e.dokter, e.jumlah
    ]),
    [],
  ]);

  const columnWidths = [
    { wch: 14 },
    { wch: 25 },
    { wch: 25 },
  ];
  worksheet['!cols'] = columnWidths;

  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 0, c: 10 } };

  const mergeSubtitle1 = { s: { r: 1, c: 0 }, e: { r: 1, c: 10 } };
  const mergeSubtitle2 = { s: { r: 2, c: 0 }, e: { r: 2, c: 10 } };
  const mergeSubtitle4 = { s: { r: 4, c: 0 }, e: { r: 4, c: 10 } };
  const mergeSubtitle5 = { s: { r: 5, c: 0 }, e: { r: 5, c: 10 } };

  worksheet['!merges'] = [mergeTitle, mergeSubtitle1, mergeSubtitle2, mergeSubtitle4, mergeSubtitle5];
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, 'data');

  const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
  saveAsExcelJumlahDpjpFile(excelBuffer, 'products');
}

const exportExcelJenisKelamin = () => {
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['PEMERINTAH KOTA CIREBON'],
    ['RSD GUNUNG JATI'],
    ['Jl. Kesambi No.56, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134 (0231) 206330'],
    [],
    ['LAPORAN JENIS KELAMIN IGD'],
    [periode.value],
    [],
    [
      'No', 'Jenis Kelamin', 'Jumlah'
    ],
    ...dataSourceJenisKelamin.value.map((e) => [
      e.no, e.jeniskelamin, e.jumlah
    ]),
    [],
  ]);

  const columnWidths = [
    { wch: 14 },
    { wch: 25 },
    { wch: 25 },
  ];
  worksheet['!cols'] = columnWidths;

  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 0, c: 10 } };

  const mergeSubtitle1 = { s: { r: 1, c: 0 }, e: { r: 1, c: 10 } };
  const mergeSubtitle2 = { s: { r: 2, c: 0 }, e: { r: 2, c: 10 } };
  const mergeSubtitle4 = { s: { r: 4, c: 0 }, e: { r: 4, c: 10 } };
  const mergeSubtitle5 = { s: { r: 5, c: 0 }, e: { r: 5, c: 10 } };

  worksheet['!merges'] = [mergeTitle, mergeSubtitle1, mergeSubtitle2, mergeSubtitle4, mergeSubtitle5];
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, 'data');

  const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
  saveAsExcelJenisKelamin(excelBuffer, 'products');
}

const exportExcelDomisili = () => {
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['PEMERINTAH KOTA CIREBON'],
    ['RSD GUNUNG JATI'],
    ['Jl. Kesambi No.56, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134 (0231) 206330'],
    [],
    ['LAPORAN DOMISILI IGD'],
    [periode.value],
    [],
    [
      'No', 'Kota/Kabupaten', 'Jumlah'
    ],
    ...dataSourceDomisili.value.map((e) => [
      e.no, e.namakotakabupaten, e.jumlah
    ]),
    [],
  ]);

  const columnWidths = [
    { wch: 14 },
    { wch: 25 },
    { wch: 25 },
  ];
  worksheet['!cols'] = columnWidths;

  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 0, c: 10 } };

  const mergeSubtitle1 = { s: { r: 1, c: 0 }, e: { r: 1, c: 10 } };
  const mergeSubtitle2 = { s: { r: 2, c: 0 }, e: { r: 2, c: 10 } };
  const mergeSubtitle4 = { s: { r: 4, c: 0 }, e: { r: 4, c: 10 } };
  const mergeSubtitle5 = { s: { r: 5, c: 0 }, e: { r: 5, c: 10 } };

  worksheet['!merges'] = [mergeTitle, mergeSubtitle1, mergeSubtitle2, mergeSubtitle4, mergeSubtitle5];
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, 'data');

  const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
  saveAsExcelDomisili(excelBuffer, 'products');
}
const exportExcelMutasi = () => {
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['PEMERINTAH KOTA CIREBON'],
    ['RSD GUNUNG JATI'],
    ['Jl. Kesambi No.56, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134 (0231) 206330'],
    [],
    ['LAPORAN MUTASI IGD'],
    [periode.value],
    [],
    [
      'No', 'Tanggal Mutasi','Tanggal Registrasi', 'Nama Pasien', 'No CM', 'Noregistrasi', 'Ruangan Asal IGD', 'Ruangan Mutasi',
      'kamar', 'Dokter', 'Desa/Kelurahan', 'Kecamatan', 'Kabupaten', 'No BPJS', 'Alamat Rumah', 'NIK', 'Kelas'
    ],
    ...dataSourceMutasi.value.map((e) => [
      e.no, e.tglmasuk, e.tglregistrasi, maskNamaPasien(e.namapasien), e.nocm, e.noregistrasi, e.Ruangan_asal_IGD, e.Ruangan_Mutasi, e.reportdisplay,
      e.Dokter, e.namadesakelurahan, e.namakecamatan, e.namakotakabupaten, e.nobpjs, e.alamatrmh, e.NIK, e.namakelas
    ]),
    [],
    ['JUMLAH RUANGAN  :',jumlahRuangan.value ],
  ]);

  const columnWidths = [
    { wch: 14 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
  ];
  worksheet['!cols'] = columnWidths;

  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 0, c: 20 } };

  const mergeSubtitle1 = { s: { r: 1, c: 0 }, e: { r: 1, c: 20 } };
  const mergeSubtitle2 = { s: { r: 2, c: 0 }, e: { r: 2, c: 20 } };
  const mergeSubtitle4 = { s: { r: 4, c: 0 }, e: { r: 4, c: 20 } };
  const mergeSubtitle5 = { s: { r: 5, c: 0 }, e: { r: 5, c: 20 } };

  worksheet['!merges'] = [mergeTitle, mergeSubtitle1, mergeSubtitle2, mergeSubtitle4, mergeSubtitle5];
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, 'data');

  const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
  saveAsExcelMutasi(excelBuffer, 'products');
}

const exportExcelDiagnosaPerpasien = () => {
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['PEMERINTAH KOTA CIREBON'],
    ['RSD GUNUNG JATI'],
    ['Jl. Kesambi No.56, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134 (0231) 206330'],
    [],
    ['LAPORAN DIAGNOSA PER PASIEN IGD'],
    [periode.value],
    [],
    [
    'No', 'Noregistrasi', 'NO CM', 'Nama Pasien', 'Jenis Kelamin', 'Tanggal Lahir',
    'Tanggal Masuk', 'Status Pulang', 'Kondisi Pasien', 'Tanggal Pulang', 'Jenis Pasien',
    'Nama Ruangan', 'Dokter', 'KD Diagnosa Tindakan', 'Nama Diagnosa Tindakan',
    'Alamat Lengkap', 'Kota/Kabupaten', 'Kecamatan', 'Desa/Kelurahan', 'RT/RW',
    'Provinsi', 'Status Pasien', 'ID Dokter'
    ],
    ...dataSourceDiagnosaPerpasien.value.map((e) => [
    e.no, e.noregistrasi, e.nocm, maskNamaPasien(e.namapasien), e.jeniskelamin, e.tgllahir, e.tglmasuk, e.statuspulang, e.kondisipasien,
    e.tglpulang, e.jenis_pasien, e.namaruangan, e.dokter, e.kddiagnosatindakan, e.namadiagnosatindakan, e.alamatlengkap,
    e.namakotakabupaten, e.namakecamatan, e.namadesakelurahan, e.rtrw, e.namapropinsi, e.statuspasien, e.id_dokter
    ]),
    [],
  ]);

  const columnWidths = [
    { wch: 14 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
  ];
  worksheet['!cols'] = columnWidths;

  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 0, c: 22 } };

  const mergeSubtitle1 = { s: { r: 1, c: 0 }, e: { r: 1, c: 22 } };
  const mergeSubtitle2 = { s: { r: 2, c: 0 }, e: { r: 2, c: 22 } };
  const mergeSubtitle4 = { s: { r: 4, c: 0 }, e: { r: 4, c: 22 } };
  const mergeSubtitle5 = { s: { r: 5, c: 0 }, e: { r: 5, c: 22 } };

  worksheet['!merges'] = [mergeTitle, mergeSubtitle1, mergeSubtitle2, mergeSubtitle4, mergeSubtitle5];
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, 'data');

  const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
  saveAsExcelDiagnosaPerpasien(excelBuffer, 'products');
}

const exportExcelIndexing = () => {
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['PEMERINTAH KOTA CIREBON'],
    ['RSD GUNUNG JATI'],
    ['Jl. Kesambi No.56, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134 (0231) 206330'],
    [],
    ['LAPORAN INDEXING IGD'],
    [periode.value],
    [],
    [
    'No', 'NamaPasien', 'NO CM', 'NoRegistrasi', 'Jenis Kelamin', 'Tanggal Lahir',
    'Tanggal Masuk', 'Kelompok Pasien', 'Tanggal', 'StatusPulang', 'Tanggal Pulang',
    'Kondisi Pasien', 'Dokter', 'Ruangan Asal', 'RuanganRawat', 'Kelas', 'Asal Rujukan',
    'Jenis Diagnosa', 'Kode Diagnosa', 'Nama Diagnosa', 'Kota Kabupaten', 'Kecamatan',
    'Desa', 'Provinsi', 'Status'
    ],
    ...dataSourceIndexing.value.map((e) => [
      e.no, maskNamaPasien(e.namapasien), e.nocm, e.noregistrasi, e.jeniskelamin, e.tgllahir, e.tglmasuk, e.jenis_pasien,
      e.tglmasuk, e.statuspulang, e.tglpulang, e.kondisipulang, e.dokter, e.namaruanganasal, e.namaruanganrawat, e.namakelas, e.asalrujukan,
      e.jenisdiagnosa, e.kodediagnosa, e.namadiagnosa, e.namakotakabupaten, e.namakecamatan, e.namadesakelurahan, e.namapropinsi, e.statuspasien
    ]),
    [],
  ]);

  const columnWidths = [
    { wch: 14 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
  ];
  worksheet['!cols'] = columnWidths;

  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 0, c: 25 } };

  const mergeSubtitle1 = { s: { r: 1, c: 0 }, e: { r: 1, c: 25 } };
  const mergeSubtitle2 = { s: { r: 2, c: 0 }, e: { r: 2, c: 25 } };
  const mergeSubtitle4 = { s: { r: 4, c: 0 }, e: { r: 4, c: 25 } };
  const mergeSubtitle5 = { s: { r: 5, c: 0 }, e: { r: 5, c: 25 } };

  worksheet['!merges'] = [mergeTitle, mergeSubtitle1, mergeSubtitle2, mergeSubtitle4, mergeSubtitle5];
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, 'data');

  const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
  saveAsExcelIndexing(excelBuffer, 'products');
}

const exportExcelPenandaPasien = () => {
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['PEMERINTAH KOTA CIREBON'],
    ['RSD GUNUNG JATI'],
    ['Jl. Kesambi No.56, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134 (0231) 206330'],
    [],
    ['LAPORAN PENANDA PASIEN IGD'],
    [periode.value],
    [],
    [
    'No', 'Nama Pasien', 'NO CM', 'No Registrasi', 'Jenis Kelamin', 'Tanggal Lahir', 'Usia Pasien', 'Ruangan Asal', 'Ruangan Terakhir', 'Penanda', 'Kategori Usia'
    ],
    ...dataSourcePenandaPasien.value.map((e) => [
      e.no, maskNamaPasien(e.namapasien), e.nocm, e.noregistrasi, e.jeniskelamin, e.tgllahir, e.umur, e.namaruanganasal, e.namaruanganrawat, e.penanda, e.kategoriUsia
    ]),
    [],
  ]);

  const columnWidths = [
    { wch: 14 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
  ];
  worksheet['!cols'] = columnWidths;

  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 0, c: 25 } };

  const mergeSubtitle1 = { s: { r: 1, c: 0 }, e: { r: 1, c: 25 } };
  const mergeSubtitle2 = { s: { r: 2, c: 0 }, e: { r: 2, c: 25 } };
  const mergeSubtitle4 = { s: { r: 4, c: 0 }, e: { r: 4, c: 25 } };
  const mergeSubtitle5 = { s: { r: 5, c: 0 }, e: { r: 5, c: 25 } };

  worksheet['!merges'] = [mergeTitle, mergeSubtitle1, mergeSubtitle2, mergeSubtitle4, mergeSubtitle5];
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, 'data');

  const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
  saveAsExcelPenandaPasien(excelBuffer, 'products');
}

const saveAsExcelFile = (buffer: any, fileName: string) => {
  let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
  let EXCEL_EXTENSION = '.xlsx';
  const data: Blob = new Blob([buffer], {
    type: EXCEL_TYPE
  });
  // window.open(_url, EXCEL_EXTENSION).focus();
  const desiredFileName = 'laporan_igd_asalrujukan' + EXCEL_EXTENSION;
  const link = document.createElement('a');
  link.href = window.URL.createObjectURL(data);
  link.download = desiredFileName;
  link.click();
  window.URL.revokeObjectURL(link.href);
}
const saveAsExcelCaraBayarFile = (buffer: any, fileName: string) => {
  let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
  let EXCEL_EXTENSION = '.xlsx';
  const data: Blob = new Blob([buffer], {
    type: EXCEL_TYPE
  });
  // window.open(_url, EXCEL_EXTENSION).focus();
  const desiredFileName = 'laporan_igd_carabayar' + EXCEL_EXTENSION;
  const link = document.createElement('a');
  link.href = window.URL.createObjectURL(data);
  link.download = desiredFileName;
  link.click();
  window.URL.revokeObjectURL(link.href);
}

const saveAsExcelCaraPualangFile = (buffer: any, fileName: string) => {
  let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
  let EXCEL_EXTENSION = '.xlsx';
  const data: Blob = new Blob([buffer], {
    type: EXCEL_TYPE
  });
  // window.open(_url, EXCEL_EXTENSION).focus();
  const desiredFileName = 'laporan_igd_carapulang' + EXCEL_EXTENSION;
  const link = document.createElement('a');
  link.href = window.URL.createObjectURL(data);
  link.download = desiredFileName;
  link.click();
  window.URL.revokeObjectURL(link.href);
}

const saveAsExcelDiagnosaFile = (buffer: any, fileName: string) => {
  let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
  let EXCEL_EXTENSION = '.xlsx';
  const data: Blob = new Blob([buffer], {
    type: EXCEL_TYPE
  });
  // window.open(_url, EXCEL_EXTENSION).focus();
  const desiredFileName = 'laporan_igd_diagnosa' + EXCEL_EXTENSION;
  const link = document.createElement('a');
  link.href = window.URL.createObjectURL(data);
  link.download = desiredFileName;
  link.click();
  window.URL.revokeObjectURL(link.href);
}

const saveAsExcelDpjpFile = (buffer: any, fileName: string) => {
  let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
  let EXCEL_EXTENSION = '.xlsx';
  const data: Blob = new Blob([buffer], {
    type: EXCEL_TYPE
  });
  // window.open(_url, EXCEL_EXTENSION).focus();
  const desiredFileName = 'laporan_igd_dpjp' + EXCEL_EXTENSION;
  const link = document.createElement('a');
  link.href = window.URL.createObjectURL(data);
  link.download = desiredFileName;
  link.click();
  window.URL.revokeObjectURL(link.href);
}

const saveAsExcelJumlahDpjpFile = (buffer: any, fileName: string) => {
  let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
  let EXCEL_EXTENSION = '.xlsx';
  const data: Blob = new Blob([buffer], {
    type: EXCEL_TYPE
  });
  // window.open(_url, EXCEL_EXTENSION).focus();
  const desiredFileName = 'laporan_igd_jumlah_dpjp' + EXCEL_EXTENSION;
  const link = document.createElement('a');
  link.href = window.URL.createObjectURL(data);
  link.download = desiredFileName;
  link.click();
  window.URL.revokeObjectURL(link.href);
}

const saveAsExcelJenisKelamin = (buffer: any, fileName: string) => {
  let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
  let EXCEL_EXTENSION = '.xlsx';
  const data: Blob = new Blob([buffer], {
    type: EXCEL_TYPE
  });
  // window.open(_url, EXCEL_EXTENSION).focus();
  const desiredFileName = 'laporan_igd_jenis_kelamin' + EXCEL_EXTENSION;
  const link = document.createElement('a');
  link.href = window.URL.createObjectURL(data);
  link.download = desiredFileName;
  link.click();
  window.URL.revokeObjectURL(link.href);
}

const saveAsExcelDomisili = (buffer: any, fileName: string) => {
  let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
  let EXCEL_EXTENSION = '.xlsx';
  const data: Blob = new Blob([buffer], {
    type: EXCEL_TYPE
  });
  // window.open(_url, EXCEL_EXTENSION).focus();
  const desiredFileName = 'laporan_igd_domisili' + EXCEL_EXTENSION;
  const link = document.createElement('a');
  link.href = window.URL.createObjectURL(data);
  link.download = desiredFileName;
  link.click();
  window.URL.revokeObjectURL(link.href);
}

const saveAsExcelMutasi = (buffer: any, fileName: string) => {
  let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
  let EXCEL_EXTENSION = '.xlsx';
  const data: Blob = new Blob([buffer], {
    type: EXCEL_TYPE
  });
  // window.open(_url, EXCEL_EXTENSION).focus();
  const desiredFileName = 'laporan_igd_mutasi' + EXCEL_EXTENSION;
  const link = document.createElement('a');
  link.href = window.URL.createObjectURL(data);
  link.download = desiredFileName;
  link.click();
  window.URL.revokeObjectURL(link.href);
}

const saveAsExcelDiagnosaPerpasien = (buffer: any, fileName: string) => {
  let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
  let EXCEL_EXTENSION = '.xlsx';
  const data: Blob = new Blob([buffer], {
    type: EXCEL_TYPE
  });
  // window.open(_url, EXCEL_EXTENSION).focus();
  const desiredFileName = 'laporan_igd_diagnosa_perpasien' + EXCEL_EXTENSION;
  const link = document.createElement('a');
  link.href = window.URL.createObjectURL(data);
  link.download = desiredFileName;
  link.click();
  window.URL.revokeObjectURL(link.href);
}

const saveAsExcelIndexing = (buffer: any, fileName: string) => {
  let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
  let EXCEL_EXTENSION = '.xlsx';
  const data: Blob = new Blob([buffer], {
    type: EXCEL_TYPE
  });
  // window.open(_url, EXCEL_EXTENSION).focus();
  const desiredFileName = 'laporan_indexing_igd' + EXCEL_EXTENSION;
  const link = document.createElement('a');
  link.href = window.URL.createObjectURL(data);
  link.download = desiredFileName;
  link.click();
  window.URL.revokeObjectURL(link.href);
}

const saveAsExcelPenandaPasien = (buffer: any, fileName: string) => {
  let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
  let EXCEL_EXTENSION = '.xlsx';
  const data: Blob = new Blob([buffer], {
    type: EXCEL_TYPE
  });
  // window.open(_url, EXCEL_EXTENSION).focus();
  const desiredFileName = 'laporan_penanda_pasien_igd' + EXCEL_EXTENSION;
  const link = document.createElement('a');
  link.href = window.URL.createObjectURL(data);
  link.download = desiredFileName;
  link.click();
  window.URL.revokeObjectURL(link.href);
}

const maskNamaPasien = (nama: string): string => {
  if (!nama) return '';

  const parts = nama.split(' ');
  const maskedParts = parts.map((part) => {
    if (part.length <= 2) {
      return part;
    }
    const first = part[0];
    const last = part[part.length - 1];
    const mask = '*'.repeat(part.length - 2);
    return `${first}${mask}${last}`;
  });

  return maskedParts.join(' ');
};

onMounted(async () => {
  await fetchData(),
    await fetchDataCaraBayar(),
    await fetchDataCaraPulang(),
    await fetchDataDiagnosa(),
    await fetchDataDpjp(),
    await fetchDataJumlahDpjp(),
    await fetchDataJenisKelamin(),
    await fetchDataDomisili(),
    await fetchDataMutasi(),
    await fetchDataDiagnosaPerpasien(),
    await fetchDataIndexing()
    await fetchDataPenandaPasien()
})
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

.tabs-wrapper.is-slider .tabs,
.tabs-wrapper-alt.is-slider .tabs {
  position: relative !important;
  background: var(--fade-grey-light-2) !important;
  border: 1px solid var(--fade-grey) !important;
  max-width: 70% !important;
  height: 35px !important;
  border-bottom: none !important;
}

.lifestyle-dashboard-v4 {
  .illustration-header-2 {
    display: flex;
    align-items: center;
    padding: 10px;
    border-radius: 16px;
    background: var(--primary-dark-24);
    font-family: var(--font);
    box-shadow: var(--primary-box-shadow);

    .header-image {
      position: relative;
      height: 175px;
      width: 320px;

      img {
        position: absolute;
        top: 0;
        left: -40px;
        display: block;
        pointer-events: none;
      }
    }

    .header-meta {
      margin-left: 0;
      padding-right: 30px;

      h3 {
        color: var(--smoke-white);
        font-family: var(--font-alt);
        font-weight: 700;
        font-size: 1.3rem;
        max-width: 280px;
      }

      p {
        font-weight: 400;
        color: var(--smoke-white-dark-2);
        margin-bottom: 16px;
        max-width: 320px;
      }

      .action-link {
        span {
          font-size: 0.8rem;
          text-transform: uppercase;
          margin-right: 6px;
        }

        i {
          font-size: 12px;
        }
      }
    }
  }

  .writing-stats {
    display: flex;
    margin-bottom: 1rem;
    margin-left: -8px;
    margin-right: -8px;

    .writing-stat {
      @include vuero-l-card;

      margin: 8px;
      width: calc(33.3% - 16px);
      padding: 12px;

      span {
        display: block;

        &:first-child {
          font-family: var(--font-alt);
          font-size: 0.8rem;
          font-weight: 500;
          text-transform: uppercase;
          margin-bottom: 5px;
          color: var(--light-text);
        }

        &:nth-child(2) {
          font-family: var(--font);
          font-weight: 700;
          font-size: 1.8rem;
          color: var(--dark-text);
        }
      }
    }
  }

  .featured-authors {
    @include vuero-l-card;

    padding: 20px;

    .featured-authors-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 30px;

      h3 {
        font-family: var(--font-alt);
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--dark-text);
      }

      .action-link {
        font-size: 0.9rem;
      }
    }

    .featured-authors-list {
      .featured-authors-item {
        &:not(:last-child) {
          margin-bottom: 20px;
        }

        .media-flex-center {
          .flex-end {
            span {
              font-family: var(--font-alt);
              font-weight: 600;
              color: var(--dark-text);
            }
          }
        }
      }
    }
  }

  .updates {
    @include vuero-l-card;

    padding: 20px;
    margin-top: 8px;

    .updates-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;

      h3 {
        font-family: var(--font-alt);
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--dark-text);
      }

      .action-link {
        font-size: 0.9rem;
      }
    }

    .updates-list {
      .update-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--fade-grey-dark-3);

        &:last-child {
          margin-bottom: 0;
          border-bottom: none;
        }

        p {
          font-size: 0.9rem;
        }

        span {
          display: block;
          min-width: 60px;
          text-align: right;
          font-family: var(--font);
          font-weight: 600;
          font-size: 0.8rem;
          color: var(--dark-text);
        }
      }
    }
  }

  .page-placeholder .placeholder-content h3 {
    font-size: 0.9rem;
    font-weight: 600;
    font-family: var(--font-alt);
    color: var(--dark-text);
  }

  .tile-grid-v2 {
    .tile-grid-item {
      @include vuero-s-card;

      border-radius: 14px;
      padding: 16px;
      cursor: pointer;

      &:hover,
      &:focus {
        border-color: var(--primary);
        box-shadow: var(--light-box-shadow);
      }

      .tile-grid-item-inner {
        display: flex;
        align-items: center;

        >img {
          display: block;
          width: 50px;
          height: 50px;
          min-width: 50px;
        }

        .meta {
          margin-left: 10px;
          line-height: 1.4;

          span {
            display: block;
            font-family: var(--font);

            &:first-child {
              color: var(--dark-text);
              font-family: var(--font-alt);
              font-weight: 600;
              font-size: 0.9rem;
            }

            &:nth-child(2) {
              display: flex;
              align-items: center;

              span {
                display: inline-block;
                color: var(--light-text);
                font-size: 0.8rem;
                font-weight: 400;
              }

              .icon-separator {
                position: relative;
                font-size: 4px;
                color: var(--light-text);
                padding: 0 6px;
              }
            }
          }
        }

        .dropdown {
          margin-left: auto;
        }
      }
    }
  }

  .is-dark {
    .tile-grid {
      .tile-grid-item {
        @include vuero-card--dark;
      }
    }

    .tile-grid-v2 {
      .tile-grid-item {
        @include vuero-card--dark;

        &:hover,
        &:focus {
          border-color: var(--primary) !important;
        }
      }
    }
  }

  .articles-feed {
    background: var(--widget-grey);
    padding: 30px;
    border-radius: 12px;

    .articles-feed-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;

      h3 {
        font-family: var(--font-alt);
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--dark-text);
      }

      .action-link {
        font-size: 0.9rem;
      }
    }

    .articles-feed-subheader {
      margin-bottom: 20px;

      .selector {
        .button {
          font-size: 0.8rem;
          border-radius: 50px;
          margin-right: 4px;

          &.is-selected {
            background: var(--primary);
            color: var(--white);
            border-color: var(--primary);
            box-shadow: var(--primary-box-shadow);
          }
        }
      }
    }

    .articles-feed-list {
      .articles-feed-list-inner {
        .articles-feed-item {
          display: block;

          &:not(:last-child) {
            margin-bottom: 20px;
          }

          .featured-image {
            height: 180px;
            overflow: hidden;
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;

            img {
              display: block;
              width: 100%;
              height: 100%;
              object-fit: cover;
            }
          }

          .featured-content {
            position: relative;
            padding: 25px;
            border-radius: 18px;
            background: var(--white);
            margin-top: -40px;
            z-index: 1;

            h4,
            p {
              margin-bottom: 10px;
            }

            h4 {
              font-family: var(--font-alt);
              font-size: 1rem;
              font-weight: 600;
              color: var(--dark-text);
            }

            .media-flex-center {
              .flex-meta {
                span {
                  font-size: 0.8rem;
                }
              }
            }
          }
        }
      }
    }
  }
}

.is-dark {
  .lifestyle-dashboard-v4 {
    .illustration-header-2 {
      background: var(--dark-sidebar);
      box-shadow: none;
    }

    .writing-stats {
      .writing-stat {
        @include vuero-card--dark;
      }
    }

    .updates,
    .featured-authors {
      @include vuero-card--dark;
    }

    .articles-feed {
      background: var(--dark-sidebar-light-8);

      .articles-feed-subheader {
        .selector {
          .button {
            &.is-selected {
              background: var(--primary) !important;
              border-color: var(--primary) !important;
              box-shadow: var(--primary-box-shadow) !important;
              color: var(--white) !important;
            }
          }
        }
      }

      .articles-feed-list {
        .articles-feed-list-inner {
          .articles-feed-item {
            .featured-content {
              background: var(--dark-sidebar);
            }
          }
        }
      }
    }
  }
}

.hr-dashboard {
  .block-header {
    display: flex;
    border-radius: 16px;
    padding: 50px;
    background: var(--primary);
    font-family: var(--font);
    box-shadow: var(--primary-box-shadow);

    .left,
    .right {
      width: 30%;
    }

    .center {
      display: flex;
      flex-direction: column;
      width: 40%;
      padding-right: 30px;
      margin-right: 30px;
      border-right: 1px solid var(--primary-light-10);

      .block-text {
        margin-bottom: 16px;
      }

      .candidates {
        margin-top: auto;

        >.v-avatar {
          margin-right: 10px;
        }

        button {
          height: 40px;
          width: 40px;
          display: inline-flex;
          justify-content: center;
          align-items: center;
          border-radius: 10px;
          background: var(--white);
          color: var(--light-text);
          border: none;
          cursor: pointer;
          transition: all 0.3s; // transition-all test

          svg {
            height: 18px;
            width: 18px;
          }
        }
      }
    }

    .left {
      display: flex;
      justify-content: center;
      align-items: center;

      .current-user {
        .v-avatar {
          margin-bottom: 1rem;
        }

        h3 {
          font-family: var(--font-alt);
          font-weight: 700;
          font-size: 1.8rem;
          color: var(--white);
          line-height: 1.2;
        }
      }
    }

    .right {
      display: flex;
      flex-direction: column;

      .button {
        margin-top: auto;
      }
    }

    .block-heading {
      font-family: var(--font-alt);
      font-weight: 600;
      font-size: 1.1rem;
      color: var(--white);
      margin-bottom: 4px;
    }

    .block-text {
      font-family: var(--font);
      font-size: 0.9rem;
      color: var(--white);
      margin-bottom: 16px;
    }

    .header-meta {
      margin-left: 0;
      padding-right: 30px;

      h3 {
        color: var(--smoke-white);
        font-family: var(--font-alt);
        font-weight: 700;
        font-size: 1.3rem;
        max-width: 280px;
      }

      p {
        font-weight: 400;
        color: var(--smoke-white-dark-2);
        margin-bottom: 16px;
        max-width: 320px;
      }

      .action-link {
        span {
          font-size: 0.8rem;
          text-transform: uppercase;
          margin-right: 6px;
        }

        i {
          font-size: 12px;
        }
      }
    }
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

  .side-text {
    h3 {
      font-family: var(--font-alt);
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--dark-text);
      margin-bottom: 8px;
    }

    p {
      font-size: 0.95rem;
      margin-bottom: 8px;
    }

    .action-link {
      font-size: 0.9rem;
    }
  }

  .recent-rookies {
    .recent-rookies-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;

      h3 {
        font-family: var(--font-alt);
        font-size: 2rem;
        font-weight: 600;
        color: var(--dark-text);
      }
    }

    .user-grid {
      &.user-grid-v4 {
        .grid-item {
          @include vuero-l-card;
        }
      }
    }
  }
}

.tabs-wrapper.is-slider .tabs,
.tabs-wrapper-alt.is-slider .tabs {
  position: relative;
  background: var(--fade-grey-light-2);
  border: 1px solid var(--fade-grey);
  max-width: 70%;
  height: 35px;
  border-bottom: none;

}

.tile-grid {
  .columns {
    margin-left: -0.5rem !important;
    margin-right: -0.5rem !important;
    margin-top: -0.5rem !important;
  }

  .column {
    padding: 0.5rem !important;
  }
}

.tile-grid-v2 {
  .tile-grid-item {
    @include vuero-s-card;

    border-radius: 14px;
    padding: 16px;
    cursor: pointer;

    &:hover,
    &:focus {
      border-color: var(--primary);
      box-shadow: var(--light-box-shadow);
    }

    .tile-grid-item-inner {
      display: flex;
      align-items: center;

      >img {
        display: block;
        width: 50px;
        height: 50px;
        min-width: 50px;
      }

      .meta {
        margin-left: 10px;
        line-height: 1.4;

        span {
          display: block;
          font-family: var(--font);

          &:first-child {
            color: var(--dark-text);
            font-family: var(--font-alt);
            font-weight: 600;
            font-size: 1rem;
          }

          &:nth-child(2) {
            display: flex;
            align-items: center;

            span {
              display: inline-block;
              color: var(--light-text);
              font-size: 0.8rem;
              font-weight: 400;
            }

            .icon-separator {
              position: relative;
              font-size: 4px;
              color: var(--light-text);
              padding: 0 6px;
            }
          }
        }
      }

      .dropdown {
        margin-left: auto;
      }
    }
  }
}

.title.is-5 {
  font-size: 1.05rem;
}

.is-dark {
  .tile-grid {
    .tile-grid-item {
      @include vuero-card--dark;
    }
  }

  .tile-grid-v2 {
    .tile-grid-item {
      @include vuero-card--dark;

      &:hover,
      &:focus {
        border-color: var(--primary) !important;
      }
    }
  }
}

.soccer-dashboard {
  .soccer-dashboard-inner {
    .live-match {
      @include vuero-l-card;

      padding: 1.5rem;
      margin-bottom: 1.5rem;

      .head {
        margin-bottom: 1.5rem;

        .league {
          display: flex;
          align-items: center;
          justify-content: space-between;

          .left {
            span {
              display: block;
              font-family: var(--font);

              &:first-child {
                color: var(--dark-text);
              }

              &:nth-child(2) {
                color: var(--light-text);
                font-size: 0.9rem;
              }
            }
          }

          .right {
            .live-block {
              display: inline-flex;
              align-items: center;
              padding: 0.25rem 0.75rem;
              border-radius: 50rem;
              background: var(--danger);
              color: var(--white);
              box-shadow: var(--danger-box-shadow);

              span {
                display: inline-block;
                font-family: var(--font);
                font-size: 0.85rem;
                margin-left: 0.25rem;
              }
            }
          }
        }
      }

      .match {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;

        .left,
        .right {
          text-align: center;

          .team-logo {
            display: block;
            min-width: 50px;
            max-width: 50px;
            text-align: center;
            margin-bottom: 0.25rem;
          }

          .team-name {
            display: block;
            font-family: var(--font);
            font-weight: 500;
          }
        }

        .center {
          display: flex;
          justify-content: center;
          align-items: center;

          .score {
            display: block;
            font-family: var(--font);
            font-weight: 700;
            font-size: 2.20rem;
          }

          .separator {
            position: relative;
            top: -2px;
            display: block;
            padding: 0 0.5rem;
            font-family: var(--font);
            font-weight: 700;
            font-size: 1.75rem;
          }
        }
      }

      .action {
        .v-button {
          border-radius: 0.65rem;
          height: 44px;
        }
      }
    }

    .leagues {
      @include vuero-l-card;

      padding: 2rem;

      .head {
        margin-bottom: 1.5rem;
      }

      .leagues-list {
        .league-item {
          display: flex;
          align-items: center;

          &:not(:last-child) {
            margin-bottom: 1rem;
          }

          .league-logo {
            display: block;
            min-width: 38px;
            max-width: 38px;
          }

          .meta {
            margin-left: 0.5rem;
            line-height: 1.2;

            .league-name {
              display: block;
              font-family: var(--font-alt);
              font-size: 1rem;
              font-weight: 600;
              color: var(--dark-text);
            }

            .league-country {
              display: block;
              font-family: var(--font);
              font-size: 0.9rem;
              color: var(--light-text);
            }
          }

          .end {
            margin-left: auto;
            font-family: var(--font);
            font-size: 0.9rem;
            color: var(--light-text);
          }
        }
      }
    }

    .dashboard-cta {
      background-color: var(--primary);
      padding: 2rem;
      border-radius: 1rem;
      position: relative;
      margin-bottom: 1.5rem;

      .dashboard-cta-title {
        font-family: var(--font-alt);
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--white);
        margin: 0 0 0.25rem;
      }

      .dashboard-cta-text {
        color: var(--white);
        opacity: 0.9;
        font-family: var(--font);
        line-height: 1.7;
        margin-top: 0;
        max-width: 58%;
        margin-bottom: 1rem;
      }

      .dashboard-cta-img {
        width: 40%;
        max-width: 350px;
        position: absolute;
        overflow: hidden;
        height: calc(110%);
        top: -10%;
        right: 2rem;

        img {
          width: 100%;
          height: auto;
        }
      }
    }

    .matches-card {
      @include vuero-l-card;

      padding: 0;
      overflow: hidden;

      .matches-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 3rem;
        padding: 2rem 2rem 0;

        .header-nav {
          display: flex;

          .nav-item {
            .nav-link {
              font-family: var(--font);
              margin-right: 1rem;
              border-bottom: 3px solid transparent;
              padding-bottom: 1rem;
              color: var(--light-text);

              &.is-active {
                color: var(--dark-text);
                border-bottom-color: var(--primary);
              }
            }
          }
        }
      }

      .matches-card-body {
        overflow-x: auto;

        .table {
          width: 100%;

          thead th {
            border: none;
            font-family: var(--font);
            font-size: 0.8rem;
            text-transform: uppercase;
          }

          tr {

            th:first-child,
            td:first-child {
              padding-left: 2rem;
            }

            th:last-child,
            td:last-child {
              padding-right: 2rem;
            }

            td {
              padding-top: 1.5rem;
              padding-bottom: 1.5rem;

              &.score-cell {
                min-width: 300px;
              }

              .match-time-row {
                display: flex;
                align-items: center;

                .match-time {
                  font-family: var(--font);
                  color: var(--light-text);
                  margin-right: 0.75rem;
                }

                .tag {
                  font-family: var(--font);

                  svg {
                    color: var(--warning);
                  }

                  &.is-live {
                    svg {
                      color: var(--danger);
                    }
                  }
                }
              }

              .table-action {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 36px;
                width: 36px;
                border-radius: 50%;
                color: var(--light-text);
                transition: background-color 0.3s;

                &:hover,
                &:focus {
                  background: var(--widget-grey);
                }
              }
            }
          }

          .score {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;

            .score-vertical {
              justify-content: flex-start;
            }

            .score-team {
              text-align: center;

              span {
                display: block;
                font-weight: 500;
                padding-top: 0.25rem;
              }

              img {
                width: 40px;
              }

              &.score-team-vertical {
                display: flex;
                align-items: center;
                flex: 1;

                &:first-child {
                  justify-content: flex-end;
                }

                span {
                  white-space: nowrap;
                  font-size: inherit;
                }

                img {
                  width: 32px;
                  margin: 0 0.5rem;
                }
              }
            }

            .score-result {
              text-align: center;
              width: 100%;
              font-weight: 900;
              font-size: 1.75rem;
              margin: 0;
              letter-spacing: 0.4em;

              &.score-result-not-started {
                color: gray;
              }

              &.score-result-vertical {
                letter-spacing: 0.2em;
                font-size: inherit;
                flex: 0 0 auto;
                width: auto;
              }
            }
          }
        }
      }
    }

    .matches {
      .nav-item {
        &:first-child {
          .nav-link {
            padding-left: 0;
          }
        }

        .nav-link {
          padding-top: 0;
          padding-bottom: 0;
        }
      }
    }
  }
}

.is-dark {
  .soccer-dashboard {
    .soccer-dashboard-inner {

      .live-match,
      .leagues {
        @include vuero-card--dark;

        .head {
          .title {
            color: var(--white) !important;
          }
        }

        .match {

          .left,
          .right {
            .team-name {
              color: var(--white) !important;
            }
          }
        }

        .leagues-list {
          .league-item {
            .meta {
              span:first-child {
                color: var(--white) !important;
              }
            }
          }
        }
      }

      .matches-card {
        @include vuero-card--dark;

        .matches-card-header {
          .header-nav {
            .nav-item {
              .nav-link {
                &.is-active {
                  color: var(--white) !important;
                }
              }
            }
          }
        }
      }

      .matches-card-body {
        .table {
          .score {
            .score-team {
              &.score-team-vertical {
                >span {
                  color: var(--white) !important;
                }
              }
            }
          }

          tr td {
            .table-action {
              &:hover {
                background: var(--dark-sidebar-light-2) !important;
              }
            }
          }
        }
      }
    }
  }
}

.user-grid {
  .columns {
    margin-left: -0.5rem !important;
    margin-right: -0.5rem !important;
    margin-top: -0.5rem !important;
  }

  .column {
    padding: 0.5rem !important;
  }

  .grid-item {
    position: relative;
    @include vuero-s-card;

    text-align: center;

    &:hover,
    &:focus {
      .button-wrap {
        >div {
          a {
            opacity: 1;
            pointer-events: all;
          }
        }
      }
    }

    .dropdown {
      position: absolute;
      top: 10px;
      right: 10px;
      text-align: left;
    }

    >.v-avatar {
      display: block;
      margin: 0 auto 4px;
    }

    h3 {
      font-family: var(--font-alt);
      font-size: 0.85rem;
      font-weight: 600;
      color: var(--dark-text);
    }

    p {
      font-size: 0.7rem;
    }

    .button-wrap {
      margin: 20px 0 0;

      .v-button {
        width: 100%;
        max-width: 120px;
        margin: 0 auto;
      }

      >div {
        margin: 6px 0 0;

        a {
          opacity: 0;
          pointer-events: none;
          color: var(--light-text);
          font-weight: 500;
          font-size: 0.8rem;
          transition: opacity 0.3s, color 0.3s;

          &:hover,
          &:focus {
            color: var(--primary);
          }
        }
      }
    }
  }
}

.is-dark {
  .user-grid {
    .grid-item {
      @include vuero-card--dark;
    }
  }

  .hr-dashboard {
    .block-header {
      background: var(--dark-sidebar);
      box-shadow: none;

      .center {
        border-color: var(--dark-sidebar-light-10);

        .candidates {
          button {
            background: var(--dark-sidebar-light-10);
            border: 1px solid transparent;
            transition: all 0.3s; // transition-all test

            &:hover {
              border-color: var(--primary);

              svg {
                color: var(--primary);
              }
            }
          }
        }
      }
    }

    .feed-settings {
      .button {
        &.is-selected {
          background: var(--primary) !important;
          border-color: var(--primary) !important;
          box-shadow: var(--primary-box-shadow) !important;
          color: var(--white) !important;
        }
      }
    }

    .recent-rookies {
      .user-grid {
        &.user-grid-v4 {
          .grid-item {
            @include vuero-card--dark;
          }
        }
      }
    }
  }
}

.list-view-v3 {
  .list-view-item {
    @include vuero-r-card;

    margin-bottom: 16px;
    padding: 16px;

    .list-view-item-inner {
      display: flex;
      align-items: center;

      >img {
        width: 100%;
        max-width: 60px;
        min-width: 60px;
        max-height: 60px;
        min-height: 60px;
        border-radius: var(--radius-rounded);
        border: 1px solid var(--fade-grey);
      }

      .meta-left {
        margin-left: 16px;

        h3 {
          font-family: var(--font-alt);
          color: var(--dark-text);
          font-weight: 500;
          font-size: 0.85rem;
          line-height: 1;
        }

        >span:not(.tag) {
          font-size: 0.9rem;
          color: var(--light-text);

          svg {
            position: relative;
            top: 1px;
            height: 12px;
            width: 12px;
          }

          .icon-separator {
            position: relative;
            top: -3px;
            font-size: 5px;
            color: var(--light-text);
            padding: 0 8px;
          }

          .iconify {
            margin-right: 0.25rem;
          }
        }
      }

      .meta-right {
        margin-left: auto;
        display: flex;
        align-items: center;
        justify-content: flex-end;

        .buttons {
          margin-bottom: 0;
          margin-right: 10px;
        }
      }
    }
  }
}

.illustration-header-2 {
  display: flex;
  align-items: center;
  padding: 10px;
  border-radius: 16px;
  background: var(--primary-dark-24);
  font-family: var(--font);
  box-shadow: var(--primary-box-shadow);

  .header-image {
    position: relative;
    height: 175px;
    width: 320px;

    img {
      position: absolute;
      top: 0;
      left: -40px;
      display: block;
      pointer-events: none;
    }
  }

  .header-meta {
    margin-left: 0;
    padding-right: 30px;

    h3 {
      color: var(--smoke-white);
      font-family: var(--font-alt);
      font-weight: 700;
      font-size: 1.3rem;
      max-width: 280px;
    }

    p {
      font-weight: 400;
      color: var(--smoke-white-dark-2);
      margin-bottom: 16px;
      max-width: 320px;
    }

    .action-link {
      span {
        font-size: 0.8rem;
        text-transform: uppercase;
        margin-right: 6px;
      }

      i {
        font-size: 12px;
      }
    }
  }
}

.is-dark {
  .list-view-v3 {
    .list-view-item {
      @include vuero-card--dark;

      .list-view-item-inner {
        >img {
          border-color: var(--dark-sidebar-light-12);
        }

        .meta-left {
          h3 {
            color: var(--dark-dark-text) !important;
          }
        }

        .meta-right {
          .buttons {
            .button {
              &:nth-child(2) {
                background: var(--dark-sidebar-light-2);
                border-color: var(--dark-sidebar-light-8);
                color: var(--dark-dark-text);
                transition: color 0.3s, background-color 0.3s, border-color 0.3s,
                  height 0.3s, width 0.3s;

                &:hover,
                &:focus {
                  border-color: var(--primary);
                  color: var(--primary);
                }
              }
            }
          }
        }
      }
    }
  }
}

.search-menu {
  height: 56px;
  white-space: nowrap;
  display: flex;
  flex-shrink: 0;
  align-items: center;
  background-color: whitesmoke;
  border-radius: 8px;
  width: 100%;
  padding-left: 0.75rem;

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
      width: 100%;
      height: 90%;
      display: block;
      font-family: var(--font);
      color: var(--input-color);
      background-color: whitesmoke;
      border: none;
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
    color: var(--button-color);
    cursor: pointer;
    margin-left: auto;
  }
}

.p-tabview .p-tabview-nav li.p-highlight .p-tabview-nav-link {
  background: #ffffff !important;
  border-color: #4CAF50 !important;
  color: #4CAF50 !important;
}

@media only screen and (max-width: 767px) {
  .lifestyle-dashboard-v4 {
    .illustration-header-2 {
      flex-direction: column;
      text-align: center;

      .header-image {
        height: auto;
        width: 100%;

        img {
          position: relative;
          width: 100%;
          max-width: 260px;
          margin: 0 auto;
          top: 0;
          left: 0;
          margin-top: -34px;
        }
      }

      .header-meta {
        padding: 20px;

        >p {
          max-width: 280px;
          margin-left: auto;
          margin-right: auto;
        }
      }
    }

    .writing-stats {
      .writing-stat {
        text-align: center;
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .lifestyle-dashboard-v4 {
    .articles-feed {
      .articles-feed-list {
        .articles-feed-list-inner {
          display: flex;
          flex-wrap: wrap;
          margin-left: -12px;
          margin-right: -12px;

          .articles-feed-item {
            width: calc(50% - 24px);
            margin: 12px;
          }
        }
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
  .lifestyle-dashboard-v4 {
    .updates {
      .updates-list {
        .update-item {
          >span {
            display: none;
          }
        }
      }
    }

    .articles-feed {
      padding: 20px;
    }
  }
}
</style>
