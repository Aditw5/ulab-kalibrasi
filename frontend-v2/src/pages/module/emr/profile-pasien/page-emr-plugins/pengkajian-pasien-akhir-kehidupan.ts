export function planning(): any {
  return [
      {
          "Kriteria": "Mengetahui diagnosis",
          "pasien": [
              {
                  "label": "Ya",
                  "model": "DiagnosisPasien"
              },
              {
                  "label": "Tidak",
                  "model": "DiagnosisPasien"
              }
          ],
          "keluarga": [
              {
                  "label": "Ya",
                  "model": "DiagnosisKeluarga"
              },
              {
                  "label": "Tidak",
                  "model": "DiagnosisKeluarga"
              }
          ],
      },
      {
          "Kriteria": "Mengetahui Prognosis",
          "pasien": [
              {
                  "label": "Ya",
                  "model": "prognosisPasien"
              },
              {
                  "label": "Tidak",
                  "model": "prognosisPasien"
              }
          ],
          "keluarga": [
              {
                  "label": "Ya",
                  "model": "prognosisKeluarga"
              },
              {
                  "label": "Tidak",
                  "model": "prognosisKeluarga"
              }
          ],
      },
      {
          "Kriteria": "Mengetahui Tujuan Perawatan",
          "pasien": [
              {
                  "label": "Ya",
                  "model": "perawatanPasien"
              },
              {
                  "label": "Tidak",
                  "model": "perawatanPasien"
              }
          ],
          "keluarga": [
              {
                  "label": "Ya",
                  "model": "perawatanKeluarga"
              },
              {
                  "label": "Tidak",
                  "model": "perawatanKeluarga"
              }
          ],
      },
      {
          "Kriteria": "Kebutuhan dan dukungan spiritual",
          "pasien": [
              {
                  "label": "Ya",
                  "model": "spiritualPasien"
              },
              {
                  "label": "Tidak",
                  "model": "spiritualPasien"
              }
          ],
          "keluarga": [
              {
                  "label": "Ya",
                  "model": "spiritualKeluarga"
              },
              {
                  "label": "Tidak",
                  "model": "spiritualKeluarga"
              }
          ],
      },
      {
        "Kriteria": "Keyakinan alam proses kehilangan",
        "pasien": [
            {
                "label": "Denial",
                "model": "KehilanganPasien"
            },
            {
                "label": "Angger",
                "model": "KehilanganPasien"
            },
            {
                "label": "Bergaining",
                "model": "KehilanganPasien"
            },
            {
                "label": "Depresi",
                "model": "KehilanganPasien"
            },
            {
                "label": "Acceptance",
                "model": "KehilanganPasien"
            }
        ],
        "keluarga": [
          {
            "label": "Denial",
            "model": "KehilanganKeluarga"
          },
          {
              "label": "Angger",
              "model": "KehilanganKeluarga"
          },
          {
              "label": "Bergaining",
              "model": "KehilanganKeluarga"
          },
          {
              "label": "Depresi",
              "model": "KehilanganKeluarga"
          },
          {
              "label": "Acceptance",
              "model": "KehilanganKeluarga"
          }
        ],
    },
    {
      "Kriteria": "Kecemasan pasien/kerabat terhadap diri sendiri/orang lain",
        "pasien": [
            {
                "label": "Ya",
                "model": "KecemasanPasien"
            },
            {
                "label": "Tidak",
                "model": "KecemasanPasien"
            },
            {
                "label": "Keadaan Koma",
                "model": "KecemasanPasien"
            }
        ],
        "keluarga": [
            {
                "label": "Ya",
                "model": "KecemasanKeluarga"
            },
            {
                "label": "Tidak",
                "model": "KecemasanKeluarga"
            }
        ],
    },
    {
      "Kriteria": "Kecemasan pasien/kerabat terhadap diri sendiri/orang lain",
      "pasien": [
          {
              "label": "Ya",
              "model": "KecemasanPasien"
          },
          {
              "label": "Tidak",
              "model": "KecemasanPasien"
          },
          {
              "label": "Keadaan Koma",
              "model": "KecemasanPasien"
          }
      ],
      "keluarga": [
          {
              "label": "Ya",
              "model": "KecemasanKeluarga"
          },
          {
              "label": "Tidak",
              "model": "KecemasanKeluarga"
          }
      ],
    },

  ]
}

export function planning1(): any {
  return [
      {
          "Kriteria": "Tingkat Keberuntungan",
          "pilihan": [
              {
                  "label": "Total",
                  "model": "tingkatKeberuntungan"
              },
              {
                  "label": "Parsial",
                  "model": "tingkatKeberuntungan"
              },
              {
                "label": "Mandiri",
                "model": "tingkatKeberuntungan"
            }
          ],
      },
      {
          "Kriteria": "Status Mental",
          "pilihan": [
              {
                  "label": "Sedih",
                  "model": "StatusMental"
              },
              {
                  "label": "Khawatir",
                  "model": "StatusMental"
              },
              {
                "label": "Ketakutan",
                "model": "StatusMental"
              },
              {
                "label": "Putus Asa",
                "model": "StatusMental"
              },
              {
                "label": "Hilang kontrol diri",
                "model": "StatusMental"
              }
          ],
      },
      {
          "Kriteria": "Respon Emosi",
          "pilihan": [
              {
                  "label": "Mudah Tersinggung",
                  "model": "ResponEmosi"
              },
              {
                  "label": "Tidak ingin Berkomunikasi",
                  "model": "ResponEmosi"
              }
          ],
      },
      {
          "Kriteria": "relasi dengan orang lain",
          "pilihan": [
              {
                  "label": "isolasi Diri",
                  "model": "RelasiDenganOrang"
              },
              {
                  "label": "Menarik Diri",
                  "model": "RelasiDenganOrang"
              },
          ],
      },
      {
          "Kriteria": "Suasana Hati",
          "pilihan": [
              {
                  "label": "Putus Asa",
                  "model": "SuasanaHati"
              },
              {
                  "label": "Merasa tidak yakin",
                  "model": "SuasanaHati"
              },
              {
                "label": "Merasa penderitaan seumur hidup",
                "model": "SuasanaHati"
            }
          ],
      },
  ]
}

export function kesadaranFisik(): any {
  return [
      {
          "title": "CM",
          "model": "kesadaranFisik"
      },
      {
          "title": "Apatis",
          "model": "kesadaranFisik"
      },
      {
          "title": "Somnolen",
          "model": "kesadaranFisik"
      },
      {
          "title": "Koma",
          "model": "kesadaranFisik"
      },
  ]
}

export function perubahanMental(): any {
  return [
      {
          "title": "Agitasi",
          "model": "perubahanMental"
      },
      {
          "title": "Gelisah",
          "model": "perubahanMental"
      },
  ]
}

export function jenisPernafasan(): any {
  return [
      {
          "title": "CheyneStokes",
          "model": "jenisPernafasan"
      },
      {
          "title": "Kusmaul",
          "model": "jenisPernafasan"
      },
      {
          "title": "Apneu",
          "model": "jenisPernafasan"
      },
  ]
}

export function sekret(): any {
  return [
      {
          "title": "Ya",
          "model": "sekret"
      },
      {
          "title": "Tidak",
          "model": "sekret"
      },
  ]
}

export function irama(): any {
  return [
      {
          "title": "Reguler",
          "model": "irama"
      },
      {
          "title": "Irreguler",
          "model": "irama"
      },
  ]
}
export function ekstremitas(): any {
  return [
      {
          "title": "Hangat",
          "model": "ekstremitas"
      },
      {
          "title": "Dingin",
          "model": "ekstremitas"
      },
  ]
}
export function warnaKulit(): any {
  return [
      {
          "title": "Ya",
          "model": "warnaKulit"
      },
      {
          "title": "Tidak",
          "model": "warnaKulit"
      },
  ]
}
export function penglihatan(): any {
  return [
      {
          "title": "Normal",
          "model": "penglihatan"
      },
      {
          "title": "Kabur",
          "model": "penglihatan"
      },
  ]
}
export function reflexBerkedip(): any {
  return [
      {
          "title": "Ya",
          "model": "reflexBerkedip"
      },
      {
          "title": "Tidak",
          "model": "reflexBerkedip"
      },
  ]
}
export function pendengaran(): any {
  return [
      {
          "title": "Normal",
          "model": "pendengaran"
      },
      {
          "title": "Menurun",
          "model": "pendengaran"
      },
  ]
}
export function sensi(): any {
  return [
      {
          "title": "Normal",
          "model": "sensi"
      },
      {
          "title": "Menurun",
          "model": "sensi"
      },
  ]
}
export function tonusOtot(): any {
  return [
      {
          "title": "Baik",
          "model": "tonusOtot"
      },
      {
          "title": "Menurun",
          "model": "tonusOtot"
      },
      {
          "title": "Tidak Ada",
          "model": "tonusOtot"
      },
  ]
}
export function nafsuMakan(): any {
  return [
      {
          "title": "Baik",
          "model": "nafsuMakan"
      },
      {
          "title": "Berkurang",
          "model": "nafsuMakan"
      },
      {
          "title": "Mual",
          "model": "nafsuMakan"
      },
      {
          "title": "Muntah",
          "model": "nafsuMakan"
      },
  ]
}
export function mukosaMulut(): any {
  return [
      {
          "title": "Lembab",
          "model": "mukosaMulut"
      },
      {
          "title": "Kering",
          "model": "mukosaMulut"
      },
  ]
}
export function distensiAbdomen(): any {
  return [
      {
          "title": "Ya",
          "model": "distensiAbdomen"
      },
      {
          "title": "Tidak",
          "model": "distensiAbdomen"
      },
  ]
}
export function cegukan(): any {
  return [
      {
          "title": "Ya",
          "model": "cegukan"
      },
      {
          "title": "Tidak",
          "model": "cegukan"
      },
  ]
}
export function eliminasi(): any {
  return [
      {
          "title": "Konstipasi",
          "model": "eliminasi"
      },
      {
          "title": "Ikontinentia",
          "model": "eliminasi"
      },
      {
          "title": "Retensi Urin",
          "model": "eliminasi"
      },
  ]
}
export function komunikasiKeluarga(): any {
  return [
      {
          "title": "Setiap Saat",
          "model": "komunikasiKeluarga"
      },
      {
          "title": "Tidak pada malam hari",
          "model": "komunikasiKeluarga"
      },
      {
          "title": "Pada saat menginap di Rumah Sakit",
          "model": "komunikasiKeluarga"
      },
  ]
}
export function kerjasamaMedisLain(): any {
  return [
      {
          "title": "Onkologi",
          "model": "kerjasamaMedisLain"
      },
      {
          "title": "paliatif",
          "model": "kerjasamaMedisLain"
      },
      {
          "title": "Psikiater",
          "model": "kerjasamaMedisLain"
      },
      {
          "title": "Anestesi",
          "model": "kerjasamaMedisLain"
      },
      {
          "title": "Ahli Gizi",
          "model": "kerjasamaMedisLain"
      },
      {
          "title": "Perawat",
          "model": "kerjasamaMedisLain"
      },
      {
          "title": "Terapis",
          "model": "kerjasamaMedisLain"
      },
      {
          "title": "Farmasi",
          "model": "kerjasamaMedisLain"
      },
      {
          "title": "Lainnya",
          "model": "kerjasamaMedisLain"
      },
  ]
}

