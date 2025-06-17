<?php
/*
| Ever tried.
| Ever failed.
| No matter.
| Try Again.
| Fail again.
| Fail better".
| , Samuel Beckett
|
*/

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthCtrl;
use App\Http\Controllers\EMR\TindakanCtrl;
use App\Http\Controllers\Bridging\BSRECtrl;
use App\Http\Controllers\General\GeneralCtrl;
use App\Http\Controllers\General\SysAdminCtrl;
use App\Http\Controllers\EMR\InputDiagnosaCtrl;
use App\Http\Controllers\EMR\ProfilePasienCtrl;
use App\Http\Controllers\Registrasi\PasienCtrl;
use App\Http\Controllers\Registrasi\MitraCtrl;
use App\Http\Controllers\Asman\AsmanCtrl;
use App\Http\Controllers\Manager\ManagerCtrl;
use App\Http\Controllers\Penyelia\PenyeliaCtrl;
use App\Http\Controllers\Pelaksana\PelaksanaCtrl;
use App\Http\Controllers\Farmasi\InputResepCtrl;
use App\Http\Controllers\Farmasi\OrderResepCtrl;
use App\Http\Controllers\Logistik\KartuStokCtrl;
use App\Http\Controllers\Sysadmin\MasterSukuCtrl;
use App\Http\Controllers\Logistik\StokRuanganCtrl;
use App\Http\Controllers\Sysadmin\MasterAgamaCtrl;
use App\Http\Controllers\Sysadmin\MasterKamarCtrl;
use App\Http\Controllers\Sysadmin\MasterKelasCtrl;
use App\Http\Controllers\Sysadmin\MasterPaketCtrl;
use App\Http\Controllers\Sysadmin\MasterSignaCtrl;
use App\Http\Controllers\Registrasi\PasienBaruCtrl;
use App\Http\Controllers\Registrasi\MitraBaruCtrl;
use App\Http\Controllers\Registrasi\PasienLamaCtrl;
use App\Http\Controllers\Registrasi\MitraLamaCtrl;
use App\Http\Controllers\Sysadmin\MasterNegaraCtrl;
use App\Http\Controllers\Sysadmin\MasterProdukCtrl;
use App\Http\Controllers\Sysadmin\MasterJabatanCtrl;
use App\Http\Controllers\Sysadmin\MasterPegawaiCtrl;
use App\Http\Controllers\Sysadmin\MasterRekananCtrl;
use App\Http\Controllers\Sysadmin\MasterRuanganCtrl;
use App\Http\Controllers\BedahSentral\OrderBedahCtrl;
use App\Http\Controllers\Dashboard\DashboardApotikCtrl;
use App\Http\Controllers\Dashboard\DashboardBedahCtrl;
use App\Http\Controllers\Sysadmin\MasterProvinsiCtrl;
use App\Http\Controllers\Radiologi\OrderRadiologiCtrl;
use App\Http\Controllers\Sysadmin\MasterKecamatanCtrl;
use App\Http\Controllers\Sysadmin\MasterPekerjaanCtrl;
use App\Http\Controllers\Logistik\DistribusiBarangCtrl;
use App\Http\Controllers\Sysadmin\MasterAsalProdukCtrl;
use App\Http\Controllers\Sysadmin\MasterDepartemenCtrl;
use App\Http\Controllers\Sysadmin\MasterPendidikanCtrl;
use App\Http\Controllers\Sysadmin\MasterTandaTanganCtrl;
use App\Http\Controllers\Sysadmin\SettingDataFixedCtrl;
use App\Http\Controllers\Sysadmin\MasterEMRCtrl;
use App\Http\Controllers\Dashboard\DashboardPegawaiCtrl;
use App\Http\Controllers\Dashboard\DashboardMasterDataCtrl;
use App\Http\Controllers\Dashboard\DashboardRadiologiCtrl;
use App\Http\Controllers\Dashboard\DashboardRJCtrl;
use App\Http\Controllers\Dashboard\DashboardRICtrl;
use App\Http\Controllers\Dashboard\DashboardObatAlkesCtrl;
use App\Http\Controllers\Dashboard\DashboardTindakanCtrl;
use App\Http\Controllers\Dashboard\DashboardLaboratoriumCtrl;
use App\Http\Controllers\Dashboard\DashboardKasirCtrl;
use App\Http\Controllers\Dashboard\DashboardLogistikCtrl;
use App\Http\Controllers\Sysadmin\MasterAsalRujukanCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisProdukCtrl;
use App\Http\Controllers\Sysadmin\MasterMapKelompokCtrl;
use App\Http\Controllers\Sysadmin\MasterSatuanResepCtrl;
use App\Http\Controllers\Sysadmin\MasterTipePegawaiCtrl;
use App\Http\Controllers\Farmasi\DaftarPasienFarmasiCtrl;
use App\Http\Controllers\Registrasi\DaftarRegistrasiCtrl;
use App\Http\Controllers\Sysadmin\MasterJadwalDokterCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisJabatanCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisKelaminCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisPegawaiCtrl;
use App\Http\Controllers\Sysadmin\MasterKelompokUserCtrl;
use App\Http\Controllers\Sysadmin\MasterRouteFarmasiCtrl;
use App\Http\Controllers\Sysadmin\MasterStatusKeluarCtrl;
use App\Http\Controllers\Sysadmin\MasterStatusPulangCtrl;
use App\Http\Controllers\Registrasi\RegistrasiRuanganCtrl;
use App\Http\Controllers\Registrasi\RegistrasiMitraCtrl;
use App\Http\Controllers\Sysadmin\MasterGolonganDarahCtrl;
use App\Http\Controllers\Sysadmin\MasterKondisiPasienCtrl;
use App\Http\Controllers\Sysadmin\MasterKotaKabupatenCtrl;
use App\Http\Controllers\Sysadmin\MasterModulAplikasiCtrl;
use App\Http\Controllers\Sysadmin\MasterSatuanStandarCtrl;
use App\Http\Controllers\Sysadmin\MasterStatusPegawaiCtrl;
use App\Http\Controllers\Sysadmin\MasterKelompokPasienCtrl;
use App\Http\Controllers\Sysadmin\MasterKelompokProdukCtrl;
use App\Http\Controllers\Sysadmin\MasterProdusenProdukCtrl;
use App\Http\Controllers\Sysadmin\MasterSlottingOnlineCtrl;
use App\Http\Controllers\Laboratorium\OrderLaboratoriumCtrl;
use App\Http\Controllers\Sysadmin\MasterKelompokJabatanCtrl;
use App\Http\Controllers\Sysadmin\MasterTambahLoginUserCtrl;
use App\Http\Controllers\Sysadmin\MasterMapPaketToProdukCtrl;
use App\Http\Controllers\Sysadmin\MasterStatusPerkawinanCtrl;
use App\Http\Controllers\Sysadmin\MasterDetailJenisProdukCtrl;
use App\Http\Controllers\Sysadmin\MasterKelompokTransaksiCtrl;
use App\Http\Controllers\Sysadmin\MasterMapRuanganToKelasCtrl;
use App\Http\Controllers\Farmasi\TransaksiPelayananFarmasiCtrl;
use App\Http\Controllers\Sysadmin\MasterAsalAnggaranCtrl;
use App\Http\Controllers\Sysadmin\MasterAsalSukuCadangCtrl;
use App\Http\Controllers\Sysadmin\MasterAsuransiPasienCtrl;
use App\Http\Controllers\Sysadmin\MasterKelompokShiftCtrl;
use App\Http\Controllers\Sysadmin\MasterBahanProdukCtrl;
use App\Http\Controllers\Sysadmin\MasterBentukProdukCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisKondisiPasienCtrl;
use App\Http\Controllers\Sysadmin\MasterMapRuanganToProdukCtrl;
use App\Http\Controllers\Sysadmin\MasterDetailKategoryPegawaiCtrl;
use App\Http\Controllers\Sysadmin\MasterDiagnosaCtrl;
use App\Http\Controllers\Sysadmin\MasterDiagnosaTindakanCtrl;
use App\Http\Controllers\Sysadmin\MasterGenerikCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisPetugasPelaksanaCtrl;
use App\Http\Controllers\Sysadmin\MasterMapLoginUserToRuanganCtrl;
use App\Http\Controllers\Sysadmin\MasterHargaNettoProdukByKelasCtrl;
use App\Http\Controllers\Sysadmin\MasterHubunganKeluargaCtrl;
use App\Http\Controllers\Sysadmin\MasterJadwalPraktekCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisAlamatCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisGenerikCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisKomponenHargaCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisPerawatanCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisRacikanCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisTarifCtrl;
use App\Http\Controllers\Sysadmin\MasterKategoriDiagnosaCtrl;
use App\Http\Controllers\Sysadmin\MasterKedudukanPegawaiCtrl;
use App\Http\Controllers\Sysadmin\MasterKomponenHargaCtrl;
use App\Http\Controllers\Sysadmin\MasterMapLoginUserToModulAplikasiCtrl;
use App\Http\Controllers\Sysadmin\MasterMapJenisPetugasToJenisPegawaiCtrl;
use App\Http\Controllers\Sysadmin\MasterMerkProdukCtrl;
use App\Http\Controllers\Sysadmin\MasterRhesusCtrl;
use App\Http\Controllers\Sysadmin\MasterSatuanBesarCtrl;
use App\Http\Controllers\Sysadmin\MasterSatuanKecilCtrl;
use App\Http\Controllers\Sysadmin\MasterShiftKerjaCtrl;
use App\Http\Controllers\Sysadmin\MasterStatusApotikCtrl;
use App\Http\Controllers\Sysadmin\MasterStatusBedCtrl;
use App\Http\Controllers\Sysadmin\MasterTempatTidurCtrl;
use App\Http\Controllers\Sysadmin\MasterListCtrl;
use App\Http\Controllers\Kasir\BillingCtrl;
use App\Http\Controllers\Kasir\TagihanPasienCtrl;
use App\Http\Controllers\Kasir\DaftarPasienPulangCtrl;
use App\Http\Controllers\Kasir\VerifikasiTagihanCtrl;
use App\Http\Controllers\Kasir\PembayaranTagihanCtrl;
use App\Http\Controllers\Logistik\StokBarangCtrl;
use App\Http\Controllers\Logistik\TransferBarangCtrl;
use App\Http\Controllers\RawatInap\PulangPindahCtrl;
use App\Http\Controllers\Bridging\BridgingBPJSCtrl;
use App\Http\Controllers\Bridging\InaCbgCtrl;
use App\Http\Controllers\Bridging\BridgingSirsOnlineCtrl;
use App\Http\Controllers\Registrasi\PemakaianAsuransiCtrl;
use App\Http\Controllers\Dashboard\DashboardRegistrasiCtrl;
use App\Http\Controllers\Dashboard\DashboardGiziCtrl;
use App\Http\Controllers\Farmasi\PelayananObatBebasCtrl;
use App\Http\Controllers\Logistik\PenerimaanBarangCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisDietCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisUsulanCtrl;
use App\Http\Controllers\Sysadmin\MasterKategoryDietCtrl;
use App\Http\Controllers\Kasir\DaftarPenerimaanKasirCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisWaktuCtrl;
use App\Http\Controllers\Kasir\DaftarPasienAktifKasirCtrl;
use App\Http\Controllers\Kasir\DaftarTagihanNonLayananCtrl;
use App\Http\Controllers\Sysadmin\MasterKonversiSatuanCtrl;
use App\Http\Controllers\Sysadmin\MasterSediaanCtrl;
use App\Http\Controllers\Kasir\DaftarPengeluaranKasirCtrl;
use App\Http\Controllers\Kasir\PiutangPasienCtrl;
use App\Http\Controllers\Kasir\TagihanNonLayananCtrl;
use App\Http\Controllers\Logistik\OrderBarangCtrl;
use App\Http\Controllers\Laboratorium\LaboratoriumCtrl;
use App\Http\Controllers\Registrasi\MutasiPasienCtrl;
use App\Http\Controllers\EMR\EMRCtrl;
use App\Http\Controllers\EMR\ReportEMRCtrl;
use App\Http\Controllers\Laboratorium\PendukungPemeriksaanCtrl;
use App\Http\Controllers\Radiologi\RadiologiCtrl;
use App\Http\Controllers\Reservasi\ReservasiMobileCtrl;
use App\Http\Controllers\Antrian\AntrianCtrl;
use App\Http\Controllers\Cathlab\OrderCathlabCtrl;
use App\Http\Controllers\Cathlab\CathlabCtrl;
use App\Http\Controllers\Dashboard\DashboardCathlabCtrl;
use App\Http\Controllers\Humas\HumasCtrl;
use App\Http\Controllers\Laporan\LaporanPengunjungCtrl;
use App\Http\Controllers\Registrasi\DaftarPasienPerjanjianCtrl;
use App\Http\Controllers\Registrasi\RegistrasiPasienCtrl;
use App\Http\Controllers\Bridging\SATUSEHATCtrl;
use App\Http\Controllers\Kiosk\KiosKController;
use App\Http\Controllers\Report\ReportCtrl;
use App\Http\Controllers\Dashboard\DashboardIGDCtrl;
use App\Http\Controllers\Sysadmin\MasterPaketObatCtrl;
use App\Http\Controllers\Bridging\BridgingPenunjangCtrl;
use App\Http\Controllers\Logistik\PurchaseOrderCtrl;
use App\Http\Controllers\RekamMedis\RekamMedisCtrl;
use App\Http\Controllers\Sysadmin\MasterAlergiCtrl;
use App\Http\Controllers\Bridging\AntrianOnlineCtrl;
use App\Http\Controllers\Sysadmin\MapAdministrasiCtrl;
use App\Http\Controllers\Sysadmin\MapProdukPacsCtrl;
use App\Http\Controllers\Bridging\NoAuthCtrl;
use App\Http\Controllers\Higea\HigeaCtrl;
use App\Http\Controllers\Sysadmin\MasterMapDepoToRuanganCtrl;
use App\Http\Controllers\Sysadmin\MapAkomodasiCtrl;
use App\Http\Controllers\Laporan\LaporanTindakanPasienCtrl;
use App\Http\Controllers\Logistik\PersediaanCtrl;
use App\Http\Controllers\Piutang\PiutangCtrl;
use App\Http\Controllers\Bridging\SiranapCtrl;
use App\Http\Controllers\Gizi\MasterMenuGiziCtrl;
use App\Http\Controllers\laporan\LaporanRekamMedisCtrl;
use App\Http\Controllers\Sysadmin\MasterSatuanGiziCtrl;
use App\Http\Controllers\Sysadmin\MapKelompokLaporanCtrl;
use App\Http\Controllers\Sysadmin\MapKsmToRuanganCtrl;
use App\Http\Controllers\Sysadmin\MasterKelompokLaporanCtrl;
use App\Http\Controllers\Sysadmin\MasterKSMCtrl;
use App\Http\Controllers\Sysadmin\MasterVendorGiziCtrl;
use App\Http\Controllers\WaServer\WaServerCtrl as WaServerWaServerCtrl;
use App\Http\Controllers\WaServerCtrl;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['jwt.auth'])->prefix("service")->group(function () {
Route::middleware(['log'])->group(function () {
    // Route::prefix("histransmedic/serve")->group(function () {


    Route::prefix("general/menu")->group(function () {
        Route::controller(SysAdminCtrl::class)->group(function () {
            Route::get('/list-menu', 'listMenu');
        });
    });
    Route::prefix("bridging")->group(function () {
        Route::controller(BridgingBPJSCtrl::class)->group(function () {
            Route::post('/bpjs/tools', 'bpjsTools');
            Route::get('/bpjs/get-rujukan-pcare-nokartu', 'getNoRujukanPcareNoKartu');
            Route::get('/bpjs/get-kamar-rs', 'getKamarRS');
            Route::post('/bpjs/get-list-pemakaian-asuransi', 'getListPemakaianAsuransi');
            Route::post('/bpjs/update-kamar', 'updateAplicaresBedAfter');
        });
        Route::controller(InaCbgCtrl::class)->group(function () {
            Route::get('/inacbgs', 'daftarPasienINACBG');
            Route::get('/inacbgs/dropdown', 'dropDownINACBG');
            Route::get('/inacbgs/dokter-paging', 'listDokterPaging');
            Route::get('/inacbgs/get-status', 'getStatusBridgingINACBG');
            Route::get('/inacbgs/get-status-grouping', 'getGroupingINACBG');
            Route::get('/inacbgs/bundle-dokumen', 'bundleDokumen');
            Route::get('/inacbgs/get-for-plafon', 'getFlafonINACBG');
            Route::get('/inacbgs/claim-print', 'claimPRINT');
            Route::post('/inacbgs/bundle-all-documents', 'downloadAllDocuments');
            Route::post('/inacbgs/save-verifikasi-koder', 'saveVerifikasiKoder');
            Route::post('/inacbgs/save-status-dpjp', 'saveStatusDPJPincbgs');
            Route::get('/inacbgs/berkas-pasien', 'getBerkasbyNoCM');

            Route::post('/inacbgs/save', 'saveBridgingINACBG');
            Route::post('/inacbgs/save-status', 'saveStatusBridgingINACBG');
            Route::post('/inacbgs/save-grouping', 'saveGroupingINACBG');
            Route::post('/inacbgs/save-dokumen', 'saveDokumenINACBG');
            Route::post('/inacbgs/collect-dokumen', 'collectDokumenINACBG');
            Route::post('/inacbgs/collect-semua-dokumen', 'collectSemuaDokumenINACBG');
            Route::post('/inacbgs/save-pemakaian-asuransi', 'savePemakaianAsuransi');

            Route::get('/klaim/get-daftar-klaim', 'getDaftarKlaim');
            Route::get('/klaim/get-monitoring-klaim', 'getMonitoringKlaim');
            Route::get('/klaim/data-history-kamar', 'dataHistoryKamar');
            Route::get('/klaim/get-daftar-hasil-lab', 'getDaftarKlaimHasilLab');
            Route::get('/klaim/get-daftar-expertise-radiologi', 'getDaftarKlaimHasilRad');
            Route::get('/klaim/get-daftar-emr', 'getDaftarKlaimEMR');
            Route::get('/klaim/get-ulasan', 'loadUlasan');
            Route::post('/klaim/save-ulasan', 'saveUlasan');

        });
        Route::controller(BridgingPenunjangCtrl::class)->group(function () {
            Route::get('/penunjang/get-hasil-new', 'getHasilLabBridgingNew');
            Route::get('/penunjang/cetakan-hasil-lab-new', 'cetakHasilLabNew');
            Route::get('/penunjang/get-hasil-new-registrasi', 'getHasilLabBridgingNewRegistrasi');
            Route::get('/penunjang/get-hasil-microbiologi', 'getHasilLabBridgingMicrobiologi');

            Route::post('/penunjang/save-bridging-zeta', 'saveBridgingPacs');
            Route::post('/penunjang/save-bridging-zeta-terjadwal', 'saveBridgingPacsTerjadwal');
            Route::post('/penunjang/save-bridging-vans-lab', 'saveBridgingVansLab');
            Route::post('/penunjang/save-bridging-vans-lab-terjadwal', 'saveBridgingVansLabTerjadwal');
            Route::post('/penunjang/update-expertise', 'saveSendBack');
            Route::post('/penunjang/save-bridging-vans-lab-new', 'saveBridgingVansLabNew');
            Route::post('/penunjang/kirim-hasil-lab-wa', 'kirimHasilLabWA');
        });
        Route::controller(BridgingSirsOnlineCtrl::class)->group(function () {
            Route::post('/kemenkes/tools', 'kemenkesTools');

            Route::get('/rsonline/get-pasien', 'daftarPasienRS');
        });

        Route::controller(SATUSEHATCtrl::class)->group(function () {
            Route::get('/satusehat/get-list', 'getList');
            Route::get('/satusehat/get-setting', 'getSetting');
            Route::get('/satusehat/get-for-encounter', 'getListRegis');
            Route::get('/satusehat/get-for-observation', 'getListRegisObservation');

            Route::post('/satusehat/tools', 'ihsTools');
            Route::post('/satusehat/generate-token', 'generateToken');
            Route::post('/satusehat/Organization', 'Organization');
            Route::post('/satusehat/Location', 'Location');
            Route::post('/satusehat/Encounter', 'Encounter');
            Route::post('/satusehat/Condition', 'Condition');
            Route::post('/satusehat/Practitioner', 'Practitioner');
            Route::post('/satusehat/update-ihs-pasien', 'updateIHSPasien');
            Route::post('/satusehat/Medication', 'Medication');
            Route::post('/satusehat/MedicationRequest', 'MedicationRequest');
            Route::post('/satusehat/MedicationDispense', 'MedicationDispense');
            Route::post('/satusehat/MedicationDispenseObatBebas', 'MedicationDispenseObatBebas');
            Route::post('/satusehat/Observation', 'Observation');
            Route::post('/satusehat/Procedure', 'Procedure');
            Route::post('/satusehat/Immunization', 'Immunization');
            Route::post('/satusehat/Composition', 'Composition');
            Route::post('/satusehat/ServiceRequest', 'ServiceRequest');
            Route::post('/satusehat/Specimen', 'Specimen');
            Route::post('/satusehat/ObservationLab', 'ObservationLab');
            Route::post('/satusehat/ObservationRad', 'ObservationRad');
            Route::post('/satusehat/DiagnosticReport', 'DiagnosticReport');
            Route::post('/satusehat/ObservationLabDiagnos', 'ObservationLabDiagnos');
            Route::post('/satusehat/AllergyIntolerance', 'AllergyIntolerance');
            Route::post('/satusehat/ClinicalImpression', 'ClinicalImpression');
            Route::post('/satusehat/ObservationKesadaran', 'ObservationKesadaran');
            Route::post('/satusehat/ProcedureEdukasi', 'ProcedureEdukasi');
            Route::post('/satusehat/ConditionSaatMeninggalkanRS', 'ConditionSaatMeninggalkanRS');
            Route::post('/satusehat/MedicationStatement', 'MedicationStatement');
            Route::post('/satusehat/QuestionnaireResponse', 'QuestionnaireResponse');
            Route::post('/satusehat/CarePlan', 'CarePlan');
        });

        Route::controller(AntrianOnlineCtrl::class)->group(function () {
            Route::post('/antrol/antrean', 'ambilAntrean');
            Route::post('/antrol/sendDataAntrean', 'sendDataAntrean');
            Route::post('/antrol/sendTaskId', 'sendTaskId');
            Route::get('/antrol/ambilWaktudiKiosk', 'ambilWaktudiKiosk');
            Route::get('/antrol/getMonitoringWaktu', 'getMonitoringWaktu');
            Route::post('/antrol/saveMonitoringTaksId', 'saveMonitoringTaksId');
            Route::get('/antrol/getComboMonitoring', 'getComboMonitoring');
            Route::get('/antrol/getDataAntrean', 'getDataAntrean');
            Route::post('/antrol/updateDataAntrean', 'updateDataAntrean');
        });
        Route::controller(SiranapCtrl::class)->group(function () {
            Route::post('/siranap/tools', 'siranapTools');
            Route::get('/siranap/get-tt', 'getTTeuy');
            Route::get('/siranap/master-kelas', 'masterKelas');
            Route::get('/siranap/master-kamar', 'masterKamar');
            Route::post('/siranap/map-kelas', 'mapKelas');
            Route::post('/siranap/map-kamar', 'mapKamar');
        });
    });
    Route::controller(DashboardPegawaiCtrl::class)->group(function () {
        Route::get('dashboard/data-pegawai', 'dashboardPegawai');
        Route::get('dashboard/data-pegawaiaktif', 'DataPegawaiAktif');
        Route::get('dashboard/get-ruangpegawai', 'getRuangKerja');

        Route::get('dashboard/get-detail-pegawai', 'getJumlah');
    });
    Route::controller(DashboardMasterDataCtrl::class)->group(function () {
        Route::get('dashboard/dashboard-sysadmin', 'dashboardMasterData');
        Route::get('dashboard/dashboard-masterdata', 'DataMaster');

        Route::post('dashboard/save-master-list', 'saveListMaster');
    });

    Route::controller(DashboardRJCtrl::class)->group(function () {
        Route::get('dashboard/rawat-jalan-detail', 'getRawatJalanDetail');
        Route::get('dashboard/jadwal-dokter', 'getJadDokter');
        Route::get('dashboard/rawat-jalan-pasien', 'getRJPasien');
        Route::get('dashboard/pasien-total', 'getRJPasienTotal');
        Route::get('dashboard/pasien-total-konsul', 'getRJPasienKonsul');
        // Route::get('dashboard/pasien-total-konsul', 'getRJPasienKonsul');
        Route::get('dashboard/rawat-jalan-reservasi', 'getRJPasienReservasi');
        Route::get('dashboard/dropdown-rawat-jalan', 'getDD');
        Route::get('dashboard/get-pelayanan-status', 'HitungAntrian');
        Route::get('dashboard/get-combo-jumlah', 'getComboCount');
        Route::get('dashboard/get-jumlah-konsul', 'CountKonsul');
        Route::get('dashboard/get-jumlah-request-sitb', 'CountSITB');
        Route::get('dashboard/get-detail-konsul','getDetailKonsul');
        Route::get('dashboard/cetak-konsul','cetakKonsul');

        Route::post('dashboard/rawat-jalan/panggil', 'panggilPasien');
        Route::post('dashboard/checkin-jkn', 'checkinJkn');
        Route::post('dashboard/batal-jkn', 'batalJkn');

        Route::post('/dashboard/send-WA', 'kirimWASuratDokter');
    });
    Route::controller(DashboardRegistrasiCtrl::class)->group(function () {
        Route::get('dashboard/registrasi/dropdown', 'getDropdown');
        Route::get('dashboard/registrasi', 'dashboardRegis');
        Route::get('dashboard/registrasi/list-pasien-reservasi', 'daftarReservasi');
        Route::get('dashboard/registrasi/cetak-label-pasien', 'cetakLabelPasien');
        Route::get('dashboard/registrasi/cetak-label-odc', 'cetakLabelODC');
        Route::get('dashboard/registrasi/cetak-kartu-pasien', 'cetakKartuPasien');
        Route::get('dashboard/registrasi/cetak-surat-keterangan-dokter', 'getDataSuratKeteranganDokterAsli');
        Route::get('dashboard/registrasi/cetak-surat-keterangan-keluar', 'getDataSuratKeteranganKeluar');
        Route::get('dashboard/registrasi/cetak-billing-bpjs', 'getDataBillingBpjs');
        Route::get('dashboard/get-norecapd', 'getAPD');

        Route::post('dashboard/registrasi/save-surat-regis-ranap', 'SaveSuratRegisRanap');
        Route::post('dashboard/registrasi/hapus-reservasi', 'hapusReservasi');
        Route::post('dashboard/save-batal-registrasi', 'saveBatalRegis');
        Route::post('dashboard/save-tanda-selesai', 'SaveTandaSelesai');
        Route::post('dashboard/save-nositb-pasien', 'SaveNoSITB');
        Route::post('dashboard/order-nositb-pasien', 'OrderNoSITB');
        Route::post('dashboard/save-inputan-infeksi-ppi', 'saveInfeksiPPI');
        Route::post('dashboard/update-tanda-pasien', 'UpdateTandaPasienIGD');
    });

    Route::controller(DashboardRICtrl::class)->group(function () {
        Route::get('dashboard/dropdown-rawat-inap', 'getDropdown');
        Route::get('dashboard/detail-rawat-inap', 'getDetailRI');
        Route::get('dashboard/rawat-inap-pasien-total', 'getRIPasienTotal');
        Route::get('dashboard/rawat-inap-pasien-total-resume-null', 'getRIPasienTotalResumeNull');
        Route::get('dashboard/pasien-rawat-inap-resume-null', 'getPasienRanapRemueNull');
        Route::get('dashboard/rawat-inap/list', 'getRIPasien');
        Route::get('dashboard/rawat-inap-resume', 'getRIPasienResume');
        Route::get('dashboard/pasien-ppi-ranap', 'getPasienRanapPPI');
        Route::get('dashboard/pasien-ppi-rajal', 'getPasienRajalPPI');
        Route::get('dashboard/kmkb', 'getKMKB');
        Route::get('dashboard/get-data-surat-keterangan', 'getDataSuratKeterangan');
        Route::get('dashboard/get-jumlah-pendapatan', 'getPendapatan');
        Route::get('dashboard/get-mutasi-ranap', 'getRiwayatMutasiRanap');
        Route::get('dashboard/get-daftarpasienrawatinap', 'getDaftarPasienRawatInap');

        Route::post('dashboard/batal-ranap', 'BatalRawatInap');
        Route::post('dashboard/save-surat-keterangan-dokter', 'SaveSuratKeteranganDokter');
        Route::post('dashboard/save-surat-keterangan-sakit', 'SaveSuratKeteranganSakit');

        Route::post('/dashboard/send-WA-Ranap', 'kirimWASuratDokterRanap');
    });

    Route::controller(DashboardGiziCtrl::class)->group(function () {
        Route::get('dashboard/detail-pasien-gizi', 'headerPasienGizi');
        Route::get('dashboard/pasien-order-gizi', 'getPasienInap');
        Route::get('dashboard/dropdown-order-gizi', 'listOrderGizi');
        Route::get('dashboard/riwayat-order-gizi', 'riwayatOrderGizi');
        Route::get('dashboard/laporan-order-gizi', 'laporanOrderGizi');
        Route::get('dashboard/riwayat-kirim-gizi', 'riwayatKirimGizi');
        Route::get('dashboard/riwayat-order-gizi-new', 'riwayatOrderGiziNew');
        Route::get('dashboard/get-laporan-order-gizi', 'getLaporanOrderGizi');

        Route::post('dashboard/save-order-gizi', 'simpanOrderGizi');
        Route::post('dashboard/delete-order-gizi', 'deleteOrderGizi');
        Route::post('dashboard/save-kirim-gizi', 'saveKirimGizi');
        Route::post('dashboard/save-status-order-gizi', 'saveStatusOrderGizi');
    });

    Route::controller(DashboardObatAlkesCtrl::class)->group(function () {
        Route::get('dashboard/data-obat', 'getObat');
        Route::get('farmasi/daftar-retur-obat-alkes', 'getDaftarReturObat');
        Route::get('farmasi/cetak-bukti-retur', 'cetakReturResep');
    });
    Route::controller(DashboardTindakanCtrl::class)->group(function () {
        Route::get('dashboard/data-tindakan', 'getTindakan');
        Route::get('dashboard/data-tindakan-dropdown', 'TindakanDropdown');
    });

    Route::controller(DashboardLaboratoriumCtrl::class)->group(function () {
        Route::get('dashboard/so-lab', 'getStrukOrderLab');
        Route::get('dashboard/list-lab', 'listLab');
        Route::get('dashboard/get-lab-verify', 'getOrderLab');
        Route::get('dashboard/lab-detail', 'getLabDetail');
        Route::get('dashboard/get-pelayanan-lab', 'getPelayananLab');
        Route::get('dashboard/get-order', 'getOrderPelayananLab');
        Route::get('dashboard/get-dokter-verify', 'ListDokterVerify');
        Route::get('dashboard/get-komponen-lab', 'getKomponenHargaLab');
        Route::get('dashboard/chart-lab-ruangan', 'chartOrderLabByRuangan');
        Route::get('dashboard/penunjang-lab', 'getPenunjangPasien');
        Route::get('dashboard/headerpasien', 'HeaderPasienLab');
        Route::get('laboratorium/report/bukti-layanan-lab', 'cetakBuktiLab');
        Route::get('dashboard/petugaspe', 'detailPetugasLab');

        Route::post('dashboard/batal-verif-lab', 'BatalVerifLab');
        Route::post('dashboard/delete-pp', 'hapusPelayananTindakan');
        Route::post('dashboard/delete-petugaspe', 'deleteJenisPetugasLab');
        Route::post('dashboard/save-order-pelayanan-lab', 'savePelayananPasienLab');
        Route::post('dashboard/save-order-pelayanan-lab-terjadwal', 'savePelayananPasienLabTerjadwal');
        Route::post('dashboard/save-petugaspe', 'savePetugasPe');
        Route::post('dashboard/save-jenkel', 'UpdateJenisKelamin');
        Route::post('dashboard/save-goldar', 'UpdateGolonganDarah');
    });

    Route::controller(DashboardKasirCtrl::class)->group(function () {
        Route::get('dashboard/kasir', 'countDashboardKasir');
        Route::get('dashboard/data-combo-kasir', 'getDataComboKasir');
        Route::get('dashboard/tagihan-lunas', 'TagihanLunas');
        Route::get('dashboard/kasir/list-tagihan-pasien', 'listTagihanPasien');
        Route::get('dashboard/tagihan-non-layanan', 'TagihanNonLayanan');
        Route::get('dashboard/daftar-pasien-pulang', 'daftarPasienPulang');
        Route::get('dashboard/daftar-pasien-pulang/detail-verif', 'detailVerifikasi');
        Route::post('dashboard/daftar-pasien-pulang/batal-verif', 'batalVerifikasiTagihan');
    });


    Route::controller(PasienLamaCtrl::class)->group(function () {
        Route::get('registrasi/pasien-lama', 'pasienLama');
        Route::get('registrasi/cek-pasien-pulang', 'cekPulangpasien');
        Route::get('registrasi/cek-sep', 'cekSEPpasien');
        Route::post('registrasi/delete-pasien', 'deletePasien');
    });

    Route::controller(MitraLamaCtrl::class)->group(function () {
        Route::get('registrasi/mitra-lama', 'mitraLama');
        Route::get('registrasi/cek-pasien-pulang', 'cekPulangpasien');
        Route::get('registrasi/cek-sep', 'cekSEPpasien');
        Route::post('registrasi/delete-pasien', 'deletePasien');
    });
    Route::controller(PasienBaruCtrl::class)->group(function () {
        Route::get('registrasi/desa-kelurahan-paging', 'listDesaKelurahanPaging');
        Route::get('registrasi/kecamatan-paging', 'listKecamatanPaging');
        Route::get('registrasi/kotakabupaten', 'listKotaKab');
        Route::get('registrasi/kecamatan', 'listKecamatan');
        Route::get('registrasi/desakelurahan', 'listDesa');
        Route::get('registrasi/list-dropdown', 'listDropdown');
        Route::get('registrasi/pasien', 'pasienByID');
        Route::get('registrasi/riwayat-registrasi', 'riwayatRegistrasi');

        Route::post('registrasi/save-pasien', 'savePasien');
        Route::post('registrasi/save-pasien-bayi', 'savePasienBayi');
        Route::post('registrasi/save-pasien-foto', 'savePasienFoto');
    });

    Route::controller(MitraBaruCtrl::class)->group(function () {
        Route::get('registrasi/desa-kelurahan-paging', 'listDesaKelurahanPaging');
        Route::get('registrasi/kecamatan-paging', 'listKecamatanPaging');
        Route::get('registrasi/kotakabupaten', 'listKotaKab');
        Route::get('registrasi/kecamatan', 'listKecamatan');
        Route::get('registrasi/desakelurahan', 'listDesa');
        Route::get('registrasi/list-mitra-dropdown', 'listDropdown');
        Route::get('registrasi/pasien', 'pasienByID');
        Route::get('registrasi/riwayat-registrasi', 'riwayatRegistrasi');

        Route::post('registrasi/save-mitra', 'saveMitra');
        Route::post('registrasi/save-pasien-bayi', 'savePasienBayi');
        Route::post('registrasi/save-pasien-foto', 'savePasienFoto');
    });
    Route::controller(RegistrasiRuanganCtrl::class)->group(function () {
        Route::get('registrasi/pasien-registrasi', 'pasienRegistrasi');
        Route::get('registrasi/dokter-paging', 'listDokterPaging');
        Route::get('registrasi/kelas-by-ruangan', 'listKelasByRuangan');
        Route::get('registrasi/kamar-by-kelas', 'listKamarByKelas');
        Route::get('registrasi/penjamin-by-kelompokpasien', 'listPenjaminByKelompokPasien');
        Route::get('registrasi/pilihan-ruangan', 'listRuanganByLoginUser');
        Route::get('registrasi/pasien-hari-ini', 'checkIsExsist');
        Route::get('registrasi/periode-bulanan', 'checkIsLessOneMonth');

        Route::post('registrasi/save-registrasi', 'saveRegistrasi');
        Route::post('registrasi/save-adminsitrasi', 'saveAdministrasi');
        Route::post('registrasi/confirmReservasi', 'confirmReservasi');
    });

    Route::controller(RegistrasiMitraCtrl::class)->group(function () {
        Route::get('registrasi/mitra-registrasi', 'mitraRegistrasi');
        Route::post('registrasi/save-registrasi-mitra', 'saveRegistrasiMitra');
    });
    Route::controller(PemakaianAsuransiCtrl::class)->group(function () {
        Route::get('registrasi/pemakaian-asuransi', 'pemakaianAsuransi');
        Route::get('registrasi/pemakaian-asuransi/sep', 'cetakSEP');

        Route::post('registrasi/pemakaian-asuransi/save', 'savePemakaianAsuransi');
    });
    Route::controller(PasienCtrl::class)->group(function () {
        Route::get('registrasi/list-pasien-grid', 'listPasienGrid');
        Route::get('registrasi/count-daftar', 'CountDaftar');
        Route::get('pasien/get-data-pasien-kanker', 'getDataPasienKanker');
        Route::post('pasien/batal-status-kanker', 'saveBatalStatusKanker');
        Route::post('pasien/jadikan-status-kanker', 'saveJadikanStatusKanker');
    });

    Route::controller(MitraCtrl::class)->group(function () {
        Route::get('registrasi/list-mitra-grid', 'listMitraGrid');
        Route::get('registrasi/get-alat-registrasi', 'getAlatRegistrasi');
        Route::get('registrasi/count-daftar', 'CountDaftar');
        Route::get('registrasi/header-mitra', 'HeaderMitra');
        Route::get('registrasi/layana-mitra', 'LayananKajian');
        Route::get('registrasi/pegawai-kalibrasi', 'pegawaiManager');
        Route::get('registrasi/pegawai-lokasi-kalibrasi', 'pegawaiLokasi');
        Route::post('registrasi/save-kajian-ulang-item', 'saveKajianUlangItem');
        Route::post('registrasi/save-kaji-ulang', 'saveKajiUlang');
        Route::post('registrasi/save-batal-regis-mitra', 'saveBatalRegis');
        Route::post('registrasi/save-konfirmasi-pendaftaran', 'saveKonfirmasiPendaftaran');
        Route::get('registrasi/cetak-tanda-terima', 'cetakTandaTerima');
        Route::get('registrasi/cetak-permintaan-kalibrasi', 'cetakPermintaanKalibrasi');
    });

    Route::controller(AsmanCtrl::class)->group(function () {
        Route::get('asman/list-mitra-regis', 'listMitraAsmanGrid');
        Route::get('asman/get-detail-pegawai', 'getAsmanDetail');
        Route::get('asman/layanan-verif', 'LayananVerif');
        Route::get('asman/detail-produk', 'detailProduk');
        Route::post('asman/save-verif-item', 'saveVerifItem');
        Route::post('asman/save-verif', 'saveVerif');
        Route::post('asman/save-penolakan', 'savePenolakanAsman');
        Route::get('asman/header-mitra', 'HeaderMitra');
        Route::get('asman/cetak-spk', 'cetakSPK');
        Route::get('asman/get-alat-asman', 'getAlatAsman');
        Route::get('asman/get-lembar-kerja-asman', 'getLembarKerjaAsman');
        Route::post('asman/save-setujui-serti-asman', 'setujuiSertifikatAsman');
        Route::get('asman/cetak-sertifikat-lembar-kerja', 'cetakSertifikatLembarKerja');
    });

    Route::controller(ManagerCtrl::class)->group(function () {
        Route::get('manager/list-mitra-regis', 'listMitraAsmanGrid');
        Route::get('manager/get-detail-pegawai', 'getAsmanDetail');
        Route::get('manager/detail-produk', 'detailProduk');
        Route::get('manager/header-mitra', 'HeaderMitra');
        Route::get('manager/cetak-spk', 'cetakSPK');
        Route::get('manager/get-alat-manager', 'getAlatManager');
        Route::get('manager/get-lembar-kerja-manager', 'getLembarKerjaManager');
        Route::post('manager/save-setujui-serti-manager', 'setujuiSertifikatManager');
        Route::get('manager/cetak-sertifikat-lembar-kerja', 'cetakSertifikatLembarKerja');
    });

    Route::controller(PenyeliaCtrl::class)->group(function () {
        Route::post('penyelia/save-verif-item', 'saveVerifItem');
        Route::get('penyelia/get-alat-penyelia', 'getAlatPenyelia');
        Route::get('penyelia/layanan-verif-penyelia', 'LayananVerifPenyelia');
        Route::post('penyelia/save-verif', 'saveVerif');
        Route::post('penyelia/save-setujui-serti', 'setujuiSertifikat');
        Route::get('penyelia/detail-produk', 'detailProduk');
        Route::get('penyelia/get-lembar-kerja', 'getLembarKerjaPenyelia');
        Route::get('penyelia/download-template-lembar-kerja', 'downloadTemplateLembarKerjaPenyelia');
        Route::post('penyelia/save-data-upload-lembar-kerja', 'simpanUploadLembaKerjaPenyelia');
        Route::get('penyelia/detail-produk-lembar-kerja', 'detailProdukLembarKerjaPenyelia');
        Route::get('penyelia/cetak-spk', 'cetakSPK');
        Route::get('penyelia/cetak-sertifikat-lembar-kerja', 'cetakSertifikatLembarKerja');
    });

    Route::controller(PelaksanaCtrl::class)->group(function () {
        Route::get('pelaksana/get-alat-pelaksana', 'getAlatPelaksana');
        Route::get('pelaksana/layanan-verif-pelaksana', 'LayananVerifPelaksana');
        Route::post('pelaksana/save-verif', 'saveVerif');
        Route::get('pelaksana/detail-produk', 'detailProduk');
        Route::get('pelaksana/get-lembar-kerja', 'getLembarKerja');
        Route::get('pelaksana/download-template-lembar-kerja', 'downloadTemplateLembarKerja');
        Route::post('pelaksana/save-data-upload-lembar-kerja', 'simpanUploadLembaKerja');
        Route::get('pelaksana/detail-produk-lembar-kerja', 'detailProdukLembarKeerja');
        Route::get('pelaksana/cetak-spk', 'cetakSPK');
        Route::get('pelaksana/cetak-sertifikat-lembar-kerja', 'cetakSertifikatLembarKerja');
        Route::get('pelaksana/get-merk-standar', 'getMerkStandar');
        Route::get('pelaksana/get-tipe-standar', 'getTipeStandar');
        Route::get('pelaksana/get-sn-standar', 'getSnStandar');
    });

    Route::controller(DaftarRegistrasiCtrl::class)->group(function () {
        Route::get('registrasi/daftar-registrasi-grid', 'listRegistrasi');
        Route::get('registrasi/daftar-registrasi-dropdown', 'listRegistrasiDropdown');
        Route::post('registrasi/cancel-registration', 'batalRegistrasi');
        Route::post('registrasi/change-dokter-registration', 'ubahDokter');
        Route::get('registrasi/get-detail-registrasi', 'detailRegistrasi');
        Route::get('registrasi/get-detail-registrasi-pasien', 'detailRegistrasiPasien');
        Route::get('registrasi/get-data-combo-detail-registrasi', 'getDataComboDetailRegis');
        Route::post('registrasi/simpan-pasien-konsul', 'simpanKonsul');
        Route::post('registrasi/change-dokter-apd', 'ubahDokterAPD');
        Route::post('registrasi/change-dokter-dpjp', 'ubahDokterDPJP');
        Route::post('registrasi/penanda-pasien', 'penandaPasien');
        Route::post('registrasi/pesan-ruangan', 'pesanRuangan');
        Route::post('registrasi/change-tglpulang', 'ubahTglPulang');
        Route::post('registrasi/merge-pasien', 'mergePasien');
        Route::post('registrasi/hapus-apd', 'hapusAPD');
        Route::post('registrasi/ubah-tanggal', 'ubahTanggalDetailRegis');
        Route::get('registrasi/get-daftar-pasien-meninggal2', 'getDaftarPasienMeningga2');
        Route::get('registrasi/get-daftar-konsultasi', 'getDaftarKonsulFromOrder');
        Route::get('registrasi/get-order-konsul', 'getOrderKonsul');
        Route::get('registrasi/get-order-sitb', 'getOrderSITB');
        Route::get('registrasi/get-ruangan', 'getRuangan');
        Route::get('registrasi/get-daftar-waiting-list', 'getDaftarWaitingList');
        Route::post('registrasi/get-save-konsul-order', 'saveKonsulFromOrder');
        Route::post('registrasi/get-save-sitb-order', 'saveSITBOrder');
        Route::post('registrasi/save-pilih-dokter-konsul', 'updateDokterAntrian');
        Route::post('registrasi/tetapkan-perawat', 'tetapkanPerawat');
    });
    Route::controller(ProfilePasienCtrl::class)->group(function () {
        Route::get('emr/header-pasien', 'headerPasien');
        Route::get('emr/detail-pelayanan', 'detailPelayanan');
        Route::get('emr/list-pasien-rj', 'listPasienRJ');
        Route::get('emr/total-biliing', 'getTotalBilling');
        Route::get('emr/info-pasien', 'infoPasien');
        Route::get('emr/history-sim-lama', 'detailPelayananSIMRSLama');
        Route::get('dokter/get-catatan', 'getCatatanDokter');
        Route::get('emr/hasil-lab', 'getHasilLab');
        Route::get('emr/hasil-lab-pa', 'hasilLabPA');
        Route::get('emr/count-dialisis', 'countDialisis');

        Route::post('emr/simpan-alergi-pasien', 'simpanAlergiPasien');
        Route::post('emr/closing-pasien', 'changeClosing');
        Route::post('dokter/save-catatan', 'saveCatatanDokter');
    });
    Route::controller(EMRCtrl::class)->group(function () {
        Route::get('emr/get-emr', 'getEMR');
        Route::get('emr/get-emr-perdosi', 'getEMRPerdosi');
        Route::get('emr/riwayat-emr', 'getRiwayatEMR');
        Route::get('emr/riwayat-emr-detail', 'getRiwayatEMR_DETAIL');
        Route::get('emr/menu-emr-detail', 'menuEMR');
        Route::get('emr/get-list-pegawai', 'getDataComboPegawai');
        Route::get('emr/get-diagnosa-pasien-icd9', 'getDiagnosaPasienByNoregICD9');
        Route::get('emr/get-diagnosa-pasien-icd10', 'getDiagnosaPasienICD10');
        Route::get('emr/get-data-diagnosa', 'getDataComboDiagnosa');
        Route::get('emr/dropdown/{table}', 'dropdownEMR');
        Route::get('emr/get-emr-dynamic', 'getEMRDynamic');
        Route::get('emr/get-data-exist', 'getDataExist');
        Route::get('emr/get-dokter-dpjp', 'getDokterDPJP');
        Route::get('emr/tanda-tangan/{pegawaifk}', 'getTandaTangan');
        Route::get('emr/auto-fill', 'getAutoFill');
        Route::get('emr/auto-fill-cppt', 'getAutoFillCppt');
        Route::get('emr/menu-emr', 'menuNavigasi');
        Route::get('emr/master-bundle-hais', 'getDataBundleHais');
        Route::get('emr/get-obat', 'getDataComboPartObat');
        Route::get('emr/auto-fill-icd10/{noregistrasi}', 'getAutoFillICD10');
        Route::get('emr/auto-fill-icd9/{noregistrasi}', 'getAutoFillICD9');
        Route::get('emr/register-pasien-emr', 'getAutoRegisterPasien');
        Route::get('emr/master-faktor-resiko', 'getFaktorRisiko');
        Route::get('emr/get-ecg-fukuda', 'getFukuda');
        Route::get('emr/combo-jenis-berkas', 'getComboBerkas');
        Route::get('emr/berkas-pasien', 'getBerkasPasien');
        Route::get('emr/get-perjanjian', 'getPasienPerjanjian');
        Route::get('emr/collection/{url_form}', 'getCollectionNameByForm');
        Route::get('emr/get-order-konsul', 'getOrderKonsul');
        Route::get('emr/get-resume-medis', 'getResumeMedis');
        Route::get('emr/get-dropdown-diagnosa-keperawatan', 'getDropdownDiagnosaKeper');
        Route::get('emr/get-emr-cppt', 'getEMRCPPT');
        Route::get('emr/get-emr-cppt-ranap', 'getEMRCPPTRanap');
        Route::get('emr/list-diagnosa-keperawatan', 'listDiagnosaKeperawatan');
        Route::get('emr/list-diagnosa-keperawatan-edokep', 'listDiagnosaKeperawatanEdokep');
        Route::get('emr/list-tujuan-diagnosa-keperawatan-edokep', 'listTujuanDiagnosaKeperawatan');
        Route::get('emr/list-intervensi-diagnosa-keperawatan-edokep', 'listIntervensiDiagnosaKeperawatan');
        Route::get('emr/list-aktifitas-diagnosa-keperawatan-edokep', 'listAktifitasDiagnosaKeperawatan');
        Route::get('emr/list-kriteria-diagnosa-keperawatan-edokep', 'listKriteriaDiagnosaKeperawatan');
        Route::get('emr/list-tujuan-keperawatan', 'listTujuanKeperawatan');
        Route::get('emr/list-intervensi', 'listIntervensiKeperawatan');
        Route::get('emr/list-implementasi', 'implementasiKeperawatan');
        Route::get('emr/auto-resep', 'getResep');
        Route::get('emr/get-petugas', 'getPetugasPe');
        Route::get('emr/get-hasil-radiologi', 'getHasilRadiologi');
        Route::get('emr/get-hasil-radiologi-emr', 'getHasilRadiologiEmr');
        Route::get('emr/pemantauan-penunjang', 'pemantauanPemeriksaanPenunjang');
        Route::get('emr/get-tabs-emr', 'getEMRTabs');

        Route::post('emr/simpan-emr', 'saveEMR');
        Route::post('emr/hapus-emr', 'hapusEMR');
        Route::post('emr/simpan-emr-cppt', 'saveEMRCPPT');
        Route::post('emr/simpan-diagnosa-x-cppt-ranap', 'saveDiagnosaXCPPTRanap');
        Route::post('emr/simpan-diagnosa-ix-cppt-ranap', 'saveDiagnosaIXCPPTRanap');
        Route::post('emr/hapus-emr-cppt', 'hapusEMRCPPT');
        Route::post('emr/verif-emr-cppt', 'verifEMRCPPT');
        Route::post('emr/verif-harian-emr-cppt', 'verifHarianEMRCPPT');
        Route::post('emr/simpan-bundle-hais', 'saveBundleHis');
        Route::post('emr/hapus-bundle-hais', 'hapusBundleHis');
        Route::post('emr/simpan-berkas-pasien', 'saveBerkasPasien');
        Route::post('emr/hapus-berkas-pasien', 'hapusBerkasPasien');
        Route::post('emr/simpan-perjanjian', 'simpanPerjanjian');
        Route::post('emr/hapus-perjanjian', 'hapusOrderPerjanjian');
        Route::post('emr/simpan-order-konsul', 'saveOrderKonsul');
        Route::post('emr/hapus-order-konsul', 'hapusOrderKonsul');
        Route::post('emr/jawab-order-konsul', 'jawabOrderKonsul');
        Route::post('emr/uploadImage', 'uploadImageBarcode');
        Route::post('emr/update-status-dpjp-koder', 'updatestausdpjpkoder');

        Route::post('emr/simpan-resume', 'saveResumeMedis');
        Route::post('emr/kirim-wa-resume', 'kirimResumeMedis');
        Route::post('emr/hapus-resume-medis', 'hapusResumeMedis');
    });

    Route::controller(ReportEMRCtrl::class)->group(function () {
        Route::get('emr/cetak-asesmen-medis-ri', 'cetakAsesmenMedisRI');
        Route::get('emr/cetak-asesmen-gizi-awal', 'cetakAsesmenGiziAwal');
        Route::get('emr/cetak-asesmen-awal-keper-ri', 'cetakAsesmenAwalKeperawatanRI');
        Route::get('emr/cetak-triase', 'cetakTriase');
        Route::get('emr/cetak-resume-medis-rj', 'cetakResumeRJ');
        Route::get('emr/cetak-asesmen-keper-rj', 'cetakAsesmenAwalKeperRJ');
        Route::get('emr/cetak-asesmen-medis-rj', 'cetakAsesmenMedisRJ');
        Route::get('emr/cetak-hasil-pemeriksaan-mcu', 'cetakHasilPemeriksaanMCU');
        Route::get('emr/cetak-pola-nafas-tidak-efektif', 'cetakPolaNafasTidakEfektif');
        Route::get('emr/cetak-formulir-skrining-igd', 'cetakFormulirSkriningIGD');
        Route::get('emr/cetak-resume-medis', 'cetakResumeMedis');
        Route::get('emr/cetak-formulir-permintaan-konseling-gizi', 'cetakFormulirPermintaanKonselingGizi');
        Route::get('emr/cetak-asesmen-keperawatan-igd', 'cetakAsesmenKeperawatanIGD');
        Route::get('emr/cetak/{collection}', 'cetakEMR');
        Route::get('emr/cetak-obat/{collection}', 'cetakSuratObatEMR');
        Route::get('emr/cetak-rehab-layanan/{collection}', 'cetakRehabLayanan');
        Route::get('emr/cetak-rehab-perdosi/{collection}', 'cetakRehabPerdosi');
        Route::get('emr/cetak-rehab-prosedur/{collection}', 'cetakRehabProsedur');
        Route::get('emr/cetak-surat-pengantar-ranap/{collection}', 'cetakSuratPengantarRananp');
        Route::get('emr/cetak-lembar-assesmen-gizi/{collection}', 'cetakLembarAssesmenGizi');
        Route::get('emr/cetak-lembar-assesmen-gizi-monitoring/{collection}', 'cetakLembarAssesmenGiziMonitoring');
        Route::get('emr/cetak-surat-kontrol', 'cetakSuratKontrol');
        Route::get('emr/cetak-spri', 'cetakSPRI');
        Route::get('emr/cetak-rujukan', 'cetakRujukan');
        Route::get('emr/get-riwayat-resep', 'getResep');
        Route::get('emr/cetak-pengkajian-dokter-ri', 'cetakPengkajianDokterRi');

        Route::post('/emr/send-WA', 'kirimWARujukan');
    });

    Route::controller(TindakanCtrl::class)->group(function () {
        Route::get('tindakan/header-pasien', 'headerPasien');
        Route::get('tindakan/list-tindakan', 'listTindakan');
        Route::get('tindakan/list-tindakan-komponen', 'listTindakanKomponen');
        Route::get('tindakan/list-jenis-petugas', 'listJenisPetugasPE');
        Route::get('tindakan/list-map-jenis-petugas', 'listMapJenisPetugasPE');
        Route::get('tindakan/list-paket', 'listPaket');

        Route::post('tindakan/save-tindakan', 'saveTindakan');
    });
    Route::controller(InputDiagnosaCtrl::class)->group(function () {
        Route::get('diagnosa/header-pasien', 'headerPasien');
        Route::get('diagnosa/diagnosa-x-paging', 'listDianosaX');
        Route::get('diagnosa/diagnosa-ix-paging', 'listDianosaIX');
        Route::get('diagnosa/list-dropdown', 'listDropdownDiagnosa');
        Route::get('diagnosa/riwayat-diagnosa-x', 'riwayatDiagnosaX');
        Route::get('diagnosa/riwayat-diagnosa-x-cppt', 'riwayatDiagnosaXCppt');
        Route::get('diagnosa/riwayat-diagnosa-ix', 'riwayatDiagnosaIX');
        Route::get('diagnosa/riwayat-diagnosa-ix-cppt', 'riwayatDiagnosaIXCppt');
        Route::get('diagnosa/riwayat-diagnosa-keperawatan', 'riwayatDiagnosaKeperawatan');
        Route::get('diagnosa/riwayat-perencanaan-keperawatan', 'riwayatPerencanaanKeperawatan');
        Route::get('diagnosa/riwayat-pengkajian-keperawatan', 'riwayatPengkajianKeperawatan');

        Route::post('diagnosa/save-diagnosa', 'saveDiagnosaPasien');
        Route::post('diagnosa/save-diagnosa-selesai', 'saveDiagnosaPasienSelesai');
        Route::post('diagnosa/save-diagnosa-ix', 'saveDiagnosaTindakanPasien');
        Route::post('diagnosa/save-diagnosa-ix-selesai', 'saveDiagnosaTindakanPasienSelesai');
        Route::post('diagnosa/save-more-diagnosa', 'saveMoreDiagnosaPasien');
        Route::post('diagnosa/save-more-diagnosa-ix', 'saveMoreDiagnosaTindakanPasien');
        Route::post('diagnosa/delete-diagnosa-x', 'deleteDiagnosaPasienX');
        Route::post('diagnosa/delete-diagnosa-ix', 'deleteDiagnosaPasienIX');
    });
    Route::controller(OrderLaboratoriumCtrl::class)->group(function () {
        Route::get('laboratorium/header-pasien-order', 'headerPasienOrder');
        Route::get('laboratorium/list-dropdown', 'listDropdown');
        Route::get('laboratorium/list-tindakan-for-order', 'listTindakanForOrder');
        Route::get('laboratorium/riwayat-order', 'listRiwayatOrder');
        Route::get('laboratorium/detail-order', 'detailOrder');
        Route::get('laboratorium/paket-pre-operasi', 'paketPreOP');
        Route::get('laboratorium/paket-laboratorium/{namaPaket}', 'paketLab');

        Route::post('laboratorium/simpan-order', 'simpanOrderLab');
        Route::post('laboratorium/delete-order', 'hapusOrderLab');
        Route::post('laboratorium/save-berkas-lab', 'saveBerkasLab');
        Route::post('laboratorium/update-filter-produk-lab', 'updateFilterProd');
    });
    Route::controller(OrderRadiologiCtrl::class)->group(function () {
        Route::get('radiologi/header-pasien-order', 'headerPasienOrder');
        Route::get('radiologi/list-dropdown', 'listDropdown');
        Route::get('radiologi/list-tindakan-for-order', 'listTindakanForOrder');
        Route::get('radiologi/riwayat-order', 'listRiwayatOrder');
        Route::get('radiologi/detail-order', 'detailOrder');

        Route::post('radiologi/delete-order-rad', 'hapusOrderRad');
    });
    Route::controller(OrderCathlabCtrl::class)->group(function () {
        Route::get('cathlab/header-pasien-order', 'headerPasienOrder');
        Route::get('cathlab/list-dropdown', 'listDropdown');
        Route::get('cathlab/list-tindakan-for-order', 'listTindakanForOrder');
        Route::get('cathlab/riwayat-order', 'listRiwayatOrder');
        Route::get('cathlab/detail-order', 'detailOrder');

        Route::post('cathlab/delete-order-rad', 'hapusOrderRad');
    });
    Route::controller(OrderBedahCtrl::class)->group(function () {
        Route::get('bedah/header-pasien-order', 'headerPasienOrder');
        Route::get('bedah/list-dropdown', 'listDropdown');
        Route::get('bedah/list-tindakan-for-order', 'listTindakanForOrder');
        Route::get('bedah/riwayat-order', 'listRiwayatOrder');
        Route::get('bedah/detail-order', 'detailOrder');

        Route::post('bedah/simpan-order', function (Request $request) {
            return app('App\Http\Controllers\Laboratorium\OrderLaboratoriumCtrl')->simpanOrderLab($request);
        });
        Route::post('bedah/delete-order-bedah', 'hapusOrderBedah');
    });
    Route::controller(OrderResepCtrl::class)->group(function () {
        Route::get('farmasi/riwayat-order-resep', 'riwayatOrderResep');
        Route::get('farmasi/riwayat-order-resep-cppt', 'riwayatOrderResepCppt');
        Route::get('farmasi/riwayat-order-resep-verif', 'riwayatOrderResepVerif');
        Route::get('farmasi/riwayat-resep-verif', 'resepVerif');
        Route::get('farmasi/resep-kpo', 'resepKPO');
        Route::get('farmasi/resep-kpo-stop', 'resepKPOStop');
        Route::post('farmasi/simpan-kpo', 'simpanKPO');
        Route::post('farmasi/validasi-pemberian', 'validasiPemberian');
        Route::post('farmasi/stop-kpo', 'stopKPO');
        Route::post('farmasi/ganti-dosis-kpo', 'gantiDosisKPO');
        Route::get('farmasi/riwayat-resep-pulang', 'riwayatResepPulang');
        Route::get('farmasi/riwayat-order-resep-pulang', 'riwayatOrderResepPulang');
        Route::get('farmasi/data-order-resep-hari-ini', 'getOrderResepNow');
        Route::get('farmasi/data-paket-obat', 'getDataPaketObat');

        Route::post('farmasi/simpan-order', 'simpanOrderResep');
        Route::post('farmasi/hapus-order', 'hapusOrderResep');
    });
    Route::controller(StokRuanganCtrl::class)->group(function () {
        Route::get('logistik/stok-ruangan-grid', 'getDataGrid');
        Route::get('logistik/stok-ruangan-cbo', 'getCombo');
    });

    Route::controller(PulangPindahCtrl::class)->group(function () {
        Route::get('rawatinap/get-pasien-pindah', 'dataPasien');
        Route::get('rawatinap/combo-pindah', 'dropDownPulang');
        Route::get('rawatinap/riwayat-apd', 'riwayatAPD');
        Route::get('rawatinap/kelas-ranap-by-ruangan', 'RanapKelasByRuangan');
        Route::get('rawatinap/kamar-ranap-by-kelas', 'RanapKamarByKelas');

        Route::post('rawatinap/save-pulang-pasien', 'savePulang');
        Route::post('rawatinap/save-pindah-pasien', 'savePindah');
        Route::post('rawatinap/save-pindah-bed-pasien', 'savePindahBed');
    Route::post('rawatinap/save-meninggal-pasien', 'saveMeninggal');
        Route::post('rawatinap/save-rujuk-pasien', 'saveRujuk');
        Route::post('rawatinap/batal-pindah-pasien', 'saveBatalPindah');
        Route::post('rawatinap/batal-pulang-pasien', 'saveBatalPulang');
    });
    Route::controller(PersediaanCtrl::class)->group(function () {
        Route::get('farmasi/saldo/ruangan', 'getRuanganPersediaan');
        Route::get('farmasi/get-saldo-ruangan-detail', 'getDataSaldoRuanganDetail');
        Route::get('farmasi/persediaan-dropdown', 'getDropdownPersediaan');
        Route::get('farmasi/get-data-laporan-persediaan-v4', 'getLaporanPersediaan_v4_2');
        Route::get('farmasi/get-data-laporan-persediaan-v5', 'getLaporanPersediaan_v5');

        Route::post('farmasi/get-save-saldo-produk-detail', 'saveSaldoProdukDetail');
    });

    Route::controller(DaftarPasienPerjanjianCtrl::class)->group(function () {
        Route::get('registrasi/daftar-pasien-penunjang', 'index');
        Route::post('registrasi/update-tanggal-reservasi', 'updateTglReservasi');
        Route::post('registrasi/delete-pasien-penunjang', 'deleteReservasi');
        Route::post('registrasi/reminder-pasien-reservasi', 'reminderPasienReservasi');
    });

    Route::controller(LaporanPengunjungCtrl::class)->group(function () {
        Route::get('pelayanan/get-laporan-pengunjung', 'getLaporanPengunjungPemeriksaan');
        Route::get('pelayanan/get-laporan-pengunjung-status', 'getLaporanPengunjungStatus');
        Route::get('pelayanan/get-laporan-pengunjung-status-kelompok', 'getLaporanPengunjungStatusKelompok');
        Route::get('pelayanan/get-laporan-pengunjung-tindakan', 'getLaporanPengunjungTindakan');
        Route::get('pelayanan/get-laporan-penyerahan-obat', 'getLaporanPenyerahanObat');
        Route::get('pelayanan/get-laporan-retur-suplier', 'getDaftarReturPenerimaanSuplierDetail');
        Route::get('pelayanan/get-laporan-retur-obat', 'getDaftarReturObatDetail');
        Route::get('pelayanan/get-laporan-sensus-rawat-inap', 'getLaporanSesusRawatInap');
        Route::get('pelayanan/get-laporan-antrian-kuota-poli', 'getLaporanAntrianKuotaPoli');
        Route::get('pelayanan/get-laporan-antrian-online', 'informasiAntrianPasienAntrol');
        Route::get('pelayanan/get-ruangan-poli', 'getRuanganPoli');
        Route::get('pelayanan/get-laporan-indexing-ri', 'getLaporanIndexingRI');
        Route::get('pelayanan/get-laporan-infeksi-ppi', 'getLaporanInfeksiPPI');
        Route::get('pelayanan/get-laporan-tindakan-ri', 'getLaporanTindakanRI');
        Route::get('pelayanan/get-laporan-ri', 'getLaporanRI');
        Route::get('pelayanan/get-laporan-indexing-gd', 'getLaporanIndexingGD');
        Route::get('pelayanan/get-laporan-penanda-pasien-igd', 'getPenandaPasien');
        Route::get('pelayanan/get-laporan-indexing-rj', 'getLaporanIndexingRJ');
        Route::get('pelayanan/get-laporan-Antrol', 'getlaporanAntol');
        Route::get('pelayanan/get-laporan-data-rehab-medik', 'getLaporanDataPasienRehabMedik');
        Route::get('pelayanan/get-laporan-penyakit-tidak-menular', 'getLaporanPenyakitTidakMenular');
        Route::get('pelayanan/get-laporan-insidensi-pegawai-sakit', 'getLaporanInsidensiPegawaiSakit');
    });

    Route::controller(LaporanTindakanPasienCtrl::class)->group(function () {
        Route::get('laporan/get-laporan-pengunjung', 'getPelayananTindakan');
        Route::get('laporan/laporan-time-respon', 'getTimeRespon');
        Route::get('laporan/pilihan-search', 'pilihanSearch');
    });

    Route::controller(KartuStokCtrl::class)->group(function () {
        Route::get('logistik/kartu-stok-grid', 'getDataGrid');
        Route::get('logistik/penggunaan-obat-alkes', 'getPenggunaanObatAlkes');
        Route::get('logistik/kartu-stok-cbo', 'getCombo');
        Route::get('logistik/list-produk', 'listProduk');
    });
    Route::controller(DistribusiBarangCtrl::class)->group(function () {
        Route::get('logistik/distribusi-barang-produk', 'getProduk');
        Route::get('logistik/distribusi-barang-cbo', 'getCombo');
        Route::get('logistik/distribusi-edit', 'getDetailKirim');
        Route::get('logistik/distribusi-detail', 'getDetailKirimBarang');
        Route::get('logistik/get-detail-kirim-order', 'getDetailOrderBarangForKirim');
        Route::get('logistik/daftar-distribusi', 'getDaftarDistribusiBarang');
        Route::get('logistik/daftar-retur-distribusi', 'getReturDistribusiBarang');
        Route::post('logistik/chekstok-validasi', 'checkStok');
        Route::post('logistik/distribusi-barang-produk-save', 'saveKirimBarangRuangan');
        Route::post('logistik/retur-distribusi-barang-save', 'SaveReturDistribusi');
        Route::post('logistik/save-kirim-order-barang', 'saveKirimOrderBarang');
        Route::get('logistik/cetak-bukti-kirim', 'cetakBuktiKirim');
        Route::get('logistik/kirim-barang-farmasi', 'getDataKirimBarangFarmasi');
        Route::get('logistik/terima-barang-farmasi', 'getDataTerimaBarangFarmasi');
        Route::get('logistik/rekap-kirim-barang-farmasi', 'getRekapKirimBarangFarmasi');
        Route::get('logistik/rekap-terima-barang-farmasi', 'getRekapTerimaBarangFarmasi');
        Route::get('logistik/daftar-retur-ruangan', 'getDaftarReturBarangRuangan');
        Route::post('logistik/save-retur-order-barang', 'saveReturOrderBarang');
        Route::post('logistik/retur-barang-produk-save', 'saveReturBarangRuangan');
        Route::post('logistik/batal-retur-barang-ruangan', 'BatalReturBarangRuangan');
        Route::get('logistik/cetak-bukti-terima-retur', 'cetakBuktiTerimaRetur');
    });
    Route::controller(PenerimaanBarangCtrl::class)->group(function () {
        Route::get('logistik/penerimaan-barang', 'getDaftarPenerimaanSuplier');
        Route::get('logistik/get-retur-barang-suplier', 'getDaftarReturPenerimaanSuplier');
        Route::get('logistik/get-detail-penerimaan', 'getDetailPenerimaanBarang');
        Route::get('logistik/penerimaan-barang-suplier', 'saveDataPenerimaanBarang');
        Route::get('logistik/penerimaan-barang/get-data-combo', 'getDataCombo');
        Route::get('logistik/penerimaan-barang/get-produkdetail', 'getHargaTerakhir');
        Route::get('logistik/penerimaan-barang/get-no-terima', 'getNoTerimaGenerate');
        Route::get('logistik/penerimaan-barang/get-produk-bykelompok', 'getDataProdukDetail');
        Route::post('logistik/penerimaan-barang/save-penerimaan-suplier', 'savePenerimaanBarangSuplier');
        Route::post('logistik/penerimaan-barang/retur-penerimaan-suplier', 'SaveReturPenerimaan');
        Route::post('logistik/penerimaan-barang/delete-penerimaan-suplier', 'DeletePenerimaanBarangSupplier');
        Route::get('logistik/penerimaan-barang/get-data-produk-logistik', 'getDataProdukLogitik');
        Route::get('logistik/report/cetak-bukti-penerimaan-barang', 'cetakBuktiPenerimaanBarang');
        Route::post('logistik/penerimaan-barang/save-faktur-penerimaan', 'uploadFile');
    });

    Route::controller(PurchaseOrderCtrl::class)->group(function () {
        Route::get('logistik/get-combo-barang-logistik', 'getDataProdukLogistik');
        Route::get('logistik/get-harga-produk', 'getHargaTerakhir');
    });

    Route::controller(InputResepCtrl::class)->group(function () {
        Route::get('farmasi/input-resep-header', 'getHeader');
        Route::get('farmasi/input-resep-cbo', 'getCombo');
        Route::get('farmasi/input-resep-cbo-ruang', 'getComboRuang');
        Route::get('farmasi/input-resep-produk', 'getProduk');
        Route::get('farmasi/get-produkdetail', 'getProdukDetail');
        Route::get('farmasi/input-resep-edit', 'getDetailResep');
        Route::get('farmasi/input-resep-order', 'getDetailOrder');
        Route::get('farmasi/dropdown-obat', 'dropdownObat');
        Route::get('farmasi/check-obat-periode', 'chekPeriodeObat');

        Route::post('farmasi/input-resep-save', 'simpanResep');
        Route::post('farmasi/input-resep-kronis-save', 'simpanResepKronis');
        Route::post('farmasi/input-resep-pulang-save', 'simpanResepPulang');
        Route::post('farmasi/save-retur-pelayanan', 'SimpanReturPelayananObat');
        Route::post('farmasi/save-retur-resep-dibayar', 'SimpanReturResepDibayar');
        Route::get('farmasi/report-kwitansi-pengembalian-obat-dibayar', 'cetakKwitansi');
    });
    Route::controller(PelayananObatBebasCtrl::class)->group(function () {
        Route::get('farmasi/get-daftar-jual-bebas', 'getDaftarPenjualanBebas');
        Route::get('farmasi/get-pasien', 'getPasien');
        Route::get('farmasi/get-detail-pasien', 'getDetailResepBebas');
        Route::post('farmasi/save-input-non-layanan-obat', 'saveInputTagihanObat');
        Route::post('farmasi/save-stock-merger', 'stokMerger');
        Route::post('farmasi/delete-resep-bebas', 'deleteResepOB');
        Route::post('farmasi/save-retur-resep-bebas', 'saveReturTagihanObat');
    });
    Route::controller(DaftarPasienFarmasiCtrl::class)->group(function () {
        Route::get('farmasi/daftar-pasien-farmasi-grid', 'getDataGrid');
        Route::get('farmasi/daftar-pasien-ranap', 'getDataRanap');
        Route::get('farmasi/daftar-pasien-farmasi-cbo', 'getCombo');
        Route::get('farmasi/daftar-ruangan-cbo', 'listRuangan');
        Route::get('farmasi/daftar-resep-pasien', 'getDaftarResep');
    });
    Route::controller(TransaksiPelayananFarmasiCtrl::class)->group(function () {
        Route::get('farmasi/transaksi-pelayanan-farmasi', 'transaksiPelayananFarmasi');
        Route::get('farmasi/transaksi-pelayanan-farmasi-modal', 'transaksiPelayananFarmasiModal');
        Route::get('farmasi/transaksi-pelayanan-farmasi-kronis', 'transaksiPelayananFarmasiKronis');

        Route::post('farmasi/transaksi-pelayanan-farmasi-hapus', 'transaksiPelayananFarmasiHapus');
        Route::post('farmasi/transaksi-pelayanan-farmasi-hapus-kronis', 'transaksiPelayananFarmasiHapusKronis');
    });

    Route::controller(HumasCtrl::class)->group(function () {
        Route::get('humas/data-tempat-tidur', 'getDataViewBed');
        Route::get('humas/data-detail-tempat-tidur', 'getDetailBed');
        Route::get('humas/info-bed', 'infoBed');
        Route::get('humas/data-info-layanan', 'getInfoLayanan');
        Route::get('humas/combo-cari', 'getPilihan');
    });


    Route::controller(MasterRuanganCtrl::class)->group(function () {
        Route::get('sysadmin/master-ruangan', 'masterRuangan');
        Route::get('sysadmin/master-ruangan-dropdown', 'masterRuangandropdown');

        Route::post('sysadmin/save-master-ruangan', 'saveRuangan');
        Route::post('sysadmin/delete-master-ruangan', 'deleteRuangan');
        Route::post('sysadmin/detail-master-ruangan', 'detailRuangan');
        Route::post('sysadmin/update-master-ruangan', 'updateRuangan');
    });

    Route::controller(MasterDepartemenCtrl::class)->group(function () {
        Route::get('sysadmin/master-departemen', 'masterDepartemen');
        Route::get('sysadmin/master-departemen-dropdown', 'masterDepartemendropdown');

        Route::post('sysadmin/save-master-departemen', 'saveDepartemen');
        Route::post('sysadmin/delete-master-departemen', 'deleteDepartemen');
        Route::post('sysadmin/detail-master-departemen', 'detailDepartemen');
    });

    Route::controller(MasterKelompokPasienCtrl::class)->group(function () {
        Route::get('sysadmin/master-kelompok-pasien', 'masterKelompokPasien');
        Route::get('sysadmin/master-kelompok-pasien-dropdown', 'masterKelompokPasiendropdown');

        Route::post('sysadmin/save-master-kelompok-pasien', 'saveKelompokPasien');
        Route::post('sysadmin/delete-master-kelompok-pasien', 'deleteKelompokPasien');
    });

    Route::controller(MasterRekananCtrl::class)->group(function () {
        Route::get('sysadmin/master-rekanan', 'masterRekanan');
        Route::get('sysadmin/master-rekanan-dropdown', 'masterRekanandropdown');
        Route::get('sysadmin/desa-kelurahan-paging', 'listDesaKelurahanPaging');
        Route::get('sysadmin/kecamatan-paging', 'listKecamatanPaging');
        Route::get('sysadmin/kotakabupaten', 'listKotaKab');
        Route::get('sysadmin/kecamatan', 'listKecamatan');
        Route::get('sysadmin/desakelurahan', 'listDesa');

        Route::post('sysadmin/save-master-rekanan', 'saveRekanan');
        Route::post('sysadmin/delete-master-rekanan', 'deleteRekanan');
        Route::post('sysadmin/detail-master-rekanan', 'detailRekanan');
    });

    Route::controller(MasterMapKelompokCtrl::class)->group(function () {
        Route::get('sysadmin/master-map-kelompok', 'masterMapKelompok');
        Route::get('sysadmin/master-map-kelompok-dropdown', 'masterMapKelompokdropdown');

        Route::post('sysadmin/save-map-kelompok', 'saveMapKelompok');
        Route::post('sysadmin/delete-map-kelompok', 'deleteMapKelompok');
        Route::post('sysadmin/detail-map-kelompok', 'detailMapKelompok');
    });

    Route::controller(MasterMapDepoToRuanganCtrl::class)->group(function () {
        Route::get('sysadmin/master-map-depo-to-ruangan', 'masterMapDepoToRuangan');
        Route::get('sysadmin/master-map-depo-to-ruangan-dropdown', 'masterMapDepoToRuangandropdown');

        Route::post('sysadmin/save-map-depo-to-ruangan', 'saveMapDepoToRuangan');
        Route::post('sysadmin/delete-map-depo-to-ruangan', 'deleteMapDepoToRuangan');
    });

    Route::controller(MapKelompokLaporanCtrl::class)->group(function () {
        Route::get('sysadmin/get-combo-map-laporan', 'getCombo');
        Route::get('sysadmin/get-map-laporan-rl', 'getMapLaporanRL');

        Route::post('sysadmin/save-map-laporan-rl', 'SaveMappingRl');
        Route::post('sysadmin/delete-map-laporan-rl', 'deleteMap');
    });

    Route::controller(MasterKelompokLaporanCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-kelompok-laporan')->group(function () {
            Route::get('/', 'index');
            Route::get('/get-jenis-laporan', 'getJenisLaporan');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });


    Route::controller(GeneralCtrl::class)->group(function () {
        Route::get('general/pasien-registrasi', 'pasienRegistrasiSearching');
        Route::get('general/dokter-paging', 'listDokterPaging');
        Route::get('general/header-pasien', 'headerPasien');
        Route::get('general/header-pasien-first', 'headerPasienFirst');
        Route::get('general/show-file', 'showFileGeneral');
        Route::get('general/ppk-bpjs', 'settingPPKBPJS');
        Route::get('general/template-expertise', 'getTemplateExpertice');
        Route::get('general/printer', 'masterPrinter');
        Route::get('general/list-log-user', 'getLogUser');
        Route::get('general/device-name', function () {
            $response = array(
                'metaData' => array(
                    "code" => 200,
                    "message" => 'Sukses',
                ),
                'response' => gethostname(),
            );
            return $response;
        });
        Route::get('/jumlah', 'getTerbilang');


        Route::post('general/api-tools', 'apiTOOLS');
        Route::post('general/save-surat-keterangan-kematian', 'SaveSuratKeteranganKematian');
        Route::post('general/save-surat-keterangan-meninggal', 'SaveSuratKeteranganMeninggal');
        Route::post('general/save-printer', 'savePrinter');
        Route::post('general/delete-printer', 'deletePrinter');
        Route::post('general/store-notif', 'storeNotif');

        Route::post('general/generate-seq', 'generateSeq');
        Route::post('general/save-sequence', 'saveSequence');
    });
    Route::controller(MasterKelompokProdukCtrl::class)->group(function () {
        Route::get('sysadmin/master-kelompok-produk', 'masterKelompokProduk');
        Route::get('sysadmin/master-kelompok-produk-dropdown', 'masterKelompokProdukdropdown');

        Route::post('sysadmin/save-master-kelompok-produk', 'saveKelompokProduk');
        Route::post('sysadmin/delete-kelompok-produk', 'deleteKelompokProduk');
    });
    Route::controller(MasterJenisProdukCtrl::class)->group(function () {
        Route::get('sysadmin/master-jenis-produk', 'masterJenisProduk');
        Route::get('sysadmin/master-jenis-produk-dropdown', 'masterJenisProdukdropdown');

        Route::post('sysadmin/save-master-jenis-produk', 'saveJenisProduk');
        Route::post('sysadmin/delete-jenis-produk', 'deleteJenisProduk');
        Route::post('sysadmin/detail-jenis-produk', 'detailJenisProduk');
    });
    Route::controller(MasterDetailJenisProdukCtrl::class)->group(function () {
        Route::get('sysadmin/master-detail-jenis-produk', 'masterDetailJenisProduk');
        Route::get('sysadmin/master-detail-jenis-produk-dropdown', 'masterDetailJenisProdukdropdown');

        Route::post('sysadmin/save-master-detail-jenis-produk', 'saveDetailJenisProduk');
        Route::post('sysadmin/delete-detail-jenis-produk', 'deleteDetailJenisProduk');
        Route::post('sysadmin/detail-jenis-produk', 'detailJenisProduk');
    });
    Route::controller(MasterProdukCtrl::class)->group(function () {
        Route::get('sysadmin/master-produk', 'masterProduk');
        Route::get('sysadmin/master-produk-tidak-aktif', 'masterProdukTidakAktif');
        Route::get('sysadmin/master-produk-dropdown', 'masterProdukdropdown');
        Route::get('sysadmin/produk-grid', 'getDataGrid');
        Route::get('sysadmin/produk-cbo', 'getCombo');
        Route::get('sysadmin/jenis-produk', 'listJenisProduk');
        Route::get('sysadmin/detail-jenis-produk', 'listDetailJenisProduk');
        Route::get('sysadmin/master-produk-satset', 'masterProdukSatset');

        Route::post('sysadmin/save-master-produk', 'saveProduk');
        Route::post('sysadmin/delete-master-produk', 'deleteProduk');
        Route::post('sysadmin/detail-produk', 'detailProduk');
        Route::post('sysadmin/update-produk', 'updateProduk');
    });
    Route::controller(MasterSatuanStandarCtrl::class)->group(function () {
        Route::get('sysadmin/master-satuan-standar', 'masterSatuanStandar');
        Route::get('sysadmin/master-satuan-standar-dropdown', 'masterSatuanStandardropdown');

        Route::post('sysadmin/save-satuan-standar', 'saveSatuanStandar');
        Route::post('sysadmin/delete-satuan-standar', 'deleteSatuanStandar');
        Route::post('sysadmin/detail-satuan-standar', 'detailSatuanStandar');
    });
    Route::controller(MasterKelompokTransaksiCtrl::class)->group(function () {
        Route::get('sysadmin/master-kelompok-transaksi', 'masterKelompokTransaksi');
        Route::get('sysadmin/master-kelompok-transaksi-dropdown', 'masterKelompokTransaksidropdown');

        Route::post('sysadmin/save-kelompok-transaksi', 'saveKelompokTransaksi');
        Route::post('sysadmin/delete-kelompok-transaksi', 'deleteKelompokTransaksi');
        Route::post('sysadmin/detail-kelompok-transaksi', 'detailKelompokTransaksi');
    });
    Route::controller(MasterAsalProdukCtrl::class)->group(function () {
        Route::get('sysadmin/master-asal-produk', 'masterAsalProduk');
        Route::get('sysadmin/master-asal-produk-dropdown', 'masterAsalProdukdropdown');

        Route::post('sysadmin/save-asal-produk', 'saveAsalProduk');
        Route::post('sysadmin/delete-asal-produk', 'deleteAsalProduk');
        Route::post('sysadmin/detail-asal-produk', 'detailAsalProduk');
    });
    Route::controller(MasterAgamaCtrl::class)->group(function () {
        Route::get('sysadmin/master-agama', 'masterAgama');

        Route::post('sysadmin/save-agama', 'saveAgama');
        Route::post('sysadmin/delete-agama', 'deleteAgama');
        Route::post('sysadmin/detail-agama', 'detailAgama');
    });
    Route::controller(MasterJenisKelaminCtrl::class)->group(function () {
        Route::get('sysadmin/master-jenis-kelamin', 'masterJenisKelamin');
        Route::post('sysadmin/save-jenis-kelamin', 'saveJenisKelamin');
        Route::post('sysadmin/delete-jenis-kelamin', 'deleteJenisKelamin');
        Route::post('sysadmin/detail-jenis-kelamin', 'detailJenisKelamin');
    });
    Route::controller(MasterKSMCtrl::class)->group(function () {
        Route::get('sysadmin/master-ksm', 'masterKSM');
        Route::post('sysadmin/save-ksm', 'saveKSM');
        Route::post('sysadmin/delete-ksm', 'deleteKSM');
        Route::post('sysadmin/detail-ksm', 'detailKSM');
    });
    Route::controller(MapKsmToRuanganCtrl::class)->group(function () {
        Route::get('sysadmin/get-combo-map-ksm', 'getComboMapKsm');
        Route::get('sysadmin/get-map-ksm-to-ruangan', 'getMapKsmToRuangan');

        Route::post('sysadmin/save-map-ksm-to-ruangan', 'saveMapKsmToRuangan');
        Route::post('sysadmin/delete-map-ksm-to-ruangan', 'deleteMapKsmToRuangan');
    });
    Route::controller(MasterStatusPerkawinanCtrl::class)->group(function () {
        Route::get('sysadmin/master-status-perkawinan', 'masterStatusPerkawinan');

        Route::post('sysadmin/save-status-perkawinan', 'saveStatusPerkawinan');
        Route::post('sysadmin/delete-status-perkawinan', 'deleteStatusPerkawinan');
    });
    Route::controller(MasterPendidikanCtrl::class)->group(function () {
        Route::get('sysadmin/master-pendidikan', 'masterPendidikan');
        Route::get('sysadmin/master-pendidikan-dropdown', 'masterPendidikandropdown');

        Route::post('sysadmin/save-pendidikan', 'savePendidikan');
        Route::post('sysadmin/delete-pendidikan', 'deletePendidikan');
    });
    Route::controller(MasterPekerjaanCtrl::class)->group(function () {
        Route::get('sysadmin/master-pekerjaan', 'masterPekerjaan');
        Route::get('sysadmin/master-pekerjaan-dropdown', 'masterPekerjaandropdown');

        Route::post('sysadmin/save-pekerjaan', 'savePekerjaan');
        Route::post('sysadmin/delete-pekerjaan', 'deletePekerjaan');
    });
    Route::controller(MasterGolonganDarahCtrl::class)->group(function () {
        Route::get('sysadmin/master-golongan-darah', 'masterGolonganDarah');

        Route::post('sysadmin/save-golongan-darah', 'saveGolonganDarah');
        Route::post('sysadmin/delete-golongan-darah', 'deleteGolonganDarah');
    });
    Route::controller(MasterSatuanResepCtrl::class)->group(function () {
        Route::get('sysadmin/master-satuan-resep', 'masterSatuanResep');

        Route::post('sysadmin/save-satuan-resep', 'saveSatuanResep');
        Route::post('sysadmin/delete-satuan-resep', 'deleteSatuanResep');
    });
    Route::controller(MasterNegaraCtrl::class)->group(function () {
        Route::get('sysadmin/master-negara', 'masterNegara');

        Route::post('sysadmin/save-negara', 'saveNegara');
        Route::post('sysadmin/delete-negara', 'deleteNegara');
    });
    Route::controller(MasterSignaCtrl::class)->group(function () {
        Route::get('sysadmin/master-signa', 'masterSigna');

        Route::post('sysadmin/save-signa', 'saveSigna');
        Route::post('sysadmin/delete-signa', 'deleteSigna');
    });
    Route::controller(MasterRouteFarmasiCtrl::class)->group(function () {
        Route::get('sysadmin/master-route-farmasi', 'masterRouteFarmasi');

        Route::post('sysadmin/save-route-farmasi', 'saveRouteFarmasi');
        Route::post('sysadmin/delete-route-farmasi', 'deleteRouteFarmasi');
    });
    Route::controller(MasterSukuCtrl::class)->group(function () {
        Route::get('sysadmin/master-suku', 'masterSuku');

        Route::post('sysadmin/save-suku', 'saveSuku');
        Route::post('sysadmin/delete-suku', 'deleteSuku');
    });
    Route::controller(MasterProvinsiCtrl::class)->group(function () {
        Route::get('sysadmin/master-provinsi', 'masterProvinsi');

        Route::post('sysadmin/save-provinsi', 'saveProvinsi');
        Route::post('sysadmin/delete-provinsi', 'deleteProvinsi');
    });
    Route::controller(MasterKotaKabupatenCtrl::class)->group(function () {
        Route::get('sysadmin/master-kota-kabupaten', 'masterKotaKabupaten');
        Route::get('sysadmin/master-kota-kabupaten-dropdown', 'masterKotaKabupatendropdown');

        Route::post('sysadmin/save-kota-kabupaten', 'saveKotaKabupaten');
        Route::post('sysadmin/delete-kota-kabupaten', 'deleteKotaKabupaten');
    });
    Route::controller(MasterKecamatanCtrl::class)->group(function () {
        Route::get('sysadmin/master-kecamatan', 'masterKecamatan');
        Route::get('sysadmin/master-kecamatan-dropdown', 'masterKecamatandropdown');

        Route::post('sysadmin/save-kecamatan', 'saveKecamatan');
        Route::post('sysadmin/delete-kecamatan', 'deleteKecamatan');
    });
    Route::controller(MasterHargaNettoProdukByKelasCtrl::class)->group(function () {
        Route::get('sysadmin/master-harga-netto-produk-by-kelas', 'masterHargaNettoProdukByKelas');
        Route::get('sysadmin/master-harga-netto-produk-by-kelas/{id}', 'masterHargaNettoProdukByKelasEdit');
        Route::get('sysadmin/master-harga-netto-produk-by-kelas-dropdown', 'masterHargaNettoProdukByKelasdropdown');
        Route::get('sysadmin/master-harga-netto-produk-by-kelas-dropdown-import', 'masterHargaNettoProdukByKelasdropdownImport');
        Route::get('sysadmin/master-harga-netto-produk-by-kelas-download', 'downloadTemplate');

        Route::post('sysadmin/save-harga-netto-produk-by-kelas', 'saveHargaNettoProdukByKelas');
        Route::post('sysadmin/add-harga-netto-produk-by-kelas', 'saveKomponenHarga');
        Route::post('sysadmin/delete-harga-netto-produk-by-kelas', 'deleteHargaNettoProdukByKelas');
        Route::post('sysadmin/import-harga-netto-produk-by-kelas', 'importTarif');
    });
    Route::controller(MasterMapRuanganToProdukCtrl::class)->group(function () {
        Route::get('sysadmin/master-map-ruangan-to-produk', 'masterMapRuanganToProduk');
        Route::get('sysadmin/master-map-ruangan-to-produk-dropdown', 'masterMapRuanganToProdukdropdown');
        Route::get('sysadmin/master-map-ruangan-to-produk-dropdown-produk', 'masterMapRuanganToProdukdropdownProduk');
        Route::get('sysadmin/ruangan', 'listRuangan');
        Route::get('sysadmin/kelompok-produk', 'listKelompokProduk');
        Route::get('sysadmin/jenis-produk-', 'listJenisProduk');
        Route::get('sysadmin/detail-jenis-produk-', 'listDetailJenisProduk');
        Route::get('sysadmin/list-produk', 'listProduk');

        Route::post('sysadmin/save-map-ruangan-to-produk', 'saveMapRuanganToProduk');
        Route::post('sysadmin/delete-map-ruangan-to-produk', 'deleteMapRuanganToProduk');
        Route::post('sysadmin/detail-map-ruangan-to-produk', 'detailMapRuanganToProduk');
    });
    Route::controller(MasterPaketCtrl::class)->group(function () {
        Route::get('sysadmin/master-paket', 'masterPaket');
        Route::get('sysadmin/master-paket-dropdown', 'masterPaketdropdown');

        Route::post('sysadmin/save-master-paket', 'savePaket');
        Route::post('sysadmin/delete-master-paket', 'deletePaket');
    });
    Route::controller(MasterKelasCtrl::class)->group(function () {
        Route::get('sysadmin/master-kelas', 'masterKelas');

        Route::post('sysadmin/save-master-kelas', 'saveKelas');
        Route::post('sysadmin/delete-master-kelas', 'deleteKelas');
    });
    Route::controller(MasterMapRuanganToKelasCtrl::class)->group(function () {
        Route::get('sysadmin/master-map-ruangan-to-kelas', 'masterMapRuanganToKelas');
        Route::get('sysadmin/master-map-ruangan-to-kelas-dropdown', 'masterMapRuanganToKelasdropdown');
        Route::get('sysadmin/ruangan', 'listRuangan');

        Route::post('sysadmin/save-map-ruangan-to-kelas', 'saveMapRuanganToKelas');
        Route::post('sysadmin/delete-map-ruangan-to-kelas', 'deleteMapRuanganToKelas');
        Route::post('sysadmin/detail-map-ruangan-to-kelas', 'detailMapRuanganToKelas');
    });
    Route::controller(MasterMapPaketToProdukCtrl::class)->group(function () {
        Route::get('sysadmin/master-map-paket-to-produk', 'masterMapPaketToProduk');
        Route::get('sysadmin/master-map-paket-to-produk-dropdown', 'masterMapPaketToProdukdropdown');

        Route::post('sysadmin/save-map-paket-to-produk', 'saveMapPaketToProduk');
        Route::post('sysadmin/delete-map-paket-to-produk', 'deleteMapPaketToProduk');
        Route::post('sysadmin/detail-map-paket-to-produk', 'detailMapPaketToProduk');
    });
    Route::controller(MasterJabatanCtrl::class)->group(function () {
        Route::get('sysadmin/master-jabatan', 'masterJabatan');
        Route::get('sysadmin/master-jabatan-dropdown', 'masterJabatandropdown');

        Route::post('sysadmin/save-master-jabatan', 'saveJabatan');
        Route::post('sysadmin/delete-master-jabatan', 'deleteJabatan');
    });
    Route::controller(MasterKelompokJabatanCtrl::class)->group(function () {
        Route::get('sysadmin/master-kelompok-jabatan', 'masterKelompokJabatan');
        Route::get('sysadmin/master-kelompok-jabatan-dropdown', 'masterKelompokJabatandropdown');

        Route::post('sysadmin/save-master-kelompok-jabatan', 'saveKelompokJabatan');
        Route::post('sysadmin/delete-master-kelompok-jabatan', 'deleteKelompokJabatan');
    });
    Route::controller(MasterJenisJabatanCtrl::class)->group(function () {
        Route::get('sysadmin/master-jenis-jabatan', 'masterJenisJabatan');
        Route::get('sysadmin/master-jenis-jabatan-dropdown', 'masterJenisJabatandropdown');

        Route::post('sysadmin/save-master-jenis-jabatan', 'saveJenisJabatan');
        Route::post('sysadmin/delete-master-jenis-jabatan', 'deleteJenisJabatan');
    });
    Route::controller(MasterAsalRujukanCtrl::class)->group(function () {
        Route::get('sysadmin/master-asal-rujukan', 'masterAsalRujukan');

        Route::post('sysadmin/save-asal-rujukan', 'saveAsalRujukan');
        Route::post('sysadmin/delete-asal-rujukan', 'deleteAsalRujukan');
    });
    Route::controller(MasterSlottingOnlineCtrl::class)->group(function () {
        Route::get('sysadmin/master-slotting-online', 'masterSlottingOnline');
        Route::get('sysadmin/master-slotting-online-dropdown', 'masterSlottingOnlinedropdown');

        Route::post('sysadmin/save-slotting-online', 'saveSlottingOnline');
        Route::post('sysadmin/delete-slotting-online', 'deleteSlottingOnline');
    });
    Route::controller(MasterKelompokUserCtrl::class)->group(function () {
        Route::get('sysadmin/master-kelompok-user', 'masterKelompokUser');

        Route::post('sysadmin/save-master-kelompok-user', 'saveKelompokUser');
        Route::post('sysadmin/delete-master-kelompok-user', 'deleteKelompokUser');
    });
    Route::controller(MasterJenisPetugasPelaksanaCtrl::class)->group(function () {
        Route::get('sysadmin/master-jenis-petugas-pelaksana', 'masterJenisPetugasPelaksana');

        Route::post('sysadmin/save-jenis-petugas-pelaksana', 'saveJenisPetugasPelaksana');
        Route::post('sysadmin/delete-jenis-petugas-pelaksana', 'deleteJenisPetugasPelaksana');
    });
    Route::controller(MasterPegawaiCtrl::class)->group(function () {
        Route::get('sysadmin/master-pegawai', 'masterPegawai');
        Route::get('sysadmin/master-pegawai-dropdown', 'masterPegawaidropdown');
        Route::get('sysadmin/pegawai', 'pegawaiByID');
        Route::get('sysadmin/pegawai-by-id', 'pegawaiByID');
        Route::get('sysadmin/jadwal-kerja', 'jadwalKerja');
        Route::get('sysadmin/master-pegawai-satset', 'masterPegawaiSatset');

        Route::post('sysadmin/save-pegawai', 'savePegawai');
        Route::post('sysadmin/save-pegawai-foto', 'savePegawaiFoto');
        Route::post('sysadmin/delete-pegawai', 'deletePegawai');
        Route::post('sysadmin/update-pegawai', 'updatePegawai');
    });
    Route::controller(MasterJenisPegawaiCtrl::class)->group(function () {
        Route::get('sysadmin/master-jenis-pegawai', 'masterJenisPegawai');
        Route::get('sysadmin/master-jenis-pegawai-dropdown', 'masterJenisPegawaidropdown');

        Route::post('sysadmin/save-jenis-pegawai', 'saveJenisPegawai');
        Route::post('sysadmin/delete-jenis-pegawai', 'deleteJenisPegawai');
    });
    Route::controller(MasterMapJenisPetugasToJenisPegawaiCtrl::class)->group(function () {
        Route::get('sysadmin/master-map-jenis-petugas-to-jenis-pegawai', 'masterMapJenisPetugasToJenisPegawai');
        Route::get('sysadmin/master-map-jenis-petugas-to-jenis-pegawai-dropdown', 'masterMapJenisPetugasToJenisPegawaiDropdown');

        Route::post('sysadmin/save-map-jenis-petugas-to-jenis-pegawai', 'saveMapJenisPetugasToJenisPegawai');
        Route::post('sysadmin/delete-map-jenis-petugas-to-jenis-pegawai', 'deleteMapJenisPetugasToJenisPegawai');
    });
    Route::controller(MasterDetailKategoryPegawaiCtrl::class)->group(function () {
        Route::get('sysadmin/master-detail-kategory-pegawai', 'masterDetailKategoriPegawai');
        Route::get('sysadmin/master-detail-kategory-pegawai-dropdown', 'masterDetailKategoryPegawaidropdown');

        Route::post('sysadmin/save-detail-kategory-pegawai', 'saveDetailKategoryPegawai');
        Route::post('sysadmin/delete-detail-kategory-pegawai', 'deleteDetailKategoryPegawai');
    });
    Route::controller(MasterJadwalDokterCtrl::class)->group(function () {
        Route::get('sysadmin/master-jadwal-dokter', 'masterJadwalDokter');
        Route::get('sysadmin/master-jadwal-dokter-dropdown', 'masterJadwalDokterDropdown');

        Route::post('sysadmin/save-jadwal-dokter', 'saveJadwalDokter');
        Route::post('sysadmin/delete-jadwal-dokter', 'deleteJadwalDokter');
    });
    Route::controller(MasterTambahLoginUserCtrl::class)->group(function () {
        Route::get('sysadmin/master-tambah-login-user', 'masterTambahLoginUser');
        Route::get('sysadmin/master-tambah-login-user-dropdown', 'masterTambahLoginUserDropdown');

        Route::post('sysadmin/save-login-user', 'saveLoginUser');
        Route::post('sysadmin/delete-login-user', 'deleteLoginUser');
    });
    Route::controller(MasterModulAplikasiCtrl::class)->group(function () {
        Route::get('sysadmin/master-modul-aplikasi', 'masterModulAplikasi');
        Route::get('sysadmin/master-objek-modul-aplikasi', 'masterObjekModulAplikasi');
        Route::get('sysadmin/master-modul-aplikasi-by-head', 'masterModulAplikasiHead');
        Route::get('sysadmin/master-menu-modul-aplikasi', 'masterMenuObjek');
        Route::get('sysadmin/master-modul-aplikasi-nourut', 'lastNourutObjekModul');


        Route::post('sysadmin/save-modul-aplikasi', 'saveModulAplikasi');
        Route::post('sysadmin/delete-modul-aplikasi', 'deleteModulAplikasi');
        Route::post('sysadmin/save-objek-modul-aplikasi', 'saveObjekModulAplikasi');
        Route::post('sysadmin/delete-objek-modul-aplikasi', 'deleteObjekModulAplikasi');
        Route::post('sysadmin/save-objek-modul-aplikasi-map', 'saveObjekModulAplikasiMap');
        Route::post('sysadmin/hapus-objek-modul-aplikasi-map', 'hapusObjekModulAplikasiMap');
    });
    Route::controller(MasterMapLoginUserToModulAplikasiCtrl::class)->group(function () {
        Route::get('sysadmin/master-map-loginuser-to-modulaplikasi', 'masterMapLoginUserToModulAplikasi');
        Route::get('sysadmin/master-map-loginuser-to-modulaplikasi-dropdown', 'masterMapLoginUserToModulAplikasidropdown');

        Route::post('sysadmin/save-map-loginuser-to-modulaplikasi', 'saveMapLoginUserToModulAplikasi');
        Route::post('sysadmin/delete-map-loginuser-to-modulaplikasi', 'deleteMapLoginUserToModulAplikasi');
    });
    Route::controller(MasterMapLoginUserToRuanganCtrl::class)->group(function () {
        Route::get('sysadmin/master-map-loginuser-to-ruangan', 'masterMapLoginUserToRuangan');
        Route::get('sysadmin/master-map-loginuser-to-ruangan-dropdown', 'masterMapLoginUserToRuangandropdown');

        Route::post('sysadmin/save-map-loginuser-to-ruangan', 'saveMapLoginUserToRuangan');
        Route::post('sysadmin/delete-map-loginuser-to-ruangan', 'deleteMapLoginUserToRuangan');
    });
    Route::controller(SettingDataFixedCtrl::class)->group(function () {
        Route::get('sysadmin/get-settingdatafixed', 'getDataFixed');
        Route::get('sysadmin/get-settingdatafixedbyid', 'getSettingById');
        Route::get('sysadmin/update-status-enabled', 'updateStatuEnabled');
        Route::get('sysadmin/get-kelompok-setting', 'getKelompokSettingDataFix');
        Route::get('sysadmin/get-setting-detail', 'getSettingDetail');
        Route::get('sysadmin/get-setting-combo', 'getComboPart');
        Route::get('sysadmin/get-table', 'getTable');
        Route::get('sysadmin/get-field-table', 'getFieldTable');
        Route::get('sysadmin/get-data-from-table', 'getDataFromTable');
        Route::get('sysadmin/get-report-display', 'getReportDisplayTable');
        Route::get('sysadmin/get/{namaField}', 'getSettingDataFixedGeneric');

        Route::post('sysadmin/post-settingdatafixe', 'SaveSettingDataFixed');
        Route::post('sysadmin/hapus-settingdatafixe', 'HapusSettingDataFixed');
        Route::post('sysadmin/tambah-settingdatafixe', 'TambahSettingDataFixed');
        Route::post('sysadmin/delete-settingdatafixed', 'deleteSettingDataFix');
        Route::post('sysadmin/update-setting', 'updateSettingDataFix');
    });

    Route::controller(MasterJenisDietCtrl::class)->group(function () {
        Route::get('sysadmin/master-jenis-diet', 'masterJenisDiet');
        Route::get('sysadmin/dropdown-jenis-diet', 'KelompokDrop');

        Route::post('sysadmin/save-jenis-diet', 'saveJenisDiet');
        Route::post('sysadmin/delete-jenis-diet', 'deleteJenisDiet');
    });

    Route::controller(MasterKategoryDietCtrl::class)->group(function () {
        Route::get('sysadmin/master-kategory-diet', 'masterKategoryDiet');
        Route::get('sysadmin/dropdown-kp', 'ListKP');

        Route::post('sysadmin/save-kategory-diet', 'saveKategoryDiet');
        Route::post('sysadmin/delete-kategory-diet', 'deleteKategoryDiet');
    });

    Route::controller(MasterSatuanGiziCtrl::class)->group(function () {
        Route::get('sysadmin/master-satuan-gizi', 'masterSatuanGizi');

        Route::post('sysadmin/save-satuan-gizi', 'saveSatuanGizi');
        Route::post('sysadmin/delete-satuan-gizi', 'deleteSatuanGizi');
    });

    Route::controller(MasterVendorGiziCtrl::class)->group(function () {
        Route::get('sysadmin/master-vendor-gizi', 'masterVendorGizi');

        Route::post('sysadmin/save-vendor-gizi', 'saveVendorGizi');
        Route::post('sysadmin/delete-vendor-gizi', 'deleteVendorGizi');
    });

    Route::controller(MasterMenuGiziCtrl::class)->group(function () {
        Route::get('gizi/get-daftar-menu-gizi', 'getMenuGizi');
        Route::get('gizi/get-daftar-jadwal-menu-gizi', 'getDataJadwalMenuGizi');
        Route::get('gizi/get-menu-berdasarkan-jadwal', 'getMenuBerdasarkanJadwal');
        Route::get('gizi/get-menu-gizi-berdasarkan-kelas', 'getMenuGiziByKelas');

        Route::post('gizi/save-menu-gizi', 'saveMenuGizi');
        Route::post('gizi/delete-menu-gizi', 'deleteMenuGizi');
        Route::post('gizi/save-map-jadwal-menu-gizi', 'saveMapJadwalMenuGizi');
        Route::post('gizi/delete-map-jadwal-menu-gizi', 'deleteMapJadwalMenuGizi');
    });

    Route::controller(MasterJenisWaktuCtrl::class)->group(function () {
        Route::get('sysadmin/master-jenis-waktu', 'masterJenisWaktu');
        Route::get('sysadmin/dropdown-departemen', 'DropdownKP');

        Route::post('sysadmin/save-jenis-waktu', 'saveJenisWaktu');
        Route::post('sysadmin/delete-jenis-waktu', 'deleteJenisWaktu');
    });

    Route::controller(StokBarangCtrl::class)->group(function () {
        Route::prefix('logistik')->group(function () {
            Route::get('/stok-barang', 'getStokProduck');
            Route::get('/select-item', 'itemDropdown');
            Route::get('/daftar-stok-opname', 'getDaftarStokOpname');
            Route::get('/stok-opname', 'getStokRuanganSO');
            Route::post('/save-stok-opname', 'saveStockOpname');
        });
    });

    Route::controller(MasterStatusPulangCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-status-pulang')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterStatusPegawaiCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-status-pegawai')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterProdusenProdukCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-produsen-produk')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
            Route::get('/select-item', 'dropdownItem');
        });
    });

    Route::controller(MasterBahanProdukCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-bahan-produk')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
            Route::get('/select-item', 'dropdownItem');
        });
    });

    Route::controller(MasterBentukProdukCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-bentuk-produk')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
            Route::get('/select-item', 'dropdownItem');
        });
    });

    Route::controller(MasterKamarCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-kamar')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
            Route::get('/select-item', 'dropdownItem');
        });
    });

    Route::controller(MasterStatusKeluarCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-status-keluar')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
            Route::get('/select-jenis-kondisi', 'jenisKondisiPasien');
        });
    });
    Route::controller(MasterJenisKondisiPasienCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-jenis-kondisi-pasien')->group(function () {
            Route::get('/', 'index');
            Route::get('/cek', 'test');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterKondisiPasienCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-kondisi-pasien')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::get('/detail/{id}', 'detail');
            Route::post('/delete', 'delete');
            Route::get('/jenis-kondisi-pasien', 'jenisKondisiPasien');
        });
    });

    Route::controller(MasterAlergiCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-alergi')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterTipePegawaiCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-tipe-pegawai')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterJenisUsulanCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-jenis-usulan')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterDiagnosaCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-diagnosa')->group(function () {
            Route::get('/', 'index');
            Route::get('/select-item', 'dropdownItem');
            Route::get('/export-diagnosa', 'exportDiagnosa');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
            Route::get('/coba', 'coba');
        });
    });

    Route::controller(MasterKategoriDiagnosaCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-kategori-diagnosa')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterDiagnosaTindakanCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-diagnosa-tindakan')->group(function () {
            Route::get('/', 'index');
            Route::get('/select-item', 'kategoriDiagnosa');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterKelompokShiftCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-kelompok-shift')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterShiftKerjaCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-shift-kerja')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::get('/select-item', 'dropdownItem');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterJadwalPraktekCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-jadwal-praktek')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterKedudukanPegawaiCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-kedudukan-pegawai')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterHubunganKeluargaCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-hubungan-keluarga')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterTempatTidurCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-tempat-tidur')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::get('/select-item', 'dropdownItem');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterStatusBedCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-status-bed')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterSatuanBesarCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-satuan-besar')->group(function () {
            Route::get('/', 'index');
            Route::get('/select-item', 'dropdownItem');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterSatuanKecilCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-satuan-kecil')->group(function () {
            Route::get('/', 'index');
            Route::get('/select-item', 'dropdownItem');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterMerkProdukCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-merk-produk')->group(function () {
            Route::get('/', 'index');
            Route::get('/select-item', 'dropdownItem');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterKomponenHargaCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-komponen-harga')->group(function () {
            Route::get('/', 'index');
            Route::get('/select-item', 'dropdownItem');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterJenisKomponenHargaCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-jenis-komponen-harga')->group(function () {
            Route::get('/', 'index');
            Route::get('/select-item', 'dropdownItem');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterJenisTarifCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-jenis-tarif')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterAsalAnggaranCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-asal-anggaran')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterAsalSukuCadangCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-asal-suku-cadang')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterJenisAlamatCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-jenis-alamat')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterGenerikCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-generik')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::get('/select-item', 'dropdown');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterJenisGenerikCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-jenis-generik')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterJenisPerawatanCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-jenis-perawatan')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterJenisRacikanCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-jenis-racikan')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterStatusApotikCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-status-apotik')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterSediaanCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-sediaan')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterRhesusCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-rhesus')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterStatusApotikCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-status-apotik')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterAsuransiPasienCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-asuransi-pasien')->group(function () {
            Route::get('/', 'index');
            Route::get('/select-item', 'dropdownItem');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterKonversiSatuanCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-konversi-satuan')->group(function () {
            Route::get('/', 'index');
            Route::get('/get-produk', 'getProduk');
            Route::get('/list-satuan', 'listSatuanStandar');
            Route::post('/save', 'store');
            Route::get('/data-konversi-satuan-by-produk', 'getDataKonversiSatuan');
            Route::post('/delete', 'delete');
        });
    });

    Route::controller(MasterTandaTanganCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-tanda-tangan')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'save');
            Route::post('/delete', 'delete');
        });
    });
    Route::controller(MasterEMRCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-emr')->group(function () {
            Route::get('/', 'index');
            Route::get('/nourut', 'nourut');
            Route::get('/get-map', 'getMap');
            Route::post('/save', 'save');
            Route::post('/delete', 'delete');
            Route::post('/save-map', 'saveMap');
        });
    });
    Route::controller(DashboardApotikCtrl::class)->group(function () {
        Route::prefix('dashboard/apotik')->group(function () {
            Route::get('/', 'getDaftarOrder');
            Route::get('/detail-order', 'getDetailOrder');
            Route::get('/count-by-date', 'countOrderByStatus');
            Route::get('/count-by-room', 'countAllbyRoom');
            Route::get('/data-combo', 'dataComboApotik');
            Route::get('/list-stok-obat', 'getStokObat');
            Route::get('/cetak-resep-obat', 'cetakResepObat');
            Route::get('/laporan-instalasi-farmasi', 'getDataInstalasi');
            Route::post('/batal-verifikasi', 'batalVerifikasi');
            Route::post('/save-status-resepelektonik', 'saveStatusResepElektronik');
        });
    });

    Route::controller(DashboardRadiologiCtrl::class)->group(function () {
        Route::prefix('dashboard/radiologi')->group(function () {
            Route::get('/', 'getStrukOrderRad');
            Route::get('/detail-order', 'getDetailOrder');
            Route::get('/get-order-verify', 'getDetailOrderVerify');
            Route::get('/get-order-layanan-rad', 'getOrderPelayananRad');
            Route::get('/get-pelayanan', 'getPelayanan');
            Route::get('/get-dokter', 'getDataDokter');
            Route::get('/get-komponen-harga', 'getKomponenHarga');
            Route::get('/chart-layanan-ruangan', 'chartOrderByRuangan');
            Route::get('/get-detail-rad', 'getRadDetail');
            Route::get('/get-penunjang-rad', 'getDaftarPasienPenunjang');
            Route::get('/get-header-rad', 'HeaderPasienRad');

            Route::post('/save-order-pelayanan', 'savePelayananPasien');
            Route::post('/save-order-pelayanan-terjadwal', 'savePelayananPasienTerjadwal');
            Route::post('/save-order-pelayanan-tunda', 'savePelayananPasienTunda');
            Route::post('/update-jk', 'UpdateJK');
            Route::post('/update-goldar', 'UpdateGoldar');
        });
    });
    Route::controller(DashboardCathlabCtrl::class)->group(function () {
        Route::prefix('dashboard/cathlab')->group(function () {
            Route::get('/', 'getStrukOrderCathlab');
            Route::get('/detail-order', 'getDetailOrder');
            Route::get('/get-order-verify', 'getDetailOrderVerify');
            Route::get('/get-order-layanan', 'getOrderPelayanan');
            Route::get('/get-pelayanan', 'getPelayanan');
            Route::get('/get-dokter', 'getDataDokter');
            Route::get('/get-komponen-harga', 'getKomponenHarga');
            Route::get('/chart-layanan-ruangan', 'chartOrderByRuangan');
            Route::get('/get-detail-rad', 'getRadDetail');
            Route::get('/get-penunjang-rad', 'getDaftarPasienPenunjang');
            Route::get('/get-header-rad', 'HeaderPasienRad');

            Route::post('/save-order-pelayanan', 'savePelayananPasien');
            Route::post('/update-jk', 'UpdateJK');
            Route::post('/update-goldar', 'UpdateGoldar');
        });
    });

    Route::controller(DashboardTindakanCtrl::class)->group(function () {
        Route::prefix('dashboard/tindakan')->group(function () {
            Route::get('/', 'produkTindakan');
        });
    });

    Route::controller(DashboardBedahCtrl::class)->group(function () {
        Route::get('dashboard/get-order-bedah', 'getOrderBedah');
        Route::get('dashboard/list-bedah', 'ListBedah');
        Route::get('dashboard/bedah-detail', 'getBedahDetail');
        Route::get('dashboard/get-detail-order', 'getOrderPelayananBedah');
        Route::get('dashboard/get-pelayanan-bedah', 'getPelayanaBedah');
        Route::get('dashboard/get-komponen-bedah', 'getKomponenHargaBedah');
        Route::get('dashboard/get-verifikator-dokter', 'ListVerif');
        Route::get('dashboard/jadwal-operasi', 'getJadwalOperasi');
        Route::get('dashboard/get-bedah-verify', 'getBedahVerif');
        Route::get('dashboard/get-petugas-verify', 'getpetugasVerif');
        Route::get('dashboard/laporan-tindakan-operasi', 'LapTindakanOperasi');
        Route::get('dashboard/get-monitoring-operasi', 'getJadwalOperasiNew');
        Route::get('dashboard/get-jadwal-operasi-bedah', 'getJadwalOperasi2');
        Route::get('dashboard/master-pelaksana-operasi', 'getMasterPelaksana');
        Route::get('dashboard/master-pelaksana-operasi-dropdown', 'getMasterPelaksanaDropdown');

        Route::post('dashboard/save-order-pelayanan-bedah', 'savePelayananPasienBedah');
        Route::post('dashboard/save-jadwal-operasi-bedah', 'saveJadwalBedah');
        Route::post('dashboard/save-master-pelaksana-operasi', 'saveMasterPelaksanaOperasi');
        Route::post('dashboard/delete-master-pelaksana', 'saveMasterPelaksanaOperasi');
    });

    Route::controller(TransferBarangCtrl::class)->group(function () {
        Route::prefix('/logistik')->group(function () {
            Route::get('/order-barang', 'getComboLogistik');
            Route::post('/verif-order-barang', 'saveKirimBarangRuangan');
        });
    });

    Route::controller(DashboardLogistikCtrl::class)->group(function () {
        Route::prefix('dashboard/logistik')->group(function () {
            Route::get('/get-daftar-order', 'getDaftarOrderBarang');
            Route::get('/list-ruangan', 'getListRuangan');
            Route::get('/chart-request-ruangan', 'chartCountRuanganByDate');
            Route::get('/get-data-distribusi', 'getDaftarDistribusiBarang');
            Route::get('/get-stok-produk', 'stokProdukByRuangan');
            Route::get('/daftar-penerimaan-barang', 'getDaftarPenerimaanBarang');
            Route::get('/chart-medis-non-medis', 'chartMedisNonMedis');
            Route::get('/get-informasi-stok', 'getInformasiStok');
            Route::post('/batal-kirim-barang', 'BatalKirimTerima');
            Route::get('/get-barang-kadaluarsa', 'getBarangKadaluarsa');
            Route::get('/get-combo-produk', 'getDataComboKadaluarsa');
            Route::post('/save-barang-kadaluarsa', 'saveBarangKadaluarsa');
        });
    });



    Route::controller(MasterListCtrl::class)->group(function () {
        Route::get('sysadmin/master-list', 'masterList');
        Route::post('sysadmin/save-master-list', 'saveList');
        Route::post('sysadmin/delete-master-list', 'deleteList');
    });
    Route::controller(BillingCtrl::class)->group(function () {
        Route::prefix('kasir/billing')->group(function () {
            Route::get('/', 'billingPasien');
            Route::get('/tagihan-konversi', 'detailKonversiHarga');
            Route::get('/tagihan-konversi-dropdown', 'detailKonversiHargaDropdown');
            Route::get('/petugas-tindakan', 'detailPetugasTindakan');
            Route::get('/detail-tindakan', 'detailKomponenTindakan');
            Route::get('/report/rincian-biaya', 'cetakBilling')->name('rincian-biaya');
            Route::get('/report/rincian-biaya-terverif', 'cetakBillingTerverif')->name('rincian-biaya-terverif');
            Route::get('/report/bukti-layanan-jasa', 'cetakBuktiLayananJasa');
            Route::get('/report/bukti-layanan-pertindakan', 'cetakBuktiLayananPerTindakan');
            Route::get('/report/bukti-layanan-ruangan', 'cetakBuktiLayananRuangan');
            Route::post('/hapus-tindakan', 'hapusTindakan');
            Route::post('/save-jenis-petugas', 'saveJenisPetugasTindakan');
            Route::post('/delete-jenis-petugas', 'deleteJenisPetugasTindakan');
            Route::post('/update-tgl-tindakan', 'updateTglTindakan');
            Route::post('/update-diskon-tindakan', 'updateDiskon');
            Route::post('/update-harga-konversi', 'konversiharga');
            Route::post('/simpan-harga-konversi', 'simpankonversiharga');
        });
    });

    Route::controller(PiutangPasienCtrl::class)->group(function () {
        Route::get('kasir/daftar-piutang-pasien', 'daftarPiutangPasien');
        Route::get('kasir/autofill-pasien', 'autofillPasien');
        Route::get('kasir/autofill-pasien-sitb', 'autofillPasienSitb');
        Route::get('kasir/cetak-surat-tagihan-kontraktor', 'cetakSuratTagihanKontraktor');
        Route::get('kasir/cetak-surat-tagihan-perorangan', 'cetakSuratTagihanPerorangan');
        Route::get('kasir/daftar-bayar-piutang', 'daftarPiutang');
        Route::post('kasir/verify-piutang-pasien', 'verifyPiutangPasien');
        Route::post('kasir/cancel-verif-piutang', 'cancelVerifyPiutangPasien');
        Route::get('kasir/list-item-pendukung', 'loadListData');
        Route::post('kasir/update-rekanan', 'editRekanan');
        Route::get('kasir/detail-piutang-pasien', 'detailPiutangPasien');
    });

    Route::controller(PiutangCtrl::class)->prefix('piutang')->group(function () {
        Route::get('daftar-piutang-layanan', 'daftarPiutang');
        Route::get('daftar-piutang-non-layanan', 'daftarPiutangNonLayanan');
        Route::get('daftar-collected-piutang-layanan', 'daftarCollectedPiutang');
    });

    Route::controller(TagihanPasienCtrl::class)->group(function () {
        Route::get('kasir/daftar-tagihan-lunas', 'DaftarTagihanLunas');
        Route::get('kasir/daftar-tagihan-belum-lunas', 'DaftarTagihanBelumLunas');
        Route::get('kasir/detail-tagihan', 'detailTagihanPasien');
        Route::get('kasir/detail-bayaran', 'detailBayaran');
        Route::get('kasir/daftar-deposit-pasien', 'getDaftarDepositPasien');
    });
    Route::controller(DaftarPasienPulangCtrl::class)->group(function () {
        Route::get('kasir/daftar-pasien-pulang', 'daftarPasienPulang');
        // Route::get('kasir/verifikasi-tagihan', 'verifikasiTagihan');
        // Route::get('kasir/detail-verif-tagihan', 'detailTagihanVerifikasi');

    });
    Route::controller(VerifikasiTagihanCtrl::class)->group(function () {
        Route::get('kasir/verifikasi-tagihan', 'dataTagihan');
        Route::get('kasir/list-kelas', 'listKelas');

        Route::post('kasir/verifikasi-tagihan/simpan', 'simpanVerifikasiTagihan');
    });
    Route::controller(PembayaranTagihanCtrl::class)->group(function () {
        Route::get('kasir/pembayaran-tagihan', 'dataPembayaranPasien');
        Route::post('kasir/pembayaran-tagihan/simpan', 'simpanPembayaran');
        Route::get('kasir/cara-bayar', 'caraBayar');
    });
    Route::controller(DaftarPenerimaanKasirCtrl::class)->group(function () {
        Route::get('kasir/daftar-penerimaan', 'daftarPenerimaan');
        Route::get('kasir/daftar-penerimaan/dropdown', 'daftarPenerimaanDropdown');
        Route::get('kasir/daftar-penerimaan/report/kwitansi', 'cetakKwitansi');
        Route::get('kasir/daftar-penerimaan/report/kwitansi-bayar-piutang', 'cetakKwitansiBayarPiutang');

        Route::get('kasir/daftar-penerimaan/report/kwitansi-piutang', 'cetakKwitansiPiutang');

        Route::post('kasir/daftar-penerimaan/ubah-cara-bayar', 'saveUbahCaraBayar');
        Route::post('kasir/daftar-penerimaan/batal-bayar', 'saveBatalBayar');
    });
    Route::controller(DaftarPengeluaranKasirCtrl::class)->group(function () {
        Route::get('kasir/daftar-pengeluaran', 'daftarPengeluaran');
        Route::get('kasir/daftar-pengeluaran/dropdown', 'daftarpengeluaranDropdown');
        Route::get('kasir/daftar-pengeluaran/report/kwitansi', 'cetakKwitansi');

        Route::post('kasir/daftar-pengeluaran/ubah-cara-bayar', 'saveUbahCaraBayar');
        Route::post('kasir/daftar-pengeluaran/batal-bayar', 'saveBatalBayar');
    });
    Route::controller(DaftarPasienAktifKasirCtrl::class)->group(function () {
        Route::get('kasir/daftar-pasien-aktif', 'daftarPasienAktif');
        Route::get('kasir/daftar-pasien-aktif/detail-deposit', 'detailDeposit');
    });
    Route::controller(DaftarTagihanNonLayananCtrl::class)->group(function () {
        Route::get('kasir/daftar-tagihan-non-layanan', 'daftarTagihanNonLayanan');
        Route::get('kasir/jumlah-nominal', 'nomialTagihan');
        Route::post('kasir/daftar-tagihan-non-layanan/hapus', 'hapusNonLayanan');
    });
    Route::controller(TagihanNonLayananCtrl::class)->group(function () {
        Route::get('kasir/tagihan-non-layanan', 'tagihanNonLayanan');
        Route::get('kasir/tagihan-non-layanan/dropdown', 'dropdownTagihanNonLayanan');
        Route::get('kasir/tagihan-non-layanan/penjamin-by-kelompokpasien', 'listPenjaminByKelompokPasien');
        Route::get('kasir/tagihan-non-layanan/pelayanan', 'listPelayananNonKelas');

        Route::post('kasir/tagihan-non-layanan/simpan', 'simpanNonLayanan');
    });
    Route::controller(OrderBarangCtrl::class)->group(function () {
        Route::get('logistik/list-order-cbo', 'dropdownList');
        Route::get('logistik/get-order-barang', 'getDaftarOrderBarang');
        Route::get('logistik/get-detail-order', 'getDetailOrderBarang');

        Route::post('logistik/hapus-order-barang', 'hapusOrderBarang');
        Route::post('logistik/save-order-barang', 'saveOrderBarang');
        Route::post('logistik/batal-kirim-order-barang', 'batalKirimBarang');
    });
    Route::controller(LaboratoriumCtrl::class)->group(function () {
        Route::get('laboratorium/layanan-lab', 'LayananLab');
        Route::get('laboratorium/dokter-hasil-lab', 'dokterLab');
        Route::get('laboratorium/get-hasil-manual', 'getHasilLabManual');
        Route::get('laboratorium/get-hasil-bridging', 'getHasilLabBridging');
        Route::get('laboratorium/petugas-lab', 'detailPetugasLab');
        Route::get('laboratorium/layanan-lab-pertindakan', 'LayananLabPerTindakan');
        Route::get('laboratorium/cetakan-hasil-lab', 'cetakHasilLab');
        Route::get('laboratorium/get-hasil-pa', 'getHasilPemeriksaanLab');
        Route::get('laboratorium/get-hasil-papsmear', 'getHasilPemeriksaanLabPapSmear');
        Route::get('laboratorium/get-hasil-pcr', 'getHasilPemeriksaanPcr');
        Route::get('laboratorium/get-hasil-mikro', 'getHasilPemeriksaanMikro');
        Route::get('laboratorium/get-regis-pasien', 'listPasienLab');
        Route::get('laboratorium/get-expertise', 'getExpertise');
        Route::get('laboratorium/cetak-ekspertise', 'cetakEkspertiseEcho');
        Route::get('laboratorium/cetak-hasil-papsmear', 'cetakHasilLabPapSmear');
        Route::get('laboratorium/hasil-lab', 'hasilLab');
        Route::get('laboratorium/source-hasil-lab', 'sourceHasilLab');
        Route::get('laboratorium/get-hasil-imunohistokimia', 'getHasilPemeriksaanImunohistokimia');
        Route::get('laboratorium/cetak-imunohistokimia', 'cetakImunohistokimia');

        Route::post('laboratorium/save-hasillab-pa', 'saveHasilLabPA');
        Route::post('laboratorium/save-hasil-manual', 'saveHasilLabManual');
        Route::post('laboratorium/save-petugas-lab', 'savePetugasLab');
        Route::post('laboratorium/delet-petugas-lab', 'deletePetugasLab');
        Route::post('laboratorium/hapus-tindakan-lab', 'hapusTindakanLab');
        Route::post('laboratorium/save-penunjang', 'saveTransaksi');
        Route::post('laboratorium/save-expertise', 'saveExpertise');
        Route::post('laboratorium/save-hasillab-pcr', 'saveHasilLabPCR');
        Route::post('laboratorium/save-hasillab-papsmear', 'saveHasilLabPapSmear');
        Route::post('laboratorium/save-hasillab-mikro', 'saveHasilLabMikro');
        Route::get('laboratorium/laporan-glucotest' ,'getLaporanGlucotest');
        Route::get('laboratorium/laporan-hasil' ,'getLaporanHasil');
        Route::post('laboratorium/save-hasil-imunohistokimia', 'saveHasilLabImunohistokimia');
    });
    Route::controller(MutasiPasienCtrl::class)->group(function () {
        Route::get('registrasi/head-mutasi', 'headMutasi');
        Route::get('registrasi/dokter-mutasi', 'dokterMutasi');
        Route::get('registrasi/list-kelas-mutasi', 'listKelasMutasi');
        Route::get('registrasi/list-kamar-mutasi', 'listKamarMutasi');
        Route::get('registrasi/penjamin-mutasi', 'listPenjaminMutasi');

        Route::post('registrasi/save-mutasi', 'saveMutasi');
    });
    Route::controller(RadiologiCtrl::class)->group(function () {
        Route::get('radiologi/layanan-radiologi', 'LayananRad');
        Route::get('radiologi/petugas-radiologi', 'detailPetugasRad');
        Route::get('radiologi/cetakan-hasil-radiologi', 'cetakLayananRadiologi');
        Route::get('radiologi/get-expertise', 'getExpertise');
        Route::get('radiologi/cetak-ekspertise', 'cetakEkspertiseEcho');
        Route::get('radiologi/list-pasien-regis', 'listRegisRadiologi');
        Route::get('radiologi/laporan-tindakana-radiologi' ,'getLaporanTindakanRadiologi');
        Route::get('radiologi/laporan-rekap-tindakan-radiologi' ,'getLaporanRekapTindakanRadiologi');

        Route::post('radiologi/hapus-tindakan-rad', 'hapusTindakanRad');
        Route::post('radiologi/save-petugas-rad', 'savePetugasRad');
        Route::post('radiologi/hapus-petugas-rad', 'deletePetugasRad');
        Route::post('radiologi/save-expertise', 'saveExpertise');
        // Route::post('radiologi/send-WA', 'kirimWA');
        Route::post('radiologi/save-transaksi-rad', 'saveTransaksiRad');
        Route::post('radiologi/hapus-expertise', 'hapusExpertise');
    });

    Route::controller(WaServerWaServerCtrl::class)->group(function(){
        Route::post('radiologi/send-WA', 'kirimWARadiologi');
    });
    Route::controller(CathlabCtrl::class)->group(function () {
        Route::get('cathlab/layanan-cathlab', 'LayananRad');
        Route::get('cathlab/petugas-cathlab', 'detailPetugasRad');
        Route::get('cathlab/cetakan-hasil-cathlab', 'cetakLayananCathlab');

        Route::post('cathlab/hapus-tindakan-rad', 'hapusTindakanRad');
        Route::post('cathlab/save-petugas-rad', 'savePetugasRad');
        Route::post('cathlab/hapus-petugas-rad', 'deletePetugasRad');
        Route::post('cathlab/save-expertise', 'saveExpertise');
    });
    Route::controller(PendukungPemeriksaanCtrl::class)->group(function () {
        Route::get('laboratorium/get-jenis-pemeriksaan', 'getJenisPemeriksaan');
        Route::get('laboratorium/load-pendukung', 'LoadPendukung');
        Route::get('laboratorium/get-satuan-hasil', 'getSatuanHasil');
        Route::get('laboratorium/get-nilai-normal', 'getNilaiNormal');
        Route::get('laboratorium/get-detail-pemeriksaan', 'getMapHasilLab');
        Route::get('laboratorium/get-dd-layanan', 'getLayananDD');

        Route::post('laboratorium/save-jenis-pemeriksaan', 'saveJenisPemeriksaan');
        Route::post('laboratorium/delete-pendukung', 'deleteJenisPemeriksaan');
        Route::post('laboratorium/save-satuan-hasil', 'saveSatuanHasil');
        Route::post('laboratorium/delete-satuan-hasil', 'deleteSatuanHasil');
        Route::post('laboratorium/save-detail-pemeriksaan', 'saveDetailPemeriksaan');
    });
    Route::controller(DashboardIGDCtrl::class)->group(function () {
        Route::get('dashboard/igd-detail', 'getIGDDetail');
        Route::get('dashboard/igd-pasien', 'getIGDPasien');
        Route::get('dashboard/dropdown-igd', 'getIGD');
        Route::get('dashboard/get-pelayanan-igd', 'HitungAntrianIGD');
        Route::get('dashboard/get-riwayat-mutasi-igd', 'getRiwayatMutasiIGD');

        Route::post('dashboard/igd/panggil', 'panggilPasienIGD');
        Route::post('/dashboard/send-WA-IGD', 'kirimWASuratDokterIGD');
    });
    Route::controller(MasterPaketObatCtrl::class)->group(function () {
        Route::get('sysadmin/master-paket-obat', 'masterPaketObat');
        Route::get('sysadmin/master-paket-obat-dd', 'masterPaketObatdropdown');

        Route::post('sysadmin/save-master-paket-obat', 'savePaketObat');
        Route::post('sysadmin/delete-master-paket-obat', 'deletePaketObat');
        Route::post('sysadmin/delete-master-kelompok-pasien', 'deleteKelompokPasien');
    });
    Route::controller(MapAdministrasiCtrl::class)->group(function () {
        Route::get('sysadmin/get-ruang', 'getListCombo');
        Route::get('sysadmin/produk-admin', 'getProdukAdmin');
        Route::get('sysadmin/get-produk-harga', 'getTindakanKomponen');
        Route::get('sysadmin/mapping-admin', 'getMapAdministrasi');

        Route::post('sysadmin/save-map-administrasi', 'saveMapAdmin');
        Route::post('sysadmin/delete-administrasi', 'deletMapping');
    });
    Route::controller(MapAkomodasiCtrl::class)->group(function () {
        Route::get('sysadmin/get-ruang-akomodasi', 'getListComboakomodasi');
        Route::get('sysadmin/produk-akomodasi', 'getProdukakomodasi');
        Route::get('sysadmin/get-produk-harga-akomodasi', 'getTindakanKomponen');
        Route::get('sysadmin/mapping-akomodasi', 'getMapAkomodasi');

        Route::post('sysadmin/save-map-akomodasi', 'saveMapAkomodasi');
        Route::post('sysadmin/save-akomodasi', 'saveAkomodasiAuto');
        Route::post('sysadmin/delete-akomodasi', 'deletMappingAkomodasi');
    });
    Route::controller(MapProdukPacsCtrl::class)->group(function () {
        Route::get('sysadmin/list-produk-pacs', 'getProdukRad');
        Route::get('sysadmin/list-modality', 'getMapping');

        Route::post('sysadmin/save-map-pacs', 'saveMapPacs');
    });


    Route::controller(LaporanRekamMedisCtrl::class)->group(function () {

        Route::get('laporan/indikator-pelayanan-rs', 'getDataRL31RawatInapNew');
        Route::get('laporan/indikator-pelayanan-ranap', 'getDataRL31RawatInap');
        Route::get('laporan/get-produk-mapping', 'getProdukMapLaporanRL');
        Route::get('laporan/kegiatan-pelayanan-ranap', 'getDataRL31YangRawatInap');
        Route::get('laporan/get-produk-mapping-rl', 'getProdukMapLaporanRL');
        Route::get('laporan/get-combo-data-rl', 'getComboMappingRL');
        Route::get('laporan/get-laporan-rl4a', 'getLaporanRL4aRawatInap');
        Route::get('laporan/get-laporan-rl4b', 'getLaporanRL4bRawatJalan');
        Route::get('laporan/get-laporan-kunjugan-gd', 'getLaporanRL32RawatDarurat');
        Route::get('laporan/get-laporan-kegiatan-gigi-mulut', 'getKegiatanKesehatanGigidanMulut');
        Route::get('laporan/get-laporan-kegiatan-kebidanan', 'getLaporanRL34Kebidanan');
        Route::get('laporan/get-laporan-kegiatan-perinatologi', 'getLaporanRL35Perinatologi');
        Route::get('laporan/get-laporan-kegiatan-pembedahan', 'getLaporanRL36Pembedahan');
        Route::get('laporan/get-laporan-kegiatan-radiologi', 'getLaporanRL37Radiologi');
        Route::get('laporan/get-laporan-kegiatan-Laboratorium', 'getPemeriksaanLab');
        Route::get('laporan/get-laporan-kegiatan-rehab-medik', 'getPelayananRehab');
        Route::get('laporan/get-laporan-kegiatan-pelayanan-khusus', 'getLaporanRL310Khusus');
        Route::get('laporan/get-laporan-kegiatan-kesehatan-jiwa', 'getLaporanRL311KesehatanJiwa');
        Route::get('laporan/get-laporan-kegiatan-asal-rujukan', 'getRL314Rujukan');
        Route::get('laporan/get-laporan-cara-bayar', 'getRL315CaraBayar');
        Route::get('laporan/get-kegiatan-laporan-farmasi', 'getPengadaanObat');
        Route::get('laporan/get-laporan-rl5a', 'getDataLaporanRL51Kujungan');
        Route::get('laporan/get-laporan-rl5b', 'getDataLaporanRL52KunjuanRawatJalan');
        Route::get('laporan/get-laporan-rl5c', 'getDataLaporanRL53PenyakitaRawatInap');
        Route::get('laporan/get-laporan-rl5d', 'getDataLaporanRL54PenyakitaRawatJalan');
    });

    Route::controller(ReportCtrl::class)->group(function () {
        Route::prefix('report')->group(function () {
            Route::get('farmasi/resep', 'cetakResep');
            Route::get('farmasi/cetak-order-resep', 'cetakOrderResep');
            Route::get('farmasi/resep-obat-bebas', 'cetakResepObatBebas');
            Route::get('farmasi/label-resep', 'cetakLabelResep');
            Route::get('farmasi/copy-resep', 'cetakCopyResep');
            Route::get('/cetak-lembar-ranap', 'cetakLembarRawatInap');
            Route::get('/cetak-lembar-keluar-masuk', 'suratKeluarMasuk');
            Route::get('/cetak-gelang-pasien', 'cetakGelangPasien');
            Route::get('/cetak-surat-kematian', 'cetakSuratKematian');
            Route::get('/cetak-surat-meninggal', 'cetakSuratMeninggal');
            Route::get('farmasi/get-data-waktuminum-resep', 'getDataWaktuMinum');
            // Route::get('farmasi/cetak-apotik-rekap-label', 'apotikRekapLabel');
            Route::get('farmasi/cetak-nomor-antrian', 'apotikCetakAntrian');
            Route::get('farmasi/resep-obat-23', 'cetakResepObat23');
            Route::get('farmasi/resep-obat-pulang', 'cetakResepObatPulang');
            Route::get('farmasi/kwitansi-obat-23', 'cetakKwitansiObat23');
            Route::get('farmasi/rekap-label-kecil', 'rekapLabelInject');
            Route::get('farmasi/label-custom', 'labelCustom');
            Route::get('farmasi/laporan-instalasi-farmasi', 'laporanInstalasiFarmasi');
            Route::get('farmasi/laporan-resep-dokter', 'laporanResepDokter');
            Route::get('farmasi/laporan-pendapatan-resep', 'laporanPendapatanResep');
            Route::get('/laporan-rehab-medik', 'laporanRehabMedik');
            Route::get('farmasi/laporan-pendapatan', 'laporanPendapatan');

            Route::get('kiosk/cetak-antrian', 'cetakAntrianKiosk');
            Route::get('/cetak-order', 'cetakOrder');
            Route::get('/cetak-label-tindakan', 'cetakLabelTindakan');

            Route::get('farmasi/cetak-apotik-label-kecil', 'apotikRekapLabelKecil');
            Route::get('farmasi/cetak-nama-pasien', 'apotikCetakNama');
            Route::get('farmasi/cetak-apotik-label-kecil-bebas', 'apotikRekapLabelKecilObatBebas');
            Route::get('farmasi/get-data-waktuminum-resep', 'getDataWaktuMinum');
            Route::get('farmasi/cetak-nomor-antrian', 'apotikCetakAntrian');
            Route::get('farmasi/laporan-pengeluaran-narkotika', 'getDataLaporanNarkotika');
            Route::get('kasir/laporan-penerimaan', 'getDataLaporanPenerimaanSemuaKasirPDF');
            Route::get('kasir/laporan-penerimaan-harian', 'getDataLaporanPenerimaanHarianPDF');

            Route::get('cetak-antrian', 'cetakAntrianKiosk');
            Route::get('bukti-pendaftaran', 'cetakBuktiPendaftaran');
            Route::get('kasir/bukti-pembayaran', 'buktiPembayaran');

            Route::get('ranap/cetak-surat-sehat', 'cetakSuratKeteranganSehat');
            Route::get('ranap/cetak-surat-sakit', 'cetakSuratKeteranganSakit');
            Route::get('cetak-surat-pendaftaran-ranap', 'suratPendaftaranRanap');

            Route::get('bukti-layanan-bpjs', 'cetakBillbpjs');
            Route::get('resum-medis', 'cetakResumMedis');

            Route::get('radiologi/cetak-rekap-expertise', 'cetakRekapExpertise');

            Route::get('farmasi/cetak-rekap-kirim-barang-farmasi', 'cetakRekapKirimBarangFarmasi');
            Route::get('farmasi/cetak-rekap-terima-barang-farmasi', 'cetakRekapTerimaBarangFarmasi');

            Route::get('gizi/cetak-label', 'cetakLabelGizi');
            Route::get('mcu/cetak-mcu', 'cetakMCU');
            Route::get('/cetak-sks', 'cetakSKS');
            Route::get('/cetak-skjiwa', 'cetakSKJiwa');
            Route::get('/cetak-sknapza', 'cetakSKNapza');
            Route::get('/get-riwayat-resep', 'getResep');
            Route::get('/cetak-hasil-pcr', 'cetakHasilAntigen');
            Route::get('/cetak-hasil-mikro', 'cetakHasilMikro');

            Route::get('/pelayanan/get-laporan-surveilans', 'getLaporanSurveilans');
            Route::get('/pelayanan/get-laporan-dokter-perpasien-rajal', 'getLaporanDokterPerpasienRajal');
            Route::get('/pelayanan/get-laporan-dokter-perpasien-ranap', 'getLaporanDokterPerpasienRanap');
            Route::get('/pelayanan/get-laporan-asal-rujukan-igd', 'getLaporanIgdAsalRujukan');
            Route::get('/pelayanan/get-laporan-cara-bayar-igd', 'getLaporanIgdCaraBayar');
            Route::get('/pelayanan/get-laporan-cara-pulang-igd', 'getLaporanIgdCaraPulang');
            Route::get('/pelayanan/get-laporan-diagnosa-igd', 'getLaporanIgdDiagnosa');
            Route::get('/pelayanan/get-laporan-dpjp-igd', 'getLaporanIgdDpjp');
            Route::get('/pelayanan/get-laporan-jumlah-dpjp-igd', 'getLaporanIgdJumlahDpjp');
            Route::get('/pelayanan/get-laporan-jeniskelamin-igd', 'getLaporanIgdJenisKelamin');
            Route::get('/pelayanan/get-laporan-domisili-igd', 'getLaporanIgdDomisili');
            Route::get('/pelayanan/get-laporan-mutasi-igd', 'getLaporanIgdMutasi');
            Route::get('/pelayanan/get-laporan-diagnosa-perpasien-igd', 'getLaporanIgdDiagnosaPerpasien');

            Route::get('/pelayanan/get-laporan-jp-inap', 'getLaporanJPInap');
            Route::get('/pelayanan/get-laporan-jp-rajal', 'getLaporanJPRajal');
            Route::get('/pelayanan/get-laporan-jp-penunjang', 'getLaporanJPPenunjang');
            Route::get('/pelayanan/get-laporan-jp-radiologi', 'getLaporanJPradiologi');

            // Route::get('/bjb/cetak-surat-va', 'cetakSuratVA');
        });
    });
    Route::controller(RekamMedisCtrl::class)->group(function () {
        Route::prefix('rekammedis')->group(function () {
            Route::get('/dropdown', 'getDropdown');
            Route::get('/get-ruangan-by-departement/{id}', 'getRuanganBydepartemenId');
            Route::get('/get-data-kendali-dokumen-rm', 'getDaftarKendaliDokumenRM');
            Route::post('/update-status-kendali-dokumen-rm', 'updateStatusKendaliDokumenRM');
        });
    });

    Route::prefix("medifirst2000")->group(function () {
        Route::controller(AntrianCtrl::class)->group(function () {
            Route::get('viewer/update-antrian', 'updatePanggil');
            Route::get('viewer/get-data-viewer', 'getViewer');
            Route::get('viewer/get-setting-viewer', 'getSettingViewer');
            Route::get('viewer/get-dipanggil', 'getDipanggil');
            Route::get('viewer/get-list-antrian', 'getListAntrian');
            Route::get('viewer/get-list-antrian-farmasi', 'getListAntrianFarm');
            Route::get('viewer/get-data-viewer-far', 'getViewerFar');
            Route::get('viewer/get-data-detail-panggil', 'getDetail');
            Route::get('viewer/get-list-datalast-panggil', 'getListCallerByRuangan');
        });

        Route::controller(GeneralCtrl::class)->group(function () {
            Route::get('sysadmin/logging/save-log-all', 'saveLoggingAll');
            Route::get('sysadmin/settingdatafixed/get/{setting}', 'settingFixData');
        });
        Route::prefix("bridging")->group(function () {
            Route::controller(BridgingBPJSCtrl::class)->group(function () {
                Route::post('/bpjs/tools', 'bpjsTools');
                Route::get('/bpjs/get-rujukan-pcare-nokartu', 'getNoRujukanPcareNoKartu');
                Route::get('/bpjs/get-rujukan-rs-nokartu', 'getNoRujukanRs');
                Route::get('/bpjs/get-no-peserta', 'getNoPeserta');
                Route::get('/bpjs/monitoring/HistoriPelayanan/NoKartu/{noKartu}', 'getMonitoringHistori');
                Route::get('/bpjs/get-rujukan-pcare', 'getNoRujukanPcare');
                Route::get('/bpjs/get-rujukan-rs', 'getNoRujukanRs');
                Route::get('/bpjs/get-ref-dokter-dpjp', 'getDokterDPJP');
                Route::get('/bpjs/get-mapping-dkoterbpjs', 'getDaftarMappingDokterBpjsToDokterRs');

                Route::post('/bpjs/save-data-mappingdkoterbpjs' , 'saveMappingDokterBpjsDokterRs');
                Route::post('/bpjs/delete-data-mappingdkoterbpjs' , 'saveHapusMappingDokterBpjsDokterRs');
            });
        });
        Route::prefix("reservasionline")->group(function () {
            Route::controller(ReservasiMobileCtrl::class)->group(function () {
                Route::get('/get-history', 'getHistoryReservasi');
                Route::post('/update-data-status-reservasi', 'UpdateStatConfirm');
                Route::get('/get-pasien-nokartu/{nocm}', 'getPasienByNoka');
            });
        });
        Route::controller(ReportCtrl::class)->group(function () {
            Route::prefix('report')->group(function () {
                Route::get('/cetak-antrian', 'cetakAntrianKiosk');
                Route::get('/cetak-bukti-pendaftaran', 'cetakBuktiPendaftaran');
                Route::get('/get-cetak-bukti-pendaftaran', 'Report\ReportController@cetakBuktiPendaftaranGet');
            });
        });

        Route::controller(KiosKController::class)->group(function () {
            Route::prefix('kiosk')->group(function () {
                Route::get('get-combo-setting', 'getComboSettingKios');
                Route::post('save-antrian', 'saveAntrianTouchscreen')->name("pasienBaru");
                Route::get('get-combo-registrasi', 'getComboRegBaru');
                Route::get('get-pasien/{nocm}/{tgllahir}', 'getPasienByNoCmTglLahir');
                Route::get('get-combo-kiosk2', 'getComboKios2');
                Route::get('get-daftar-jadwal-dokter', 'getJadwalDokter');
                Route::get('get-daftar-poli-internal', 'getRuanganBPJSInternal');
                Route::get('get-penjaminbykelompokpasien', 'getPenjaminByKelompokPasien');
                Route::get('get-diagnosabykode/{kode}', 'getDiagnosaByKode');
                Route::get('get-ruanganbykode/{kode}', 'getRuanganByKodeInternal');
                Route::get('get-ruangan', 'getComboRuanganKios');
                Route::get('get-jumlah-loket', 'getJumlahLoket');
                Route::get('get-slotting-kosong', 'getSlottingKosong');
                Route::get('get-slotting-kiosk', 'getSlottingKios');
                Route::get('get-data-pasien/{identitas}', 'getDataPasien');
                Route::get('get-tiket-hari-ini', 'checkIsGetTicket');
                Route::get('get-dokter-internal', 'getDokterInternal');
                Route::get('get-tiket-peserta', 'checkIsGetTicket');
                Route::post('save-slotting-kiosk', 'saveSlottingKios');
                Route::post('delete-slotting-kiosk', 'deleteSlotting');


                // Route::post('kiosk/save-antrian','KiosK\KiosKController@saveAntrianTouchscreen')->name("pasienBaru");
                // Route::get('kiosk/get-ruanganbykode/{kode}','KiosK\KiosKController@getRuanganByKodeInternal');
                // Route::get('kiosk/get-diagnosabykode/{kode}','KiosK\KiosKController@getDiagnosaByKode');
                // Route::get('kiosk/get-view-bed-tea', 'KiosK\KiosKController@getKetersediaanTempatTidurView');
                // Route::get('kiosk/get-view-bed', 'KiosK\KiosKController@viewBed');
                // Route::get('kiosk/get-combo', 'KiosK\KiosKController@getDataCombo');
                // Route::get('kiosk/get-tarif', 'KiosK\KiosKController@getDaftarTarif');
                // Route::post('kiosk/save-survey', 'KiosK\KiosKController@saveSurvey');
                // Route::get('kiosk/get-combo-dokter-temp', 'KiosK\KiosKController@getComboDokterKios');
                // Route::get('kiosk/get-combo-setting', 'KiosK\KiosKController@getComboSettingKios');
                // Route::get('kiosk/get-ruangan', 'KiosK\KiosKController@getComboRuanganKios');
                // Route::get('kiosk/get-slotting-kosong', 'KiosK\KiosKController@getSlottingKosong');
                // Route::get('kiosk/get-list-loket', 'KiosK\KiosKController@getListLoket');
                // Route::get('kiosk/get-dokter-internal', 'KiosK\KiosKController@getDokterInternal');
                // Route::get('kiosk/get-combo-kiosk2', 'KiosK\KiosKController@getComboKios2');
                // Route::get('kiosk/get-daftar-jadwal-dokter','KiosK\KiosKController@getJadwalDokter');
                // Route::get('kiosk/get-pasien-by-noka','KiosK\KiosKController@getPasienByNoka');
                // Route::get('kiosk/get-quisoner','KiosK\KiosKController@getQuisonerMaster');
                // Route::get('kiosk/get-quisoner-transaksi-detail', 'KiosK\KiosKController@getQuisonerTransaksiDetail');
                // Route::get('kiosk/get-data-ruangan', 'KiosK\KiosKController@getDataRuangan');

                // Route::post('kiosk/save-keluhan-pelanggan', 'Humas\HumasController@SaveKeluhanPelanggan');
                // Route::post('kiosk/save-quiz', 'Humas\HumasController@saveQuisDinamis');
            });
        });

        Route::controller(PasienBaruCtrl::class)->group(function () {
            Route::post('registrasi/save-pasien-fix', 'savePasien');
        });
        Route::controller(RegistrasiRuanganCtrl::class)->group(function () {
            Route::post('registrasi/save-registrasipasien', 'saveRegistrasi');
            Route::post('registrasi/save-adminsitrasi', 'saveAdministrasi');
            Route::post('registrasi/confirmReservasi', 'confirmReservasi');
        });

        Route::controller(ReservasiMobileCtrl::class)->group(function () {
            Route::prefix('reservasionline')->group(function () {
                Route::get('/get-list-data', 'getComboReservasi');
                Route::get('/get-daftar-slotting', 'getDaftarSlotting');
                Route::get('/get-history', 'getHistoryReservasi');
                Route::get('/get-pasien/{nocm}/{tgllahir}', 'getPasienByNoCmTglLahir');
                Route::get('/get-libur', 'getLiburSlotting');
                Route::get('/get-bank-account', 'getNomorRekening');
                Route::get('/cek-reservasi-satu', 'cekReservasiDipoliYangSama');
                Route::get('/get-slotting-by-ruangan-new/{kode}/{tgl}', 'getSlottingByRuanganNew');
                Route::get('/get-slot-available', 'getDaftarSlottingAktif');
                Route::get('/tagihan/get-pasien/{noregistrasi}', 'getPasienByNoRegistrasi'); //done
                Route::get('/get-tagihan-pasien/{noregistasi}', 'getTagihanEbilling');
                Route::get('/get-setting', 'getSetting');
                Route::get('/daftar-riwayat-registrasi', 'getDaftarRiwayatRegistrasi');
                Route::get('/cek-pasien-baru-by-nik/{nik}', 'cekPasienByNik');
                Route::get('/get-status-va', 'getDaftarStatusVA');
                Route::get('/get-slotting-new', 'getSlottingByRuanganNew2');
                Route::get('/get-data', 'getDataReservasi');
                Route::get('/get-slotting-rev', 'getSlottingByRuanganDokter');
                Route::get('/get-dokter', 'getDokterByRuang');
                Route::get('/get-pasien-nokartu/{nocm}', 'getPasienByNoka');
                Route::get('/billing', 'billingPasien');
                Route::get('/info-bed', 'infoBed');
                Route::get('/jadwal-dokter', 'jadwalDokter');


                Route::post('/save-slotting', 'saveSlotting');
                Route::post('/update-data-status-reservasi', 'UpdateStatConfirm');
                Route::post('/update-nocmfk-antrian-registrasi', 'updateNoCmInAntrianRegistrasi');
                Route::post('/save', 'saveReservasi');
                Route::post('/delete', 'deleteReservasi');
                Route::post('/save-libur', 'saveLibur');
                Route::post('/delete-libur', 'deleteLibur');
                Route::post('/update-tglreservasi', 'updateTglReservasi');
            });
        });
    });

    Route::controller(RegistrasiPasienCtrl::class)->group(function () {
        Route::get('resgistrasi/get-daftar-pasienbatal', 'getPembatalanPasien');
        Route::get('resgistrasi/get-daftar-pasien-meninggal', 'getPasienMeninggal');
        Route::get('resgistrasi/laporan-pasien-daftar-mjkn', 'laporanPasienDaftarMJKN');
        Route::get('laporan/pendaftaran', 'getLaporanPasienDaftar');
        Route::get('resgistrasi/get-top-ten-diagnosa', 'getTopTenDiagnosa');
        Route::get('resgistrasi/get-laporan-tracer', 'getLaporanTracer');
        Route::get('registrasi/cetak-tracer', 'cetakTracer');
        Route::post('registrasi/save-update-rekanan_pd', 'simpanUpdateRekananPD');
    });
});
});
Route::controller(NoAuthCtrl::class)->group(function () {
    Route::post('service/bridging/penunjang/update-hasil-pacs', 'saveSendBack');
});
Route::controller(BSRECtrl::class)->group(function () {
    Route::get('service/bsre-esign/bsre-cppt-ranap', 'BSRECPPTRanap');
    Route::get('service/bsre-esign/get-ttd-emr', 'getDataList');
    Route::post('service/bsre-esign/save-dokumen-pengajuan-tte', 'saveDokumenPengajuanTTE');
    Route::post('service/bsre-esign/hapus-dokumen', 'hapusDokumen');
    Route::post('service/bsre-esign/save-sign-pdf', 'saveSIGNPDF');
    Route::post('service/bsre-esign/save-verify-pdf', 'saveVerifyPDF');
});
Route::controller(AuthCtrl::class)->group(function () {
    Route::post('service/auth/login', 'login');
    Route::post('service/auth/pasien', 'loginPasien');
});

