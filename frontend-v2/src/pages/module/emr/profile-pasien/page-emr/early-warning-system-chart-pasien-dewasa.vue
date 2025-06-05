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
      <div class="columns is-multiline">
        <div class="column is-12">
          <div class="column is-12 p-2">
            <div class="columns is-multiline">
              <div class="column" style="overflow: scroll; ">
                <table class="tg table-fkprj" v-for="(data, index) in input.ews" :key="index">
                  <tbody>
                    <tr class="tr-fkprj">
                      <td class="td-fkprj" colspan="2">
                        Tanggal/Bulan/Waktu
                      </td>
                      <td class="td-fkprj">

                      </td>
                      <td width="500px" class="td-fkprj" v-for="(datatgl, index) in data.tglpemantauan">
                        <VField class="mt-2">
                          <VDatePicker v-model="datatgl.tgl" mode="dateTime" style="width: 100%" trim-weeks
                            :max-date="new Date()" is24hr>
                            <template #default="{ inputValue, inputEvents }">
                              <VField>
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                </VControl>
                              </VField>
                            </template>
                          </VDatePicker>
                        </VField>
                      </td>
                    </tr>
                    <tr class="tr-fkprj">
                      <td class="td-fkprj" rowspan="5" style="text-align: center; font-size: 12pt;">
                        Frekuensi Pernapasan
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt; width: 400px !important;">
                        > 35
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #eb5146;">
                        3
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemFrekuensiA, index) in data.frekuensiA"
                        style="background-color: #eb5146;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemFrekuensiA.skor" :true-value="3" class="p-0"
                              style="text-align: center;background-color: #eb5146;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;">
                        21 - 35
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #bd9c31;">
                        2
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemFrekuensiB, index) in data.frekuensiB"
                        style="background-color: #bd9c31;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemFrekuensiB.skor" :true-value="2" class="p-0"
                              style="text-align: center;background-color: #bd9c31;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;">
                        12 - 20
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #fcfffe;">
                        0
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemFrekuensiC, index) in data.frekuensiC"
                        style="background-color: #fcfffe;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemFrekuensiC.skor" :true-value="0" class="p-0"
                              style="text-align: center;background-color: #fcfffe;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;">
                        9 - 11
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #28751a;">
                        1
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemFrekuensiD, index) in data.frekuensiD"
                        style="background-color: #28751a;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemFrekuensiD.skor" :true-value="1" class="p-0"
                              style="text-align: center;background-color: #28751a;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;">
                        8
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #eb5146;">
                        3
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemFrekuensiE, index) in data.frekuensiE"
                        style="background-color: #eb5146;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemFrekuensiE.skor" :true-value="3" class="p-0"
                              style="text-align: center;background-color: #eb5146;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr class="tr-fkprj">
                      <td class="td-fkprj" rowspan="4" style="text-align: center; font-size: 12pt;">
                        SpO<sub>2</sub>
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt; width: 400px !important;">
                        > 95
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #fcfffe;">
                        0
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemSpo2A, index) in data.spo2A"
                        style="background-color: #fcfffe;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemSpo2A.skor" :true-value="0" class="p-0"
                              style="text-align: center;background-color: #fcfffe;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;">
                        94 - 95
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #28751a;">
                        1
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemSpo2B, index) in data.spo2B"
                        style="background-color: #28751a;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemSpo2B.skor" :true-value="1" class="p-0"
                              style="text-align: center;background-color: #28751a;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;">
                        92 - 93
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #bd9c31;">
                        2
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemSpo2C, index) in data.spo2C"
                        style="background-color: #bd9c31;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemSpo2C.skor" :true-value="2" class="p-0"
                              style="text-align: center;background-color: #bd9c31;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;">
                        &le; 91
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #eb5146;">
                        3
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemSpo2D, index) in data.spo2D"
                        style="background-color: #eb5146;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemSpo2D.skor" :true-value="3" class="p-0"
                              style="text-align: center;background-color: #eb5146;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr class="tr-fkprj">
                      <td class="td-fkprj" style="text-align: center; font-size: 10pt;">
                        % O<sub>2</sub> Inspirasi/suplemen O<sub>2</sub>
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt; width: 400px !important;">
                        Ya
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #bd9c31;">
                        2
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemInspirasiA, index) in data.inspirasiA"
                        style="background-color: #bd9c31;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemInspirasiA.skor" :true-value="2" class="p-0"
                              style="text-align: center;background-color: #bd9c31;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr class="tr-fkprj">
                      <td class="td-fkprj" rowspan="5" style="text-align: center; font-size: 12pt;">
                        Temperatur
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt; width: 400px !important;">
                        > 39
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #bd9c31;">
                        2
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemTemepraturA, index) in data.temepraturA"
                        style="background-color: #bd9c31;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemTemepraturA.skor" :true-value="2" class="p-0"
                              style="text-align: center;background-color: #bd9c31;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;">
                        > 38 - 39
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #28751a;">
                        1
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemTemepraturB, index) in data.temepraturB"
                        style="background-color: #28751a;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemTemepraturB.skor" :true-value="1" class="p-0"
                              style="text-align: center;background-color: #28751a;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;">
                        > 36 - 38
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #fcfffe;">
                        0
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemTemepraturC, index) in data.temepraturC"
                        style="background-color: #fcfffe;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemTemepraturC.skor" :true-value="0" class="p-0"
                              style="text-align: center;background-color: #fcfffe;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;">
                        35 - 36
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #28751a;">
                        1
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemTemepraturD, index) in data.temepraturD"
                        style="background-color: #28751a;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemTemepraturD.skor" :true-value="1" class="p-0"
                              style="text-align: center;background-color: #28751a;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;">
                        &lt; 35
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #eb5146;">
                        3
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemTemepraturE, index) in data.temepraturE"
                        style="background-color: #eb5146;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemTemepraturE.skor" :true-value="3" class="p-0"
                              style="text-align: center;background-color: #eb5146;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr class="tr-fkprj">
                      <td class="td-fkprj" rowspan="6" style="text-align: center; font-size: 12pt;">
                        Tek. Darah Sistolik
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt; width: 400px !important;">
                        &ge; 220
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #eb5146;">
                        3
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemSistolikA, index) in data.sistolikA"
                        style="background-color: #eb5146;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemSistolikA.skor" :true-value="3" class="p-0"
                              style="text-align: center;background-color: #eb5146;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;">
                        > 180
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #bd9c31;">
                        2
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemSistolikB, index) in data.sistolikB"
                        style="background-color: #bd9c31;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemSistolikB.skor" :true-value="2" class="p-0"
                              style="text-align: center;background-color: #bd9c31;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;">
                        > 110 - 180
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #fcfffe;">
                        0
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemSistolikC, index) in data.sistolikC"
                        style="background-color: #fcfffe;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemSistolikC.skor" :true-value="0" class="p-0"
                              style="text-align: center;background-color: #fcfffe;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;">
                        > 100 - 110
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #28751a;">
                        1
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemSistolikD, index) in data.sistolikD"
                        style="background-color: #28751a;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemSistolikD.skor" :true-value="1" class="p-0"
                              style="text-align: center;background-color: #28751a;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;">
                        &ge; 91 - 100
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #bd9c31;">
                        2
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemSistolikE, index) in data.sistolikE"
                        style="background-color: #bd9c31;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemSistolikE.skor" :true-value="2" class="p-0"
                              style="text-align: center;background-color: #bd9c31;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;">
                        &lt; 50 - 90
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #eb5146;">
                        3
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemSistolikF, index) in data.sistolikF"
                        style="background-color: #eb5146;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemSistolikF.skor" :true-value="3" class="p-0"
                              style="text-align: center;background-color: #eb5146;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr class="tr-fkprj">
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;">
                        Tek. Darah Diastolik
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt; width: 400px !important;">
                        > 110
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #eb5146;">
                        3
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemDiastolikA, index) in data.diastolikA"
                        style="background-color: #eb5146;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemDiastolikA.skor" :true-value="3" class="p-0"
                              style="text-align: center;background-color: #eb5146;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr class="tr-fkprj">
                      <td class="td-fkprj" rowspan="6" style="text-align: center; font-size: 12pt;">
                        Denyut Nadi
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt; width: 400px !important;">
                        > 130 - > 140
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #eb5146;">
                        3
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemNadiA, index) in data.nadiA"
                        style="background-color: #eb5146;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemNadiA.skor" :true-value="3" class="p-0"
                              style="text-align: center;background-color: #eb5146;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;">
                        > 110 - 130
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #bd9c31;">
                        2
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemNadiB, index) in data.nadiB"
                        style="background-color: #bd9c31;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemNadiB.skor" :true-value="2" class="p-0"
                              style="text-align: center;background-color: #bd9c31;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;">
                        > 90 - 110
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #28751a;">
                        1
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemNadiC, index) in data.nadiC"
                        style="background-color: #28751a;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemNadiC.skor" :true-value="1" class="p-0"
                              style="text-align: center;background-color: #28751a;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;">
                        > 50 - 90
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #fcfffe;">
                        0
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemNadiD, index) in data.nadiD"
                        style="background-color: #fcfffe;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemNadiD.skor" :true-value="0" class="p-0"
                              style="text-align: center;background-color: #fcfffe;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;">
                        > 40 - 50
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #28751a;">
                        1
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemNadiE, index) in data.nadiE"
                        style="background-color: #28751a;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemNadiE.skor" :true-value="1" class="p-0"
                              style="text-align: center;background-color: #28751a;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;">
                        > 30 - 40
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #eb5146;">
                        3
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemNadiF, index) in data.nadiF"
                        style="background-color: #eb5146;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemNadiF.skor" :true-value="3" class="p-0"
                              style="text-align: center;background-color: #eb5146;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr class="tr-fkprj">
                      <td class="td-fkprj" rowspan="2" style="text-align: center; font-size: 12pt;">
                        Tingkat Kesadaran
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt; width: 400px !important;">
                        Alert
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #fcfffe;">
                        0
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemKesadaranA, index) in data.kesadaranA"
                        style="background-color: #fcfffe;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemKesadaranA.skor" :true-value="0" class="p-0"
                              style="text-align: center;background-color: #fcfffe;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;">
                        VIP/U
                      </td>
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;background-color: #eb5146;">
                        3
                      </td>
                      <td width="250px" class="td-fkprj" v-for="(itemKesadaranB, index) in data.kesadaranB"
                        style="background-color: #eb5146;text-align: center;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="itemKesadaranB.skor" :true-value="3" class="p-0"
                              style="text-align: center;background-color: #eb5146;" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>

                    <tr class="tr-fkprj">
                      <td class="td-fkprj" colspan="3">Gula Darah</td>
                      <td width="250px" class="td-fkprj" v-for="(itemGulaDarah, index) in data.gulaDarah">
                        <VField>
                          <VControl>
                            <VInput style="text-align: center;" v-model="itemGulaDarah.skor" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr class="tr-fkprj">
                      <td class="td-fkprj" colspan="3" style="font-weight: bold;">
                        Skor Total :
                      </td>
                      <!-- <td class="td-fkprj ml-10" colspan="3">Skor Total :</td> -->
                      <td width="250px" class="td-fkprj" v-for="(itemTotalSkor, index) in data.totalSkor">
                        <VField>
                          <VControl>
                            <VInput style="text-align: center;" v-model="itemTotalSkor.skor" class="input"
                              :disabled="true" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr class="tr-fkprj">
                      <td class="td-fkprj" colspan="3">Skor Nyeri</td>
                      <td width="250px" class="td-fkprj" v-for="(itemNyeri, index) in data.nyeri">
                        <VField>
                          <VControl>
                            <VInput style="text-align: center;" v-model="itemNyeri.skor" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr class="tr-fkprj">
                      <td class="td-fkprj" colspan="3">Urine Output</td>
                      <td width="250px" class="td-fkprj" v-for="(itemUrine, index) in data.urine">
                        <VField>
                          <VControl>
                            <VInput style="text-align: center;" v-model="itemUrine.skor" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr class="tr-fkprj">
                      <td class="td-fkprj" colspan="3">Frekuensi Monitoring</td>
                      <td width="250px" class="td-fkprj" v-for="(itemFrekuensi, index) in data.frekuensi">
                        <VField>
                          <VControl>
                            <VInput style="text-align: center;" v-model="itemFrekuensi.skor" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr class="tr-fkprj">
                      <td class="td-fkprj" colspan="3">Rencana Eskalasi</td>
                      <td width="250px" class="td-fkprj" v-for="(itemEskalasi, index) in data.eskalasi">
                        <VField>
                          <VControl>
                            <VInput v-model="itemEskalasi.skor" style="text-align: center;" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr class="tr-fkprj">
                      <td class="td-fkprj" colspan="3">Paraf/Inisial PPA</td>
                      <td width="250px" class="td-fkprj" v-for="(itemInisial, index) in data.inisial">
                        <!-- <VField>
                          <VControl>
                            <VInput v-model="itemInisial.skor" style="text-align: center;" class="input" />
                          </VControl>
                        </VField> -->
                        <TandaTangan :elemenID="'signature_' + [index]" :width="'100'" :height="'100'" class="dek" />
                      </td>
                    </tr>
                  </tbody>
                </table>
                <div class="column is-12">
                  <div class="columns is-multiline px-3">
                    <div class="column is-12">
                      <span>V= Verbal (Suara); P= Pain (Kesakitan); U= Unresponsive (Kurang memberi respons)</span>
                    </div>
                  </div>
                  <div class="columns is-multiline px-3">
                    <div class="column is-12">
                      <span style="font-weight: bold;">Keterangan :</span><br>
                      <span>1. Apabila saturasi dan pasien mendapat suplemen oksigen : Skor 2 :</span><br>
                      <span>2. Terdapat temuan kondisi klinis dengan kesesuaian dengan hasil laboratorium : Skor 3</span><br>
                      <span>3. Periksaan Glukosa Sewaktu (Vena/prifer) periodik (tergantung penilaian klinis)</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </VCard>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr-rehab-perdosi.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import * as EMR from '../page-emr-plugins/pemantauan-tanda-vital'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let dataPasien: any = ref([])

