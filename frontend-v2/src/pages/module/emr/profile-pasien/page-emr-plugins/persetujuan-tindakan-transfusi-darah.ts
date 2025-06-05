export function DaftarInformasi(): any {
    return [
        {
            "no": 1.,
            "Kriteria": "Pengertian Transfusi",
            "keterangan": [
                {
                    "model" : "isiDiagnosa",
                    "type"  : "textArea",
                    "deskripsi"  : "Suatu proses pemindahan darah dari orang sehat (Donor) ke tubuh orang sakit (Pasien)"
                }
            ],
            "pilihan": [
                {
                    "label": "Ada",
                    "model": "AdaDiagnosa"
                },
            ],          
        },
        {
            "no": 2.,
            "Kriteria": "Jenis Darah",
            "keterangan": [
                {
                    "model" : "isiDasar",
                    "type"  : "textArea",
                    "deskripsi"  : "Darah yang dipindahkan dapat berupa darah lengkap atau komponen darah"
                }
            ],
            "pilihan": [
                {
                    "label": "Ada",
                    "model": "adaDasarDiagnosa"
                },
            ],          
        },
        {
            "no": 3.,
            "Kriteria": "Tujuan Transfusi",
            "keterangan": [
               
                {
                    "model" : "isiTranfusi",
                    "type"  : "textArea",
                    "deskripsi"  : "Untuk menolong pasien yang mengalami kurang darah baik dalam sel darah maupun komponen komponennya"
                }

            ],
            "pilihan": [
                {
                    "label": "Ada",
                    "model": "adaTranfusi"
                },
            ],          
        },
        {
            "no": 4.,
            "Kriteria": "Risiko",
            "keterangan": [
                {
                    "model" : "isiIndikasi",
                    "type"  : "textArea",
                    "deskripsi"  : "Pemindahan darah selain dapat menolong orang dapat juga menimbulkan penyulit tergantung pada masing - masing individu penerimanya, terlebih pada pasien yang ada riwayat alergi "
                }
            ],
            "pilihan": [
                {
                    "label": "Ada",
                    "model": "adaIndikasi"
                },
            ],          
        },
        {
            "no": 5.,
            "Kriteria": "Komplikasi",
            "keterangan": [
                {
                    "model" : "isiTatacara",
                    "type"  : "textArea",
                    "deskripsi"  : "Penyulit atau komplikasi yang bisa timbul sifatnya dapat terjadi cepat (akut) atau lambat, selain itu jika dilakukan transfusi dalam jumlah banyak dan cepat juga dapat menimbulkan akibat seperti Hipotermi yaitu suhu tubuh menurun, dan beberapa penyakit lainnya"
                }
            ],
            "pilihan": [
                {
                    "label": "Ada",
                    "model": "adaTataCara"
                },
            ],          
        },
        
       
    ]
}