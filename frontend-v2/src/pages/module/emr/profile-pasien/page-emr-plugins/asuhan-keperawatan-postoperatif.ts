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
      "model":"bowel",
      "type" : "radio",
      "listValue":[
        {
          "label": "Distensi",
          "value":"distensi"
        },
        {
          "label": "Mual / Muntah",
          "value":"mual"
        }
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
      "value" : "PENILAIAN NYERI",
      "type" : "label",
    },
    {
      "value" : "NYERI",
      "type" : "label",
    },
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
      "value" : "Lokasi",
      "model" : "evaluasiPreOperatifLokasi",
      "type" : "text",
    },
    {
      "value" : "Intensitas (0-10)",
      "model" : "evaluasiPreOperatifIntensitas",
      "type" : "text",
    },
    {
      "value" : "Jenis",
      "type" : "label",
    },
    {
      "model": "evaluasiPreOperatif_2",
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
      "model" : "evaluasiPreOperatifSkor",
      "type" : "text",
    },
    {
      "value" : "Metode",
      "type" : "label",
    },
    {
      "model": "evaluasiPreOperatif_3",
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
      "model" : "evaluasiPreOperatifTD",
      "type" : "textsatuan",
    },
    {
      "value" : "ND",
      "label" : "ND",
      "satuan" : "x/mnt",
      "model" : "evaluasiPreOperatifND",
      "type" : "textsatuan",
    },
    {
      "value" : "Resp",
      "label" : "Resp rate",
      "satuan" : "x/mnt",
      "model" : "evaluasiPreOperatifResp",
      "type" : "textsatuan",
    },
    {
      "value" : "Suhu",
      "label" : "Suhu",
      "satuan" : "°C",
      "model" : "evaluasiPreOperatifSuhu",
      "type" : "textsatuan",
    },
    {
      "value" : "Saturasi",
      "label" : "Saturasi O²",
      "satuan" : "x/mnt",
      "model" : "evaluasiPreOperatifSaturasi",
      "type" : "textsatuan",
    },
    {
      "model": "evaluasiPreOperatif_4",
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
      "model": "evaluasiPreOperatif_5",
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
      "model": "evaluasiPreOperatif_6",
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
      "model": "evaluasiPreOperatif_7",
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
      "model": "evaluasiPreOperatif_8",
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
  ]
}
