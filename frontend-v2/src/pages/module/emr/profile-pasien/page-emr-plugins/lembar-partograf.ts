export function checklist(): any {
  return [{
    'label' :'TD',
    'model' :'td',
    'satuan' :'mmHg',
  },{
    'label' :'Nadi',
    'model' :'n',
    'satuan' :'x/mnt',
  },
  {
    'label' :'R',
    'model' :'rr',
    'satuan' :'x/mnt',
  },
  {
    'label' :'Suhu',
    'model' :'suhu',
    'satuan' :'C',
  },
  {
    'label' :'SpO2',
    'model' :'SpO2',
    'satuan' :'x/mnt',
  },
]
}
export function checkbox(): any {
  return [
   {
    'label' :'Keadaan Umum',
    'values':[
        {
            'name' :'Baik',
            'model' :'jalanNafas',
            'value' :'Baik'
        },
        {
          'name' :'Buruk',
          'model' :'jalanNafas',
          'value' :'Buruk'
      }
    ]
  },
  {
    'label' :'Tingkat Kesadaran',
    'values':[
        {
            'name' :'CM',
            'model' :'tingkatKesadaran',
            'value' :'CM'
        },
        {
            'name' :'Apatis',
            'model' :'tingkatKesadaran',
            'value' :'Apatis'
        },
        {
            'name' :'Delirium',
            'model' :'tingkatKesadaran',
            'value' :'Delirium'
        },
        {
            'name' :'Somnolen',
            'model' :'tingkatKesadaran',
            'value' :'Somnolen'
        },
        {
          'name' :'Stupor',
          'model' :'tingkatKesadaran',
          'value' :'Stupor'
        },
        {
          'name' :'Coma',
          'model' :'tingkatKesadaran',
          'value' :'Coma'
        },
    ]
  },
  {
    'label' :'Sirkulasi',
    'values':[
        {
            'name' :'Merah muda',
            'model' :'sirkulasi',
            'value' :'Merah muda'
        },
        {
            'name' :'Kebiru-biruan',
            'model' :'sirkulasi',
            'value' :'Kebiru-biruan'
        }
    ]
  }
  ,{
    'label' :'Jalan Nafas',
    'values':[
        {
            'name' :'Baik',
            'model' :'jalanNafas',
            'value' :'Baik'
        },
        {
            'name' :'ETT Masih terpasang',
            'model' :'jalanNafas',
            'value' :'ETT Masih terpasang'
        },
        {
            'name' :'OPA Masih Terpasang',
            'model' :'jalanNafas',
            'value' :'OPA Masih Terpasang'
        }
    ]
  },
  {
    'label' :'Pernafasan',
    'values':[
        {
            'name' :'Spontan',
            'model' :'pernafasan',
            'value' :'Spontan'
        },
        {
            'name' :'Dibantu',
            'model' :'pernafasan',
            'value' :'Dibantu'
        }
    ]
  },
  {
    'label' :'Jenis Anastesi',
    'values':[
        {
            'name' :'NU',
            'model' :'jenisAnestesi',
            'value' :'NU'
        },
        {
            'name' :'Spinal',
            'model' :'jenisAnestesi',
            'value' :'Spinal'
        },
        {
            'name' :'Epidural',
            'model' :'jenisAnestesi',
            'value' :'Epidural'
        },
        {
            'name' :'Lokal',
            'model' :'jenisAnestesi',
            'value' :'Lokal'
        }
    ]
  }
]
}