export function hubunganPasien(): any {
  return [
    {
      title: 'Baik',
      model: 'hubunganPasien_',
    },
    {
      title: 'Tidak baik',
      model: 'hubunganPasien_',
    },
  ]
}

export function spritualPasien(): any {
  return [
    {
      title: 'Kecenderuangan bunuh diri dilaporkan ke..',
      value : 'laporanBunuhdiri',
      ketValue : 'ketLaporkan',
      model: 'spritualPasien',
    },
    {
      title: 'Lain-lain sebutkan..',
      value : 'kecenderuanganLain',
      ketValue : 'ketKecenderuangan',
      model: 'spritualPasien',
    },
  ]
}

export function faktorPencetus(): any {
  return [
    {
      model: 'faktorPencetus',
      label: 'Trauma',
      value: 'Trauma',
    },
    {
      model: 'faktorPencetus',
      label: 'Infeksi',
      value: 'Infeksi',
    },
    {
      model: 'faktorPencetus',
      label: 'Lain-lain',
      value: 'LainLain',
    },
  ]
}
export function riwayatAlergiObatMakanan(): any {
  return [
    {
      model: 'riwayatAlergiObatMakanan',
      label: 'Tidak Ada/No',
      value: 'TidakAda',
    },
    {
      model: 'riwayatAlergiObatMakanan',
      label: 'Ada/ Yes Sebutkan',
      value: 'Ada',
    },
  ]
}

export function keadaanHamil(): any {
  return [
    {
      model: 'keadaanHamil',
      label: 'Tidak',
      value: 'Tidak',
    },
    {
      model: 'keadaanHamil',
      label: 'Ya',
      value: 'Ya',
    },
  ]
}

export function seberapaSeringNyeri(): any {
  return [
    {
      model: 'seberapaSeringNyeri',
      label: 'Setiap 1-2 Jam',
      value: 'Setiap1-2Jam',
    },
    {
      model: 'seberapaSeringNyeri',
      label: 'Setiap 3-4 Jam',
      value: 'Setiap3-4Jam',
    },
    {
      model: 'seberapaseringNyeri',
      label: 'Setiap 3-4 Jam',
      value: 'Setiap3-4Jam',
    },
  ]
}

export function berapaLamaNyeri(): any {
  return [
    {
      model: 'berapaLamaNyeri',
      label: 'Selama < 30 Menit',
      value: 'Selama<30Menit',
    },
    {
      model: 'berapaLamaNyeri',
      label: 'Selama > 30 Menit',
      value: 'Selama>30Menit',
    },
  ]
}

export function diagnosisKeperawatan(): any {
  return [
    {
      model: 'diagnosisKeperawatan',
      label: 'Penurunan Kesdaran',
      value: 'PenurunanKesdaran',
    },
    {
      model: 'diagnosisKeperawatan',
      label: 'Kejang',
      value: 'Kejang',
    },
    {
      model: 'diagnosisKeperawatan',
      label: 'Ketidak Efektifan/Bersihan Jalan Panas',
      value: 'KetidakEfektifan/BersihanJalanPanas',
    },
    {
      model: 'diagnosisKeperawatan',
      label: 'Sesak',
      value: 'Sesak',
    },
    {
      model: 'diagnosisKeperawatan',
      label: 'Nyeri',
      value: 'Nyeri',
    },
    {
      model: 'diagnosisKeperawatan',
      label: 'Gangguan Hamadinamik',
      value: 'GangguanHamadinamik',
    },
    {
      model: 'diagnosisKeperawatan',
      label: 'Gangguan Integritas Kulit',
      value: 'GangguanIntegritasKulit',
    },
    {
      model: 'diagnosisKeperawatan',
      label: 'Gangguan Keseimbangan Cairan dan Elektrilit',
      value: 'GangguanKeseimbanganCairandanElektrilitIntegritasKulit',
    },
    {
      model: 'diagnosisKeperawatan',
      label: 'Peningkatan Suhu Tubuh',
      value: 'PeningkatanSuhuTubuh',
    },
    {
      model: 'diagnosisKeperawatan',
      label: 'Lain-Lain',
      value: 'Lain-Lain',
    },
  ]
}

export function penyebabNyeriBerkurangBertambah(): any {
  return [
    {
      model: 'penyebabNyeriBerkurangBertambah',
      label: 'Kompres Hangat/Dingin',
      value: 'KompresHangat/Dingin',
    },
    {
      model: 'penyebabNyeriBerkurangBertambah',
      label: 'Aktivitas Dikurangi/Bertambah',
      value: 'AktivitasDikurangi/Bertambah',
    },
  ]
}

export function lamaNyeri(): any {
  return [
    {
      model: 'lamaNyeri',
      label: '< 3 Bulan',
      value: '<3Bulan',
    },
    {
      model: 'lamaNyeri',
      label: 'Akut',
      value: 'Akut',
    },
    {
      model: 'lamaNyeri',
      label: '> 3 Bulan',
      value: '>3Bulan',
    },
    {
      model: 'lamaNyeri',
      label: 'Kronik',
      value: 'Kronik',
    },
  ]
}

export function rasaNyeri(): any {
  return [
    {
      model: 'rasaNyeri',
      label: 'Tajam',
      value: 'Tajam',
    },
    {
      model: 'rasaNyeri',
      label: 'Nyeri Tumpul',
      value: 'NyeriTumpul',
    },
    {
      model: 'rasaNyeri',
      label: 'Seperti Ditarik',
      value: 'SepertiDitarik',
    },
    {
      model: 'rasaNyeri',
      label: 'Seperti Ditusuk',
      value: 'SepertiDitusuk',
    },
    {
      model: 'rasaNyeri',
      label: 'Seperti Dipukul',
      value: 'SepertiDipukul',
    },
    {
      model: 'rasaNyeri',
      label: 'Seperti Dibakar',
      value: 'SepertiDibakar',
    },
    {
      model: 'rasaNyeri',
      label: 'Seperti Berdenyut',
      value: 'SepertiBerdenyut',
    },
    {
      model: 'rasaNyeri',
      label: 'Seperti Ditikam',
      value: 'SepertiDitikam',
    },
    {
      model: 'rasaNyeri',
      label: 'Seperti Kram',
      value: 'SepertiKram',
    },
  ]
}

export function riwayatKelahiran(): any {
  return [
    {
      model: 'riwayatKelahiran',
      label: 'Spontan',
      value: 'Spontan',
    },
    {
      model: 'riwayatKelahiran',
      label: 'Operasi',
      value: 'Operasi',
    },
    {
      model: 'riwayatKelahiran',
      label: 'Cukup Bulan',
      value: 'CukupBulan',
    },
    {
      model: 'riwayatKelahiran',
      label: 'Kurang Bulan',
      value: 'KurangBulan',
    },
  ]
}

