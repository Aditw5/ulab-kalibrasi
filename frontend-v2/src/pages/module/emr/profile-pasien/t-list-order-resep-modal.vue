<script setup lang="ts">
import Toolbar from 'primevue/toolbar';
import { onceImageErrored } from '/@src/utils/via-placeholder'
// export default {
//     emits: ['editItems', 'hapusItems'],

// }
import * as H from '/@src/utils/appHelper'
const emit = defineEmits<{
    (e: 'editItems', value: any): void,
    (e: 'hapusItems', value: any): void,
}>()
const props = withDefaults(
    defineProps<{
        title?: string
        straight?: boolean
        items?: any[]
        squared?: boolean
        colored?: boolean
    }>(),
    {
        title: 'List Widget',
        items: () => [],
    }
)
</script>
    
<template>
    <div>
        <div class="update-item is-dark-bordered-12 " style="display: block;" v-if="props.items.length == 0">
            <div class="search-results-wrapper">
                <div class="search-results-body ">
                    <!--Search Placeholder -->
                    <div class="page-placeholder" style="min-height: 263px;">
                        <div class="placeholder-content">
                            <img class="light-image" style=" max-width: 100px;"
                                src="/images/simrs/not-found-emr.png" alt="" />
                            <img class="dark-image" style=" max-width: 100px;"
                                src="/@src/assets/illustrations/placeholders/search-7-dark.svg" alt="" />
                            <h3>Data belum ada.</h3>
                            <p class="is-larger">
                                Sepertinya data ini belum di inputkan,
                                silahkan melakukan penginputan terlebih
                                dahulu.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="height:300px;overflow: auto;" class="list-widget" :class="[props.straight && 'is-straight']" v-if="props.items.length > 0">
            <table class="tg" style="border: 1px solid var(--fade-grey-dark-2) !important; border-spacing: 2px;padding: 10px 2px">
                <thead>
                    <tr>
                    <!-- <td class="tg-0lax text-center">No</td> -->
                        <td style="border: 1px solid var(--fade-grey-dark-2) !important; border-spacing: 2px;padding: 10px 2px"  class="tg-0lax text-center">Produk</td>
                        <td style="border: 1px solid var(--fade-grey-dark-2) !important; border-spacing: 2px;padding: 10px 2px"  class="tg-0lax text-center">Aturan Pakai</td>
                        <td style="border: 1px solid var(--fade-grey-dark-2) !important; border-spacing: 2px;padding: 10px 2px"  class="tg-0lax text-center">Qty</td>
                        <td style="border: 1px solid var(--fade-grey-dark-2) !important; border-spacing: 2px;padding: 10px 2px"  class="tg-0lax text-center">Satuan</td>
                        <td style="border: 1px solid var(--fade-grey-dark-2) !important; border-spacing: 2px;padding: 10px 2px"  class="tg-0lax text-center">Harga</td>
                        <td style="border: 1px solid var(--fade-grey-dark-2) !important; border-spacing: 2px;padding: 10px 2px"  class="tg-0lax text-center">Total</td>
                        <td style="border: 1px solid var(--fade-grey-dark-2) !important; border-spacing: 2px;padding: 10px 2px"  class="tg-0lax text-center">#</td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in props.items" :key="item.id">
                        <td style="border: 1px solid var(--fade-grey-dark-2) !important; border-spacing: 2px;padding: 10px 2px" width="40%">
                            <p>{{ item.namaproduk }}</p>      
                            <span>{{ 'R/ke : ' + item.rke }}</span>
                            <p class="p-bawah">{{ item.jeniskemasan }}</p>      
                        </td>
                        <td class="text-center" style="border: 1px solid var(--fade-grey-dark-2) !important; border-spacing: 2px;padding: 10px 2px" width="5%">
                            <VTag :color="'info'" :label="item.aturanpakai" />          
                        </td>
                        <td class="text-center" style="border: 1px solid var(--fade-grey-dark-2) !important; border-spacing: 2px;padding: 10px 2px" width="5%">
                            <span>{{ item.jumlah }}</span>          
                        </td>
                        <td class="text-center" style="border: 1px solid var(--fade-grey-dark-2) !important; border-spacing: 2px;padding: 10px 2px" width="10%">
                            <span>{{ item.satuanstandar }}</span>        
                        </td>
                        <td style="border: 1px solid var(--fade-grey-dark-2) !important; border-spacing: 2px;padding: 10px 2px" width="15%">
                            <span>{{ H.formatRp(item.hargasatuan, 'Rp.') }}</span>        
                        </td>
                        <td style="border: 1px solid var(--fade-grey-dark-2) !important; border-spacing: 2px;padding: 10px 2px" width="15%">
                            <span>{{ H.formatRp(item.total, 'Rp.') }}</span>       
                        </td>
                        <td class="text-center" style="border: 1px solid var(--fade-grey-dark-2) !important; border-spacing: 2px;padding: 10px 2px" width="5%">
                            <VIconButton icon="feather:edit" @click="$emit('editItems', item)"
                                color="warning" raised circle class="mr-2">
                            </VIconButton>
                            <VIconButton icon="feather:trash" @click="$emit('hapusItems', item)"
                                color="danger" raised circle>
                            </VIconButton>     
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- <div class="inner-list">
                <div class="icon-timeline">
                    <div v-for="item in props.items" :key="item.id" class="timeline-item">
                        <div class="timeline-icon"
                            :class="[props.squared && 'is-squared', props.colored && 'is-' + item.color]">
                            <i :class="item.icon" aria-hidden="true"></i>
                            <i aria-hidden="true" class="iconify" :data-icon="item.icon"></i>
                        </div>
                        <div class="timeline-content">
                            <table class="is-fullwidth">
                                <tr>
                                    <td style="width:25%" rowspan="2">
                                        <p>{{ item.namaproduk }}</p>
                                        <span>{{ 'R/ke : ' + item.rke }}</span>
                                        <p class="p-bawah">{{ item.jeniskemasan }}</p>
                                    </td>
                                    <td style="width:10%">
                                        <span class="td-label">Aturan Pakai </span>
                                    </td>
                                    <td style="width:10%">
                                        <span class="td-label">Qty</span>
                                    </td>
                                    <td style="width:10%">
                                        <span class="td-label">Satuan</span>
                                    </td>
                                    <td style="width:10%">
                                        <span class="td-label">Harga</span>
                                    </td>
                                    <td style="width:10%">
                                        <span class="td-label">Total</span>
                                    </td>
                                    <td style="width:10%">
                                        <span class="td-label">Dosis</span>
                                    </td>


                                    <td style="width:10%" rowspan="2">
                                        <VIconButton icon="feather:edit" @click="$emit('editItems', item)"
                                            color="warning" raised circle class="mr-2">
                                        </VIconButton>
                                        <VIconButton icon="feather:trash" @click="$emit('hapusItems', item)"
                                            color="danger" raised circle>
                                        </VIconButton>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <VTag :color="'info'" :label="item.aturanpakai" />

                                        <span>{{ item.aturanpakai }}</span>
                                    </td>
                                    <td>
                                        <span>{{ item.jumlah }}</span>
                                    </td>
                                    <td>
                                        <span>{{ item.satuanstandar }}</span>
                                    </td>
                                    <td>
                                        <span>{{ H.formatRp(item.hargasatuan, 'Rp.') }}</span>
                                    </td>
                                    <td>
                                        <span>{{ H.formatRp(item.total, 'Rp.') }}</span>
                                    </td>
                                    <td>
                                        <span>{{ item.jmldosis }}</span>
                                    </td>



                                </tr>

                            </table>
                        </div>

                    </div>
                </div>
            </div> -->
        </div>
    </div>