export function resikoNutrisional() : any {
  return {
      "pertama": [{
          "no": 1,
          "parameter": "Kesadaran",
          "pengkajian": [
              {
                  "model": "kesadaran",
                  "title": "Tidak Berespon",
                  "keterangan": "",
                  "poin": 0,
                  "value":
                  {
                      "poin": 0,
                      "keterangan": "Tidak Berespon",
                  }

              },
              {
                  "model": "kesadaran",
                  "title": "Bangun namun cepat kembali tidur",
                  "poin": 1,
                  "value":
                  {
                      "poin": 1,
                      "keterangan": "Bangun namun cepat kembali tidur",
                  }

              },
              {
                  "model": "kesadaran",
                  "title": "Sadar siaga dan orientasi",
                  "keterangan": "",
                  "poin": 2,
                  "value":
                  {
                      "poin": 2,
                      "keterangan": "Sadar siaga dan orientasi",
                  }

              },
          ],
      }],
      "kedua": [{
          "no": 2,
          "parameter": "Pernafasan",
          "pengkajian": [
              {
                  "model": "pernafasan",
                  "title": "Apneo atau obstruksi",
                  "keterangan": "",
                  "poin": 0,
                  "value":
                  {
                      "poin": 0,
                      "keterangan": "Apneo atau obstruksi",
                  }

              },
              {
                  "model": "pernafasan",
                  "title": "Dangkal namun pertukaran udara adequat",
                  "keterangan": "",
                  "poin": 1,
                  "value":
                  {
                      "poin": 1,
                      "keterangan": "Dangkal namun pertukaran udara adequat",
                  }

              },
              {
                "model": "pernafasan",
                "title": "Dapat bernafas dalam dan batuk",
                "keterangan": "",
                "poin": 2,
                "value":
                {
                    "poin": 2,
                    "keterangan": "Dapat bernafas dalam dan batuk",
                }

            },
          ],
      }],
      "ketiga": [{
        "no": 3,
        "parameter": "Tekanan Darah",
        "pengkajian": [
            {
                "model": "tekananDarah",
                "title": "Tekanan darah menyimpang > 50% dari normal",
                "keterangan": "",
                "poin": 0,
                "value":
                {
                    "poin": 0,
                    "keterangan": "Tekanan darah menyimpang > 50% dari normal",
                }

            },
            {
                "model": "tekananDarah",
                "title": "Tekanan darah menyimpang 20-25% dari normal",
                "keterangan": "",
                "poin": 1,
                "value":
                {
                    "poin": 1,
                    "keterangan": "Tekanan darah menyimpang 20-25%  dari normal",
                }

            },
            {
              "model": "tekananDarah",
              "title": "Tekanan darah menyimpang < 20% dari normal",
              "keterangan": "",
              "poin": 2,
              "value":
              {
                  "poin": 2,
                  "keterangan": "Tekanan darah menyimpang < 20% dari normal",
              }

          },
        ],
    }],
    "keempat": [{
        "no": 4,
        "parameter": "Warna Kulit",
        "pengkajian": [
            {
                "model": "warnaKulit",
                "title": "Sianosis",
                "keterangan": "",
                "poin": 0,
                "value":
                {
                    "poin": 0,
                    "keterangan": "Sianosis",
                }

            },
            {
                "model": "warnaKulit",
                "title": "Pucat",
                "keterangan": "",
                "poin": 1,
                "value":
                {
                    "poin": 1,
                    "keterangan": "Pucat",
                }

            },
            {
              "model": "warnaKulit",
              "title": "Merah Muda",
              "keterangan": "",
              "poin": 2,
              "value":
              {
                  "poin": 2,
                  "keterangan": "Merah Muda",
              }

          },
        ],
    }],
    "kelima": [{
        "no": 5,
        "parameter": "Pergerakan Otot",
        "pengkajian": [
            {
                "model": "pergerakanOtot",
                "title": "Tidak Bergerak",
                "keterangan": "",
                "poin": 0,
                "value":
                {
                    "poin": 0,
                    "keterangan": "Tidak Bergerak",
                }

            },
            {
                "model": "pergerakanOtot",
                "title": "Dua ektermitas dapat digerakan",
                "keterangan": "",
                "poin": 1,
                "value":
                {
                    "poin": 1,
                    "keterangan": "Dua ektermitas dapat digerakan",
                }

            },
            {
              "model": "pergerakanOtot",
              "title": "Seluruh ektermitas dapat digerakan",
              "keterangan": "",
              "poin": 2,
              "value":
              {
                  "poin": 2,
                  "keterangan": "Seluruh ektermitas dapat digerakan",
              }

          },
        ],
    }],
  }
}

