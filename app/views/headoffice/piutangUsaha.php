<?php
$data['piutangHeadoffice'] = $this->model('UtangPiutang')->getAllPiutang();
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
            <h2 class="pageTitle">Piutang usaha (outlet)</h2>
        </div>

        <div class="tabledata-header">
            <div class="tabledata-group">
                <i class="bi bi-search tabledata-searchicon"></i>
                <input type="text" class="tabledata-searchinput tabledata-piutang-search" placeholder="Cari piutang...">
            </div>
        </div>

        <div class="tabledata-body">
            <div class="tabledata-body-overflow-lg">
                <div class="tabledata-header">
                    <p>No</p>
                    <p>Faktur</p>
                    <p>Tagihan awal</p>
                    <p>Telah dibayar</p>
                    <p>Sisa piutang</p>
                    <p>Jatuh_tempo</p>
                    <p>Actions</p>
                </div>
    
                
                <div id="tabledata-items">
                    <?php if (count($data['piutangHeadoffice']) !== 0) { ?>
                        <?php $i = 1; foreach ($data['piutangHeadoffice'] as $piutang ) : ?>
                            <div class="tabledata-item">
                                <p class="no"><?= $i++ ?></p>
                                <p><?= $piutang['faktur'] ?></p>
                                <p>Rp. <?= number_format($piutang['tagihan_awal'],0,",","."); ?></p>
                                <p>Rp. <?= number_format($piutang['telah_dibayar'],0,",","."); ?></p>
                                <p>Rp. <?= number_format($piutang['total_sisa'],0,",","."); ?></p>
                                <p><?= $piutang['jatuh_tempo'] ?></p>
                                <div class="tabledata-actions">
                                    <div class="action openModalBtn bayar-piutang" data-id="<?= $piutang['id'] ?>" data-sisapiutang="<?= $piutang['total_sisa'] ?>" data-faktur="<?= $piutang['faktur'] ?>"><i class="bi bi-cash-stack"></i></i></div>
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
            <div class="modal-data">
                <div class="modal-body">
                    <form action="<?= BASEURL ?>/headofficeOpp/bayarPiutang" method="post">
                        <input type="hidden" name="id" id="id" value="id">
                        <input type="hidden" name="headofficer" id="headofficer" value="<?= $_SESSION['pengguna'] ?>">
                        <input type="hidden" name="faktur" id="faktur" value="">

                        <div class="form-group">
                            <label for="sisaPiutang">Sisa Piutang</label>
                            <div class="inputHargaContainer">
                                <span class="rp">Rp</span>
                                <input type="text" id="sisaPiutang" class="inputHarga" name="sisaPiutang" readonly>
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
        $('.bayar-piutang').on('click', function() {
            $("#bayar").val("")
            $("#faktur").val("")
            $(".modal-title").html("Bayar piutang");
            $('.configAlert').addClass('d-none')
            $(".modal-footer button[type=submit]").removeClass('disabled')
            const id = $(this).data("id");
            const sisaPiutang = $(this).data("sisapiutang")
            const faktur = $(this).data("faktur")
            $(".modal-body form").attr("action", "<?= BASEURL ?>/headofficeOpp/bayarPiutang/" + id);
            $("#id").val(id)
            $("#sisaPiutang").val(formatRibuan(sisaPiutang))
            $("#faktur").val(faktur)
        })
    }
    $("#bayar").on('input', function(){
        let sisaPiutang = parseInt($("#sisaPiutang").val().replace(/\./g, '') || 0);
        let bayar = parseInt($("#bayar").val().replace(/\./g, '') || 0);

        if(bayar > sisaPiutang){
            $("#bayar").val(formatRibuan(sisaPiutang))
        }
    })
            
    crudOperation();

    // search
    $('.tabledata-piutang-search').on('input', function() {
        let keyword = $(this).val();
        let outlet_id = <?= $_SESSION['outlet_id'] ?>;

        $.ajax({
            url: "<?= BASEURL ?>/adminOpp/searchPiutangByIdOutlet",
            data: { outlet_id: outlet_id, keyword: keyword },
            method: "post",
            dataType: "json",
            success: function (data) {
                let i = 1;
                let piutang = "";
                data.forEach(p => {
                    piutang += `<div class="tabledata-item">
                                <p class="no">`+ i++ +`</p>
                                <p>`+ p.faktur +`</p>
                                <p>`+ p.datetime +`</p>
                                <p>`+ p.payment +`</p>
                                <p>`+ p.cashier +`</p>
                                <p>Rp. `+ formatRibuan(p.total_price) +`</p>
                                <div class="tabledata-actions">
                                    <div class="action openModalBtn detail-piutang" data-id="`+ p.id +`"><i class="bi bi-eye detail-icon"></i></div>
                                </div>
                            </div>`;
                });

                if(data.length !== 0) {
                    $('#tabledata-items').html(piutang);
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