// Route::prefix("service/reservasionline")->group(function () {
Route::middleware(['jwt.auth.pasien'])->prefix("service/reservasionline")->group(function () {
    Route::controller(ReservasiMobileCtrl::class)->group(function () {

        Route::get('/get-list-data', 'getComboReservasi');
        Route::get('/get-daftar-slotting', 'getDaftarSlotting');
        Route::get('/get-history', 'getHistoryReservasi');
        Route::get('/get-history-all', 'getHistoryReservasiMobile');
        Route::get('/get-pasien/{nocm}/{tgllahir}', 'getPasienByNoCmTglLahir');
        Route::get('/get-libur', 'getLiburSlotting');
        Route::get('/get-bank-account', 'getNomorRekening');
        Route::get('/cek-reservasi-satu', 'cekReservasiDipoliYangSama');
        Route::get('/get-slotting-by-ruangan-new/{kode}/{tgl}', 'getSlottingByRuanganNew');
        Route::get('/get-slot-available', 'getDaftarSlottingAktif');
        Route::get('/tagihan/get-pasien/{noregistrasi}', 'getPasienByNoRegistrasi'); //done
        Route::get('/get-tagihan-pasien/{noregistasi}', 'getTagihanEbilling');
        Route::get('/get-setting', 'getSetting');
        Route::get('/daftar-riwayat-registrasi', 'getDaftarRiwayatRegistrasi');
        Route::get('/cek-pasien-baru-by-nik/{nik}', 'cekPasienByNik');
        Route::get('/get-status-va', 'getDaftarStatusVA');
        Route::get('/get-slotting-new', 'getSlottingByRuanganNew2');
        Route::get('/get-data', 'getDataReservasi');
        Route::get('/get-slotting-rev', 'getSlottingByRuanganDokter');
        Route::get('/get-dokter', 'getDokterByRuang');
        Route::get('/get-pasien-nokartu/{nocm}', 'getPasienByNoka');
        Route::get('/billing', 'billingPasien');
        Route::get('/info-bed', 'infoBed');
        Route::get('/jadwal-dokter', 'jadwalDokter');
        Route::get('/get-hasil-radiologi/{noorder}', 'getHasilRadiologi');
        Route::get('/get-hasil-laboratorium/{noorder}', 'getHasilLaboratorium');
        Route::get('/list-radiologi/{pasienID}', 'listRadiologi');
        Route::get('/list-laboratorium/{pasienID}', 'listLaboratorium');
        Route::get('/riwayat-resep/{pasienID}', 'riwayatOrderResep');


        Route::post('/save-slotting', 'saveSlotting');
        Route::post('/update-data-status-reservasi', 'UpdateStatConfirm');
        Route::post('/update-nocmfk-antrian-registrasi', 'updateNoCmInAntrianRegistrasi');
        Route::post('/save', 'saveReservasi');
        Route::post('/delete', 'deleteReservasi');
        Route::post('/save-libur', 'saveLibur');
        Route::post('/delete-libur', 'deleteLibur');
        Route::post('/update-tglreservasi', 'updateTglReservasi');
    });
});

