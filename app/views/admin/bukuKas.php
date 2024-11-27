<?php
$data['bukuKasOutlet'] = $this->model('BukuKasModel')->getAllBukuKasOutletById($_SESSION['outlet_id']);
$data['saldoOutlet'] = $this->model('BukuKasModel')->getSaldoOutletById($_SESSION['outlet_id'])['total_nominal'];
?>

<!-- <?php if (isset($_SESSION['alert']['action'])) : ?>
    <div id="alert" 
    data-alertheader="<?= $_SESSION['alert']['header'] ?>"
    data-alertdescription="<?= $_SESSION['alert']['description'] ?>"
    data-alerttype="<?= $_SESSION['alert']['type'] ?>"
    data-alertaction="<?= $_SESSION['alert']['action'] ?>"
    ></div>
<?php endif; SetAlert::deleteAlert();?>  -->
            
<section id="bukuKas">
    <div class="tabledata shadow">
        <div class="pagetitle-tabledata">
            <h2 class="pageTitle">Buku Kas</h2>
            <!-- <button class="btn edit openModalBtn tabledata-addbtn add-bukuKas"><i class="bi bi-plus-lg"></i> Tambah pemasukan/pengeluaran</button> -->
        </div>

        <div class="tabledata-header">
            <p></p>
            <!-- <div class="tabledata-group">
                <i class="bi bi-search tabledata-searchicon"></i>
                <input type="text" class="tabledata-searchinput tabledata-bukuKas-search" placeholder="Cari transaksi...">
            </div> -->
            <h3 class="saldoSaatIni">Saldo: Rp. <?= number_format($data['saldoOutlet'],0,",","."); ?></h3>
        </div>

        <div class="tabledata-subheader">
            <p class="tabledata-totaldata">Total <?= count($data['bukuKasOutlet']) ?> kontak</p>
        </div>

        <div class="tabledata-body">
            <div class="tabledata-body-overflow-lg">
                <div class="tabledata-header">
                    <p>No</p>
                    <p>Status</p>
                    <p>Faktur</p>
                    <p>Aktivitas</p>
                    <p>User</p>
                    <p>Waktu</p>
                    <p>Nominal</p>
                </div>
    
                <div id="tabledata-items">
                    <?php if (count($data['bukuKasOutlet']) !== 0) { ?>
                        <?php $i = 1; foreach ($data['bukuKasOutlet'] as $b ) : ?>
                            <div class="tabledata-item">
                                <p class="no"><?= $i++ ?></p>
                                <p><?= $b['status'] ?></p>
                                <p><?= $b['faktur'] ?></p>
                                <p><?= $b['activity'] ?></p>
                                <p><?= $b['user'] ?></p>
                                <p><?= $b['datetime'] ?></p>
                                <p>Rp. <?= number_format($b['total'],0,",","."); ?></p>
                                <!-- <?php if($b['cTransaction'] == "common") : ?>
                                <div class="tabledata-actions">
                                    <div class="action openModalBtn edit-bukuKas" data-id="<?= $b['id'] ?>"><i class="bi bi-pencil"></i></div>
                                    <a href="<?= BASEURL ?>/adminOpp/deletebukuKas/<?= $b['id'] ?>" class="action alertConfirm"><i class="bi bi-trash"></i></a>
                                </div>
                                <?php endif; ?> -->
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
                    <form action="<?= BASEURL ?>/adminOpp/addBukuKas" method="post">
                        <input type="hidden" name="id" id="id" value="id">

                        <div class="form-group">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" required>
                                <option value="pemasukan">pemasukan</option>
                                <option value="pengeluaran">pengeluaran</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="faktur">Faktur</label>
                            <input type="text" id="faktur" name="faktur" maxlength="100" required>
                        </div>
                        <div class="form-group">
                            <label for="aktivitas">Aktivitas</label>
                            <input type="aktivitas" id="aktivitas" name="aktivitas" required maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="user">User</label>
                            <input type="text" id="user" name="user" readonly value="<?= $_SESSION['pengguna'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="datetime">Tanggal/waktu</label>
                            <input type="datetime-local" id="datetime" name="datetime" required maxlength="50">
                        </div>
                        <div class="form-group">
                            <label for="nominal">Nominal</label>
                            <div class="inputHargaContainer">
                                <span class="rp">Rp</span>
                                <input type="text" id="nominal" class="inputHarga"  name="nominal">
                            </div>
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
    //add 
    $(".add-bukuKas").on("click", function () {
        $(".modal-title").html("Tambah kontak karyawan baru");
        $(".modal-footer button[type=submit]").html("Tambah");
        $(".modal-body form").attr("action", "<?= BASEURL ?>/adminOpp/addBukuKas" );
        $('.configAlert').addClass('d-none')
        $(".modal-footer button[type=submit]").removeClass('disabled')
        $("#id").val(''),
        $("#status").val(''),
        $("#faktur").val(''),
        $("#aktivitas").val(''),
        $("#datetime").val('')
        $("#nominal").val('')
    });

    // format number input harga
    $("form").on("submit", function (e) {
        const form = $(this)
        form.find('.inputHarga').each(function() {
            const input = $(this);
            const rawValue = input.val().replace(/\./g, '');
            input.val(rawValue);
        });
    })

    // fungsi format ribuan
    function formatRibuan(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    function crudOperation(){
        // edit
        $(".edit-bukuKas").on("click", function () {
            $(".modal-title").html("Edit kontak karyawan");
            $(".modal-footer button[type=submit]").html("Edit");
            $(".modal-body form").attr("action", "<?= BASEURL ?>/adminOpp/editBukuKas");
            $('.configAlert').addClass('d-none');
            $(".modal-footer button[type=submit]").removeClass('disabled');

            const id = $(this).data("id");

            $.ajax({
                url: "<?= BASEURL ?>/adminOpp/getDataEditBukuKas",
                data: {id: id},
                method: "post",
                dataType: "json",
                success: function (data) {
                    $("#id").val(data.id);
                    $("#status").val(data.status);
                    $("#faktur").val(data.faktur);
                    $("#aktivitas").val(data.activity);
                    $("#user").val(data.user);
                    $("#datetime").val(data.datetime);
                    $("#nominal").val(formatRibuan(data.total));
                },
            });
        });
    }
</script>

<script src="<?= BASEURL?>/assets/js/global-script.js"></script>