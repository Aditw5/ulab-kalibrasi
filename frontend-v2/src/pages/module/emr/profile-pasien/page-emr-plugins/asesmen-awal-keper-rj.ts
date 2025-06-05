
export function hipertensi(): any {
    return [
        {
            "model": "hipertensi",
            "title": "Ya",
        },
        {
            "model": "hiperKontrol",
            "title": "Terkontrol",
        },
        {
            "model": "hiperKontrol",
            "title": "Tidak Terkontrol",
        },
        {
            "model": "hipertensi",
            "title": "Tidak",
        },
    ]
}

export function diabetes(): any {
    return [
        {
            "model": "diabetes",
            "title": "Ya",
        },
        {
            "model": "diabetesKontrol",
            "title": "Terkontrol",
        },
        {
            "model": "diabetesKontrol",
            "title": "Tidak Terkontrol",
        },
        {
            "model": "diabetes",
            "title": "Tidak",
        },
    ]
}
export function dyslipidemia(): any {
    return [
        {
            "model": "dyslipidemia",
            "title": "Ya",
        },
        {
            "model": "dyslipidemiaKontrol",
            "title": "Terkontrol",
        },
        {
            "model": "dyslipidemiaKontrol",
            "title": "Tidak Terkontrol",
        },
        {
            "model": "dyslipidemia",
            "title": "Tidak",
        },
    ]
}
export function duaPilihan() : any {
    return ['Ya', 'Tidak']
}
export function agama() : any {
    return ['Islam', 'Budha', 'Khatolik', 'Kristen', 'Hindu', 'Khonghucu']
}
export function status() : any {
    return ['Menikah', 'Belum Menikah', 'Duda/Janda']
}
export function keluarga() : any {
    return ['Tinggal Sendiri', 'Tinggal Serumah']
}
export function tempatTinggal() : any {
    return ['Rumah', 'Panti Asuhan', 'Lainnya']
}
export function psikologis() : any {
    return ['Depresi', 'Takut', 'Agresif', 'Melukai diri Sendiri', 'Tidak ada gejala']
}
export function more() : any {
    return [
        {
            "title": 'Diagnosa Keperawatan',
            "model": 'diagnosa',
        },
        {
            "title": 'Intervensi Keperawatan',
            "model": 'intervensiKeperawatan',
        },
        {
            "title": 'Implementasi Keperawatan',
            "model": 'frontend-v2/src/pages/module/emr/profile-pasien/page-emr-plugins/asesmen-awal-keper-rj.ts',
        },
    ]
}

