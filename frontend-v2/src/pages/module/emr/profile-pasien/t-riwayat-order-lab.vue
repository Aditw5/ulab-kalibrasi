<script setup lang="ts">

const emit = defineEmits<{
    (e: 'editItems', value: any): void,
    (e: 'hapusItems', value: any): void,
    (e: 'hasilItems', value: any): void,

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
  <section>
    <div class="update-item is-dark-bordered-12 " style="display: block;" v-if="props.items.length == 0">
        <div class="search-results-wrapper">
            <div class="search-results-body ">
                <!--Search Placeholder -->
                <div class="page-placeholder">
                    <div class="placeholder-content">
                        <img class="light-image" style=" max-width: 340px;"
                            src="/@src/assets/illustrations/placeholders/search-7.svg" alt="" />
                        <img class="dark-image" style=" max-width: 340px;"
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
    <div class="list-widget" :class="[props.straight && 'is-straight']" v-if="props.items.length > 0">
        <!-- <div class="widget-head"> -->
            <!-- <h3 class="dark-inverted">{{ props.title }}</h3> -->
            <!-- <VDropdown icon="feather:more-vertical" dots right spaced>
                <template #content>
                    <a href="#" class="dropdown-item is-media">
                        <div class="icon">
                            <i aria-hidden="true" class="lnil lnil-reload"></i>
                        </div>
                        <div class="meta">
                            <span>Reload</span>
                            <span>Reload Data</span>
                        </div>
                    </a>

                    <hr class="dropdown-divider" />
                    <a href="#" class="dropdown-item is-media">
                        <div class="icon">
                            <i aria-hidden="true" class="lnil lnil-trash-can-alt"></i>
                        </div>
                        <div class="meta">
                            <span>Remove</span>
                            <span>Remove from view</span>
                        </div>
                    </a>
                </template>
            </VDropdown> -->
        <!-- </div> -->

        <!-- <div class="inner-list">
        <div class="icon-timeline"> -->
        <div class="timeline-wrapper" v-if="props.items.length > 0">
            <div class="timeline-header"></div>
            <div class="timeline-wrapper-inner pt-0">
                <div class="timeline-container">
                    <div class="timeline-item is-unread " v-for="(item, index)  in props.items" :key="item.norec">
                        <div class="date">
                            <span>{{ item.tglorder }}</span>
                        </div>
                        <div :class="'dot is-' + item.color"></div>
                        <!-- <div class="collapse-icon is-clickable"
                                    @click="isDetail[index] = true"
                                        v-if="!isDetail[index]">
                                        <VIcon icon="feather:chevron-down" />
                                    </div> -->
                        <!-- <div class="collapse-icon  is-clickable " open @click="isDetail[index] = false"
                                        v-else>
                                        <VIcon icon="feather:chevron-up" />
                                    </div> -->
                        <div class="content-wrap is-grey">
                            <div class="content-box ">
                                <div class="status"></div>
                                <VIconBox size="small" :color="'maroon'" rounded>
                                    <i :class="item.icon" aria-hidden="true"></i>
                                </VIconBox>
                                <!-- <VAvatar :picture="'/images/ruang/' + (item.nocounter != null ? item.nocounter : 1) + '.png'" /> -->
                                <div class="box-text" style="width:70%">
                                    <div class="meta-text">
                                        <table class="tb-order">
                                            <tr>
                                                <td>No Order</td>
                                                <td>:</td>
                                                <td class="text-value">{{ item.noorder }} </td>
                                            </tr>
                                            <tr>
                                                <td>Ruangan Asal </td>
                                                <td>:</td>
                                                <td class="text-value">{{ item.ruanganasal }} </td>
                                            </tr>
                                            <tr>
                                                <td>Dokter</td>
                                                <td>:</td>
                                                <td class="text-value">{{ item.dokter }} </td>
                                            </tr>
                                        </table>
                                        <!-- <p> No Order : <span>{{ item.noorder }}</span> </p>
                                        <p> Ruangan Asal : <span>{{ item.ruanganasal }}</span> </p>
                                        <p> Dokter : <span>{{ item.dokter }}</span> </p> -->
                                        <div class="is-divider p-0 m-2"></div>
                                        <div v-for="(detail, index2) in  item.details">
                                            <p><span class="status mt-1" style="position: absolute;"></span> <span
                                                    class="ml-3"> {{ detail.namaproduk }}</span></p>
                                        </div>
                                        <!-- <VTag :label="item.hargasatuan" color="purple" rounded /> -->
                                        <!-- <br />
                                        <p>No Order : {{ item.noorder }}</p> -->
                                        <!-- <p>Cito :
                                            <span v-html="item.icon"></span>
                                            <span style="font-size:0.8rem" class="ml-2">
                                                {{
                                                    item.jasacito
                                                }}</span>
                                        </p> -->
                                    </div>
                                </div>
                                <div class="box-end" style="width:30%">
                                    <div class="columns is-multiline">
                                        <div class="column is-12 mt-3">
                                            <div class="status is-pulled-right mt-2 ml-2"></div>
                                            <VTag :label="item.status" :color="item.color_status" rounded
                                                class="is-pulled-right" />
                                        </div>
                                        <div class="column is-12 ">
                                            <!-- <VDropdown icon="feather:more-vertical" dots right spaced  class="mr-2 is-pulled-right">
                                                <template #content>
                                                    <a href="#" class="dropdown-item is-media">
                                                        <div class="icon">
                                                            <i aria-hidden="true" class="lnil lnil-reload"></i>
                                                        </div>
                                                        <div class="meta">
                                                            <span>Reload</span>
                                                            <span>Reload Data</span>
                                                        </div>
                                                    </a>

                                                    <hr class="dropdown-divider" />
                                                    <a href="#" class="dropdown-item is-media">
                                                        <div class="icon">
                                                            <i aria-hidden="true" class="lnil lnil-trash-can-alt"></i>
                                                        </div>
                                                        <div class="meta">
                                                            <span>Remove</span>
                                                            <span>Remove from view</span>
                                                        </div>
                                                    </a>
                                                </template>
                                            </VDropdown>  -->
                                            <VIconButton icon="feather:file-text" @click="$emit('hasilItems', item)"
                                                raised circle class="mr-2 is-pulled-right" v-tooltip.bubble="'Hasil'">
                                            </VIconButton>
                                            <VIconButton icon="feather:trash" @click="$emit('hapusItems', item)"
                                                color="danger" raised circle class="mr-2 is-pulled-right" v-tooltip.bubble="'Hapus'">
                                            </VIconButton>
                                            <VIconButton icon="feather:edit" @click="$emit('editItems', item)"
                                                color="info" raised circle class="mr-2 is-pulled-right" v-tooltip.bubble="'Edit'">
                                            </VIconButton>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--   <VCard custom="card-green" v-if="isDetail[index]">
                                       <div class="columns is-multiline"
                                            v-for="(itemsDet, index2)  in items.pelayananpetugas" :key="index2">
                                            <div class="column is-12">
                                                <VField>
                                                    <VLabelText>Jenis Petugas</VLabelText>
                                                    <VLabel>{{ itemsDet.jenispetugaspe }} </VLabel>
                                                </VField>
                                            </div>
                                            <div class="column is-12">
                                                <VField>
                                                    <VLabelText>Pegawai</VLabelText>
                                                    <VLabel>{{ multiSelectArrayToString(itemsDet.listpegawai) }}
                                                    </VLabel>
                                                </VField>
                                            </div>
                                        </div>
                                    </VCard> -->
                    </div>
                </div>
                <!-- <div class="load-more-wrap has-text-centered p-1 mb-3">
                                <div class="columns is-multiline">
                                    <div class="column is-4 is-offset-8">
                                        <VCard>
                                            <div class="columns is-multiline">
                                                <div class="column is-3 mt-1">
                                                    <VField>
                                                        <VLabel class="fs-total-label">TOTAL </VLabel>
                                                    </VField>
                                                </div>
                                                <div class="column is-9">
                                                    <VField>
                                                        <VLabel class="fs-total">{{items.totalHarga

                                                        }} </VLabel>
                                                    </VField>
                                                </div>
                                            </div>
                                        </VCard>
                                    </div>
                                </div>
                            </div> -->
            </div>
        </div>



        <!-- <div v-for="item in props.items" :key="item.id" class="timeline-item">
                <div class="timeline-icon"
                    :class="[props.squared && 'is-squared', props.colored && 'is-' + item.color]">
                    <img v-if="item.picture" class="avatar" :src="item.picture" alt=""
                        @error.once="(event) => onceImageErrored(event, '150x150')" />
                    <i v-else aria-hidden="true" class="iconify" :data-icon="item.icon"></i>
                </div>
                <div class="timeline-content">
                    <table class="is-fullwidth">
                        <tr>
                            <td style="width:25%" rowspan="2">
                                <p>{{ item.namaproduk }}</p>
                                <span>{{ item.tglorder }}</span>
                            </td>
                            <td style="width:10%">
                                <span class="td-label">No Order</span>
                            </td>
                            <td style="width:10%" v-if="item.tgloperasi">
                                <span class="td-label">Rencana Operasi</span>
                            </td>
                            <td style="width:15%">
                                <span class="td-label">Ruangan Asal</span>
                            </td>
                            <td style="width:15%">
                                <span class="td-label">Dokter </span>
                            </td>
                            <td style="width:10%">
                                <span class="td-label">Status </span>
                            </td>
                            <td style="width:10%" rowspan="2">
                                <VIconButton icon="feather:edit" @click="$emit('editItems',item)" color="warning"
                                    raised circle class="mr-2">
                                </VIconButton>
                                <VIconButton icon="feather:trash" @click="$emit('hapusItems',item)" color="danger"
                                    raised circle>
                                </VIconButton>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>{{ item.noorder }}</span>
                            </td>
                            <td v-if="item.tgloperasi">
                                <span>{{ item.tgloperasi }}</span>
                            </td>
                            <td>
                                <span>{{ item.ruanganasal }}</span>
                            </td>
                            <td>
                                <span>{{ item.dokter }}</span>
                            </td>
                            <td>
                                <VTag :color="item.color_status" :label="item.status" />


                            </td>

                        </tr>

                    </table>
                </div>

            </div> -->
        <!-- </div>
    </div> -->
    </div>
  </section>
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

.tb-order {
    color: var(--light-text-dark-10);
    font-family: var(--font);
    font-weight: 300;

    .text-value {
        font-family: var(--font-alt);
        color: var(--dark-text);
        font-weight: 600;
    }

    td {
        padding: 0 3px 0 0;
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

.timeline-wrapper .timeline-wrapper-inner .timeline-container .timeline-item::before {
    display: none;
}
</style>
