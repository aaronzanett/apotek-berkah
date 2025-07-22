<?php
$data['stokOutletLain'] = $this->model('ProdukModel')->getStokOutletLain($_SESSION['outlet_id']);
var_dump($data['stokOutletLain']);
?>