Route::controller(AntrianOnlineCtrl::class)->prefix("antrian-bpjs")->group(function () {
    Route::get('antrol/auth', 'tokenAntrean');
    Route::middleware(['jwt.auth'])->group(function () {
        Route::middleware(['log'])->group(function () {
            Route::post('antrol/antrean', 'ambilAntrean');
            Route::post('antrol/rekap', 'statusAntrean');
            Route::post('antrol/sisa', 'sisaAntrean');
            Route::post('antrol/batal', 'batalAntrean');
            Route::post('antrol/checkin', 'checkIn');
            Route::post('antrol/pasienbaru', 'pasienBaru');
            Route::post('antrol/operasi', 'jadwalOperasiPasien');
            Route::post('antrol/jadwaloperasi', 'jadwalOperasiRS');
            // farmasi
            Route::post('antrol/antrean-farmasi', 'ambilAntreanFarmasi');
            Route::post('antrol/rekap-farmasi', 'statusAntreanFarmasi');
        });
    });
});

Route::controller(RadiologiCtrl::class)->group(function(){
    Route::get('service/radiologi/cetak-ekspertise-nontoken', 'cetakEkspertiseEchoNonToken');
});

Route::controller(BridgingPenunjangCtrl::class)->group(function(){
    Route::get('service/bridging/penunjang/cetakan-hasil-lab-new-non-token', 'cetakHasilLabNewNonToken');
    // Route::get('service/radiologi/cetak-ekspertise-nontoken', 'cetakEkspertiseEchoNonToken');
});

Route::controller(HigeaCtrl::class)->prefix('service/higea')->group(function () {
    Route::post('auth/get-access-token', 'getAccessToken');
    Route::middleware(['jwt.auth'])->group(function () {
        Route::get('list-doctor', 'getDoctor');
        Route::get('list-ruangan', 'getRuangan');
        Route::get('list-kelas', 'getKelas');
        Route::get('list-kamar-kelas', 'getKamarByKelas');
        Route::get('list-bed', 'getBed');
        Route::get('get-pasien-regitrasi' , 'getPasienRegistrasi');
        Route::get('get-rujukan' , 'getRujukan');
        Route::get('daftar-pasien-pulang' , 'daftarPasienPulang');

        Route::post('registrasi' , 'registrasi');
    });
});

Route::get('/', function () {
    return view('welcome');
});