const dataTTD: any = ref([])
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
let ptdEMR = ref(EMR.pemantauanTandaVital())
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value >= 20 })
const isLoading: any = ref(false)
const d_Dokter: any = ref([])
const d_JK: any = ref([])
const isLoadingTT = ref(false);
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref("EarlyWarningSystemChartPasienDewasa") //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const lengthTgl: any = ref(20)
const arrayTanggal = []
for (let i = 1; i <= lengthTgl.value; i++) {
  arrayTanggal.push({ no: i });
}

const input: any = ref({
  ews: [{
    no: 1,
    tglpemantauan: arrayTanggal,
    ptd: ptdEMR.value,
    frekuensiA: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    frekuensiB: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    frekuensiC: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    frekuensiD: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    frekuensiE: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    spo2A: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    spo2B: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    spo2C: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    spo2D: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    inspirasiA: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    temepraturA: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    temepraturB: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    temepraturC: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    temepraturD: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    temepraturE: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    sistolikA: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    sistolikB: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    sistolikC: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    sistolikD: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    sistolikE: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    sistolikF: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    diastolikA: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    nadiA: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    nadiB: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    nadiC: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    nadiD: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    nadiE: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    nadiF: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    kesadaranA: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    kesadaranB: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    totalSkor: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    frekuensi: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    gulaDarah: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    urine: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    nyeri: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    eskalasi: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
    inisial: Array.from({ length: 20 }, (_, j) => ({ no: j + 1 })),
  }],
});
const d_Jenis = [
  { id: 1, label: 'DPJP Utama' },
  { id: 2, label: 'DPJP Tambahan' },
]
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = () => {
  useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0]; //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk;
        }
        dataTTD.value = response[0].ews[0].inisial;

        dataTTD.value.forEach((element: any, index: any) => {
          H.tandaTangan().set("signature_" + [index], element.ttd, 100, 100);
        });
      }
    });
};


