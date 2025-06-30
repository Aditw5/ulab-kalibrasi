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
use App\Http\Controllers\General\GeneralCtrl;
use App\Http\Controllers\General\SysAdminCtrl;
use App\Http\Controllers\EMR\ProfilePasienCtrl;
use App\Http\Controllers\Registrasi\MitraCtrl;
use App\Http\Controllers\Asman\AsmanCtrl;
use App\Http\Controllers\Manager\ManagerCtrl;
use App\Http\Controllers\Penyelia\PenyeliaCtrl;
use App\Http\Controllers\Pelaksana\PelaksanaCtrl;
use App\Http\Controllers\Logistik\KartuStokCtrl;
use App\Http\Controllers\Sysadmin\MasterSukuCtrl;
use App\Http\Controllers\Sysadmin\MasterAgamaCtrl;
use App\Http\Controllers\Sysadmin\MasterKamarCtrl;
use App\Http\Controllers\Sysadmin\MasterKelasCtrl;
use App\Http\Controllers\Sysadmin\MasterPaketCtrl;
use App\Http\Controllers\Sysadmin\MasterSignaCtrl;
use App\Http\Controllers\Registrasi\MitraBaruCtrl;
use App\Http\Controllers\Registrasi\MitraLamaCtrl;
use App\Http\Controllers\Sysadmin\MasterNegaraCtrl;
use App\Http\Controllers\Sysadmin\MasterProdukCtrl;
use App\Http\Controllers\Sysadmin\MasterJabatanCtrl;
use App\Http\Controllers\Sysadmin\MasterPegawaiCtrl;
use App\Http\Controllers\Sysadmin\MasterRekananCtrl;
use App\Http\Controllers\Sysadmin\MasterRuanganCtrl;
use App\Http\Controllers\Sysadmin\MasterProvinsiCtrl;
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
use App\Http\Controllers\Dashboard\DashboardObatAlkesCtrl;
use App\Http\Controllers\Dashboard\DashboardTindakanCtrl;
use App\Http\Controllers\Sysadmin\MasterAsalRujukanCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisProdukCtrl;
use App\Http\Controllers\Sysadmin\MasterMapKelompokCtrl;
use App\Http\Controllers\Sysadmin\MasterSatuanResepCtrl;
use App\Http\Controllers\Sysadmin\MasterTipePegawaiCtrl;
use App\Http\Controllers\Registrasi\DaftarRegistrasiCtrl;
use App\Http\Controllers\Sysadmin\MasterJadwalDokterCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisJabatanCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisKelaminCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisPegawaiCtrl;
use App\Http\Controllers\Sysadmin\MasterKelompokUserCtrl;
use App\Http\Controllers\Sysadmin\MasterRouteFarmasiCtrl;
use App\Http\Controllers\Sysadmin\MasterStatusKeluarCtrl;
use App\Http\Controllers\Sysadmin\MasterStatusPulangCtrl;
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
use App\Http\Controllers\Sysadmin\MasterKelompokJabatanCtrl;
use App\Http\Controllers\Sysadmin\MasterTambahLoginUserCtrl;
use App\Http\Controllers\Sysadmin\MasterMapPaketToProdukCtrl;
use App\Http\Controllers\Sysadmin\MasterStatusPerkawinanCtrl;
use App\Http\Controllers\Sysadmin\MasterDetailJenisProdukCtrl;
use App\Http\Controllers\Sysadmin\MasterKelompokTransaksiCtrl;
use App\Http\Controllers\Sysadmin\MasterMapRuanganToKelasCtrl;
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
use App\Http\Controllers\Logistik\PenerimaanBarangCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisDietCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisUsulanCtrl;
use App\Http\Controllers\Sysadmin\MasterKategoryDietCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisWaktuCtrl;
use App\Http\Controllers\Sysadmin\MasterKonversiSatuanCtrl;
use App\Http\Controllers\Sysadmin\MasterSediaanCtrl;
use App\Http\Controllers\EMR\EMRCtrl;
use App\Http\Controllers\EMR\ReportEMRCtrl;
use App\Http\Controllers\Laporan\LaporanPelaksanaCtrl;
use App\Http\Controllers\Laporan\LaporanPengunjungCtrl;
use App\Http\Controllers\Laporan\LaporanPelaksanCtrl;
use App\Http\Controllers\Report\ReportCtrl;
use App\Http\Controllers\Sysadmin\MasterAlergiCtrl;
use App\Http\Controllers\Sysadmin\MapAdministrasiCtrl;
use App\Http\Controllers\Sysadmin\MapProdukPacsCtrl;
use App\Http\Controllers\Sysadmin\MasterMapDepoToRuanganCtrl;
use App\Http\Controllers\Sysadmin\MapAkomodasiCtrl;
use App\Http\Controllers\Laporan\LaporanTindakanPasienCtrl;
use App\Http\Controllers\laporan\LaporanRekamMedisCtrl;
use App\Http\Controllers\Sysadmin\MasterSatuanGiziCtrl;
use App\Http\Controllers\Sysadmin\MapKelompokLaporanCtrl;
use App\Http\Controllers\Sysadmin\MapKsmToRuanganCtrl;
use App\Http\Controllers\Sysadmin\MasterKelompokLaporanCtrl;
use App\Http\Controllers\Sysadmin\MasterKSMCtrl;
use App\Http\Controllers\Sysadmin\MasterVendorGiziCtrl;
use App\Http\Controllers\WaServer\WaServerCtrl as WaServerWaServerCtrl;
use App\Http\Controllers\WaServerCtrl;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Models\Master\User;
use Illuminate\Auth\Events\Verified;


