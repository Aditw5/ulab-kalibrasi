export function kriteriaMasuk() : any{
  return [
    {
      kategori: 'Prioritas I',
      dataForm: [
        {
          no: 1,
          value: 'Gagal Nafas',
          model: 'kriteriaMasuk',
        },
        {
          no: 2,
          value: 'Syok Septik',
          model: 'kriteriaMasuk',
        },
        {
          no: 3,
          value: 'Gangguan Susunan Saraf Pusat',
          model: 'kriteriaMasuk',
        },
      ],
      textArea: true,
      model: 'kriteriaMasuk'
    },
    {
      kategori: 'Prioritas II',
      dataForm: [
        {
          no: 4,
          value: 'Pasca bedah mayor',
          model: 'kriteriaMasuk',
        },
        {
          no: 5,
          value: 'Penyakit dasar Jantung / Paru / Ginjal',
          model: 'kriteriaMasuk',
        },
        {
          no: 6,
          value: 'Monitoring Pasca PTCA / Pace Maker',
          model: 'kriteriaMasuk',
        },
      ],
      textArea: true,
      model: 'kriteriaMasuk'
    },
    {
      kategori: 'Prioritas III',
      dataForm: [
        {
          no: 7,
          value: 'Keganasan disertai Infeksi',
          model: 'kriteriaMasuk',
        },
        {
          no: 8,
          value: 'Keganasan disertai Penyakit Kronid dan berat lainnya',
          model: 'kriteriaMasuk',
        },
      ],
      textArea: true,
      model: 'kriteriaMasuk'
    },
  ]
}

export function kriteriaKeluar(): any {
  return [
    {
      kategori: 'Prioritas I',
      dataForm: [
        {
          no: 1,
          value: 'Tidak membutuhkan perawatan intensif karena tidak dibutuhkan terapi intensif',
          model: 'kriteriaKeluar',
        },
      ],
      model: 'kriteriaKeluar'
    },
    {
      kategori: 'Prioritas II',
      dataForm: [
        {
          no: 2,
          value: 'Tidak memerlukan pemantauan intensif',
          model: 'kriteriaKeluar',
        },
      ],
      model: 'kriteriaKeluar'
    },
    {
      kategori: 'Prioritas III',
      dataForm: [
        {
          no: 3,
          value: 'Karsinoma yang menyebarluas',
          model: 'kriteriaKeluar',
        },
        {
          no: 4,
          value: 'Manfaat terapi intensif kecil',
          model: 'kriteriaKeluar',
        },
      ],
      model: 'kriteriaKeluar'
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
