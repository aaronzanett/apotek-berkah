<?php 
function getPrinters() {
    // Jalankan perintah shell untuk mendapatkan daftar printer
    $output = shell_exec('wmic printer get name');
    $printers = explode("\n", trim($output));

    // Hilangkan header dari output
    array_shift($printers);

    // Bersihkan daftar printer
    $printers = array_map('trim', $printers);
    return $printers;
}

// Dapatkan daftar printer dan kirim sebagai JSON
$printers = getPrinters();
?>

<section id="transaksi" class="kasir">
    <div class="transactionPageHeader">
        <div class="transactionNav">
            <h2 class="pageTitle">Kasir</h2>
        </div>
        <div class="transactionSearch shadow">
            <div class="input">
                <input type="text" id="penjualanProduk" name="penjualanProduk" placeholder="Tulis nama, produsen, atau kode produk">
                <label for="penjualanProduk">
                    <i class="bi bi-search"></i>
                </label>
            </div>
            <div class="inputResult shadow3 d-none">
                <ul class="inputResultUl">
                </ul>
            </div>
        </div>
    </div>
    <div class="transactionPage">
        <form action="<?= BASEURL ?>/adminOpp/addPenjualan" method="post">
            <div class="transactionItems">
                <div class="transactionItemsHeader">
                    <p>No</p>
                    <p>Nama Produk</p>
                    <p>Stok</p>
                    <p></p>
                    <p>Kuantitas</p>
                    <p>Satuan</p>
                    <p>Jenis Harga</p>
                    <p>Harga / satuan</p>
                    <p>Diskon (%)</p>
                    <p>Harga Diskon</p>
                    <p>Subtotal</p>
                </div>
                <div class="transactionItemsContainer">
                    
                </div>
            </div>
            <div class="transactionData">
                <input type="hidden" name="id" id="id" value="id">
                <input type="hidden" name="outlet_id" id="outlet_id" value="<?= $_SESSION['outlet_id'] ?>">
                <input type="hidden" name="outlet_name" id="outlet_name" value="<?= $_SESSION['outlet_name'] ?>">

                <div class="form-group">
                    <div class="sideButtonContainer">
                        <div class="inputSideButton">
                            <label for="faktur">Faktur</label>
                            <input type="text" id="faktur" name="faktur" required maxlength="100">
                        </div>
                        <span class="btn edit sideButton autoFaktur"><i class="bi bi-arrow-clockwise"></i> Auto</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="kasir">Kasir</label>
                    <input type="kasir" id="kasir" name="kasir" readonly value="<?= $_SESSION['pengguna'] ?>">
                </div>
                <div class="form-group">
                    <label for="datetime">Tanggal/waktu</label>
                    <input type="datetime-local" id="datetime" class="datetimenow" name="datetime" readonly>
                </div>
                <!--<div class="form-group">-->
                <!--    <label for="pelanggan" class="form-label">Pelanggan<span class="optional-input"> *</span></label>-->
                <!--    <select name="pelanggan" id="pelanggan">-->
                <!--        <?php foreach ($data['kontakPelanggan'] as $p) : ?>-->
                <!--            <option value="<?= $p['fullname'] ?>"><?= $p['fullname'] ?></option>-->
                <!--        <?php endforeach; ?>-->
                <!--    </select>-->
                <!--</div>-->
                <div class="form-group markPrice">
                    <label for="sub-total" class="markPriceLabel">Sub-total</label>
                    <div class="inputHargaContainer">
                        <span class="rp">Rp</span>
                        <input type="text" id="sub-total" class="inputHarga markPriceInput" name="sub-total" readonly>
                    </div>
                </div>
                <div class="transactionDataOverflowLine"></div>
                <div class="transactionDataOverflow">
                    <div class="form-group">
                        <label for="diskon-total">Diskon Total (%)</label>
                        <input type="number" id="diskon-total" class="diskon-total" name="diskon-total" step="0.01" value="0" required max="100">
                    </div>
                    <div class="form-group">
                        <label for="biaya-embalase">Biaya Embalase</label>
                        <div class="inputHargaContainer">
                            <span class="rp">Rp</span>
                            <input type="text" id="biaya-embalase" class="inputHarga" name="biaya-embalase" value="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ongkos-kirim">Ongkos Kirim</label>
                        <div class="inputHargaContainer">
                            <span class="rp">Rp</span>
                            <input type="text" id="ongkos-kirim" class="inputHarga" name="ongkos-kirim" value="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="biaya-lainnya">Biaya Lainnya</label>
                        <div class="inputHargaContainer">
                            <span class="rp">Rp</span>
                            <input type="text" id="biaya-lainnya" class="inputHarga" name="biaya-lainnya" value="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="note">Note<span class="optional-input"> *</span></label>
                        <textarea name="note" id="note" rows="4" maxlength="500"></textarea>
                    </div>
                    <div class="form-group markPrice">
                        <label for="total" class="markPriceLabel">Total</label>
                        <div class="inputHargaContainer">
                            <span class="rp">Rp</span>
                            <input type="text" id="total" class="inputHarga markPriceInput"  name="total" readonly>
                        </div>
                    </div>
    
                    <div class="configAlert transaksiConfigAlert d-none">
                        <p class="configNoProduk d-none">Pilih produk terlebih dahulu!</p>
                        <p class="configFaktur d-none">Faktur sudah ada!</p>
                    </div>
    
                    <button type="button" class="btn edit transactionBtn">Bayar</button>
                </div>
            </div>

            <div id="modal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="close closeButton">&times;</span>
                        <h2 class="modal-title">Pembayaran</h2>
                    </div>
                    <div class="modal-data">
                        <div class="modal-body">
                            <input type="hidden" name="id" id="id" value="id">

                            <div class="modal-kasir-total">
                                <h4>Total</h4>
                                <h1 class="modal-title-kasir-total">Rp.</h1>
                            </div>

                            <div class="bayarContainer transactionData">
                                <div class="form-group">
                                    <label for="bayar">Bayar</label>
                                    <div class="inputHargaContainer">
                                        <span class="rp">Rp</span>
                                        <input type="text" id="bayar" class="inputHarga" name="bayar" value="0">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="pembayaran" class="form-label">Pembayaran</label>
                                    <select name="pembayaran" id="pembayaran" required>
                                        <?php foreach ($data['metodePembayaran'] as $mp) : ?>
                                            <option value="<?= $mp['name'] ?>"><?= $mp['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="modal-status-bayar">
                                <h2 class="modal-status-kurang status-title">Uang Kurang</h2>
                                <h2 class="modal-status-pas status-title">Pembayaran Pas</h2>
                                <h2 class="modal-status-kembali status-title">Kembalian</h2>
                                <h1 class="status-nominal"></h1>
                            </div>
                                
                            <div class="pengaturanPrinter">
                                <div class="modal-radio-group showKatalog">
                                    <label for="lebarKertasStruk" class="radioLabel">Ukuran Kertas Struk</label>
                                    
                                    <div class="modal-radio-item">
                                        <input type="radio" name="lebarKertasStruk" id="58" value="58" required>
                                        <label for="58">58 mm</label>
                                    </div>
                                    <div class="modal-radio-item">
                                        <input type="radio" name="lebarKertasStruk" id="80" value="80">
                                        <label for="80">80 mm</label>
                                    </div>
                                </div>
                                <div class="namaPrinterContainer form-group">
                                    <label for="namaPrinter" class="harga-beli-label">Nama Printer</label>
                                    <input type="text" id="namaPrinter" name="namaPrinter" class="namaPrinter" required>
                                </div>
                            </div>
                            <div class="modal-footer modal-kasir-footer">
                                <div class="cetakStruk">
                                    <input type="checkbox" name="cetakStruk" id="cetakStruk" value="cetakStruk" checked>
                                    <label for="cetakStruk">Cetak Struk</label>
                                </div>
                                <div class="buttons">
                                    <button type="button" class="btn cancel close">Cancel</button>
                                    <button type="submit" class="btn edit bayarButton">Bayar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
$(function(){
    $("#id").val(''),
    $("#faktur").val(''),
    $("#pelanggan").val(''),
    $("#note").val(''),
    $("#pembayaran").val('')

    // auto nama printer & lebar kertas
    $.ajax({
        url: "<?= BASEURL ?>/adminOpp/getDataPrinterByOutletId",
        data: { outlet_id: <?= $_SESSION['outlet_id'] ?> },
        method: "post",
        dataType: "json",
        success: function (dataPrinter){
            $(".namaPrinter").val(dataPrinter.printer_name)

            $("input[name='lebarKertasStruk'][value='" + dataPrinter.paper_size + "']").prop('checked', true);
        }
    })
    
    // fungsi format ribuan
    function formatRibuan(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // hitung harga
    function hitungHarga() {
        let subTotal = 0;
        $('.transactionItem').each(function () {
            const item = $(this);
            const hargaJual = item.find('.jenisHarga option:selected').data("nominal");
            let kuantitas = item.find('.kuantitas').val() || 0;
            const satuanVal = item.find('.satuanPokok').val();
            const hargaSatuan = hargaJual * satuanVal;
            const stok = item.find('.stok').val();

            let maxqty = Math.floor(stok / satuanVal);

            if(kuantitas > maxqty){
                item.find('.kuantitas').val(maxqty)
                kuantitas = maxqty;
            }

            // harga dan subtotal (item)
            if(item.find('.jenisHarga option:selected').val() == 'Harga Custom') {
                item.find('.harga').attr('required', true)
                item.find('.harga').removeAttr('readonly')
            } else if(item.find('.jenisHarga option:selected').val() == 'Harga Pokok'){
                item.find('.harga').attr('readonly', true)
                item.find('.harga').removeAttr('required')
                item.find('.harga').val(formatRibuan(hargaSatuan))
            } else if(item.find('.jenisHarga option:selected').val() == 'Harga Resep'){
                item.find('.harga').attr('readonly', true)
                item.find('.harga').removeAttr('required')
                item.find('.harga').val(formatRibuan(hargaSatuan))
            }
            const harga = item.find('.harga').val().replace(/\./g, '') || 0;
            const diskon = item.find('.diskon').val() || 0;
            
            let hargaDiskon = harga - (harga * (diskon / 100));

            let subtotal = (harga * kuantitas) - ((harga * kuantitas) * (diskon / 100));
            subTotal = subTotal + subtotal;

            item.find('.hargaDiskon').val(formatRibuan(hargaDiskon))
            item.find('.subtotal').val(formatRibuan(subtotal));

        });
        const diskonTotal = $("#diskon-total").val();
        let total = subTotal - (subTotal * (diskonTotal / 100));

        total = Math.ceil(total);

        // penambahan biaya
        const biayaEmbalase = parseInt($("#biaya-embalase").val().replace(/\./g, '') || 0);
        const ongkosKirim = parseInt($("#ongkos-kirim").val().replace(/\./g, '') || 0);
        const biayaLainnya = parseInt($("#biaya-lainnya").val().replace(/\./g, '') || 0);
        let penambahanBiaya = biayaEmbalase + ongkosKirim + biayaLainnya;
        total += penambahanBiaya;

        // subtotal dan total
        $("#sub-total").val(formatRibuan(subTotal));
        $("#total").val(formatRibuan(total))
        $(".modal-title-kasir-total").html("Rp. " + formatRibuan(total));
    }
    hitungHarga();

    // trigger fungsi hitung harga
    $(document).on('input', '.kuantitas, .satuanPokok, .jenisHarga, .harga, .diskon, #diskon-total, #biaya-embalase, #ongkos-kirim, #biaya-lainnya', function(){
        hitungHarga();
    })

    // fungsi delete
    $(document).on('click', '.deleteTransaksiProduk', function() {
        let produkId = $(this).data("id");
        // Hapus produk dari tampilan
        $(`.transactionItem[data-product-id="${produkId}"]`).remove();

        $(".transactionItem").each(function(i){
            $(this).find('.nomor').html(++i)
        })
    });

    // hasil pencarian
    $(document).ready(function() {
        let debounceTimer;
        const debounceDelay = 100;
        $("#penjualanProduk").on('input', function(){
            let keyword = $(this).val();
            if(keyword == ''){
                resetProdukSearch();
                return false
            }

            $.ajax({
                url: "<?= BASEURL ?>/headofficeOpp/searchProduk",
                data: { keyword: keyword },
                method: "post",
                dataType: "json",
                success: function (data) {
                    if(keyword == ''){
                        $(".inputResultUl").html('');
                        $(".inputResult").addClass("d-none");
                    } else {
                        if(data.length >= 1){
                            let results = '';
                            data.forEach(produk => {
                                $.ajax({
                                    url: "<?= BASEURL ?>/adminOpp/getPersediaanProdukOutlet",
                                    data: { id: produk.id, outletId: <?= $_SESSION['outlet_id'] ?> },
                                    method: "post",
                                    dataType: "json",
                                    success: function (stok){
                                        if(stok == false){
                                            results += `<li class="produkSearchResult" data-id="${produk.id}" data-stok="0">${produk.product_name} (0 ${produk.satuan_name}) - @Rp. ${formatRibuan(produk.harga_jual)} (harga jual)</li>`;
                                        }else{
                                            results += `<li class="produkSearchResult" data-id="${produk.id}" data-stok="${stok.total_jumlah}">${produk.product_name} (${stok.total_jumlah} ${produk.satuan_name}) - @Rp. ${formatRibuan(produk.harga_jual)} (harga jual)</li>`;
                                        }
                                        $(".inputResultUl").html(results);
                                        $(".inputResult").removeClass("d-none");
                                    }
                                })
                            });
                        } else {
                            $(".inputResultUl").html('');
                            $(".inputResult").addClass("d-none");
                        }
                    }
                }
            });

            clearTimeout(debounceTimer);
            debounceTimer = setTimeout( () => {
                let barqode = $(this).val()
                inputProdukBarqode(barqode)
            }, debounceDelay)
        });
    });
    
    // proses input produk dengan klik
    $(document).on('click', '.produkSearchResult', function() {
        let produkId = $(this).data("id");
        let stok = $(this).data("stok");

        if(stok < 1) {
            let timerInterval;
            Swal.fire({
            title: "Stok produk kosong!",
            timer: 1200,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
            },
            willClose: () => {
                clearInterval(timerInterval);
            }
            })
        } else if (stok >= 1){
            let produkItem = $(`.transactionItem[data-product-id="${produkId}"]`);
            if (produkItem.length > 0) {
                resetProdukSearch();
                let kuantitasInput = produkItem.find('.kuantitas');
                let existingQuantity = parseInt(kuantitasInput.val());
                kuantitasInput.val(existingQuantity + 1);
                hitungHarga();
                $("#penjualanProduk").focus();
            } else {
                resetProdukSearch();
                $.ajax({
                    url: "<?= BASEURL ?>/headofficeOpp/getDataEditProduk",
                    data: { id: produkId },
                    method: "post",
                    dataType: "json",
                    success: function (data) {
                        $.ajax({
                            url: "<?= BASEURL ?>/headofficeOpp/getDataTransaksiSatuanLainnya",
                            data: { id: produkId },
                            method: "post",
                            dataType: "json",
                            success: function (satuanLainnya) {
                                let resultsCreate = `<div class="transactionItem" data-product-id="${data.id}">
                                    <input type="hidden" name="idProduk[]" value="${data.id}">
                                    <input type="hidden" name="produkName[]" value="${data.product_name}">
                                    <input type="hidden" name="satuanName[]" class="satuanName">
                                    <input type="hidden" name="stok[]" class="stok" value="${stok}">
                                    <input type="hidden" name="hargaJualPokok[]" class="stok" value="${data.harga_jual}">
                                    <p class="nomor">${$('.transactionItem').length + 1}</p>
                                    <p>${data.product_name}</p>
                                    <p>${stok} (${data.satuan_name})</p>
                                    <span class="btn-sml deleteTransaksiProduk" data-id="${data.id}"><i class="bi bi-trash3-fill"></i></span>
                                    <div class="form-group">
                                        <input type="number" name="kuantitas[]" class="kuantitas" value="1" min="1">
                                    </div>
                                    <select name="satuanPokok[]" class="satuanPokok" required>`;
    
                                
                                satuanLainnya.forEach(s => {
                                    if (s.value == '' || s.value == undefined) {
                                        s.value = 1;
                                    }
                                    resultsCreate += `<option value="${s.value}" data-satuan-name="${s.satuan_name}">${s.satuan_name} (${s.value})</option>`;
                                });
                                resultsCreate += `</select>
                                    <select name="jenisHarga[]" class="jenisHarga" required>
                                        <option value="Harga Pokok" class="jenisHargaHargaPokok" data-nominal="${data.harga_jual}" selected>Harga Pokok (Rp. ${formatRibuan(data.harga_jual)})</option>
                                        <option value="Harga Resep" class="jenisHargaHargaResep" data-nominal="${data.harga_resep}">Harga Resep (Rp. ${formatRibuan(data.harga_resep)})</option>
                                        <option value="Harga Custom">Harga Custom</option>
                                    </select>
                                    <div class="form-group">
                                        <div class="inputHargaContainer hargaContainer">
                                            <span class="rp">Rp</span>
                                            <input type="text" name="harga[]" class="inputHarga harga" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="number" name="diskon[]" class="diskon"  step="0.01" min="0" max="100" value="0" required>
                                    </div>
                                    <div class="form-group">
                                        <div class="inputHargaContainer">
                                            <span class="rp">Rp</span>
                                            <input type="text" name="hargaDiskon[]" class="inputHarga hargaDiskon" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="inputHargaContainer">
                                            <span class="rp">Rp</span>
                                            <input type="text" name="subtotal[]" class="inputHarga subtotal" readonly>
                                        </div>
                                    </div>
                                </div>`;
    
                                $(".transactionItemsContainer").append(resultsCreate);
                                hitungHarga();
                                inputHarga();
    
                                if(!$(".transactionItemsContainer").find(".transactionItem").length <= 0){
                                    $(".configNoProduk").addClass("d-none");
                                    configAlert();
                                }
                            }
                        });
    
                        $("#penjualanProduk").focus();
                    }
                });
            }
        }

    });

    // proses input dengan barqode
    function inputProdukBarqode(barqode){
        $.ajax({
            url: "<?= BASEURL ?>/headofficeOpp/getDataEditProdukWithBarqode",
            data: { barqode: barqode },
            method: "post",
            dataType: "json",
            success: function (data) {
                if(data){
                    let produkId = data.id;                
                    let produkItem = $(`.transactionItem[data-product-id="${produkId}"]`);
                    $.ajax({
                        url: "<?= BASEURL ?>/adminOpp/getPersediaanProdukOutlet",
                        data: { id: produkId, outletId: <?= $_SESSION['outlet_id'] ?> },
                        method: "post",
                        dataType: "json",
                        success: function (dataStok){
                            if(dataStok == false) {
                                resetProdukSearch();
    
                                let timerInterval;
                                Swal.fire({
                                title: "Stok produk kosong!",
                                timer: 1200,
                                timerProgressBar: true,
                                didOpen: () => {
                                    Swal.showLoading();
                                },
                                willClose: () => {
                                    clearInterval(timerInterval);
                                }
                                })
    
                                $("#penjualanProduk").focus();
                            }else if(dataStok.total_jumlah >= 1) {
                                let stok = dataStok.total_jumlah
                                if(produkItem.length > 0){
                                    resetProdukSearch();
                                    let kuantitasInput = produkItem.find('.kuantitas');
                                    let existingQuantity = parseInt(kuantitasInput.val());
                                    kuantitasInput.val(existingQuantity + 1);
                                    hitungHarga();
                                    $("#penjualanProduk").focus();
                                } else {
                                    $.ajax({
                                        url: "<?= BASEURL ?>/headofficeOpp/getDataTransaksiSatuanLainnya",
                                        data: { id: produkId },
                                        method: "post",
                                        dataType: "json",
                                        success: function (satuanLainnya) {
                                            resetProdukSearch();
                                            let resultsCreate = `<div class="transactionItem" data-product-id="${data.id}">
                                                <input type="hidden" name="idProduk[]" value="${data.id}">
                                                <input type="hidden" name="produkName[]" value="${data.product_name}">
                                                <input type="hidden" name="satuanName[]" class="satuanName">
                                                <input type="hidden" name="stok[]" class="stok" value="${stok}">
                                                <input type="hidden" name="hargaJualPokok[]" class="stok" value="${data.harga_jual}">
                                                <p>${$('.transactionItem').length + 1}</p>
                                                <p>${data.product_name}</p>
                                                <p>${stok} (${data.satuan_name})</p>
                                                <span class="btn-sml deleteTransaksiProduk" data-id="${data.id}"><i class="bi bi-trash3-fill"></i></span>
                                                <div class="form-group">
                                                    <input type="number" name="kuantitas[]" class="kuantitas" value="1">
                                                </div>
                                                <select name="satuanPokok[]" class="satuanPokok" required>`;
                    
                                                
                                            satuanLainnya.forEach(s => {
                                                if (s.value == '' || s.value == undefined) {
                                                    s.value = 1;
                                                }
                                                resultsCreate += `<option value="${s.value}" data-satuan-name="${s.satuan_name}">${s.satuan_name} (${s.value})</option>`;
                                            });
                                            resultsCreate += `</select>
                                                <select name="jenisHarga" class="jenisHarga" required>
                                                    <option value="Harga Pokok" class="jenisHargaHargaPokok" data-nominal="${data.harga_jual}" selected>Harga Pokok (Rp. ${formatRibuan(data.harga_jual)})</option>
                                                    <option value="Harga Resep" class="jenisHargaHargaResep" data-nominal="${data.harga_resep}">Harga Resep (Rp. ${formatRibuan(data.harga_resep)})</option>
                                                    <option value="Harga Custom">Harga Custom</option>
                                                </select>
                                                <div class="form-group">
                                                    <div class="inputHargaContainer hargaContainer">
                                                        <span class="rp">Rp</span>
                                                        <input type="text" name="harga[]" class="inputHarga harga" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <input type="number" name="diskon[]" class="diskon" step="0.01" min="0" max="100" value="0" required>
                                                </div>
                                                <div class="form-group">
                                                    <div class="inputHargaContainer">
                                                        <span class="rp">Rp</span>
                                                        <input type="text" name="hargaDiskon[]" class="inputHarga hargaDiskon" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="inputHargaContainer">
                                                        <span class="rp">Rp</span>
                                                        <input type="text" name="subtotal[]" class="inputHarga subtotal" readonly>
                                                    </div>
                                                </div>
                                            </div>`;
                    
                                            $(".transactionItemsContainer").append(resultsCreate);
                                            hitungHarga();
                                            inputHarga();
                                        }
                                    });
                
                                    $("#penjualanProduk").focus();
                                }
                            }
                        }
                    })
                }

            }
        });
    }

    $(document).on('click', function(event) {
        if (!$(event.target).closest('#penjualanProduk').length && !$(event.target).closest('.inputResult').length) {
            $(".inputResultUl").html('');
            $(".inputResult").addClass("d-none");
        }
    });

    function resetProdukSearch(){
        $("#penjualanProduk").val('');
        $(".inputResultUl").html('');
        $(".inputResult").addClass("d-none");
    }
    
    // format number input harga
    $("form").on("submit", function (e) {
        const form = $(this)
        form.find('.inputHarga').each(function() {
            const input = $(this);
            const rawValue = input.val().replace(/\./g, '');
            input.val(rawValue);
        });

        $('.transactionItem').each(function() {
            const selectedOption = $(this).find('select.satuanPokok option:selected');
            const satuanName = selectedOption.data('satuan-name');
            $(this).find('input.satuanName').val(satuanName);
        });
    })

    $('.transactionBtn').on('click', function() {
        if($(".transactionItemsContainer").find(".transactionItem").length <= 0){
            $(".configNoProduk").removeClass("d-none");
            configAlert();
            return false;
        }

        bayarFunction();

        let modal = document.getElementById("modal");
        modal.style.display = "block";
    })

    // config
    $('#faktur').on('input', function() {
        let keyword = $(this).val();
        $.ajax({
            url: "<?= BASEURL ?>/adminOpp/getPembelianConfig",
            data: { configdata: keyword },
            method: "post",
            dataType: "json",
            success: function (data) {
                if($(".modal-footer button[type=submit]").html() == "Edit" ) {
                    let $id = $('#id').val();
                    if(data.id == $id) {
                        $(".configFaktur").addClass("d-none");
                        configAlert();
                    } else if (data) {
                        $(".configFaktur").removeClass("d-none");
                        configAlert();
                    } else {
                        $(".configFaktur").addClass("d-none");
                        configAlert();
                    }
                } else if (data) {
                    $(".configFaktur").removeClass("d-none");
                    configAlert();
                } else {
                    $(".configFaktur").addClass("d-none");
                    configAlert();
                }
            },
        })
    })

    // config alert
    function configAlert() {
        let parent = $('.configAlert');
        let children = parent.children();
        let allHaveDNone = true;

        children.each(function() {
            if (!$(this).hasClass('d-none')) {
                allHaveDNone = false;
                return false;  // Break the loop
            }
        });

        if (allHaveDNone) {
            $(".transactionBtn").removeClass('disabled')
            $(".configAlert").addClass('d-none');
        } else {
            $(".transactionBtn").addClass('disabled')
            $('.configAlert').removeClass('d-none')
        }
    }

    // faktur fill
    function fakturFill() {
        const now = new Date();

        function padToTwoDigits(num) {
            return num.toString().padStart(2, '0');
        }
        const seconds = padToTwoDigits(now.getSeconds());
        const minutes = padToTwoDigits(now.getMinutes());
        const hours = padToTwoDigits(now.getHours());
        const date = padToTwoDigits(now.getDate());  
        const month = padToTwoDigits(now.getMonth() + 1);
        const year = now.getFullYear().toString().slice(-2);
        
        $("#faktur").val(`PJL${seconds}${minutes}${hours}${date}${month}${year}`);
    }
    fakturFill();
    $(".autoFaktur").on("click", fakturFill)

    // bayar
    function bayarFunction() {
        let total = $("#total").val().replace(/\./g, '')
        let bayar = $("#bayar").val().replace(/\./g, '') || 0

        total = parseInt(total)
        if(isNaN(bayar)){
            bayar = 0
        }else{
            bayar = parseInt(bayar)
        }

        $(".modal-status-bayar .status-title").each(function(){
            $(this).addClass("d-none")
        })

        if(total == bayar){
            $(".modal-status-pas").removeClass("d-none");
            $(".status-nominal").html("<br>")
            $(".bayarButton").removeClass("disabled")
        } else if (total < bayar){
            let kembali = bayar - total;
            $(".modal-status-kembali").removeClass("d-none");
            $(".status-nominal").removeClass("d-none").html("Rp. " + formatRibuan(kembali))
            $(".bayarButton").removeClass("disabled")
        } else if (total > bayar){
            let kurang = total - bayar;
            $(".modal-status-kurang").removeClass("d-none");
            $(".status-nominal").removeClass("d-none").html("Rp.- " + formatRibuan(kurang))
            $(".bayarButton").addClass("disabled")
        }
    }
    bayarFunction();
    $(".transactionBtn").on('click', function(){
        bayarFunction;
        focusAtEnd('#bayar');
    })
    $("#bayar").on('input', bayarFunction)

    // Fungsi untuk memfokuskan kursor di akhir teks
    function focusAtEnd(inputSelector) {
        const input = document.querySelector(inputSelector); // Ambil elemen input
        if (input) {
            input.focus();
            const length = input.value.length; // Hitung panjang value di input
            input.setSelectionRange(length, length); // Set kursor di akhir value
        }
    }
})
</script>

<script src="<?= BASEURL?>/assets/js/global-script.js"></script>