const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let tanggalRegistrasi = dataPasien.value.map((item: any) => item.tglregistrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': props.FORM_URL,
    'name_form': props.FORM_NAME,
    'jenis_emr': 'asesmen_medis',
    'data': object,
    'tanggal_registrasi': tanggalRegistrasi
  }
  json.data.ews[0].inisial.forEach((element: any, index: any) => {
    element.ttd = H.tandaTangan().get("signature_" + index);
  });

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

const deepCopyArray = (arr) => {
  return arr.map(obj => ({ ...obj }));
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap,nosip&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const setTandaTangan = async (e: any, i: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signature_" + i, element.ttd)
    }
  })
}

const kembaliKeun = () => {
  window.history.back()
}

const setAutoFill = async () => {
  input.value.tglPembuatan = new Date()
  input.value.ruangrawat = props.registrasi.namaruangan
}
const setAutoFillDiagnosa = async () => {
  await useApi().get(
    `/emr/auto-fill-icd10/${props.registrasi.noregistrasi}`).then((response: any) => {
      let kdnamadiagnosa = []
      for (let i = 0; i < response.length; i++) {
        const element = response[i];
        kdnamadiagnosa.push(response[i].kddiagnosa + '~' + response[i].namadiagnosa)
      }
      input.value.diagnosa = kdnamadiagnosa.join(',');
    })
}
// console.log(JSON.stringify(props.pasien));

