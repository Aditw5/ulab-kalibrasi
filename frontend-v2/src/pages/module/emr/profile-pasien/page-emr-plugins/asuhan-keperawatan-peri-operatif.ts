export function Pengkajian(): any {
    return [
        {
            "title": "Jenis Operasi",
            "value": [
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "KetkerusakanTindakan_",
                },    
            ]
        }, 
        {
            "title": "Pernapasan",
            "value": [
                {
                    "subTitle": "Spontan",
                    "type": "checkBox",
                    "model": "pernapasan_",
                },
                {
                    "subTitle": "Bantuan terapi O2 (Nasal/FM/ETT)",
                    "type": "checkBox",
                    "model": "pernapasan_",
                },
            ]
        }, 
        
    ]
}
export function tandaVital(): any {
    return [
        {
            "title": "Sirkulasi",
            "value": [
                {
                    "subTitle": "Tekanan darah",
                    "model": "tekananDarah",
                    "type": "textfiled",
                    "satuan": "mmHg"
                },
                {
                    "subTitle": "Nadi",
                    "model": "nadi",
                    "type": "textfiled",
                    "satuan": "x/menit"
                },
        
                {
                    "subTitle": "Pernafasan",
                    "model": "pernapasan",
                    "type": "textfiled",
                    "satuan": "x/menit"
                },
                {
                    "subTitle": "Suhu",
                    "model": "suhu",
                    "type": "textfiled",
                    "satuan": "C",
    
                },
            ],
        },
    ]
}
export function kesadaran(): any {
    return [
        {
            "title": "Kesadaran",
            "value": [
                {
                    "subTitle": "Compos Mentis",
                    "type": "checkBox",
                    "model": "kesadaran_",
                },
                {
                    "subTitle": "Somnolen",
                    "type": "checkBox",
                    "model": "kesadaran_",
                },
                {
                    "subTitle": "Sopor",
                    "type": "checkBox",
                    "model": "kesadaran_",
                },
                {
                    "subTitle": "Coma",
                    "type": "checkBox",
                    "model": "kesadaran_",
                },
            ]
        }, 
        
    ]
}
export function Molekul(): any {
    return [
        {
            "title": "Patah Tulang",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "patahTulang_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "patahTulang_",
                },
            ]
        }, 
        {
            "title": "Risiko Jatuh",
            "value": [
                {
                    "subTitle": "Rendah",
                    "type": "checkBox",
                    "model": "risikoJatuh_",
                },
                {
                    "subTitle": "Sedang",
                    "type": "checkBox",
                    "model": "risikoJatuh_",
                },
            ]
        }, 
        
    ]
}
export function Integumen() : any {
    return [
        {
            "title": "Luka Bakar",
            "value": [
                {
                    "subTitle": "Ya, Lokasi",
                    "type": "checkBox",
                    "model": "lukaBakar_",
                },
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketlukaBakar_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "lukaBakar_",
                },
            ]
        },
        {
            "title": "Lecet",
            "value": [
                {
                    "subTitle": "Ya, Lokasi",
                    "type": "checkBox",
                    "model": "lecet_",
                },
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketlecet_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "lecet_",
                },
            ]
        },  
        {
            "title": "Status Psikologis",
            "value": [
                {
                    "subTitle": "Tenang",
                    "type": "checkBox",
                    "model": "statusPsikologis_",
                },
                {
                    "subTitle": "Gelisah",
                    "type": "checkBox",
                    "model": "statusPsikologis_",
                },
                {
                    "subTitle": "Banyak Bicara",
                    "type": "checkBox",
                    "model": "statusPsikologis_",
                },
                {
                    "subTitle": "Meringgis",
                    "type": "checkBox",
                    "model": "statusPsikologis_",
                },
                {
                    "subTitle": "Merintih",
                    "type": "checkBox",
                    "model": "statusPsikologis_",
                },
                {
                    "subTitle": "Menangis",
                    "type": "checkBox",
                    "model": "statusPsikologis_",
                },
            ]
        },  
    ]
}
export function diagnosa(): any {
    return [
        {
            "title": "",
            "value": [
                {
                    "subTitle": "Cemas : Kurang informasi tentang prosedur operasi",
                    "type": "checkBox",
                    "model": "diagnosa_",
                },
                {
                    "subTitle": "Nyeri akut",
                    "type": "checkBox",
                    "model": "diagnosa_",
                },
                {
                    "subTitle": "Trauma jaringan",
                    "type": "checkBox",
                    "model": "diagnosa_",
                },
                {
                    "subTitle": "Inkontinuitas jaringan",
                    "type": "checkBox",
                    "model": "diagnosa_",
                },
                {
                    "subTitle": "Inkontinuitas jaringan",
                    "type": "checkBox",
                    "model": "diagnosa_",
                },
                {
                    "subTitle": "Proses peradangan",
                    "type": "checkBox",
                    "model": "diagnosa_",
                },
                {
                    "subTitle": "HIS",
                    "type": "checkBox",
                    "model": "diagnosa_",
                },
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "KetkerusakanTindakan_",
                },    
                
            ]
        },  
    ]
}
export function intervensi(): any {
    return [
        {
            "title": "",
            "value": [
                {
                    "subTitle": "Kaji data yang berhubungan dengan diagnosa keperawatan",
                    "type": "checkBox",
                    "model": "intervensi_",
                },
                {
                    "subTitle": "Bina hubungan saling percaya : Salam dan perkenalkan diri pada pasien, identifikasi klien",
                    "type": "checkBox",
                    "model": "intervensi_",
                },
                {
                    "subTitle": "Orientasi pasien terhadap lingkungan kamar bedah dr. Bedah, Anestesi, Perawat yang akan melaksanakan tindakan Jelaskan : Tujuan pembedahan, Prosedur pembiusan dan pembedahan",
                    "type": "checkBox",
                    "model": "intervensi_",
                },
                {
                    "subTitle": "Anjurkan / ajak klien untuk berdoa",
                    "type": "checkBox",
                    "model": "intervensi_",
                },
                {
                    "subTitle": "Atur posisi tidur klien yang nyaman Pasang restrain",
                    "type": "checkBox",
                    "model": "intervensi_",
                },
                {
                    "subTitle": "Ajarkan teknik relaksasi dan distraksi",
                    "type": "checkBox",
                    "model": "intervensi_",
                },
            ]
        },
    ]
}
export function implementasi() : any {
    return [
        {
            "title": "",
            "value": [
                {
                    "subTitle": "Mengkaji TTV",
                    "type": "checkBox",
                    "model": "implementasi_",
                },
                {
                    "subTitle": "Memperkenalkan diri, mengidentifikasi klien dan mengorientasikan lingkungan",
                    "type": "checkBox",
                    "model": "implementasi_",
                },
                {
                    "subTitle": "Menjelaskan prosedur pembiusan dan pembedahan",
                    "type": "checkBox",
                    "model": "implementasi_",
                },
                {
                    "subTitle": "Mengajak klien berdoa",
                    "type": "checkBox",
                    "model": "implementasi_",
                },
                {
                    "subTitle": "Mengatur posisi tidur klien",
                    "type": "checkBox",
                    "model": "implementasi_",
                },
                {
                    "subTitle": "Memasang restrain",
                    "type": "checkBox",
                    "model": "implementasi_",
                },
                {
                    "subTitle": "Mengajarkan teknik relaksasi dan distraksi",
                    "type": "checkBox",
                    "model": "implementasi_",
                },
            ]
        },
    ]
}

