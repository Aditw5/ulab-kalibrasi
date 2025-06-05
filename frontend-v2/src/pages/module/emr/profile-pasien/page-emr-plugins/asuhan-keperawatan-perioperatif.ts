export function Kesadaran(): any {
  return [
    {
      "value": "Compos Mentis",
      "model": "kesadaran"
    },
    {
      "value": "Apatis",
      "model": "kesadaran"
    },
    {
      "value": "Delirium",
      "model": "kesadaran"
    },
    {
      "value": "Somnolen",
      "model": "kesadaran"
    },
    {
      "value": "Stupor",
      "model": "kesadaran"
    },
    {
      "value": "Koma",
      "model": "kesadaran"
    },
  ]
}

export function statusEmosi(): any {
  return [
    {
      "value": "Cemas",
      "model": "statusEmosi",
    },
    {
      "value": "Tenang",
      "model": "statusEmosi",
    },
  ]
}

export function penilaianNyeri(): any {
  return [
    {
      "value":"Nyeri",
      "model": "nyeri",
      "type" : "radio",
      "listValue":[
        {
          "value":"Tidak"
        },
        {
          "value":"Iya"
        },
      ]
    },
    {
      "value" : "Pencetus",
      "model" : "pencetus",
      "type" : "text",
    },
    {
      "value" : "Gambaran Nyeri",
      "model" : "gambaranNyeri",
      "type" : "text",
    },
    {
      "value" : "Lokasi Nyeri",
      "model" : "lokasiNyeri",
      "type" : "text",
    },
    {
      "value" : "Durasi",
      "model" : "durasi",
      "type" : "text",
    },
    {
      "value" : "Skala Nyeri",
      "model" : "skalaNyeri",
      "type" : "text",
    },
    {
      "value":"Metode",
      "model": "metode",
      "type" : "radio",
      "listValue":[
        {
          "value":"VAS"
        },
        {
          "value":"BPS"
        },
        {
          "value":"FLACC"
        },
      ]
    },
  ]
}

export function bladder(): any{
  return [
    {
      "model":"dowerKateter",
      "type" : "radio",
      "listValue":[
        {

          "label": "Pakai Dower Kateter",
          "value":"PakaiDowerKateter"
        },
      ]
    },
    {
      "value" : "Jumlah Urine (cc)",
      "model" : "jumlahUrine",
      "type" : "text",
    },
    {
      "value" : "Lainnya",
      "model" : "bladderLainnya",
      "type" : "text",
    },
  ]
}