const calculateTotalScore = (index) => {
  let totalScore = 0;

  totalScore =
    (input.value.ews[0].frekuensiA[index].skor || 0) +
    (input.value.ews[0].frekuensiB[index].skor || 0) +
    (input.value.ews[0].frekuensiC[index].skor || 0) +
    (input.value.ews[0].frekuensiD[index].skor || 0) +
    (input.value.ews[0].frekuensiE[index].skor || 0) +
    (input.value.ews[0].spo2A[index].skor || 0) +
    (input.value.ews[0].spo2B[index].skor || 0) +
    (input.value.ews[0].spo2C[index].skor || 0) +
    (input.value.ews[0].spo2D[index].skor || 0) +
    (input.value.ews[0].inspirasiA[index].skor || 0) +
    (input.value.ews[0].temepraturA[index].skor || 0) +
    (input.value.ews[0].temepraturB[index].skor || 0) +
    (input.value.ews[0].temepraturC[index].skor || 0) +
    (input.value.ews[0].temepraturD[index].skor || 0) +
    (input.value.ews[0].temepraturE[index].skor || 0) +
    (input.value.ews[0].sistolikA[index].skor || 0) +
    (input.value.ews[0].sistolikB[index].skor || 0) +
    (input.value.ews[0].sistolikC[index].skor || 0) +
    (input.value.ews[0].sistolikD[index].skor || 0) +
    (input.value.ews[0].sistolikE[index].skor || 0) +
    (input.value.ews[0].sistolikF[index].skor || 0) +
    (input.value.ews[0].diastolikA[index].skor || 0) +
    (input.value.ews[0].nadiA[index].skor || 0) +
    (input.value.ews[0].nadiB[index].skor || 0) +
    (input.value.ews[0].nadiC[index].skor || 0) +
    (input.value.ews[0].nadiD[index].skor || 0) +
    (input.value.ews[0].nadiE[index].skor || 0) +
    (input.value.ews[0].nadiF[index].skor || 0) +
    (input.value.ews[0].kesadaranA[index].skor || 0) +
    (input.value.ews[0].kesadaranB[index].skor || 0);

  input.value.ews[0].totalSkor[index].skor = totalScore;
  console.log(totalScore);
};


