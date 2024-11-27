<?php
class PembelianModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }
    
    public function getAllPembelian(){
        $this->db->query("SELECT * FROM pembelian ORDER BY datetime DESC");
        $allResult = $this->db->allResult();
        foreach ($allResult as &$result) {
            $result['datetime'] = date('d-m-Y H:i:s', strtotime($result['datetime']));
        }
        return $allResult;
    }
    
    public function addPembelian($data){
        $queryPembelian = "INSERT INTO pembelian (id, supplier, orderer, subtotal, diskon, ppn, total_price, dibayar, utang, datetime, payment, faktur, note) VALUES ('', :supplier, :orderer, :subtotal, :diskon, :ppn, :total_price, :dibayar, :utang, :datetime, :payment, :faktur, :note)";
        $this->db->query($queryPembelian);
        $this->db->bind('supplier', $data['supplier']);
        $this->db->bind('orderer', $data['orderer']);
        $this->db->bind('subtotal', $data['sub-total']);
        $this->db->bind('diskon', $data['diskonTotal']);
        $this->db->bind('ppn', $data['ppn']);
        $this->db->bind('total_price', $data['total']);
        $this->db->bind('dibayar', $data['bayar']);
        $this->db->bind('utang', $data['utang']);
        $this->db->bind('datetime', $data['datetime']);
        $this->db->bind('payment', $data['pembayaran']);
        $this->db->bind('faktur', $data['faktur']);
        if(isset($data['note'])){
            $this->db->bind('note', $data['note']);
        }else {
            $this->db->bind('note', "-");
        }
        $this->db->execute();
        $rowcount = $this->db->rowCount();

        $idPembelian = $this->db->lastInsertId();

        // utang
        if($data['utang'] > 0){
            $queryUtang = "INSERT INTO utang (id, name, faktur, tagihan_awal, telah_dibayar, total_sisa, jatuh_tempo) VALUES ('', 'pembelian', :faktur, :utang, '', :utang, :jatuh_tempo)";
            $this->db->query($queryUtang);
            $this->db->bind('faktur', $data['faktur']);
            $this->db->bind('utang', $data['utang']);
            $this->db->bind('jatuh_tempo', $data['jatuhTempo']);
            $this->db->execute();
        }

        if(isset($data['idProduk']) && isset($data['produkName']) && isset($data['subtotal'])){
            // detail pembelian
            foreach ($data['idProduk'] as $i => $ip) {
                $queryDetailPembelian = "INSERT INTO detail_pembelian (id, pembelian_id, produk_name, base_unit_price, unit_name, quantity, unit_value, kadaluwarsa, jenis_harga, unit_price, diskon, subtotal) VALUES ('', :pembelian_id, :produk_name, :base_unit_price, :unit_name, :quantity, :unit_value, :kadaluwarsa, :jenis_harga, :unit_price, :diskon, :subtotal)";
                $this->db->query($queryDetailPembelian);
                $this->db->bind('pembelian_id', $idPembelian);
                $this->db->bind('produk_name', $data['produkName'][$i]);
                $this->db->bind('base_unit_price', $data['hargaBeliPokok'][$i]);
                $this->db->bind('unit_name', $data['satuanName'][$i]);
                $this->db->bind('quantity', $data['kuantitas'][$i]);
                $this->db->bind('unit_value', $data['satuanPokok'][$i]);
                $this->db->bind('kadaluwarsa', $data['kadaluwarsa'][$i]);
                $this->db->bind('jenis_harga', $data['jenisHarga'][$i]);
                $this->db->bind('unit_price', $data['harga'][$i]);
                $this->db->bind('diskon', $data['diskon'][$i]);
                $this->db->bind('subtotal', $data['subtotal'][$i]);
                $this->db->execute();
            }

            // persediaan produk
            foreach ($data['idProduk'] as $i => $ip) {
                $amount = $data['kuantitas'][$i] * $data['satuanPokok'][$i];

                $queryPersediaanProduk = "INSERT INTO persediaan_produk_headoffice (id, id_produk, amount, kadaluwarsa_date) VALUES ('', :id_produk, :amount, :kadaluwarsa_date)";
                $this->db->query($queryPersediaanProduk);
                $this->db->bind('id_produk', $ip);
                $this->db->bind('amount', $amount);
                $this->db->bind('kadaluwarsa_date', $data['kadaluwarsa'][$i]);
                $this->db->execute();
            }

            // cashbook
            $queryCashBook = "INSERT INTO cashbook_headoffice (id, status, faktur, activity, user, datetime, total) VALUES ('', :status, :faktur, :activity, :user, :datetime, :total)";
            $this->db->query($queryCashBook);
            $this->db->bind('status', 'pengeluaran');
            $this->db->bind('faktur', $data['faktur']);
            $this->db->bind('activity', 'pembelian');
            $this->db->bind('user', $data['orderer']);
            $this->db->bind('datetime', $data['datetime']);
            $this->db->bind('total', $data['total']);
            $this->db->execute();
        }

        
        return $rowcount;
    }
    
    public function getDataEditPembelian($id){
        $this->db->query("SELECT * from pembelian WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        $pembelian = $this->db->singleResult();
        $pembelian['datetime'] = date('d-m-Y H:i:s', strtotime($pembelian['datetime']));

        $this->db->query("SELECT * from detail_pembelian WHERE pembelian_id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        $detailPembelian = $this->db->allResult();
        foreach ($detailPembelian as &$detail) {
            $detail['kadaluwarsa'] = date('d-m-Y', strtotime($detail['kadaluwarsa']));
        }

        return $result = [
            'pembelian' => $pembelian,
            'detail_pembelian' => $detailPembelian
        ];
    }

    public function getPembelianConfig($faktur) {
        $this->db->query("SELECT * FROM pembelian WHERE faktur=:faktur");
        $this->db->bind('faktur', $faktur);
        return $this->db->singleResult();
    }

    public function searchPembelian($keyword){
        $query = "SELECT * FROM pembelian WHERE supplier LIKE :keyword OR orderer LIKE :keyword OR payment LIKE :keyword OR faktur LIKE :keyword";

        $this->db->query($query);
        $this->db->bind('keyword', "%$keyword%");

        $this->db->execute();

        $pembelian = $this->db->allResult();
        foreach ($pembelian as &$p) {
            $p['datetime'] = date('d-m-Y', strtotime($p['datetime']));
        }
        return $pembelian;
    }
}