export function riwayatImunisasi(): any {
  return [
    {
      model: 'riwayatImunisasiBCG',
      label: 'BCG',
      value: 'BCG',
    },
    {
      model: 'riwayatImunisasiDPT',
      label: 'DPT',
      value: 'DPT',
    },
    {
      model: 'riwayatImunisasiPolio',
      label: 'Polio',
      value: 'Polio',
    },
    {
      model: 'riwayatImunisasiCampak',
      label: 'Campak',
      value: 'Campak',
    },
    {
      model: 'riwayatImunisasiHepatitisB',
      label: 'Hepatitis B',
      value: 'HepatitisB',
    },
    {
      model: 'riwayatImunisasiPCV',
      label: 'PCV',
      value: 'PCV',
    },
    {
      model: 'riwayatImunisasiVericela',
      label: 'Vericela',
      value: 'Vericela',
    },
    {
      model: 'riwayatImunisasiTypoid',
      label: 'Typoid',
      value: 'Typoid',
    },
    {
      model: 'riwayatImunisasiRotervirus',
      label: 'Rotervirus',
      value: 'Rotervirus',
    },
    {
      model: 'riwayatImunisasiMMR',
      label: 'MMR',
      value: 'MMR',
    },
    {
      model: 'riwayatImunisasiInfluenza',
      label: 'Influenza',
      value: 'Influenza',
    },
    {
      model: 'riwayatImunisasiPnemuokokus',
      label: 'Pnemuokokus',
      value: 'Pnemuokokus',
    },
    {
      model: 'riwayatImunisasiHPV',
      label: 'HPV',
      value: 'HPV',
    },
    {
      model: 'riwayatImunisasiTetanus',
      label: 'Tetanus',
      value: 'Tetanus',
    },
    {
      model: 'riwayatImunisasiZooster',
      label: 'Zooster',
      value: 'Zooster',
    },
    {
      model: 'riwayatImunisasiMeningitis',
      label: 'Meningitis',
      value: 'Meningitis',
    },
    {
      model: 'riwayatImunisasiYellowFever',
      label: 'Yellow Fever',
      value: 'YellowFever',
    },
    {
      model: 'riwayatImunisasiHepatitis',
      label: 'Hepatitis',
      value: 'Hepatitis',
    },
    {
      model: 'riwayatImunisasiHIB',
      label: 'HIB',
      value: 'HIB',
    }
  ]
}

export function riwayatPsikologis(): any {
  return [
    {
      model: 'riwayatPsikologis',
      label: 'Tidak Semangat',
      value: 'TidakSemangat',
    },
    {
      model: 'riwayatPsikologis',
      label: 'Rasa Tertekan',
      value: 'RasaTertekan',
    },
    {
      model: 'riwayatPsikologis',
      label: 'Depresi',
      value: 'Depresi',
    },
    {
      model: 'riwayatPsikologis',
      label: 'Sulit Tidur',
      value: 'SulitTidur',
    },
    {
      model: 'riwayatPsikologis',
      label: 'Cepat Lelah',
      value: 'CepatLelah',
    },
    {
      model: 'riwayatPsikologis',
      label: 'Sulit Berbicara',
      value: 'SulitBerbicara',
    },
    {
      model: 'riwayatPsikologis',
      label: 'Kurang Nafsu Makan',
      value: 'KurangNafsuMakan',
    },
    {
      model: 'riwayatPsikologis',
      label: 'Sulit Konsentrasi',
      value: 'SulitKonsentrasi',
    },
    {
      model: 'riwayatPsikologis',
      label: 'Menggunakan Obat Penenang',
      value: 'MenggunakanObatPenenang',
    },
    {
      model: 'riwayatPsikologis',
      label: 'Merasa Bersalah',
      value: 'MerasaBersalah',
    },
  ]
}

export function menyusui(): any {
  return [
    {
      model: 'menyusui',
      label: 'Tidak',
      value: 'Tidak',
    },
    {
      model: 'menyusui',
      label: 'Ya',
      value: 'Ya',
    },
  ]
}

export function pengasuh(): any {
  return [
    {
      model: 'pengasuh',
      label: 'CM',
      value: 'CM',
    },
    {
      model: 'pengasuh',
      label: 'Apatis',
      value: 'Apatis',
    },
    {
      model: 'pengasuh',
      label: 'Somnolen',
      value: 'Somnolen',
    },
    {
      model: 'pengasuh',
      label: 'Koma',
      value: 'Koma',
    },
  ]
}

export function kebiasaanAnak(): any {
  return [
    {
      model: 'kebiasaanAnak',
      label: 'Suka Tertawa',
      value: 'SukaTertawa',
    },
    {
      model: 'kebiasaanAnak',
      label: 'Pendiam',
      value: 'Pendiam',
    },
    {
      model: 'kebiasaanAnak',
      label: 'Ramah',
      value: 'Ramah',
    },
    {
      model: 'kebiasaanAnak',
      label: 'Suka Berteman',
      value: 'SukaBerteman',
    },
    {
      model: 'kebiasaanAnak',
      label: 'Sering Menangis',
      value: 'SeringMenangis',
    },
  ]
}

export function bentukUbunUbun(): any {
  return [
    {
      model: 'bentukUbunUbun',
      label: 'Cekung',
      value: 'Cekung',
    },
    {
      model: 'bentukUbunUbun',
      label: 'Rata',
      value: 'Rata',
    },
    {
      model: 'bentukUbunUbun',
      label: 'Menonjol',
      value: 'Menonjol',
    },
  ]
}

export function pernafasan(): any {
  return [
    {
      model: 'pernafasan',
      label: 'Normal',
      value: 'Normal',
    },
    {
      model: 'pernafasan',
      label: 'Batuk',
      value: 'Batuk',
    },
    {
      model: 'pernafasan',
      label: 'Sesak',
      value: 'Sesak',
    },
  ]
}

export function pasienKeluargaTahuRencanaPulang(): any {
  return [
    {
      model: 'pasienKeluargaTahuRencanaPulang',
      label: 'Ya',
      value: 'Ya',
    },
    {
      model: 'pasienKeluargaTahuRencanaPulang',
      label: 'Tidak',
      value: 'Tidak',
    },
  ]
}

export function perjalananPenyakit(): any {
  return [
    {
      model: 'perjalananPenyakit',
      label: 'Akut',
      value: 'Akut',
    },
    {
      model: 'perjalananPenyakit',
      label: 'Kronis',
      value: 'Kronis',
    },
  ]
}

export function pengecapan(): any {
  return [
    {
      model: 'pengecapan',
      label: 'Baik',
      value: 'Baik',
    },
    {
      model: 'pengecapan',
      label: 'Ada Gangguan',
      value: 'AdaGangguan',
    },
  ]
}

