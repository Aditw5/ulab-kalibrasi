export function kriteriaMasuk() : any{
  return [
    {
      "no" : 1,
      "value" : "Neonatus 0-28 Hari",
      "model" : "kriteriaMasuk"
    },
    {
      "no" : 2,
      "value" : "Kegawatan pada system pernapasan",
      "model" : "kriteriaMasuk"
    },
    {
      "no" : 3,
      "value" : "Respiratory distress syndrome (RDS)",
      "model" : "kriteriaMasuk"
    },
    {
      "no" : 4,
      "value" : "Hyallin membrance deasease (HMD)",
      "model" : "kriteriaMasuk"
    },
    {
      "no" : 5,
      "value" : "Transient Tachipnea of New Born",
      "model" : "kriteriaMasuk"
    },
    {
      "no" : 6,
      "value" : "Meconium Aspirasi Syndrome (MAS)",
      "model" : "kriteriaMasuk"
    },
    {
      "no" : 7,
      "value" : "Congential Heart Desease",
      "model" : "kriteriaMasuk"
    },
    {
      "no" : 8,
      "value" : "Sepsis Neonatorium",
      "model" : "kriteriaMasuk"
    },
    {
      "no" : 9,
      "value" : "Bayi Prematur/BBLR dengan gangguan",
      "model" : "kriteriaMasuk"
    },
    {
      "no" : 10,
      "value" : "pernapasan / asfiksia berat",
      "model" : "kriteriaMasuk"
    },
    {
      "no" : 11,
      "value" : "Bayi BBLSR BBLASR",
      "model" : "kriteriaMasuk"
    },
    {
      "no" : 12,
      "value" : "Kelainan cingential yang disertai gangguan pernapasan",
      "model" : "kriteriaMasuk"
    },
    {
      "no" : 13,
      "value" : "Neonatus post operasi yang membutuhkan perawatan intensif",
      "model" : "kriteriaMasuk"
    },
    {
      "no" : 14,
      "value" : "Hiperbilirubemia disertai dengan gangguan pernafasan",
      "model" : "kriteriaMasuk"
    },
  ]
}

export function kriteriaKeluar(): any {
  return [
    {
      no: 1,
      value: "Pasien sudah stabil, tidak membutuhkan lagi pemantauan yang ketat",
      model: "kriteriaKeluar",
    },
    {
      no: 2,
      value: "Pasien indikasi rujuk ke",
      model: "kriteriaKeluar",
      formPenjelasan: true,
    },
  ]
}

export function kriteriaKhusus(): any{
  return [
    {
      no: 1,
      value: "Pasien Pulang Atas Permintaan Sendiri",
      model: "kriteriaKhusus",
    }
  ]
}
