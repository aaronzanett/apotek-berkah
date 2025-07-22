<?php
class PenjualanModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }
    
    public function getAllPenjualan(){
        $this->db->query("SELECT * FROM penjualan ORDER BY datetime DESC");
        $allResult = $this->db->allResult();
        foreach ($allResult as &$result) {
            $result['datetime'] = date('d-m-Y H:i:s', strtotime($result['datetime']));
        }
        return $allResult;
    }

    public function getAllPenjualanByIdOutlet($idOutlet){
        $this->db->query("SELECT * FROM penjualan WHERE outlet_id = :idOutlet ORDER BY datetime DESC");
        $this->db->bind('idOutlet', $idOutlet);
        $allResult = $this->db->allResult();
        foreach ($allResult as &$result) {
            $result['datetime'] = date('d-m-Y H:i:s', strtotime($result['datetime']));
        }
        return $allResult;
    }

    // dashboard needed
    public function getTodayPenjualan(){
        $this->db->query("SELECT COALESCE(SUM(total_price), 0) AS penjualan_hari_ini FROM penjualan WHERE DATE(datetime) = CURDATE()");
        return $this->db->singleResult()['penjualan_hari_ini'];
    }
    public function getTodayPenjualanOutlet($outletId){
        $this->db->query("SELECT COALESCE(SUM(total_price), 0) AS penjualan_hari_ini FROM penjualan WHERE DATE(datetime) = CURDATE() AND outlet_id = :outletId");
        $this->db->bind('outletId', $outletId);
        return $this->db->singleResult()['penjualan_hari_ini'];
    }
    
    public function addPenjualan($data){
        $queryPenjualan = "INSERT INTO penjualan (outlet_id, outlet_name, cashier, subtotal, diskon, biaya_embalase, ongkos_kirim, biaya_lainnya, total_price, dibayar, kembalian, datetime, payment, faktur, note) VALUES (:outlet_id, :outlet_name, :cashier, :subtotal, :diskon, :biaya_embalase, :ongkos_kirim, :biaya_lainnya, :total_price, :dibayar, :kembalian, :datetime, :payment, :faktur, :note)";
        $kembalian = $data['bayar'] - $data['total'];
        $this->db->query($queryPenjualan);
        $this->db->bind('outlet_id', $data['outlet_id']);
        $this->db->bind('outlet_name', $data['outlet_name']);
        $this->db->bind('cashier', $data['kasir']);
        $this->db->bind('subtotal', $data['sub-total']);
        $this->db->bind('diskon', $data['diskon-total']);
        $this->db->bind('biaya_embalase', $data['biaya-embalase']);
        $this->db->bind('ongkos_kirim', $data['ongkos-kirim']);
        $this->db->bind('biaya_lainnya', $data['biaya-lainnya']);
        $this->db->bind('total_price', $data['total']);
        $this->db->bind('dibayar', $data['bayar']);
        $this->db->bind('kembalian', $kembalian);
        $this->db->bind('datetime', $data['datetime']);
        $this->db->bind('payment', $data['pembayaran']);
        $this->db->bind('faktur', $data['faktur']);
        if(isset($data['note']) && strlen($data['note'] > 0)){
            $this->db->bind('note', $data['note']);
        }else {
            $this->db->bind('note', "-");
        }
        $this->db->execute();

        $idPenjualan = $this->db->lastInsertId();

        // pengaturan printer struk
        $this->db->query("INSERT INTO printer_kasir_outlet (id_outlet, printer_name, paper_size) VALUES (:id_outlet, :printer_name, :paper_size) ON DUPLICATE KEY UPDATE printer_name = VALUES(printer_name), paper_size = VALUES(paper_size)");
        $this->db->bind('id_outlet', $data['outlet_id']);
        $this->db->bind('printer_name', $data['namaPrinter']);
        $this->db->bind('paper_size', $data['lebarKertasStruk']);
        $this->db->execute();

        if(isset($data['idProduk']) && isset($data['produkName'])){
            // detail penjualan
            foreach ($data['idProduk'] as $i => $ip) {
                $queryDetailPenjualan = "INSERT INTO detail_penjualan (penjualan_id, produk_name, harga_jual_pokok, unit_name, quantity, unit_value, jenis_harga, unit_price, diskon, subtotal) VALUES (:penjualan_id, :produk_name, :harga_jual_pokok, :unit_name, :quantity, :unit_value, :jenis_harga, :unit_price, :diskon, :subtotal)";
                $this->db->query($queryDetailPenjualan);
                $this->db->bind('penjualan_id', $idPenjualan);
                $this->db->bind('produk_name', $data['produkName'][$i]);
                $this->db->bind('harga_jual_pokok', $data['hargaJualPokok'][$i]);
                $this->db->bind('unit_name', $data['satuanName'][$i]);
                $this->db->bind('quantity', $data['kuantitas'][$i]);
                $this->db->bind('unit_value', $data['satuanPokok'][$i]);
                $this->db->bind('jenis_harga', $data['jenisHarga'][$i]);
                $this->db->bind('unit_price', $data['harga'][$i]);
                $this->db->bind('diskon', $data['diskon'][$i]);
                $this->db->bind('subtotal', $data['subtotal'][$i]);
                $this->db->execute();
            }

            // kurang persediaan produk outlet
            foreach ($data['idProduk'] as $i => $ip) {
                $stokProdukOutlet = "SELECT * FROM persediaan_produk_outlet WHERE id_produk = :idProduk AND id_outlet = :outletId AND kadaluwarsa_date > DATE_ADD(CURDATE(), INTERVAL 3 MONTH) ORDER BY kadaluwarsa_date ASC";
                $this->db->query($stokProdukOutlet);
                $this->db->bind('idProduk', $ip);
                $this->db->bind('outletId', $data['outlet_id']);
                $this->db->execute();
                $results = $this->db->allResult();
                $amount = $data['kuantitas'][$i] * $data['satuanPokok'][$i];

                foreach ($results as $row) {
                    if ($row['amount'] >= $amount) {
                        // Jika stok pada baris cukup untuk mengurangi jumlah yang dijual
                        $queryUpdate = "UPDATE persediaan_produk_outlet SET amount = amount - :amount WHERE id = :id";
                        $this->db->query($queryUpdate);
                        $this->db->bind('id', $row['id']);
                        $this->db->bind('amount', $amount);
                        $this->db->execute();
            
                        // Hapus baris jika stoknya habis
                        $queryDelete = "DELETE FROM persediaan_produk_outlet WHERE id = :id AND amount <= 0";
                        $this->db->query($queryDelete);
                        $this->db->bind('id', $row['id']);
                        $this->db->execute();
            
                        break;
                    } else {
                        // Jika stok pada baris tidak cukup untuk mengurangi jumlah yang dijual
                        $amount -= $row['amount'];
                        
                        $queryUpdate = "UPDATE persediaan_produk_outlet SET amount = 0 WHERE id = :id";
                        $this->db->query($queryUpdate);
                        $this->db->bind('id', $row['id']);
                        $this->db->execute();
                        
                        // Hapus baris karena stoknya habis
                        $queryDelete = "DELETE FROM persediaan_produk_outlet WHERE id = :id";
                        $this->db->query($queryDelete);
                        $this->db->bind('id', $row['id']);
                        $this->db->execute();
                    }
                }
            }

            // cashbook outlet
            $queryCashBook = "INSERT INTO cashbook_outlet (outlet_id, status, faktur, activity, user, datetime, total) VALUES (:outlet_id, :status, :faktur, :activity, :user, :datetime, :total)";
            $this->db->query($queryCashBook);
            $this->db->bind('outlet_id', $data['outlet_id']);
            $this->db->bind('status', 'pemasukan');
            $this->db->bind('faktur', $data['faktur']);
            $this->db->bind('activity', 'penjualan');
            $this->db->bind('user', $data['kasir']);
            $this->db->bind('datetime', $data['datetime']);
            $this->db->bind('total', $data['total']);
            $this->db->execute();
        }
        
        // direct halaman print
        if(isset($data['cetakStruk']) && $data['cetakStruk'] == 'cetakStruk'){
            $_SESSION['id_penjualan'] = 16;
            $_SESSION['ukuran_kertas'] = $data['lebarKertasStruk'];
            $_SESSION['nama_printer'] = $data['namaPrinter'];
            header('Location:'.BASEURL.'/app/admin/cetakStrukMike42');
        }else{
            header('Location:'.BASEURL.'/app/admin/kasir');
        }
    }
    
    public function getDataEditPenjualan($id){
        $this->db->query("SELECT * from penjualan WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        $penjualan = $this->db->singleResult();
        $penjualan['datetime'] = date('d-m-Y H:i:s', strtotime($penjualan['datetime']));

        $this->db->query("SELECT * from detail_penjualan WHERE penjualan_id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        $detailPenjualan = $this->db->allResult();

        return $result = [
            'penjualan' => $penjualan,
            'detail_penjualan' => $detailPenjualan
        ];
    }

    public function getPenjualanConfig($faktur) {
        $this->db->query("SELECT * FROM penjualan WHERE faktur=:faktur");
        $this->db->bind('faktur', $faktur);
        return $this->db->singleResult();
    }

    public function searchPenjualan($keyword){
        $query = "SELECT * FROM penjualan WHERE cashier LIKE :keyword OR faktur LIKE :keyword";

        $this->db->query($query);
        $this->db->bind('keyword', "%$keyword%");

        $this->db->execute();

        $penjualan = $this->db->allResult();
        foreach ($penjualan as &$s) {
            $s['datetime'] = date('d-m-Y H:i:s', strtotime($s['datetime']));
        }
        return $penjualan;
    }
    public function searchPenjualanByIdOutlet($post){
        $outlet_id = $post['outlet_id'];
        $keyword = $post['keyword'];
        $tanggal_awal = $post['tanggal_awal'];
        $tanggal_akhir = $post['tanggal_akhir'];

        $query = "SELECT * FROM penjualan WHERE (cashier LIKE :keyword OR faktur LIKE :keyword) AND outlet_id = :outlet_id";
        if($tanggal_awal != 'kosong' && $tanggal_akhir != 'kosong'){
            $query .= " AND DATE(datetime) BETWEEN :tanggal_awal AND :tanggal_akhir";
        }
        $query .= " ORDER BY datetime DESC";

        $this->db->query($query);
        $this->db->bind('keyword', "%$keyword%");
        $this->db->bind('outlet_id', "$outlet_id");
        if($tanggal_awal != 'kosong' && $tanggal_akhir != 'kosong'){
            $this->db->bind('tanggal_awal', $tanggal_awal);
            $this->db->bind('tanggal_akhir', $tanggal_akhir);
        }

        $this->db->execute();

        $penjualan = $this->db->allResult();
        foreach ($penjualan as &$s) {
            $s['datetime'] = date('d-m-Y H:i:s', strtotime($s['datetime']));
        }
        return $penjualan;
    }

    public function getDataPrinterByOutletId($outlet_id){
        $query = "SELECT * FROM printer_kasir_outlet WHERE id_outlet = :outlet_id";

        $this->db->query($query);
        $this->db->bind('outlet_id', "$outlet_id");
        return $this->db->singleResult();
    }
}
?>