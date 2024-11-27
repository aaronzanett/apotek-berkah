<?php
class UtangPiutang {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }
    
    // utang
    public function getAllUtang(){
        $this->db->query("SELECT * FROM utang ORDER BY jatuh_tempo DESC");
        $allResult = $this->db->allResult();
        foreach ($allResult as &$result) {
            $result['jatuh_tempo'] = date('d-m-Y', strtotime($result['jatuh_tempo']));
        }
        return $allResult;
    }

    public function bayarUtang($data){
        $this->db->query("UPDATE utang SET telah_dibayar = :bayar, total_sisa = total_sisa - :bayar WHERE id = :id");
        $this->db->bind('id', $data['id']);
        $this->db->bind('bayar', $data['bayar']);
        $this->db->execute();
        $utangRow = $this->db->rowCount();
        
        $this->db->query("DELETE FROM utang WHERE id = :id AND total_sisa <= 0");
        $this->db->bind('id', $data['id']);
        $this->db->execute();

        $queryCashBook = "INSERT INTO cashbook_headoffice (id, status, faktur, activity, user, datetime, total) VALUES ('', :status, :faktur, :activity, :user, :datetime, :total)";
            $this->db->query($queryCashBook);
            $this->db->bind('status', 'pengeluaran');
            $this->db->bind('faktur', $data['faktur'] . " (pembelian)");
            $this->db->bind('activity', 'bayar utang (pembelian)');
            $this->db->bind('user', $data['headofficer']);
            $this->db->bind('datetime', $data['datetime']);
            $this->db->bind('total', $data['bayar']);
            $this->db->execute();

        return $utangRow;
    }


    // piutang / utang outlet
    public function getAllPiutang(){
        $this->db->query("SELECT * FROM piutang ORDER BY jatuh_tempo DESC");
        $allResult = $this->db->allResult();
        foreach ($allResult as &$result) {
            $result['jatuh_tempo'] = date('d-m-Y', strtotime($result['jatuh_tempo']));
        }
        return $allResult;
    }

    public function getAllPiutangByOutletId($outlet_id){
        $this->db->query("SELECT * FROM piutang WHERE outlet_id = :outlet_id");
        $this->db->bind('outlet_id', $outlet_id);
        $allResult = $this->db->allResult();
        foreach ($allResult as &$result) {
            $result['jatuh_tempo'] = date('d-m-Y', strtotime($result['jatuh_tempo']));
        }
        return $allResult;
    }

    public function bayarPiutang($data){
        $this->db->query("UPDATE piutang SET telah_dibayar = :bayar, total_sisa = total_sisa - :bayar WHERE id = :id");
        $this->db->bind('id', $data['id']);
        $this->db->bind('bayar', $data['bayar']);
        $this->db->execute();
        $utangRow = $this->db->rowCount();
        
        $this->db->query("DELETE FROM piutang WHERE id = :id AND total_sisa <= 0");
        $this->db->bind('id', $data['id']);
        $this->db->execute();

        $queryCashBook = "INSERT INTO cashbook_headoffice (id, status, faktur, activity, user, datetime, total) VALUES ('', :status, :faktur, :activity, :user, :datetime, :total)";
            $this->db->query($queryCashBook);
            $this->db->bind('status', 'pemasukan');
            $this->db->bind('faktur', $data['faktur'] . " (supply)");
            $this->db->bind('activity', 'piutang outlet (supply)');
            $this->db->bind('user', $data['headofficer']);
            $this->db->bind('datetime', $data['datetime']);
            $this->db->bind('total', $data['bayar']);
            $this->db->execute();

        return $utangRow;
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
    public function searchPenjualanByIdOutlet($outlet_id, $keyword){
        $query = "SELECT * FROM penjualan WHERE (cashier LIKE :keyword OR faktur LIKE :keyword) AND outlet_id = :outlet_id";

        $this->db->query($query);
        $this->db->bind('keyword', "%$keyword%");
        $this->db->bind('outlet_id', "$outlet_id");

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