export function penciuman(): any {
  return [
    {
      model: 'penciuman',
      label: 'Baik',
      value: 'Baik',
    },
    {
      model: 'penciuman',
      label: 'Ada Gangguan',
      value: 'AdaGangguan',
    },
  ]
}
export function bicara(): any {
  return [
    {
      model: 'bicara',
      label: 'Normal',
      value: 'Normal',
    },
    {
      model: 'bicara',
      label: 'Ada Gangguan',
      value: 'AdaGangguan',
    },
  ]
}
export function mulut(): any {
  return [
    {
      model: 'mulut',
      label: 'Bersih',
      value: 'Bersih',
    },
    {
      model: 'mulut',
      label: 'Kotor',
      value: 'Kotor',
    },
    {
      model: 'mulut',
      label: 'Lain-lain',
      value: 'Lainlain',
    },
  ]
}
export function defekasi(): any {
  return [
    {
      model: 'defekasi',
      label: 'Normal',
      value: 'Normal',
    },
    {
      model: 'defekasi',
      label: 'Retensi',
      value: 'Retensi',
    },
  ]
}
export function miksi(): any {
  return [
    {
      model: 'miksi',
      label: 'Normal',
      value: 'Normal',
    },
    {
      model: 'miksi',
      label: 'Retensi',
      value: 'Retensi',
    },
  ]
}
export function Gastrointestinal(): any {
  return [
    {
      model: 'Gastrointestinal',
      label: 'Normal',
      value: 'Normal',
    },
    {
      model: 'Gastrointestinal',
      label: 'Refluks',
      value: 'Refluks',
    },
    {
      model: 'Gastrointestinal',
      label: 'Nausea',
      value: 'Nausea',
    },
    {
      model: 'Gastrointestinal',
      label: 'Muntah',
      value: 'Muntah',
    },
  ]
}
export function polaTidur(): any {
  return [
    {
      model: 'polaTidur',
      label: 'Normal',
      value: 'Normal',
    },
    {
      model: 'polaTidur',
      label: 'Masalah',
      value: 'Masalah',
    },
  ]
}
export function taliPusat(): any {
  return [
    {
      model: 'taliPusat',
      label: 'Basah',
      value: 'Basah',
    },
    {
      model: 'taliPusat',
      label: 'Kering',
      value: 'Kering',
    },
    {
      model: 'taliPusat',
      label: 'Bau',
      value: 'Bau',
    },
    {
      model: 'taliPusat',
      label: 'Kelainan',
      value: 'Kelainan',
    },
  ]
}
export function refleksMenelan(): any {
  return [
    {
      model: 'refleksMenelan',
      label: 'Normal',
      value: 'Normal',
    },
    {
      model: 'refleksMenelan',
      label: 'Sulit',
      value: 'Sulit',
    },
    {
      model: 'refleksMenelan',
      label: 'Rusak',
      value: 'Rusak',
    },
  ]
}
export function refleksMenangis(): any {
  return [
    {
      model: 'refleksMenangis',
      label: 'Normal',
      value: 'Normal',
    },
    {
      model: 'refleksMenangis',
      label: 'Lemah',
      value: 'Lemah',
    },
    {
      model: 'refleksMenangis',
      label: 'Kelainan',
      value: 'Kelainan',
    },
  ]
}
export function refleksMenghisap(): any {
  return [
    {
      model: 'refleksMenghisap',
      label: 'Normal',
      value: 'Normal',
    },
    {
      model: 'refleksMenghisap',
      label: 'Lemah',
      value: 'Lemah',
    },
    {
      model: 'refleksMenghisap',
      label: 'Kelainan',
      value: 'Kelainan',
    },
  ]
}
export function refleksMenoleh(): any {
  return [
    {
      model: 'refleksMenoleh',
      label: 'Normal',
      value: 'Normal',
    },
    {
      model: 'refleksMenoleh',
      label: 'Lemah',
      value: 'Lemah',
    },
    {
      model: 'refleksMenoleh',
      label: 'Kelainan',
      value: 'Kelainan',
    },
  ]
}
export function refleksGenggam(): any {
  return [
    {
      model: 'refleksGenggam',
      label: 'Normal',
      value: 'Normal',
    },
    {
      model: 'refleksGenggam',
      label: 'Lemah',
      value: 'Lemah',
    },
    {
      model: 'refleksGenggam',
      label: 'Kelainan',
      value: 'Kelainan',
    },
  ]
}
export function refleksBabinski(): any {
  return [
    {
      model: 'refleksBabinski',
      label: 'Normal',
      value: 'Normal',
    },
    {
      model: 'refleksBabinski',
      label: 'Lemah',
      value: 'Lemah',
    },
    {
      model: 'refleksBabinski',
      label: 'Kelainan',
      value: 'Kelainan',
    },
  ]
}
export function refleksMoro(): any {
  return [
    {
      model: 'refleksMoro',
      label: 'Normal',
      value: 'Normal',
    },
    {
      model: 'refleksMoro',
      label: 'Lemah',
      value: 'Lemah',
    },
    {
      model: 'refleksMoro',
      label: 'Kelainan',
      value: 'Kelainan',
    },
  ]
}
export function tonicNeck(): any {
  return [
    {
      model: 'tonicNeck',
      label: 'Normal',
      value: 'Normal',
    },
    {
      model: 'tonicNeck',
      label: 'Lemah',
      value: 'Lemah',
    },
    {
      model: 'tonicNeck',
      label: 'Kelainan',
      value: 'Kelainan',
    },
  ]
}
export function polaMakanAnak(): any {
  return [
    {
      model: 'polaMakan',
      label: '2x/hr',
      value: '2x/hr',
    },
    {
      model: 'polaMakan',
      label: '3x/hr',
      value: '3x/hr',
    },
    {
      model: 'polaMakan',
      label: 'lebih dari 3x/hr',
      value: 'lebihdari3x/hr',
    },
  ]
}
export function makananYangDiberikan(): any {
  return [
    {
      model: 'makananYangDiberikan',
      label: 'ASI',
      value: 'ASI',
    },
    {
      model: 'makananYangDiberikan',
      label: 'PASI',
      value: 'PASI',
    },
    {
      model: 'makananYangDiberikan',
      label: 'Bubur Susu',
      value: 'BuburSusu',
    },
    {
      model: 'makananYangDiberikan',
      label: 'Nasi Tim',
      value: 'NasiTim',
    },
    {
      model: 'makananYangDiberikan',
      label: 'Nasi',
      value: 'Nasi',
    },
    {
      model: 'makananYangDiberikan',
      label: 'Lain-lain',
      value: 'Lainlain',
    },
  ]
}
export function kebiasaanSebelumTidur(): any {
  return [
    {
      model: 'kebiasaanSebelumTidur',
      label: 'Perlu Mainan',
      value: 'PerluMainan',
    },
    {
      model: 'kebiasaanSebelumTidur',
      label: 'Dibacakan Cerita',
      value: 'DibacakanCerita',
    },
    {
      model: 'kebiasaanSebelumTidur',
      label: 'Dengan benda-benda kesayangan',
      value: 'DenganBendaBendaKesayangan',
    },
  ]
}
export function aktivitasBermain(): any {
  return [
    {
      model: 'aktivitasBermain',
      label: 'Sendiri',
      value: 'Sendiri',
    },
    {
      model: 'aktivitasBermain',
      label: 'Dengan Orangtua',
      value: 'DenganOrangtua',
    },
    {
      model: 'aktivitasBermain',
      label: 'Dengan teman-teman sebaya',
      value: 'DenganTemanTemanSebaya',
    },
  ]
}

export function seksualitas(): any {
  return [
    {
      model: 'seksualitas',
      label: 'Impoten',
      value: 'Impoten',
    },
    {
      model: 'seksualitas',
      label: 'Perubahan Seksualita',
      value: 'PerubahanSeksualita',
    },
    {
      model: 'seksualitas',
      label: 'Frigiditas',
      value: 'Frigiditas',
    },
  ]
}

export function bentukTubuhMusculo(): any {
  return [
    {
      model: 'bentukTubuhMusculo',
      label: 'Tegap',
      value: 'Tegap',
    },
    {
      model: 'bentukTubuhMusculo',
      label: 'Tidak Tegap',
      value: 'Tidaktegap',
    },
    {
      model: 'bentukTubuhMusculo',
      label: 'Gibus',
      value: 'Gibus',
    },
    {
      model: 'bentukTubuhMusculo',
      label: 'Kiposis',
      value: 'Kiposis',
    },
    {
      model: 'bentukTubuhMusculo',
      label: 'Lordosis',
      value: 'Lordosis',
    },
    {
      model: 'bentukTubuhMusculo',
      label: 'Skoliosis',
      value: 'Skoliosis',
    },
  ]
}
export function sendiMusculo(): any {
  return [
    {
      model: 'sendiMusculo',
      label: 'Dislokasi',
      value: 'Dislokasi',
    },
    {
      model: 'sendiMusculo',
      label: 'Infeksi',
      value: 'Infeksi',
    },
    {
      model: 'sendiMusculo',
      label: 'Nyeri',
      value: 'Nyeri',
    },
    {
      model: 'sendiMusculo',
      label: 'Odema',
      value: 'Odema',
    },
    {
      model: 'sendiMusculo',
      label: 'Kontraktur Area',
      value: 'kontrakturArea',
    },
  ]
}
export function sosialSupport(): any {
  return [
    {
      model: 'sosialSupportSuami',
      label: 'Suami/Istri',
      value: 'Suami',
    },
    {
      model: 'sosialSupportOrangTua',
      label: 'Orang Tua',
      value: 'OrangTua',
    },
    {
      model: 'sosialSupportMertua',
      label: 'Mertua',
      value: 'Mertua',
    },
    {
      model: 'sosialSupportAnak',
      label: 'Anak',
      value: 'Anak',
    },
    {
      model: 'sosialSupportKeluargaLain',
      label: 'Keluarga Lain',
      value: 'KeluargaLain',
    },
  ]
}

export function polaKomunikasi(): any {
  return [
    {
      model: 'polaKomunikasi',
      label: 'Normal',
      value: 'Normal',
    },
    {
      model: 'polaKomunikasi',
      label: 'Introvet',
      value: 'Introvet',
    },
    {
      model: 'polaKomunikasi',
      label: 'Lain-Lain',
      value: 'LainLain',
    },
  ]
}

export function polaMakan(): any {
  return [
    {
      model: 'polaMakan',
      label: 'Sehat',
      value: 'Sehat',
    },
    {
      model: 'polaMakan',
      label: 'Tidak Sehat',
      value: 'TidakSehat',
    },
  ]
}

export function makananPokok(): any {
  return [
    {
      model: 'makananPokok',
      label: 'Nasi',
      value: 'Nasi',
    },
    {
      model: 'makananPokok',
      label: 'Selain Nasi',
      value: 'SelainNasi',
    },
  ]
}

export function pantangMakanan(): any {
  return [
    {
      model: 'pantangMakanan',
      label: 'Tidak',
      value: 'Tidak',
    },
    {
      model: 'pantangMakanan',
      label: 'Ya',
      value: 'Ya',
    },
  ]
}

export function pemahamanPenyakit(): any {
  return [
    {
      model: 'pemahamanPenyakit',
      label: 'Ya',
      value: 'Ya',
    },
    {
      model: 'pemahamanPenyakit',
      label: 'Tidak',
      value: 'Tidak',
    },
  ]
}

export function pemahamanPengobatan(): any {
  return [
    {
      model: 'pemahamanPengobatan',
      label: 'Ya',
      value: 'Ya',
    },
    {
      model: 'pemahamanPengobatan',
      label: 'Tidak',
      value: 'Tidak',
    },
  ]
}

export function indeksMasaTubuh(): any {
  return [
    {
      model: 'pemahamanPengobatan',
      label: 'Ya',
      value: 'Ya',
    },
    {
      model: 'pemahamanPengobatan',
      label: 'Tidak',
      value: 'Tidak',
    },
  ]
}

export function apakahPasienTinggalSendiri(): any {
  return [
    {
      model: 'apakahPasienTinggalSendiri',
      label: 'Ya',
      value: 'Ya',
    },
    {
      model: 'apakahPasienTinggalSendiri',
      label: 'Tidak',
      value: 'Tidak',
    },
  ]
}

export function kawatirKetikaKembaliKerumah(): any {
  return [
    {
      model: 'kawatirKetikaKembaliKerumah',
      label: 'Ya',
      value: 'Ya',
    },
    {
      model: 'kawatirKetikaKembaliKerumah',
      label: 'Tidak',
      value: 'Tidak',
    },
  ]
}