watch(
  () => input.value.ews[0].frekuensiA.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].frekuensiB.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].frekuensiC.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].frekuensiD.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].frekuensiE.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].spo2A.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].spo2B.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].spo2C.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].spo2D.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].inspirasiA.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].temepraturA.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].temepraturB.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].temepraturC.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].temepraturD.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].temepraturE.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].sistolikA.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].sistolikB.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].sistolikC.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].sistolikD.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].sistolikE.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].sistolikF.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].diastolikA.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].nadiA.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].nadiB.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].nadiC.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].nadiD.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].nadiE.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].nadiF.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].kesadaranA.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.ews[0].kesadaranB.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);


loadRiwayat()
setView()
setAutoFill()
setAutoFillDiagnosa()
</script>


<style lang="scss">
.table-fkprj {
  width: 320% !important;
  border-collapse: collapse !important;
}

.table-responsive {
  overflow-x: scroll;
}

.table-fkprj,
.tr-fkprj,
.th-fkprj,
.td-fkprj {
  border: 1.6px solid black !important;
}

.th-fkprj,
.td-fkprj {
  padding: 8px !important;
}

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 200%;
}

.tg td {
  // border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  // overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg th {
  // border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  // overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg .tg-0lax {
  text-align: left;
  vertical-align: middle
}
</style>
<style lang="scss">
table.triase {
  border-collapse: collapse;
  width: 100%;
}

table.triase,
th,
.triase td {
  border: 1px solid black;
}

table.triase,
th {
  // text-align: center;

}

.bg-green {
  background-color: var(--primary);
}

.bg-warning {
  background-color: var(--warning);
}

.bg-danger {
  background-color: var(--danger);
}

.triase th,
td {
  padding: 8px;
  vertical-align: top !important;
}

.yellow-background {
  background-color: yellow;
}

.green-background {
  background-color: green;
}

.orange-background {
  background-color: orange;
}

.red-background {
  background-color: red;
}
</style>