export function resikoNutrisional2() : any {
  return {
      "pertama": [{
          "pengkajian": [
              {
                  "model": "bromageScore",
                  "title": "Gerakan penuh dari tungkal ",
                  "keterangan": "",
                  "poin": 0,
                  "value":
                  {
                      "poin": 0,
                      "keterangan": "Gerakan penuh dari tungkal",
                  }

              },
              {
                  "model": "bromageScore",
                  "title": "Tak mampu ektensi tungkai",
                  "poin": 1,
                  "value":
                  {
                      "poin": 1,
                      "keterangan": "Tak mampu ektensi tungkai",
                  }

              },
              {
                  "model": "bromageScore",
                  "title": "Tak mampu fleksi lutut",
                  "keterangan": "",
                  "poin": 2,
                  "value":
                  {
                      "poin": 2,
                      "keterangan": "Tak mampu fleksi lutut",
                  }

              },
              {
                "model": "bromageScore",
                "title": "Tak mampu fleksi pergelangan kaki",
                "keterangan": "",
                "poin": 3,
                "value":
                {
                    "poin": 3,
                    "keterangan": "Tak mampu fleksi pergelangan kaki",
                }

            },
          ],
      }],
      "kedua": [{
          "no": 2,
          "parameter": "Pernafasan",
          "pengkajian": [
              {
                  "model": "pernafasan",
                  "title": "Apneo atau obstruksi",
                  "keterangan": "",
                  "poin": 0,
                  "value":
                  {
                      "poin": 0,
                      "keterangan": "Apneo atau obstruksi",
                  }

              },
              {
                  "model": "pernafasan",
                  "title": "Dangkal namun pertukaran udara adequat",
                  "keterangan": "",
                  "poin": 1,
                  "value":
                  {
                      "poin": 1,
                      "keterangan": "Dangkal namun pertukaran udara adequat",
                  }

              },
              {
                "model": "pernafasan",
                "title": "Dapat bernafas dalam dan batuk",
                "keterangan": "",
                "poin": 2,
                "value":
                {
                    "poin": 2,
                    "keterangan": "Dapat bernafas dalam dan batuk",
                }

            },
          ],
      }],
      "ketiga": [{
        "no": 3,
        "parameter": "Tekanan Darah",
        "pengkajian": [
            {
                "model": "tekananDarah",
                "title": "Tekanan darah menyimpang > 50% dari normal",
                "keterangan": "",
                "poin": 0,
                "value":
                {
                    "poin": 0,
                    "keterangan": "Tekanan darah menyimpang > 50% dari normal",
                }

            },
            {
                "model": "tekananDarah",
                "title": "Tekanan darah menyimpang 20-25% dari normal",
                "keterangan": "",
                "poin": 1,
                "value":
                {
                    "poin": 1,
                    "keterangan": "Tekanan darah menyimpang 20-25%  dari normal",
                }

            },
            {
              "model": "tekananDarah",
              "title": "Tekanan darah menyimpang < 20% dari normal",
              "keterangan": "",
              "poin": 2,
              "value":
              {
                  "poin": 2,
                  "keterangan": "Tekanan darah menyimpang < 20%  dari normal",
              }

          },
        ],
    }],
    "keempat": [{
        "no": 4,
        "parameter": "Warna Kulit",
        "pengkajian": [
            {
                "model": "warnaKulit",
                "title": "Sianosis",
                "keterangan": "",
                "poin": 0,
                "value":
                {
                    "poin": 0,
                    "keterangan": "Sianosis",
                }

            },
            {
                "model": "warnaKulit",
                "title": "Pucat",
                "keterangan": "",
                "poin": 1,
                "value":
                {
                    "poin": 1,
                    "keterangan": "Pucat",
                }

            },
            {
              "model": "warnaKulit",
              "title": "Merah Muda",
              "keterangan": "",
              "poin": 2,
              "value":
              {
                  "poin": 2,
                  "keterangan": "Merah Muda",
              }

          },
        ],
    }],
    "kelima": [{
        "no": 5,
        "parameter": "Pergerakan Otot",
        "pengkajian": [
            {
                "model": "pergerakanOtot",
                "title": "Tidak Bergerak",
                "keterangan": "",
                "poin": 0,
                "value":
                {
                    "poin": 0,
                    "keterangan": "Tidak Bergerak",
                }

            },
            {
                "model": "pergerakanOtot",
                "title": "Dua ektermitas dapat digerakan",
                "keterangan": "",
                "poin": 1,
                "value":
                {
                    "poin": 1,
                    "keterangan": "Dua ektermitas dapat digerakan",
                }

            },
            {
              "model": "pergerakanOtot",
              "title": "Seluruh ektermitas dapat digerakan",
              "keterangan": "",
              "poin": 2,
              "value":
              {
                  "poin": 2,
                  "keterangan": "Seluruh ektermitas dapat digerakan",
              }

          },
        ],
    }],
  }
}

