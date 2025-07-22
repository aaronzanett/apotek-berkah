<?php
$data['penjualan'] = $this->model('PenjualanModel')->getAllPenjualan();
?>

<?php if (isset($_SESSION['alert']['action'])) : ?>
    <div id="alert" 
    data-alertheader="<?= $_SESSION['alert']['header'] ?>"
    data-alertdescription="<?= $_SESSION['alert']['description'] ?>"
    data-alerttype="<?= $_SESSION['alert']['type'] ?>"
    data-alertaction="<?= $_SESSION['alert']['action'] ?>"
    ></div>
<?php endif; SetAlert::deleteAlert(); ?> 
            
<section id="penjualan">
<div class="tabledata shadow">
        <div class="pagetitle-tabledata">
            <h2 class="pageTitle">Penjualan outlet</h2>
        </div>

        <div class="tabledata-header">
            <div class="tabledata-group">
                <i class="bi bi-search tabledata-searchicon"></i>
                <input type="text" class="tabledata-searchinput tabledata-penjualan-search" placeholder="Cari penjualan...">
            </div>
        </div>

        <div class="tabledata-body">
            <div class="tabledata-body-overflow-lg">
                <div class="tabledata-header">
                    <p>No</p>
                    <p>Faktur</p>
                    <p>Nama outlet</p>
                    <p>Tanggal/waktu</p>
                    <p>Pembayaran</p>
                    <p>Kasir</p>
                    <p>Total</p>
                    <p>Actions</p>
                </div>
    
                
                <div id="tabledata-items">
                    <?php if (count($data['penjualan']) !== 0) { ?>
                        <?php $i = 1; foreach ($data['penjualan'] as $penjualan ) : ?>
                            <div class="tabledata-item">
                                <p class="no"><?= $i++ ?></p>
                                <p><?= $penjualan['faktur'] ?></p>
                                <p><?= $penjualan['outlet_name'] ?></p>
                                <p><?= $penjualan['datetime'] ?></p>
                                <p><?= $penjualan['payment'] ?></p>
                                <p><?= $penjualan['cashier'] ?></p>
                                <p>Rp. <?= number_format($penjualan['total_price'],0,",","."); ?></p>
                                <div class="tabledata-actions">
                                    <div class="action openModalBtn detail-penjualan" data-id="<?= $penjualan['id'] ?>"><i class="bi bi-eye detail-icon"></i></div>
                                </div>
                            </div>
                        <?php endforeach;?>
                    <?php } else { ?>
                        <div class="tabledata-item" id="tabledata-item-datanotfound-dataempty">data masih kosong</div>
                    <?php } ?>
                </div>
            </div>
        </div>

    </div>
    <div id="modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close closeButton">&times;</span>
                <h2 class="modal-title">Modal Title</h2>
            </div>
            <div class="modal-detail">
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
    // fungsi format ribuan
    function formatRibuan(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    function crudOperation(){
        // detail
        $('.detail-penjualan').on('click', function() {
            $(".modal-title").html("Detail penjualan");
            $('.configAlert').addClass('d-none')
            $(".modal-footer button[type=submit]").removeClass('disabled')
            const id = $(this).data("id");
            
            $.ajax({
                url: "<?= BASEURL ?>/adminOpp/getDataEditPenjualan",
                data: {id: id},
                method: "post",
                dataType: "json",
                success: function (data) {
                    const penjualan = data.penjualan;
                    const penjualanD = data.detail_penjualan;

                    let penjualanDetail = `<table class="table-detail">
                    <tr>
                        <td class="detailInfo"><p>Faktur:</p></td>
                        <td class="detailData">`+penjualan.faktur+`</td>
                    </tr>
                    <tr>
                        <td class="detailInfo"><p>Kasir:</p></td>
                        <td class="detailData">`+penjualan.cashier+`</td>
                    </tr>
                    <tr>
                        <td class="detailInfo"><p>Pembayaran:</p></td>
                        <td class="detailData">`+penjualan.payment+`</td>
                    </tr>
                    <tr>
                        <td class="detailInfo"><p>Tanggal/waktu:</p></td>
                        <td class="detailData">`+penjualan.datetime+`</td>
                    </tr>
                    <tr>
                        <td class="detailInfo"><p>Note:</p></td>
                        <td class="detailData">`+penjualan.note+`</td>
                    </tr>
                    <tr>
                        <td class="detailInfo"><p>Diskon Total:</p></td>
                        <td class="detailData">`+parseFloat(penjualan.diskon)+` %</td>
                    </tr>
                    <tr>
                        <td class="detailInfo"><p>Sub-Total:</p></td>
                        <td class="detailData">Rp. `+formatRibuan(penjualan.subtotal)+`</td>
                    </tr>
                    <tr>
                        <td class="detailInfo"><p>Biaya embalase:</p></td>
                        <td class="detailData">Rp. `+formatRibuan(penjualan.biaya_embalase)+`</td>
                    </tr>
                    <tr>
                        <td class="detailInfo"><p>Ongkos kirim:</p></td>
                        <td class="detailData">Rp. `+formatRibuan(penjualan.ongkos_kirim)+`</td>
                    </tr>
                    <tr>
                        <td class="detailInfo"><p>Biaya lainnya:</p></td>
                        <td class="detailData">Rp. `+formatRibuan(penjualan.biaya_lainnya)+`</td>
                    </tr>
                    <tr>
                        <td class="detailInfo"><p>Total:</p></td>
                        <td class="detailData">Rp. `+formatRibuan(penjualan.total_price)+`</td>
                    </tr>
                    <tr>
                        <td class="detailInfo"><p>Bayar:</p></td>
                        <td class="detailData">Rp. `+formatRibuan(penjualan.dibayar)+`</td>
                    </tr>
                    <tr>
                        <td class="detailInfo"><p>Kembalian:</p></td>
                        <td class="detailData">Rp. `+formatRibuan(penjualan.kembalian)+`</td>
                    </tr>
                    <tr style="border-bottom: 2px solid black">
                        <td class="detailInfo"><br></td>
                        <td class="detailData"></td>
                    </tr>`;

                    let no = 1;
                    for (let i = 0; i < penjualanD.length; i++) {
                        const p = penjualanD[i];

                        penjualanDetail += `
                        <tr>
                            <td class="detailInfo"><br></td>
                            <td class="detailData"></td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Item `+ no++ +`</p></td>
                            <td class="detailData"></td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Nama Produk:</p></td>
                            <td class="detailData">`+p.produk_name+`</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Kuantitas:</p></td>
                            <td class="detailData">`+p.quantity+` `+p.unit_name+`</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Harga / `+p.unit_name+` (pokok):</p></td>
                            <td class="detailData">Rp. `+formatRibuan(p.harga_jual_pokok)+`</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Jenis Harga:</p></td>
                            <td class="detailData">`+p.jenis_harga+`</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Harga / `+p.unit_name+`:</p></td>
                            <td class="detailData">Rp. `+formatRibuan(p.unit_price)+`</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Diskon:</p></td>
                            <td class="detailData">`+parseFloat(p.diskon)+` %</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Subtotal:</p></td>
                            <td class="detailData">Rp. `+formatRibuan(p.subtotal)+`</td>
                        </tr>
                        <tr style="border-bottom: 1px dashed rgb(90,90,90)">
                            <td class="detailInfo"><br></td>
                            <td class="detailData"></td>
                        </tr>
                    `
                    }
                        
                    penjualanDetail += `</table>`;

                    $('.modal-detail-body').html(penjualanDetail)
                },
            });
        })
    }
            
    crudOperation();

    // search
    $('.tabledata-penjualan-search').on('input', function() {
        let keyword = $(this).val();
        let outlet_id = <?= $_SESSION['outlet_id'] ?>;

        $.ajax({
            url: "<?= BASEURL ?>/adminOpp/searchPenjualan",
            data: { outlet_id: outlet_id, keyword: keyword },
            method: "post",
            dataType: "json",
            success: function (data) {
                let i = 1;
                let penjualan = "";
                data.forEach(p => {
                    penjualan += `<div class="tabledata-item">
                                <p class="no">`+ i++ +`</p>
                                <p>`+ p.faktur +`</p>
                                <p>`+ p.datetime +`</p>
                                <p>`+ p.payment +`</p>
                                <p>`+ p.cashier +`</p>
                                <p>Rp. `+ formatRibuan(p.total_price) +`</p>
                                <div class="tabledata-actions">
                                    <div class="action openModalBtn detail-penjualan" data-id="`+ p.id +`"><i class="bi bi-eye detail-icon"></i></div>
                                </div>
                            </div>`;
                });

                if(data.length !== 0) {
                    $('#tabledata-items').html(penjualan);
                } else {
                    $('#tabledata-items').html(`<div class="tabledata-item" id="tabledata-item-datanotfound-dataempty">
                        data tidak ditemukan !
                    </div>`);
                }

                crudOperation();
            },
        });
    });
</script>

<script src="<?= BASEURL?>/assets/js/global-script.js"></script>