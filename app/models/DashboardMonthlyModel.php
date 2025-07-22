<?php
class DashboardMonthlyModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }
    
    // pembelian, supply dan penjualan dalam 1 bulan (dashboard headoffice)
    public function getMonthTransaction(){
        $this->db->query("SELECT 
            tanggal.tanggal,
            COALESCE(pembelian.total_pembelian, 0) AS total_pembelian,
            COALESCE(supply.total_supply, 0) AS total_supply,
            COALESCE(penjualan.total_penjualan, 0) AS total_penjualan
        FROM 
            (SELECT DISTINCT DATE(datetime) AS tanggal FROM penjualan
            UNION
            SELECT DISTINCT DATE(datetime) AS tanggal FROM supply
            UNION
            SELECT DISTINCT DATE(datetime) AS tanggal FROM pembelian) AS tanggal
        LEFT JOIN 
            (SELECT DATE(datetime) AS tanggal, SUM(total_price) AS total_penjualan 
            FROM penjualan 
            GROUP BY DATE(datetime)) AS penjualan
        ON tanggal.tanggal = penjualan.tanggal
        LEFT JOIN 
            (SELECT DATE(datetime) AS tanggal, SUM(total_price) AS total_supply 
            FROM supply 
            GROUP BY DATE(datetime)) AS supply
        ON tanggal.tanggal = supply.tanggal
        LEFT JOIN 
            (SELECT DATE(datetime) AS tanggal, SUM(total_price) AS total_pembelian 
            FROM pembelian 
            GROUP BY DATE(datetime)) AS pembelian
        ON tanggal.tanggal = pembelian.tanggal
        WHERE tanggal.tanggal >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
        ORDER BY tanggal.tanggal");
        $allResult = $this->db->allResult();
        foreach ($allResult as &$result) {
            $result['tanggal'] = date('d-m-Y', strtotime($result['tanggal']));
        }
        return $allResult;
    }

    public function getMonthTransactionOutlet($outletId){
        $this->db->query("SELECT 
            tanggal.tanggal,
            COALESCE(supply.total_supply, 0) AS total_supply,
            COALESCE(penjualan.total_penjualan, 0) AS total_penjualan
        FROM 
            (SELECT DISTINCT DATE(datetime) AS tanggal FROM penjualan
            UNION
            SELECT DISTINCT DATE(datetime) AS tanggal FROM supply) AS tanggal
        LEFT JOIN 
            (SELECT DATE(datetime) AS tanggal, SUM(total_price) AS total_penjualan 
            FROM penjualan 
            WHERE outlet_id = :outletId
            GROUP BY DATE(datetime)) AS penjualan
        ON tanggal.tanggal = penjualan.tanggal
        LEFT JOIN 
            (SELECT DATE(datetime) AS tanggal, SUM(total_price) AS total_supply 
            FROM supply 
            WHERE outlet_id = :outletId
            GROUP BY DATE(datetime)) AS supply
        ON tanggal.tanggal = supply.tanggal
        WHERE tanggal.tanggal >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
        ORDER BY tanggal.tanggal;
        ");
        $this->db->bind('outletId', $outletId);
        $allResult = $this->db->allResult();
        foreach ($allResult as &$result) {
            $result['tanggal'] = date('d-m-Y', strtotime($result['tanggal']));
        }
        return $allResult;
    }
}