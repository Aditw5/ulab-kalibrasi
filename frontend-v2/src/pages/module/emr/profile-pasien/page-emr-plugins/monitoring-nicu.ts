export function waktu(): any {
  return ["08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00",
    "19:00", "20:00", "21:00", "22:00", "23:00", "00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06.00", "07.00"
  ]
}

export function dataTableDescrip(): any {

  let tanda1 = 'TANDA VITAL'
  let tanda2 = 'STATE'
  let tanda3 = 'WARNA KULIT'
  let tanda4 = 'SATURASI O²'

  return [
    { deskripsi: 'NBP', group: tanda1 },
    { deskripsi: 'HR', group: tanda1 },
    { deskripsi: 'RR', group: tanda1 },
    { deskripsi: 'T', group: tanda1 },
    { deskripsi: 'STATE', group: tanda2 },
    { deskripsi: '1. NORMAL', group: tanda3 },
    { deskripsi: '2. PUCAT', group: tanda3 },
    { deskripsi: '3. ICTERUS', group: tanda3 },
    { deskripsi: '4. SIANOSIS', group: tanda3 },
    { deskripsi: '5. LAIN-LAIN', group: tanda3 },
    { deskripsi: 'SATURASI O²', group: tanda4 },
  ]
}

export function dataOutput(): any {

  let tanda1 = 'OUTPUT'

  return [
    { deskripsi: 'OGT/NGT', group: tanda1 },
    { deskripsi: 'BAK', group: tanda1 },
    { deskripsi: 'BAB', group: tanda1 },
    { deskripsi: 'DRAIN/IWL', group: tanda1 },
    { deskripsi: 'MUNTAH', group: tanda1 },
    { deskripsi: 'JUMLAH 1 JAM/KUMULATIF', group: tanda1 },
    { deskripsi: 'KUMULATIF TOTAL OUTPUT', group: tanda1 },
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

export function tableOksigen(): any {

  let tanda1 = 'VENTILATOR'
  let tanda2 = 'NASAL'
  let tanda3 = 'HEADBOX'
  let tanda4 = 'RUANGAN'

  return [
    { deskripsi: 'MODUS', group: tanda1 },
    { deskripsi: 'FIO2', group: tanda1 },
    { deskripsi: 'FIOW', group: tanda1 },
    { deskripsi: 'PIP', group: tanda1 },
    { deskripsi: 'PEEP', group: tanda1 },
    { deskripsi: 'RR', group: tanda1 },
    { deskripsi: 'I:E RASIO', group: tanda1 },
    { deskripsi: 'NASAL', group: tanda2 },
    { deskripsi: 'HEADBOX', group: tanda3 },
    { deskripsi: 'RUANGAN', group: tanda4 },
  ]
}

export function tableIntake(): any {

  let tanda1 = 'MINUM'
  let tanda2 = 'TRANSFUSI'

  return [
    { deskripsi: 'MINUM', group: tanda1 },
    { deskripsi: 'I', group: tanda2 },
    { deskripsi: 'II', group: tanda2 },
    { deskripsi: 'JUMLAH 1 JAM/KUMULATIF', group: tanda2 },
    { deskripsi: 'KUMULATIF TOTAL INTAKE', group: tanda2 },
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
