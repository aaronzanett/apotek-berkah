<?php
$data['defecta'] = $this->model('ProdukModel')->getAllDefectaHeadoffice();
?>
            
<section id="defecta">
<div class="tabledata shadow">
        <div class="pagetitle-tabledata">
            <h2 class="pageTitle">Defecta</h2>
        </div>

        <div class="tabledata-header">
            <div class="tabledata-group">
                <i class="bi bi-search tabledata-searchicon"></i>
                <input type="text" class="tabledata-searchinput tabledata-produk-search" placeholder="Cari produk...">
            </div>
        </div>

        <div class="tabledata-body">
            <div class="tabledata-body-overflow-sm">
                <div class="tabledata-header">
                    <p>No</p>
                    <p>Nama Produk</p>
                    <p>Stok</p>
                    <p>Stok Minimal</p>
                </div>
    
                <form action="<?= BASEURL ?>/headofficeOpp/deleteManyProduk" method="post" class="alertConfirmProduk2">
                    <div id="tabledata-items">
                        <?php if (count($data['defecta']) !== 0) { ?>
                            <?php $i = 1; foreach ($data['defecta'] as $produk ) : ?>
                                <div class="tabledata-item">
                                <p class="no"><?= $i++ ?></p>
                                    <p><?= $produk['product_name'] ?></p>
                                    <p><?= $produk['total_jumlah'] ?> <?= $produk['satuan'] ?></p>
                                    <p><?= $produk['min_stock'] ?> <?= $produk['satuan'] ?></p>
                                </div>
                            <?php endforeach;?>
                            <?php } else { ?>
                            <div class="tabledata-item" id="tabledata-item-datanotfound-dataempty">tidak ada stok produk yang dibawah stok minimal</div>
                        <?php } ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>


<script>
    // search
    $('.tabledata-produk-search').on('input', function() {
    let keyword = $(this).val();
    $(".no").removeClass('d-none');
    $(".tabledata-choose-checkbox").addClass('d-none')
    $(".tabledata-choose").removeClass('d-none')
    $(".tabledata-choose-cancel").addClass('d-none')
    $(".tabledata-choose-delete").addClass('d-none')
    $(".tabledata-choose-checkbox").prop('checked', false);
    $(".tabledata-choose-delete").text(`Hapus`)

    $.ajax({
        url: "<?= BASEURL ?>/headofficeOpp/searchAllDefectaHeadoffice",
        data: { keyword: keyword },
        method: "post",
        dataType: "json",
        success: function (data) {
            let i = 1;
            let produk = "";
            data.forEach(p => {
                produk += `<div class="tabledata-item">
                                <p class="no">`+ i++ +`</p>
                                <p>`+ p.product_name +`</p>
                                <p>`+ p.total_jumlah + ' ' + p.satuan +`</p>
                                <p>`+ p.min_stock + ' ' + p.satuan +`</p>
                            </div>`;
            });

            if(data.length !== 0) {
                $('#tabledata-items').html(produk);
            } else {
                $('#tabledata-items').html(`<div class="tabledata-item" id="tabledata-item-datanotfound-dataempty">
                    data tidak ditemukan !
                </div>`);
            }
        },
    });
});
</script>

<script src="<?= BASEURL?>/assets/js/global-script.js"></script>