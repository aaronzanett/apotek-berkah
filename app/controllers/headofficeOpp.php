<?php
class HeadofficeOpp extends Controller {
    // PRODUK
    public function addProduk(){
        if($this->model('ProdukModel')->addProduk($_POST) > 0){           
            SetAlert::alert('Berhasil Ditambahkan!', 'Berhasil menambahkan produk baru', 'success', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/masterProduk');
            exit;
        } else {            
            SetAlert::alert('Oops... Gagal Ditambahkan!', 'Terjadi suatu masalah', 'error', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/masterProduk');
            exit;
        }
    }
    public function editProduk(){
        if($this->model('ProdukModel')->editProduk($_POST) > 0){
            SetAlert::alert('Berhasil Diperbarui!', 'Berhasil memperbarui produk', 'success', 'edit');
            header('Location:'.BASEURL.'/app/headoffice/masterProduk');
            exit;
        } else {
            SetAlert::alert('Oops... Gagal Diperbarui!', 'Tidak ada data yang diperbarui', 'error', 'edit');
            header('Location:'.BASEURL.'/app/headoffice/masterProduk');
            exit;
        }
    }
    public function deleteProduk($id){
        if($this->model('ProdukModel')->deleteProduk($id) > 0){
            SetAlert::alert('Berhasil Dihapus!', 'Berhasil menghapus produk', 'success', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/masterProduk');
            exit;
        } else { 
            SetAlert::alert('Oops... Gagal Dihapus!', 'Terjadi suatu masalah', 'error', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/masterProduk');
            exit;
        }
    }
    public function deleteManyProduk(){
        if($this->model('ProdukModel')->deleteManyProduk($_POST['deletemanyproduk']) > 0){
            SetAlert::alert('Berhasil Dihapus!', 'Berhasil menghapus produk', 'success', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/masterProduk');
            exit;
        } else { 
            SetAlert::alert('Oops... Gagal Dihapus!', 'Terjadi suatu masalah', 'error', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/masterProduk');
            exit;
        }
    }
    public function getDataEditProduk(){
        echo json_encode($this->model('ProdukModel')->getDataEditProduk($_POST['id']));
    }
    public function getBarqodeProduk(){
        echo json_encode($this->model('ProdukModel')->getBarqodeProduk($_POST['id'])); 
    }
    public function getDataEditProdukWithBarqode(){
        echo json_encode($this->model('ProdukModel')->getDataEditProdukWithBarqode($_POST['barqode']));
    }
    public function getDataEditSatuanLainnya(){
        echo json_encode($this->model('ProdukModel')->getDataEditSatuanLainnya($_POST['id']));
    }
    public function getDataTransaksiSatuanLainnya(){
        echo json_encode($this->model('ProdukModel')->getDataTransaksiSatuanLainnya($_POST['id']));
    }
    public function getProdukConfig(){
        echo json_encode($this->model('ProdukModel')->getProdukConfig($_POST['configdata']));
    }
    public function searchProduk(){
        echo json_encode($this->model('ProdukModel')->searchProduk($_POST['keyword']));
    }
    public function getLastIdProduk(){
        echo json_encode($this->model('ProdukModel')->getLastIdProduk());
    }

    // persediaan & defecta
    public function searchAllPersediaanProdukHeadoffice(){
        echo json_encode($this->model('ProdukModel')->searchAllPersediaanProdukHeadoffice($_POST['keyword']));
    }
    public function searchAllDefectaHeadoffice(){
        echo json_encode($this->model('ProdukModel')->searchAllDefectaHeadoffice($_POST['keyword']));
    }

    // supply
    public function getPersediaanProdukHeadoffice(){
        echo json_encode($this->model('ProdukModel')->getPersediaanProdukHeadoffice($_POST['id']));
    }
    public function getStokPersediaanProdukHeadoffice(){
        echo json_encode($this->model('ProdukModel')->getStokPersediaanProdukHeadoffice($_POST['id']));
    }
    public function getStokPersediaanProdukHeadofficeBarqode(){
        echo json_encode($this->model('ProdukModel')->getStokPersediaanProdukHeadofficeBarqode($_POST['barqode']));
    }


    // KATEGORI
    public function addKategori(){
        if($this->model('KategoriModel')->addKategori($_POST) > 0){           
            SetAlert::alert('Berhasil Ditambahkan!', 'Berhasil menambahkan kategori baru', 'success', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/masterKategori');
            exit;
        } else {            
            SetAlert::alert('Oops... Gagal Ditambahkan!', 'Terjadi suatu masalah', 'error', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/masterKategori');
            exit;
        }
    }
    public function editKategori(){
        if($this->model('KategoriModel')->editKategori($_POST) > 0){
            SetAlert::alert('Berhasil Diperbarui!', 'Berhasil memperbarui kategori', 'success', 'edit');
            header('Location:'.BASEURL.'/app/headoffice/masterKategori');
            exit;
        } else {
            SetAlert::alert('Oops... Gagal Diperbarui!', 'Tidak ada data yang diperbarui', 'error', 'edit');
            header('Location:'.BASEURL.'/app/headoffice/masterKategori');
            exit;
        }
    }
    public function deleteKategori($id){
        $result = $this->model('KategoriModel')->deleteKategori($id);

        if ($result === false) {
            SetAlert::alert('Oops... Gagal Dihapus!', 'Kategori ini sedang digunakan', 'error', 'hapus');
        } else if ($result > 0) {
            SetAlert::alert('Berhasil Dihapus!', 'Berhasil menghapus kategori', 'success', 'hapus');
        } else { 
            SetAlert::alert('Oops... Gagal Dihapus!', 'Terjadi suatu masalah', 'error', 'hapus');
        }

        header('Location:'.BASEURL.'/app/headoffice/masterKategori');
        exit;
    }
    public function deleteManyKategori(){
        $ids = $_POST['deletemanykategori'];
        $failedDeletions = $this->model('KategoriModel')->deleteManyKategori($ids);

        if(empty($failedDeletions)){
            SetAlert::alert('Berhasil Dihapus!', 'Berhasil menghapus kategori', 'success', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/masterKategori');
            exit;
        } else if(!empty($failedDeletions)) {
            $kategoriDipakai = implode(', ', $failedDeletions);
            SetAlert::alert('Oops... Gagal Dihapus!', "Gagal menghapus kategori berikut: $kategoriDipakai. Kategori ini sedang digunakan", 'error', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/masterKategori');
        } else {
            SetAlert::alert('Oops... Gagal Dihapus!', 'Terjadi suatu masalah', 'error', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/masterKategori');
        }
    }
    public function getDataEditKategori(){
        echo json_encode($this->model('KategoriModel')->getDataEditKategori($_POST['id']));
    }
    public function getKategoriConfig(){
        echo json_encode($this->model('KategoriModel')->getKategoriConfig($_POST['name']));
    }
    public function searchKategori(){
        echo json_encode($this->model('KategoriModel')->searchKategori($_POST['keyword']));
    }

    // SATUAN
    public function addSatuan(){
        if($this->model('SatuanModel')->addSatuan($_POST) > 0){           
            SetAlert::alert('Berhasil Ditambahkan!', 'Berhasil menambahkan satuan baru', 'success', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/masterSatuan');
            exit;
        } else {            
            SetAlert::alert('Oops... Gagal Ditambahkan!', 'Terjadi suatu masalah', 'error', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/masterSatuan');
            exit;
        }
    }
    public function editSatuan(){
        if($this->model('SatuanModel')->editSatuan($_POST) > 0){
            SetAlert::alert('Berhasil Diperbarui!', 'Berhasil memperbarui satuan', 'success', 'edit');
            header('Location:'.BASEURL.'/app/headoffice/masterSatuan');
            exit;
        } else {
            SetAlert::alert('Oops... Gagal Diperbarui!', 'Tidak ada data yang diperbarui', 'error', 'edit');
            header('Location:'.BASEURL.'/app/headoffice/masterSatuan');
            exit;
        }
    }
    public function deleteSatuan($id){
        $result = $this->model('SatuanModel')->deleteSatuan($id);

        if ($result === false) {
            SetAlert::alert('Oops... Gagal Dihapus!', 'Satuan ini sedang digunakan', 'error', 'hapus');
        } else if ($result > 0) {
            SetAlert::alert('Berhasil Dihapus!', 'Berhasil menghapus satuan', 'success', 'hapus');
        } else { 
            SetAlert::alert('Oops... Gagal Dihapus!', 'Terjadi suatu masalah', 'error', 'hapus');
        }

        header('Location:'.BASEURL.'/app/headoffice/masterSatuan');
        exit;
    }
    public function deleteManySatuan(){
        $ids = $_POST['deletemanysatuan'];
        $failedDeletions = $this->model('SatuanModel')->deleteManySatuan($ids);

        if(empty($failedDeletions)){
            SetAlert::alert('Berhasil Dihapus!', 'Berhasil menghapus satuan', 'success', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/masterSatuan');
            exit;
        } else if(!empty($failedDeletions)) {
            $satuanDipakai = implode(', ', $failedDeletions);
            SetAlert::alert('Oops... Gagal Dihapus!', "Gagal menghapus satuan berikut: $satuanDipakai. Satuan ini sedang digunakan", 'error', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/masterSatuan');
        } else {
            SetAlert::alert('Oops... Gagal Dihapus!', 'Terjadi suatu masalah', 'error', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/masterSatuan');
        }
    }
    public function getDataEditSatuan(){
        echo json_encode($this->model('SatuanModel')->getDataEditSatuan($_POST['id']));
    }
    public function getSatuanConfig(){
        echo json_encode($this->model('SatuanModel')->getSatuanConfig($_POST['name']));
    }
    public function searchSatuan(){
        echo json_encode($this->model('SatuanModel')->searchSatuan($_POST['keyword']));
    }

    // RAK
    public function addRak(){
        if($this->model('RakModel')->addRak($_POST) > 0){           
            SetAlert::alert('Berhasil Ditambahkan!', 'Berhasil menambahkan rak baru', 'success', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/masterRak');
            exit;
        } else {            
            SetAlert::alert('Oops... Gagal Ditambahkan!', 'Terjadi suatu masalah', 'error', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/masterRak');
            exit;
        }
    }
    public function editRak(){
        if($this->model('RakModel')->editRak($_POST) > 0){
            SetAlert::alert('Berhasil Diperbarui!', 'Berhasil memperbarui rak', 'success', 'edit');
            header('Location:'.BASEURL.'/app/headoffice/masterRak');
            exit;
        } else {
            SetAlert::alert('Oops... Gagal Diperbarui!', 'Tidak ada data yang diperbarui', 'error', 'edit');
            header('Location:'.BASEURL.'/app/headoffice/masterRak');
            exit;
        }
    }
    public function deleteRak($id){
        $result = $this->model('RakModel')->deleteRak($id);

        if ($result === false) {
            SetAlert::alert('Oops... Gagal Dihapus!', 'Rak ini sedang digunakan', 'error', 'hapus');
        } else if ($result > 0) {
            SetAlert::alert('Berhasil Dihapus!', 'Berhasil menghapus rak', 'success', 'hapus');
        } else { 
            SetAlert::alert('Oops... Gagal Dihapus!', 'Terjadi suatu masalah', 'error', 'hapus');
        }

        header('Location:'.BASEURL.'/app/headoffice/masterRak');
        exit;
    }
    public function deleteManyRak(){
        $ids = $_POST['deletemanyrak'];
        $failedDeletions = $this->model('RakModel')->deleteManyRak($ids);

        if(empty($failedDeletions)){
            SetAlert::alert('Berhasil Dihapus!', 'Berhasil menghapus rak', 'success', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/masterRak');
            exit;
        } else if(!empty($failedDeletions)) {
            $rakDipakai = implode(', ', $failedDeletions);
            SetAlert::alert('Oops... Gagal Dihapus!', "Gagal menghapus rak berikut: $rakDipakai. Rak ini sedang digunakan", 'error', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/masterRak');
        } else {
            SetAlert::alert('Oops... Gagal Dihapus!', 'Terjadi suatu masalah', 'error', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/masterRak');
        }
    }
    public function getDataEditRak(){
        echo json_encode($this->model('RakModel')->getDataEditRak($_POST['id']));
    }
    public function getRakConfig(){
        echo json_encode($this->model('RakModel')->getRakConfig($_POST['name']));
    }
    public function searchRak(){
        echo json_encode($this->model('RakModel')->searchRak($_POST['keyword']));
    }

    // METODE PEMBAYARAN
    public function addMetodePembayaran(){
        if($this->model('MetodePembayaranModel')->addMetodePembayaran($_POST) > 0){           
            SetAlert::alert('Berhasil Ditambahkan!', 'Berhasil menambahkan metode pembayaran baru', 'success', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/metodePembayaran');
            exit;
        } else {            
            SetAlert::alert('Oops... Gagal Ditambahkan!', 'Terjadi suatu masalah', 'error', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/metodePembayaran');
            exit;
        }
    }
    public function editMetodePembayaran(){
        if($this->model('MetodePembayaranModel')->editMetodePembayaran($_POST) > 0){
            SetAlert::alert('Berhasil Diperbarui!', 'Berhasil memperbarui metode pembayaran', 'success', 'edit');
            header('Location:'.BASEURL.'/app/headoffice/metodePembayaran');
            exit;
        } else {
            SetAlert::alert('Oops... Gagal Diperbarui!', 'Tidak ada data yang diperbarui', 'error', 'edit');
            header('Location:'.BASEURL.'/app/headoffice/metodePembayaran');
            exit;
        }
    }
    public function deleteMetodePembayaran($id){
        if($this->model('MetodePembayaranModel')->deleteMetodePembayaran($id) > 0){
            SetAlert::alert('Berhasil Dihapus!', 'Berhasil menghapus metode pembayaran', 'success', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/metodePembayaran');
            exit;
        } else { 
            SetAlert::alert('Oops... Gagal Dihapus!', 'Terjadi suatu masalah', 'error', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/metodePembayaran');
            exit;
        }
    }
    public function deleteManyMetodePembayaran(){
        if($this->model('MetodePembayaranModel')->deleteManyMetodePembayaran($_POST['deletemanymetodepembayaran']) > 0){
            SetAlert::alert('Berhasil Dihapus!', 'Berhasil menghapus metode pembayaran', 'success', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/metodePembayaran');
            exit;
        } else { 
            SetAlert::alert('Oops... Gagal Dihapus!', 'Terjadi suatu masalah', 'error', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/metodePembayaran');
            exit;
        }
    }
    public function getDataEditMetodePembayaran(){
        echo json_encode($this->model('MetodePembayaranModel')->getDataEditMetodePembayaran($_POST['id']));
    }
    public function getMetodePembayaranConfig(){
        echo json_encode($this->model('MetodePembayaranModel')->getMetodePembayaranConfig($_POST['name']));
    }
    public function searchMetodePembayaran(){
        echo json_encode($this->model('MetodePembayaranModel')->searchMetodePembayaran($_POST['keyword']));
    }

    // KADALUWARSA
    public function getStokKadaluwarsaHeadoffice(){
        echo json_encode($this->model('ProdukModel')->getStokKadaluwarsaHeadoffice($_POST['rentang'], $_POST['keyword']));
    }

    // PEMBELIAN
    public function addPembelian(){
        if($this->model('PembelianModel')->addPembelian($_POST) > 0){           
            SetAlert::alert('Berhasil Ditambahkan!', 'Berhasil menambahkan pembelian baru', 'success', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/pembelian');
            exit;
        } else {            
            SetAlert::alert('Oops... Gagal Ditambahkan!', 'Terjadi suatu masalah', 'error', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/pembelian');
            exit;
        }
    }
    public function getDataEditPembelian(){
        echo json_encode($this->model('PembelianModel')->getDataEditPembelian($_POST['id']));
    }
    public function getPembelianConfig(){
        echo json_encode($this->model('PembelianModel')->getPembelianConfig($_POST['configdata']));
    }
    public function searchPembelian(){
        echo json_encode($this->model('PembelianModel')->searchPembelian($_POST['keyword']));
    }

    // SUPPLY
    public function addSupply(){
        if($this->model('SupplyModel')->addSupply($_POST) > 0){           
            SetAlert::alert('Berhasil Ditambahkan!', 'Berhasil menambahkan supply baru', 'success', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/supply');
            exit;
        } else {            
            SetAlert::alert('Oops... Gagal Ditambahkan!', 'Terjadi suatu masalah', 'error', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/supply');
            exit;
        }
    }
    public function getDataEditSupply(){
        echo json_encode($this->model('SupplyModel')->getDataEditSupply($_POST['id']));
    }
    public function getSupplyConfig(){
        echo json_encode($this->model('SupplyModel')->getSupplyConfig($_POST['configdata']));
    }
    public function searchSupply(){
        echo json_encode($this->model('SupplyModel')->searchSupply($_POST['keyword']));
    }
    public function searchSupplyByIdOutlet(){
        echo json_encode($this->model('SupplyModel')->searchSupplyByIdOutlet($_POST['outlet_id'], $_POST['keyword']));
    }

    // BUKU KAS
    public function addBukuKas(){
        if($this->model('BukuKasModel')->addBukuKas($_POST) > 0){           
            SetAlert::alert('Berhasil Ditambahkan!', 'Berhasil menambahkan transaksi baru', 'success', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/bukuKas');
            exit;
        } else {            
            SetAlert::alert('Oops... Gagal Ditambahkan!', 'Terjadi suatu masalah', 'error', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/bukuKas');
            exit;
        }
    }
    public function editBukuKas(){
        if($this->model('BukuKasModel')->editBukuKas($_POST) > 0){
            SetAlert::alert('Berhasil Diperbarui!', 'Berhasil memperbarui transaksi', 'success', 'edit');
            header('Location:'.BASEURL.'/app/headoffice/bukuKas');
            exit;
        } else {
            SetAlert::alert('Oops... Gagal Diperbarui!', 'Tidak ada data yang diperbarui', 'error', 'edit');
            header('Location:'.BASEURL.'/app/headoffice/bukuKas');
            exit;
        }
    }
    public function deleteBukuKas($id){
        if($this->model('BukuKasModel')->deleteBukuKas($id) > 0){
            SetAlert::alert('Berhasil Dihapus!', 'Berhasil menghapus transaksi', 'success', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/bukuKas');
            exit;
        } else { 
            SetAlert::alert('Oops... Gagal Dihapus!', 'Terjadi suatu masalah', 'error', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/bukuKas');
            exit;
        }
    }
    public function getDataEditBukuKas(){
        echo json_encode($this->model('BukuKasModel')->getDataEditBukuKas($_POST['id']));
    }
    public function searchBukuKas(){
        echo json_encode($this->model('BukuKasModel')->searchBukuKas($_POST['keyword']));
    }

    // UTANG
    public function bayarUtang(){
        if($this->model('UtangPiutang')->bayarUtang($_POST) > 0){           
            SetAlert::alert('Berhasil Dibayar!', 'Berhasil membayar utang', 'success', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/utangUsaha');
            exit;
        } else {            
            SetAlert::alert('Oops... Gagal Dibayar!', 'Terjadi suatu masalah', 'error', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/utangUsaha');
            exit;
        }
    }
    // PIUTANG
    public function bayarPiutang(){
        if($this->model('UtangPiutang')->bayarPiutang($_POST) > 0){           
            SetAlert::alert('Berhasil Dibayar!', 'Piutang berhasil dibayar', 'success', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/piutangUsaha');
            exit;
        } else {
            SetAlert::alert('Oops... Gagal Dibayar!', 'Terjadi suatu masalah', 'error', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/piutangUsaha');
            exit;
        }
    }

    // PENGGUNA
    public function addPengguna(){
        if($this->model('PenggunaModel')->addPengguna($_POST) > 0){           
            SetAlert::alert('Berhasil Ditambahkan!', 'Berhasil menambahkan pengguna baru', 'success', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/daftarPengguna');
            exit;
        } else {            
            SetAlert::alert('Oops... Gagal Ditambahkan!', 'Terjadi suatu masalah', 'error', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/daftarPengguna');
            exit;
        }
    }
    public function editPengguna(){
        if($this->model('PenggunaModel')->editPengguna($_POST) > 0){
            SetAlert::alert('Berhasil Diperbarui!', 'Berhasil memperbarui pengguna', 'success', 'edit');
            header('Location:'.BASEURL.'/app/headoffice/daftarPengguna');
            exit;
        } else {
            SetAlert::alert('Oops... Gagal Diperbarui!', 'Tidak ada data yang diperbarui', 'error', 'edit');
            header('Location:'.BASEURL.'/app/headoffice/daftarPengguna');
            exit;
        }
    }
    public function deletePengguna($id){
        if($this->model('PenggunaModel')->deletePengguna($id) > 0){
            SetAlert::alert('Berhasil Dihapus!', 'Berhasil menghapus pengguna', 'success', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/daftarPengguna');
            exit;
        } else { 
            SetAlert::alert('Oops... Gagal Dihapus!', 'Terjadi suatu masalah', 'error', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/daftarPengguna');
            exit;
        }
    }
    public function deleteManyPengguna(){
        if($this->model('PenggunaModel')->deleteManyPengguna($_POST['deletemanypengguna']) > 0){
            SetAlert::alert('Berhasil Dihapus!', 'Berhasil menghapus pengguna', 'success', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/daftarPengguna');
            exit;
        } else { 
            SetAlert::alert('Oops... Gagal Dihapus!', 'Terjadi suatu masalah', 'error', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/daftarPengguna');
            exit;
        }
    }
    public function getDataEditPengguna(){
        echo json_encode($this->model('PenggunaModel')->getDataEditPengguna($_POST['idPengguna'], $_POST['idKaryawan'], $_POST['idOutlet'], $_POST['idRole']));
    }
    public function getPenggunaConfig(){
        echo json_encode($this->model('PenggunaModel')->getPenggunaConfig($_POST['username']));
    }
    public function searchPengguna(){
        echo json_encode($this->model('PenggunaModel')->searchPengguna($_POST['keyword']));
    }

    // ROLE & HAK AKSES
    public function addRole(){
        if (isset($_POST['headofficePermissions']) || isset($_POST['adminPermissions'])) {
            if($this->model('RoleHakAksesModel')->addRolePermission($_POST) > 0){           
                SetAlert::alert('Berhasil Ditambahkan!', 'Berhasil menambahkan role baru', 'success', 'tambah');
                header('Location:'.BASEURL.'/app/headoffice/roleHakAkses');
                exit;
            } else {            
                SetAlert::alert('Oops... Gagal Ditambahkan!', 'Terjadi suatu masalah', 'error', 'tambah');
                header('Location:'.BASEURL.'/app/headoffice/roleHakAkses');
                exit;
            }
        } else if (!isset($_POST['headofficePermissions']) || !isset($_POST['adminPermissions'])) {
            SetAlert::alert('Oops... Gagal Ditambahkan!', 'Tolong check / centang hak akses halaman', 'error', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/roleHakAkses');
            exit;
        }
    }
    public function editRole(){
        if (is_array($_POST['headofficePermissions']) || is_array($_POST['adminPermissions'])) {
            if($this->model('RoleHakAksesModel')->editRolePermission($_POST) > 0){           
                SetAlert::alert('Berhasil Diperbarui!', 'Berhasil memperbarui role', 'success', 'edit');
                header('Location:'.BASEURL.'/app/headoffice/roleHakAkses');
                exit;
            } else {            
                SetAlert::alert('Oops... Gagal Diperbarui!', 'Terjadi suatu masalah', 'error', 'edit');
                header('Location:'.BASEURL.'/app/headoffice/roleHakAkses');
                exit;
            }
        } else if (!isset($_POST['headofficePermissions']) || !isset($_POST['adminPermissions'])) {
            SetAlert::alert('Oops... Gagal Diperbarui!', 'Tolong check / centang hak akses halaman', 'error', 'edit');
            header('Location:'.BASEURL.'/app/headoffice/roleHakAkses');
            exit;
        }
    }
    public function deleteRole($id){
        if($this->model('RoleHakAksesModel')->deleteRolePermission($id) > 0){           
            SetAlert::alert('Berhasil Dihapus!', 'Berhasil menghapus role', 'success', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/roleHakAkses');
            exit;
        } else {            
            SetAlert::alert('Oops... Gagal Dihapus!', 'Terjadi suatu masalah', 'error', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/roleHakAkses');
            exit;
        }
    }
    public function deleteManyRole(){
        if($this->model('RoleHakAksesModel')->deleteManyRolePermission($_POST['deletemanyroles']) > 0){
            SetAlert::alert('Berhasil Dihapus!', 'Berhasil menghapus kontak karyawan', 'success', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/roleHakAkses');
            exit;
        } else { 
            SetAlert::alert('Oops... Gagal Dihapus!', 'Terjadi suatu masalah', 'error', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/roleHakAkses');
            exit;
        }
    }
    public function getDataEditRole(){
        echo json_encode($this->model('RoleHakAksesModel')->getDataEditRole($_POST['id'], $_POST['outletType']));
    }
    public function getRoleConfig(){
        echo json_encode($this->model('RoleHakAksesModel')->getRoleConfig($_POST['name']));
    }
    public function searchRole(){
        echo json_encode($this->model('RoleHakAksesModel')->searchRole($_POST['keyword']));
    }

    // KONTAK KARYAWAN
    public function addKontakKaryawan(){
        if($this->model('KontakModel')->addKontakKaryawan($_POST) > 0){           
            SetAlert::alert('Berhasil Ditambahkan!', 'Berhasil menambahkan kontak karyawan baru', 'success', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/kontakKaryawan');
            exit;
        } else {            
            SetAlert::alert('Oops... Gagal Ditambahkan!', 'Terjadi suatu masalah', 'error', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/kontakKaryawan');
            exit;
        }
    }
    public function editKontakKaryawan(){
        if($this->model('KontakModel')->editKontakKaryawan($_POST) > 0){
            SetAlert::alert('Berhasil Diperbarui!', 'Berhasil memperbarui kontak karyawan', 'success', 'edit');
            header('Location:'.BASEURL.'/app/headoffice/kontakKaryawan');
            exit;
        } else {
            SetAlert::alert('Oops... Gagal Diperbarui!', 'Tidak ada data yang diperbarui', 'error', 'edit');
            header('Location:'.BASEURL.'/app/headoffice/kontakKaryawan');
            exit;
        }
    }
    public function deleteKontakKaryawan($id){
        if($this->model('kontakModel')->deleteKontakKaryawan($id) > 0){
            SetAlert::alert('Berhasil Dihapus!', 'Berhasil menghapus kontak karyawan', 'success', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/kontakKaryawan');
            exit;
        } else { 
            SetAlert::alert('Oops... Gagal Dihapus!', 'Terjadi suatu masalah', 'error', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/kontakKaryawan');
            exit;
        }
    }
    public function deleteManyKontakKaryawan(){
        if($this->model('kontakModel')->deleteManyKontakKaryawan($_POST['deletemanycontacts']) > 0){
            SetAlert::alert('Berhasil Dihapus!', 'Berhasil menghapus kontak karyawan', 'success', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/kontakKaryawan');
            exit;
        } else { 
            SetAlert::alert('Oops... Gagal Dihapus!', 'Terjadi suatu masalah', 'error', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/kontakKaryawan');
            exit;
        }
    }
    public function getDataEditKontakKaryawan(){
        echo json_encode($this->model('KontakModel')->getDataEditKontakKaryawan($_POST['id']));
    }
    public function getKontakKaryawanConfig(){
        echo json_encode($this->model('KontakModel')->getKontakKaryawanConfig($_POST['phonenumber']));
    }
    public function searchKontakKaryawan(){
        echo json_encode($this->model('KontakModel')->searchKontakKaryawan($_POST['keyword']));
    }

    // KONTAK PELANGGAN
    public function addKontakPelanggan(){
        if($this->model('KontakModel')->addKontakPelanggan($_POST) > 0){           
            SetAlert::alert('Berhasil Ditambahkan!', 'Berhasil menambahkan kontak pelanggan baru', 'success', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/kontakPelanggan');
            exit;
        } else {            
            SetAlert::alert('Oops... Gagal Ditambahkan!', 'Terjadi suatu masalah', 'error', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/kontakPelanggan');
            exit;
        }
    }
    public function editKontakPelanggan(){
        if($this->model('KontakModel')->editKontakPelanggan($_POST) > 0){
            SetAlert::alert('Berhasil Diperbarui!', 'Berhasil memperbarui kontak pelanggan', 'success', 'edit');
            header('Location:'.BASEURL.'/app/headoffice/kontakPelanggan');
            exit;
        } else {
            SetAlert::alert('Oops... Gagal Diperbarui!', 'Tidak ada data yang diperbarui', 'error', 'edit');
            header('Location:'.BASEURL.'/app/headoffice/kontakPelanggan');
            exit;
        }
    }
    public function deleteKontakPelanggan($id){
        if($this->model('kontakModel')->deleteKontakPelanggan($id) > 0){
            SetAlert::alert('Berhasil Dihapus!', 'Berhasil menghapus kontak pelanggan', 'success', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/kontakPelanggan');
            exit;
        } else { 
            SetAlert::alert('Oops... Gagal Dihapus!', 'Terjadi suatu masalah', 'error', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/kontakPelanggan');
            exit;
        }
    }
    public function deleteManyKontakPelanggan(){
        if($this->model('kontakModel')->deleteManyKontakPelanggan($_POST['deletemanycontacts']) > 0){
            SetAlert::alert('Berhasil Dihapus!', 'Berhasil menghapus kontak pelanggan', 'success', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/kontakPelanggan');
            exit;
        } else { 
            SetAlert::alert('Oops... Gagal Dihapus!', 'Terjadi suatu masalah', 'error', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/kontakPelanggan');
            exit;
        }
    }
    public function getDataEditKontakPelanggan(){
        echo json_encode($this->model('KontakModel')->getDataEditKontakPelanggan($_POST['id']));
    }
    public function getKontakPelangganConfig(){
        echo json_encode($this->model('KontakModel')->getKontakPelangganConfig($_POST['phonenumber']));
    }
    public function searchKontakPelanggan(){
        echo json_encode($this->model('KontakModel')->searchKontakPelanggan($_POST['keyword']));
    }

    // KONTAK SUPPLIER
    public function addKontakSupplier(){
        if($this->model('KontakModel')->addKontakSupplier($_POST) > 0){           
            SetAlert::alert('Berhasil Ditambahkan!', 'Berhasil menambahkan kontak supplier baru', 'success', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/kontakSupplier');
            exit;
        } else {            
            SetAlert::alert('Oops... Gagal Ditambahkan!', 'Terjadi suatu masalah', 'error', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/kontakSupplier');
            exit;
        }
    }
    public function editKontakSupplier(){
        if($this->model('KontakModel')->editKontakSupplier($_POST) > 0){
            SetAlert::alert('Berhasil Diperbarui!', 'Berhasil memperbarui kontak supplier', 'success', 'edit');
            header('Location:'.BASEURL.'/app/headoffice/kontakSupplier');
            exit;
        } else {
            SetAlert::alert('Oops... Gagal Diperbarui!', 'Tidak ada data yang diperbarui', 'error', 'edit');
            header('Location:'.BASEURL.'/app/headoffice/kontakSupplier');
            exit;
        }
    }
    public function deleteKontakSupplier($id){
        if($this->model('kontakModel')->deleteKontakSupplier($id) > 0){
            SetAlert::alert('Berhasil Dihapus!', 'Berhasil menghapus kontak supplier', 'success', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/kontakSupplier');
            exit;
        } else { 
            SetAlert::alert('Oops... Gagal Dihapus!', 'Terjadi suatu masalah', 'error', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/kontakSupplier');
            exit;
        }
    }
    public function deleteManyKontakSupplier(){
        if($this->model('kontakModel')->deleteManyKontakSupplier($_POST['deletemanycontacts']) > 0){
            SetAlert::alert('Berhasil Dihapus!', 'Berhasil menghapus kontak supplier', 'success', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/kontakSupplier');
            exit;
        } else { 
            SetAlert::alert('Oops... Gagal Dihapus!', 'Terjadi suatu masalah', 'error', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/kontakSupplier');
            exit;
        }
    }
    public function getDataEditKontakSupplier(){
        echo json_encode($this->model('KontakModel')->getDataEditKontakSupplier($_POST['id']));
    }
    public function getKontakSupplierConfig(){
        echo json_encode($this->model('KontakModel')->getKontakSupplierConfig($_POST['phonenumber']));
    }
    public function searchKontakSupplier(){
        echo json_encode($this->model('KontakModel')->searchKontakSupplier($_POST['keyword']));
    }

    // OUTLET
    public function addOutlet(){
        if($this->model('OutletModel')->addOutlet($_POST) > 0){           
            SetAlert::alert('Berhasil Ditambahkan!', 'Berhasil menambahkan outlet baru', 'success', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/outlet');
            exit;
        } else {            
            SetAlert::alert('Oops... Gagal Ditambahkan!', 'Terjadi suatu masalah', 'error', 'tambah');
            header('Location:'.BASEURL.'/app/headoffice/outlet');
            exit;
        }
    }
    public function editOutlet(){
        if($this->model('OutletModel')->editOutlet($_POST) > 0){
            SetAlert::alert('Berhasil Diperbarui!', 'Berhasil memperbarui outlet', 'success', 'edit');
            header('Location:'.BASEURL.'/app/headoffice/outlet');
            exit;
        } else {
            SetAlert::alert('Oops... Gagal Diperbarui!', 'Tidak ada data yang diperbarui', 'error', 'edit');
            header('Location:'.BASEURL.'/app/headoffice/outlet');
            exit;
        }
    }
    public function deleteOutlet($id){
        if($this->model('OutletModel')->deleteOutlet($id) > 0){
            SetAlert::alert('Berhasil Dihapus!', 'Berhasil menghapus outlet', 'success', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/outlet');
            exit;
        } else { 
            SetAlert::alert('Oops... Gagal Dihapus!', 'Terjadi suatu masalah', 'error', 'hapus');
            header('Location:'.BASEURL.'/app/headoffice/outlet');
            exit;
        }
    }
    public function getDataEditOutlet(){
        echo json_encode($this->model('OutletModel')->getDataEditOutlet($_POST['id']));
    }
    public function getOutletConfig(){
        echo json_encode($this->model('OutletModel')->getOutletConfig($_POST['serial_number']));
    }
    public function searchOutlet(){
        echo json_encode($this->model('OutletModel')->searchOutlet($_POST['keyword']));
    }

}