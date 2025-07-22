<?php
class ProdukModel {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }

    public function getAllProduk(){
        $this->db->query("SELECT produk.*, satuan.name AS satuan_name, kategori.name AS kategori_name, rak.name AS rak_name FROM produk JOIN satuan ON produk.main_unit = satuan.id JOIN kategori ON produk.kategori_id = kategori.id JOIN rak ON produk.rak_id = rak.id");
        return $this->db->allResult();
    }
    
    public function addProduk($data) {
        // produk
        if (isset($_FILES['gambar_produk'])) {
            $picture = $this->uploadGambarProduk();
        } else {
            $picture = "";
        }
        $query = "INSERT INTO produk (product_name, factory_name, product_code, barqode, main_unit, harga_beli, harga_supply, harga_jual, harga_resep, active_substance, min_stock, catalog, kategori_id, rak_id, picture, description) VALUES (:nama_produk, :produsen, :kode_produk, :barqode_produk, :satuan_pokok, :harga_beli, :harga_supply, :harga_jual, :harga_resep, :zat_aktif, :min_stok, :katalog, :kategori, :rak, :picture, :deskripsi)";
        $this->db->query($query);
        $this->db->bind('nama_produk', $data['nama_produk']);
        $this->db->bind('produsen', $data['produsen']);
        $this->db->bind('kode_produk', $data['kode_produk']);
        $this->db->bind('barqode_produk', $data['barqode_produk']);
        $this->db->bind('satuan_pokok', $data['satuan_pokok']);
        $this->db->bind('harga_beli', $data['harga_beli']);
        $this->db->bind('harga_supply', $data['harga_supply']);
        $this->db->bind('harga_jual', $data['harga_jual']);
        $this->db->bind('harga_resep', $data['harga_resep']);
        $this->db->bind('zat_aktif', $data['zat_aktif']);
        $this->db->bind('min_stok', $data['min_stok']);
        $this->db->bind('katalog', $data['katalog']);
        $this->db->bind('kategori', $data['kategori']);
        $this->db->bind('rak', $data['rak']);
        $this->db->bind('picture', $picture);
        $this->db->bind('deskripsi', $data['deskripsi']);
        $this->db->execute();
    
        // Dapatkan ID produk yang baru saja dimasukkan
        $idProduk = $this->db->lastInsertId();
    
        // satuan lainnya
        if (isset($data['satuan_lainnya']) && isset($data['nilai_satuan_lainnya'])) {
            $satuanLainnya = $data['satuan_lainnya'];
            $nilaiSatuanLainnya = $data['nilai_satuan_lainnya'];
    
            foreach ($satuanLainnya as $i => $sl) {
                $nsl = $nilaiSatuanLainnya[$i];
    
                $query = "INSERT INTO satuan_lainnya (produk_id, satuan_id, value) VALUES (:produk_id, :satuan_id, :value)";
                $this->db->query($query);
                $this->db->bind('produk_id', $idProduk);
                $this->db->bind('satuan_id', $sl);
                $this->db->bind('value', $nsl);
                $this->db->execute();
            }
        }
    
        return $this->db->rowCount();
    }
    
    public function uploadGambarProduk() {
        $fileName = $_FILES["gambar_produk"]["name"];
        $tmpName = $_FILES["gambar_produk"]["tmp_name"];
        $error = $_FILES["gambar_produk"]["error"];
        $fileSize = $_FILES["gambar_produk"]["size"];
    
        if ($error == 4) {
            return "file bukan gambar";
        }
    
        if ($fileSize > 100000000) { // ukuran dalam byte
            return "file lebih dari 100mb";
        }
    
        $ekstensiGambar = ['jpg', 'jpeg', 'png', 'gif']; // tambahkan ekstensi yang valid
        $ekstensiFileGambar = explode('.', $fileName);
        $ekstensiFileGambar = strtolower(end($ekstensiFileGambar));
    
        if (!in_array($ekstensiFileGambar, $ekstensiGambar)) {
            return "";
        }
    
        // Generate nama file yang unik
        do {
            $newFileName = uniqid() . '.' . $ekstensiFileGambar;
            $filePath = $_SERVER['DOCUMENT_ROOT'] . "/Apotek Berkah/assets/img/products-img/" . $newFileName;
        } while (file_exists($filePath));
    
        move_uploaded_file($tmpName, $filePath);
    
        return $newFileName; // return the new file name
    }

    public function editProduk($data){
        if (isset($_FILES['gambar_produk']) && $_FILES['gambar_produk']['error'] == 0) {
            // Hapus gambar lama
            $oldPicture = $this->getDataEditProduk($data['id'])['picture'];
            if ($oldPicture && file_exists($_SERVER['DOCUMENT_ROOT'] . "/Apotek Berkah/assets/img/products-img/" . $oldPicture)) {
                unlink($_SERVER['DOCUMENT_ROOT'] . "/Apotek Berkah/assets/img/products-img/" . $oldPicture);
            }
            // Upload gambar baru
            $picture = $this->uploadGambarProduk();
        } else if($data['katalog'] == 'Tidak tampil') {
            $picture = '';
            $oldPicture = $this->getDataEditProduk($data['id'])['picture'];
            unlink($_SERVER['DOCUMENT_ROOT'] . "/Apotek Berkah/assets/img/products-img/" . $oldPicture);
        } else {
            $picture = $this->getDataEditProduk($data['id'])['picture'];
        }

        $query = "UPDATE produk SET product_name = :nama_produk, factory_name = :produsen, product_code = :kode_produk, barqode = :barqode_produk, main_unit = :satuan_pokok, harga_beli = :harga_beli, harga_supply = :harga_supply, harga_jual = :harga_jual, harga_resep = :harga_resep, active_substance = :zat_aktif, min_stock = :min_stok, catalog = :katalog, kategori_id = :kategori, rak_id = :rak, picture = :picture, description = :deskripsi 
        WHERE id = :id";
        $this->db->query($query);
        $this->db->bind('id', $data['id']);
        $this->db->bind('nama_produk', $data['nama_produk']);
        $this->db->bind('produsen', $data['produsen']);
        $this->db->bind('kode_produk', $data['kode_produk']);
        $this->db->bind('barqode_produk', $data['barqode_produk']);
        $this->db->bind('satuan_pokok', $data['satuan_pokok']);
        $this->db->bind('harga_beli', $data['harga_beli']);
        $this->db->bind('harga_supply', $data['harga_supply']);
        $this->db->bind('harga_jual', $data['harga_jual']);
        $this->db->bind('harga_resep', $data['harga_resep']);
        $this->db->bind('zat_aktif', $data['zat_aktif']);
        $this->db->bind('min_stok', $data['min_stok']);
        $this->db->bind('katalog', $data['katalog']);
        $this->db->bind('kategori', $data['kategori']);
        $this->db->bind('rak', $data['rak']);
        $this->db->bind('picture', $picture);
        $this->db->bind('deskripsi', $data['deskripsi']);

        $this->db->execute();

        // Update satuan lainnya
        if (isset($data['satuan_lainnya']) && isset($data['nilai_satuan_lainnya'])) {
            // Hapus data satuan lainnya yang lama
            $query = "DELETE FROM satuan_lainnya WHERE produk_id = :produk_id";
            $this->db->query($query);
            $this->db->bind('produk_id', $data['id']);
            $this->db->execute();
            
            // Tambahkan data satuan lainnya yang baru
            $satuanLainnya = $data['satuan_lainnya'];
            $nilaiSatuanLainnya = $data['nilai_satuan_lainnya'];
        
            foreach ($satuanLainnya as $i => $sl) {
                $nsl = $nilaiSatuanLainnya[$i];
        
                $query = "INSERT INTO satuan_lainnya (produk_id, satuan_id, value) VALUES (:produk_id, :satuan_id, :value)";
                $this->db->query($query);
                $this->db->bind('produk_id', $data['id']);
                $this->db->bind('satuan_id', $sl);
                $this->db->bind('value', $nsl);
                $this->db->execute();
            }
        }
        
        return $this->db->rowCount();
    }

    public function deleteProduk($id){
        $produk = $this->getDataEditProduk($id);

        if($produk['picture'] && file_exists($_SERVER['DOCUMENT_ROOT'] . "/Apotek Berkah/assets/img/products-img/" . $produk['picture'])) {
            unlink($_SERVER['DOCUMENT_ROOT'] . "/Apotek Berkah/assets/img/products-img/" . $produk['picture']);
        }

        $query1 = "DELETE FROM satuan_lainnya WHERE produk_id = :id";
        $this->db->query($query1);
        $this->db->bind('id', $id);
        $this->db->execute();


        $query2 = "DELETE FROM produk WHERE id=:id";
        $this->db->query($query2);
        $this->db->bind('id', $id);
        $this->db->execute();
        
        return $this->db->rowCount();
    }

    public function deleteManyProduk($ids){
        $rowCount = 0;

        foreach ($ids as $id) {
            $rowCount += $this->deleteProduk($id);
        }

        return $rowCount;
    }

    public function getDataEditProduk($id){
        $this->db->query("SELECT produk.*, satuan.name AS satuan_name, kategori.name AS kategori_name, rak.name AS rak_name FROM produk JOIN satuan ON produk.main_unit = satuan.id JOIN kategori ON produk.kategori_id = kategori.id JOIN rak ON produk.rak_id = rak.id WHERE produk.id = :id");
        $this->db->bind('id', $id);

        return $this->db->singleResult();
    }
    public function getBarqodeProduk($id){
        $this->db->query("SELECT barqode from produk WHERE id=:id");
        $this->db->bind('id', $id);
    
        return $this->db->singleResult();
    }
    public function getDataEditProdukWithBarqode($barqode){
        $this->db->query("SELECT produk.*, satuan.name AS satuan_name, kategori.name AS kategori_name, rak.name AS rak_name FROM produk JOIN satuan ON produk.main_unit = satuan.id JOIN kategori ON produk.kategori_id = kategori.id JOIN rak ON produk.rak_id = rak.id WHERE produk.barqode = :barqode");
        $this->db->bind('barqode', $barqode);

        return $this->db->singleResult();
    }
    public function getDataEditSatuanLainnya($id){
        $this->db->query("SELECT DISTINCT satuan_lainnya.*, satuan.name AS satuan_name 
        FROM satuan_lainnya 
        JOIN satuan ON satuan.id = satuan_lainnya.satuan_id
        WHERE satuan_lainnya.produk_id = :id");
        $this->db->bind('id', $id);
        return $this->db->allResult();
    }
    public function getDataTransaksiSatuanLainnya($id){
        $this->db->query("SELECT produk.main_unit AS satuan_id, satuan.name AS satuan_name
                    FROM produk
                    JOIN satuan ON satuan.id = produk.main_unit
                    WHERE produk.id = :id");
        $this->db->bind('id', $id);
        $result = $this->db->allResult();
        $this->db->execute();

        $this->db->query("SELECT DISTINCT satuan_lainnya.*, satuan.name AS satuan_name 
        FROM satuan_lainnya 
        JOIN satuan ON satuan.id = satuan_lainnya.satuan_id
        WHERE satuan_lainnya.produk_id = :id");
        $this->db->bind('id', $id);
        $result2 = $this->db->allResult();
        if(!empty($result2)){
            foreach ($result2 as $r) {
                array_push($result, $r);
            }
        }

        return $result;
    }

    public function getProdukConfig($keyword) {
        $this->db->query("SELECT * FROM produk WHERE product_name=:keyword OR product_code=:keyword OR barqode=:keyword");
        $this->db->bind('keyword', $keyword);
        return $this->db->singleResult();
    }

    public function searchProduk($keyword){
        $keyword = $_POST['keyword'];

        $query = "SELECT produk.*, satuan.name AS satuan_name, kategori.name AS kategori_name, rak.name AS rak_name FROM produk JOIN satuan ON produk.main_unit = satuan.id JOIN kategori ON produk.kategori_id = kategori.id JOIN rak ON produk.rak_id = rak.id WHERE produk.product_name LIKE :keyword OR produk.barqode LIKE :keyword ORDER BY produk.product_name ASC";

        $this->db->query($query);
        $this->db->bind('keyword', "$keyword%");

        $this->db->execute();

        return $this->db->allResult();
    }

    public function getLastIdProduk(){
        $query = "SELECT MAX(id) as lastProdukId FROM produk";
        $this->db->query($query);

        return $this->db->singleResult();
    }

    // cek stok outlet lain
    public function getStokOutletLain($id_outlet){
        $query = "SELECT p.id AS id_produk,
               p.product_name AS nama_produk,
               SUM(CASE 
                   WHEN pp.kadaluwarsa_date > CURDATE() + INTERVAL 3 MONTH 
                    AND pp.id_outlet != :id_outlet THEN pp.amount
                   ELSE 0 
               END) AS total_amount,
               s.name AS satuan,
               o.name AS nama_outlet,
               o.address AS alamat_outlet
        FROM produk p
        LEFT JOIN persediaan_produk_outlet pp ON p.id = pp.id_produk
        LEFT JOIN satuan s ON p.main_unit = s.id
        LEFT JOIN outlet o ON pp.id_outlet = o.id
        WHERE pp.kadaluwarsa_date > CURDATE() + INTERVAL 3 MONTH
        GROUP BY p.id, p.product_name, s.name, o.id, o.name, o.address
        HAVING total_amount > 0
        ORDER BY p.id;";
        $this->db->query($query);
        $this->db->bind('id_outlet', $id_outlet);
        return $this->db->allResult();
    }

    // supply
    public function getPersediaanProdukHeadoffice($id){
        $this->db->query("SELECT pp.id_produk, SUM(pp.amount) AS total_jumlah, p.product_name
        FROM persediaan_produk_headoffice pp 
        JOIN produk p ON pp.id_produk = p.id 
        WHERE p.id = :id 
        AND pp.kadaluwarsa_date > CURDATE() + INTERVAL 3 MONTH
        GROUP BY pp.id_produk");
        $this->db->bind('id', $id);
        return $this->db->singleResult();
    }
    public function getStokPersediaanProdukHeadoffice($id){
        $this->db->query("SELECT pp.id, pp.id_produk, SUM(pp.amount) AS total_jumlah, p.product_name, pp.no_batch, pp.kadaluwarsa_date, pp.harga_beli_pokok, s.name AS satuan_name
        FROM persediaan_produk_headoffice pp 
        JOIN produk p ON pp.id_produk = p.id 
        JOIN satuan s ON p.main_unit = s.id 
        WHERE p.id = :id 
        AND pp.kadaluwarsa_date > CURDATE() + INTERVAL 3 MONTH
        GROUP BY pp.id, pp.id_produk, pp.no_batch, pp.kadaluwarsa_date, s.name
        ORDER BY pp.kadaluwarsa_date ASC;");
        $this->db->bind('id', $id);
        $this->db->execute();
        $stok = $this->db->allResult();
        
        foreach ($stok as &$s) {
            $s['kadaluwarsa_date'] = date('d-m-Y', strtotime($s['kadaluwarsa_date']));
        }

        return $stok;
    }
    public function getStokPersediaanProdukHeadofficeBarqode($barqode){
        $this->db->query("SELECT pp.id, pp.id_produk, SUM(pp.amount) AS total_jumlah, p.product_name, pp.no_batch, pp.kadaluwarsa_date, s.name AS satuan_name
        FROM persediaan_produk_headoffice pp 
        JOIN produk p ON pp.id_produk = p.id 
        JOIN satuan s ON p.main_unit = s.id 
        WHERE p.barqode = :barqode
        AND pp.kadaluwarsa_date > CURDATE() + INTERVAL 3 MONTH
        GROUP BY pp.id, pp.id_produk, pp.no_batch, pp.kadaluwarsa_date, s.name
        ORDER BY pp.kadaluwarsa_date ASC;");
        $this->db->bind('barqode', $barqode);
        $this->db->execute();
        $stok = $this->db->allResult();
        
        foreach ($stok as &$s) {
            $s['kadaluwarsa_date'] = date('d-m-Y', strtotime($s['kadaluwarsa_date']));
        }

        return $stok;
    }

    // KASIR / PENJUALAN
    public function getPersediaanProdukOutlet($id, $outletId){
        $this->db->query("SELECT pp.id_produk, SUM(pp.amount) AS total_jumlah, p.product_name 
        FROM persediaan_produk_outlet pp 
        JOIN produk p ON pp.id_produk = p.id WHERE p.id = :id AND pp.id_outlet = :outletId AND pp.kadaluwarsa_date > DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
        GROUP BY pp.id_produk");
        $this->db->bind('id', $id);
        $this->db->bind('outletId', $outletId);
        return $this->db->singleResult();
    }

    // persediaan produk 
    public function getAllPersediaanProdukHeadoffice(){
        $this->db->query("SELECT p.id AS id_produk, IFNULL(SUM(CASE WHEN pp.kadaluwarsa_date > DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN pp.amount ELSE 0 END), 0)AS total_jumlah, p.product_name, s.name AS satuan_name   
            FROM produk p
            LEFT JOIN persediaan_produk_headoffice pp ON pp.id_produk = p.id
            LEFT JOIN satuan s ON p.main_unit = s.id
            GROUP BY p.id
            ORDER BY total_jumlah DESC
        ");
        return $this->db->allResult();
    }
    public function searchAllPersediaanProdukHeadoffice($keyword){
        $this->db->query("SELECT pp.id_produk, SUM(pp.amount) AS total_jumlah, p.product_name, s.name AS satuan_name
            FROM produk p 
            JOIN persediaan_produk_headoffice pp ON p.id = pp.id_produk
            JOIN satuan s ON p.main_unit = s.id
            WHERE p.product_name LIKE :keyword AND pp.kadaluwarsa_date > DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
            GROUP BY pp.id_produk
        ");
        $this->db->bind('keyword', "%$keyword%");
        return $this->db->allResult();
    }
    
    // public function getAllPersediaanProdukOutlet(){
    //     $this->db->query("SELECT pp.id_produk, SUM(pp.amount) AS total_jumlah, p.product_name 
    //     FROM persediaan_produk_outlet pp 
    //     JOIN produk p ON pp.id_produk = p.id
    //     GROUP BY pp.id_produk");
    //     return $this->db->allResult();
    // }
    // public function searchAllPersediaanProdukOutlet($keyword){
    //     $this->db->query("SELECT pp.id_produk, SUM(pp.amount) AS total_jumlah, p.product_name 
    //         FROM persediaan_produk_outlet pp 
    //         JOIN produk p ON pp.id_produk = p.id 
    //         WHERE p.product_name LIKE :keyword
    //         GROUP BY pp.id_produk");
    //     $this->db->bind('keyword', "%$keyword%");
    //     return $this->db->allResult();
    // }
    public function getAllPersediaanProdukByIdOutlet($id) {
        $this->db->query("SELECT p.id AS id_produk, IFNULL(SUM(CASE WHEN pp.kadaluwarsa_date > DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN pp.amount ELSE 0 END), 0) AS total_jumlah, p.product_name, s.name AS satuan_name
            FROM produk p
            LEFT JOIN persediaan_produk_outlet pp ON pp.id_produk = p.id AND pp.id_outlet = :id
            LEFT JOIN satuan s ON p.main_unit = s.id
            GROUP BY p.id
            ORDER BY total_jumlah DESC;
        ");
        $this->db->bind('id', $id);
        return $this->db->allResult();
    }
    public function searchAllPersediaanProdukByIdOutlet($id, $keyword) {
        $this->db->query("SELECT pp.id_produk, SUM(pp.amount) AS total_jumlah, p.product_name, s.name AS satuan_name
            FROM persediaan_produk_outlet pp
            JOIN produk p ON pp.id_produk = p.id
            JOIN satuan s ON p.main_unit = s.id
            WHERE pp.id_outlet = :id AND p.product_name LIKE :keyword AND pp.kadaluwarsa_date > DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
            GROUP BY pp.id_produk, s.name
        ");
        $this->db->bind('id', $id);
        $this->db->bind('keyword', "%$keyword%");
        return $this->db->allResult();
    }

    // defecta
    public function getAllDefectaHeadoffice() {
        $this->db->query("SELECT 
                p.id AS id_produk, IFNULL(SUM(CASE WHEN pp.kadaluwarsa_date > DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN pp.amount ELSE 0 END), 0)AS total_jumlah, p.product_name, p.min_stock, s.name AS satuan    
            FROM produk p
            LEFT JOIN persediaan_produk_headoffice pp ON pp.id_produk = p.id
            LEFT JOIN satuan s ON p.main_unit = s.id
            GROUP BY p.id
            HAVING total_jumlah < p.min_stock
        ");
        return $this->db->allResult();
    }    
    public function searchAllDefectaHeadoffice($keyword) {
        $this->db->query("SELECT p.id AS id_produk, IFNULL(SUM(CASE WHEN pp.kadaluwarsa_date > DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN pp.amount ELSE 0 END), 0) AS total_jumlah, p.product_name, p.min_stock, s.name AS satuan
            FROM produk p
            LEFT JOIN persediaan_produk_headoffice pp ON pp.id_produk = p.id
            LEFT JOIN satuan s ON p.main_unit = s.id
            WHERE p.product_name LIKE :keyword
            GROUP BY p.id
            HAVING total_jumlah < p.min_stock
        ");
        $this->db->bind('keyword', "%$keyword%");
        return $this->db->allResult();
    }    

    // public function getAllDefectaOutlet(){
    //     $this->db->query("SELECT pp.id_produk, SUM(pp.amount) AS total_jumlah, p.product_name, p.min_stock 
    //     FROM persediaan_produk_outlet pp 
    //     JOIN produk p ON pp.id_produk = p.id
    //     GROUP BY pp.id_produk
    //     HAVING total_jumlah < p.min_stock");
    //     return $this->db->allResult();
    // }
    // public function searchAllDefectaOutlet($keyword){
    //     $this->db->query("SELECT pp.id_produk, SUM(pp.amount) AS total_jumlah, p.product_name, p.min_stock 
    //     FROM persediaan_produk_outlet pp 
    //     JOIN produk p ON pp.id_produk = p.id 
    //     WHERE p.product_name LIKE :keyword
    //     GROUP BY pp.id_produk
    //     HAVING total_jumlah < p.min_stock");
    //     $this->db->bind('keyword', "%$keyword%");
    //     return $this->db->allResult();
    // }
    public function getAllDefectaByIdOutlet($id) {
        $this->db->query("SELECT p.id AS id_produk, IFNULL(SUM(CASE WHEN pp.kadaluwarsa_date > DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN pp.amount ELSE 0 END), 0) AS total_jumlah, p.product_name, p.min_stock, s.name AS satuan
            FROM produk p
            LEFT JOIN persediaan_produk_outlet pp ON pp.id_produk = p.id AND pp.id_outlet = :id
            LEFT JOIN satuan s ON p.main_unit = s.id
            GROUP BY p.id
            HAVING total_jumlah < p.min_stock
        ");
        $this->db->bind('id', $id);
        return $this->db->allResult();
    }
    public function searchAllDefectaByIdOutlet($id, $keyword) {
        $this->db->query("SELECT p.id AS id_produk, IFNULL(SUM(CASE WHEN pp.kadaluwarsa_date > DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN pp.amount ELSE 0 END), 0) AS total_jumlah, p.product_name, p.min_stock,s.name AS satuan
            FROM produk p
            LEFT JOIN persediaan_produk_outlet pp ON pp.id_produk = p.id AND pp.id_outlet = :id
            LEFT JOIN satuan s ON p.main_unit = s.id
            WHERE p.product_name LIKE :keyword
            GROUP BY p.id
            HAVING total_jumlah < p.min_stock
        ");
        $this->db->bind('id', $id);
        $this->db->bind('keyword', "%$keyword%");
        return $this->db->allResult();
    }

    // kadaluwarsa
    public function getStokKadaluwarsaHeadoffice($rentang, $keyword) {
        $currentDate = date('Y-m-d');
        switch ($rentang) {
            case 'current':
                $condition = "persediaan_produk_headoffice.kadaluwarsa_date < '$currentDate'";
                break;
            case '3months':
                $futureDate = date('Y-m-d', strtotime('+3 months', strtotime($currentDate)));
                $condition = "persediaan_produk_headoffice.kadaluwarsa_date BETWEEN '$currentDate' AND '$futureDate'";
                break;
            case '6months':
                $futureDate = date('Y-m-d', strtotime('+6 months', strtotime($currentDate)));
                $condition = "persediaan_produk_headoffice.kadaluwarsa_date BETWEEN '$currentDate' AND '$futureDate'";
                break;
            case '1year':
                $futureDate = date('Y-m-d', strtotime('+1 year', strtotime($currentDate)));
                $condition = "persediaan_produk_headoffice.kadaluwarsa_date BETWEEN '$currentDate' AND '$futureDate'";
                break;
            default:
                return [];
        }
    
        $this->db->query("
            SELECT produk.product_name, SUM(persediaan_produk_headoffice.amount) AS total_stok 
            FROM persediaan_produk_headoffice 
            JOIN produk ON persediaan_produk_headoffice.id_produk = produk.id 
            WHERE $condition AND produk.product_name LIKE :keyword
            GROUP BY produk.id, produk.product_name
        ");
        $this->db->bind('keyword', '%' . $keyword . '%');
        return $this->db->allResult();
    }

    public function getStokKadaluwarsaOutletById($rentang, $keyword, $outlet_id) {
        $currentDate = date('Y-m-d');
        switch ($rentang) {
            case 'current':
                $condition = "persediaan_produk_outlet.kadaluwarsa_date < '$currentDate'";
                break;
            case '3months':
                $futureDate = date('Y-m-d', strtotime('+3 months', strtotime($currentDate)));
                $condition = "persediaan_produk_outlet.kadaluwarsa_date BETWEEN '$currentDate' AND '$futureDate'";
                break;
            case '6months':
                $futureDate = date('Y-m-d', strtotime('+6 months', strtotime($currentDate)));
                $condition = "persediaan_produk_outlet.kadaluwarsa_date BETWEEN '$currentDate' AND '$futureDate'";
                break;
            case '1year':
                $futureDate = date('Y-m-d', strtotime('+1 year', strtotime($currentDate)));
                $condition = "persediaan_produk_outlet.kadaluwarsa_date BETWEEN '$currentDate' AND '$futureDate'";
                break;
            default:
                return [];
        }
    
        $this->db->query("
            SELECT produk.product_name, SUM(persediaan_produk_outlet.amount) AS total_stok 
            FROM persediaan_produk_outlet 
            JOIN produk ON persediaan_produk_outlet.id_produk = produk.id 
            WHERE $condition AND produk.product_name LIKE :keyword AND persediaan_produk_outlet.id_outlet = :outlet_id
            GROUP BY produk.id, produk.product_name
        ");
        $this->db->bind('keyword', '%' . $keyword . '%');
        $this->db->bind('outlet_id', $outlet_id);
        return $this->db->allResult();
    }
    
}