export function pengkajian2(): any {
    return [
        {
            "title": "Posisi Klien",
            "value": [
                {
                    "subTitle": "Supine",
                    "type": "checkBox",
                    "model": "posisiKlien_",
                },
                {
                    "subTitle": "Prone",
                    "type": "checkBox",
                    "model": "posisiKlien_",
                },
                 {
                    "subTitle": "Litothomy",
                    "type": "checkBox",
                    "model": "posisiKlien_",
                },
                {
                    "subTitle": "Lateral Kiri / Kanan",
                    "type": "checkBox",
                    "model": "posisiKlien_",
                },
            ]
        }, 
        {
            "title": "Sirkulasi",
            "value": [
            ]
        },
        {
            "title": "Lihat lembar status anestesi/sedasi",
            "value": [
                {
                    "subTitle": "Perdarahan",
                    "type": "checkBox",
                    "model": "lembar_",
                },
                {
                    "subTitle": "Balance cairan",
                    "type": "checkBox",
                    "model": "lembar_",
                },
                 {
                    "subTitle": "TTV",
                    "type": "checkBox",
                    "model": "lembar_",
                },
               
            ]
        }, 
        {
            "title": "CRT",
            "value": [
                {
                    "subTitle": ">3",
                    "type": "checkBox",
                    "model": "crt_",
                },
                 {
                    "subTitle": "<3",
                    "type": "checkBox",
                    "model": "crt_",
                },
            ]
        },
        {
            "title": "Akral",
            "value": [
                {
                    "subTitle": "Hangat",
                    "type": "checkBox",
                    "model": "akral_",
                },
                 {
                    "subTitle": "Dingin",
                    "type": "checkBox",
                    "model": "akral_",
                },
                {
                    "subTitle": "Cyanosis",
                    "type": "checkBox",
                    "model": "akral_",
                },
            ]
        },
        {
            "title": "Irigiasi",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "irigiasi",
                },
                 {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "irigiasi",
                },
            ]
        },
        {
            "title": "Jenis Cairan",
            "value": [
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "jenisCairan_",
                },
            ]
        },
        {
            "title": "Jumlah",
            "value": [
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "jumlah_",
                },
            ]
        },
        {
            "title": "Integumen",
            "value": [
            ]
        },
        {
            "title": "Diatermi",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "diatermi_",
                },
                 {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "diatermi_",
                },
            ]
        },
        {
            "title": "Jenis",
            "value": [
                {
                    "subTitle": "Monopolar",
                    "type": "checkBox",
                    "model": "jenis_",
                },
                 {
                    "subTitle": "Bipolar, Merk/Tipe",
                    "type": "checkBox",
                    "model": "jenis_",
                },
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketjenis_",
                },
            ]
        },
        {
            "title": "Lokasi pemasangan patient plate",
            "value": [
                {
                    "subTitle": "Dibawah Betis Kanan/ Kiri",
                    "type": "checkBox",
                    "model": "lokasi_",
                },
                 {
                    "subTitle": "Diatas Lengan Kanan/ Kiri",
                    "type": "checkBox",
                    "model": "lokasi_",
                },
                {
                    "subTitle": "Di Bawah Bokong Kanan/ Kiri",
                    "type": "checkBox",
                    "model": "lokasi_",
                },
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketlokasi_",
                },
            ]
        },
        {
            "title": "Unit pemanas",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "unit_",
                },
                 {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "unit_",
                },
            ]
        },
        {
            "title": "Jenis Unit Pemanas",
            "value": [
                {
                    "subTitle": "Anak / Bayi",
                    "type": "checkBox",
                    "model": "jenis2",
                },
                 {
                    "subTitle": "Dewasa",
                    "type": "checkBox",
                    "model": "jenis2",
                },
            ]
        },
        {
            "title": "Torniquet",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "torniquet_",
                },
                 {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "torniquet_",
                },
            ]
        },
        {
            "title": "Catheter",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "Catheter_",
                },
                 {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "Catheter_",
                },
            ]
        },
        {
            "title": "Jenis",
            "value": [
                {
                    "subTitle": "2 Cabang",
                    "type": "checkBox",
                    "model": "jenisCatheter_",
                },
                 {
                    "subTitle": "3 Cabang",
                    "type": "checkBox",
                    "model": "jenisCatheter_",
                },
            ]
        },
        {
            "title": "Pemasangan Implant",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "pemasanganImplan_",
                },
                 {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "pemasanganImplan_",
                },
            ]
        },
        {
            "title": "Lokasi",
            "value": [
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "lokasiImplan_",
                },
                
            ]
        },
        {
            "title": "Jenis",
            "value": [
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "jenisImplan_",
                },
                
            ]
        },


    ]
}
export function diagnosa2(): any {
    return [
        {
            "title": "",
            "value": [
                {
                    "subTitle": "Ganggan perfusi jaringan perifer : Perdarahan",
                    "type": "checkBox",
                    "model": "diagnosaIntra_",
                },
                {
                    "subTitle": "Risiko terjadinya cidera :",
                    "type": "checkBox",
                    "model": "diagnosaIntra_",
                },
                {
                    "subTitle": "Pemasangan Diatermi",
                    "type": "checkBox",
                    "model": "diagnosaIntra_",
                },
                {
                    "subTitle": "Pemasangan Restrain",
                    "type": "checkBox",
                    "model": "diagnosaIntra_",
                },
                {
                    "subTitle": "Posisi Tidur Tidak Anatomis",
                    "type": "checkBox",
                    "model": "diagnosaIntra_",
                },
                {
                    "subTitle": "Risiko Jatuh",
                    "type": "checkBox",
                    "model": "diagnosaIntra_",
                },
            ]
        },
    ]
}
export function intervensi2(): any {
    return [
        {
            "title": "",
            "value": [
                {
                    "subTitle": "Kajian data yang berhubungan dengan diagnosa keperawatan",
                    "type": "checkBox",
                    "model": "intervensi2_",
                },
                {
                    "subTitle": "Pasang selimut pemanas",
                    "type": "checkBox",
                    "model": "intervensi2_",
                },
                {
                    "subTitle": "Atur posisi tidur klien",
                    "type": "checkBox",
                    "model": "intervensi2_",
                },
                {
                    "subTitle": "Pasang patient plate",
                    "type": "checkBox",
                    "model": "intervensi2_",
                },
                {
                    "subTitle": "Pasang restrain",
                    "type": "checkBox",
                    "model": "intervensi2_",
                },
                {
                    "subTitle": "Berikan cairan infus / produk darah sesuai order",
                    "type": "checkBox",
                    "model": "intervensi2_",
                },
                {
                    "subTitle": "Hitung intake / output cairan",
                    "type": "checkBox",
                    "model": "intervensi2_",
                },
                
            ]
        },
    ]
}
export function implementasi2(): any {
    return [
        {
            "title": "",
            "value": [
                {
                    "subTitle": "Mengkaji TTV",
                    "type": "checkBox",
                    "model": "implementasi2_",
                },
                {
                    "subTitle": "Memasang selimut pemanas",
                    "type": "checkBox",
                    "model": "implementasi2_",
                },
                {
                    "subTitle": "Mengatur posisi tidur klien dengan benar, memberi ganjal pada tumit, lengan, kaki dan axila",
                    "type": "checkBox",
                    "model": "implementasi2_",
                },
                {
                    "subTitle": "Memasang patient plate dengan benar",
                    "type": "checkBox",
                    "model": "implementasi2_",
                },
                {
                    "subTitle": "Memasang restrain",
                    "type": "checkBox",
                    "model": "implementasi2_",
                },
                {
                    "subTitle": "Memberikan cairan infus / produk darah sesuai order",
                    "type": "checkBox",
                    "model": "implementasi2_",
                },
                {
                    "subTitle": "Menghitung intake / output cairan",
                    "type": "checkBox",
                    "model": "implementasi2_",
                },
               
            ]
        }
    ]
}
export function pengkajian3(): any {
    return [
        {
            "title": "Pernapasan",
            "value": [
                {
                    "subTitle": "Spontan",
                    "type": "checkBox",
                    "model": "pengkajian3_",
                },    
                {
                    "subTitle": "Bantuan terapi O2 (Nasal/FM/ETT)",
                    "type": "checkBox",
                    "model": "pengkajian3_",
                },    
            ]
        }, 
        {
            "title": "Sirkulasi",
            "value": [

            ]
        }, 
        {
            "title": "Lihat gambar status anestesi/sedasi",
            "value": [
                {
                    "subTitle": "Perdarahan",
                    "type": "checkBox",
                    "model": "statusAnestesi_",
                },    
                {
                    "subTitle": "Balance cairan",
                    "type": "checkBox",
                    "model": "statusAnestesi_",
                },  
                {
                    "subTitle": "TTV",
                    "type": "checkBox",
                    "model": "statusAnestesi_",
                },      
            ]
        }, 
        {
            "title": "Kesadaran",
            "value": [
                {
                    "subTitle": "Compos Mentis",
                    "type": "checkBox",
                    "model": "kesadaran_",
                },    
                {
                    "subTitle": "Samnolen",
                    "type": "checkBox",
                    "model": "kesadaran_",
                },  
                {
                    "subTitle": "Sopor",
                    "type": "checkBox",
                    "model": "kesadaran_",
                },      
                {
                    "subTitle": "Coma",
                    "type": "checkBox",
                    "model": "kesadaran_",
                },      
            ]
        }, 
        {
            "title": "Muskuloskeletal",
            "value": [

            ]
        }, 
        {
            "title": "Resiko Jatuh",
            "value": [
                {
                    "subTitle": "Rendah",
                    "type": "checkBox",
                    "model": "resikoJatuh_",
                },    
                {
                    "subTitle": "Sedang",
                    "type": "checkBox",
                    "model": "resikoJatuh_",
                },  
                {
                    "subTitle": "Tinggi",
                    "type": "checkBox",
                    "model": "resikoJatuh_",
                },      
            ]
        }, 
        {
            "title": "Integumen",
            "value": [
                {
                    "subTitle": "Kulit teraba dingin",
                    "type": "checkBox",
                    "model": "Integumen_",
                },    
                {
                    "subTitle": "Suhu tubuh dibawah rentang normal",
                    "type": "checkBox",
                    "model": "Integumen_",
                },  
                {
                    "subTitle": "Menggigil",
                    "type": "checkBox",
                    "model": "Integumen_",
                },      
            ]
        }, 
    ]
}
export function diagnosa3(): any {
    return [
        {
            "title": "",
            "value": [
                {
                    "subTitle": "Bersihkan jalan nafas tidak efektif : Akumulasi sekret",
                    "type": "checkBox",
                    "model": "diagnosa3_",
                },   
                {
                    "subTitle": "Nyeri : Inkontinuitas jaringan",
                    "type": "checkBox",
                    "model": "diagnosa3_",
                },  
                {
                    "subTitle": "Risiko Cidera",
                    "type": "checkBox",
                    "model": "diagnosa3_",
                },     
                {
                    "subTitle": "Pemasangan Diartemi",
                    "type": "checkBox",
                    "model": "diagnosa3_",
                },   
                {
                    "subTitle": "Pemasangan Restrain",
                    "type": "checkBox",
                    "model": "diagnosa3_",
                },     
                {
                    "subTitle": "Posisi tidur tidak anatomis",
                    "type": "checkBox",
                    "model": "diagnosa3_",
                },     
                {
                    "subTitle": "Risiko Jatuh",
                    "type": "checkBox",
                    "model": "diagnosa3_",
                },     
                        
            ]
        }, 
    ]
}