Route::middleware(['jwt.auth'])->prefix("service")->group(function () {
Route::middleware(['log'])->group(function () {

    Route::prefix("general/menu")->group(function () {
        Route::controller(SysAdminCtrl::class)->group(function () {
            Route::get('/list-menu', 'listMenu');
        });
    });

    Route::controller(SysAdminCtrl::class)->group(function () {
        Route::get('/get-time-server', 'getTimeServer');
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

    Route::controller(DashboardObatAlkesCtrl::class)->group(function () {
        Route::get('dashboard/data-obat', 'getObat');
        Route::get('farmasi/daftar-retur-obat-alkes', 'getDaftarReturObat');
        Route::get('farmasi/cetak-bukti-retur', 'cetakReturResep');
    });
    Route::controller(DashboardTindakanCtrl::class)->group(function () {
        Route::get('dashboard/data-tindakan', 'getTindakan');
        Route::get('dashboard/data-tindakan-dropdown', 'TindakanDropdown');
    });

    Route::controller(MitraLamaCtrl::class)->group(function () {
        Route::get('registrasi/mitra-lama', 'mitraLama');
        Route::get('registrasi/cek-pasien-pulang', 'cekPulangpasien');
        Route::get('registrasi/cek-sep', 'cekSEPpasien');
        Route::post('registrasi/delete-mitra', 'deleteMitra');
    });

    Route::controller(MitraBaruCtrl::class)->group(function () {
        Route::get('registrasi/desa-kelurahan-paging', 'listDesaKelurahanPaging');
        Route::get('registrasi/kecamatan-paging', 'listKecamatanPaging');
        Route::get('registrasi/kotakabupaten', 'listKotaKab');
        Route::get('registrasi/kecamatan', 'listKecamatan');
        Route::get('registrasi/desakelurahan', 'listDesa');
        Route::get('registrasi/list-mitra-dropdown', 'listDropdown');
        Route::get('registrasi/mitra', 'mitraByID');
        Route::get('registrasi/riwayat-registrasi', 'riwayatRegistrasi');

        Route::post('registrasi/save-mitra', 'saveMitra');
        Route::post('registrasi/save-pasien-bayi', 'savePasienBayi');
        Route::post('registrasi/save-pasien-foto', 'savePasienFoto');
    });
    Route::controller(RegistrasiMitraCtrl::class)->group(function () {
        Route::get('registrasi/mitra-registrasi', 'mitraRegistrasi');
        Route::post('registrasi/save-registrasi-mitra', 'saveRegistrasiMitra');
        Route::post('registrasi/save-registrasi-repair-mitra', 'saveRegistrasiRepairMitra');
        Route::get('registrasi/dropdown-paket-kalibrasi', 'dropdownPaketKalibrasi');
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
        Route::post('registrasi/save-penolakan-alat', 'savePenolakanALat');
        Route::get('registrasi/produk-by-id', 'produkByMitra');
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
        Route::get('asman/download-file-terunggah', 'downloadFileTerunggah');
        Route::get('asman/excel-length', 'getExcelLength');
        Route::get('asman/get-laporan-repair', 'getLaporanRepairAsman');
        Route::get('asman/detail-alat-repair', 'detailAlatRepairAsman');
        Route::post('asman/hapus-laporan-repair', 'hapusLaporanRepairAsman');
        Route::post('asman/save-setujui-laporan-repair', 'setujuiLaporanRepairAsman');
        Route::get('asman/cetak-laporan-repair', 'cetakLaporanRepairAsman');
        Route::post('asman/hapus-status-repair', 'hapusLaporanStatus');
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
        Route::get('manager/download-file-terunggah', 'downloadFileTerunggah');
        Route::get('manager/excel-length', 'getExcelLength');
        Route::get('manager/get-laporan-repair', 'getLaporanRepairManager');
        Route::get('manager/detail-alat-repair', 'detailAlatRepairManager');
        Route::post('manager/hapus-laporan-repair', 'hapusLaporanRepairManager');
        Route::post('manager/save-setujui-laporan-repair', 'setujuiLaporanRepairManager');
        Route::get('manager/cetak-laporan-repair', 'cetakLaporanRepairManager');
        Route::post('manager/hapus-status-repair', 'hapusLaporanStatus');
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
        Route::get('penyelia/download-file-terunggah', 'downloadFileTerunggah');
        Route::get('penyelia/excel-length', 'getExcelLength');
        Route::get('penyelia/get-pegawai-pelaksana', 'getPegawaiPelaksana');
        Route::post('penyelia/save-data-laporan-repair', 'simpanLaporanRepairPenyelia');
        Route::get('penyelia/get-laporan-repair', 'getLaporanRepairPenyelia');
        Route::get('penyelia/detail-alat-repair', 'detailAlatRepairPenyelia');
        Route::post('penyelia/hapus-laporan-repair', 'hapusLaporanRepairPenyelia');
        Route::post('penyelia/save-setujui-laporan-repair', 'setujuiLaporanRepair');
        Route::post('penyelia/hapus-status-repair', 'hapusLaporanStatus');
        Route::get('penyelia/cetak-laporan-repair', 'cetakLaporanRepairPenyelia');
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
        Route::post('pelaksana/save-excel-lembar-kerja', 'saveExcelLembarKerja');
        Route::get('pelaksana/download-file-terunggah', 'downloadFileTerunggah');
        Route::get('pelaksana/excel-length', 'getExcelLength');
        Route::post('pelaksana/save-data-laporan-repair', 'simpanLaporanRepair');
        Route::get('pelaksana/get-laporan-repair', 'getLaporanRepair');
        Route::get('pelaksana/detail-alat-repair', 'detailAlatRepair');
        Route::post('pelaksana/hapus-laporan-repair', 'hapusLaporanRepair');
        Route::post('pelaksana/hapus-status-repair', 'hapusLaporanStatus');
        Route::get('pelaksana/cetak-laporan-repair', 'cetakLaporanRepair');
    });

    Route::controller(EMRCtrl::class)->group(function () {
        Route::get('emr/dropdown/{table}', 'dropdownEMR');
        Route::post('emr/kirim-wa-resume', 'kirimResumeMedis');
    });

    Route::controller(LaporanPengunjungCtrl::class)->group(function () {
        Route::get('pelayanan/get-laporan-pengunjung', 'getLaporanPengunjungPemeriksaan');
    });

    Route::controller(LaporanPelaksanaCtrl::class)->group(function () {
        Route::get('laporan/get-laporan-alat-pelaksana', 'getLaporanAlatPelaksana');
    });

    Route::controller(LaporanTindakanPasienCtrl::class)->group(function () {
        Route::get('laporan/get-laporan-pengunjung', 'getPelayananTindakan');
        Route::get('laporan/laporan-time-respon', 'getTimeRespon');
        Route::get('laporan/pilihan-search', 'pilihanSearch');
    });

    Route::controller(MasterRuanganCtrl::class)->group(function () {
        Route::get('sysadmin/master-ruangan', 'masterRuangan');
        Route::get('sysadmin/master-ruangan-dropdown', 'masterRuangandropdown');

        Route::post('sysadmin/save-master-ruangan', 'saveRuangan');
        Route::post('sysadmin/delete-master-ruangan', 'deleteRuangan');
        Route::post('sysadmin/detail-master-ruangan', 'detailRuangan');
        Route::post('sysadmin/update-master-ruangan', 'updateRuangan');
    });

    Route::controller(MasterMapKelompokCtrl::class)->group(function () {
        Route::get('sysadmin/master-map-kelompok', 'masterMapKelompok');
        Route::get('sysadmin/master-map-kelompok-dropdown', 'masterMapKelompokdropdown');

        Route::post('sysadmin/save-map-kelompok', 'saveMapKelompok');
        Route::post('sysadmin/delete-map-kelompok', 'deleteMapKelompok');
        Route::post('sysadmin/detail-map-kelompok', 'detailMapKelompok');
    });

    Route::controller(MapKelompokLaporanCtrl::class)->group(function () {
        Route::get('sysadmin/get-combo-map-laporan', 'getCombo');
        Route::get('sysadmin/get-map-laporan-rl', 'getMapLaporanRL');

        Route::post('sysadmin/save-map-laporan-rl', 'SaveMappingRl');
        Route::post('sysadmin/delete-map-laporan-rl', 'deleteMap');
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
    Route::controller(MasterKelompokTransaksiCtrl::class)->group(function () {
        Route::get('sysadmin/master-kelompok-transaksi', 'masterKelompokTransaksi');
        Route::get('sysadmin/master-kelompok-transaksi-dropdown', 'masterKelompokTransaksidropdown');

        Route::post('sysadmin/save-kelompok-transaksi', 'saveKelompokTransaksi');
        Route::post('sysadmin/delete-kelompok-transaksi', 'deleteKelompokTransaksi');
        Route::post('sysadmin/detail-kelompok-transaksi', 'detailKelompokTransaksi');
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
    Route::controller(MasterJabatanCtrl::class)->group(function () {
        Route::get('sysadmin/master-jabatan', 'masterJabatan');
        Route::get('sysadmin/master-jabatan-dropdown', 'masterJabatandropdown');

        Route::post('sysadmin/save-master-jabatan', 'saveJabatan');
        Route::post('sysadmin/delete-master-jabatan', 'deleteJabatan');
    });
    Route::controller(MasterJenisJabatanCtrl::class)->group(function () {
        Route::get('sysadmin/master-jenis-jabatan', 'masterJenisJabatan');
        Route::get('sysadmin/master-jenis-jabatan-dropdown', 'masterJenisJabatandropdown');

        Route::post('sysadmin/save-master-jenis-jabatan', 'saveJenisJabatan');
        Route::post('sysadmin/delete-master-jenis-jabatan', 'deleteJenisJabatan');
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

    Route::controller(MasterJenisWaktuCtrl::class)->group(function () {
        Route::get('sysadmin/master-jenis-waktu', 'masterJenisWaktu');
        Route::get('sysadmin/dropdown-departemen', 'DropdownKP');

        Route::post('sysadmin/save-jenis-waktu', 'saveJenisWaktu');
        Route::post('sysadmin/delete-jenis-waktu', 'deleteJenisWaktu');
    });
    Route::controller(MasterKamarCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-kamar')->group(function () {
            Route::get('/', 'index');
            Route::post('/save', 'store');
            Route::post('/delete', 'delete');
            Route::get('/select-item', 'dropdownItem');
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

    Route::controller(MasterKategoriDiagnosaCtrl::class)->group(function () {
        Route::prefix('sysadmin/master-kategori-diagnosa')->group(function () {
            Route::get('/', 'index');
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

    Route::controller(DashboardTindakanCtrl::class)->group(function () {
        Route::prefix('dashboard/tindakan')->group(function () {
            Route::get('/', 'produkTindakan');
        });
    });

    Route::controller(MasterListCtrl::class)->group(function () {
        Route::get('sysadmin/master-list', 'masterList');
        Route::post('sysadmin/save-master-list', 'saveList');
        Route::post('sysadmin/delete-master-list', 'deleteList');
    });


    Route::controller(WaServerWaServerCtrl::class)->group(function(){
        Route::post('radiologi/send-WA', 'kirimWARadiologi');
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
});
});
Route::controller(AuthCtrl::class)->group(function () {
    Route::post('service/auth/login', 'login');
    Route::post('service/auth/register', 'registerUser');
});

Route::get('https://ulabumro.id:8000/service/auth/verify-email/{id}/{hash}', function (Request $request, $id, $hash) {
    if (! $request->hasValidSignature()) {
        abort(403, 'Tautan verifikasi tidak valid atau sudah kadaluarsa.');
    }
    $user = User::findOrFail($id);
    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403, 'Tautan verifikasi tidak cocok.');
    }
    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }
    return redirect()->away('https://ulabumro.id/auth/signup-1?verified=1');
    //  return redirect()->away('https://localhost:5173/auth/signup-1?verified=1');
})
->middleware('signed')
->name('verification.verify');

Route::get('/', function () {
    return view('welcome');
});
