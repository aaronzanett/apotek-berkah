<section id="transaksi" class="transaksiPembelian">
    <div class="transactionPageHeader">
        <div class="transactionNav">
            <a href="<?= BASEURL ?>/app/headoffice/pembelian"><i class="bi bi-arrow-left"></i></a>
            <h2 class="pageTitle">Transaksi Pembelian</h2>
        </div>
        <div class="transactionSearch shadow">
            <div class="input">
                <input type="text" id="pembelianProduk" name="pembelianProduk" placeholder="Tulis nama, produsen, atau kode produk" autofocus>
                <label for="pembelianProduk">
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
        <form action="<?= BASEURL ?>/headofficeOpp/addPembelian" method="post">
            <div class="transactionItems">
                <div class="transactionItemsHeader">
                    <p>No</p>
                    <p>Nama Produk</p>
                    <p></p>
                    <p>Kuantitas</p>
                    <p>Satuan</p>
                    <p>No. Batch</p>
                    <p>Tgl. Kadaluwarsa</p>
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
                    <label for="supplier" class="form-label">Supplier</label>
                    <select name="supplier" id="supplier" required>
                        <?php foreach ($data['kontakSupplier'] as $supplier) : ?>
                            <option value="<?= $supplier['fullname'] ?>"><?= $supplier['fullname'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="orderer">Pemesan</label>
                    <input type="orderer" id="orderer" name="orderer" readonly value="<?= $_SESSION['pengguna'] ?>">
                </div>
                <div class="form-group">
                    <label for="pembayaran" class="form-label">Pembayaran</label>
                    <select name="pembayaran" id="pembayaran" required>
                        <?php foreach ($data['metodePembayaran'] as $mp) : ?>
                            <option value="<?= $mp['name'] ?>"><?= $mp['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="datetime">Tanggal/waktu</label>
                    <input type="datetime-local" id="datetime" class="datetimenow" name="datetime" readonly>
                </div>
                <div class="form-group">
                    <label for="note">Note<span class="optional-input"> *</span></label>
                    <textarea name="note" id="note" rows="4" maxlength="500"></textarea>
                </div>
                <div class="form-group">
                    <label for="sub-total">Sub-total</label>
                    <div class="inputHargaContainer">
                        <span class="rp">Rp</span>
                        <input type="text" id="sub-total" class="inputHarga" name="sub-total" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="diskonTotal">Diskon Total (%)</label>
                    <input type="number" id="diskonTotal" class="diskonTotal" name="diskonTotal" step="0.01" value="0" required max="100">
                </div>
                <div class="form-group">
                    <label for="ppn">PPN (%)</label>
                    <input type="number" id="ppn" class="ppn" name="ppn" step="0.01" value="11" required max="100">
                </div>
                <div class="form-group">
                    <label for="total">Total</label>
                    <div class="inputHargaContainer">
                        <span class="rp">Rp</span>
                        <input type="text" id="total" class="inputHarga"  name="total" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="bayar">Bayar</label>
                    <div class="inputHargaContainer">
                        <span class="rp">Rp</span>
                        <input type="text" id="bayar" class="inputHarga" name="bayar" required>
                    </div>
                    <input type="hidden" name="utang" id="utang" val="">
                    <p class="bayarTransaksiInfo d-none">Utang sejumlah Rp. 0 ditambahkan secara otomatis dari transaksi ini</p>
                </div>
                <div class="jatuhTempo"></div>

                <div class="configAlert transaksiConfigAlert d-none">
                    <p class="configNoProduk d-none">Pilih produk terlebih dahulu!</p>
                    <p class="configFaktur d-none">Faktur sudah ada!</p>
                </div>

                <button type="submit" class="btn edit">Simpan</button>
            </div>
        </form>
    </div>
</section>

<script>
$(function(){
    $("#id").val(''),
    $("#faktur").val(''),
    $("#supplier").val(''),
    $("#pembayaran").val(''),
    $("#note").val('')

    
    // fungsi format ribuan
    function formatRibuan(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    // hitung harga
    function hitungHarga() {
        let subTotal = 0;
        $('.transactionItem').each(function () {
            const item = $(this);
            const hargaBeliPokok = item.find('.hargaBeliPokok').val().replace(/\./g, '') || 0;
            const kuantitas = item.find('.kuantitas').val() || 0;
            const satuanVal = item.find('.satuanPokok').val();
            const hargaSatuanPokok = hargaBeliPokok * satuanVal;
            item.find('.jenisHargaHargaPokok').text(`Harga Pokok (Rp. ${formatRibuan(hargaSatuanPokok)})`)
            let hargaSatuan;
            // harga dan subtotal (item)
            if(item.find('#jenisHarga option:selected').val() == 'Harga Custom') {
                hargaSatuan = item.find('.harga').val().replace(/\./g, '') || 0 * satuanVal;
                item.find('.harga').attr('required', true)
                item.find('.harga').removeAttr('readonly')
            } else {
                hargaSatuan = hargaBeliPokok * satuanVal;
                item.find('.harga').attr('readonly', true)
                item.find('.harga').removeAttr('required')
                item.find('.harga').val(formatRibuan(hargaSatuan))
            }
            const diskon = item.find('.diskon').val() || 0;
            
            let hargaDiskon = hargaSatuan - (hargaSatuan * (diskon / 100));
            
            let subtotal = (hargaSatuan * kuantitas) - ((hargaSatuan * kuantitas) * (diskon / 100));
            subTotal = subTotal + subtotal;

            item.find('.hargaDiskon').val(formatRibuan(hargaDiskon))
            item.find('.subtotal').val(formatRibuan(subtotal));

        });
        const diskonTotal = $("#diskonTotal").val();
        const ppn = $("#ppn").val();
        let total = subTotal - (subTotal * (diskonTotal / 100));
        total = total + (total * (ppn / 100));
        total = Math.ceil(total);

        // subtotal dan total
        $("#sub-total").val(formatRibuan(subTotal));
        $("#total").val(formatRibuan(total))

        // bayar & utang
        let bayar = $("#bayar").val().replace(/\./g, '');
        if(bayar > total){
            $("#bayar").val(formatRibuan(total))
        }
        let utang = total - bayar;
        if(utang > 0){
            $(".bayarTransaksiInfo").removeClass("d-none").text(`Utang sejumlah Rp. ${formatRibuan(utang)} ditambahkan secara otomatis dari transaksi ini`)
            $("#utang").val(utang)
            $(".jatuhTempo").html(`<div class="form-group">
                    <label for="jatuhTempo">Jatuh Tempo Utang</label>
                    <input type="date" id="jatuhTempo" name="jatuhTempo" required>
                </div>`)
        }else{
            $(".bayarTransaksiInfo").addClass("d-none")
            $("#utang").val(0)
            $(".jatuhTempo").html("")
        }
    }
    hitungHarga();

    // proses input produk dengan klik
    $(document).on('click', '.produkSearchResult', function() {
        let produkId = $(this).data("id");

        let produkItem = $(`.transactionItem[data-product-id="${produkId}"]`);
        if (produkItem.length > 0) {
            resetProdukSearch();
            let kuantitasInput = produkItem.find('.kuantitas');
            let existingQuantity = parseInt(kuantitasInput.val());
            kuantitasInput.val(existingQuantity + 1);
            hitungHarga();
            $("#pembelianProduk").focus();
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
                                <input type="hidden" name="hargaBeliPokok[]" class="hargaBeliPokok" value="${data.harga_beli}">
                                <input type="hidden" name="satuanName[]" class="satuanName">
                                <p class="nomor">${$('.transactionItem').length + 1}</p>
                                <p>${data.product_name}</p>
                                <span class="btn-sml deleteTransaksiProduk" data-id="${data.id}"><i class="bi bi-trash3-fill"></i></span>
                                <div class="form-group">
                                    <input type="number" name="kuantitas[]" class="kuantitas" value="1" min="1">
                                </div>
                                <select name="satuanPokok[]" id="satuanPokok" class="satuanPokok" required>`;

                            satuanLainnya.forEach(s => {
                                if (s.value == '' || s.value == undefined) {
                                    s.value = 1;
                                }
                                resultsCreate += `<option value="${s.value}" data-satuan-name="${s.satuan_name}">${s.satuan_name} (${s.value})</option>`;
                            });
                            resultsCreate += `</select>
                                <div class="form-group noBatch">
                                    <input type="text" id="noBatch" class="noBatch" name="noBatch[]" required autocomplete="off">
                                </div>
                                <div class="form-group transaksiKadaluwarsa">
                                    <input type="date" id="kadaluwarsa" class="kadaluwarsa" name="kadaluwarsa[]" required>
                                </div>
                                <select name="jenisHarga[]" id="jenisHarga" class="jenisHarga" required>
                                    <option value="Harga Pokok" class="jenisHargaHargaPokok" selected>Harga Pokok (Rp. ${formatRibuan(data.harga_beli)})</option>
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
                            configNoProduk();
                        }
                    });

                    $("#pembelianProduk").focus();
                }
            });
        }
    });

    // trigger fungsi hitung harga
    $(document).on('input', '.deleteTransaksiProduk ,.kuantitas, .satuanPokok, .jenisHarga, .harga, .diskon, #diskonTotal, #ppn, #bayar', function(){
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

        configNoProduk();
        hitungHarga();
    });

    // hasil pencarian
    $(document).ready(function() {
        let debounceTimer;
        const debounceDelay = 100;
        $("#pembelianProduk").on('input', function(){
            let keyword = $(this).val();
            if(keyword == ''){
                resetProdukSearch();
                return false;
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
                        let results = '';
                        if(data.length >= 1){
                            data.forEach(produk => {
                                results += `<li class="produkSearchResult" data-id="${produk.id}">${produk.product_name} - @Rp. ${formatRibuan(produk.harga_beli)} (harga beli)</li>`;
                            });

                            $(".inputResultUl").html(results);
                            $(".inputResult").removeClass("d-none");
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

                if(barqode == ''){
                    return false
                } else{
                    inputProdukBarqode(barqode)
                }
            }, debounceDelay)
        });
    });

    // proses input dengan barqode
    function inputProdukBarqode(barqode){
        $.ajax({
            url: "<?= BASEURL ?>/headofficeOpp/getDataEditProdukWithBarqode",
            data: { barqode: barqode },
            method: "post",
            dataType: "json",
            success: function (data) {
                let produkId = data.id;                
                let produkItem = $(`.transactionItem[data-product-id="${produkId}"]`);
                
                if(data != false){
                    if(produkItem.length > 0){
                        resetProdukSearch();
                        let kuantitasInput = produkItem.find('.kuantitas');
                        let existingQuantity = parseInt(kuantitasInput.val());
                        kuantitasInput.val(existingQuantity + 1);
                        hitungHarga();
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
                                    <input type="hidden" name="hargaBeliPokok[]" class="hargaBeliPokok" value="${data.harga_beli}">
                                    <input type="hidden" name="satuanName[]" class="satuanName">
                                    <p class="nomor">${$('.transactionItem').length + 1}</p>
                                    <p>${data.product_name}</p>
                                    <span class="btn-sml deleteTransaksiProduk" data-id="${data.id}"><i class="bi bi-trash3-fill"></i></span>
                                    <div class="form-group">
                                        <input type="number" name="kuantitas[]" class="kuantitas" value="1" min="1">
                                    </div>
                                    <select name="satuanPokok[]" id="satuanPokok" class="satuanPokok" required>`;
        
                                    
                                satuanLainnya.forEach(s => {
                                    if (s.value == '' || s.value == undefined) {
                                        s.value = 1;
                                    }
                                    resultsCreate += `<option value="${s.value}" data-satuan-name="${s.satuan_name}">${s.satuan_name} (${s.value})</option>`;
                                });
                                resultsCreate += `</select>
                                    <div class="form-group noBatch">
                                        <input type="text" id="noBatch" class="noBatch" name="noBatch[]" required autocomplete="off">
                                    </div>
                                    <div class="form-group transaksiKadaluwarsa">
                                        <input type="date" id="kadaluwarsa" class="kadaluwarsa" name="kadaluwarsa[]" required>
                                    </div>
                                    <select name="jenisHarga[]" id="jenisHarga" class="jenisHarga" required>
                                        <option value="Harga Pokok" class="jenisHargaHargaPokok" selected>Harga Pokok (Rp. ${formatRibuan(data.harga_beli)})</option>
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
                                configNoProduk();
                            }
                        });
                    }
                }

            }
        });
    }

    $(document).on('click', function(event) {
        if (!$(event.target).closest('#pembelianProduk').length && !$(event.target).closest('.inputResult').length) {
            $(".inputResultUl").html('');
            $(".inputResult").addClass("d-none");
        }
    });

    function resetProdukSearch(){
        $("#pembelianProduk").val('');
        $(".inputResultUl").html('');
        $(".inputResult").addClass("d-none");
    }
    
    // format number input harga
    $("form").on("submit", function (e) {
        // produk kosong
        if($(".transactionItemsContainer").find(".transactionItem").length <= 0){
            $(".configNoProduk").removeClass("d-none");
            configAlert();
            e.preventDefault();
            return false
        }
        if(!$(".configNoProduk").hasClass("d-none")){
            configAlert();
            e.preventDefault();
            return false;
        }

        // faktur
        if(!$(".configFaktur").hasClass("d-none")){
            configAlert();
            e.preventDefault();
            return false;
        }

        e.preventDefault();
        
        Swal.fire({
            title: "Simpan transaksi pembelian?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, simpan!",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
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
                form.off("submit").submit();
            }
        });
    })

    // config
    $('#faktur').on('input', function() {
        let keyword = $(this).val();
        $.ajax({
            url: "<?= BASEURL ?>/headofficeOpp/getPembelianConfig",
            data: { configdata: keyword },
            method: "post",
            dataType: "json",
            success: function (data) { 
                if (data) {
                    $(".configFaktur").removeClass("d-none");
                    configAlert();
                } else {
                    $(".configFaktur").addClass("d-none");
                    configAlert();
                }
            },
        })
    })

    function configNoProduk(){
        if($(".transactionItemsContainer").find(".transactionItem").length <= 0){
            $(".configNoProduk").removeClass("d-none");
            configAlert();
        }else {
            $(".configNoProduk").addClass("d-none");
            configAlert();
        }
    }
    configNoProduk();

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
            $(".configAlert").addClass('d-none');
        } else {
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
        
        $("#faktur").val(`PBL${seconds}${minutes}${hours}${date}${month}${year}`);

        $(".configFaktur").addClass("d-none");
        configAlert();
    }
    fakturFill();
    $(".autoFaktur").on("click", fakturFill)
})
</script>

<script src="<?= BASEURL?>/assets/js/global-script.js"></script>