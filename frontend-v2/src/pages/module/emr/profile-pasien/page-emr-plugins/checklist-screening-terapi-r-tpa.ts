export function indikasiTrombolisis() : any {
    return [ 
      {
        "title": "Indikasi Terapi Trombolisis r-TPA Intravena (SEMUA HARUS YA)",
        "type": "",
        "detail": [ 
            {
                label: "1. Waktu awitan kurang dari 4,5 jam",
                model: "indikasiTrombolisis_1",
                no: 1,
                keterangan: true,
                options: [
                    { label: "Ya", value: "Ya" },
                    { label: "Tidak", value: "Tidak" },
                ],
            },
            {
                label: "2. Diagnosis klinis stroke iskemik yang menyebabkan defisit neurologis dengan NIHSS > 2",
                model: "indikasiTrombolisis_2",
                no: 2,
                keterangan: true,
                options: [
                    { label: "Ya", value: "Ya" },
                    { label: "Tidak", value: "Tidak" },
                ],
            },
            {
                label: "3. Usia > 18 tahun",
                model: "indikasiTrombolisis_3",
                no: 3,
                keterangan: true,
                options: [
                    { label: "Ya", value: "Ya" },
                    { label: "Tidak", value: "Tidak" },
                ],
            }
            ]
        },
    ]
}