export function resikoNutrisional3() : any {
  return {
      "pertama": [{
          "no": 1,
          "parameter": "Pergerakan",
          "pengkajian": [
              {
                  "model": "pergerakanSteward",
                  "title": "Tidak Bergerak",
                  "keterangan": "",
                  "poin": 0,
                  "value":
                  {
                      "poin": 0,
                      "keterangan": "Tidak Bergerak",
                  }

              },
              {
                  "model": "pergerakanSteward",
                  "title": "Gerak tak bertujuan",
                  "poin": 1,
                  "value":
                  {
                      "poin": 1,
                      "keterangan": "Gerak tak bertujuan",
                  }

              },
              {
                  "model": "pergerakanSteward",
                  "title": "Gerak bertujuan",
                  "keterangan": "",
                  "poin": 2,
                  "value":
                  {
                      "poin": 2,
                      "keterangan": "Gerak bertujuan",
                  }

              },
          ],
      }],
      "kedua": [{
          "no": 2,
          "parameter": "Pernafasan",
          "pengkajian": [
              {
                  "model": "pernafasanSteward",
                  "title": "Perlu Bantuan",
                  "keterangan": "",
                  "poin": 0,
                  "value":
                  {
                      "poin": 0,
                      "keterangan": "Perlu Bantuan",
                  }

              },
              {
                  "model": "pernafasanSteward",
                  "title": "Pertahankan jalan nafas",
                  "keterangan": "",
                  "poin": 1,
                  "value":
                  {
                      "poin": 1,
                      "keterangan": "Pertahankan jalan nafas",
                  }

              },
              {
                "model": "pernafasanSteward",
                "title": "Batuk , menangis",
                "keterangan": "",
                "poin": 2,
                "value":
                {
                    "poin": 2,
                    "keterangan": "Batuk , menangis",
                }

            },
          ],
      }],
      "ketiga": [{
        "no": 3,
        "parameter": "Kesadaran",
        "pengkajian": [
            {
                "model": "kesadaranSteward",
                "title": "Tidak bereaksi",
                "keterangan": "",
                "poin": 0,
                "value":
                {
                    "poin": 0,
                    "keterangan": "Tidak bereaksi",
                }

            },
            {
                "model": "kesadaranSteward",
                "title": "Bereaksi terhadap rangsangan",
                "keterangan": "",
                "poin": 1,
                "value":
                {
                    "poin": 1,
                    "keterangan": "Bereaksi terhadap rangsangan",
                }

            },
            {
              "model": "kesadaranSteward",
              "title": "Menangis",
              "keterangan": "",
              "poin": 2,
              "value":
              {
                  "poin": 2,
                  "keterangan": "Menangis",
              }

          },
        ],
    }],
  }
}