export function intervensi3(): any {
    return [
        {
            "title": "",
            "value": [
                {
                    "subTitle": "Kaji data yang berhubungan dengan diagnosa keperawatan",
                    "type": "checkBox",
                    "model": "intervensi3_",
                },    
                {
                    "subTitle": "Atur posisi tidur untuk keefektifan jalan nafas",
                    "type": "checkBox",
                    "model": "intervensi3_",
                },    
                {
                    "subTitle": "Beri oksigen sesuai kebutuhan",
                    "type": "checkBox",
                    "model": "intervensi3_",
                },   
                {
                    "subTitle": "Pasang air way peringeal",
                    "type": "checkBox",
                    "model": "intervensi3_",
                },   
                {
                    "subTitle": "Lakukan pengisapan sekret",
                    "type": "checkBox",
                    "model": "intervensi3_",
                },    
                {
                    "subTitle": "Ajarkan teknik batuk efektif dan mobilisasi",
                    "type": "checkBox",
                    "model": "intervensi3_",
                },    
                {
                    "subTitle": "Ajarkan teknik relaksasi dan distraksi",
                    "type": "checkBox",
                    "model": "intervensi3_",
                },    
                {
                    "subTitle": "Pasang selimut",
                    "type": "checkBox",
                    "model": "intervensi3_",
                },    
                {
                    "subTitle": "Pasang bed plang",
                    "type": "checkBox",
                    "model": "intervensi3_",
                },   
                {
                    "subTitle": "Pasang retrain",
                    "type": "checkBox",
                    "model": "intervensi3_",
                },    
                {
                    "subTitle": "Kolaborasi pemberian analgetik",
                    "type": "checkBox",
                    "model": "intervensi3_",
                },      
            ]
        }, 
    ]
}

