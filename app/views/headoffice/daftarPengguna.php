<?php if (isset($_SESSION['alert']['action'])) : ?>
    <div id="alert" 
    data-alertheader="<?= $_SESSION['alert']['header'] ?>"
    data-alertdescription="<?= $_SESSION['alert']['description'] ?>"
    data-alerttype="<?= $_SESSION['alert']['type'] ?>"
    data-alertaction="<?= $_SESSION['alert']['action'] ?>"
    ></div>
<?php endif; SetAlert::deleteAlert(); ?> 
            
<section id="daftarPengguna">
    <div class="tabledata shadow">
        <div class="pagetitle-tabledata">
            <h2 class="pageTitle">Daftar Pengguna</h2>
            <button class="btn edit openModalBtn tabledata-addbtn add-pengguna"><i class="bi bi-plus-lg"></i> Tambah pengguna</button>
        </div>

        <div class="tabledata-header">
            <div class="tabledata-group">
                <i class="bi bi-search tabledata-searchicon"></i>
                <input type="text" class="tabledata-searchinput tabledata-pengguna-search" placeholder="Cari pengguna...">
            </div>
        </div>

        <div class="tabledata-subheader">
            <p class="tabledata-totaldata">Total <?= count($data['pengguna']) ?> pengguna</p>
        </div>

        <div class="tabledata-body">
            <div class="tabledata-body-overflow-md">
                <div class="tabledata-header">
                    <p>No</p>
                    <p>Nama Pengguna</p>
                    <p>Username</p>
                    <p>Outlet <i class="bi bi-arrow-down-up"></i></p>
                    <p>Actions</p>
                </div>
    
                <form action="<?= BASEURL ?>/headofficeOpp/deleteManyPengguna" method="post">
                    <div id="tabledata-items">
                        <?php if (count($data['pengguna']) !== 0) { ?>
                            <?php $i = 1; foreach ($data['pengguna'] as $pengguna ) : ?>
                                <div class="tabledata-item tabledata-item-pengguna" data-idpengguna="<?= $pengguna['id'] ?>" data-idkaryawan="<?= $pengguna['fullname'] ?>" data-idoutlet="<?= $pengguna['outlet_id'] ?>" data-idrole="<?= $pengguna['role_id'] ?>">
                                    <p class="no"><?= $i++ ?></p><input type="checkbox" class="tabledata-choose-checkbox d-none" name="deletemanypengguna[]" id="<?= $pengguna['id'] ?>" value="<?= $pengguna['id'] ?>">
                                    <p class="nama_karyawan"></p>
                                    <p class="username"><?= $pengguna['username'] ?></p>
                                    <p class="outlet"></p>
                                    <div class="tabledata-actions">
                                        <div class="action openModalBtn detail-pengguna" data-idpengguna="<?= $pengguna['id'] ?>" data-idkaryawan="<?= $pengguna['fullname'] ?>" data-idoutlet="<?= $pengguna['outlet_id'] ?>" data-idrole="<?= $pengguna['role_id'] ?>"><i class="bi bi-eye detail-icon"></i></div>
                                        <div class="action openModalBtn edit-pengguna" data-idpengguna="<?= $pengguna['id'] ?>" data-idkaryawan="<?= $pengguna['fullname'] ?>" data-idoutlet="<?= $pengguna['outlet_id'] ?>" data-idrole="<?= $pengguna['role_id'] ?>"><i class="bi bi-pencil"></i></div>
                                        <a href="<?= BASEURL ?>/headofficeOpp/deletepengguna/<?= $pengguna['id'] ?>" class="action alertConfirm"><i class="bi bi-trash"></i></a>
                                    </div>
                                </div>
                            <?php endforeach;?>
                            <?php } else { ?>
                            <div class="tabledata-item" id="tabledata-item-datanotfound-dataempty">data masih kosong</div>
                        <?php } ?>
                    </div>
                <?php if (count($data['pengguna']) !== 0) { ?>
                    <div class="tabledata-footer">
                        <div class="choose-items">
                            <button type="button" class="btn edit tabledata-choose">Pilih pengguna</button>
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
                    <form action="<?= BASEURL ?>/headofficeOpp/addPengguna" method="post">
                        <input type="hidden" name="id" id="id" value="id">

                        <div class="form-group">
                            <label for="fullname" class="form-label">Nama Karyawan</label>
                            <select name="fullname" id="fullname" required>
                                <?php foreach ($data['kontakKaryawan'] as $karyawan) : ?>
                                    <option value="<?= $karyawan['id'] ?>"><?= $karyawan['fullname'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" id="username" name="username" required maxlength="50">
                        </div>
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input type="text" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="role_id" class="form-label">Pilih Role</label>
                            <select name="role_id" id="role_id" required>
                                <?php foreach ($data['roles'] as $role) : ?>
                                    <option value="<?= $role['id'] ?>" data-outlettype="<?= $role['outlet_type'] ?>"><?= $role['name'] ?> (<?= $role['outlet_type'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="roleChooseAuto"></div>
                        </div>
                        
                        <div class="configAlert d-none">
                            <p>Username sudah digunakan!</p>
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
    $(".add-pengguna").on("click", function () {
        $(".modal-title").html("Tambah pengguna baru");
        $(".modal-footer button[type=submit]").html("Tambah");
        $(".modal-body form").attr("action", "<?= BASEURL ?>/headofficeOpp/addPengguna" );
        $('.configAlert').addClass('d-none')
        $(".modal-footer button[type=submit]").removeClass('disabled')
        $("#id").val(''),
        $("#fullname").val(''),
        $("#username").val(''),
        $("#password").val(''),
        $("#role_id").val(''),
        $("#outlet_id").val('')
    });

    // check outlet type
    $("#role_id").on("change", function() {
        const $this = $(this);

        const $selectedOption = $this.find("option:selected");

        if ($selectedOption.data("outlettype") === "Headoffice") {
            $(".roleChooseAuto").html(`<div class="headofficeChoosed">
                                <label for="headoffice" class="form-label">Pilih Outlet</label>
                                <input type="text" name="headoffice" id="headoffice" value="Headoffice" readonly>
                            </div>`);
        } else {
            $(".roleChooseAuto").html(`<label for="outlet_id" class="form-label">Pilih Outlet</label>
                                <select name="outlet_id" id="outlet_id" required>
                                    <?php foreach ($data['outlet'] as $outlet) : ?>
                                        <option value="<?= $outlet['id'] ?>"><?= $outlet['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>`);
        }
    })

    function crudOperation(){
        // fill
        $('.tabledata-item-pengguna').each(function() {
            const $this = $(this);
            
            const idPengguna = $this.data('idpengguna');
            const idKaryawan = $this.data('idkaryawan');
            const idOutlet = $this.data('idoutlet');
            const idRole = $this.data('idrole');

            $.ajax({
            url: "<?= BASEURL ?>/headofficeOpp/getDataEditPengguna",
            data: {idPengguna: idPengguna, idKaryawan: idKaryawan, idOutlet: idOutlet, idRole: idRole},
            method: "post",
            dataType: "json",
            success: function (data) {
                $this.find("p.nama_karyawan").html(data['nama_karyawan'])
                $this.find("p.outlet").html(data['nama_outlet'])
            },
            });
        });

        // edit
        $(".edit-pengguna").on("click", function () {
            $(".modal-title").html("Edit pengguna");
            $(".modal-footer button[type=submit]").html("Edit");
            $(".modal-body form").attr("action","<?= BASEURL ?>/headofficeOpp/editPengguna");
            $('.configAlert').addClass('d-none')
            $(".modal-footer button[type=submit]").removeClass('disabled')

            const $this = $(this);

            const idPengguna = $this.data('idpengguna');
            const idKaryawan = $this.data('idkaryawan');
            const idOutlet = $this.data('idoutlet');
            const idRole = $this.data('idrole');
    
            $.ajax({
            url: "<?= BASEURL ?>/headofficeOpp/getDataEditPengguna",
            data: {idPengguna: idPengguna, idKaryawan: idKaryawan, idOutlet: idOutlet, idRole: idRole},
            method: "post",
            dataType: "json",
            success: function (data) {
                $("#id").val(data.id),
                $("#fullname").val(data.fullname),
                $("#username").val(data.username),
                $("#password").val(data.decryptedPassword),
                $("#role_id").val(data.role_id),
                $("#outlet_id").val(data.outlet_id)

                
                const $roleInput = $('#role_id');

                const $selectedOption = $roleInput.find("option:selected");

                if ($selectedOption.data("outlettype") === "Headoffice") {
                    $(".roleChooseAuto").html(`<div class="headofficeChoosed">
                                        <label for="headoffice" class="form-label">Pilih Outlet</label>
                                        <input type="text" name="headoffice" id="headoffice" value="Headoffice" readonly>
                                    </div>`);
                } else {
                    $(".roleChooseAuto").html(`<label for="outlet_id" class="form-label">Pilih Outlet</label>
                                        <select name="outlet_id" id="outlet_id" required>
                                            <?php foreach ($data['outlet'] as $outlet) : ?>
                                                <option value="<?= $outlet['id'] ?>"><?= $outlet['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>`);
                }
            },
            });
        });
    
        // detail
        $('.detail-pengguna').on('click', function() {
            $(".modal-title").html("Detail pengguna");
            $('.configAlert').addClass('d-none')
            $(".modal-footer button[type=submit]").removeClass('disabled')

            const $this = $(this);

            const idPengguna = $this.data('idpengguna');
            const idKaryawan = $this.data('idkaryawan');
            const idOutlet = $this.data('idoutlet');
            const idRole = $this.data('idrole');
    
            $.ajax({
                url: "<?= BASEURL ?>/headofficeOpp/getDataEditPengguna",
                data: {idPengguna: idPengguna, idKaryawan: idKaryawan, idOutlet: idOutlet, idRole: idRole},
                method: "post",
                dataType: "json",
                success: function (data) {
                    let penggunaDetail = `<table class="table-detail">
                        <tr>
                            <td class="detailInfo"><p>Nama Pengguna:</p></td>
                            <td class="detailData">`+data.nama_karyawan+`</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Username:</p></td>
                            <td class="detailData">`+data.username+`</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Password:</p></td>
                            <td class="detailData">`+data.decryptedPassword+`</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Role:</p></td>
                            <td class="detailData">`+data.nama_role+`</td>
                        </tr>
                        <tr>
                            <td class="detailInfo"><p>Outlet:</p></td>
                            <td class="detailData">`+data.nama_outlet+`</td>
                        </tr>
                    </table>`;
                    $('.modal-detail-body').html(penggunaDetail)
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
    $('#username').on('keyup', function() {
        let username = $(this).val();
        
        $.ajax({
            url: "<?= BASEURL ?>/headofficeOpp/getPenggunaConfig",
            data: {username: username},
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
    $('.tabledata-pengguna-search').on('keyup', function() {
        let keyword = $(this).val();

        $.ajax({
        url: "<?= BASEURL ?>/headofficeOpp/searchpengguna",
        data: { keyword: keyword},
        method: "post",
        dataType: "json",
        success: function (data) {
            let i = 1;

            let pengguna = "";
            data.forEach(p => {
                pengguna += `<div class="tabledata-item tabledata-item-pengguna" data-idpengguna="`+p.id+`" data-idkaryawan="`+p.fullname+`" data-idoutlet="`+p.outlet_id+`" data-idrole="`+p.role_id+`">
                                    <p class="no">`+ i++ +`</p><input type="checkbox" class="tabledata-choose-checkbox d-none" name="deletemanypengguna[]" id="`+p.id+`" value="`+p.id+`">
                                    <p class="nama_karyawan"></p>
                                    <p class="username">`+p.username+`</p>
                                    <p class="outlet"></p>
                                    <div class="tabledata-actions">
                                        <div class="action openModalBtn detail-pengguna" data-idpengguna="`+p.id+`" data-idkaryawan="`+p.fullname+`" data-idoutlet="`+p.outlet_id+`" data-idrole="`+p.role_id+`"><i class="bi bi-eye detail-icon"></i></div>
                                        <div class="action openModalBtn edit-pengguna" data-idpengguna="`+p.id+`" data-idkaryawan="`+p.fullname+`" data-idoutlet="`+p.outlet_id+`" data-idrole="`+p.role_id+`"><i class="bi bi-pencil"></i></div>
                                        <a href="<?= BASEURL ?>/headofficeOpp/deletepengguna/`+p.id+`" class="action alertConfirm"><i class="bi bi-trash"></i></a>
                                    </div>
                                </div>`;
            })

            if(data.length !== 0) {
                $('#tabledata-items').html(pengguna)
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