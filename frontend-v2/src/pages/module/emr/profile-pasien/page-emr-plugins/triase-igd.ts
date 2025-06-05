export function PasienDewasa() {
  return [
    {
      label: 'Jalan nafas',
      children: [
        [
          {
            label: 'Sumbatan',
            model: 'jalanNafas',
            value: 'Sumbatan',
            type: 'checkbox',
          },
        ],
        [
          {
            label: 'Bebas',
            model: 'jalanNafas',
            value: 'BebasUrgent',
            type: 'checkbox',
          },
        ],
        [
          {
            label: 'Bebas',
            model: 'jalanNafas',
            value: 'BebasNonUrgent',
            type: 'checkbox',
          },
        ],
        [
          {
            label: 'Tekanan Darah',
            model: 'tandaVitalPernafasa',
            value: 'Tekanan Darah',
            button: 'mmHG',
            type: 'input',
          },
        ],
      ],
    },
    {
      label: 'Pernafasan',
      children: [
        [
          {
            label: 'Henti nafas',
            model: 'hentiNafas',
            value: 'Henti Nafas',
            type: 'checkbox',
          },
          {
            label: 'Frekuensi nafas <8 atau >25',
            model: 'frekuensiNafas8',
            value: 'Frekuensi nafas <8 atau >25',
            type: 'checkbox',
          },
          {
            label: 'sianosis',
            model: 'sianosis',
            value: 'sianosis',
            type: 'checkbox',
          },
        ],
        [
          {
            label: 'Frek. napas 21-24 atau 9-11',
            model: 'freknafas21',
            value: 'Frek. napas 21-24 atau 9-11',
            type: 'checkbox',
          },
          {
            label: 'Mengi',
            model: 'Mengi',
            value: 'Mengi',
            type: 'checkbox',
          },
        ],
        [
          {
            label: 'Frek. napas 12-20',
            model: 'Pernafasan12',
            value: 'Frek. napas 12-20',
            type: 'checkbox',
          },
        ],
        [
          {
            label: 'Frekuensi nadi',
            model: 'frekuensiNadi',
            value: 'Frekuensi nadi',
            button: 'BPM',
            type: 'input',
          },
          {
            label: 'Frekuensi nafas',
            model: 'frekuensiNafas',
            value: 'frekuensiNadi',
            button: 'XPM',
            type: 'input',
          },
        ],
      ],
    },
    {
      label: 'Sirkulasi',
      children: [
        [
          {
            label: 'Henti jantung',
            model: 'hentiJantung',
            value: 'Henti jantung',
            type: 'checkbox',
          },
          {
            label: 'nadi tidak teraba atau teraba lemah',
            model: 'nadiTidakTeraba',
            value: 'nadi tidak teraba atau teraba lemah',
            type: 'checkbox',
          },
          {
            label: 'nadi <41, atau >130',
            model: 'nadi<41atau>130',
            value: 'nadi <41, atau >130',
            type: 'checkbox',
          },
          {
            label: 'Pucat',
            model: 'pucar',
            value: 'Pucat',
            type: 'checkbox',
          },
          {
            label: 'Akral dingin',
            model: 'akralDingin',
            value: 'Akral dingin',
            type: 'checkbox',
          },
          {
            label: 'Spo2 <92',
            model: 'spo2<92',
            value: 'Spo2 <92',
            type: 'checkbox',
          },
          {
            label: 'TD Sistol < 90 atau >200',
            model: 'TDSistol<90atau>200',
            value: 'TD Sistol < 90 atau >200',
            type: 'checkbox',
          },
        ],
        [
          {
            label: 'Nadi 40-60 atau 100-130',
            model: 'frekNadi40',
            value: 'Nadi 40-60 atau 100-130',
            type: 'checkbox',
          },
          {
            label: 'TD sistol >160',
            model: 'tdSitol',
            value: 'TD sistol >160',
            type: 'checkbox',
          },
          {
            label: 'TD Diastol >100',
            model: 'tdDiastol',
            value: 'TD Diastol >100',
            type: 'checkbox',
          },
          {
            label: 'Spo2 92-95',
            model: 'spo29295',
            value: 'Spo2 92-95',
            type: 'checkbox',
          },
        ],
        [
          {
            label: 'Frek. nadi 60-100',
            model: 'frekNadi60',
            value: 'Frek. nadi 60-100',
            type: 'checkbox',
          },
          {
            label: 'TD sistol ≥90-160',
            model: 'tdSistol90',
            value: 'TD sistol ≥90-160',
            type: 'checkbox',
          },
          {
            label: 'TD Diastol ≥80-100',
            model: 'sikulasi',
            value: 'TD Diastol ≥80-100',
            type: 'checkbox',
          },
          {
            label: 'Spo2 >95',
            model: 'spo2>95',
            value: 'Spo2 >95',
            type: 'checkbox',
          },
        ],
        [
          {
            label: 'Suhu',
            model: 'sikulasiSuhu',
            value: 'Suhu',
            button: '\u2103',
            type: 'input',
          },
          {
            label: 'SPO2',
            model: 'saturasiOksigen',
            value: 'SPO2',
            button: '%',
            type: 'input',
          },
          {
            label: 'Riwayat alergi',
            model: 'sikulasiRiwayatAlergi',
            value: 'Riwayat alergi',
            type: 'input',
          },
          {
            label: 'Makanan',
            model: 'sikulasiMakanan',
            value: 'Makanan',
            type: 'input',
          },
          {
            label: 'Lain-lain',
            model: 'sikulasiLainLain',
            value: 'Lain-lain',
            type: 'input',
          },
        ],
      ],
    },
    {
      label: 'Kesadaran',
      children: [
        [
          {
            label: 'GCS < 9',
            model: 'Kesadaran',
            value: 'GCS < 9',
            type: 'checkbox',
          },
        ],
        [
          {
            label: 'GCS >12',
            model: 'Kesadaran',
            value: 'GCS >12',
            type: 'checkbox',
          },
        ],
        [
          {
            label: 'GCS 15',
            model: 'Kesadaran',
            value: 'GCS 15',
            type: 'checkbox',
          },
        ],
      ],
    },
  ]
}

export function PasienBayi() {
  return [
    {
      label: 'Koma',
      checkbox: true,
      model: 'koma',
      children: [
        [
          {
            label: 'Terdapat tanda prioritas:',
            model: 'sikulasi',
            value: 'Henti jantung',
            type: 'input',
          },
        ],
        [
          {
            label: 'Tidak ada tanda gawat darurat',
            model: 'sikulasi',
            value: 'Henti jantung',
            type: 'checkbox',
          },
        ],
      ],
    },
    {
      label: 'Kejang',
      checkbox: false,
    },
  ]
}
