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
                    "deskripsi"  : "Darah yang dipindahkan dapat berupa darah lengkap atau hanya komponen darah"
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
                    "model" : "isiTindakanKedokteran",
                    "type"  : "textArea",
                    "deskripsi"  : "Untuk menolong pasien yang mengalami kurang darah baik dalam bentuk sel darah maupun komponen komponennya"
                }

            ],
            "pilihan": [
                {
                    "label": "Ada",
                    "model": "adaTindakanKedokteran"
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
                    "deskripsi"  : "Pemindahan darah selain dapat menolong orang dapat juga menimbulkan penyulit tergantung pada masing masing individu penerimanaya, terlebih pada pasien yang ada riwayat alergi"
                }
            ],
            "pilihan": [
                {
                    "label": "Ada",
                    "model": "AdaIndikasi"
                },
            ],          
        },
        {
            "no": 5.,
            "Kriteria": "Komplikasi",
            "keterangan": [
                {
                    "model" : "isiTujuan",
                    "type"  : "textArea",
                    "deskripsi" : "Penyulit atau komplikasi yang bisa timbul sifatnya dapat terjadi cepat (akut) atau lambat, selain itu bila dilakukan Transfusi dalam jumlah banyak dan cepat juga dapat menimbulkan akibat seperti yang terjadi di bagian belakang surat pernyataan ini."
                }
            ],
            "pilihan": [
                {
                    "label": "Ada",
                    "model": "AdaTujuan"
                },
            ],          
        },
       
       
    ]
}