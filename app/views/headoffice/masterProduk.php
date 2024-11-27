<?php
    $generator = new \Picqer\Barcode\BarcodeGeneratorHTML();
    $redColor = [255, 0, 0];

    $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
    // echo $generator->getBarcode('081231723897', $generator::TYPE_CODE_128);
?>

<?php if (isset($_SESSION['alert']['action'])) : ?>
    <div id="alert" 
    data-alertheader="<?= $_SESSION['alert']['header'] ?>"
    data-alertdescription="<?= $_SESSION['alert']['description'] ?>"
    data-alerttype="<?= $_SESSION['alert']['type'] ?>"
    data-alertaction="<?= $_SESSION['alert']['action'] ?>"
    ></div>
<?php endif; SetAlert::deleteAlert(); ?> 
            
<section id="produk">
    <div class="tabledata shadow">
        <div class="pagetitle-tabledata">
            <h2 class="pageTitle">Master Produk</h2>
            <button class="btn edit openModalBtn tabledata-addbtn add-produk"><i class="bi bi-plus-lg"></i> Tambah produk</button>
        </div>

        <div class="tabledata-header">
            <div class="tabledata-group">
                <i class="bi bi-search tabledata-searchicon"></i>
                <input type="text" class="tabledata-searchinput tabledata-produk-search" placeholder="Cari produk...">
            </div>
        </div>

        <div class="tabledata-subheader">
            <p class="tabledata-totaldata">Total <?= count($data['produk']) ?> produk</p>
        </div>

        <div class="tabledata-body">
            <div class="tabledata-body-overflow-lg">
                <div class="tabledata-header">
                    <p>No</p>
                    <p>Nama Produk</p>
                    <p>Satuan Pokok</p>
                    <p>Harga Beli</p>
                    <p>Harga Jual</p>
                    <p>Katalog</p>
                    <p>Actions</p>
                </div>
    
                <form action="<?= BASEURL ?>/headofficeOpp/deleteManyProduk" method="post" class="alertConfirm2">
                    <div id="tabledata-items">
                        <?php if (count($data['produk']) !== 0) { ?>
                            <?php $i = 1; foreach ($data['produk'] as $produk ) : ?>
                                <div class="tabledata-item">
                                    <p class="no"><?= $i++ ?></p><input type="checkbox" class="tabledata-choose-checkbox d-none" name="deletemanyproduk[]" id="<?= $produk['id'] ?>" value="<?= $produk['id'] ?>">
                                    <p><?= $produk['product_name'] ?></p>
                                    <p><?= $produk['satuan_name'] ?></p>
                                    <p>Rp. <?= number_format($produk['harga_beli'],0,",","."); ?></p>
                                    <p>Rp. <?= number_format($produk['harga_jual'],0,",","."); ?></p>
                                    <?php if($produk['catalog'] == 'Tampil'): ?>
                                        <p class="produk-catalog-status produk-catalog-show"><i class="bi bi-check-circle"></i></p>
                                    <?php else : ?>
                                        <p class="produk-catalog-status produk-catalog-dontshow"><i class="bi bi-ban"></i></p>
                                    <?php endif; ?>
                                    <div class="tabledata-actions">
                                        <div class="action openModalBtn detail-produk" data-id="<?= $produk['id'] ?>"><i class="bi bi-eye detail-icon"></i></div>
                                        <div class="action openModalBtn edit-produk" data-id="<?= $produk['id'] ?>"><i class="bi bi-pencil"></i></div>
                                        <a href="<?= BASEURL ?>/headofficeOpp/deleteproduk/<?= $produk['id'] ?>" class="action alertConfirm"><i class="bi bi-trash"></i></a>
                                        <?php if($produk['barqode'] != '') : ?>
                                            <div class="action barqode-produk" data-id="<?= $produk['id'] ?>">
                                                <i class="bi bi-upc barqode-icon"></i>
                                            </div>
                                        <?php else : ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach;?>
                            <?php } else { ?>
                            <div class="tabledata-item" id="tabledata-item-datanotfound-dataempty">data masih kosong</div>
                        <?php } ?>
                    </div>
                    <?php if (count($data['produk']) !== 0) { ?>
                        <div class="tabledata-footer">
                            <div class="choose-items">
                                <button type="button" class="btn edit tabledata-choose">Pilih produk</button>
                                <button type="button" class="btn cancel tabledata-choose-cancel d-none">Batal</button>
                                <button type="submit" class="btn delete tabledata-choose-delete d-none">Hapus</button>
                            </div>
                        </div>
                    <?php } else {} ?>
                </form>
            </div>
        </div>

    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close closeButton">&times;</span>
                <h2 class="modal-title">Modal Title</h2>
            </div>
            <div class="modal-data">
                <div class="modal-body">
                    <form action="<?= BASEURL ?>/headofficeOpp/addProduk" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id" value="id">

                        <div class="form-group">
                            <label for="nama_produk">Nama Produk</label>
                            <input type="text" id="nama_produk" name="nama_produk" required maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="produsen">Nama Pabrik/Produsen</label>
                            <input type="text" id="produsen" name="produsen" required maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="kategori" class="form-label">Kategori Produk</label>
                            <select name="kategori" id="kategori" required>
                                <?php foreach ($data['kategori'] as $kategori) : ?>
                                    <option value="<?= $kategori['id'] ?>"><?= $kategori['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="rak" class="form-label">Rak</label>
                            <select name="rak" id="rak" required>
                                <?php foreach ($data['rak'] as $rak) : ?>
                                    <option value="<?= $rak['id'] ?>"><?= $rak['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="sideButtonContainer">
                                <div class="inputSideButton">
                                    <label for="kode_produk">Kode Produk</label>
                                    <input type="text" id="kode_produk" name="kode_produk" required maxlength="20">
                                </div>
                                <span class="btn edit sideButton generateKodeProduk"><i class="bi bi-arrow-clockwise"></i> Generate</span>
                            </div>
                            <span class="generateKodeProdukAlert d-none">Tolong isi nama produk dan pilih kategori terlebih dahulu !</span>
                        </div>
                        <div class="form-group">
                            <label for="barqode_produk">Barqode<span class="optional-input"> *</span></label>
                            <input type="text" id="barqode_produk" name="barqode_produk" maxlength="20">
                        </div>
                        <div class="form-group">
                            <label for="satuan_pokok" class="form-label">Satuan Pokok (terkecil)</label>
                            <select name="satuan_pokok" id="satuan_pokok" required>
                                <?php foreach ($data['satuan'] as $satuan) : ?>
                                    <option value="<?= $satuan['id'] ?>"><?= $satuan['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="satuan-lainnya-container">
                            
                        </div>
                        <span class="btn edit modal-content-btn tambah-satuan-lainnya inputHargaTrigger">Tambah satuan lainnya</span>
                        <div class="form-group">
                            <label for="harga_beli" class="harga-beli-label">Harga Beli</label>
                            <div class="inputHargaContainer">
                                <span class="rp">Rp</span>
                                <input type="text" id="harga_beli" name="harga_beli" class="inputHarga hargaBeli" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="harga_supply" class="harga-supply-label">Harga Supply</label>
                            <div class="inputHargaContainer">
                                <span class="rp">Rp</span>
                                <input type="text" id="harga_supply" name="harga_supply" class="inputHarga hargaSupply" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="harga_jual" class="harga-jual-label">Harga Jual</label>
                            <div class="inputHargaContainer">
                                <span class="rp">Rp</span>
                                <input type="text" id="harga_jual" name="harga_jual" class="inputHarga hargaJual" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="harga_resep" class="harga-resep-label">Harga Resep</label>
                            <div class="inputHargaContainer">
                                <span class="rp">Rp</span>
                                <input type="text" id="harga_resep" name="harga_resep" class="inputHarga hargaResep" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="zat_aktif">Zat Aktif</label>
                            <input type="text" id="zat_aktif" name="zat_aktif" maxlength="100" required>
                        </div>
                        <div class="form-group">
                            <label for="min_stok">Stok Minimal</label>
                            <input type="number" id="min_stok" name="min_stok" required min="1">
                        </div>
                        <div class="modal-radio-group showKatalog">
                            <h4 class="modal-radio-title">Tampilkan produk di katalog</h4>
                            
                            <div class="modal-radio-item">
                                <input type="radio" name="katalog" id="tampilkan" value="Tampil" required>
                                <label for="tampilkan">Tampilkan</label>
                            </div>
                            <div class="modal-radio-item">
                                <input type="radio" name="katalog" id="tidaktampil" value="Tidak tampil">
                                <label for="tidaktampil">Tidak tampil</label>
                            </div>
                        </div>
                        <div class="form-drop-image gambarProduk">
                            
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="4" required maxlength="500"></textarea>
                        </div>
                        
                        <div class="configAlert d-none">
                            <p class='alertNamaProduk d-none'>Nama produk sudah digunakan !</p>
                            <p class='alertKodeProduk d-none'>Kode produk sudah digunakan !</p>
                            <p class='alertBarqodeProduk d-none'>Barqode produk sudah digunakan !</p>
                            <p class='alertGambarProduk d-none'>Silahkan upload gambar produk !</p>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn cancel close">Cancel</button>
                            <button type="submit" class="btn edit">save</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-detail d-none">
                <div class="modal-detail-header"></div>
                <div class="modal-detail-body"></div>
                <div class="modal-detail-footer">
                    <button type="button" class="btn cancel close">Close</button>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
$(function () {
    // fungsi format ribuan
    function formatRibuan(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // format number input harga
    $("form").on("submit", function (e) {
        e.preventDefault();
        const form = $(this)
        form.find('.inputHarga').each(function() {
            const input = $(this);
            const rawValue = input.val().replace(/\./g, '');
            input.val(rawValue);
        });
        
        form.off('submit').submit();
    })

    //add 
    $(".add-produk").on("click", function () {
        $(".modal-title").html("Tambah produk  baru");
        $(".modal-footer button[type=submit]").html("Tambah");
        $(".modal-body form").attr("action", "<?= BASEURL ?>/headofficeOpp/addProduk" );
        $('.configAlert').addClass('d-none')
        $(".modal-footer button[type=submit]").removeClass('disabled')
        $("#id").val(''),
        $("#nama_produk").val(''),
        $("#produsen").val(''),
        $("#kode_produk").val(''),
        $("#barqode_produk").val(''),
        $("#satuan_pokok").val(''),
        $("#harga_beli").val(''),
        $("#harga_jual").val(''),
        $("#harga_resep").val(''),
        $("#zat_aktif").val(''),
        $("#min_stok").val(''),
        $(".modal input[type=radio]").prop('checked', false),
        $("#kategori").val(''),
        $("#rak").val(''),
        $("#drop_image").val(''),
        $('.image-view').css('background-image', `none`),
        $('.image-view').html(`<img src="<?= BASEURL ?>/assets/img/other/upload file.png" alt=""> <p class="image-view-text">Drag and drop or click here <br>to upload image</p>`),
        $('.image-view').css('border', '2px dashed #7ec9fc'),
        $("#deskripsi").val('')
        $(".satuan-lainnya-container").children().remove();
    });

    function crudOperation(){
        // delete many confirm custom
        $('.alertConfirm2').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: "Apakah Anda Yakin?",
                text: "Anda tidak dapat mengembalikannya lagi!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, hapus!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // detail
        $('.detail-produk').on('click', function() {
            $(".modal-title").html("Detail produk ");
            $('.configAlert').addClass('d-none')
            $(".modal-footer button[type=submit]").removeClass('disabled')
            const id = $(this).data("id");
    
            $.ajax({
                url: "<?= BASEURL ?>/headofficeOpp/getDataEditProduk",
                data: { id: id },
                method: "post",
                dataType: "json",
                success: function (data) {
                    let produkDetail = `<table class="table-detail">
                        <tr>
                            <td class="detailInfo"><p>Nama Produk:</p></td>
                            <td class="detailData">`+data.product_name+`</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Nama Pabrik/Produsen:</p></td>
                            <td class="detailData">`+data.factory_name+`</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Kode Produk:</p></td>
                            <td class="detailData">`+data.product_code+`</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Barqode:</p></td>
                            <td class="detailData">`+data.barqode+`</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Satuan Pokok:</p></td>
                            <td class="detailData">`+data.satuan_name+`</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Harga Beli / `+data.satuan_name+`:</p></td>
                            <td class="detailData">Rp. ${formatRibuan(data.harga_beli)}</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Harga Supply / `+data.satuan_name+`:</p></td>
                            <td class="detailData">Rp. ${formatRibuan(data.harga_supply)}</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Harga Jual / `+data.satuan_name+`:</p></td>
                            <td class="detailData">Rp. ${formatRibuan(data.harga_jual)}</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Harga Resep / `+data.satuan_name+`:</p></td>
                            <td class="detailData">Rp. ${formatRibuan(data.harga_resep)}</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Zat Aktif:</p></td>
                            <td class="detailData">`+data.active_substance+`</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Stok Minimal:</p></td>
                            <td class="detailData">`+data.min_stock+`</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Katalog:</p></td>
                            <td class="detailData">`+data.catalog+`</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Kategori:</p></td>
                            <td class="detailData">`+data.kategori_name+`</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Rak:</p></td>
                            <td class="detailData">`+data.rak_name+`</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Deskripsi:</p></td>
                            <td class="detailData">`+data.description+`</td>
                        </tr>
                    </table>`;

                    $('.modal-detail-body').html(produkDetail)

                    $.ajax({
                        url: "<?= BASEURL ?>/headofficeOpp/getDataEditSatuanLainnya",
                        data: { id: id },
                        method: "post",
                        dataType: "json",
                        success: function (dataSatuan) {
                            let satuanLainnya = '';

                            if(dataSatuan.length != 0) {
                                satuanLainnya += `<tr>
                                    <td class="detailInfo"><p>Satuan Lainnya:</p></td>
                                    <td class="detailData">`;

                                let satuanList = [];
                                for (let i = 0; i < dataSatuan.length; i++) {
                                    const satuan = dataSatuan[i];
                                    satuanList.push(satuan.satuan_name + ` (${satuan.value} ${data.satuan_name})`);
                                }

                                satuanLainnya += satuanList.join(', ');
                                satuanLainnya += `</td></tr>`;
                            }else {
                                satuanLainnya += `<tr>
                                    <td class="detailInfo"><p>Satuan Lainnya:</p></td>
                                    <td class="detailData">-</td>
                                </tr>`;
                            }

                            $('.table-detail').append(satuanLainnya);
                        },
                    });
                },
            });
    
            $('.modal-data').addClass('d-none')
            $('.modal-detail').removeClass('d-none')
        })

        // edit
        $(".edit-produk").on("click", function() {
            $(".modal-title").html("Edit produk");
            $(".modal-footer button[type=submit]").html("Edit");
            $(".modal-body form").attr("action","<?= BASEURL ?>/headofficeOpp/editProduk");
            $('.configAlert').addClass('d-none')
            $(".modal-footer button[type=submit]").removeClass('disabled')

            const id = $(this).data("id");
            let satuanPokok;
    
            $.ajax({
            url: "<?= BASEURL ?>/headofficeOpp/getDataEditProduk",
            data: {id: id},
            method: "post",
            dataType: "json",
            success: function (data) {
                $("#id").val(data.id),
                $("#nama_produk").val(data.product_name),
                $("#produsen").val(data.factory_name),
                $("#kode_produk").val(data.product_code),
                $("#barqode_produk").val(data.barqode),
                $("#satuan_pokok").val(data.main_unit),
                $("#harga_beli").val(formatRibuan(data.harga_beli)),
                $("#harga_supply").val(formatRibuan(data.harga_supply)),
                $("#harga_jual").val(formatRibuan(data.harga_jual)),
                $("#harga_resep").val(formatRibuan(data.harga_resep)),
                $("#zat_aktif").val(data.active_substance),
                $("#min_stok").val(data.min_stock),
                $(`input[name="katalog"][value="${data.catalog}"]`).prop('checked', true),
                $("#kategori").val(data.kategori_id),
                $("#rak").val(data.rak_id);
                if($("#tampilkan").prop("checked") == true) {
                    $(".gambarProduk").html(`<p class="form-drop-image-title">Gambar Produk</p>
                                    <label for="drop_image" class="drop-image-area">
                                        <input type="file" accept="image/*" class="drop-image-input" id="drop_image" name="gambar_produk">
                                        <div class="image-view">
                                            <img src="<?= BASEURL ?>/assets/img/other/upload file.png" alt="">
                                            <p class="image-view-text">Drag and drop or click here <br>to upload image</p>
                                        </div>
                                    </label>`)
                } else if ($("#tidaktampil").prop("checked") == true) {
                    $(".gambarProduk").html("");
                }
                $('.image-view').css('background-image', `url('<?= BASEURL ?>/assets/img/products-img/${data.picture}')`),
                $('.image-view').html(''),
                $('.image-view').css('border', 'none'),
                $("#deskripsi").val(data.description)
                $(".satuan-lainnya-container").children().remove();

                satuanPokok = data.satuan_name;
            },
            });

            $.ajax({
            url: "<?= BASEURL ?>/headofficeOpp/getDataEditSatuanLainnya",
            data: {id: id},
            method: "post",
            dataType: "json",
            success: function (data) {
                data.forEach(d => {
                    let satuanLainnya = `<div class="form-group satuan_lainnya">
                                <div class="satuan_lainnya_sl">
                                    <p class="satuan_lainnya_jumlah">1</p>
                                    <select name="satuan_lainnya[]" class="satuan_lainnya" required>
                                        <?php foreach ($data['satuan'] as $satuan) : ?>
                                            <option value="<?= $satuan['id'] ?>"><?= $satuan['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <p class="satuan_lainnya_comparison"> = </p>
                                <div class="satuan_lainnya_nsl">
                                    <input type="number" name="nilai_satuan_lainnya[]" class="nilai_satuan_lainnya" min="1" required>
                                    <p class="nilai_satuan_lainnya_jumlah">`+satuanPokok+`</p>
                                </div>
                                <span class="btn-sml delete satuan_lainnya_delete inputHargaTrigger"><i class="bi bi-dash-lg"></i></span>
                            </div>`;
                    $(".satuan-lainnya-container").append(satuanLainnya);

                    $(".satuan_lainnya:last").val(d.satuan_id);
                    $(".nilai_satuan_lainnya:last").val(d.value);
                });
            },
            });
    
        });

        // barqode
        $(".barqode-produk").on("click", function() {
            const id = $(this).data("id") 
            $.ajax({
                url: "<?= BASEURL ?>/headofficeOpp/getBarqodeProduk",
                data: { id: id },
                method: "post",
                dataType: "json",
                success: function (data) {
                    
                },
            });
        })

        // delete many items
        $(".tabledata-choose").on('click', function() {
            $(".no").addClass('d-none');
            $(".tabledata-choose-checkbox").removeClass('d-none')
            $(".tabledata-choose").addClass('d-none')
            $(".tabledata-choose-cancel").removeClass('d-none')
            $(".tabledata-choose-delete").removeClass('d-none')
        })
        $(".tabledata-choose-cancel").on('click', function() {
            $(".no").removeClass('d-none');
            $(".tabledata-choose-checkbox").addClass('d-none')
            $(".tabledata-choose").removeClass('d-none')
            $(".tabledata-choose-cancel").addClass('d-none')
            $(".tabledata-choose-delete").addClass('d-none')

            $(".tabledata-choose-checkbox").prop('checked', false);
            $(".tabledata-choose-delete").text(`Hapus`)
        })
        $(".tabledata-choose-delete").addClass('disabled');

        function countItemsChoosed() {
            let count = 0;
            $(".tabledata-choose-checkbox").each(function() {
                if (this.checked) {
                    count++;
                }
            });

            if(count !== 0){
                $(".tabledata-choose-delete").text(`Hapus (${count})`);
                $(".tabledata-choose-delete").removeClass('disabled');
            } else {
                $(".tabledata-choose-delete").text(`Hapus`);
                $(".tabledata-choose-delete").addClass('disabled');
            }
        }

        $(".tabledata-choose-checkbox").on('change', function() {
            countItemsChoosed();
        });
    }

    crudOperation();

    // reset detail
    $(window).on('click', function(e) {
        const $target = $(e.target);

        if ($target.is('.close') || $target.is('#modal')) {
            setTimeout(() => {
                $('.modal-data').removeClass('d-none');
                $('.modal-detail').addClass('d-none');
            }, 300)
        }
    });

    // config
    $('#nama_produk').on('input', function() {
        let keyword = $(this).val();
        $.ajax({
            url: "<?= BASEURL ?>/headofficeOpp/getProdukConfig",
            data: { configdata: keyword },
            method: "post",
            dataType: "json",
            success: function (data) {
                if($(".modal-footer button[type=submit]").html() == "Edit" ) {
                    let $id = $('#id').val();
                    if(data.id == $id) {
                        $(".alertNamaProduk").addClass("d-none");
                        configAlert();
                    } else if (data) {
                        $(".alertNamaProduk").removeClass("d-none");
                        configAlert();
                    } else {
                        $(".alertNamaProduk").addClass("d-none");
                        configAlert();
                    }
                } else if (data) {
                    $(".alertNamaProduk").removeClass("d-none");
                    configAlert();
                } else {
                    $(".alertNamaProduk").addClass("d-none");
                    configAlert();
                }
            },
        })
    })
    $('#kode_produk').on('input', function() {
        let keyword = $(this).val();
        $.ajax({
            url: "<?= BASEURL ?>/headofficeOpp/getProdukConfig",
            data: { configdata: keyword },
            method: "post",
            dataType: "json",
            success: function (data) {
                if($(".modal-footer button[type=submit]").html() == "Edit" ) {
                    let $id = $('#id').val();
                    if(data.id == $id) {
                        $(".alertKodeProduk").addClass("d-none");
                        configAlert();
                    } else if (data) {
                        $(".alertKodeProduk").removeClass("d-none");
                        configAlert();
                    } else {
                        $(".alertKodeProduk").addClass("d-none");
                        configAlert();
                    }
                } else if (data) {
                    $(".alertKodeProduk").removeClass("d-none");
                    configAlert();
                } else {
                    $(".alertKodeProduk").addClass("d-none");
                    configAlert();
                }
            },
        })
    })
    $('#barqode_produk').on('input', function() {
        let keyword = $(this).val();
        if($(this).val() == ''){
            return false
        }
        $.ajax({
            url: "<?= BASEURL ?>/headofficeOpp/getProdukConfig",
            data: { configdata: keyword },
            method: "post",
            dataType: "json",
            success: function (data) {
                if($(".modal-footer button[type=submit]").html() == "Edit" ) {
                    let $id = $('#id').val();
                    if(data.id == $id) {
                        $(".alertBarqodeProduk").addClass("d-none");
                        configAlert();
                    } else if (data) {
                        $('.configAlert').append("<p class='alertBarqodeProduk'>Barqode produk sudah digunakan !</p>")
                        configAlert();
                    } else {
                        $(".alertBarqodeProduk").addClass("d-none");
                        configAlert();
                    }
                } else if (data) {
                    $('.configAlert').append("<p class='alertBarqodeProduk'>Barqode produk sudah digunakan !</p>")
                    configAlert();
                } else {
                    $(".alertBarqodeProduk").addClass("d-none");
                    configAlert();
                }
            },
        })
    })
    function productImageConfig() {
        $("#drop_image").off('change').on('change', function() {
            if ($("#drop_image").prop('files').length === 0 && $(".modal-footer button[type=submit]").html() != "Edit") {
                $(".alertGambarProduk").removeClass("d-none");
            } else {
                $(".alertGambarProduk").addClass("d-none");
                configAlert();
                uploadDropImage();
            }
        });

        $(".form-drop-image").off("dragover").on("dragover", function(e) {
            e.preventDefault();
        });

        $(".form-drop-image").off("drop").on("drop", function(e) {
            e.preventDefault();
            let files = e.originalEvent.dataTransfer.files;
            if (files.length > 0) {
                let dataTransfer = new DataTransfer();
                dataTransfer.items.add(files[0]);
                $("#drop_image").prop("files", dataTransfer.files);
                $("#drop_image").trigger('change');
            }
        });
    }
    function uploadDropImage() {
        let gambar = $('#drop_image').get(0).files[0];
        if (gambar) {
            let imgLink = URL.createObjectURL(gambar);
            $('.image-view').css('background-image', `url(${imgLink})`);
            $('.image-view').html('');
            $('.image-view').css('border', 'none');
        }
    }
    $("form").on("submit", function(e) {
        if($("#tampilkan").prop("checked") == true){
            e.preventDefault();
            if($("#drop_image").prop('files').length === 0 && $(".modal-footer button[type=submit]").html() != "Edit"){
                $(".alertGambarProduk").removeClass("d-none");
                configAlert();
            } else {
                $(".alertGambarProduk").addClass("d-none");
                configAlert();
                $(this).off('submit').submit();
            }
        }
    })
    $("input[name=katalog]").on('change', function(){
        if($(this).val() == "Tampil"){
            configAlert();
        }else {
            $(".alertGambarProduk").addClass("d-none");
            configAlert();
        }
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
            $(".configAlert").addClass('d-none');
            $(".modal-footer button[type=submit]").removeClass('disabled')
        } else {
            $('.configAlert').removeClass('d-none')
            $(".modal-footer button[type=submit]").addClass('disabled')
        }
    }

    // generate code
    function generateProductCode(productName, categoryName, lastId) {
        // Get first 3 letters of the product name
        let productInitials = productName.substring(0, 3).toUpperCase();
            
        // Get first 3 letters of the category name
        let categoryInitials = categoryName.substring(0, 3).toUpperCase();
            
        // Generate the new ID
        let newId = lastId + 1;
            
        // Combine to form the product code
        return `${productInitials}-${categoryInitials}-${newId}`;
    }
    $(".generateKodeProduk").on('click', function(){
        var namaProduk = $('#nama_produk').val();
        var kategori = $('#kategori option:selected').text();

        if (namaProduk === '' || kategori === '') {
            $(".generateKodeProdukAlert").removeClass("d-none")
            return false
        } else {
            $(".generateKodeProdukAlert").addClass("d-none")
        }

        $.ajax({
        url: "<?= BASEURL ?>/headofficeOpp/getLastIdProduk",
        method: "get",
        dataType: 'json',
        success: function (data) {
            let lastProdukId = data.lastProdukId;
            let kode_produk = generateProductCode(namaProduk, kategori, lastProdukId);
            $("#kode_produk").val(kode_produk);
        },

        });
    })

    // search
    $('.tabledata-produk-search').on('keyup', function() {
        let keyword = $(this).val();
        $(".no").removeClass('d-none');
        $(".tabledata-choose-checkbox").addClass('d-none')
        $(".tabledata-choose").removeClass('d-none')
        $(".tabledata-choose-cancel").addClass('d-none')
        $(".tabledata-choose-delete").addClass('d-none')
        $(".tabledata-choose-checkbox").prop('checked', false);
        $(".tabledata-choose-delete").text(`Hapus`)

        $.ajax({
        url: "<?= BASEURL ?>/headofficeOpp/searchProduk",
        data: { keyword: keyword},
        method: "post",
        dataType: "json",
        success: function (data) {
            let i = 1;

            let products = "";
            data.forEach(produk => {
                products += `<div class="tabledata-item">
                    <p class="no">${i++}</p>
                    <input type="checkbox" class="tabledata-choose-checkbox d-none" name="deletemanyproduk[]" id="${produk.id}" value="${produk.id}">
                    <p>${produk.product_name}</p>
                    <p>${produk.satuan_name}</p>
                    <p>Rp. ${formatRibuan(produk.harga_beli)}</p>
                    <p>Rp. ${formatRibuan(produk.harga_jual)}</p>
                    <p class="produk-catalog-status ${produk.catalog == 'Tampil' ? 'produk-catalog-show' : 'produk-catalog-dontshow'}"><i class="bi bi-${produk.catalog == 'Tampil' ? 'check-circle' : 'ban'}"></i></p>
                    <div class="tabledata-actions">
                        <div class="action openModalBtn detail-produk" data-id="${produk.id}"><i class="bi bi-eye detail-icon"></i></div>
                        <div class="action openModalBtn edit-produk" data-id="${produk.id}"><i class="bi bi-pencil"></i></div>
                        <a href="<?= BASEURL ?>/headofficeOpp/deleteproduk/${produk.id}" class="action alertConfirm"><i class="bi bi-trash"></i></a>
                    </div>
                </div>`;
            })

            if(data.length !== 0) {
                $('#tabledata-items').html(products)
            } else {
                $('#tabledata-items').html(`<div class="tabledata-item" id="tabledata-item-datanotfound-dataempty">
                data tidak ditemukan !
                </div>`)
            }
            crudOperation();
        },

        });
    })

    // satuan & satuan lainnya
    $("#satuan_pokok").on("change", function() {
        const satuan = $(this).find('option:selected').text();
        $(".harga-beli-label").text("Harga Beli / " + satuan);
        $(".harga-jual-label").text("Harga Jual / " + satuan);
        $(".harga-resep-label").text("Harga Resep / " + satuan);
        $(".nilai_satuan_lainnya_jumlah").text(satuan)
    })

    $(".tambah-satuan-lainnya").on("click", function() {
        let satuan = $("#satuan_pokok").find('option:selected').text();
        if(satuan == '') {
            let satuanLainnya = `<div class="form-group satuan_lainnya">
                                <div class="satuan_lainnya_sl">
                                    <p class="satuan_lainnya_jumlah">1</p>
                                    <select name="satuan_lainnya[]" class="satuan_lainnya" required>
                                        <?php foreach ($data['satuan'] as $satuan) : ?>
                                            <option value="<?= $satuan['id'] ?>"><?= $satuan['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <p class="satuan_lainnya_comparison"> = </p>
                                <div class="satuan_lainnya_nsl">
                                    <input type="number" name="nilai_satuan_lainnya[]" class="nilai_satuan_lainnya" min="1" required>
                                    <p class="nilai_satuan_lainnya_jumlah">(Satuan Pokok)</p>
                                </div>
                                <span class="btn-sml delete satuan_lainnya_delete inputHargaTrigger"><i class="bi bi-dash-lg"></i></span>
                            </div>`;
            $(".satuan-lainnya-container").append(satuanLainnya);
        } else {
            let satuanLainnya = `<div class="form-group satuan_lainnya">
                                <div class="satuan_lainnya_sl">
                                    <p class="satuan_lainnya_jumlah">1</p>
                                    <select name="satuan_lainnya[]" class="satuan_lainnya" required>
                                        <?php foreach ($data['satuan'] as $satuan) : ?>
                                            <option value="<?= $satuan['id'] ?>"><?= $satuan['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <p class="satuan_lainnya_comparison"> = </p>
                                <div class="satuan_lainnya_nsl">
                                    <input type="number" name="nilai_satuan_lainnya[]" class="nilai_satuan_lainnya" min="1" required>
                                    <p class="nilai_satuan_lainnya_jumlah">`+satuan+`</p>
                                </div>
                                <span class="btn-sml delete satuan_lainnya_delete inputHargaTrigger"><i class="bi bi-dash-lg"></i></span>
                            </div>`;
            $(".satuan-lainnya-container").append(satuanLainnya);
        }
        satuanlainnyarefresh();
    })

    function satuanlainnyarefresh(){
        $(".satuan_lainnya_delete").on("click", function() {
            $(this).parent().remove();
        })

        $(".satuan-lainnya-container").find(".satuan_lainnya").last().val("");

        $(".nilai_satuan_lainnya").on("input", function(){
            if($(this).val() < 1) {
                $(this).val(1);
            }
        })
    }
    satuanlainnyarefresh();
    
    // gambar
    $(".showKatalog").on("change", function(){
        if($("#tampilkan").prop("checked") == true) {
            $(".gambarProduk").html(`<p class="form-drop-image-title">Gambar Produk</p>
                            <label for="drop_image" class="drop-image-area">
                                <input type="file" accept="image/*" class="drop-image-input" id="drop_image" name="gambar_produk">
                                <div class="image-view">
                                    <img src="<?= BASEURL ?>/assets/img/other/upload file.png" alt="">
                                    <p class="image-view-text">Drag and drop or click here <br>to upload image</p>
                                </div>
                            </label>`)
        } else if ($("#tidaktampil").prop("checked") == true) {
            $(".gambarProduk").html("");
        }
        productImageConfig();
    })
    productImageConfig();
});
</script>

<script src="<?= BASEURL?>/assets/js/global-script.js"></script>