</template>
    
<style lang="scss">
@import '/@src/scss/abstracts/all';


.is-dark .td-label {

    color: var(--dark-dark-text);
}

.list-widget {
    @include vuero-l-card;

    padding: 30px;

    &:not(:last-child) {
        margin-bottom: 1.5rem;
    }

    &.is-straight {
        @include vuero-s-card;
    }

    .widget-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 32px;
        margin-bottom: 10px;

        h3 {
            color: var(--dark-text);
            font-size: 1.1rem;
            font-weight: 500;
        }
    }

    .inner-list {
        padding: 10px 0;

        .inner-list-item {
            +.inner-list-item {
                margin-top: 24px;
            }
        }
    }
}

.is-dark {
    .list-widget {
        @include vuero-card--dark;
    }
}

.list-widget {
    .icon-timeline {
        .timeline-item {
            position: relative;
            display: flex;
            padding-bottom: 30px;

            &::after {
                content: '';
                position: absolute;
                top: 36px;
                left: 18px;
                width: 1px;
                height: calc(100% - 36px);
                border-left: 1px solid var(--fade-grey-dark-3);
            }

            .timeline-icon {
                position: relative;
                // height: 36px;
                height: auto;
                width: 36px;
                display: flex;
                justify-content: center;
                align-items: center;
                background: var(--white);
                border: 1px solid var(--fade-grey-dark-3);
                border-radius: var(--radius-rounded);
                color: var(--light-text);
                box-shadow: var(--light-box-shadow);

                &::after {
                    content: '';
                    position: absolute;
                    top: 17px;
                    left: 40px;
                    width: 20px;
                    height: 1px;
                    border-top: 1px solid var(--fade-grey-dark-3);
                }

                &.is-squared {
                    border-radius: 10px;

                    img {
                        border-radius: 10px;
                    }
                }

                &.is-primary {
                    background: var(--primary);
                    border-color: var(--primary);
                    box-shadow: var(--primary-box-shadow);

                    svg {
                        color: var(--smoke-white);
                    }
                }

                &.is-info {
                    background: var(--info);
                    border-color: var(--info);
                    box-shadow: var(--info-box-shadow);

                    svg {
                        color: var(--smoke-white);
                    }
                }

                &.is-success {
                    background: var(--success);
                    border-color: var(--success);
                    box-shadow: var(--success-box-shadow);

                    svg {
                        color: var(--smoke-white);
                    }
                }

                &.is-orange {
                    background: var(--orange);
                    border-color: var(--orange);
                    box-shadow: var(--orange-box-shadow);

                    svg {
                        color: var(--smoke-white);
                    }
                }

                &.is-yellow {
                    background: var(--yellow);
                    border-color: var(--yellow);

                    svg {
                        color: var(--smoke-white);
                    }
                }

                img {
                    display: block;
                    height: 28px;
                    width: 28px;
                    border-radius: var(--radius-rounded);
                }

                svg {
                    height: 16px;
                    width: 16px;
                    stroke-width: 1.6px;
                }
            }

            .timeline-content {
                margin-left: 34px;
                line-height: 1.2;
                width: 100%;

                span {
                    font-size: 0.85rem;
                    color: var(--light-text);

                }

                .is-danger {
                    span.icon {
                        color: var(--danger--color-invert);
                    }

                }

                .is-warning {
                    span.icon {
                        color: var(--warning--color-invert);
                    }

                }

                span.td-label {
                    color: var(--dark-text);
                }

                p {
                    font-family: var(--font-alt);
                    font-size: 0.95rem;
                    font-weight: 500;
                    color: var(--dark-text);
                    width: 220px;
                    white-space: nowrap;
                    overflow: hidden !important;
                    text-overflow: ellipsis;
                }

                p.p-bawah {
                    font-size: 0.85rem;
                    font-style: inherit;
                    font-weight: inherit;
                    color: var(--light-text);
                }
            }
        }
    }
}

.is-dark {
    .list-widget {
        .icon-timeline {
            .timeline-item {
                &::after {
                    border-color: var(--dark-sidebar-light-12) !important;
                }

                .timeline-icon:not(.is-primary):not(.is-info):not(.is-success):not(.is-orange):not(.is-yellow) {
                    background: var(--dark-sidebar-light-3) !important;
                    border-color: var(--dark-sidebar-light-12) !important;
                }

                .timeline-icon {
                    &::after {
                        border-color: var(--dark-sidebar-light-12) !important;
                    }

                    &.is-primary {
                        background: var(--primary);
                        border-color: var(--primary);
                        box-shadow: var(--primary-box-shadow);

                        svg {
                            color: var(--smoke-white);
                        }
                    }
                }

                .timeline-content {
                    p {
                        color: var(--dark-dark-text);
                    }
                }
            }
        }
    }
}
</style>
    