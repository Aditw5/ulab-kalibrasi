export function anamnesa() : any {
    return  [
        {
            "title": "Keluhan Utama",
            "model": "keluhanUtama"
        },
        {
            "title": "Riwayat Penyakit Sekarang",
            "model": "riwayatPenyakitSekarang"
        },
        {
            "title": "Riwayat Penyakit Dahulu",
            "model": "riwayatPenyakitDahulu"
        }
    ]
}

export function faktorResJantung() : any {
    return [
        {
            "title": "Hipertensi",
            "detail": [
                {
                    "model": "hipertensi",
                    "label": "Ya",
                },
                {
                    "model": "hiperKontrol",
                    "label": "Terkontrol",
                },
                {
                    "model": "hiperKontrol",
                    "label": "Tidak Terkontrol",
                },
                {
                    "model": "hipertensi",
                    "label": "Tidak",
                },
            ]
        },
        {
            "title": "Diabetes Melitus",
            "detail" : [
                {
                    "model": "diabetes",
                    "label": "Ya",
                },
                {
                    "model": "diabetesKontrol",
                    "label": "Terkontrol",
                },
                {
                    "model": "diabetesKontrol",
                    "label": "Tidak Terkontrol",
                },
                {
                    "model": "diabetes",
                    "label": "Tidak",
                },
            ]
        },
        {
            "title": "Dyslipidemia (kelainan kolestrol darah)",
            "detail" : [
                {
                    "model": "dyslipidemia",
                    "label": "Ya",
                },
                {
                    "model": "dyslipidemiaKontrol",
                    "label": "Terkontrol",
                },
                {
                    "model": "dyslipidemiaKontrol",
                    "label": "Tidak Terkontrol",
                },
                {
                    "model": "dyslipidemia",
                    "label": "Tidak",
                },
            ]
        },
        {
            "title": "Riwayat serangan jantung dini pada orang tua (pria <55 tahun atau wanita <65 tahun)",
            "detail": [
                {
                    "model" : "riwayatJantung",
                    "label" : "Ya",
                },
                {
                    "model" : "riwayatJantung",
                    "label" : "Tidak",
                },
            ]
        },
    ]
}
export function keadaanUmum_1() : any {
    return [
        {
            "title": "1. Keadaan Umum",
            "value": ["Baik", "Sakit Ringan", "Sakit Sedang", "Sakit Berat"],
            "model": 'keadaanUmum'
        }
    ]
}
export function pemeriksaanFisik() : any {
    return [
        {
            "title": "2. Tanda Vital",
            "value": [
                {
                    "subTitle": "Tekanan Darah",
                    "model": "tekananDarah",
                },
                {
                    "subTitle": "Frekuensi Nadi",
                    "model": "frekuensiNadi",
                },
                {
                    "subTitle": "Suhu",
                    "model": "suhu",
                },
                {
                    "subTitle": "Frekuensi Nafas",
                    "model": "frekuensiNafas",
                },
                {
                    "subTitle": "Skor Nyeri",
                    "model": "skorNyeri",
                },
            ]
        },
        {
            "title": "3. Antropometri",
            "value": [
                {
                    "subTitle": "Berat Badan",
                    "model": "beratBadan",
                },
                {
                    "subTitle": "Tinggi Badan",
                    "model": "tinggiBadan",
                },
                {
                    "subTitle": "Lingkar Perut",
                    "model": "lingkarPerut",
                },
                {
                    "subTitle": "IMT",
                    "model": "IMT",
                },
            ]
        },
    ]
}
export function kesadaran() : any {
    return [
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
                    "poin": "6"
                },
                {
                    "model": "kesadaranM",
                    "poin": "5"
                },
                {
                    "model": "kesadaranM",
                    "poin": "4"
                },
                {
                    "model": "kesadaranM",
                    "poin": "3"
                },
                {
                    "model": "kesadaranM",
                    "poin": "2"
                },
                {
                    "model": "kesadaranM",
                    "poin": "1"
                },
            ]
        },
        {
            "no": 3,
            "parameter": "v",
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
    ]
}
export function dscRangeKesadaran() : any {
    return [
        {
            "des": "CMC (14-15)",
            "model": "rangeKesadaran",
            "value": {
                "keterangan": "CMC (14-15)",
                "poin": 15
            },
        },
        {
            "des": "Apatis (12-13)",
            "model": "rangeKesadaran",
            "value": {
                "keterangan": "Apatis (12-13)",
                "poin": 13
            },
        },
        {
            "des": "Somnolen (10-11)",
            "model": "rangeKesadaran",
            "value": {
                "keterangan": "Somnolen (10-11)",
                "poin": 11
            },
        },
        {
            "des": "Delirium (7-9)",
            "model": "rangeKesadaran",
            "value": {
                "keterangan": "Delirium (7-9)",
                "poin": 9
            },
        },
        {
            "des": "Stupar (4-6)",
            "model": "rangeKesadaran",
            "value": {
                "keterangan": "Stupar (4-6)",
                "poin": 6
            },
        },
        {
            "des": "Koma ( <= 3)",
            "model": "rangeKesadaran",
            "value": {
                "keterangan": "Koma ( <= 3)",
                "poin": 3
            },
        },
    ]
}
export function keadaanUmum_2() : any {
    return [
        {
            "title": "5. Mata",
            "value": [
                {
                    "subTitle": "Konjungtiva",
                    "item": [
                        {
                            "label":"Normal",
                            "model" : "mataKonjungtiva",
                        },
                        {
                            "label":"Anemis",
                            "model" : "mataKonjungtiva",
                        },
                        {
                            "label":"Lainnya",
                            "model" : "mataKonjungtiva",
                        },
                    ],
                    "optional": [{
                        'model': "ketKonjungtiva"
                    }]
                },
                {
                    "subTitle": "Sklera",
                    "item": [
                        {
                            "label":"Normal",
                            "model" : "mataSklera",
                        },
                        {
                            "label":"Ikterik",
                            "model" : "mataSklera",
                        },
                        {
                            "label":"Lainnya",
                            "model" : "mataSklera",
                        },
                    ],
                    "optional": [{
                        'model': "ketSklera"
                    }]
                },
            ]
        },
        {
            "title": "6. Hidung",
            "value": [
                {
                    "subTitle": "",
                    "item": [
                        {
                            "label" : "Normal",
                            "model" : "hidung",
                        },
                        {
                            "label" : "Nafas Cuping Hidung",
                            "model" : "hidung",
                        },
                        {
                            "label" : "Lainnya",
                            "model" : "hidung",
                        },
                    ],
                    "optional": [{
                        'model': "ketHidung"
                    }]
                },
            ]
        },
        {
            "title": "7. Bibir",
            "value": [
                {
                    "subTitle": "",
                    "item": [
                        {
                            "label" : "Normal",
                            "model" : "bibir",
                        },
                        {
                            "label" : "Sianosis",
                            "model" : "bibir",
                        },
                        {
                            "label" : "Lainnya",
                            "model" : "bibir",
                        },
                    ],
                    "optional": [{
                        'model': "ketBibir"
                    }]
                },
            ]
        },
        {
            "title": "8. Thorax",
            "value": [
                {
                    "subTitle": "",
                    "model": "thorax",
                    "item": [
                        {
                            "label" : "Simetris",
                            "model" : "thorax"
                        },
                        {
                            "label" : "Tidak Simetris",
                            "model" : "thorax"
                        },
                        {
                            "label" : "Barel Chest",
                            "model" : "thorax"
                        },
                        {
                            "label" : "Pigeon Chest",
                            "model" : "thorax"
                        },
                        {
                            "label" : "Picuts Excavatum",
                            "model" : "thorax"
                        },
                        {
                            "label" : "Retraksi Dinding Dada",
                            "model" : "thorax"
                        },
                    ],
                    "optional": [{
                        'model': "ketThorax"
                    }]
                },
            ]
        },
        {
            "title": "9. Cor",
            "value": [
                {
                    "subTitle": "Inspeksi",
                    "item":[
                        {
                            "label" : "Ictus cordis tidak tampak",
                            "model" : "corInspeksi"
                        },
                        {
                            "label" : "Ictus cordis tampak",
                            "model" : "corInspeksi"
                        },
                        {
                            "label" : "Lainnya",
                            "model" : "corInspeksi"
                        },
                    ],
                    "optional": [{
                        'model': "ketCorInspeksi"
                    }]
                },
                {
                    "subTitle": "Palpasi",
                    "model": "palpasi",
                    "item":[
                        {
                            "label" : "Ictus cordis tidak teraba",
                            "model" : "corPalpasi"
                        },
                        {
                            "label" : "Ictus cordis teraba",
                            "model" : "corPalpasi"
                        },
                        {
                            "label" : "Lainnya",
                            "model" : "corPalpasi"
                        },
                    ],
                    "optional": [{
                        'model': "ketCorPalpasi"
                    }]
                },
                {
                    "subTitle": "Perkusi",
                    "model": "corPerkusi",
                    "item": "",
                    "optional": [{
                        'model': "ketCorPerkusi"
                    }]
                },
                {
                    "subTitle": "Auskultasi",
                    "item": [
                        {
                            "label" : "S1 dan S2 Reguler",
                            "model" : "corAuskultasi",
                        },
                        {
                            "label" : "Murmur",
                            "model" : "corAuskultasi",
                        },
                        {
                            "label" : "Lainnya",
                            "model" : "corAuskultasi",
                        },
                    ],
                    "optional": [{
                        'model': "ketCorAuskultasi"
                    }]
                },
            ]
        },
        {
            "title": "10. Paru",
            "value": [
                {
                    "subTitle": "Inspeksi",
                    "model": "inspeksi_",
                    "item": [
                        {
                            "label" : "Normal",
                            "model" : "paruInspeksi"
                        },
                        {
                            "label" : "Retraksi Dinding Dada",
                            "model" : "paruInspeksi"
                        },
                        {
                            "label" : "Alat Bantu Nafas",
                            "model" : "paruInspeksi"
                        },
                        {
                            "label" : "Lainnya",
                            "model" : "paruInspeksi"
                        },
                    ],
                    "optional": [{
                        'model': "ketParuInspeksi"
                    }]
                },
                {
                    "subTitle": "Palpasi",
                    "item": [
                        {
                            "label" : "Fremitus Kiri dan Kanan Sama",
                            "model" : "paruPalpasi",
                        },
                        {
                            "label" : "Fremitus Kiri Menurun",
                            "model" : "paruPalpasi",
                        },
                        {
                            "label" : "Fremitus Kiri Meningkat",
                            "model" : "paruPalpasi",
                        },
                        {
                            "label" : "Fremitus Kanan Menurun",
                            "model" : "paruPalpasi",
                        },
                        {
                            "label" : "Fremitus Kanan Meningkat",
                            "model" : "paruPalpasi",
                        },
                        {
                            "label" : "Lainnya",
                            "model" : "paruPalpasi",
                        },
                    ],
                    "optional": [{
                        'model': "ketParuPalpasi"
                    }]
                },
                {
                    "subTitle": "Perkusi",
                    "item": [
                        {
                            "label" : "Sonor",
                            "model" : "paruPerkusi",
                        },
                        {
                            "label" : "Hipersonor",
                            "model" : "paruPerkusi",
                        },
                        {
                            "label" : "Lainnya",
                            "model" : "paruPerkusi",
                        },
                    ],
                    "optional": [{
                        'model': "ketParuPerkusi"
                    }]
                },
                {
                    "subTitle": "Auskultasi",
                    "item": [
                        {
                            "label" : "Ronki",
                            "model" : "paruAusRonki"
                        },
                        {
                            "label" : "",
                            "model" : "auskultasiKetRonki"
                        },
                        {
                            "label" : "Wheezing",
                            "model" : "paruAusWheezing"
                        },
                        {
                            "label" : "",
                            "model" : "auskultasiKetWheezing"
                        },
                    ],
                    "optional": [
                      {
                        "model" : "ketLainAuskultasi"
                      }
                    ]
                },
            ]
        },
        {
            "title": "11. Abdomen",
            "value": [
                {
                    "subTitle": "a. Inspeksi",
                    "item": [
                        {
                            "label" : "Datar",
                            "model" : "abdomenInspeksi",
                        },
                        {
                            "label" : "Cekung",
                            "model" : "abdomenInspeksi",
                        },
                        {
                            "label" : "Lainnya",
                            "model" : "abdomenInspeksi",
                        },
                    ],
                    "optional": [{
                        'model': "ketInspeksi"
                    }]
                },
                {
                    "subTitle": "b. Palpasi",
                    "item": [
                        {
                            "label" : "Normal",
                            "model" : "abdomenPalpasi",
                        },
                        {
                            "label" : "Nyeri Tekan",
                            "model" : "abdomenPalpasi",
                        },
                    ],
                    "optional": [{
                        'model': "ketAbdomenPalpasi"
                    }]
                },
                {
                    "subTitle": "c. Hepar",
                    "item": [],
                    "optional": [{
                        'model': "ketAbdomenHepar"
                    }]
                },
                {
                    "subTitle": "d. Limpa",
                    "item": [],
                    "optional": [{
                        'model': "ketAbdomenLimpa"
                    }]
                },
                {
                    "subTitle": "d. Perkusi",
                    "model": "perkusi",
                    "item": [
                        {
                            "label" : "Timpani",
                            "model" : "abdomenPerkusi",
                        },
                        {
                            "label" : "Hipertimpani",
                            "model" : "abdomenPerkusi",
                        },
                        {
                            "label" : "Pekak",
                            "model" : "abdomenPerkusi",
                        },
                    ],
                    "optional": [
                        {
                            'model': "ketAbdomenPerkusi"
                        },
    
                    ]
                },
            ]
        },
        {
            "title": "12. Ekstrmitas",
            "value": [
                {
                    "subTitle": "",
                    "item": [
                        {
                            "label" : "Akral Hangat",
                            "model" : "ekstrmitas_1",
                        },
                        {
                            "label" : "Akral Dingin",
                            "model" : "ekstrmitas_1",
                        },
                        {
                            "label" : "CRT < 2 Detik",
                            "model" : "ekstrmitas_2",
                        },
                        {
                            "label" : "CRT > 2 Detik",
                            "model" : "ekstrmitas_2",
                        },
                        {
                            "label" : "Pitting Edem",
                            "model" : "ekstrmitas_3",
                        },
                    ],
                    "optional": [{
                        'model': "ketEkstrmitas"
                    }]
                },
            ]
        },
        {
            "title": "13. Kekuatan Otot",
            "value": [
                {
                    "subTitle": "",
                    "item": [],
                    "optional": [{
                        'model': "KekuatanOtot"
                    }]
                },
            ]
        },
        {
            "title": "14. Pemeriksaan Neurologi",
            "value": [
                {
                    "subTitle": "",
                    "item": [],
                    "optional": [{
                        'model': "pemeriksaanNeurologi"
                    }]
                },
            ]
        },
    ]
}

