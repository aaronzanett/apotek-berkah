<?php
$data['pembelian'] = $this->model('PembelianModel')->getAllPembelian();
?>

<?php if (isset($_SESSION['alert']['action'])) : ?>
    <div id="alert" 
    data-alertheader="<?= $_SESSION['alert']['header'] ?>"
    data-alertdescription="<?= $_SESSION['alert']['description'] ?>"
    data-alerttype="<?= $_SESSION['alert']['type'] ?>"
    data-alertaction="<?= $_SESSION['alert']['action'] ?>"
    ></div>
<?php endif; SetAlert::deleteAlert(); ?> 
            
<section id="pembelian">
<div class="tabledata shadow">
        <div class="pagetitle-tabledata">
            <h2 class="pageTitle">Pembelian</h2>
            <a href="<?= BASEURL ?>/app/headoffice/transaksiPembelian" class="link" data-target="transaksiPembelian">
                <button class="btn edit tabledata-addbtn add-pembelian"><i class="bi bi-plus-lg"></i> Pembelian</button>
            </a>
        </div>

        <div class="tabledata-header">
            <div class="tabledata-group">
                <i class="bi bi-search tabledata-searchicon"></i>
                <input type="text" class="tabledata-searchinput tabledata-pembelian-search" placeholder="Cari pembelian...">
            </div>
        </div>

        <div class="tabledata-body">
            <div class="tabledata-body-overflow-lg">
                <div class="tabledata-header">
                    <p>No</p>
                    <p>Faktur</p>
                    <p>Tanggal/waktu</p>
                    <p>Pembayaran</p>
                    <p>Supplier</p>
                    <p>Pemesan</p>
                    <p>Total</p>
                    <p>Actions</p>
                </div>
    
                <div id="tabledata-items">
                    <?php if (count($data['pembelian']) !== 0) { ?>
                        <?php $i = 1; foreach ($data['pembelian'] as $pembelian ) : ?>
                            <div class="tabledata-item">
                                <p class="no"><?= $i++ ?></p>
                                <p><?= $pembelian['faktur'] ?></p>
                                <p><?= $pembelian['datetime'] ?></p>
                                <p><?= $pembelian['payment'] ?></p>
                                <p><?= $pembelian['supplier'] ?></p>
                                <p><?= $pembelian['orderer'] ?></p>
                                <p>Rp. <?= number_format($pembelian['total_price'],0,",","."); ?></p>
                                <div class="tabledata-actions">
                                    <div class="action openModalBtn detail-pembelian" data-id="<?= $pembelian['id'] ?>"><i class="bi bi-eye detail-icon"></i></div>
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
        $('.detail-pembelian').on('click', function() {
            $(".modal-title").html("Detail pembelian");
            $('.configAlert').addClass('d-none')
            $(".modal-footer button[type=submit]").removeClass('disabled')
            const id = $(this).data("id");
            
            $.ajax({
                url: "<?= BASEURL ?>/headofficeOpp/getDataEditPembelian",
                data: {id: id},
                method: "post",
                dataType: "json",
                success: function (data) {
                    const pembelian = data.pembelian;
                    const pembelianD = data.detail_pembelian;

                    let pembelianDetail = `<table class="table-detail">
                    <tr>
                        <td class="detailInfo"><p>Faktur:</p></td>
                        <td class="detailData">`+pembelian.faktur+`</td>
                    </tr>
                    <tr>
                        <td class="detailInfo"><p>Supplier:</p></td>
                        <td class="detailData">`+pembelian.supplier+`</td>
                    </tr>
                    <tr>
                        <td class="detailInfo"><p>Pemesan:</p></td>
                        <td class="detailData">`+pembelian.orderer+`</td>
                    </tr>
                    <tr>
                        <td class="detailInfo"><p>Pembayaran:</p></td>
                        <td class="detailData">`+pembelian.payment+`</td>
                    </tr>
                    <tr>
                        <td class="detailInfo"><p>Tanggal/waktu:</p></td>
                        <td class="detailData">`+pembelian.datetime+`</td>
                    </tr>
                    <tr>
                        <td class="detailInfo"><p>Note:</p></td>
                        <td class="detailData">`+pembelian.note+`</td>
                    </tr>
                    <tr>
                        <td class="detailInfo"><p>Sub-Total:</p></td>
                        <td class="detailData">Rp. `+formatRibuan(pembelian.subtotal)+`</td>
                    </tr>
                    <tr>
                        <td class="detailInfo"><p>Diskon Total:</p></td>
                        <td class="detailData">`+parseFloat(pembelian.diskon)+` %</td>
                    </tr>
                    <tr>
                        <td class="detailInfo"><p>PPN:</p></td>
                        <td class="detailData">`+parseFloat(pembelian.ppn)+` %</td>
                    </tr>
                    <tr>
                        <td class="detailInfo"><p>Total:</p></td>
                        <td class="detailData">Rp. `+formatRibuan(pembelian.total_price)+`</td>
                    </tr>
                    <tr>
                        <td class="detailInfo"><p>Bayar:</p></td>
                        <td class="detailData">Rp. `+formatRibuan(pembelian.dibayar)+`</td>
                    </tr>
                    <tr>
                        <td class="detailInfo"><p>Utang:</p></td>
                        <td class="detailData">Rp. `+formatRibuan(pembelian.utang)+`</td>
                    </tr>
                    <tr style="border-bottom: 2px solid black">
                        <td class="detailInfo"><br></td>
                        <td class="detailData"></td>
                    </tr>`;

                    let no = 1;
                    for (let i = 0; i < pembelianD.length; i++) {
                        const p = pembelianD[i];

                        pembelianDetail += `
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
                            <td class="detailInfo"><p>Tgl. Kadaluwarsa:</p></td>
                            <td class="detailData">`+p.kadaluwarsa+`</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Kuantitas:</p></td>
                            <td class="detailData">`+p.quantity+` `+p.unit_name+`</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Harga / `+p.unit_name+` (pokok):</p></td>
                            <td class="detailData">Rp. `+formatRibuan(p.base_unit_price)+`</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Jenis Harga:</p></td>
                            <td class="detailData">`+p.jenis_harga+`</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Harga / `+p.unit_name+`</p></td>
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
                        
                    pembelianDetail += `</table>`;

                    $('.modal-detail-body').html(pembelianDetail)
                },
            });
        })
    }
            
    crudOperation();

    // search
    $('.tabledata-pembelian-search').on('input', function() {
        let keyword = $(this).val();

        $.ajax({
            url: "<?= BASEURL ?>/headofficeOpp/searchPembelian",
            data: { keyword: keyword },
            method: "post",
            dataType: "json",
            success: function (data) {
                let i = 1;
                let pembelian = "";
                data.forEach(p => {
                    pembelian += `<div class="tabledata-item">
                                    <p class="no">`+ i++ +`</p>
                                    <p>`+p.faktur+`</p>
                                    <p>`+p.datetime+`</p>
                                    <p>Rp. ${formatRibuan(p.total_price)}</p>
                                    <p>`+p.payment+`</p>
                                    <p>`+p.supplier+`</p>
                                    <p>`+p.orderer+`</p>
                                    <div class="tabledata-actions">
                                        <div class="action openModalBtn detail-pembelian" data-id="`+p.id+`"><i class="bi bi-eye detail-icon"></i></div>
                                    </div>
                                </div>`;
                });

                if(data.length !== 0) {
                    $('#tabledata-items').html(pembelian);
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