export function bowel(): any{
  return [
    {
      "value" : "BB (Kg)",
      "model" : "bowelBB",
      "type" : "text",
    },
    {
      "value" : "TB (cm)",
      "model" : "bowlTB",
      "type" : "text",
    },
    {
      "model":"bowel",
      "type" : "radio",
      "listValue":[
        {
          "label": "Puasa",
          "value":"puasa"
        },
        {
          "label": "Distensi",
          "value":"distensi"
        },
        {
          "label": "Mual",
          "value":"mual"
        },
        {
          "label": "Muntah",
          "value":"muntah"
        },
        {
          "label": "Sulit Menelan",
          "value":"SulitMenelan"
        },
      ]
    },
  ]
}
export function bone(): any{
  return [
    {
      "value":"Integritas Kulit",
      "model": "integritasKulit",
      "type" : "radio",
      "listValue":[
        {
          "label": "Utuh",
          "value":"Utuh"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "value":"Tulang",
      "model": "tulang",
      "type" : "radio",
      "listValue":[
        {
          "label": "Patah",
          "value":"Patah"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
  ]
}

export function masalahPreOperatif(): any {
  return [
    {
      model: 'masalahPreOperatif_1',
      label: 'Cemas',
      value: 'cemas',
    },
    {
      model: 'masalahPreOperatif_2',
      label: 'Kurang Pengetahuan',
      value: 'KurangPengetahuan',
    },
    {
      model: 'masalahPreOperatif_3',
      label: 'Gangguan Integritas Kulit',
      value: 'GangguanIntegritasKulit',
    },
    {
      model: 'masalahPreOperatif_4',
      label: 'Penurunan Kesadaran',
      value: 'PenurunanKesadaran',
    },
    {
      model: 'masalahPreOperatif_5',
      label: 'Risiko Gangguan Cairan',
      value: 'RisikoGangguanCairan',
    },
    {
      model: 'masalahPreOperatif_6',
      label: 'Nyeri',
      value: 'Nyeri',
    },
    {
      model: 'masalahPreOperatif_7',
      label: 'Resiko Cedera',
      value: 'ResikoCedera',
    },
  ]
}

export function masalahIntraOperatif(): any {
  return [
    {
      model: 'masalahIntraOperatif_1',
      label: 'Resiko Infeksi',
      value: 'ResikoInfeksi',
    },
    {
      model: 'masalahIntraOperatif_2',
      label: 'Resiko Injury',
      value: 'ResikoInjury',
    },
    {
      model: 'masalahIntraOperatif_3',
      label: 'Resiko Hipotermi',
      value: 'ResikoHipotermi',
    },
    {
      model: 'masalahIntraOperatif_4',
      label: 'Cemas',
      value: 'Cemas',
    },
    {
      model: 'masalahIntraOperatif_5',
      label: 'Gangguan Integritas Kulit',
      value: 'GangguanIntegritasKulit',
    },
    {
      model: 'masalahIntraOperatif_6',
      label: 'Kebersihan Jalan Nafas Tidak Efektif',
      value: 'KebersihanJalanNafasTidakEfektif',
    },
    {
      model: 'masalahIntraOperatif_7',
      label: 'Resiko Gangguan Cairan',
      value: 'ResikoGangguanCairan',
    },
    {
      model: 'masalahIntraOperatif_1',
      label: 'Nyeri',
      value: 'Nyeri',
    },
  ]
}

export function masalahPostOperatif(): any {
  return [
    {
      model: 'masalahPostOperatif_1',
      label: 'Nyeri',
      value: 'nyeri',
    },
    {
      model: 'masalahPostOperatif_2',
      label: 'Bersihan jalan nafas',
      value: 'bersihanJalanNafas',
    },
    {
      model: 'masalahPostOperatif_3',
      label: 'Resiko Injury',
      value: 'resikoInjury',
    },
    {
      model: 'masalahPostOperatif_4',
      label: 'Resiko Hopotermi',
      value: 'resikoHopotermi',
    },
    {
      model: 'masalahPostOperatif_5',
      label: 'Cemas',
      value: 'cemas',
    },
    {
      model: 'masalahPostOperatif_6',
      label: 'Gangguan Integritas Kulit',
      value: 'gangguanIntegritasKulit',
    },
    {
      model: 'masalahPostOperatif_7',
      label: 'Resiko gangguan cairan',
      value: 'resikoGangguanCairan',
    },
  ]
}

export function rencanaTindakanPreOperatif(): any {
  return [
      {
        "model": "rencanaTindakanPreOperatif_1",
        "label": "Laksanakan protap interaksi social",
        "value": "LaksanakanProtapInteraksiSocial"
      },
      {
        "model": "rencanaTindakanPreOperatif_2",
        "label": "Cek kelengkapan dokumen pre operasi",
        "value": "CekKelengkapanDokumenPreOperasi"
      },
      {
        "model": "rencanaTindakanPreOperatif_3",
        "label": "Laksanakan orientasi pre operasi HE prosedur tindakan",
        "value": "LaksanakanOrientasiPreOperasiHEProsedurTindakan"
      },
      {
        "model": "rencanaTindakanPreOperatif_4",
        "label": "Observasi vital sign dan keadaan umum pasien",
        "value": "ObservasiVitalSignDanKeadaanUmumPasien"
      },
      {
        "model": "rencanaTindakanPreOperatif_5",
        "label": "Kolaborasi pemasangan cairan intra Vena",
        "value": "KolaborasiPemasanganCairanIntraVena"
      },
      {
        "model": "rencanaTindakanPreOperatif_6",
        "label": "Berikan posisi nyaman",
        "value": "BerikanPosisiNyaman"
      },
      {
        "model": "rencanaTindakanPreOperatif_7",
        "label": "Siapkan mesin anesthesia",
        "value": "SiapkanMesinAnesthesia"
      },
      {
        "model": "rencanaTindakanPreOperatif_8",
        "label": "Siapkan Alat dan obat-obatan anesthesi",
        "value": "SiapkanAlatDanObatObatanAnesthesi"
      },
      {
        "model": "rencanaTindakanPreOperatif_9",
        "label": "Kolaborasi pemberian premedikasi",
        "value": "KolaborasiPemberianPremedikasi"
      },
      {
        "model": "rencanaTindakanPreOperatif_10",
        "label": "Monitor efek pemberian Premedikasi",
        "value": "MonitorEfekPemberianPremedikasi"
      },
      {
        "model": "rencanaTindakanPreOperatif_11",
        "label": "Siapkan alat dan obat sesuai pembedahan",
        "value": "SiapkanAlatDanObatSesuaiPembedahan"
      },
      {
        "model": "rencanaTindakanPreOperatif_12",
        "label": "Lakukan sign In",
        "value": "LakukanSignIn"
      },
      {
        "model": "rencanaTindakanPreOperatif_13",
        "label": "Kolaborasi pemberian antibiotika",
        "value": "KolaborasiPemberianAntibiotika"
      }
    ]
}

export function rencanaTindakanIntraOperatif(): any {
  return [
      {
        "model": "rencanaTindakanIntraOperatif_1",
        "label": "Siapkan Lingkungan Kamar Operasi",
        "value": "SiapkanLingkunganKamarOperasi"
      },
      {
        "model": "rencanaTindakanIntraOperatif_2",
        "label": "Pasang Alat Penghangat",
        "value": "PasangAlatPenghangat"
      },
      {
        "model": "rencanaTindakanIntraOperatif_3",
        "label": "Siapkan Pasien Dimeja Operasi",
        "value": "SiapkanPasienDimejaOperasi"
      },
      {
        "model": "rencanaTindakanIntraOperatif_4",
        "label": "Observasi Vital Sign",
        "value": "ObservasiVitalSignn"
      },
      {
        "model": "rencanaTindakanIntraOperatif_5",
        "label": "Siapkan Instrument dan Linen",
        "value": "SiapkanInstrumentdanLinen"
      },
      {
        "model": "rencanaTindakanIntraOperatif_6",
        "label": "Asisteni Dokter Anestesi untuk GA/RA",
        "value": "AsisteniDokterAnestesiuntukGARA"
      },
      {
        "model": "rencanaTindakanIntraOperatif_7",
        "label": "Posisikan Pasien Sesuaikan Pembedahan",
        "value": "PosisikanPasienSesuaikanPembedahan"
      },
      {
        "model": "rencanaTindakanIntraOperatif_8",
        "label": "Laksanakan Standard Precution Pembedahan",
        "value": "LaksanakanStandardPrecutionPembedahan"
      },
      {
        "model": "rencanaTindakanIntraOperatif_9",
        "label": "Lakukan Penghitungan Awal Instrument Gaas dan Kelengkapannya",
        "value": "LakukanPenghitunganAwalInstrumentGaasdanKelengkapannya"
      },
      {
        "model": "rencanaTindakanIntraOperatif_10",
        "label": "Observasi Pemasangan Packing Ditenggorokan",
        "value": "ObservasiPemasanganPackingDitenggorokan"
      },
      {
        "model": "rencanaTindakanIntraOperatif_11",
        "label": "Observasi Pemakaian Torniquet",
        "value": "ObservasiPemakaianTorniquet"
      },
      {
        "model": "rencanaTindakanIntraOperatif_12",
        "label": "Pasang Diametry dan Awasi Kondisi Kulit Tempat Pemasangan",
        "value": "PasangDiametrydanAwasiKondisiKulitTempatPemasangan"
      },
      {
        "model": "rencanaTindakanIntraOperatif_13",
        "label": "Lakukan Skin Preparation",
        "value": "LakukanSkinPreparation"
      },
      {
        "model": "rencanaTindakanIntraOperatif_14",
        "label": "Lakukan Time Out",
        "value": "LakukanTimeOut"
      },
      {
        "model": "rencanaTindakanIntraOperatif_15",
        "label": "Monitor Intake Output",
        "value": "MonitorIntakeOutput"
      },
      {
        "model": "rencanaTindakanIntraOperatif_16",
        "label": "Cuci Luka",
        "value": "CuciLuka"
      },
      {
        "model": "rencanaTindakanIntraOperatif_17",
        "label": "Lakukan Penghitungan Awal Instrument Gaas dan Kelengkapannya",
        "value": "LakukanPenghitunganAwalInstrumentGaasdanKelengkapannya2"
      },
      {
        "model": "rencanaTindakanIntraOperatif_18",
        "label": "Lakukan Sign Out",
        "value": "LakukanSignOut"
      },
      {
        "model": "rencanaTindakanIntraOperatif_19",
        "label": "Rawat Luka",
        "value": "RawatLuka"
      },
      {
        "model": "rencanaTindakanIntraOperatif_20",
        "label": "Asistensi Pengakhiran Anesthesi",
        "value": "AsistensiPengakhiranAnesthesi"
      },
      {
        "model": "rencanaTindakanIntraOperatif_21",
        "label": "Rapikan Alat Anesthesi",
        "value": "RapikanAlatAnesthesi"
      },
      {
        "model": "rencanaTindakanIntraOperatif_22",
        "label": "Cek Bahan Spesimen",
        "value": "CekBahanSpesimen"
      }
    ]
}

export function rencanaTindakanPostOperatif(): any {
  return [
      {
        "model": "rencanaTindakanPostOperatif_1",
        "label": "Kaji Skala Nyeri",
        "value": "KajiSkalaNyeri"
      },
      {
        "model": "rencanaTindakanPostOperatif_2",
        "label": "Observasi Vital Sign",
        "value": "ObservasiVitalSign"
      },
      {
        "model": "rencanaTindakanPostOperatif_3",
        "label": "Beri Posisi yang nyaman",
        "value": "BeriPosisiYangNyaman"
      },
      {
        "model": "rencanaTindakanPostOperatif_4",
        "label": "Observasi kondisi luka operasi",
        "value": "ObservasiKondisiLukaOperasi"
      },
      {
        "model": "rencanaTindakanPostOperatif_5",
        "label": "Beri selimut hangat",
        "value": "BeriSelimutHangat"
      },
      {
        "model": "rencanaTindakanPostOperatif_6",
        "label": "Observasi intake Output",
        "value": "ObservasiIntakeOutput"
      },
      {
        "model": "rencanaTindakanPostOperatif_7",
        "label": "Kolaborasi pemberian therapy O²",
        "value": "KolaborasiPemberianTherapyO2"
      },
      {
        "model": "rencanaTindakanPostOperatif_8",
        "label": "Kolaborasi pemberian Analgetik",
        "value": "KolaborasiPemberianAnalgetik"
      },
      {
        "model": "rencanaTindakanPostOperatif_9",
        "label": "Serah Terima Dengan Petugas Ruangan",
        "value": "SerahTerimaDenganPetugasRuangan"
      }
    ]
}

export function implementasiPreOperatif(): any {
  return [
    {
      "model": "implementasiPreOperatif_1",
      "label": "Perkenalan diri petugas OK ke pasien",
      "value": "PerkenalanDiriPetugasOKKePasien"
    },
    {
      "model": "implementasiPreOperatif_2",
      "label": "Operan pasien dan kelengkapan dokumen",
      "value": "OperanPasienDanKelengkapanDokumen"
    },
    {
      "model": "implementasiPreOperatif_3",
      "label": "Memberikan orientasi dan informasi lingkungan dan proses operasi",
      "value": "MemberikanOrientasiDanInformasiLingkunganDanProsesOperasi"
    },
    {
      "model": "implementasiPreOperatif_4",
      "label": "Mengobservasi vital sign dan keadaan umum pasien",
      "value": "MengobservasiVitalSignDanKeadaanUmumPasien"
    },
    {
      "model": "implementasiPreOperatif_5",
      "label": "Memasang akses intra Vena",
      "value": "MemasangAksesIntraVena"
    },
    {
      "model": "implementasiPreOperatif_6",
      "label": "Memberikan posisi yang nyaman",
      "value": "MemberikanPosisiYangNyaman"
    },
    {
      "model": "implementasiPreOperatif_7",
      "label": "Menyiapkan mesin anesthesi",
      "value": "MenyiapkanMesinAnesthesi"
    },
    {
      "model": "implementasiPreOperatif_8",
      "label": "Menyiapkan alat dan obat anesthesi",
      "value": "MenyiapkanAlatDanObatAnesthesi"
    },
    {
      "model": "implementasiPreOperatif_9",
      "label": "Membantu pemberian premedikasi dan mengobservasi efeknya",
      "value": "MembantuPemberianPremedikasiDanMengobservasiEfeknya"
    },
    {
      "model": "implementasiPreOperatif_10",
      "label": "Memonitor efek pemberian Premedikasi",
      "value": "MemonitorEfekPemberianPremedikasi"
    },
    {
      "model": "implementasiPreOperatif_11",
      "label": "Menyiapkan alat dan obat sesuai pembedahan",
      "value": "MenyiapkanAlatDanObatSesuaiPembedahan"
    },
    {
      "model": "implementasiPreOperatif_12",
      "label": "Melakukan sign In",
      "value": "MelakukanSignIn"
    },
    {
      "model": "implementasiPreOperatif_13",
      "label": "Memberian antibiotika sesuai instruksi dokter",
      "value": "MemberianAntibiotikaSesuaiInstruksiDokter"
    }
  ]
}

export function implementasiIntraOperatif(): any {
  return [
    {
      "model": "implementasiIntraOperatif_1",
      "label": "Menyiapkan Lingkungan Kamar Operasi",
      "value": "MenyiapkanLingkunganKamarOperasi"
    },
    {
      "model": "implementasiIntraOperatif_2",
      "label": "Memasang Alat Penghangat",
      "value": "MemasangAlatPenghangat"
    },
    {
      "model": "implementasiIntraOperatif_3",
      "label": "Menyiapkan Pasien Dimeja Operasi",
      "value": "MenyiapkanPasienDimejaOperasi"
    },
    {
      "model": "implementasiIntraOperatif_4",
      "label": "Mengobservasi Vital Sign",
      "value": "MengobservasiVitalSignn"
    },
    {
      "model": "implementasiIntraOperatif_5",
      "label": "Menyiapkan Instrument dan Linen",
      "value": "MenyiapkanInstrumentdanLinen"
    },
    {
      "model": "implementasiIntraOperatif_6",
      "label": "Mengasisteni Dokter Anestesi untuk GA/RA",
      "value": "MengasisteniDokterAnestesiuntukGARA"
    },
    {
      "model": "implementasiIntraOperatif_7",
      "label": "Memposisikan Pasien Sesuaikan Pembedahan",
      "value": "MemposisikanPasienSesuaikanPembedahan"
    },
    {
      "model": "implementasiIntraOperatif_8",
      "label": "Melaksanakan Standard Precution Pembedahan",
      "value": "MelaksanakanStandardPrecutionPembedahan"
    },
    {
      "model": "implementasiIntraOperatif_9",
      "label": "Melakukan Penghitungan Awal Instrument Gaas dan Kelengkapannya",
      "value": "MelakukanPenghitunganAwalInstrumentGaasdanKelengkapannya"
    },
    {
      "model": "implementasiIntraOperatif_10",
      "label": "Mengobservasi Pemasangan Packing Ditenggorokan",
      "value": "MengobservasiPemasanganPackingDitenggorokan"
    },
    {
      "model": "implementasiIntraOperatif_11",
      "label": "Mengobservasi Pemakaian Torniquet",
      "value": "MengobservasiPemakaianTorniquet"
    },
    {
      "model": "implementasiIntraOperatif_12",
      "label": "Memasang Diametry dan Awasi Kondisi Kulit Tempat Pemasangan",
      "value": "MemasangDiametrydanAwasiKondisiKulitTempatPemasangan"
    },
    {
      "model": "implementasiIntraOperatif_13",
      "label": "Melakukan Skin Preparation",
      "value": "MelakukanSkinPreparation"
    },
    {
      "model": "implementasiIntraOperatif_14",
      "label": "Melakukan Time Out",
      "value": "MelakukanTimeOut"
    },
    {
      "model": "implementasiIntraOperatif_15",
      "label": "Memonitor Intake Output",
      "value": "MemonitorIntakeOutput"
    },
    {
      "model": "implementasiIntraOperatif_16",
      "label": "Mencuci Luka",
      "value": "MencuciLuka"
    },
    {
      "model": "implementasiIntraOperatif_17",
      "label": "Melakukan Penghitungan Awal Instrument Gaas dan Kelengkapannya",
      "value": "MelakukanPenghitunganAwalInstrumentGaasdanKelengkapannya2"
    },
    {
      "model": "implementasiIntraOperatif_18",
      "label": "Melakukan Sign Out",
      "value": "MelakukanSignOut"
    },
    {
      "model": "implementasiIntraOperatif_19",
      "label": "Merawat Luka",
      "value": "MerawatLuka"
    },
    {
      "model": "implementasiIntraOperatif_20",
      "label": "Mengasistensi Pengakhiran Anesthesi",
      "value": "MengasistensiPengakhiranAnesthesi"
    },
    {
      "model": "implementasiIntraOperatif_21",
      "label": "Merapikan Alat Anesthesi",
      "value": "MerapikanAlatAnesthesi"
    },
    {
      "model": "implementasiIntraOperatif_22",
      "label": "Mengecek Bahan Spesimen",
      "value": "MengecekBahanSpesimen"
    }
  ]
}

export function implementasiPostOperatif(): any {
  return [
    {
      "model": "implementasiPostOperatif_1",
      "label": "Mengkaji Skala Nyeri",
      "value": "MengkajiSkalaNyeri"
    },
    {
      "model": "implementasiPostOperatif_2",
      "label": "Mengobservasi Vital Sign",
      "value": "MengobservasiVitalSign"
    },
    {
      "model": "implementasiPostOperatif_3",
      "label": "Memberi posisi yang nyaman",
      "value": "MemberiPosisiYangNyaman"
    },
    {
      "model": "implementasiPostOperatif_4",
      "label": "Mengobservasi kondisi Luka Operasi",
      "value": "MengobservasiKondisiLukaOperasi"
    },
    {
      "model": "implementasiPostOperatif_5",
      "label": "Memberi Selimut Hangat",
      "value": "MemberiSelimutHangat"
    },
    {
      "model": "implementasiPostOperatif_6",
      "label": "Mengukur Intake Output",
      "value": "MengukurIntakeOutput"
    },
    {
      "model": "implementasiPostOperatif_7",
      "label": "Melakukan kolaborasi pemberian O²",
      "value": "MelakukanKolaborasiPemberianO2"
    },
    {
      "model": "implementasiPostOperatif_8",
      "label": "Melakukan kolaborasi pemberian Analgetik",
      "value": "MelakukanKolaborasiPemberianAnalgetik"
    },
    {
      "model": "implementasiPostOperatif_9",
      "label": "Melakukan serah terima dengan petugas ruangan",
      "value": "MelakukanSerahTerimaDenganPetugasRuangan"
    }
  ]
}

export function evaluasiPreOperatif() : any {
  return [
    {
      "model": "evaluasiPreOperatif_1",
      "type" : "radio",
      "listValue":[
        {
          "label": "Ya",
          "value":"Ya"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "model": "evaluasiPreOperatif_2",
      "type" : "radio",
      "listValue":[
        {
          "label": "Lengkap",
          "value":"Lengkap"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "model": "evaluasiPreOperatif_3",
      "type" : "radio",
      "listValue":[
        {
          "label": "Pasien Mengerti",
          "value":"PasienMengerti"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "model": "evaluasiPreOperatif_4",
      "type" : "radio",
      "listValue":[
        {
          "label": "Stabil",
          "value":"Stabil"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "model": "evaluasiPreOperatif_5",
      "type" : "radio",
      "listValue":[
        {
          "label": "Lancar",
          "value":"Lancar"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "model": "evaluasiPreOperatif_6",
      "type" : "radio",
      "listValue":[
        {
          "label": "Supine",
          "value":"Supine"
        },
        {
          "label": "Lateral",
          "value":"Lateral"
        },
      ]
    },
    {
      "model": "evaluasiPreOperatif_7",
      "type" : "radio",
      "listValue":[
        {
          "label": "Siap",
          "value":"Siap"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "model": "evaluasiPreOperatif_8",
      "type" : "radio",
      "listValue":[
        {
          "label": "Siap",
          "value":"Siap"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "model": "evaluasiPreOperatif_9",
      "type" : "radio",
      "listValue":[
        {
          "label": "Ya",
          "value":"Ya"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "model": "evaluasiPreOperatif_10",
      "type" : "radio",
      "listValue":[
        {
          "label": "Pasien Kooperatif",
          "value":"PasienKooperatif"
        },
        {
          "label": "Pasien Gelisah",
          "value":"PasienGelisah"
        },
      ]
    },
    {
      "model": "evaluasiPreOperatif_11",
      "type" : "radio",
      "listValue":[
        {
          "label": "Ya",
          "value":"Ya"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "model": "evaluasiPreOperatif_12",
      "type" : "radio",
      "listValue":[
        {
          "label": "Ya",
          "value":"Ya"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "model": "evaluasiPreOperatif_13",
      "type" : "radio",
      "listValue":[
        {
          "label": "Ya",
          "value":"Ya"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "value" : "Jenis",
      "model" : "evaluasiPreOperatifJenis",
      "type" : "text",
    },
    {
      "value" : "Dosis",
      "model" : "evaluasiPreOperatifDosis",
      "type" : "text",
    },
  ]
}

export function evaluasiIntraOperatif() : any {
  return [
    {
      "model": "evaluasiIntraOperatif_1",
      "type" : "radio",
      "listValue":[
        {
          "label": "Siap",
          "value":"Siap"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "model": "evaluasiIntraOperatif_2",
      "type" : "radio",
      "listValue":[
        {
          "label": "Ya",
          "value":"Ya"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "model": "evaluasiIntraOperatif_3",
      "type" : "radio",
      "listValue":[
        {
          "label": "Siap",
          "value":"Siap"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "value" : "TD",
      "label" : "TD",
      "satuan" : "mmHg",
      "model" : "evaluasiIntraOperatifTD",
      "type" : "textsatuan",
    },
    {
      "value" : "ND",
      "label" : "ND",
      "satuan" : "x/mnt",
      "model" : "evaluasiIntraOperatifND",
      "type" : "textsatuan",
    },
    {
      "value" : "Resp",
      "label" : "Resp rate",
      "satuan" : "x/mnt",
      "model" : "evaluasiIntraOperatifResp",
      "type" : "textsatuan",
    },
    {
      "value" : "Suhu",
      "label" : "Suhu",
      "satuan" : "°C",
      "model" : "evaluasiIntraOperatifSuhu",
      "type" : "textsatuan",
    },
    {
      "value" : "Saturasi",
      "label" : "Saturasi O²",
      "satuan" : "x/mnt",
      "model" : "evaluasiIntraOperatifSaturasi",
      "type" : "textsatuan",
    },
    {
      "model": "evaluasiIntraOperatif_4",
      "type" : "radio",
      "listValue":[
        {
          "label": "Ya",
          "value":"Ya"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "model": "evaluasiIntraOperatif_5",
      "type" : "radio",
      "listValue":[
        {
          "label": "Ya",
          "value":"Ya"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "model": "evaluasiIntraOperatif_6",
      "type" : "radio",
      "listValue":[
        {
          "label": "Terlentang",
          "value":"Terlentang"
        },
        {
          "label": "Tengkurap",
          "value":"Tengkurap"
        },
        {
          "label": "Lateral Kanan",
          "value":"LateralKanan"
        },
        {
          "label": "Lithotomy",
          "value":"Lithotomy"
        },
        {
          "label": "Lateral Kiri",
          "value":"LateralKiri"
        },
      ]
    },
    {
      "model": "evaluasiIntraOperatif_7",
      "type" : "radio",
      "listValue":[
        {
          "label": "Ya",
          "value":"Ya"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "model": "evaluasiIntraOperatif_8",
      "type" : "radio",
      "listValue":[
        {
          "label": "Lengkap",
          "value":"Lengkap"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "model": "evaluasiIntraOperatif_9",
      "type" : "radio",
      "listValue":[
        {
          "label": "Ya",
          "value":"Ya"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "value":"Jam Keluar",
      "type" : "label",
    },
    {
      "model" : "evaluasiIntraOperatifJamKeluar",
      "type" : "time",
    },
    {
      "value":"Lokasi :",
      "type" : "label",
    },
    {
      "model": "evaluasiIntraOperatif_10",
      "type" : "radio",
      "listValue":[
        {
          "label": "Lengan Kanan",
          "value":"LenganKanan"
        },
        {
          "label": "Kaki Kanan",
          "value":"KakiKanan"
        },
        {
          "label": "Lengan Kiri",
          "value":"LenganKiri"
        },
        {
          "label": "Kaki Kiri",
          "value":"KakiKiri"
        },
      ]
    },
    {
      "value":"Jam Mulai",
      "type" : "label",
    },
    {
      "model" : "evaluasiIntraOperatifJamMulai",
      "type" : "time",
    },
    {
      "value":"Jam Selesai",
      "type" : "label",
    },
    {
      "model" : "evaluasiIntraOperatifJamSelesai",
      "type" : "time",
    },
    {
      "value":"Tekanan",
      "model" : "evaluasiIntraOperatifTekanan",
      "type" : "text",
    },
    {
      "model": "evaluasiIntraOperatif_11",
      "type" : "radio",
      "listValue":[
        {
          "label": "Monopolar",
          "value":"Monopolar"
        },
        {
          "label": "Bipolar",
          "value":"Bipolar"
        },
      ]
    },
    {
      "value":"Posisi Diametry :",
      "type" : "label",
    },
    {
      "model": "evaluasiIntraOperatif_12",
      "type" : "radio",
      "listValue":[
        {
          "label": "Lengan Kanan",
          "value":"LenganKanan"
        },
        {
          "label": "Paha Kanan",
          "value":"PahaKanan"
        },
        {
          "label": "Lengan Kiri",
          "value":"LenganKiri"
        },
        {
          "label": "Paha Kiri",
          "value":"PahaKiri"
        },
      ]
    },
    {
      "value":"Lainnya",
      "model" : "evaluasiIntraOperatifPDiametryLainnya",
      "type" : "text",
    },
    {
      "value":"Kondisi Kulit Post Pemasangan Diametry :",
      "type" : "label",
    },
    {
      "model": "evaluasiIntraOperatif_13",
      "type" : "radio",
      "listValue":[
        {
          "label": "Utuh",
          "value":"Utuh"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "model": "evaluasiIntraOperatif_14",
      "type" : "radio",
      "listValue":[
        {
         "label": "Povidine iodine",
         "value":"Povidineiodine"
        },
        {
          "label": "Alcohol",
          "value":"Alcohol"
        },
      ]
    },
    {
      "value":"Lainnya",
      "model" : "evaluasiIntraOperatifPemasanganDiametryLainnya",
      "type" : "text",
    },
    {
      "model": "evaluasiIntraOperatif_15",
      "type" : "radio",
      "listValue":[
        {
          "label": "Ya",
          "value":"Ya"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "value" : "Intake",
      "type" : "label",
    },
    {
      "value" : "Kristaloid",
      "label" : "Kristaloid",
      "satuan" : "cc",
      "model" : "evaluasiPreOperatifKristaloid",
      "type" : "textsatuan",
    },
    {
      "value" : "Koloid",
      "label" : "Koloid",
      "satuan" : "cc",
      "model" : "evaluasiPreOperatifKoloid",
      "type" : "textsatuan",
    },
    {
      "value" : "Darah",
      "label" : "Darah",
      "satuan" : "cc",
      "model" : "evaluasiPreOperatifDarah",
      "type" : "textsatuan",
    },
    {
      "value" : "Lainnya",
      "label" : "Lainnya",
      "satuan" : "cc",
      "model" : "evaluasiPreOperatifLainnya",
      "type" : "textsatuan",
    },
    {
      "value" : "Output",
      "type" : "label",
    },
    {
      "value" : "Perdarahan",
      "label" : "Perdarahan",
      "satuan" : "cc",
      "model" : "evaluasiPreOperatifPerdarahan",
      "type" : "textsatuan",
    },
    {
      "value" : "Urine",
      "label" : "Urine",
      "satuan" : "cc",
      "model" : "evaluasiPreOperatifUrine",
      "type" : "textsatuan",
    },
    {
      "value" : "LainnyaOutput",
      "label" : "Lainnya",
      "satuan" : "cc",
      "model" : "evaluasiPreOperatifLainnyaOutput",
      "type" : "textsatuan",
    },
    {
      "model": "evaluasiIntraOperatif_16",
      "type" : "radio",
      "listValue":[
        {
          "label": "Normal Saline",
          "value":"NormalSaline"
        },
        {
          "label": "PovidoneIodine",
          "value":"PovidoneIodine"
        },
        {
          "label": "Lainnya",
          "value":"Lainnya"
        },
      ]
    },
    {
      "model": "evaluasiIntraOperatif_17",
      "type" : "radio",
      "listValue":[
        {
          "label": "Lengkap",
          "value":"Lengkap"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "value":"X-Ray yang dilakukan",
      "model" : "evaluasiIntraOperatifXRay",
      "type" : "text",
    },
    {
      "value":"Insiden Report :",
      "type" : "label",
    },
    {
      "model": "evaluasiIntraOperatif_18",
      "type" : "radio",
      "listValue":[
        {
          "label": "Ya",
          "value":"Ya"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "model": "evaluasiIntraOperatif_19",
      "type" : "radio",
      "listValue":[
        {
         "label": "Tulle",
         "value":"Tulle"
        },
        {
          "label": "Wound Dress",
          "value":"WoundDress"
        },
      ]
    },
    {
      "value":"Lainnya",
      "model" : "evaluasiIntraOperatifInsidenLainnya",
      "type" : "text",
    },
    {
      "model": "evaluasiIntraOperatif_20",
      "type" : "radio",
      "listValue":[
        {
          "label": "Ya",
          "value":"Ya"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "model": "evaluasiIntraOperatif_21",
      "type" : "radio",
      "listValue":[
        {
          "label": "Ya",
          "value":"Ya"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "model": "evaluasiIntraOperatif_22",
      "type" : "radio",
      "listValue":[
        {
          "label": "Ya",
          "value":"Ya"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "value":"Jika Ada..",
      "model" : "evaluasiIntraOperatif_22_jika_ada",
      "type" : "text",
    },
  ]
}

export function evaluasiPostOperatif() : any {
  return [
    {
      "value" : "PENILAIAN NYERI",
      "type" : "label",
    },
    {
      "value" : "NYERI",
      "type" : "label",
    },
    {
      "model": "evaluasiPostOperatif_1",
      "type" : "radio",
      "listValue":[
        {
          "label": "Ya",
          "value":"Ya"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "value" : "Lokasi",
      "model" : "evaluasiPostOperatifLokasi",
      "type" : "text",
    },
    {
      "value" : "Intensitas (0-10)",
      "model" : "evaluasiPostOperatifIntensitas",
      "type" : "text",
    },
    {
      "value" : "Jenis",
      "type" : "label",
    },
    {
      "model": "evaluasiPostOperatif_2",
      "type" : "radio",
      "listValue":[
        {
          "label": "Akut",
          "value":"Akut"
        },
        {
          "label": "Kronis",
          "value":"Kronis"
        },
      ]
    },
    {
      "value" : "Skor",
      "model" : "evaluasiPostOperatifSkor",
      "type" : "text",
    },
    {
      "value" : "Metode",
      "type" : "label",
    },
    {
      "model": "evaluasiPostOperatif_3",
      "type" : "radio",
      "listValue":[
        {
          "label": "VAS",
          "value":"VAS"
        },
        {
          "label": "BPS",
          "value":"BPS"
        },
        {
          "label": "NIPS",
          "value":"NIPS"
        },
        {
          "label": "FLACC",
          "value":"FLACC"
        },
      ]
    },
    {
      "value" : "TD",
      "label" : "TD",
      "satuan" : "mmHg",
      "model" : "evaluasiPostOperatifTD",
      "type" : "textsatuan",
    },
    {
      "value" : "ND",
      "label" : "ND",
      "satuan" : "x/mnt",
      "model" : "evaluasiPostOperatifND",
      "type" : "textsatuan",
    },
    {
      "value" : "Resp",
      "label" : "Resp rate",
      "satuan" : "x/mnt",
      "model" : "evaluasiPostOperatifResp",
      "type" : "textsatuan",
    },
    {
      "value" : "Suhu",
      "label" : "Suhu",
      "satuan" : "°C",
      "model" : "evaluasiPostOperatifSuhu",
      "type" : "textsatuan",
    },
    {
      "value" : "Saturasi",
      "label" : "Saturasi O²",
      "satuan" : "x/mnt",
      "model" : "evaluasiPostOperatifSaturasi",
      "type" : "textsatuan",
    },
    {
      "model": "evaluasiPostOperatif_4",
      "type" : "radio",
      "listValue":[
        {
          "label": "Ya",
          "value":"Ya"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "model": "evaluasiPostOperatif_5",
      "type" : "radio",
      "listValue":[
        {
          "label": "Terlentang",
          "value":"Terlentang"
        },
        {
          "label": "Lithotomy",
          "value":"Lithotomy"
        },
        {
          "label": "Tengkurap",
          "value":"Tengkurap"
        },
        {
          "label": "Lateral Kiri",
          "value":"Lateral Kiri"
        },
        {
          "label": "Lateral Kanan",
          "value":"Lateral Kanan"
        },
      ]
    },
    {
      "value" : "Perdarahan",
      "type" : "label",
    },
    {
      "model": "evaluasiPostOperatif_6",
      "type" : "radio",
      "listValue":[
        {
          "label": "Ya",
          "value":"Ya"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "value" : "Menggigil",
      "type" : "label",
    },
    {
      "model": "evaluasiPostOperatif_7",
      "type" : "radio",
      "listValue":[
        {
          "label": "Ya",
          "value":"Ya"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "model": "evaluasiPostOperatif_8",
      "type" : "radio",
      "listValue":[
        {
          "label": "Ya",
          "value":"Ya"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "value" : "Intake",
      "type" : "label",
    },
    {
      "value" : "Kristaloid",
      "label" : "Kristaloid",
      "satuan" : "cc",
      "model" : "evaluasiPostOperatifKristaloid",
      "type" : "textsatuan",
    },
    {
      "value" : "Koloid",
      "label" : "Koloid",
      "satuan" : "cc",
      "model" : "evaluasiPostOperatifKoloid",
      "type" : "textsatuan",
    },
    {
      "value" : "Darah",
      "label" : "Darah",
      "satuan" : "cc",
      "model" : "evaluasiPostOperatifDarah",
      "type" : "textsatuan",
    },
    {
      "value" : "Lainnya",
      "label" : "Lainnya",
      "satuan" : "cc",
      "model" : "evaluasiPostOperatifLainnya",
      "type" : "textsatuan",
    },
    {
      "value" : "Output",
      "type" : "label",
    },
    {
      "value" : "Perdarahan",
      "label" : "Perdarahan",
      "satuan" : "cc",
      "model" : "evaluasiPostOperatifPerdarahan",
      "type" : "textsatuan",
    },
    {
      "value" : "Urine",
      "label" : "Urine",
      "satuan" : "cc",
      "model" : "evaluasiPostOperatifUrine",
      "type" : "textsatuan",
    },
    {
      "value" : "LainnyaOutput",
      "label" : "Lainnya",
      "satuan" : "cc",
      "model" : "evaluasiPostOperatifLainnyaOutput",
      "type" : "textsatuan",
    },
    {
      "model": "evaluasiPostOperatif_9",
      "type" : "radio",
      "listValue":[
        {
          "label": "Ya",
          "value":"Ya"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "model": "evaluasiPostOperatif_10",
      "type" : "radio",
      "listValue":[
        {
          "label": "Ya",
          "value":"Ya"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
    {
      "model": "evaluasiPostOperatif_11",
      "type" : "radio",
      "listValue":[
        {
          "label": "Ya",
          "value":"Ya"
        },
        {
          "label": "Tidak",
          "value":"Tidak"
        },
      ]
    },
  ]
}

export function perhitunganPerioperatif(): any {
  return [
    {
      "model": "hitungPerioperatif_1",
      "id": "1",
      "JenisHitung": "Gausa Kecil",
    },
    {
      "model": "hitungPerioperatif_2",
      "id": "2",
      "JenisHitung": "Gausa Besar",
    },
    {
      "model": "hitungPerioperatif_3",
      "id": "3",
      "JenisHitung": "Kacang",
    },
    {
      "model": "hitungPerioperatif_4",
      "id": "4",
      "JenisHitung": "Gausa Raytec",
    },
    {
      "model": "hitungPerioperatif_5",
      "id": "5",
      "JenisHitung": "Scalpel Blade",
    },
    {
      "model": "hitungPerioperatif_6",
      "id": "6",
      "JenisHitung": "Needle Atraumatik",
    },
    {
      "model": "hitungPerioperatif_7",
      "id": "7",
      "JenisHitung": "Needle Ordenary",
    },
    {
      "model": "hitungPerioperatif_8",
      "id": "8",
      "JenisHitung": "String Needle",
    },
    {
      "model": "hitungPerioperatif_9",
      "id": "9",
      "JenisHitung": "Arteri Klem",
    },
  ]
}
