<template>
    <VCard custom="card-green" :class="[isaktif == true ? '' : 'nonaktif']" class="panjang">
        
        <div style="display: flex; flex-direction: row;">
            <div>
                <h3 class="title is-5 mb-4">Info Registrasi </h3>
            </div>
            <div>
                <VTag :color="'light'" style="font-size:9pt; margin-left:10px; margin-top:-5px;" v-if="selectedRegistrasi.nosep == undefined && selectedRegistrasi.objectkelompokpasienlastfk == '2'"><b>SEP Belum Diinput</b></VTag> 
            </div>
        </div>

        <div class="columns is-multiline" v-if="selectedRegistrasi.norec == undefined">
            <div class="column is-12">
                <VField>
                    <VPlaceload height="15px" />
                    <VPlaceload height="20px" class="mt-2" />
                </VField>
            </div>
            <div class="column is-12">
                <VField>
                    <VPlaceload height="15px" />
                    <VPlaceload height="20px" class="mt-2" />
                </VField>
            </div>
        </div>
        <div class="columns is-multiline" v-if="selectedRegistrasi.norec != undefined">
            <div class="column is-4">
                <VField>
                    <VLabelText>No Registrasi</VLabelText>
                    <VLabel>
                        <i aria-hidden="true" class="bulet fas fa-circle"></i>
                        {{ selectedRegistrasi.noregistrasi }}
                    </VLabel>

                </VField>
            </div>

            <div class="column is-4">
                <VField>
                    <VLabelText>Tgl Masuk</VLabelText>
                    <VLabel>
                        <i aria-hidden="true" class="bulet fas fa-circle"></i>
                        {{ selectedRegistrasi.tglregistrasi }}
                    </VLabel>
                </VField>
            </div>
            <div class="column is-4">
                <VField>
                    <VLabelText>Ruangan</VLabelText>
                    <VLabel>
                        <i aria-hidden="true" class="bulet fas fa-circle"></i>
                        {{ selectedRegistrasi.namaruangan }}
                    </VLabel>
                </VField>
            </div>
            <div class="column is-4">
                <VField>
                    <VLabelText>Kelas/Tipe Layanan</VLabelText>
                    <VLabel>
                        <i aria-hidden="true" class="bulet fas fa-circle"></i>
                        {{ selectedRegistrasi.namakelas+ ' / ' + selectedRegistrasi.jenispelayananBaru }}
                    </VLabel>
                </VField>
            </div>
            <div class="column is-4" v-if="selectedRegistrasi.objectkelompokpasienlastfk == '2'">
                <VField>
                    <VLabelText>Tipe Pembiayaan</VLabelText>
                    <VLabel>
                        <i aria-hidden="true" class="bulet fas fa-circle"></i>
                        {{ selectedRegistrasi.kelompokpasien + ' / ' + selectedRegistrasi.nosep }}
                    </VLabel>
                </VField>
            </div>
            <div class="column is-4" v-else>
                <VField>
                    <VLabelText>Tipe Pembiayaan</VLabelText>
                    <VLabel>
                        <i aria-hidden="true" class="bulet fas fa-circle"></i>
                        {{ selectedRegistrasi.kelompokpasien + ' / ' + selectedRegistrasi.namarekanan }}
                    </VLabel>
                </VField>
            </div>
            <div class="column is-4">
                <VField>
                    <VLabelText>DPJP </VLabelText>
                    <VLabel>
                        <i aria-hidden="true" class="bulet fas fa-circle"></i>
                        {{ selectedRegistrasi.dokter }}
                    </VLabel>
                </VField>
            </div>
            <!-- <div class="column is-4">
                <VField>
                    <VLabelText>Tipe Pelayanan </VLabelText>
                    <VLabel>
                        <i aria-hidden="true" class="bulet fas fa-circle"></i>
                        {{ selectedRegistrasi.jenispelayananBaru }}
                    </VLabel>
                </VField>
            </div> -->
        </div>
    </VCard>
</template>

<script setup lang="ts">
// export default {
//     props: {
//         selectedRegistrasi: {}
//     },
//     setup(props: any) {

//     }
// }

const props = withDefaults(
    defineProps<{
        isaktif?: boolean
        selectedRegistrasi?: any
    }>(),
    {
        selectedRegistrasi: {},
        isaktif: true
    }
)

</script>
<style lang="scss">
.bulet {
    color: #A0E3C5;
}

.r-card.card-green.panjang {
    height: 200px !important
}

@media only screen and (max-width: 767px) {
    .r-card.card-green.panjang {
      overflow:auto;
    }
}
</style>
