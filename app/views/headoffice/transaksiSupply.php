<section id="transaksi" class="transaksiSupply">
    <div class="transactionPageHeader">
        <div class="transactionNav">
            <a href="<?= BASEURL ?>/app/headoffice/supply"><i class="bi bi-arrow-left"></i></a>
            <h2 class="pageTitle">Transaksi Supply</h2>
        </div>
        <div class="transactionSearch shadow">
            <div class="input">
                <input type="text" id="supplyProduk" name="supplyProduk" placeholder="Tulis nama, produsen, atau kode produk" autofocus>
                <label for="supplyProduk">
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
        <form action="<?= BASEURL ?>/headofficeOpp/addSupply" method="post">
            <div class="transactionItems">
                <div class="transactionItemsHeader">
                    <p>No</p>
                    <p>Nama Produk</p>
                    <p>Stok</p>
                    <p></p>
                    <p>Kuantitas</p>
                    <p>Satuan</p>
                    <p>Tgl. Kadaluwarsa</p>
                    <p>Harga / satuan</p>
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
                <input type="hidden" name="outlet_name" id="outlet_name" val="">
                <div class="form-group">
                    <label for="outlet_id" class="form-label">Outlet (yang akan disupply)</label>
                    <select name="outlet_id" id="outlet_id" required>
                        <?php foreach ($data['outlet'] as $outlet) : ?>
                            <option value="<?= $outlet['id'] ?>"><?= $outlet['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="pemesan" class="form-label">Pemesan</label>
                    <select name="pemesan" id="pemesan" required>
                        <?php foreach ($data['kontakKaryawan'] as $pemesan) : ?>
                            <option value="<?= $pemesan['fullname'] ?>"><?= $pemesan['fullname'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="headofficer">Headofficer</label>
                    <input type="headofficer" id="headofficer" name="headofficer" readonly value="<?= $_SESSION['pengguna'] ?>">
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
                    <label for="total">Total</label>
                    <div class="inputHargaContainer">
                        <span class="rp">Rp</span>
                        <input type="text" id="total" class="inputHarga" name="total" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="bayar">Bayar</label>
                    <div class="inputHargaContainer">
                        <span class="rp">Rp</span>
                        <input type="text" id="bayar" class="inputHarga" name="bayar" required>
                    </div>
                    <input type="hidden" name="piutang" id="piutang" val="">
                    <p class="bayarTransaksiInfo d-none">Piutang sejumlah Rp. 0 ditambahkan secara otomatis dari transaksi ini</p>
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

    <div id="modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close closeButton">&times;</span>
                <h2 class="modal-title">Pilih Stok</h2>
            </div>
            <div class="modal-data">
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(function(){
    $("#id").val(''),
    $("#faktur").val(''),
    $("#outlet_id").val(''),
    $("#pemesan").val(''),
    $("#pembayaran").val(''),
    $("#note").val('')
    
    // fungsi format ribuan
    function formatRibuan(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    // hitung harga
    function hitungHarga() {
        let total = 0;
        $('.transactionItem').each(function () {
            const item = $(this);
            const hargaSupply = item.find('.hargaSupply').val().replace(/\./g, '') || 0;
            let kuantitas = item.find('.kuantitas').val() || 0;
            const satuanVal = item.find('.satuanPokok').val();
            const hargaSatuan = hargaSupply * satuanVal;
            const stok = item.find('.stok').val();

            let maxqty = Math.floor(stok / satuanVal);

            if(kuantitas > maxqty){
                item.find('.kuantitas').val(maxqty)
                kuantitas = maxqty;
            }
            
            let subtotal = (hargaSatuan * kuantitas);
            total = total + subtotal;

            item.find('.harga').val(formatRibuan(hargaSatuan));
            item.find('.subtotal').val(formatRibuan(subtotal));
        });

        // total
        $("#total").val(formatRibuan(total))

        // bayar & piutang
        let bayar = $("#bayar").val().replace(/\./g, '');
        if(bayar > total){
            $("#bayar").val(formatRibuan(total))
        }
        let piutang = total - bayar;
        if(piutang > 0){
            $(".bayarTransaksiInfo").removeClass("d-none").text(`Piutang sejumlah Rp. ${formatRibuan(piutang)} ditambahkan secara otomatis dari transaksi ini`)
            $("#piutang").val(piutang)
            $(".jatuhTempo").html(`<div class="form-group">
                    <label for="jatuhTempo">Jatuh Tempo Piutang</label>
                    <input type="date" id="jatuhTempo" name="jatuhTempo" required>
                </div>`)
        }else{
            $(".bayarTransaksiInfo").addClass("d-none")
            $("#piutang").val(0)
            $(".jatuhTempo").html("")
        }
    }
    hitungHarga();

    // proses pilih stok produk
    $(document).on('click', '.pilihStokProduk', function() {
        let produkId = $(this).data("idproduk");
        let idpp = $(this).data("idpp");
        let stok = $(this).data("stok");
        let kadaluwarsa = $(this).data("kadaluwarsa");

        // modal default
        $("#modal").css("display", "none")

        let produkGroup = $(`.transactionGroup[data-product-id="${produkId}"]`);
        let produkItem = $(`.transactionItem[data-idpp="${idpp}"]`);
        if (produkGroup.length > 0) {
            if(produkItem.length > 0){
                let kuantitasInput = produkItem.find('.kuantitas');
                let existingQuantity = parseInt(kuantitasInput.val());
                kuantitasInput.val(existingQuantity + 1);
                hitungHarga();
                $("#supplyProduk").focus();
            }else{
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
                            let resultsItemCreate = `<div class="transactionItem" data-product-id="${data.id}" data-idpp="${idpp}">
                                <input type="hidden" name="idProduk[]" value="${data.id}">
                                <input type="hidden" name="produkName[]" value="${data.product_name}">
                                <input type="hidden" name="hargaSupply[]" class="hargaSupply" value="${data.harga_supply}">
                                <input type="hidden" name="satuanName[]" class="satuanName">
                                <input type="hidden" name="stok[]" class="stok" value="${stok}">
                                <input type="hidden" name="idpp[]" class="idpp" value="${idpp}">
                                <p class="nomor"></p>
                                <p>${data.product_name}</p>
                                <p>${stok} (${data.satuan_name})</p>
                                <span class="btn-sml deleteTransaksiProduk" data-id="${data.id}" data-idpp="${idpp}"><i class="bi bi-trash3-fill"></i></span>
                                <div class="form-group">
                                    <input type="number" name="kuantitas[]" class="kuantitas" value="1" min="1">
                                </div>
                                <select name="satuanPokok[]" class="satuanPokok" required>`;

                            satuanLainnya.forEach(s => {
                                if (s.value == '' || s.value == undefined) {
                                    s.value = 1;
                                }
                                resultsItemCreate += `<option value="${s.value}" data-satuan-name="${s.satuan_name}">${s.satuan_name} (${s.value})</option>`;
                            });
                            resultsItemCreate += `</select>
                                <div class="form-group transaksiKadaluwarsa">
                                    <input type="text" class="kadaluwarsa" name="kadaluwarsa[]" value="${kadaluwarsa}" readonly>
                                </div>
                                <div class="form-group">
                                    <div class="inputHargaContainer hargaContainer">
                                        <span class="rp">Rp</span>
                                        <input type="text" name="harga[]" class="inputHarga harga" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="inputHargaContainer">
                                        <span class="rp">Rp</span>
                                        <input type="text" name="subtotal[]" class="inputHarga subtotal" readonly>
                                    </div>
                                </div>
                            </div>`;

                            $(`.transactionGroup[data-product-id="${produkId}"]`).append(resultsItemCreate);
                            hitungHarga();
                            inputHarga();
                            configNoProduk();
                        }
                    });

                    $("#supplyProduk").focus();
                }
            });
            }
        } else {
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
                            let resultsCreate = `<div class="transactionGroup" data-product-id="${data.id}">
                            <div class="transactionItem" data-product-id="${data.id}" data-idpp="${idpp}">
                                <input type="hidden" name="idProduk[]" value="${data.id}">
                                <input type="hidden" name="produkName[]" value="${data.product_name}">
                                <input type="hidden" name="hargaSupply[]" class="hargaSupply" value="${data.harga_supply}">
                                <input type="hidden" name="satuanName[]" class="satuanName">
                                <input type="hidden" name="stok[]" class="stok" value="${stok}">
                                <input type="hidden" name="idpp[]" class="idpp" value="${idpp}">
                                <p class="nomor">${$('.transactionGroup').length + 1}</p>
                                <p>${data.product_name}</p>
                                <p>${stok} (${data.satuan_name})</p>
                                <span class="btn-sml deleteTransaksiProduk" data-id="${data.id}" data-idpp="${idpp}"><i class="bi bi-trash3-fill"></i></span>
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
                                <div class="form-group transaksiKadaluwarsa">
                                    <input type="text" class="kadaluwarsa" name="kadaluwarsa[]" value="${kadaluwarsa}" readonly>
                                </div>
                                <div class="form-group">
                                    <div class="inputHargaContainer hargaContainer">
                                        <span class="rp">Rp</span>
                                        <input type="text" name="harga[]" class="inputHarga harga" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="inputHargaContainer">
                                        <span class="rp">Rp</span>
                                        <input type="text" name="subtotal[]" class="inputHarga subtotal" readonly>
                                    </div>
                                </div>
                            </div>
                            </div>`;

                            $(".transactionItemsContainer").append(resultsCreate);
                            hitungHarga();
                            inputHarga();
                            configNoProduk();
                        }
                    });

                    $("#supplyProduk").focus();
                }
            });
        }
    });

    // trigger fungsi hitung harga
    $(document).on('input', '.kuantitas, .satuanPokok, .jenisHarga, .harga, .diskon, #diskonTotal, #ppn, #bayar', function(){
        hitungHarga();
    })

    // fungsi delete
    $(document).on('click', '.deleteTransaksiProduk', function() {
        let idpp = $(this).data("idpp");
        let produkId = $(this).data("id");

        // Hapus produk dari tampilan
        $(`.transactionItem[data-idpp="${idpp}"]`).remove();

        // transactionGroup
        $(".transactionGroup").each(function(){
            if($(this).find(".transactionItem").length == 0){
                $(this).remove();
            }
        })

        $(".transactionGroup").each(function(i){
            let firstTransactionItem = $(this).find('.transactionItem').first();

            firstTransactionItem.find('.nomor').html(++i)
        })

        configNoProduk();
        hitungHarga();
    });

    // hasil pencarian
    $(document).ready(function() {
        let debounceTimer;
        const debounceDelay = 100;
        $("#supplyProduk").on('input', function(){
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
                                let id = produk.id
                                $.ajax({
                                    url: "<?= BASEURL ?>/headofficeOpp/getPersediaanProdukHeadoffice",
                                    data: { id: id },
                                    method: "post",
                                    dataType: "json",
                                    success: function (stok) {
                                        results += `<li class="produkSearchResult" data-id="${produk.id}" data-stok="${stok.total_jumlah}">${produk.product_name} (${stok.total_jumlah || 0} ${produk.satuan_name}) - @Rp. ${formatRibuan(produk.harga_supply)} (harga supply)</li>`;
                                        $(".inputResultUl").html(results);
                                        $(".inputResult").removeClass("d-none");
                                    }
                                });
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

                if(barqode == ''){
                    return false
                }else{
                    inputProdukBarqode(barqode)
                }
            }, debounceDelay)
        });
    });

    // proses input dengan klik
    $(document).on('click', '.produkSearchResult', function() {
        let id = $(this).data("id");

        $.ajax({
            url: "<?= BASEURL ?>/headofficeOpp/getStokPersediaanProdukHeadoffice",
            data: { id: id },
            method: "post",
            dataType: "json",
            success: function (data) {
                $("#modal").css('display', 'block')
                if(data.length == 0){
                    $(".modal-body").html(`<div class="stokProdukKosong">
                        <p>Stok produk kosong</p>
                    </div>`)
                }else{
                    let produkStok = ``;
                    data.forEach(produk => {
                        produkStok += `<div>
                            <p class="pilihStokProduk" data-idproduk="${produk.id_produk}" data-idpp="${produk.id}" data-stok="${produk.total_jumlah}" data-kadaluwarsa="${produk.kadaluwarsa_date}">${produk.product_name} (${produk.total_jumlah} ${produk.satuan_name}) exp. ${produk.kadaluwarsa_date}</p>
                        </div>`
                    });
                    $(".modal-body").html(produkStok)
                }

                resetProdukSearch();
            }
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
                if(data != false){
                    $.ajax({
                        url: "<?= BASEURL ?>/headofficeOpp/getStokPersediaanProdukHeadofficeBarqode",
                        data: { barqode: barqode },
                        method: "post",
                        dataType: "json",
                        success: function (data) {
                            $("#modal").css('display', 'block')
                            if(data.length != 0){
                                let produkStok = ``;
                                data.forEach(produk => {
                                    produkStok += `<div>
                                    <p class="pilihStokProduk" data-idproduk="${produk.id_produk}" data-idpp="${produk.id}" data-stok="${produk.total_jumlah}" data-kadaluwarsa="${produk.kadaluwarsa_date}">${produk.product_name} (${produk.total_jumlah} ${produk.satuan_name}) exp. ${produk.kadaluwarsa_date}</p>
                                    </div>`
                                });
                                $(".modal-body").html(produkStok)
                                resetProdukSearch()
                            }else {
                                $(".modal-body").html(`<div class="stokProdukKosong">
                                <p>Stok produk kosong</p>
                                </div>`)
                            }
                        }
                    });
                }
            }
        });
    }

    $(document).on('click', function(event) {
        if (!$(event.target).closest('#supplyProduk').length && !$(event.target).closest('.inputResult').length) {
            $(".inputResultUl").html('');
            $(".inputResult").addClass("d-none");
        }
    });

    function resetProdukSearch(){
        $("#supplyProduk").val('');
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

        // outlet name
        $("#outlet_name").val($("#outlet_id option:selected").text())

        e.preventDefault();
        
        Swal.fire({
            title: "Simpan transaksi supply?",
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
            url: "<?= BASEURL ?>/headofficeOpp/getSupplyConfig",
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
        
        $("#faktur").val(`SPY${seconds}${minutes}${hours}${date}${month}${year}`);

        $(".configFaktur").addClass("d-none");
        configAlert();
    }
    fakturFill();
    $(".autoFaktur").on("click", fakturFill)
})
</script>

<script src="<?= BASEURL?>/assets/js/global-script.js"></script>