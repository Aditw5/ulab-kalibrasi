export function kriteriaMasuk() : any{
  return [
    {
      "no" : 1,
      "value" : "Sindroma KorenerAcut (STEMI, NON STEMI dan Angina Pektoristakstabil (UAP)",
      "model" : "kriteriaMasuk"
    },
    {
      "no" : 2,
      "value" : "Angina Pektoris Progresif / Canadian Class (CSS III-IV)",
      "model" : "kriteriaMasuk"
    },
    {
      "no" : 3,
      "value" : "Aritmia Cordis Maligna: SVT, AFVRR, VES Bigemini, TAVB, Bradyaritmia, Takiaritmia",
      "model" : "kriteriaMasuk"
    },
    {
      "no" : 4,
      "value" : "Gagal Jantung Klassfungsional III-IV",
      "model" : "kriteriaMasuk"
    },
    {
      "no" : 5,
      "value" : "Gagal Jantung akut (edema ParuAkut)",
      "model" : "kriteriaMasuk"
    },
    {
      "no" : 6,
      "value" : "SyokCardiogenik",
      "model" : "kriteriaMasuk"
    },
    {
      "no" : 7,
      "value" : "Hipertensi Krisis dengan kegawatan cardiovaskuler",
      "model" : "kriteriaMasuk"
    },
    {
      "no" : 8,
      "value" : "Kegawatan penyakit jantung katup ( jantungReuma)",
      "model" : "kriteriaMasuk"
    },
    {
      "no" : 9,
      "value" : "Kegawatan penyakit Vaskuler: Diseksi aorta, emboli paru, Penyakit Arteriperifer (PAD), DVT",
      "model" : "kriteriaMasuk"
    },
    {
      "no" : 10,
      "value" : "Kegawatan penyakit kardiovaskuler lainnya: endocarditis, myocarditis. tampon dejantung",
      "model" : "kriteriaMasuk"
    },
  ]
}

export function kriteriaKeluar(): any {
  return [
    {
      no: 1,
      value: "Pasien sudah mulai mobilisasi tahap 1 dan sudah lepas terapi oksigen",
      model: "kriteriaKeluar",
    },
    {
      no: 2,
      value: "Pasien SKA (Sindoma korener Akut) tanpa komplikasi dengan hemodinamik stabil 2 x 24 jam ",
      model: "kriteriaKeluar",
    },
    {
      no: 3,
      value: "Pasien kegawatan kardiovaskuler  Non SKA setelah hemodinamik dan atau keadaan umum baik dalam 2 x 24 jam",
      model: "kriteriaKeluar",
    },
    {
      no: 4,
      value: "Pasien telah terdiagnosis bukan penyakit kardiovaskuler selama 24 jam",
      model: "kriteriaKeluar",
    },
    {
      no: 5,
      value: "Pasien Akan dirujuk ke rumah sakit lain",
      model: "kriteriaKeluar",
    },
    {
      no: 6,
      value: "Tidak ada lagi kegawatan Kardiovaskular",
      model: "kriteriaKeluar",
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
