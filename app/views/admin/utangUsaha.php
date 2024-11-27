<?php
$data['utangAdmin'] = $this->model('UtangPiutang')->getAllPiutangByOutletId($_SESSION['outlet_id']);
?>

<?php if (isset($_SESSION['alert']['action'])) : ?>
    <div id="alert" 
    data-alertheader="<?= $_SESSION['alert']['header'] ?>"
    data-alertdescription="<?= $_SESSION['alert']['description'] ?>"
    data-alerttype="<?= $_SESSION['alert']['type'] ?>"
    data-alertaction="<?= $_SESSION['alert']['action'] ?>"
    ></div>
<?php endif; SetAlert::deleteAlert(); ?> 
            
<section id="utangPiutang">
<div class="tabledata shadow">
        <div class="pagetitle-tabledata">
            <h2 class="pageTitle">Utang usaha</h2>
        </div>

        <div class="tabledata-header">
            <div class="tabledata-group">
                <i class="bi bi-search tabledata-searchicon"></i>
                <input type="text" class="tabledata-searchinput tabledata-utang-search" placeholder="Cari utang...">
            </div>
        </div>

        <div class="tabledata-body">
            <div class="tabledata-body-overflow-lg">
                <div class="tabledata-header">
                    <p>No</p>
                    <p>Faktur</p>
                    <p>Tagihan awal</p>
                    <p>Telah dibayar</p>
                    <p>Sisa utang</p>
                    <p>Jatuh_tempo</p>
                </div>
    
                
                <div id="tabledata-items">
                    <?php if (count($data['utangAdmin']) !== 0) { ?>
                        <?php $i = 1; foreach ($data['utangAdmin'] as $utang ) : ?>
                            <div class="tabledata-item">
                                <p class="no"><?= $i++ ?></p>
                                <p><?= $utang['faktur'] ?></p>
                                <p>Rp. <?= number_format($utang['tagihan_awal'],0,",","."); ?></p>
                                <p>Rp. <?= number_format($utang['telah_dibayar'],0,",","."); ?></p>
                                <p>Rp. <?= number_format($utang['total_sisa'],0,",","."); ?></p>
                                <p><?= $utang['jatuh_tempo'] ?></p>
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
            <div class="modal-data">
                <div class="modal-body">
                    <form action="<?= BASEURL ?>/headofficeOpp/bayarUtang" method="post">
                        <input type="hidden" name="id" id="id" value="id">
                        <input type="hidden" name="headofficer" id="headofficer" value="<?= $_SESSION['pengguna'] ?>">
                        <input type="hidden" name="faktur" id="faktur" value="">

                        <div class="form-group">
                            <label for="sisaUtang">Sisa Utang</label>
                            <div class="inputHargaContainer">
                                <span class="rp">Rp</span>
                                <input type="text" id="sisaUtang" class="inputHarga" name="sisaUtang" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="bayar">Bayar</label>
                            <div class="inputHargaContainer">
                                <span class="rp">Rp</span>
                                <input type="text" id="bayar" class="inputHarga" name="bayar" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="datetime">Tanggal/waktu</label>
                            <input type="datetime-local" id="datetime" class="datetimenow" name="datetime" readonly>
                        </div>
                        
                        <div class="configAlert d-none">
                            <p>Nama rak sudah digunakan!</p>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn cancel close">Cancel</button>
                            <button type="submit" class="btn edit">save</button>
                        </div>
                    </form>
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

    $("form").on("submit", function(e){
        e.preventDefault();
        let form = $(this)
        form.find('.inputHarga').each(function() {
            const input = $(this);
            const rawValue = input.val().replace(/\./g, '');
            input.val(rawValue);
        });
        form.off("submit").submit();
    })
    
    function crudOperation(){
        // bayar
        $('.bayar-utang').on('click', function() {
            $("#bayar").val("")
            $("#faktur").val("")
            $(".modal-title").html("Bayar utang");
            $('.configAlert').addClass('d-none')
            $(".modal-footer button[type=submit]").removeClass('disabled')
            const id = $(this).data("id");
            const sisaUtang = $(this).data("sisautang")
            const faktur = $(this).data("faktur")
            $(".modal-body form").attr("action", "<?= BASEURL ?>/headofficeOpp/bayarUtang/" + id);
            $("#id").val(id)
            $("#sisaUtang").val(formatRibuan(sisaUtang))
            $("#faktur").val(faktur)
        })
    }
    $("#bayar").on('input', function(){
        let sisaUtang = parseInt($("#sisaUtang").val().replace(/\./g, '') || 0);
        let bayar = parseInt($("#bayar").val().replace(/\./g, '') || 0);

        if(bayar > sisaUtang){
            $("#bayar").val(formatRibuan(sisaUtang))
        }
    })
            
    crudOperation();

    // search
    $('.tabledata-utang-search').on('input', function() {
        let keyword = $(this).val();
        let outlet_id = <?= $_SESSION['outlet_id'] ?>;

        $.ajax({
            url: "<?= BASEURL ?>/adminOpp/searchUtangByIdOutlet",
            data: { outlet_id: outlet_id, keyword: keyword },
            method: "post",
            dataType: "json",
            success: function (data) {
                let i = 1;
                let utang = "";
                data.forEach(p => {
                    utang += `<div class="tabledata-item">
                                <p class="no">`+ i++ +`</p>
                                <p>`+ p.faktur +`</p>
                                <p>`+ p.datetime +`</p>
                                <p>`+ p.payment +`</p>
                                <p>`+ p.cashier +`</p>
                                <p>Rp. `+ formatRibuan(p.total_price) +`</p>
                                <div class="tabledata-actions">
                                    <div class="action openModalBtn detail-utang" data-id="`+ p.id +`"><i class="bi bi-eye detail-icon"></i></div>
                                </div>
                            </div>`;
                });

                if(data.length !== 0) {
                    $('#tabledata-items').html(utang);
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