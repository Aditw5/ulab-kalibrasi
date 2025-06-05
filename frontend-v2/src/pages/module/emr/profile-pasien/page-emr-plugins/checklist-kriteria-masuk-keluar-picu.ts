export function kriteriaMasuk() : any{
  return [
    {
      kategori: 'Prioritas I',
      dataForm: [
        {
          no: 1,
          value: 'Pasien sakit kritis perlu terapi intensif',
          model: 'kriteriaMasuk',
        },
      ]
    },
    {
      kategori: 'Prioritas II',
      dataForm: [
        {
          no: 2,
          value: 'Pasien sakit kritis penyakit dasar saat ini belum dapat ditanggulangi namun dengan therapy intensif dapat menanggulangi keadaan kritisnya',
          model: 'kriteriaMasuk',
        },
      ]
    },
    {
      kategori: 'Prioritas III',
      dataForm: [
        {
          no: 3,
          value: 'Pasien sakit kritis, pasien tidak mempunyai kontak dengan lingkunganya secara permanen dan tidak mengalami tumbuh kembang',
          model: 'kriteriaMasuk',
        },
      ]
    },
    {
      kategori: 'Prioritas IV',
      dataForm: [
        {
          no: 4,
          value: 'Pasien sakit kritis dengan prognosis sangat buruk sehingga therapy intensif pun proses kematian tidak dapat dicegah',
          model: 'kriteriaMasuk',
        },
      ]
    },
  ]
}

export function kriteriaKeluar(): any {
  return [
    {
      no: 1,
      value: "Parameter Hemidinamik stabil",
      model: "kriteriaKeluar",
    },
    {
      no: 2,
      value: "Status respirasi stabil (tanpa ETT, jalan napas bebas, analisa gas darah",
      model: "kriteriaKeluar",
    },
    {
      no: 3,
      value: "Kebutuhan suplementasi oksigen minimal",
      model: "kriteriaKeluar",
    },
    {
      no: 4,
      value: "Tidak dibutuhkan",
      model: "kriteriaKeluar",
    },
    {
      no: 5,
      value: "Alat pemantau tekanan intracranial invasive tidak terpasang lagi",
      model: "kriteriaKeluar",
    },
    {
      no: 6,
      value: "Disriatmia jantung terkontrol",
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