export function apakhDirumahAdaYangMerawat(): any {
  return [
    {
      model: 'apakhDirumahAdaYangMerawat',
      label: 'Ya',
      value: 'Ya',
    },
    {
      model: 'apakhDirumahAdaYangMerawat',
      label: 'Tidak',
      value: 'Tidak',
    },
  ]
}

export function perawatanLajutanDiruamah(): any {
  return [
    {
      model: 'perawatanLajutanDiruamah',
      label: 'Ya',
      value: 'Ya',
    },
    {
      model: 'perawatanLajutanDiruamah',
      label: 'Tidak',
      value: 'Tidak',
    },
  ]
}

export function transportasiSaatPulang(): any {
  return [
    {
      model: 'transportasiSaatPulang',
      label: 'Ya',
      value: 'Ya',
    },
    {
      model: 'transportasiSaatPulang',
      label: 'Tidak',
      value: 'Tidak',
    },
  ]
}

export function permohonanPendampingan(): any {
  return [
    {
      model: 'permohonanPendampingan',
      label: 'Ya',
      value: 'Ya',
    },
    {
      model: 'permohonanPendampingan',
      label: 'Tidak',
      value: 'Tidak',
    },
  ]
}

export function enamJenisObatSaatPulang(): any {
  return [
    {
      model: 'enamJenisObatSaatPulang',
      label: 'Ya',
      value: 'Ya',
    },
    {
      model: 'enamJenisObatSaatPulang',
      label: 'Tidak',
      value: 'Tidak',
    },
  ]
}

export function tanggungJawabMemelihara(): any {
  return [
    {
      model: 'tanggungJawabMemelihara',
      label: 'Ya',
      value: 'Ya',
    },
    {
      model: 'tanggungJawabMemelihara',
      label: 'Tidak',
      value: 'Tidak',
    },
  ]
}

export function tempatTinggalAdaTangga(): any {
  return [
    {
      model: 'tempatTinggalAdaTangga',
      label: 'Ya',
      value: 'Ya',
    },
    {
      model: 'tempatTinggalAdaTangga',
      label: 'Tidak',
      value: 'Tidak',
    },
  ]
}

export function jenisTempatTinggalPasien(): any {
  return [
    {
      model: 'jenisTempatTinggalPasien',
      label: 'Rumah',
      value: 'Rumah',
    },
    {
      model: 'jenisTempatTinggalPasien',
      label: 'Kost',
      value: 'Kost',
    },
    {
      model: 'jenisTempatTinggalPasien',
      label: 'Apartemen',
      value: 'Apartemen',
    },
  ]
}

export function kehilanganBB(): any {
  return [
    {
      model: 'kehilanganBB',
      label: 'Ya',
      value: 'Ya',
    },
    {
      model: 'kehilanganBB',
      label: 'Tidak',
      value: 'Tidak',
    },
  ]
}

export function asupanMakananPasienKurang(): any {
  return [
    {
      model: 'asupanMakananPasienKurang',
      label: 'Ya',
      value: 'Ya',
    },
    {
      model: 'asupanMakananPasienKurang',
      label: 'Tidak',
      value: 'Tidak',
    },
  ]
}

export function penyakitBeratPasien(): any {
  return [
    {
      model: 'penyakitBeratPasien',
      label: 'Ya',
      value: 'Ya',
    },
    {
      model: 'penyakitBeratPasien',
      label: 'Tidak',
      value: 'Tidak',
    },
  ]
}

export function keluhanNyeri(): any {
  return [
     {
      model: 'keluhanNyeri',
      label: 'Tidak',
      value: 'Tidak',
    },
    {
      model: 'keluhanNyeri',
      label: 'Ya',
      value: 'Ya',
    },
  ]
}

export function nyeriBerpindah(): any {
  return [
     {
      model: 'nyeriBerpindah',
      label: 'Tidak',
      value: 'Tidak',
    },
    {
      model: 'nyeriBerpindah',
      label: 'Ya',
      value: 'Ya',
    },
  ]
}

export function metodePenilaianNyeri(): any {
  return [
     {
      model: 'metodePenilaianNyeri',
      label: 'VAS',
      value: 'VAS',
    },
    {
      model: 'metodePenilaianNyeri',
      label: 'BPS',
      value: 'BPS',
    },
    {
      model: 'metodePenilaianNyeri',
      label: 'Wong-Baker FACES',
      value: 'WongBakerFACES',
    },
  ]
}

export function hambatanMenerimaNutrisi(): any {
  return [
    {
      model: 'hambatanMenerimaNutrisi',
      label: 'Tidak Ada',
      value: 'TidakAda',
    },
    {
      model: 'hambatanMenerimaNutrisi',
      label: 'Ada Gangguan Pengelihatan',
      value: 'AdaGangguanPengelihatan',
    },
    {
      model: 'hambatanMenerimaNutrisi',
      label: 'Ada Gangguan Pendengaran',
      value: 'AdaGangguanPendengaran',
    },
    {
      model: 'hambatanMenerimaNutrisi',
      label: 'Belum Melek Huruf',
      value: 'BelumMelekHuruf',
    },
    {
      model: 'hambatanMenerimaNutrisi',
      label: 'Ada Gangguan Emosi',
      value: 'AdaGangguanEmosi',
    },
    {
      model: 'hambatanMenerimaNutrisi',
      label: 'Ada Gangguan Fisik',
      value: 'AdaGangguanFisik',
    },
    {
      model: 'hambatanMenerimaNutrisi',
      label: 'Keterbatasan Motifasi',
      value: 'KeterbatasanMotifasi',
    },
    {
      model: 'hambatanMenerimaNutrisi',
      label: 'Ada Keterbatasan Dalam Hal Budaya/Spiritual/Agama',
      value: 'AdaKeterbatasanDalamHalBudayaSpiritualAgama',
    },
    {
      model: 'hambatanMenerimaNutrisi',
      label: 'Ada Keterbatasan Dalam Berbahasa',
      value: 'AdaKeterbatasanDalamBerbahasa',
    },
  ]
}

export function pemahamanNutrisi(): any {
  return [
    {
      model: 'pemahamanNutrisi',
      label: 'Ya',
      value: 'Ya',
    },
    {
      model: 'pemahamanNutrisi',
      label: 'Tidak',
      value: 'Tidak',
    },
  ]
}

export function pemahamanPerawatan(): any {
  return [
    {
      model: 'pemahamanPerawatan',
      label: 'Ya',
      value: 'Ya',
    },
    {
      model: 'pemahamanPerawatan',
      label: 'Tidak',
      value: 'Tidak',
    },
  ]
}

export function kebutuhanBelajar(): any {
  return [
    {
      model: 'kebutuhanBelajar',
      label: 'Aktivitas',
      value: 'Aktivitas',
    },
    {
      model: 'kebutuhanBelajar',
      label: 'Kontrol',
      value: 'Kontrol',
    },
    {
      model: 'kebutuhanBelajar',
      label: 'Makan',
      value: 'Makan',
    },
    {
      model: 'kebutuhanBelajar',
      label: 'Senam',
      value: 'Senam',
    },
    {
      model: 'kebutuhanBelajar',
      label: 'Pengobatan',
      value: 'Pengobatan',
    },
    {
      model: 'kebutuhanBelajar',
      label: 'Rawat Luka',
      value: 'RawatLuka',
    },
    {
      model: 'kebutuhanBelajar',
      label: 'Tumbang',
      value: 'Tumbang',
    },
    {
      model: 'kebutuhanBelajar',
      label: 'Seksual',
      value: 'Seksual',
    },
    {
      model: 'kebutuhanBelajar',
      label: 'Modifikasi Lingkungan',
      value: 'ModifikasiLingkungan',
    },
    {
      model: 'kebutuhanBelajar',
      label: 'Manajemen Stress',
      value: 'ManajemenStress',
    },
    {
      model: 'kebutuhanBelajar',
      label: 'Pencegaahan Penyakit',
      value: 'PencegaahanPenyakit',
    },
    {
      model: 'kebutuhanBelajar',
      label: 'Pencegaahan Komplikasi',
      value: 'Pencegaahan Komplikasi',
    },
  ]
}

export function pengaruhKepercayaan(): any {
  return [
    {
      model: 'pengaruhKepercayaan',
      label: 'Tidak',
      value: 'Tidak',
    },
    {
      model: 'pengaruhKepercayaan',
      label: 'Ya',
      value: 'Ya',
    },
  ]
}

export function nilaiBudayaPenyebabPenyakit(): any {
  return [
    {
      model: 'nilaiBudayaPenyebabPenyakit',
      label: 'Hukuman',
      value: 'Hukuman',
    },
    {
      model: 'nilaiBudayaPenyebabPenyakit',
      label: 'Ujian',
      value: 'Ujian',
    },
    {
      model: 'nilaiBudayaPenyebabPenyakit',
      label: 'Kesehatan',
      value: 'Kesehatan',
    },
    {
      model: 'nilaiBudayaPenyebabPenyakit',
      label: 'Takdir',
      value: 'Takdir',
    },
    {
      model: 'nilaiBudayaPenyebabPenyakit',
      label: 'Buatan Orang Lain',
      value: 'BuatanOrangLain',
    },
    {
      model: 'nilaiBudayaPenyebabPenyakit',
      label: 'Keturunan',
      value: 'Keturunan',
    },
  ]
}

