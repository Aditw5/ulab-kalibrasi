export function waktu(): any {
  return ["07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00",
    "19:00", "20:00", "21:00", "22:00", "23:00", "00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06.00"
  ]
}

export function dataTableDescrip(): any {

  let tanda1 = 'TANDA VITAL'
  let tanda2 = 'GAMBARAN EKG'
  let tanda3 = 'SATURASI OKSIGEN'
  let tanda4 = 'HEMODINAMIK'
  let tanda5 = 'GALSGOW COMA SCALE'
  let tanda6 = 'KESADARAN'
  let tanda7 = 'PUPIL'
  let tanda8 = 'Skala Nyeri CCPOT, NRS'
  let tanda9 = 'Skala Jatuh Morse, Sydney'
  let tanda10 = 'SKALA NORTON'
  let tanda11 = 'VENTILASI'

  return [
    { deskripsi: 'NBP', group: tanda1 },
    { deskripsi: 'HR', group: tanda1 },
    { deskripsi: 'RR', group: tanda1 },
    { deskripsi: 'T', group: tanda1 },
    { deskripsi: 'GAMBARAN EKG', group: tanda2 },
    { deskripsi: 'SATURASI OKSIGEN', group: tanda3 },
    { deskripsi: 'BP', group: tanda4 },
    { deskripsi: 'CVP', group: tanda4 },
    { deskripsi: 'CO', group: tanda4 },
    { deskripsi: 'CRT', group: tanda4 },
    { deskripsi: 'E', group: tanda5 },
    { deskripsi: 'M', group: tanda5 },
    { deskripsi: 'V', group: tanda5 },
    { deskripsi: 'KESADARAN', group: tanda6 },
    { deskripsi: 'KANAN', group: tanda7 },
    { deskripsi: 'KIRI', group: tanda7 },
    { deskripsi: 'Skala Nyeri ccpot, nrs', group: tanda8 },
    { deskripsi: 'Skala Jatuh morse, sydney', group: tanda9 },
    { deskripsi: 'SKALA NORTON', group: tanda10 },
    { deskripsi: 'TIPE', group: tanda11 },
    { deskripsi: 'RR Setting/Aktual', group: tanda11 },
    { deskripsi: 'Inspirasi : Ekspirasi Ratio', group: tanda11 },
    { deskripsi: 'Tidak Volume Setting/Aktual', group: tanda11 },
    { deskripsi: 'Minute Volume Setting/Aktual', group: tanda11 },
    { deskripsi: 'Pressure Control / Preasure Support', group: tanda11 },
    { deskripsi: 'PEEP', group: tanda11 },
    { deskripsi: 'FIO\u2082/Flow', group: tanda11 },
    { deskripsi: 'Peak/Plateu Pressure', group: tanda11 },
    { deskripsi: 'ETT/TC : DIAMETER/KEDALAMAN', group: tanda11 },
    { deskripsi: 'Tekanan Cuff ETT/TC', group: tanda11 },
  ]
}

export function dataOutput(): any {

  let tanda1 = 'OUTPUT'
  let tanda2 = 'KESEIMBANGAN CAIRAN'

  return [
    { deskripsi: 'URINE', group: tanda1 },
    { deskripsi: 'NGT', group: tanda1 },
    { deskripsi: 'BAB', group: tanda1 },
    { deskripsi: 'DRAIN I', group: tanda1 },
    { deskripsi: 'DRAIN II', group: tanda1 },
    { deskripsi: 'DRAIN III', group: tanda1 },
    { deskripsi: 'KESEIMBANGAN CAIRAN', group: tanda2 },
  ]
}

export function statusRespirasi(): any {
  return [
    {
      "label": "Alat Bantu",
      "model": "alatBantu",
      "type": "select",
    },
    {
      "label": "No. ETT",
      "model": "noETT",
      "type": "textBox",
    },
    {
      "label": "PEEP",
      "model": "noETT",
      "type": "textBox",
    },
    {
      "label": "RR",
      "model": "rr",
      "type": "textBox",
    },
    {
      "label": "FiO2",
      "model": "fio2",
      "type": "textBox",
    },
    {
      "label": "Flow",
      "model": "flow",
      "type": "textBox",
    },
    {
      "label": "PIP",
      "model": "pip",
      "type": "textBox",
    },
    {
      "label": "I:E",
      "model": "ie",
      "type": "textBox",
    },
    {
      "label": "TV",
      "model": "tv",
      "type": "textBox",
    },
  ]
}

export function Hemodinamik(): any {
  return [
    {
      "label": "CVP",
      "model": "cvp",
      "type": "textBox",
    },
    {
      "label": "CRT",
      "model": "crt",
      "type": "select",
    },
    {
      "label": "Sedasi Sore",
      "model": "sedasiScore",
      "type": "textBox",
    },
    {
      "label": "Gambaran EKG",
      "model": "ekg",
      "type": "textBox",
    },
  ]
}

export function tableBalansCairan(): any {

  let tanda1 = '1. Intake'
  let tanda2 = '2. Output'
  let tanda4 = ' 3. Balans'

  return [
    { deskripsi: 'Cairan', group: tanda1 },
    { deskripsi: 'NGT', group: tanda1 },
    { deskripsi: 'Oral', group: tanda1 },
    { deskripsi: 'Transfusi', group: tanda1 },
    { deskripsi: 'Total Intake', group: tanda1 },
    { deskripsi: 'Drain', group: tanda2 },
    { deskripsi: 'Urine', group: tanda2 },
    { deskripsi: 'NGT', group: tanda2 },
    { deskripsi: 'BAB', group: tanda2 },
    { deskripsi: 'WSD', group: tanda2 },
    { deskripsi: 'IWL', group: tanda2 },
    { deskripsi: 'Pendarahan', group: tanda2 },
    { deskripsi: 'Total Output', group: tanda2 },
    { deskripsi: 'Total Balans', group: tanda4 }
  ]
}

export function tableIntake(): any {

  let tanda1 = 'TRANSFUSI'
  let tanda2 = 'MAKAN'

  return [
    { deskripsi: 'I', group: tanda1 },
    { deskripsi: 'II', group: tanda1 },
    { deskripsi: 'III', group: tanda1 },
    { deskripsi: 'IV', group: tanda1 },
    { deskripsi: 'JUMLAH 1 JAM/KUMULATIF', group: tanda1 },
    { deskripsi: 'ORAL', group: tanda2 },
    { deskripsi: 'ENTERAL : Susu', group: tanda2 },
    { deskripsi: 'ENTERAL : Air Putih', group: tanda2 },
    { deskripsi: 'JUMLAH 1 JAM/KUMULATIF', group: tanda2 },
  ]
}

export function listDescripWaktu(): any {
  return {
    "ketWaktu": [
      "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM",
      "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA",
    ],
    "descWaktu": [
      "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis",
      "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML",
    ]
  }
}

export function titleMore(): any {
  return [
    {
      "title": "VII. Data Laboratorium",
      "value" : "dataLaboratorium"
    },
    {
      "title": "VIII. Catatan Penting",
      "value" : "catatanPenting"
    },
  ]
}
