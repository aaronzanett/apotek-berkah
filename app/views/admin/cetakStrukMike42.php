<?php
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

$idPenjualan = $_SESSION['id_penjualan'];
$ukuranKertas = $_SESSION['ukuran_kertas'];
$namaPrinter = $_SESSION['nama_printer'];

require_once("app/models/PenjualanModel.php");
require_once("app/models/OutletModel.php");

$penjualanModel = new PenjualanModel();
$outletModel = new OutletModel();

// data transaksi (info transaksi, info barang)
$penjualanData = $penjualanModel->getDataEditPenjualan($idPenjualan);
$penjualan = $penjualanData['penjualan'];
$penjualanItems = $penjualanData['detail_penjualan'];

// data header struk
$outletName = $penjualan['outlet_name'];
$outletAddress = $outletModel->getDataEditOutlet($penjualan['outlet_id'])['address'];

// hitung karakter per baris berdasarkan ukuran kertas
$charPerLine;
if($ukuranKertas == 58){
    $charPerLine = 32;
}else if($ukuranKertas == 80){
    $charPerLine = 48;
}

$connector = new WindowsPrintConnector($namaPrinter);
$printer = new Printer($connector);

try {
    function alignText($left, $right, $width) {
        $leftWidth = strlen($left);
        $rightWidth = strlen($right);
        $spaceWidth = $width - $leftWidth - $rightWidth;
        return $left . str_repeat(' ', $spaceWidth) . $right;
    }

    function truncateText($text, $maxLength) {
        return (strlen($text) > $maxLength) ? substr($text, 0, $maxLength - 3) . '...' : $text;
    }

    // Cetak header struk
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text("$outletName\n");
    $printer->text("$outletAddress\n");
    // $printer->text("Telp: 08129238923\n");
    $printer->text(str_repeat("-", $charPerLine) . "\n");
    
    // Cetak info transaksi
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text("Tgl/wkt: " . $penjualan['datetime'] . "\n");
    $printer->text("No: " . $penjualan['faktur'] . "\n");
    $printer->text("Ksr: " . $penjualan['cashier'] . "\n");
    $printer->text("Pel: " . $penjualan['payment'] . "\n");
    $printer->text(str_repeat("-", $charPerLine) . "\n");

    // Cetak detail item belanja
    $totalItems = 0;
    $totalQuantity = 0;
    foreach ($penjualanItems as $index => $item) {
        $itemName = truncateText(($index+1) . ". " . $item['produk_name'], $charPerLine - 10);
        $printer->text($itemName . " " . $item['quantity'] . "\n");
        $line = "    " . number_format($item['unit_price'], 0, ',', '.') . " x " . $item['quantity'] . " = ";
        $price = "Rp " . number_format($item['unit_price'] * $item['quantity'], 0, ',', '.');
        $printer->text(alignText($line, $price, $charPerLine) . "\n");
        $totalItems++;
        $totalQuantity += $item['quantity'];
    }
    $printer->text(str_repeat("-", $charPerLine) . "\n");
    $printer->text(alignText("BRS=".$totalItems."    QTY=".$totalQuantity, "Rp " . number_format($penjualan['total_price'], 0,',','.'), $charPerLine) . "\n");
    $printer->text(alignText("Tunai", "Rp " . number_format($penjualan['dibayar'],0,',','.'), $charPerLine) . "\n");
    $printer->text(str_repeat("-", $charPerLine) . "\n");
    $printer->text(alignText("Kembali", "Rp " . number_format($penjualan['kembalian'],0,',','.'), $charPerLine) . "\n");

    // Cetak footer struk
    $printer->text("\n");
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text("Barang yang sudah dibeli tidak\n");
    $printer->text("dapat dikembalikan\n");
    $printer->text("lekas sembuh ya pejuang sembuh\n");

    $printer->feed(4);

    $printer->cut();
} catch (Exception $e) {
    echo "Error during printing: " . $e->getMessage();
} finally {
    $printer->close();
}

echo '<script type="text/javascript">
       window.location.href = "'.BASEURL.'/app/admin/kasir";
      </script>';
?>