export function psikososial(): any {
  return [
    {
      model: 'psikososial',
      label: 'Denail (Menolak/Tidak Percaya)',
      value: 'Denail(Menolak/Tidak Percaya)',
    },
    {
      model: 'psikososial',
      label: 'Anger (Marah)',
      value: 'AngerMarah',
    },
    {
      model: 'psikososial',
      label: 'Bergaining (Tawar Menawar)',
      value: 'BergainingTawarMenawar',
    },
    {
      model: 'psikososial',
      label: 'Depresi (Depresi)',
      value: 'Depresi',
    },
    {
      model: 'psikososial',
      label: 'Tidak Semangat',
      value: 'TidakSemangat',
    },
    {
      model: 'psikososial',
      label: 'Menerima (Acception)',
      value: 'MenerimaAcception',
    },
    {
      model: 'psikososial',
      label: 'Rasa Tertekan',
      value: 'RasaTertekann',
    },
    {
      model: 'psikososial',
      label: 'Cepat Lelah',
      value: 'CepatLelah',
    },
    {
      model: 'psikososial',
      label: 'Sulit Berbicara',
      value: 'SulitBerbicara',
    },
    {
      model: 'psikososial',
      label: 'Sulit Konsentrasi',
      value: 'SulitKonsentrasi',
    },
    {
      model: 'psikososial',
      label: 'Merasa Bersalah',
      value: 'MerasaBersalah',
    },
    {
      model: 'psikososial',
      label: 'Sulit Tidur',
      value: 'SulitTidur',
    },
  ]
}

export function tulangMusculo(): any {
  return [
    {
      model: 'tulangMusculo',
      label: 'Faktur Terbuka',
      value: 'FakturTerbuka',
    },
    {
      model: 'tulangMusculo',
      label: 'Faktur Tertutup',
      value: 'FakturTertutup',
    },
    {
      model: 'tulangMusculo',
      label: 'Faktur Kompresi',
      value: 'FakturKompresi',
    },
    {
      model: 'tulangMusculo',
      label: 'Faktur Patologis',
      value: 'FakturPatologis',
    },
    {
      model: 'tulangMusculo',
      label: 'Amputasi',
      value: 'Amputasi',
    },
    {
      model: 'tulangMusculo',
      label: 'Brus Fraktur',
      value: 'BrusFraktur',
    },
    {
      model: 'tulangMusculo',
      label: 'Tumor Tulang',
      value: 'TumorTulang',
    },
    {
      model: 'tulangMusculo',
      label: 'Nyeri Tulang',
      value: 'NyeriTulang',
    },
  ]
}

export function aktivitasIstirahat(): any {
  return [
    {
      model: 'aktivitasIstirahat',
      label: 'Insomnia',
      value: 'Insomnia',
    },
    {
      model: 'aktivitasIstirahat',
      label: 'Tremor',
      value: 'Tremor',
    },
    {
      model: 'aktivitasIstirahat',
      label: 'Malaise/Fatique',
      value: 'MalaiseFatique',
    },
    {
      model: 'aktivitasIstirahat',
      label: 'ROM Menurun',
      value: 'ROMMenurun',
    },
    {
      model: 'aktivitasIstirahat',
      label: 'Mobiltas Dibatasi',
      value: 'MobiltasDibatasi',
    },
    {
      model: 'aktivitasIstirahat',
      label: 'Aktivitas Dengan Bantuan',
      value: 'AktivitasDenganBantuan',
    },
    {
      model: 'aktivitasIstirahat',
      label: 'Kontraktur',
      value: 'Kontraktur',
    },
  ]
}

export function eliminasiXhr(): any {
  return [
    {
      model: 'eliminasiXhr',
      label: 'Hematuri',
      value: 'Hematuri',
    },
    {
      model: 'eliminasiXhr',
      label: 'Poliuri',
      value: 'Poliuri',
    },
    {
      model: 'eliminasiXhr',
      label: 'Disuria',
      value: 'Disuria',
    },
    {
      model: 'eliminasiXhr',
      label: 'Inkontinensi',
      value: 'Inkontinensi',
    },
    {
      model: 'eliminasiXhr',
      label: 'Kateter',
      value: 'Kateter',
    },
    {
      model: 'eliminasiXhr',
      label: 'Retensi',
      value: 'Retensi',
    },
  ]
}

export function pakaiAlatBantu(): any {
  return [
    {
      model: 'pakaiAlatBantu',
      label: 'Tidak',
      value: 'Tidak',
    },
    {
      model: 'pakaiAlatBantu',
      label: 'Tongkat',
      value: 'Tongkat',
    },
    {
      model: 'pakaiAlatBantu',
      label: 'Walker',
      value: 'Walker',
    },
    {
      model: 'pakaiAlatBantu',
      label: 'Kursi Roda',
      value: 'KursiRoda',
    },
    {
      model: 'pakaiAlatBantu',
      label: 'Lain-lain',
      value: 'LainLain',
    },
  ]
}

export function integrasiKulit(): any {
  return [
    {
      model: 'integrasiKulit',
      label: 'Kondisi Bersih',
      value: 'KondisiBersih',
    },
    {
      model: 'integrasiKulit',
      label: 'Kondisi Kotor',
      value: 'KondisiKotor',
    },
    {
      model: 'integrasiKulit',
      label: 'Laserasi',
      value: 'Laserasi',
    },
    {
      model: 'integrasiKulit',
      label: 'Jaringan Parut',
      value: 'JaringanParut',
    },
    {
      model: 'integrasiKulit',
      label: 'Bulae',
      value: 'Bulae',
    },
    {
      model: 'integrasiKulit',
      label: 'Kemerahan',
      value: 'Kemerahan',
    },
    {
      model: 'integrasiKulit',
      label: 'Memar',
      value: 'Memar',
    },
    {
      model: 'integrasiKulit',
      label: 'Ulceerasi',
      value: 'Ulceerasi',
    },
    {
      model: 'integrasiKulit',
      label: 'Luka di area',
      value: 'lukaDiarea',
    },
  ]
}

export function peningkatanTik(): any {
  return [
    {
      model: 'peningkatanTik',
      label: 'Tidak Ada',
      value: 'TidakAda',
    },
    {
      model: 'peningkatanTik',
      label: 'Sakit Kepala',
      value: 'SakitKepala',
    },
    {
      model: 'peningkatanTik',
      label: 'Pusing',
      value: 'Pusing',
    },
    {
      model: 'peningkatanTik',
      label: 'Muntah Proyektil',
      value: 'Muntah Proyektil',
    },
  ]
}

export function sirkulasiCairan(): any {
  return [
    {
      model: 'sirkulasiCairan',
      label: 'Diaforesis',
      value: 'Diaforesis',
    },
    {
      model: 'sirkulasiCairan',
      label: 'Pucat',
      value: 'Pucat',
    },
    {
      model: 'sirkulasiCairan',
      label: 'Hematemesis',
      value: 'Hematemesis',
    },
    {
      model: 'sirkulasiCairan',
      label: 'Melena',
      value: 'Melena',
    },
    {
      model: 'sirkulasiCairan',
      label: 'Ascites',
      value: 'Ascites',
    },
    {
      model: 'sirkulasiCairan',
      label: 'Akral Dingin',
      value: 'AkralDingin',
    },
    {
      model: 'sirkulasiCairan',
      label: 'Akral Hangat',
      value: 'AkralHangat',
    },
    {
      model: 'sirkulasiCairan',
      label: 'Disritma',
      value: 'Disritma',
    },
    {
      model: 'sirkulasiCairan',
      label: 'Anemesis',
      value: 'Anemesis',
    },
    {
      model: 'sirkulasiCairan',
      label: 'Epistaksis',
      value: 'Epistaksis',
    },
    {
      model: 'sirkulasiCairan',
      label: 'Mukosa Mulut Kering',
      value: 'MukosaMulutKering',
    },
  ]
}

export function riwayatPenyakitKeluarga(): any {
  return [
    {
      model: 'riwayatPenyakitKeluargaAsma',
      label: 'Asma',
      value: 'Asma',
    },
    {
      model: 'riwayatPenyakitKeluargaHipertensi',
      label: 'Hipertensi',
      value: 'Hipertensi',
    },
    {
      model: 'riwayatPenyakitKeluargaJantung',
      label: 'Jantung',
      value: 'Jantung',
    },
    {
      model: 'riwayatPenyakitKeluargaDiabetesMelitus',
      label: 'Diabetes Melitus',
      value: 'DiabetesMelitus',
    },
    {
      model: 'riwayatPenyakitKeluargaLainLain',
      label: 'Lain-lain',
      value: 'LainLain',
    },
  ]
}

export function pernapasan(): any {
  return [
    {
      model: 'pernapasan',
      label: 'Vesikuner',
      value: 'Vesikuner',
    },
    {
      model: 'pernapasan',
      label: 'Wheezing',
      value: 'Wheezing',
    },
    {
      model: 'pernapasan',
      label: 'Ronchi',
      value: 'Ronchi',
    },
    {
      model: 'pernapasan',
      label: 'Dispnea',
      value: 'Dispnea',
    },
    {
      model: 'pernapasan',
      label: 'Stridor',
      value: 'Stridor',
    },
    {
      model: 'pernapasan',
      label: 'Cuping Hidung',
      value: 'CupingHidung',
    },
    {
      model: 'pernapasan',
      label: 'Batuk',
      value: 'Batuk',
    },
    {
      model: 'pernapasan',
      label: 'Skret',
      value: 'Skret',
    },
    {
      model: 'pernapasan',
      label: 'Otot Bantu Nafas',
      value: 'OtotBahuNafas',
    },
    {
      model: 'pernapasan',
      label: 'Sianosis',
      value: 'Sianosis',
    },
    {
      model: 'pernapasan',
      label: 'Krepitasi',
      value: 'Krepitasi',
    },
  ]
}

