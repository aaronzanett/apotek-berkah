<?php
class App extends Controller {
    // headoffice
    private $navigations_headoffice = [
    'Dashboard', 
    ['Master Data', 'Master Produk', 'Master Kategori', 'Master Satuan', 'Master Rak', 'Metode Pembayaran'],
    ['Persediaan', 'Persediaan Produk', 'Defecta', 'Stok Kadaluwarsa', 'Retur Stok Outlet', 'Stok Opname', 'Riwayat Stok Opname'],
    'Pembelian',
    'Supply',
    'Penjualan',
    ['Keuangan', 'Buku Kas', 'Utang Usaha', 'Piutang Usaha'],
    ['Pengguna', 'Daftar Pengguna', 'Role & Hak Akses'],
    ['Daftar & Kontak', 'Kontak Karyawan', 'Kontak Pelanggan', 'Kontak Supplier'],
    'Outlet',
    ];
    private $navlinks_headoffice = [
    'dashboard', 
    ['masterData', 'masterProduk', 'masterKategori', 'masterSatuan', 'masterRak', 'metodePembayaran'], 
    ['persediaan', 'persediaanProduk', 'defecta', 'stokKadaluwarsa', 'returStokOutlet', 'stokOpname', 'riwayatStokOpname'], 
    'pembelian', 
    'supply',
    'penjualan', 
    ['keuangan', 'bukuKas', 'utangUsaha', 'piutangUsaha'],
    ['pengguna', 'daftarPengguna', 'roleHakAkses'], 
    ['daftarKontak', 'kontakKaryawan', 'kontakPelanggan', 'kontakSupplier'],
    'outlet',
    ];
    
    // admin
    private $navigations_admin = [
    'Dashboard',
    ['Persediaan', 'Persediaan Produk', 'Defecta', 'Stok Kadaluwarsa', 'Stok Opname', 'Riwayat Stok Opname'],
    'Kasir',
    'Supply',
    'Penjualan',
    ['Keuangan', 'Buku Kas', 'Utang Usaha'],
    ['Daftar & Kontak', 'Kontak Pelanggan', 'Kontak Supplier']
    ];
    private $navlinks_admin = [
    'dashboard',
    ['persediaan', 'persediaanProduk', 'defecta', 'stokKadaluwarsa', 'stokOpname', 'riwayatStokOpname'],
    'kasir',
    'supply',
    'penjualan',
    ['keuangan', 'bukuKas', 'utangUsaha'],
    ['daftarKontak', 'kontakPelanggan', 'kontakSupplier']
    ];
    
    private $headofficeData, $adminData;

    public function __construct() {
        $this->headofficeData = [
            "navigations" => $this->navigations_headoffice,
            "navlinks" => $this->navlinks_headoffice,
            "insights" => $this->insights,
            "insightlinks" => $this->insightlinks
        ];

        $this->adminData = [
            "navigations" => $this->navigations_admin,
            "navlinks" => $this->navlinks_admin,
            "insights" => $this->insights,
            "insightlinks" => $this->insightlinks
        ];
    }

    public function headoffice($page) {
        if(!isset($_SESSION['permissions']) || $_SESSION['outlet_type'] != "Headoffice"){
            header("Location: ".BASEURL."/app/loginAdminHeadoffice");
            exit;
        }
        $data = $this->headofficeData;
        $this->template('header-headoffice', $data);

        $this->headofficeContent($page);

        $this->template('footer-headoffice', $data);
    }
    public function headofficeContent($content) {
        $data['produk'] = $this->model('ProdukModel')->getAllProduk();
        $data['kategori'] = $this->model('KategoriModel')->getAllKategori();
        $data['satuan'] = $this->model('SatuanModel')->getAllSatuan();
        $data['rak'] = $this->model('RakModel')->getAllRak();
        $data['metodePembayaran'] = $this->model('MetodePembayaranModel')->getAllMetodePembayaran();
        $data['saldoHeadoffice'] = $this->model('BukuKasModel')->getSaldoHeadoffice()['total_nominal'];
        $data['bukuKasHeadoffice'] = $this->model('BukuKasModel')->getAllBukuKasHeadoffice();
        $data['pengguna'] = $this->model('PenggunaModel')->getAllPengguna();
        $data['roles'] = $this->model('RoleHakAksesModel')->getAllRole();
        $data['kontakKaryawan'] = $this->model('KontakModel')->getAllKontakKaryawan();
        $data['kontakPelanggan'] = $this->model('KontakModel')->getAllKontakPelanggan();
        $data['kontakSupplier'] = $this->model('KontakModel')->getAllKontakSupplier();
        $data['outlet'] = $this->model('OutletModel')->getAllOutlet();
        $this->view_headoffice($content, $data);
    }

    public function admin($page) {
        if(!isset($_SESSION['permissions']) || $_SESSION['outlet_type'] != "Admin"){
            header("Location: ".BASEURL."/app/loginAdminheadOffice");
            exit;
        }
        $data = $this->adminData;
        $this->template('header-admin', $data);

        $this->adminContent($page);

        $this->template('footer-admin', $data);
    }
    public function adminContent($content) {
        $data['produk'] = $this->model('ProdukModel')->getAllProduk();
        $data['metodePembayaran'] = $this->model('MetodePembayaranModel')->getAllMetodePembayaran();
        $data['kontakPelanggan'] = $this->model('KontakModel')->getAllKontakPelanggan();
        $data['kontakSupplier'] = $this->model('KontakModel')->getAllKontakSupplier();
        $this->view_admin($content, $data);
    }

    public function loginAdminHeadoffice(){
        $this->template('loginAdminHeadoffice');
    }

    public function logout(){
        $this->template('logoutAdminHeadoffice');
    }
}