export function tempatPersalinan(): any {
  return [
    {
      model: 'tempatPersalinan',
      label: 'Rumah Ibu',
      value: 'RumahIbu',
    },
    {
      model: 'tempatPersalinan',
      label: 'Polindas',
      value: 'Polindas',
    },
    {
      model: 'tempatPersalinan',
      label: 'Puskesmas',
      value: 'Puskesmas',
    },
    {
      model: 'tempatPersalinan',
      label: 'Rumah Sakit',
      value: 'RumahSakit',
    },
    {
      model: 'tempatPersalinan',
      label: 'Klinik Swasta',
      value: 'KlinikSwasta',
    },
    {
      model: 'tempatPersalinan',
      label: 'Lainnya',
      value: 'Lainnya',
    },
  ]
}

export function catatan(): any {
  return [
    {
      model: 'catatan',
      label: 'Rujuk Kala I',
      value: 'RujukKalaI',
    },
    {
      model: 'catatan',
      label: 'Kala II',
      value: 'KalaII',
    },
    {
      model: 'catatan',
      label: 'Kala III',
      value: 'KalaIII',
    },
    {
      model: 'catatan',
      label: 'Kala IV',
      value: 'KalaIV',
    },
  ]
}

export function pendamping(): any {
  return [
    {
      model: 'pendamping',
      label: 'Bidan',
      value: 'Bidan',
    },
    {
      model: 'pendamping',
      label: 'Teman',
      value: 'Teman',
    },
    {
      model: 'pendamping',
      label: 'Suami',
      value: 'Suami',
    },
    {
      model: 'pendamping',
      label: 'Dukun',
      value: 'Dukun',
    },
    {
      model: 'pendamping',
      label: 'Keluarga',
      value: 'Keluarga',
    },
    {
      model: 'pendamping',
      label: 'Tidak Ada',
      value: 'TidakAda',
    },
  ]
}

export function masalahKehamilan(): any {
  return [
    {
      model: 'masalahKehamilan',
      label: 'Gawat Darurat',
      value: 'GawatDarurat',
    },
    {
      model: 'masalahKehamilan',
      label: 'Pendarahan',
      value: 'Pendarahan',
    },
    {
      model: 'masalahKehamilan',
      label: 'HDK',
      value: 'HDK',
    },
    {
      model: 'masalahKehamilan',
      label: 'Infeksi',
      value: 'Infeksi',
    },
    {
      model: 'masalahKehamilan',
      label: 'PMTC',
      value: 'PMTC',
    },
  ]
}

export function partogram(): any {
  return [
    {
      model: 'partogram',
      label: 'Ya',
      value: 'YA',
    },
    {
      model: 'partogram',
      label: 'Tidak',
      value: 'Tidak',
    },
  ]
}

export function kondisiBayi(): any {
  return [
    {
      model: 'kondisiBayi',
      label: 'Normal',
      value: 'Normal',
    },
    {
      model: 'kondisiBayi',
      label: 'Asfiksia',
      value: 'Asfiksia',
    },
    {
      model: 'kondisiBayi',
      label: 'Cacat Bawaan',
      value: 'CacatBawaan',
    },
    {
      model: 'kondisiBayi',
      label: 'Hipotermi',
      value: 'Hipotermi',
    },
  ]
}

export function tindakanBayi(): any {
  return [
    {
      model: 'tindakanBayi',
      label: 'Mengeringkan',
      value: 'Mengeringkan',
    },
    {
      model: 'tindakanBayi',
      label: 'Menghangatkan',
      value: 'Menghangatkan',
    },
    {
      model: 'tindakanBayi',
      label: 'Rangsang Taktik',
      value: 'RangsangTaktik',
    },
    {
      model: 'tindakanBayi',
      label: 'Rangsang Taktik',
      value: 'RangsangTaktik',
    },
    {
      model: 'tindakanBayi',
      label: 'Pakaian/Selimut',
      value: 'Pakaian',
    },
    {
      model: 'tindakanBayi',
      label: 'Tindakan Pencegahan Infeksi',
      value: 'TindakanPencegahanInfeksi',
    },
  ]
}