export function terapiKomplementari(): any {
  return [
    {
      model: 'terapiKomplementari',
      label: 'Jamu/Herbal',
      value: 'JamuHerbal',
    },
    {
      model: 'terapiKomplementari',
      label: 'Acupuncture',
      value: 'Acupunture',
    },
    {
      model: 'terapiKomplementari',
      label: 'Pijat/Massage',
      value: 'PijatMassage',
    },
    {
      model: 'terapiKomplementari',
      label: 'Lain-Lain/Other',
      value: 'LainLain',
    },
  ]
}

export function riwayatPenyakitDahulu(): any {
  return [
    {
      model: 'riwayatPenyakitDahulu',
      label: 'Diabets Melitus',
      value: 'DiabetsMelitus',
    },
    {
      model: 'riwayatPenyakitDahulu',
      label: 'Hepatitis',
      value: 'Hepatitis',
    },
    {
      model: 'riwayatPenyakitDahulu',
      label: 'Stroke',
      value: 'Stroke',
    },
    {
      model: 'riwayatPenyakitDahulu',
      label: 'Ginjal',
      value: 'Ginjal',
    },
    {
      model: 'riwayatPenyakitDahulu',
      label: 'Hipertensi',
      value: 'Hipertensi',
    },
    {
      model: 'riwayatPenyakitDahulu',
      label: 'TBC',
      value: 'TBC',
    },
    {
      model: 'riwayatPenyakitDahulu',
      label: 'Jantung',
      value: 'Jantung',
    },
    {
      model: 'riwayatPenyakitDahulu',
      label: 'Keganasan',
      value: 'Keganasan',
    },
    {
      model: 'riwayatPenyakitDahulu',
      label: 'Lain-lain',
      value: 'LainLain',
    },
  ]
}

export function riwayatPenyakitDahuluAnak(): any {
  return [
    {
      model: 'riwayatPenyakitDahuluAnak',
      label: 'Demam',
      value: 'Demam',
    },
    {
      model: 'riwayatPenyakitDahuluAnak',
      label: 'Kejang',
      value: 'Kejang',
    },
    {
      model: 'riwayatPenyakitDahuluAnak',
      label: 'Batuk Pilek',
      value: 'BatukPilek',
    },
    {
      model: 'riwayatPenyakitDahuluAnak',
      label: 'Mimisan',
      value: 'Mimisan',
    },
  ]
}
export function statusPsikologisPasien(): any {
  return [
    {
      model: 'statusPsikologisPasien',
      label: 'Tenang',
      value: 'Tenang',
    },
    {
      model: 'statusPsikologisPasien',
      label: 'Cemas',
      value: 'Cemas',
    },
    {
      model: 'statusPsikologisPasien',
      label: 'Takut',
      value: 'Takut',
    },
    {
      model: 'statusPsikologisPasien',
      label: 'Marah',
      value: 'Marah',
    },
    {
      model: 'statusPsikologisPasien',
      label: 'Sedih',
      value: 'Sedih',
    },
  ]
}

export function caraMasukRS(): any {
  return [
    {
      model: 'caraMasukRS',
      label: 'IRJ',
      value: 'Tenang',
    },
    {
      model: 'caraMasukRS',
      label: 'IGD',
      value: 'Cemas',
    },
    {
      model: 'caraMasukRS',
      label: 'IGH',
      value: 'Takut',
    },
    {
      model: 'caraMasukRS',
      label: 'Dokter Pribadi',
      value: 'DokterPribadi',
    },
    {
      model: 'caraMasukRS',
      label: 'Lain-Lain',
      value: 'LainLain',
    },
  ]
}

export function tibaRS(): any {
  return [
    {
      model: 'tibaRS',
      label: 'Jalan',
      value: 'Jalan',
    },
    {
      model: 'tibaRS',
      label: 'Kursi Roda',
      value: 'KursiRoda',
    },
    {
      model: 'tibaRS',
      label: 'Brankar',
      value: 'Brankar',
    },
    {
      model: 'tibaRS',
      label: 'Inkubator',
      value: 'Inkubator',
    },
  ]
}

export function macamTrauma(): any {
  return [
    {
      model: 'macamTrauma',
      label: 'KLL',
      value: 'KLL',
      isDewasaAnak: true,
      isDewasa: true,
    },
    {
      model: 'macamTrauma',
      label: 'KDRT',
      value: 'KDRT',
      isDewasaAnak: true,
      isDewasa: true,
    },
    {
      model: 'macamTrauma',
      label: 'Child Abuse (Kekerasan Anak)',
      value: 'ChildAbuse(Kekerasan Anak)',
      isDewasaAnak: true,
      isDewasa: true,
    },
    {
      model: 'macamTrauma',
      label: 'Kecelakaan Kerja',
      value: 'KecelakaanKerja',
      isDewasaAnak: false,
      isDewasa: true,
    },
  ]
}

export function vitalSign(): any {
  return [
    {
      label: 'Tekanan Darah',
      model: 'tekananDarah',
      addon: 'mmHG',
    },
    {
      label: 'Frekuensi Nadi',
      model: 'nadi',
      addon: 'x/mnt',
    },
    {
      label: 'Suhu',
      model: 'suhu',
      addon: '°C',
    },
    {
      label: 'Frekuensi Nafas',
      model: 'pernapasan',
      addon: 'x/mnt',
    },
    {
      label: 'Berat Badan',
      model: 'beratBadan',
      addon: 'kg',
    },
    {
      label: 'Tinggi Badan',
      model: 'tinggiBadan',
      addon: 'cm',
    },
    {
      label: 'Lingkar Kepala',
      model: 'lingkarKepala',
      addon: 'cm',
    },
  ]
}
export function vitalSignBayi(): any {
  return [
    {
      label: 'Tekanan Darah',
      model: 'tekananDarah',
      addon: 'mmHG',
    },
    {
      label: 'Frekuensi Nadi',
      model: 'nadi',
      addon: 'x/mnt',
    },
    {
      label: 'Suhu',
      model: 'suhu',
      addon: '°C',
    },
    {
      label: 'Frekuensi Nafas',
      model: 'pernapasan',
      addon: 'x/mnt',
    },
    {
      label: 'Berat Badan',
      model: 'beratBadan',
      addon: 'g',
    },
    {
      label: 'Tinggi Badan',
      model: 'tinggiBadan',
      addon: 'cm',
    },
    {
      label: 'Lingkar Kepala',
      model: 'lingkarKepala',
      addon: 'cm',
    },
  ]
}

export function skriningGizi(): any {
  return [
    {
      label:
        ' 1. Apakah pasien mengalami penurunan BB yang tidak diinginkan dalam 6 bulan terakhir?',
      children: [
        {
          label: 'a. Tidak ada penurunan berat badan',
          type: 'checkbox',
          model: 'tidakAdaTurunBeratBadan',
          text :"Ya",
          value: 0,
        },
        {
          label: 'b. Tidak yakin / tidak tahu/ terasa baju lebih longgar',
          type: 'checkbox',
          text :"Ya",
          model: 'tidakAdaTurunBeratBadan',
          value: 2,
        },
        {
          label: 'c. Jika ya, berapa penurunan berat badan tersebut',
          children: [
            {
              type: 'checkbox',
              label: '1 - 5 kg',
              model: 'turunBeratBadan',
              value: 1,
            },
            {
              type: 'checkbox',
              label: '6 - 10 kg',
              model: 'turunBeratBadan',
              value: 2,
            },
            {
              type: 'checkbox',
              label: '11 - 15 kg',
              model: 'turunBeratBadan',
              value: 3,
            },
            {
              type: 'checkbox',
              label: '> 15 kg',
              model: 'turunBeratBadan',
              value: 4,
            },
          ],
        },
      ],
    },
    {
      label: ' 2. Apakah asupan makan berkurang karena tidak nafsu makan?',
      children: [
        {
          text :"Ya",
          type: 'checkbox',
          model: 'asupanMakan',
          value: 1,
        },
        {
          text :"Tidak",
          type: 'checkbox',
          model: 'asupanMakan',
          value: 0,
        },
      ],
    },
  ]
}

export function diagnosaKhusus(): any {
  return [
    {
      label: 'DM',
      model: 'diagnosaKhusus',
      type: 'checkbox',
      value: 'dm',
    },
    {
      label: 'Kemoterapi',
      model: 'diagnosaKhusus',
      type: 'checkbox',
      value: 'Kemoterapi',
    },
    {
      label: 'Hemodialisa',
      model: 'diagnosaKhusus',
      type: 'checkbox',
      value: 'Hemodialisa',
    },
    {
      label: 'Geriatri',
      model: 'diagnosaKhusus',
      type: 'checkbox',
      value: 'Geriatri',
    },
    {
      label: 'Immunitas menurun',
      model: 'diagnosaKhusus',
      type: 'checkbox',
      value: 'immunitasmMnurun',
    },
    {
      label: 'Lain-lain',
      model: 'diagnosaKhusus',
      type: 'checkbox',
      value: 'lain_lain',
    },
  ]
}
export function statusFungsional(): any {
  return [
    {
      label: 'Mandiri',
      value: 'mandiri',
      model: 'statusFungsional',
    },
    {
      label: 'Perlu bantuan',
      value: 'perluBantuan',
      model: 'statusFungsional',
    },
    {
      label: 'Alat Bantu',
      value: 'alatBantu',
      model: 'statusFungsional',
    },
    {
      label: 'Prothesa',
      value: 'prothesa',
      model: 'statusFungsional',
    },
    {
      label: 'Cacat Tubuh',
      value: 'cacatTubuh',
      model: 'statusFungsional',
    },
    {
      label: 'Ketergantungan Total',
      value: 'ketergantunganTotal',
      model: 'statusFungsional',
    },
  ]
}