export function skoringNyeri() : any {
    return{
        "nama": "Score ", "detail": [
            { "nama": "0 - 1 = Tidak Ada Nyeri", "descNilai": 0 },
            { "nama": "2 - 3 = Sedikit Nyeri", "descNilai": 2 },
            { "nama": "4 - 5 = Cukup Nyeri", "descNilai": 4 },
            { "nama": "6 - 7 = Lumayan Nyeri", "descNilai": 6 },
            { "nama": "8 - 9 = Sangat Nyeri", "descNilai": 8 },
            { "nama": "10 = Amat Sangat Nyeri", "descNilai": 10 },
        ]
    }
}
export function imgNyeri() : any {
    return {
        "nama": "Hurts", "detail": [
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

export function resikoNutrisional() : any {
    return {
        "pertama": [{
            "no": 1,
            "parameter": "Apakah ada penurunan berat badan yang tidak diinginkan selama 6 bulan terakhir ?",
            "pengkajian": [
                {
                    "model": "penurunanBB",
                    "title": "Tidak",
                    "keterangan": "",
                    "poin": 0,
                    "value":
                    {
                        "poin": 0,
                        "keterangan": "Tidak",
                    }
    
                },
                {
                    "model": "penurunanBB",
                    "title": "Tidak Yakin",
                    "poin": 2,
                    "value":
                    {
                        "poin": 2,
                        "keterangan": "Tidak Yakin",
                    }
    
                },
                {
                    "model": "penurunanBB",
                    "title": "Ya,1-5 Kg",
                    "keterangan": "",
                    "poin": 1,
                    "value":
                    {
                        "poin": 1,
                        "keterangan": "Ya,1-5 Kg",
                    }
    
                },
                {
                    "model": "penurunanBB",
                    "title": "6-10 Kg",
                    "keterangan": "",
                    "poin": 2,
                    "value":
                    {
                        "poin": 2,
                        "keterangan": "6-10 Kg",
                    }
    
                },
                {
                    "model": "penurunanBB",
                    "title": "11-15 Kg",
                    "keterangan": "",
                    "poin": 3,
                    "value":
                    {
                        "poin": 3,
                        "keterangan": "11-15 Kg",
                    }
    
                },
                {
                    "model": "penurunanBB",
                    "title": "> 15 Kg",
                    "keterangan": "",
                    "poin": 4,
                    "value":
                    {
                        "poin": 4,
                        "keterangan": "> 15 Kg",
                    }
    
                },
            ],
        }],
        "kedua": [{
            "no": 2,
            "parameter": "Apakah asupan makan menurun yang dikarenakan adanya penurunan nafsu makan/kesulitan menerima makan ?",
            "pengkajian": [
                {
                    "model": "penurunanNafsuMakan",
                    "title": "Tidak",
                    "keterangan": "",
                    "poin": 0,
                    "value":
                    {
                        "poin": 0,
                        "keterangan": "Tidak",
                    }
    
                },
                {
                    "model": "penurunanNafsuMakan",
                    "title": "Ya",
                    "keterangan": "",
                    "poin": 1,
                    "value":
                    {
                        "poin": 1,
                        "keterangan": "Ya",
                    }
    
                },
            ],
        }],
    }
}

export function fungsionalPertama() : any {
    return [
        {
            "title": "Alat Bantu",
            "model": "alatBantu",
        },
        {
            "title": "ADL",
            "model": "adl",
        },
        {
            "title": "Pendidikan",
            "model": "pendidikan",
        },
        {
            "title": "Pekerjaan",
            "model": "pekerjaan",
        },
    ]
}
export function nilaiPoin() : any {
    return ['Skor 0 - 1', 'Skor 2 - 3', 'Skor > 4 ']
}
export function descNilai() : any {
    return ['Tidak Beresiko', 'Beresiko (Asuhan Gizi Oleh Dietizen)', 'Skor > 4 ']
}

export function pertanyaanA() : any {
    return [{
        "pertanyaan": "A. Perhatikan cara berjalan pasien saat akan duduk di kursi, apakah pasien tampa seimbang (sempoyongan/limbung) ?",
        "jawaban": [
            {
                "deskripsi": "Kondisi Pasien Akan Duduk Sempoyangan",
                "jawab": 'Ya'
            },
            {
                "deskripsi": "Kondisi Pasien Akan Duduk Sempoyangan",
                "jawab": 'Tidak'
            },
        ]
    }]
}

export function pertanyaanB() : any {
    return [{
        "pertanyaan": "B. Apakah pasien memegang pinggiran kursi atau meja atau benda lain sebagai penopang saat akan duduk ?",
        "jawaban": [
            {
                "deskripsi": "Pegangan Pasien ketika Duduk",
                "jawab": 'Ya'
            },
            {
                "deskripsi": "Pegangan Pasien ketika Duduk",
                "jawab": 'Tidak'
            },
        ]
    }]
}
export function pertanyaanC() : any {
    return [{
        "pertanyaan": "C. Hasil",
        "detail": [
            {
                "title" : "Tidak beresiko (tidak ditemukan a dan b)",
                "value" : "Tidak beresiko",
            },
            {
                "title" : "Risiko tinggi (ditemukan a dan b)",
                "value" : "Risiko tinggi",
            },
            {
                "title" : "Risiko Rendah Sedang (ditemukan a atau b)",
                "value" : "Risiko Rendah Sedang",
            },
        ]
    }]
}