export function penunjang() : any {
    return[
        {
            "title": "EKG",
            "model": "pemeriksaanEkg",
            "value": "Ya",
            "desc": "ketEKG"
        },
        {
            "title": "Echocardiography",
            "model": "pemeriksaanEchocardiography",
            "value": "Ya",
            "desc": "ketEchocardiography"
        },
        {
            "title": "laboratorium",
            "model": "pemeriksaanLaboratorium",
            "value": "Ya",
            "desc": "ketLaboratorium"
        },
        {
            "title": "Radiologi",
            "model": "pemeriksaanRadiologi",
            "value": "Ya",
            "desc": "ketRadiologi"
        },
    ]
}

export function dateAndDescrip() : any {
    return [
        {
            "name": "F. Tata Laksana",
            "detail": [{
                "title": "Tanggal dan Jam",
                "model": "waktuTataLaksana",
                "descrip": [{
                    "subTitle": "",
                    "model": "ketTataLaksana"
                }]
            }]
    
        },
        {
            "name": "G. Rencana Selanjutnya",
            "detail": [{
                "title": "Kontrol Tanggal",
                "model": "waktuKontrol",
                "descrip": [
                    {
                        "subTitle": "Rawat",
                        "model": "rawat"
                    },
                    {
                        "subTitle": "Rujuk",
                        "model": "rujuk"
                    },
                ]
            }]
    
        },
    ]
}

export function prognosis() :any {
    return ["Bonam","Dubia ad Bonam","Dubia ad Malam","Malam"]
}
