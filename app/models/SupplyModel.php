<?php
class SupplyModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }
    
    public function getAllSupply(){
        $this->db->query("SELECT * FROM supply ORDER BY datetime DESC");
        $allResult = $this->db->allResult();
        foreach ($allResult as &$result) {
            $result['datetime'] = date('d-m-Y H:i:s', strtotime($result['datetime']));
        }
        return $allResult;
    }

    public function getAllSupplyByIdOutlet($idOutlet){
        $this->db->query("SELECT * FROM supply WHERE outlet_id = :idOutlet ORDER BY datetime DESC");
        $this->db->bind('idOutlet', $idOutlet);
        $allResult = $this->db->allResult();
        foreach ($allResult as &$result) {
            $result['datetime'] = date('d-m-Y H:i:s', strtotime($result['datetime']));
        }
        return $allResult;
    }
    
    public function addSupply($data){
        $querySupply = "INSERT INTO supply (id, outlet_id, outlet_name, orderer, headofficer, total_price, dibayar, piutang, datetime, payment, faktur, note) VALUES ('', :outlet_id, :outlet_name, :orderer, :headofficer, :total_price, :dibayar, :piutang, :datetime, :payment, :faktur, :note)";
        $this->db->query($querySupply);
        $this->db->bind('outlet_id', $data['outlet_id']);
        $this->db->bind('outlet_name', $data['outlet_name']);
        $this->db->bind('orderer', $data['pemesan']);
        $this->db->bind('headofficer', $data['headofficer']);
        $this->db->bind('total_price', $data['total']);
        $this->db->bind('dibayar', $data['bayar']);
        $this->db->bind('piutang', $data['piutang']);
        $this->db->bind('datetime', $data['datetime']);
        $this->db->bind('payment', $data['pembayaran']);
        $this->db->bind('faktur', $data['faktur']);
        if(isset($data['note']) && strlen($data['note'] > 0)){
            $this->db->bind('note', $data['note']);
        }else {
            $this->db->bind('note', "-");
        }
        $this->db->execute();
        $rowcount = $this->db->rowCount();

        $idSupply = $this->db->lastInsertId();

        // piutang
        if($data['piutang'] > 0){
            $queryPiutang = "INSERT INTO piutang (id, outlet_id, name, faktur, tagihan_awal, telah_dibayar, total_sisa, jatuh_tempo) VALUES ('', :outlet_id, 'supply', :faktur, :piutang, '', :piutang, :jatuh_tempo)";
            $this->db->query($queryPiutang);
            $this->db->bind('outlet_id', $data['outlet_id']);
            $this->db->bind('faktur', $data['faktur']);
            $this->db->bind('piutang', $data['piutang']);
            $this->db->bind('jatuh_tempo', $data['jatuhTempo']);
            $this->db->execute();
        }

        if(isset($data['idProduk']) && isset($data['produkName'])){
            foreach ($data['kadaluwarsa'] as $i => $k) {
                $data['kadaluwarsa'][$i] = date('Y-m-d', strtotime($k));
            }

            // detail supply
            foreach ($data['idProduk'] as $i => $ip) {
                $queryDetailSupply = "INSERT INTO detail_supply (id, supply_id, produk_name, supply_price, unit_name, quantity, unit_value, unit_price, kadaluwarsa, subtotal) VALUES ('', :supply_id, :produk_name, :supply_price, :unit_name, :quantity, :unit_value, :unit_price, :kadaluwarsa, :subtotal)";
                $hargaSatuan = $data['hargaSupply'][$i] * $data['satuanPokok'][$i];
                $this->db->query($queryDetailSupply);
                $this->db->bind('supply_id', $idSupply);
                $this->db->bind('produk_name', $data['produkName'][$i]);
                $this->db->bind('supply_price', $data['hargaSupply'][$i]);
                $this->db->bind('unit_name', $data['satuanName'][$i]);
                $this->db->bind('quantity', $data['kuantitas'][$i]);
                $this->db->bind('unit_value', $data['satuanPokok'][$i]);
                $this->db->bind('unit_price', $hargaSatuan);
                $this->db->bind('kadaluwarsa', $data['kadaluwarsa'][$i]);
                $this->db->bind('subtotal', $data['subtotal'][$i]);
                $this->db->execute();
            }

            // kurang persediaan produk headoffice
            foreach ($data['idProduk'] as $i => $ip) {
                $amount = $data['kuantitas'][$i] * $data['satuanPokok'][$i];
                $queryUpdatePP = "UPDATE persediaan_produk_headoffice SET amount = amount - :amount WHERE id = :idpp";
                $this->db->query($queryUpdatePP);
                $this->db->bind('amount', $amount);
                $this->db->bind('idpp', $data['idpp'][$i]);
                $this->db->execute();

                $queryDeleteEmptyPP = "DELETE FROM persediaan_produk_headoffice WHERE id = :idpp AND amount <= 0";
                $this->db->query($queryDeleteEmptyPP);
                $this->db->bind('idpp', $data['idpp'][$i]);
                $this->db->execute();
            }

            // tambah persediaan produk outlet
            foreach ($data['idProduk'] as $i => $ip) {
                $amount = $data['kuantitas'][$i] * $data['satuanPokok'][$i];

                $queryPersediaanProduk = "INSERT INTO persediaan_produk_outlet (id, id_produk, id_outlet, amount, kadaluwarsa_date) VALUES ('', :id_produk, :id_outlet, :amount, :kadaluwarsa_date)";
                $this->db->query($queryPersediaanProduk);
                $this->db->bind('id_produk', $ip);
                $this->db->bind('id_outlet', $data['outlet_id']);
                $this->db->bind('amount', $amount);
                $this->db->bind('kadaluwarsa_date', $data['kadaluwarsa'][$i]);
                $this->db->execute();
            }

            // cashbook headoffice
            $queryCashBook = "INSERT INTO cashbook_headoffice (id, status, faktur, activity, user, datetime, total) VALUES ('', :status, :faktur, :activity, :user, :datetime, :total)";
            $this->db->query($queryCashBook);
            $this->db->bind('status', 'pemasukan');
            $this->db->bind('faktur', $data['faktur']);
            $this->db->bind('activity', 'supply');
            $this->db->bind('user', $data['headofficer']);
            $this->db->bind('datetime', $data['datetime']);
            $this->db->bind('total', $data['total']);
            $this->db->execute();

            // cashbook outlet
            $queryCashBook = "INSERT INTO cashbook_outlet (id, outlet_id, status, faktur, activity, user, datetime, total) VALUES ('', :outlet_id, :status, :faktur, :activity, :user, :datetime, :total)";
            $this->db->query($queryCashBook);
            $this->db->bind('outlet_id', $data['outlet_id']);
            $this->db->bind('status', 'pengeluaran');
            $this->db->bind('faktur', $data['faktur']);
            $this->db->bind('activity', 'supply');
            $this->db->bind('user', $data['headofficer']);
            $this->db->bind('datetime', $data['datetime']);
            $this->db->bind('total', $data['total']);
            $this->db->execute();
        }

        
        return $rowcount;
    }
    
    public function getDataEditSupply($id){
        $this->db->query("SELECT * from supply WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        $supply = $this->db->singleResult();
        $supply['datetime'] = date('d-m-Y H:i:s', strtotime($supply['datetime']));

        $this->db->query("SELECT * from detail_supply WHERE supply_id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        $detailSupply = $this->db->allResult();
        foreach ($detailSupply as &$detail) {
            $detail['kadaluwarsa'] = date('d-m-Y', strtotime($detail['kadaluwarsa']));
        }

        return $result = [
            'supply' => $supply,
            'detail_supply' => $detailSupply
        ];
    }

    public function getSupplyConfig($faktur) {
        $this->db->query("SELECT * FROM supply WHERE faktur=:faktur");
        $this->db->bind('faktur', $faktur);
        return $this->db->singleResult();
    }

    public function searchSupply($keyword){
        $query = "SELECT * FROM supply WHERE headofficer LIKE :keyword OR orderer LIKE :keyword OR headofficer LIKE :keyword OR payment LIKE :keyword OR faktur LIKE :keyword";

        $this->db->query($query);
        $this->db->bind('keyword', "%$keyword%");

        $this->db->execute();

        $supply = $this->db->allResult();
        foreach ($supply as &$s) {
            $s['datetime'] = date('d-m-Y H:i:s', strtotime($s['datetime']));
        }
        return $supply;
    }
    public function searchSupplyByIdOutlet($outlet_id, $keyword){
        $query = "SELECT * FROM supply WHERE (headofficer LIKE :keyword OR orderer LIKE :keyword OR headofficer LIKE :keyword OR payment LIKE :keyword OR faktur LIKE :keyword) AND outlet_id = :outlet_id";

        $this->db->query($query);
        $this->db->bind('keyword', "%$keyword%");
        $this->db->bind('outlet_id', "$outlet_id");

        $this->db->execute();

        $supply = $this->db->allResult();
        foreach ($supply as &$s) {
            $s['datetime'] = date('d-m-Y H:i:s', strtotime($s['datetime']));
        }
        return $supply;
    }
}