export function skriningResikoJatuh(): any {
  return [
    {
      title:
        'Perhatikan cara berjalan pasien saat akan duduk di kursi. Apakah pasien tampak tidak seimbang (sempoyongan / limbung) ?',
      value: [
        {
          subTitle: 'Ya',
          value: 'ya',
          type: 'checkBox',
          model: 'pasienTidakSeimbang',
        },
        {
          subTitle: 'Tidak',
          type: 'checkBox',
          value: 'tidak',
          model: 'pasienTidakSeimbang',
        },
      ],
    },
    {
      title:
        'Apakah pasien memegang pinggiran kursi atau meja atau benda lain sebagai penopang saat akan duduk?',
      value: [
        {
          subTitle: 'Ya',
          value: 'ya',
          type: 'checkBox',
          model: 'butuhBantuanJalan',
        },
        {
          subTitle: 'Tidak',
          type: 'checkBox',
          value: 'tidak',
          model: 'butuhBantuanJalan',
        },
      ],
    },
    {
      title: 'Hasil',
      value: [
        {
          subTitle: 'Tidak Berisiko (tidak ditemukan a dan b)',
          type: 'checkBox',
          value: 'tidakBerisiko',
          model: 'resikoPasien',
        },
        {
          subTitle: 'Risiko Tinggi (a dan b ditemukan)',
          type: 'checkBox',
          value: 'resikoTinggi',
          model: 'resikoPasien',
        },
        {
          subTitle: 'Risiko Rendah (ditemukan a atau b)',
          type: 'checkBox',
          value: 'resikoRendah',
          model: 'resikoPasien',
        },
      ],
    },
  ]
}
export function skriningNyeri(): any {
  return [
    {
      label: '',
      children: [
        {
          label: 'Tidak ada nyeri',
          type: 'checkbox',
          model: 'statusNyeri',
          value: 'tidakAdaNyeri',
        },
        {
          label: 'Nyeri kronis',
          type: 'checkbox',
          model: 'statusNyeri',
          value: 'nyeriKronis',
        },
        {
          label: 'Nyeri Akut',
          type: 'checkbox',
          model: 'statusNyeri',
          value: 'nyeriAkut',
        },
        {
          label: 'Skala Nyeri',
          type: 'text',
          model: 'sklanyeri',
        },
        {
          label: 'Lokasi',
          type: 'text',
          model: 'lokasiNyeri',
        },
        {
          label: 'Durasi',
          type: 'text',
          model: 'durasiNyeri',
        },
        {
          label: 'Frekuensi',
          type: 'text',
          model: 'frekuensiNyeri',
        },
      ],
    },
    {
      label: 'Nyeri hilang,bila :',
      children: [
        {
          label: 'Minum Obat',
          type: 'checkbox',
          model: 'penghilangNyeri',
          value: 'minumObat',
        },
        {
          label: 'Mendengarkan Musik',
          type: 'checkbox',
          model: 'penghilangNyeri',
          value: 'mendengarkanMusik',
        },
        {
          label: 'Istirahat',
          type: 'checkbox',
          model: 'penghilangNyeri',
          value: 'istirahat',
        },
        {
          label: 'Berubah posisi / tidur',
          type: 'checkbox',
          model: 'penghilangNyeri',
          value: 'berubahPosisiTidur',
        },
        {
          label: 'Lain-lain',
          type: 'checkbox',
          model: 'penghilangNyeri',
          value: 'lainLain',
        },
        {
          label: 'Sebutkan',
          type: 'text',
          model: 'penghilangNyeriLain',
        },
      ],
    },
    {
      label: 'Diberitahuan ke dokter :',
      children: [
        {
          label: 'Ya',
          type: 'checkbox',
          model: 'nyeriDiberitahukanKeDokter',
          value: 'ya',
        },
        {
          label: 'Pukul',
          type: 'date',
          model: 'waktuNyeriDiberitahukanKeDokter',
        },
        {
          label: 'Tidak',
          type: 'checkbox',
          model: 'nyeriDiberitahukanKeDokter',
          value: 'tidak',
        },
      ],
    },
  ]
}
export function detailKeperawatan(): any {
  return [
    // {
    //   label: 'DAFTAR MASALAH',
    //   placeholder: 'Daftar Masalah...',
    //   model: 'daftarMasalah',
    // },
    {
      label: 'RENCANA KEPERAWATAN',
      placeholder: 'Rencana keperawatan...',
      model: 'rencanaKeperawatan',
    },
    {
      label: 'INSTRUKSI KEPERAWATAN',
      placeholder: 'Intruksi keperawatan...',
      model: 'intruksiKeperawatan',
    },
    {
      label: 'SASARAN',
      placeholder: 'Sasaran...',
      model: 'sasaran',
    },
  ]
}
export function skriningGizi2(): any {
  return [
    {
      label: '',
      chilren: [],
    },
    {
      label: '',
      chilren: [],
    },
    {
      label: '',
      chilren: [],
    },
  ]
}

export function parameter() : any{
  return [
    {
      'no': 1,
      "parameter" : "Umur",
      "skala" : " 4 : <  3 tahun<br> 3: 3 – 7   Tahun<br> 2: 8 – 13 Tahun<br> 1: 14 – 18 tahun",
      "model": 'risikoJatuhAnak',
    },
    {
      'no': 2,
      "parameter" : "Jenis Kelamin",
      "skala" : " 2 : Laki-laki<br> 1: Perempuan",
      "model": 'risikoJatuhAnak',
    },
    {
      'no': 3,
      "parameter" : "Diagnosis",
      "skala" : "4 : Kelainan Neorologi<br>3 : Gangguan Oksigenasi (Pernafasan,Anemi Dehidrasi,Anoreksia,Sinkop, Sakit Kepala<br>2 : Kelemahan Fisik / Kelainan Psikis<br>1 : Diagnosis Lain",
      "model": 'risikoJatuhAnak',
    },
    {
      'no': 4,
      "parameter" : "Gangguan Kognitif",
      "skala" : "3 : Tidak Memahami Keterbatasan<br>2 : Lupa Keterbatasan<br>1 : Orientasi terhadap Kelemahan",
      "model": 'risikoJatuhAnak',
    },
    {
      'no': 5,
      "parameter" : "Lingkungan",
      "skala" : "4 : Riwayat jatuh dari Tempat Tidur saat Bayi-Anak<br>3 : Menggunakan alat bantu (Box / Mebel)<br>2 : Pasien berada di tempat tidur<br>1 : Pasien berada diluar area ruang perawatan",
      "model": 'risikoJatuhAnak',
    },
    {
      'no': 6,
      "parameter" : "Respon Op",
      "skala" : "3 : < 24 Jam<br>2 : < 48 Jam<br>1 : > 48 Jam",
      "model": 'risikoJatuhAnak',
    },
    {
      'no': 7,
      "parameter" : "Penggunaan Obat",
      "skala" : "3 : Obat Sedatif, Hipnotik,Barbiturat,Phenotiazin<br>2 : Salah Satu Obat Diatas<br>1 : Pengobatan lain",
      "model": 'risikoJatuhAnak',
    },
  ]
}

export function imgNyeri(): any {
  return {
    nama: 'Hurts',
    detail: [
      {
        nama: 'No Hurt',
        descNilai: 0,
        img: '/images/skalanyeri/1.png',
      },
      {
        nama: 'Hurts Little Bit',
        descNilai: 2,
        img: '/images/skalanyeri/2.png',
      },
      {
        nama: 'Hurts Little More',
        descNilai: 4,
        img: '/images/skalanyeri/3.png',
      },
      {
        nama: 'Hurts Even More',
        descNilai: 6,
        img: '/images/skalanyeri/4.png',
      },
      {
        nama: 'Hurts Whole Lot',
        descNilai: 8,
        img: '/images/skalanyeri/5.png',
      },
      {
        nama: 'Hurts whorts',
        descNilai: 10,
        img: '/images/skalanyeri/6.png',
      },
    ],
  }
}
export function skoringNyeri(): any {
  return {
    nama: 'Score ',
    detail: [
      { nama: '0 - 1 = Tidak Ada Nyeri', descNilai: 0 },
      { nama: '2 - 3 = Sedikit Nyeri', descNilai: 2 },
      { nama: '4 - 5 = Cukup Nyeri', descNilai: 4 },
      { nama: '6 - 7 = Lumayan Nyeri', descNilai: 6 },
      { nama: '8 - 9 = Sangat Nyeri', descNilai: 8 },
      { nama: '10 = Amat Sangat Nyeri', descNilai: 10 },
    ],
  }
}

