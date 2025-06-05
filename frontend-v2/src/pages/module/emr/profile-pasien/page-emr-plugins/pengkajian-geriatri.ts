export function anamnesis(): any {
    return [
      {
        model: 'anamnesisDemensia',
        label: 'Demensia',
        value: 'Demensia',
      },
      {
        model: 'anamnesisDelirium',
        label: 'Delirium',
        value: 'Delirium',
      },
      {
        model: 'anamnesisInkontinensiaUrin',
        label: 'Inkontinensia Urin',
        value: 'InkontinensiaUrin',
      },
      {
        model: 'anamnesisGangguanGaib',
        label: 'Gangguan Gaib(Gangguan berjalan)',
        value: 'GangguanGaib',
      },
      {
        model: 'anamnesisGangguanTidur',
        label: 'Gangguan Tidur',
        value: 'GangguanTidur',
      },
      {
        model: 'anamnesisGangguanDengar',
        label: 'Gangguan Dengar',
        value: 'GangguanDengar',
      },
      {
        model: 'anamnesisGangguanLihat',
        label: 'Gangguan Lihat',
        value: 'GangguanLihat',
      },
      {
        model: 'anamnesisOsteopenia',
        label: 'Osteopenia',
        value: 'Osteopenia',
      },
      {
        model: 'anamnesisMalnutrisi',
        label: 'Malnutrisi',
        value: 'Malnutrisi',
      },
      {
        model: 'anamnesisGangguanMakan',
        label: 'Gangguan Makan',
        value: 'GangguanMakan',
      },
      {
        model: 'anamnesisUlkusDecubitus',
        label: 'Ulkus Decubitus',
        value: 'UlkusDecubitus',
      },
      {
        model: 'anamnesisRiwayatPingsan',
        label: 'Riwayat Pingsan',
        value: 'RiwayatPingsan',
      },
      {
        model: 'anamnesisPandanganBergoyang',
        label: 'Pandangan Bergoyang (dizziness)',
        value: 'PandanganBergoyang',
      },
      {
        model: 'anamnesisPoliFarmasi',
        label: 'Poli Farmasi(>5 obat rutin)',
        value: 'PoliFarmasi',
      },
      {
        model: 'anamnesisSelfNeglect',
        label: 'Self Neglect(tidak ada yang mendampingi)',
        value: 'SelfNeglect',
      },
      {
        model: 'anamnesisElderAbuse',
        label: 'Elder Abuse(kekerasan pada lansia)',
        value: 'ElderAbuse',
      },
      {
        model: 'anamnesisFrailty',
        label: 'Frailty(Ringkih)',
        value: 'Frailty',
      },
      {
        model: 'anamnesisHypoDanHyperthermia',
        label: 'Hypo dan Hyperthermia',
        value: 'HypoDanHyperthermia',
      },
      {
        model: 'anamnesisDehidrasiDanGangguanElektrolit',
        label: 'Dehidrasi dan Gangguan Elektrolit',
        value: 'DehidrasiDanGangguanElektrolit',
      },
      {
        model: 'anamnesisLatrogenesis',
        label: 'Latrogenesis (penggunaan NGT, kateter dll)',
        value: 'Latrogenesis',
      },
    ]
  }

export function masalah(): any {
    return [
        {
            model: 'masalahGangguanPenglihatan',
            label: 'Gangguan Penglihatan',
            value: 'GangguanPenglihatan',
        },
        {
            model: 'masalahGangguanPendengaran',
            label: 'Gangguan Pendengaran',
            value: 'GangguanPendengaran',
        },
        {
            model: 'masalahGangguanBerkemih',
            label: 'Gangguan Berkemih',
            value: 'GangguanBerkemih',
        },
        {
            model: 'masalahGangguanMental',
            label: 'Gangguan Mental',
            value: 'GangguanMental',
        },
        {
            model: 'masalahGangguanEmosional',
            label: 'Gangguan Emosional',
            value: 'GangguanEmosional',
        },
        {
            model: 'masalahGangguanAktivitas',
            label: 'Gangguan Aktivitas',
            value: 'GangguanAktivitas',
        },
        {
            model: 'masalahGangguanBicara',
            label: 'Gangguan Bicara',
            value: 'GangguanBicara',
        },
        {
            model: 'masalahGangguanNutrisi',
            label: 'Gangguan Nutrisi',
            value: 'GangguanNutrisi',
        },
        {
            model: 'masalahGangguanDayaIngat',
            label: 'Gangguan Daya Ingat',
            value: 'GangguanDayaIngat',
        },
    ]
  }
