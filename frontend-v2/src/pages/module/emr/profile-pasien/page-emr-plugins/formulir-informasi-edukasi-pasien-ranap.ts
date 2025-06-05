export function informasi(): any {
  return [
    {
      label: 'Bahasa',
      children: [
        {
          label: 'Indonesia',
          model: 'bahasa',
          value: 'indonesia',
          type: 'checkbox',
        },
        {
          label: 'Inggris',
          model: 'bahasa',
          value: 'inggris',
          type: 'checkbox',
        },
        {
          label: 'Daerah',
          model: 'bahasa',
          value: 'daerah',
          type: 'checkbox',
        },
        {
          model: 'bahasaDaerah',
          type: 'input',
          placeholder: 'Bahasa daerah..',
        },
        {
          label: 'Lain-lain',
          model: 'bahasa',
          value: 'lain-lain',
          type: 'checkbox',
        },
        {
          model: 'bahasaLain',
          type: 'input',
          placeholder: 'Bahasa lain..',
        },
      ],
    },
    {
      label: 'Kebutuhan Penerjemah',
      children: [
        {
          label: 'Ya',
          model: 'kebutuhanPenerjemah',
          value: 'ya',
          type: 'checkbox',
        },
        {
          label: 'Tidak',
          model: 'kebutuhanPenerjemah',
          value: 'tidak',
          type: 'checkbox',
        },
      ],
    },
    {
      label: 'Pendidikan pasien',
      children: [
        {
          label: 'SD',
          model: 'pendidikanPasien',
          value: 'sd',
          type: 'checkbox',
        },
        {
          label: 'SLTP',
          model: 'pendidikanPasien',
          value: 'sltp',
          type: 'checkbox',
        },
        {
          label: 'SLTA',
          model: 'pendidikanPasien',
          value: 'slta',
          type: 'checkbox',
        },
        {
          label: 'S-1',
          model: 'pendidikanPasien',
          value: 's1',
          type: 'checkbox',
        },
        {
          label: 'Lain-lain',
          model: 'pendidikanPasien',
          value: 'lain-lain',
          type: 'checkbox',
        },
        {
          model: 'pendidikanLainnya',
          type: 'input',
          placeholder: 'Pendidiakan lain..',
        },
      ],
    },
    {
      label: 'Baca dan tulis',
      children: [
        {
          label: 'Baik',
          model: 'bacaDanTulis',
          value: 'baik',
          type: 'checkbox',
        },
        {
          label: 'Kurang',
          model: 'bacaDanTulis',
          value: 'kurang',
          type: 'checkbox',
        },
      ],
    },
    {
      label: 'Pilih tipe pembelajaran',
      children: [
        {
          label: 'Verbal',
          model: 'tipePemebelajaran',
          value: 'Verbal',
          type: 'checkbox',
        },
        {
          label: 'Tulisan',
          model: 'tipePemebelajaran',
          value: 'Tulisan',
          type: 'checkbox',
        },
      ],
    },
  ]
}
export function detailEdukasi(): any {
  return [
    {
      label: ' 1. Penyakit yang diderita pasien',
      model: 'penyakitPasien',
    },
    {
      label: ' 2. Rencana tindakan / terapi',
      model: 'rencanaTindakanTerapi',
    },
    {
      label: ' 3. Pengobatan dan prosedur yang diberikan atau diperlukan',
      model: 'pengobatanDanProsedurYangDiberikan',
    },
    {
      label:
        ' 4. Hasil pelayanan,termasuk terjadinya kejadian yang diharapkan dan tidak diharapkan',
      model: 'hasilPelayanan',
    },
    {
      label: ' 5. Tatalaksana covid',
      model: 'tatalaksanaCovid',
    },
    {
      label: ' a. Hasil pemeriksaan',
      model: 'hasilPemeriksaan',
    },
    {
      label: ' b. Rencanakan tindakan dan pengobatan (isolasi mandiri/isolasi di RS/Karantina',
      model: 'hasilPemeriksaan',
      children: [
        {
          label: 'isolasi',
          value: 'isolasi',
          type: 'checkbox',
        },
        {
          label: 'mandiri/isolasi',
          value: 'mandiri/isolasi',
          type: 'checkbox',
        },
        {
          label: 'di RS/karantina',
          value: 'di RS/karantina',
          type: 'checkbox',
        },
      ],
    },
  ]
}
