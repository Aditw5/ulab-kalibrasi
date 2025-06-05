export function diputuskanUntuk(): any {
    return [
      {
        model: 'diputuskanUntuk',
        label: 'Rawat Inap',
        value: 'RawatInap',
        details: [
          {
            model: 'diputuskanUntukRawatInap',
            label: 'Kebidanan',
            value: 'Kebidanan',
          },
          {
            model: 'diputuskanUntukRawatInap',
            label: 'Kandungan',
            value: 'Kandungan',
          },
        ]
      },
      {
        model: 'diputuskanUntuk',
        label: 'Intensive',
        value: 'Intensive',
        details: [
          {
            model: 'diputuskanUntukIntensive',
            label: 'HCU',
            value: 'HCU',
          },
          {
            model: 'diputuskanUntukIntensive',
            label: 'ICU',
            value: 'ICU',
          },
          {
            model: 'diputuskanUntukIntensive',
            label: 'PICU',
            value: 'PICU',
          },
          {
            model: 'diputuskanUntukIntensive',
            label: 'NICU',
            value: 'NICU',
          },
        ]
      },
      {
        model: 'diputuskanUntuk',
        label: 'Operasi',
        value: 'Operasi',
      },
      {
        model: 'diputuskanUntuk',
        label: 'Rujuk',
        value: 'Rujuk',
      },
      {
        model: 'diputuskanUntuk',
        label: 'Pulang',
        value: 'Pulang',
      },
    ]
  }
  export function riwayatAlergiObat(): any {
    return [
      {
        model: 'riwayatAlergiObat',
        label: 'Tidak',
        value: 'Tidak',
      },
      {
        model: 'riwayatAlergiObat',
        label: 'Ya',
        value: 'Ya',
      },
    ]
  }
  export function listSkoringNyeri(): any {
    return {
      "nama": "Score ",
      "detail": [
        { "nama": "0 - 1 = Tidak Ada Nyeri", "descNilai": 0 },
        { "nama": "2 - 3 = Sedikit Nyeri", "descNilai": 2 },
        { "nama": "4 - 5 = Cukup Nyeri", "descNilai": 4 },
        { "nama": "6 - 7 = Lumayan Nyeri", "descNilai": 6 },
        { "nama": "8 - 9 = Sangat Nyeri", "descNilai": 8 },
        { "nama": "10 = Amat Sangat Nyeri", "descNilai": 10 },
      ]
    }
  }
  export function descripJmlPoin(): any {
    return [
      {
        "model": "tingkatResiko",
        "title": "Tidak Beresiko",
        "nilai": "0-24",
        "value": {
          "keterangan": "Tidak Beresiko",
          "rangeNilai": 24
        },
      },
      {
        "model": "tingkatResiko",
        "title": "Resiko Rendah",
        "nilai": "24-45",
        "value": {
          "keterangan": "Resiko Rendah",
          "rangeNilai": 45,
        },
      },
      {
        "model": "tingkatResiko",
        "title": "Resiko Tinggi",
        "nilai": "> 45",
        "value": {
          "keterangan": "Resiko Tinggi",
          "rangeNilai": 46,
        },
      },
    ]
  }
  export function listImageNyeri(): any {
    return {
      "nama": "Hurts",
      "detail": [
        {
          "nama": "No Hurt", "descNilai": 0,
          "img": "/images/skalanyeri/1.png"
        },
        {
          "nama": "Hurts Little Bit", "descNilai": 2,
          "img": "/images/skalanyeri/2.png",
        },
        {
          "nama": "Hurts Little More", "descNilai": 4,
          "img": "/images/skalanyeri/3.png",
        },
        {
          "nama": "Hurts Even More", "descNilai": 6,
          "img": "/images/skalanyeri/4.png",
        },
        {
          "nama": "Hurts Whole Lot", "descNilai": 8,
          "img": "/images/skalanyeri/5.png",
        },
        {
          "nama": "Hurts whorts", "descNilai": 10,
          "img": "/images/skalanyeri/6.png",
        }]
    }
  }
  
  