export function kontradiksiTrombolisis() : any {
    return [
        {
            "title": "Kontraindikasi Terapi Trombolisis r-TPA Intravena (SEMUA HARUS TIDAK)",
            "type" : "",
            "children": [
                {
                    "title": "RIWAYAT PENYAKIT",
                    "type": "",
                    "detail": [ 
                        {
                            label: "1. Riwayat stroke perdarahan atau perdarahan subarahnoid",
                            model: "riwayatPenyakit_1",
                            no: 1,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "2. Riwayat stroke iskemik dalam 3 bulan terakhir",
                            model: "riwayatPenyakit_2",
                            no: 2,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "3. Riwayat trauma kepala atau medulla spinalis yang berat dalam 3 bulan terakhir",
                            model: "riwayatPenyakit_3",
                            no: 3,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "4. Riwayat trauma mayor selain selain trauma kepala dan medulla spinalis dalam 14 hari terakhir",
                            model: "riwayatPenyakit_4",
                            no: 4,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "5. Riwayat operasi intrakranial atau intraspinal dalam 3 bulan terakhir",
                            model: "riwayatPenyakit_5",
                            no: 5,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "6. Riwayat operasi mayor selain intrakranial dan intraspinal dalam 14 hari terakhir",
                            model: "riwayatPenyakit_6",
                            no: 6,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "7. Riwayat perdarahan saluran cerna atau saluran kemih dalam 21 hari terakhir",
                            model: "riwayatPenyakit_7",
                            no: 7,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "8. Riwayat penyakit keganasan saluran cerna",
                            model: "riwayatPenyakit_8",
                            no: 8,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "9. Riwayat konsumsi direct oral anticoagulant < 48 jam",
                            model: "riwayatPenyakit_9",
                            no: 9,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "10. Riwayat pemberian low molecular weight heparin (LMWH) dalam 24 jam terakhir",
                            model: "riwayatPenyakit_10",
                            no: 10,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "11. Riwayat hipersensitivitas obat terhadap alteplase",
                            model: "riwayatPenyakit_11",
                            no: 11,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                    ]
                },
                {
                    "title": "TEMUAN KLINIS",
                    "type": "",
                    "detail": [ 
                        {
                            label: "1. Adanya perdarahan (intrakranial, gastrointestinal, saluran kemih, retroperitoneal, atau hemoptisis)",
                            model: "temuanKlinis_1",
                            no: 1,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "2. Tekanan darah sistolik ≥185 mmHg atau diastolik ≥110 mmHg meskipun sudah menggunakan obat antihipertensi IV",
                            model: "temuanKlinis_2",
                            no: 2,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "3. Adanya kecurigaan perdarahan subarachnoid",
                            model: "temuanKlinis_3",
                            no: 3,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "4. Adanya kecurigaan endokartis infektif (demam, murmur, petechiae, subungual (splinter) hemorrhage, Osler nodes, Janeway lesions, Roth spots)",
                            model: "temuanKlinis_4",
                            no: 4,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "5. Adanya kecurigaan diseksi aorta akut (nyeri dada, perbedaan tekanan darah lengan kanan-kiri >20 mmHg, murmur aorta, dilatasi aorta pada rontgen thorax)",
                            model: "temuanKlinis_5",
                            no: 5,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        }
                    ]
                },
                {
                    "title": "LABORATORIUM",
                    "type": "",
                    "detail": [ 
                        {
                            label: "1. Kadar glukosa darah yang abnormal (<50 atau >400 mg/dL meskipun setelah dikoreksi)",
                            model: "laboratorium_1",
                            no: 1,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "2. Trombosit ≤100.000/mm3 (Catatan: rTPA tetap berjalan tanpa menunggu hasil trombosit)",
                            model: "laboratorium_2",
                            no: 2,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "3. Pasien mengkonsumsi warfarin dan nilai INR >1,7 atau APTT memanjang >1,5 kali",
                            model: "laboratorium_3",
                            no: 3,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        }
                    ]
                },
                {
                    "title": "PENCITRAAN",
                    "type": "",
                    "detail": [ 
                        {
                            label: "1. CT menunjukkan perdarahan intrakranial",
                            model: "pencitraan_1",
                            no: 1,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "2. CT menunjukkan adanya efek desak ruang yang disertai pendorongan garis tengah",
                            model: "pencitraan_2",
                            no: 2,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "3. CT menunjukkan gambaran iskemik luas melibatkan lebih dari 1/3 teritori arteri serebri media",
                            model: "pencitraan_3",
                            no: 3,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        }
                    ]
                },
            ]
        }
    ]
}

export function penyulitTerapi() : any {
    return [ 
        {
            "title": "",
            "type": "",
            "detail": [ 
                {
                    label: "1. Usia > 80 tahun",
                    model: "penyulitTerapi_1",
                    no: 1,
                    keterangan: true,
                    options: [
                        { label: "Ya", value: "Ya" },
                        { label: "Tidak", value: "Tidak" },
                    ],
                },
            ]
        },
    ]
}

export function penyulitTerapiTrombolisis() : any {
    return [ 
        {
            "title": "Penyulit Terapi Trombolisis r-TPA Intravena (KONSUL DIVISI NEUROVASKULAR BILA YA)",
            "type" : "",
            "children": [
                {
                    "title": "RIWAYAT PENYAKIT",
                    "type": "",
                    "detail": [ 
                        {
                            label: "1. Riwayat stroke sebelumnya dengan diabetes mellitus (jika waktu awitan 3-4,5 jam)",
                            model: "riwayatPenyakitDua_1",
                            no: 1,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "2. Riwayat infark miokard dalam 3 bulan terakhir",
                            model: "riwayatPenyakitDua_2",
                            no: 2,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "3. Riwayat konsumsi direct oral anticoagulant >48 jam",
                            model: "riwayatPenyakitDua_3",
                            no: 3,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "4. Riwayat konsumsi double antiplatelet",
                            model: "riwayatPenyakitDua_4",
                            no: 4,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "5. Riwayat biopsi dalam 10 hari terakhir",
                            model: "riwayatPenyakitDua_5",
                            no: 5,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "6. Riwayat lumbal pungsi dalam 7 hari terakhir",
                            model: "riwayatPenyakitDua_6",
                            no: 6,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "7. Riwayat pungsi arteri non-compressible dalam 7 hari terakhir",
                            model: "riwayatPenyakitDua_7",
                            no: 7,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "8. Riwayat melahirkan, aborsi, atau persalinan prematur dalam 10 hari",
                            model: "riwayatPenyakitDua_8",
                            no: 8,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "9. Riwayat penyakit keganasan sistemik",
                            model: "riwayatPenyakitDua_9",
                            no: 9,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "10. Riwayat aneurisma serebral, malformasi vaskular serebral, neoplasma intrakranial, aneurisma aorta, penyakit Moyamoya",
                            model: "riwayatPenyakitDua_10",
                            no: 10,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "11. Riwayat tukak saluran cerna, divertikulitis, kolitis",
                            model: "riwayatPenyakitDua_11",
                            no: 11,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "12. Riwayat retinopati hemoragik diabetik atau kondisi mata hemoragik",
                            model: "riwayatPenyakitDua_12",
                            no: 12,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                    ]
                },
                {
                    "title": "TEMUAN KLINIS",
                    "type": "",
                    "detail": [ 
                        {
                            label: "1. Defisit neurologis berat NIHSS ≥26 (jika waktu awitan 3-4,5 jam)",
                            model: "temuanKlinisDua_1",
                            no: 1,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "2. Defisit neurologis ringan NIHSS 2-5",
                            model: "temuanKlinisDua_2",
                            no: 2,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "3. Perbaikan yang cepat atau perburukan tanda-tanda neurologis",
                            model: "temuanKlinisDua_3",
                            no: 3,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "4. Adanya kejang pada saat awitan stroke",
                            model: "temuanKlinisDua_4",
                            no: 4,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "5. Adanya infark miokard akut, perikarditis akut",
                            model: "temuanKlinisDua_5",
                            no: 5,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "6. Adanya penyakit gagal ginjal kronis on HD",
                            model: "temuanKlinisDua_6",
                            no: 6,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                        {
                            label: "7. Sedang dalam periode menstruasi",
                            model: "temuanKlinisDua_7",
                            no: 7,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                    ]
                },
                {
                    "title": "PENCITRAAN",
                    "type": "",
                    "detail": [ 
                        {
                            label: "1. Adanya cerebral microbleed >10 pada MRI otak (pada pasien yang sebelumnya pernah dilakukan MRI otak di RS PON)",
                            model: "pencitraanDua_1",
                            no: 1,
                            keterangan: true,
                            options: [
                                { label: "Ya", value: "Ya" },
                                { label: "Tidak", value: "Tidak" },
                            ],
                        },
                    ]
                },
            ]
        }
    ]
}