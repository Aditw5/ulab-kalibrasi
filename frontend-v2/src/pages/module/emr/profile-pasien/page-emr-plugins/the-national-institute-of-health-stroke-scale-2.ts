export function parameter() : any{
    return [
      {
        'id': 1,
        "no" : '1a',
        "parameter" : "Tingkat Kesadaran",
        "skala" : "0 = sadar penuh<br>1 = tidak sadar penuh, bangun dengan stimulasi minor (suara) <br>2 = tidak sadar penuh, respon dengan stimulasi berulang/nyeri <br>3 = koma, tidak respon dengan stimulasi apapun",
        "inputTambahan": false,
        "model": 'nihssTingkatKesadaran',
      },
      {
        'id': 2,
        "no" : '1b',
        "parameter" : "Menjawab Pertanyaan",
        "skala" : "0 = benar semua<br>1 = 1 benar/ETT/disartria<br>2 = salah semua/afasia/stupor/koma",
        "inputTambahan": false,
        "model": 'nihssMenjawabPertanyaan',
      },
      {
        'id': 3,
        "no" : '1c',
        "parameter" : "Mengikuti Perintah",
        "skala" : "0 = mampu melakukan 2 perintah<br>1 = mampu melakukan 1 perintah<br>2 = tidak mampu melakukan perintah",
        "inputTambahan": false,
        "model": 'nihssMengikutiPerintah',
      },
      {
        'id': 4,
        "no" : '2',
        "parameter" : "Gaze : Gerakan Mata Konjugat Horisontal",
        "skala" : "0 = normal<br>1 = paresis gaze parsial pada 1 atau 2 mata, terdapat normal gaze, forced deviation ataupun paresis gaze total (-)<br>2 = forced deviation atau paresis gaze total tidak dapat diatasi dengan manuver okulosefalik",
        "inputTambahan": false,
        "model": 'nihssGaze',
      },
      {
        'id': 5,
        "no" : '3',
        "parameter" : "Visual : lapang pandang pada tes konfrontasi",
        "skala" : "0 = tidak ada gangguan 1 = hemianopsia parsial <br>2 = hemianopsia komplit<br>3 = bilateral hemianopsia (mencakup buta kortikal)",
        "inputTambahan": false,
        "model": 'nihssVisual',
      },
      {
        'id': 6,
        "no" : '4',
        "parameter" : "Paresis Wajah",
        "skala" : "0 = normal<br>1 = paralisis minor (sulcus nasolabial rata, asimetris saat senyum<br>2 = paralisis parsial (paralisis total atau near-total wajah bagian bawah<br>3 = paralisis komplit satu atau kedua sisi wajah (tidak ada gerakan wajah atas maupun bawah",
        "inputTambahan": false,
        "model": 'nihssParesisWajah',
      },
      {
        'id': 7,
        "no" : '5',
        "parameter" : "Motorik Lengan",
        "skala" : "0 = tidak ada drift, lengan dapat diangkat minimal 10 detik penuh (90<sup>o</sup> bila duduk, 45<sup>o</sup> bila tidur)<br>1 = drift, lengan dapat diangkat namun turun sebelum 10 detik, tidak mengenai tempat tidur<br>2 = upaya melawan gravitasi (+), lengan tidak dapat diangkat atau dipertahankan dalam posisi 90/45o, jatuh mengenai tempat tidur, namun ada upaya melawan gravitasi<br>3 = tidak ada upaya melawan gravitasi, tidak mampu mengangkat, hanya bergeser<br>4 = tidak ada gerakan<br>UN = amputasi/fusi sendi, penjelasan :",
        "inputTambahan": true,
        "model": 'nihssMotorikLengan',
        'kiriKanan': true,
      },
      {
        'id': 8,
        "no" : '6',
        "parameter" : "Motorik Tungkai",
        "skala" : "0 = tidak ada drift, tungkai dapat dipertahankan posisi 30o minimal 5 detik penuh<br>1 = drift, tungkai jatuh setelah persis 5 detik, namun tidak mengenai tempat tidur<br>2 = upaya melawan gravitasi (+), tungkai jatuh mengenai tempat tidur dalam 5 detik<br>3 = upaya melawan gravitasi (-) 4 = tidak ada gerakan<br>UN = amputasi/fusi sendi, penjelasan :",
        "inputTambahan": true,
        "model": 'nihssMotorikTungkai',
        'kiriKanan': true,
      },
      {
        'id': 9,
        "no" : '7',
        "parameter" : "Ataksia Anggota Gerak",
        "skala" : "0 = ataksia (-)<br>1 = ataksia pada satu ekstremitas 2 = ataksia pada ≥ 2 ekstremitas<br>UN = amputasi/fusi sendi, penjelasan :",
        "inputTambahan": true,
        "model": 'nihssAtaksiaAnggotaGerak',
      },
      {
        'id': 10,
        "no" : '8',
        "parameter" : "Sensorik",
        "skala" : "0 = normal, tidak ada gangguan sensorik<br>1 = gangguan sensorik ringan-sedang, sensasi disentuh atau nyeri berkurang namun masih terasa disentuh<br>2 = gangguan sensorik berat, tidak merasa sentuhan di wajah, lengan, atau tungkai",
        "inputTambahan": false,
        "model": 'nihssSensorik',
      },
      {
        'id': 11,
        "no" : '9',
        "parameter" : "Bahasa Terbaik",
        "skala" : "0 = normal, tidak ada afasia<br>1 = afasia ringan-sedang, komunikasi (+) namun terbatas, mengenali benda (+), kesulitas bicara percakapan dan mengerti percakapan<br>2 = afasia berat, seluruh komunikasi melalui ekspresi yang terfragmentasi, dikira-kira, dan pemeriksa tidak dapat memahami respon pasien<br>3 = mutisme, afasia global, tidak ada kata yang keluar maupun pengertian akan kata-kata",
        "inputTambahan": false,
        "model": 'nihssBahasaTerbaik',
      },
      {
        'id': 12,
        "no" : '10',
        "parameter" : "Disatria",
        "skala" : "0 = normal<br>1 = disartia ringan-sedang, pasien pelo pada beberapa kata, namun meski berat, dapat dimengerti<br>2 = disartria berat, bicara pasien sangat pelo namun tidak afasia UN = intubasi/hambatan fisik lain, penjelasan:",
        "inputTambahan": true,
        "model": 'nihssDisatria',
      },
      {
        'id': 13,
        "no" : '11',
        "parameter" : "Pengabaian & Inatensi (Neglect)",
        "skala" : "0 = tidak ada neglect<br>1 = tidak ada atensi pada salah satu modalitas berikut, visual, taktil, auditori, spasial, atau atensi personal<br>2 = tidak ada atensi pada ﹥1 modalitas",
        "inputTambahan": false,
        "model": 'nihssPengabaianInatensi',
      },
    ]
}

export function gambar() : any{
    return [
        {
            id: 14,
            'special': true,
            'content': '<img src="/images/simrs/nihss1.png">'
          },
          {
            id: 15,
            'special': true,
            'content': '<img src="/images/simrs/nihss2.png">'
          },
          {
            id: 16,
            'special': true,
            'content': 'Anda tahu kenapa <br>Jatuh ke bumi<br>Saya pulang dari kerja <br>Dekat meja di ruang makan<br>Mereka mendengar dia siaran di radio tadi malam'
          },
    ]
}
  