export function implementasi3(): any {
    return [
        {
            "title": "",
            "value": [
                {
                    "subTitle": "Mengkaji TTV, pernapasan, dan sirkulasi",
                    "type": "checkBox",
                    "model": "implementasi3_",
                },  
                {
                    "subTitle": "Mengatur Posisi tidur untuk keefektifan jalan nafas",
                    "type": "checkBox",
                    "model": "implementasi3_",
                },  
                {
                    "subTitle": "Membari oksigen sesuai dengan kebutuhan",
                    "type": "checkBox",
                    "model": "implementasi3_",
                },  
                {
                    "subTitle": "Memasang air way peringel (Jika Perlu)",
                    "type": "checkBox",
                    "model": "implementasi3_",
                },  
                {
                    "subTitle": "Melakukan pengisapan sekret (jika perlu)",
                    "type": "checkBox",
                    "model": "implementasi3_",
                },  
                {
                    "subTitle": "Mengajarkan teknik batuk efektif dan mobilisasi",
                    "type": "checkBox",
                    "model": "implementasi3_",
                },  
                {
                    "subTitle": "Mengajarkan teknik relaksasi dan distraksi",
                    "type": "checkBox",
                    "model": "implementasi3_",
                },  
                {
                    "subTitle": "Memasang Selimut",
                    "type": "checkBox",
                    "model": "implementasi3_",
                },  
                {
                    "subTitle": "Memasang bed plang",
                    "type": "checkBox",
                    "model": "implementasi3_",
                },  
                {
                    "subTitle": "Memasang restrain (jika perlu)",
                    "type": "checkBox",
                    "model": "implementasi3_",
                },  
                {
                    "subTitle": "Melakukan Kolaborasi pemberian analgetik",
                    "type": "checkBox",
                    "model": "implementasi3_",
                },  
              
            ]
        } 
    ]
}

