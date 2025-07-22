<?php
require_once('vendor/autoload.php');
require_once('vendor/tecnickcom/tcpdf/tcpdf.php');

// Dummy Parameter
$idPenjualan = 999;
$namaOutlet = "Apotek Dummy Sehat";
$alamatOutlet = "Jl. Contoh Nomor 123, Kota Dummy";
$tanggalWaktu = date("Y-m-d H:i:s");

// Items Dummy
$itemsDummy = [
    ["name" => "Paracetamol", "unit_price" => 10000, "quantity" => 2],
    ["name" => "Vitamin C", "unit_price" => 15000, "quantity" => 1],
];
$totalHarga = 50000;

$ukuranKertas = $_SESSION['ukuran_kertas'] ?? 58;
$charPerLine = ($ukuranKertas == 58) ? 32 : 48;
$paperSize = ($ukuranKertas == 58) ? array(0, 0, 580, 3276) : array(0, 0, 800, 3276);

// Inisialisasi TCPDF
$pdf = new TCPDF('P', 'mm', $paperSize, true, 'UTF-8', false);
$pdf->SetMargins(5, 5, 5);
$pdf->AddPage();
$pdf->SetFont('courier', '', 10);

// Header
$pdf->Cell(0, 6, $namaOutlet, 0, 1, 'C');
$pdf->Cell(0, 6, $alamatOutlet, 0, 1, 'C');
$pdf->Cell(0, 6, str_repeat("-", $charPerLine), 0, 1, 'C');

// Transaksi
$pdf->Cell(0, 6, "Tanggal/Waktu: " . $tanggalWaktu, 0, 1, 'L');
$pdf->Cell(0, 6, "No Faktur: DUMMY-$idPenjualan", 0, 1, 'L');
$pdf->Cell(0, 6, str_repeat("-", $charPerLine), 0, 1, 'C');

// Detail Items
$totalQuantity = 0;
foreach ($itemsDummy as $item) {
    $itemName = substr($item['name'], 0, $charPerLine - 10);
    $line = number_format($item['unit_price'], 0, ',', '.') . " x " . $item['quantity'] . " = ";
    $price = "Rp " . number_format($item['unit_price'] * $item['quantity'], 0, ',', '.');

    $pdf->Cell(0, 6, $itemName, 0, 1, 'L');
    $pdf->Cell(0, 6, $line . $price, 0, 1, 'R');
    $totalQuantity += $item['quantity'];
}

// Total
$pdf->Cell(0, 6, str_repeat("-", $charPerLine), 0, 1, 'C');
$pdf->Cell(0, 6, "Total Barang: " . $totalQuantity, 0, 1, 'L');
$pdf->Cell(0, 6, "Total Harga: Rp " . number_format($totalHarga, 0, ',', '.'), 0, 1, 'L');

// Footer
$pdf->Ln(5);
$pdf->Cell(0, 6, "Barang yang dibeli tidak dapat dikembalikan", 0, 1, 'C');

// Path untuk menyimpan PDF
$savePath = __DIR__ . "/../../../assets/img/struk_dummy_$idPenjualan.pdf";

if (!file_exists(dirname($savePath))) {
    mkdir(dirname($savePath), 0777, true); // Buat folder jika belum ada
}

$pdf->Output($savePath, 'F'); // Simpan PDF

?>

<script type="text/javascript">
window.onload = function() {
    var fileUrl = '/assets/img/struk_dummy_<?php echo $idPenjualan; ?>.pdf';
    var iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    iframe.src = fileUrl;
    document.body.appendChild(iframe);

    iframe.onload = function() {
        iframe.contentWindow.print();
        setTimeout(function() {
            alert("Print selesai!");
            window.location.href = "javascript:window.history.back()";
        }, 30000);
    };
};
</script>