export function dekubitus(): any {
    return [
        {
            model: 'dekubitus',
            label: 'Score 18 - 15 : Risiko rendah terjadi luka dekubitus',
            value: 'Rendah',
        },
        {
            model: 'dekubitus',
            label: 'Score 13 - 14 : Rentan terjadi luka dekubitus',
            value: 'Rentan',
        },
        {
            model: 'dekubitus',
            label: 'Score 10 - 12 : Risiko tinggi terjadi luka dekubitus',
            value: 'Tinggi',
        },
        {
            model: 'dekubitus',
            label: 'Score <=9 : Risiko sangat tinggi terjadi luka dekubitus',
            value: 'SangatTinggi',
        },
    ]
  }

export function emosional() : any {
    return [ 
        {
            "title": "Pertanyaan tahap pertama",
            "ket" : '(lanjut pertanyaan tahap dua apabila klien menjawab "Ya" satu atau lebih dari satu)',
            "type": "",
            "detail": [ 
              {
                label: "1. apakah klien mengalami sulit tidur?",
                model: "emosionalPertama_1",
                options: [
                  { label: "Ya", value: "Ya" },
                  { label: "Tidak", value: "Tidak" },
                ],
              },
              {
                label: "2. apakah klien sering gelisah?",
                model: "emosionalPertama_2",
                options: [
                  { label: "Ya", value: "Ya" },
                  { label: "Tidak", value: "Tidak" },
                ],
              },
              {
                label: "3. apakah klien sering murung dan menangis sendiri?",
                model: "emosionalPertama_3",
                options: [
                  { label: "Ya", value: "Ya" },
                  { label: "Tidak", value: "Tidak" },
                ],
              },
              {
                label: "4. apakah klien sering was-was atau khawatir?",
                model: "emosionalPertama_4",
                options: [
                  { label: "Ya", value: "Ya" },
                  { label: "Tidak", value: "Tidak" },
                ],
              },
            ]
        },
        {
            "title": "Pertanyaan tahap kedua",
            "type": "",
            "detail": [ 
              {
                label: "1. keluhan lebih dari tiga bulan atau lebih dari satu kali dalam sebulan?",
                model: "emosionalKedua_1",
                options: [
                  { label: "Ya", value: "Ya" },
                  { label: "Tidak", value: "Tidak" },
                ],
              },
              {
                label: "2. ada banyak masalah atau fikiran?",
                model: "emosionalKedua_2",
                options: [
                  { label: "Ya", value: "Ya" },
                  { label: "Tidak", value: "Tidak" },
                ],
              },
              {
                label: "3. ada masalah dengan keluarga?",
                model: "emosionalKedua_3",
                options: [
                  { label: "Ya", value: "Ya" },
                  { label: "Tidak", value: "Tidak" },
                ],
              },
              {
                label: "4. menggunakan obat tidur atau penenang atas anjuran dokter?",
                model: "emosionalKedua_4",
                options: [
                  { label: "Ya", value: "Ya" },
                  { label: "Tidak", value: "Tidak" },
                ],
              },
              {
                label: "5. cenderung mengurung diri?",
                model: "emosionalKedua_5",
                options: [
                  { label: "Ya", value: "Ya" },
                  { label: "Tidak", value: "Tidak" },
                ],
              },
            ]
        },
     ]
}
export function gangguanSistem() : any {
  return [ 
    {
        "title": "",
        "type": "",
        "detail": [ 
          {
            label: "1. Gangguan penglihatan?",
            model: "gangguanSistem_1",
            options: [
              { label: "Ya", value: "Ya" },
              { label: "Tidak", value: "Tidak" },
            ],
          },
          {
            label: "2. Gangguan pendengaran?",
            model: "gangguanSistem_2",
            options: [
              { label: "Ya", value: "Ya" },
              { label: "Tidak", value: "Tidak" },
            ],
          },
          {
            label: "3. Gangguan berkemih?",
            model: "gangguanSistem_3",
            options: [
              { label: "Ya", value: "Ya" },
              { label: "Tidak", value: "Tidak" },
              { label: "InkontinensiaUrine", value: "InkontinensiaUrine" },
              { label: "Disuria", value: "Disuria" },
              { label: "Poliuria", value: "Poliuria" },
              { label: "Oliguria", value: "Oliguria" },
              { label: "Anuria", value: "Anuria" },
            ],
          },
          {
            label: "4. Gangguan daya ingat?",
            model: "gangguanSistem_4",
            options: [
              { label: "Ya", value: "Ya" },
              { label: "Tidak", value: "Tidak" },
            ],
          },
          {
            label: "5. Gangguan bicara?",
            model: "gangguanSistem_5",
            options: [
              { label: "Ya", value: "Ya" },
              { label: "Tidak", value: "Tidak" },
            ],
          },
        ]
      },
    ]
}