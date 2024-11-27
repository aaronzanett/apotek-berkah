<?php
class AdminOpp extends Controller {
    // persediaan & defecta
    public function searchPersediaanProdukByOutletId(){
        echo json_encode($this->model('ProdukModel')->searchAllPersediaanProdukByIdOutlet($_POST['outlet_id'], $_POST['keyword']));
    }
    public function searchDefectaByOutletId(){
        echo json_encode($this->model('ProdukModel')->searchAllDefectaByIdOutlet($_POST['outlet_id'], $_POST['keyword']));
    }

    // KADALUWARSA
    public function getStokKadaluwarsaOutletById(){
        echo json_encode($this->model('ProdukModel')->getStokKadaluwarsaOutletById($_POST['rentang'], $_POST['keyword'], $_POST['outlet_id']));
    }

    // supply
    public function getPersediaanProdukOutlet(){
        echo json_encode($this->model('ProdukModel')->getPersediaanProdukOutlet($_POST['id'], $_POST['outletId']));
    }

    // PENJUALAN
    public function addPenjualan(){
        $this->model('PenjualanModel')->addPenjualan($_POST);
    }
    public function getDataEditPenjualan(){
        echo json_encode($this->model('PenjualanModel')->getDataEditPenjualan($_POST['id']));
    }
    public function getPenjualanConfig(){
        echo json_encode($this->model('PenjualanModel')->getPenjualanConfig($_POST['configdata']));
    }
    public function searchPenjualan(){
        echo json_encode($this->model('PenjualanModel')->searchPenjualan($_POST['keyword']));
    }
    public function searchPenjualanByIdOutlet(){
        echo json_encode($this->model('PenjualanModel')->searchPenjualanByIdOutlet($_POST));
    }
    
    public function getDataPrinterByOutletId(){
        echo json_encode($this->model('PenjualanModel')->getDataPrinterByOutletId($_POST['outlet_id']));
    }
}