export function evaluasiS(): any {
    return [
        {
            "title": "Keadaan Pasien",
            "value": [
                {
                    "subTitle": "Tenang",
                    "type": "checkBox",
                    "model": "keadaanPasien_",
                },    
                {
                    "subTitle": "Gelisah",
                    "type": "checkBox",
                    "model": "keadaanPasien_",
                },    
                {
                    "subTitle": "CSG",
                    "type": "checkBox",
                    "model": "keadaanPasien_",
                },    
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "KetkeadaanPasien_",
                },    
            ]
        }, 
        {
            "title": "TTV",
            "value": [
                {
                    "subTitle": "Dalam Batas Normal",
                    "type": "checkBox",
                    "model": "ttv",
                },    
                {
                    "subTitle": "Perubahan yang signifikan",
                    "type": "checkBox",
                    "model": "ttv",
                },    
            ]
        }, 
        {
            "title": "Ekspresi Wajah",
            "value": [
                {
                    "subTitle": "Meringis",
                    "type": "checkBox",
                    "model": "ekspresiWajah_",
                },    
                {
                    "subTitle": "Merintih",
                    "type": "checkBox",
                    "model": "ekspresiWajah_",
                },    
                {
                    "subTitle": "Menangis",
                    "type": "checkBox",
                    "model": "ekspresiWajah_",
                },    
            ]
        }, 
        {
            "title": "Adanya cidera",
            "value": [
                {
                    "subTitle": "Luka Bakar Diatermi",
                    "type": "checkBox",
                    "model": "adanyaCidera_",
                },    
                {
                    "subTitle": "Risiko Jatuh",
                    "type": "checkBox",
                    "model": "adanyaCidera_",
                },    
                
            ]
        }, 
        {
            "title": "Perdarahan",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "perdarahan_",
                },    
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "perdarahan_",
                },    
                
            ]
        }, 
        {
            "title": "CRT",
            "value": [
                {
                    "subTitle": "< 3 detik",
                    "type": "checkBox",
                    "model": "crt_",
                },    
                {
                    "subTitle": "> 3 detik",
                    "type": "checkBox",
                    "model": "crt_",
                },    
                
            ]
        }, 
        {
            "title": "Akral",
            "value": [
                {
                    "subTitle": "Hangat",
                    "type": "checkBox",
                    "model": "akral_",
                },    
                {
                    "subTitle": "Dingin",
                    "type": "checkBox",
                    "model": "akral_",
                },    
                {
                    "subTitle": "Cynosis",
                    "type": "checkBox",
                    "model": "akral_",
                },    
                
            ]
        }, 
        {
            "title": "Pernapasan",
            "value": [
                {
                    "subTitle": "Sesak",
                    "type": "checkBox",
                    "model": "pernapasan_",
                },    
                {
                    "subTitle": "Tidak Sesak",
                    "type": "checkBox",
                    "model": "pernapasan_",
                },    
                {
                    "subTitle": "ETT",
                    "type": "checkBox",
                    "model": "pernapasan_",
                },    
                
            ]
        }, 
        {
            "title": "Kulit",
            "value": [
                {
                    "subTitle": "Dingin",
                    "type": "checkBox",
                    "model": "kulit_",
                },    
                {
                    "subTitle": "Hangat",
                    "type": "checkBox",
                    "model": "kulit_",
                },    
                {
                    "subTitle": "ETT",
                    "type": "checkBox",
                    "model": "kulit_",
                },    
                
            ]
        }, 
    ]
}
export function evaluasiA(): any {
    return [
        {
            "title": "Cemas",
            "value": [
                {
                    "subTitle": "Belum Teratasi",
                    "type": "checkBox",
                    "model": "cemas_",
                },
                {
                    "subTitle": "Teratasi Sebagian",
                    "type": "checkBox",
                    "model": "cemas_",
                },
                {
                    "subTitle": "Teratasi",
                    "type": "checkBox",
                    "model": "cemas_",
                },
            ]
        }, 
         {
            "title": "Nyeri",
            "value": [
                {
                    "subTitle": "Belum Teratasi",
                    "type": "checkBox",
                    "model": "nyeri_",
                },
                {
                    "subTitle": "Teratasi Sebagian",
                    "type": "checkBox",
                    "model": "nyeri_",
                },
                {
                    "subTitle": "Teratasi",
                    "type": "checkBox",
                    "model": "nyeri_",
                },
            ]
        }, 
        {
            "title": "Risiko Cedera",
            "value": [
                {
                    "subTitle": "Belum Teratasi",
                    "type": "checkBox",
                    "model": "cedera_",
                },
                {
                    "subTitle": "Teratasi Sebagian",
                    "type": "checkBox",
                    "model": "cedera_",
                },
                {
                    "subTitle": "Teratasi",
                    "type": "checkBox",
                    "model": "cedera_",
                },
            ]
        }, 
         {
            "title": "Gangguan Perfusi Jaringan",
            "value": [
                {
                    "subTitle": "Belum Teratasi",
                    "type": "checkBox",
                    "model": "perfusi_",
                },
                {
                    "subTitle": "Teratasi Sebagian",
                    "type": "checkBox",
                    "model": "perfusi_",
                },
                {
                    "subTitle": "Teratasi",
                    "type": "checkBox",
                    "model": "perfusi_",
                },
            ]
        }, 
        {
            "title": "Bersihkan Jalan Nafas",
            "value": [
                {
                    "subTitle": "Belum Teratasi",
                    "type": "checkBox",
                    "model": "jalanNafas_",
                },
                {
                    "subTitle": "Teratasi Sebagian",
                    "type": "checkBox",
                    "model": "jalanNafas_",
                },
                {
                    "subTitle": "Teratasi",
                    "type": "checkBox",
                    "model": "jalanNafas_",
                },
            ]
        }, 
        {
            "title": "Hipotermia",
            "value": [
                {
                    "subTitle": "Belum Teratasi",
                    "type": "checkBox",
                    "model": "hipotermia_",
                },
                {
                    "subTitle": "Teratasi Sebagian",
                    "type": "checkBox",
                    "model": "hipotermia_",
                },
                {
                    "subTitle": "Teratasi",
                    "type": "checkBox",
                    "model": "hipotermia_",
                },
            ]
        }, 
    ]
}
export function evaluasiP(): any {
    return [
        {
            "title": "",
            "value": [
                {
                    "subTitle": "Lanjutkan intervensi terhadap diagnosa keperawatan yang belum teratasi",
                    "type": "checkBox",
                    "model": "evaluasiP_",
                },
                {
                    "subTitle": "Lihat intruksi pada laporan operasi dan status anestesi/sedasi",
                    "type": "checkBox",
                    "model": "evaluasiP_",
                },
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketevaluasiP_",
                },
            ]
        }, 
    ]
}






























