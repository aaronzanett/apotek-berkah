<?php if (isset($_SESSION['alert']['action'])) : ?>
    <div id="alert" 
    data-alertheader="<?= $_SESSION['alert']['header'] ?>"
    data-alertdescription="<?= $_SESSION['alert']['description'] ?>"
    data-alerttype="<?= $_SESSION['alert']['type'] ?>"
    data-alertaction="<?= $_SESSION['alert']['action'] ?>"
    ></div>
<?php endif; SetAlert::deleteAlert(); ?> 
            
<section id="kontak">
    <div class="tabledata shadow">
        <div class="pagetitle-tabledata">
            <h2 class="pageTitle">Kontak Supplier</h2>
            <button class="btn edit openModalBtn tabledata-addbtn add-kontakSupplier"><i class="bi bi-plus-lg"></i> Tambah kontak</button>
        </div>

        <div class="tabledata-header">
            <div class="tabledata-group">
                <i class="bi bi-search tabledata-searchicon"></i>
                <input type="text" class="tabledata-searchinput tabledata-kontakSupplier-search" placeholder="Cari kontak supplier...">
            </div>
        </div>

        <div class="tabledata-subheader">
            <p class="tabledata-totaldata">Total <?= count($data['kontakSupplier']) ?> kontak</p>
        </div>

        <div class="tabledata-body">
            <div class="tabledata-body-overflow-md">
                <div class="tabledata-header">
                    <p>No</p>
                    <p>Nama Lengkap</p>
                    <p>No Telepon</p>
                    <p>Actions</p>
                </div>
    
                <form action="<?= BASEURL ?>/headofficeOpp/deleteManyKontakSupplier" method="post" class="alertConfirmKontakSupplier2">
                    <div id="tabledata-items">
                        <?php if (count($data['kontakSupplier']) !== 0) { ?>
                            <?php $i = 1; foreach ($data['kontakSupplier'] as $kontak ) : ?>
                                <div class="tabledata-item">
                                    <p class="no"><?= $i++ ?></p><input type="checkbox" class="tabledata-choose-checkbox d-none" name="deletemanycontacts[]" id="<?= $kontak['id'] ?>" value="<?= $kontak['id'] ?>">
                                    <p><?= $kontak['fullname'] ?></p>
                                    <p><?= $kontak['phonenumber'] ?></p>
                                    <div class="tabledata-actions">
                                        <div class="action openModalBtn detail-kontakSupplier" data-id="<?= $kontak['id'] ?>"><i class="bi bi-eye detail-icon"></i></div>
                                        <div class="action openModalBtn edit-kontakSupplier" data-id="<?= $kontak['id'] ?>"><i class="bi bi-pencil"></i></div>
                                        <a href="<?= BASEURL ?>/headofficeOpp/deleteKontakSupplier/<?= $kontak['id'] ?>" class="action alertConfirmKontakSupplier"><i class="bi bi-trash"></i></a>
                                    </div>
                                </div>
                            <?php endforeach;?>
                            <?php } else { ?>
                            <div class="tabledata-item" id="tabledata-item-datanotfound-dataempty">data masih kosong</div>
                        <?php } ?>
                    </div>
                <?php if (count($data['kontakSupplier']) !== 0) { ?>
                    <div class="tabledata-footer">
                        <div class="choose-items">
                            <button type="button" class="btn edit tabledata-choose">Pilih kontak</button>
                            <button type="button" class="btn cancel tabledata-choose-cancel d-none">Batal</button>
                            <button type="submit" class="btn delete tabledata-choose-delete d-none alertConfirmKontakSupplier">Hapus</button>
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
                    <form action="<?= BASEURL ?>/headofficeOpp/addKontakSupplier" method="post">
                        <input type="hidden" name="id" id="id" value="id">

                        <div class="form-group">
                            <label for="fullname">Nama Lengkap</label>
                            <input type="text" id="fullname" name="fullname" required maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="phonenumber">No Telepon</label>
                            <input type="number" id="phonenumber" name="phonenumber" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="address">Alamat</label>
                            <input type="text" id="address" name="address" required maxlength="200">
                        </div>
                        <div class="form-group">
                            <label for="rekening">No Rekening</label>
                            <input type="number" id="rekening" name="rekening" required maxlength="50">
                        </div>
                        <div class="form-group">
                            <label for="note">Note<span class="optional-input"> *</span></label>
                            <textarea name="note" id="note" rows="4" maxlength="500"></textarea>
                        </div>
                        
                        <div class="configAlert d-none">
                            <p>No Telepon sudah ada!</p>
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
    //add 
    $(".add-kontakSupplier").on("click", function () {
        $(".modal-title").html("Tambah kontak supplier baru");
        $(".modal-footer button[type=submit]").html("Tambah");
        $(".modal-body form").attr("action", "<?= BASEURL ?>/headofficeOpp/addKontakSupplier" );
        $('.configAlert').addClass('d-none')
        $(".modal-footer button[type=submit]").removeClass('disabled')
        $("#id").val(''),
        $("#fullname").val(''),
        $("#phonenumber").val(''),
        $("#email").val(''),
        $("#address").val(''),
        $("#rekening").val('')
    });

    function crudOperation(){
        // delete confirm custom
        $('.alertConfirmKontakSupplier').on('click', function(e) {
            const href = $(this).attr('href');
            e.preventDefault();
            Swal.fire({
            title: "Apakah Anda Yakin?",
            text: "Semua akun pengguna yang berkaitan dengan supplier ini akan ikut terhapus!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus!",
            reverseButtons: true
            }).then((result) => {
            if(result.isConfirmed) {
                document.location.href = href;
            }
            });
        })
        // delete many confirm custom
        $('.alertConfirmKontakSupplier2').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: "Apakah Anda Yakin?",
                text: "Semua akun pengguna yang berkaitan dengan supplier ini akan ikut terhapus!",
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

        // edit
        $(".edit-kontakSupplier").on("click", function () {
            $(".modal-title").html("Edit kontak supplier");
            $(".modal-footer button[type=submit]").html("Edit");
            $(".modal-body form").attr("action","<?= BASEURL ?>/headofficeOpp/editKontakSupplier");
            $('.configAlert').addClass('d-none')
            $(".modal-footer button[type=submit]").removeClass('disabled')

            const id = $(this).data("id");
    
            $.ajax({
            url: "<?= BASEURL ?>/headofficeOpp/getDataEditKontakSupplier",
            data: {id: id},
            method: "post",
            dataType: "json",
            success: function (data) {
                $("#id").val(data.id),
                $("#fullname").val(data.fullname),
                $("#phonenumber").val(data.phonenumber),
                $("#email").val(data.email),
                $("#address").val(data.address),
                $("#rekening").val(data.rekening),
                $("#note").val(data.note)
            },
            });
    
        });
    
        // detail
        $('.detail-kontakSupplier').on('click', function() {
            $(".modal-title").html("Detail kontak supplier");
            $('.configAlert').addClass('d-none')
            $(".modal-footer button[type=submit]").removeClass('disabled')
            const id = $(this).data("id");
    
            $.ajax({
                url: "<?= BASEURL ?>/headofficeOpp/getDataEditKontakSupplier",
                data: { id: id },
                method: "post",
                dataType: "json",
                success: function (data) {
                    let kontakSupplierDetail = `<table class="table-detail">
                        <tr>
                            <td class="detailInfo"><p>Nama Lengkap:</p></td>
                            <td class="detailData">`+data.fullname+`</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>No Telepon:</p></td>
                            <td class="detailData">`+data.phonenumber+`</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Email:</p></td>
                            <td class="detailData">`+data.email+`</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Alamat:</p></td>
                            <td class="detailData">`+data.address+`</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>No Rekening:</p></td>
                            <td class="detailData">`+data.rekening+`</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Note:</p></td>
                            <td class="detailData">`+data.note+`</td>
                        </tr>
                    </table>`;

                    $('.modal-detail-body').html(kontakSupplierDetail)
                },
            });
    
            $('.modal-data').addClass('d-none')
            $('.modal-detail').removeClass('d-none')
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
    $('#phonenumber').on('keyup', function() {
        let phonenumber = $(this).val();
        $.ajax({
            url: "<?= BASEURL ?>/headofficeOpp/getKontakSupplierConfig",
            data: { phonenumber: phonenumber },
            method: "post",
            dataType: "json",
            success: function (data) {
                if($(".modal-footer button[type=submit]").html() == "Edit" ) {
                    let $id = $('#id').val();
                    if(data.id == $id) {
                        $('.configAlert').addClass('d-none')
                        $(".modal-footer button[type=submit]").removeClass('disabled')
                    } else if (data) {
                        $('.configAlert').removeClass('d-none')
                        $(".modal-footer button[type=submit]").addClass('disabled')
                    } else {
                        $('.configAlert').addClass('d-none')
                        $(".modal-footer button[type=submit]").removeClass('disabled')
                    }
                } else if (data) {
                    $('.configAlert').removeClass('d-none')
                    $(".modal-footer button[type=submit]").addClass('disabled')
                } else {
                    $('.configAlert').addClass('d-none')
                    $(".modal-footer button[type=submit]").removeClass('disabled')
                }
            },
        })
    })

    // search
    $('.tabledata-kontakSupplier-search').on('keyup', function() {
        let keyword = $(this).val();
        $(".no").removeClass('d-none');
        $(".tabledata-choose-checkbox").addClass('d-none')
        $(".tabledata-choose").removeClass('d-none')
        $(".tabledata-choose-cancel").addClass('d-none')
        $(".tabledata-choose-delete").addClass('d-none')
        $(".tabledata-choose-checkbox").prop('checked', false);
        $(".tabledata-choose-delete").text(`Hapus`)

        $.ajax({
        url: "<?= BASEURL ?>/headofficeOpp/searchKontakSupplier",
        data: { keyword: keyword},
        method: "post",
        dataType: "json",
        success: function (data) {
            let i = 1;

            let contacts = "";
            data.forEach(contact => {
                contacts += `<div class="tabledata-item">
                            <p class="no">` + i++ + `</p><input type="checkbox" class="tabledata-choose-checkbox d-none" name="deletemanycontacts[]" id="`+contact.id+`" value="`+contact.id+`">
                            <p>` + contact.fullname + `</p>
                            <p>` + contact.phonenumber + `</p>
                            <div class="tabledata-actions">
                                <div class="action openModalBtn detail-kontakSupplier" data-id="` + contact.id + `"><i class="bi bi-eye detail-icon"></i></div>
                                <div class="action openModalBtn edit-kontakSupplier" data-id="` + contact.id + `"><i class="bi bi-pencil"></i></div>
                                <a href="<?= BASEURL ?>/headofficeOpp/deleteKontakSupplier/` + contact.id + `" class="action alertConfirmKontakSupplier"><i class="bi bi-trash"></i></a>
                            </div>
                        </div>`;
            })

            if(data.length !== 0) {
                $('#tabledata-items').html(contacts)
            } else {
                $('#tabledata-items').html(`<div class="tabledata-item" id="tabledata-item-datanotfound-dataempty">
                    data tidak ditemukan !
                </div>`)
            }

            crudOperation();
        },

        });
    })

});
</script>

<script src="<?= BASEURL?>/assets/js/global-script.js"></script>