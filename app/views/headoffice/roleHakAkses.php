<?php if (isset($_SESSION['alert']['action'])) : ?>
    <div id="alert" 
    data-alertheader="<?= $_SESSION['alert']['header'] ?>"
    data-alertdescription="<?= $_SESSION['alert']['description'] ?>"
    data-alerttype="<?= $_SESSION['alert']['type'] ?>"
    data-alertaction="<?= $_SESSION['alert']['action'] ?>"
    ></div>
<?php endif; SetAlert::deleteAlert(); ?> 
            
<section id="roleHakAkses">
    <div class="tabledata shadow">
        <div class="pagetitle-tabledata">
            <h2 class="pageTitle">Role & Hak Akses</h2>
            <button class="btn edit openModalBtn tabledata-addbtn add-role"><i class="bi bi-plus-lg"></i> Tambah role</button>
        </div>

        <div class="tabledata-header">
            <div class="tabledata-group">
                <i class="bi bi-search tabledata-searchicon"></i>
                <input type="text" class="tabledata-searchinput tabledata-role-search" placeholder="Cari role...">
            </div>
        </div>

        <div class="tabledata-subheader">
            <p class="tabledata-totaldata">Total <?= count($data['roles']) ?> role</p>
        </div>

        <div class="tabledata-body">
            <div class="tabledata-body-overflow-md">
                <div class="tabledata-header">
                    <p>No</p>
                    <p>Nama Role</p>
                    <p>Tipe Outlet <i class="bi bi-arrow-down-up"></i></p>
                    <p>Actions</p>
                </div>

                <form action="<?= BASEURL ?>/headofficeOpp/deleteManyRole" method="post" class="alertConfirmRole2">
                    <div id="tabledata-items">
                        <?php if (count($data['roles']) !== 0) { ?>
                            <?php $i = 1; foreach ($data['roles'] as $role ) : ?>
                            <div class="tabledata-item">
                                <p class="no"><?= $i++ ?></p><input type="checkbox" class="tabledata-choose-checkbox d-none" name="deletemanyroles[]" id="<?= $role['id'] ?>" value="<?= $role['id'] ?>">
                                <p><?= $role['name'] ?></p>
                                <p><?= $role['outlet_type'] ?></p>
                                <div class="tabledata-actions">
                                    <div class="action openModalBtn detail-role" data-id="<?= $role['id'] ?>" data-role="<?= $role['name'] ?>" data-outlettype="<?= $role['outlet_type'] ?>"><i class="bi bi-eye detail-icon"></i></div>
                                    <div class="action openModalBtn edit-role" data-id="<?= $role['id'] ?>" data-role="<?= $role['name'] ?>" data-outlettype="<?= $role['outlet_type'] ?>"><i class="bi bi-pencil"></i></div>
                                    <a href="<?= BASEURL ?>/headofficeOpp/deleteRole/<?= $role['id'] ?>" class="action alertConfirmRole"><i class="bi bi-trash"></i></a>
                                </div>
                            </div>
                            <?php endforeach;?>
                        <?php } else { ?>
                            <div class="tabledata-item" id="tabledata-item-datanotfound-dataempty">
                                data masih kosong!
                            </div>
                        <?php } ?>
                    </div>
                <?php if (count($data['kontakKaryawan']) !== 0) { ?>
                    <div class="tabledata-footer">
                        <div class="choose-items">
                            <button type="button" class="btn edit tabledata-choose">Pilih role</button>
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
                    <form action="<?= BASEURL ?>/headofficeOpp/addOutlet" method="post">
                        <input type="hidden" name="id" id="id" value="id">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" id="name" name="name" required maxlength="50">
                        </div>
                        <div class="modal-radio-group chooseOutlet">
                            <h4 class="modal-radio-title">Pilih tipe outlet</h4>
                            
                            <div class="modal-radio-item">
                                <input type="radio" name="chooseOutlet" id="headoffice" value="Headoffice" required>
                                <label for="headoffice">Headoffice</label>
                            </div>
                            <div class="modal-radio-item">
                                <input type="radio" name="chooseOutlet" id="admin" value="Admin">
                                <label for="admin">Admin</label>
                            </div>
                        </div>
    
                        <div class="modal-checkbox-group roleHakAkses-checkbox"></div>
                        
                        <div class="configAlert d-none">
                            <p>Nama role sudah digunakan!</p>
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
    // choose outlet
    const headofficePermissions = `<h4 class="modal-checkbox-title">Hak Akses Halaman Headoffice :</h4>
    <div class="modal-checkbox-items">
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="1" value="Master Produk">
            <label for="1">Master Produk</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="2" value="Master Kategori">
            <label for="2">Master Kategori</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="3" value="Master Satuan">
            <label for="3">Master Satuan</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="4" value="Master Rak">
            <label for="4">Master Rak</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="5" value="Metode Pembayaran">
            <label for="5">Metode Pembayaran</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="6" value="Persediaan Produk">
            <label for="6">Persediaan Produk</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="7" value="Defecta">
            <label for="7">Defecta</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="8" value="Stok Kadaluwarsa">
            <label for="8">Stok Kadaluwarsa</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="9" value="Stok Opname">
            <label for="9">Stok Opname</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="10" value="Riwayat Stok Opname">
            <label for="10">Riwayat Stok Opname</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="11" value="Pembelian">
            <label for="11">Pembelian</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="12" value="Penjualan">
            <label for="12">Penjualan</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="13" value="Penjualan Per Produk">
            <label for="13">Penjualan Per Produk</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="14" value="Penjualan Per Hari">
            <label for="14">Penjualan Per Hari</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="15" value="Penjualan Per Kategori">
            <label for="15">Penjualan Per Kategori</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="16" value="Penjualan Per Pelanggan">
            <label for="16">Penjualan Per Pelanggan</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="17" value="Penjualan Per Pengguna">
            <label for="17">Penjualan Per Pengguna</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="18" value="Pembelian Per Produk">
            <label for="18">Pembelian Per Produk</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="19" value="Pembelian Per Supplier">
            <label for="19">Pembelian Per Supplier</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="20" value="Laporan Laba / Rugi">
            <label for="20">Laporan Laba / Rugi</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="21" value="Laporan Neraca Keuangan">
            <label for="21">Laporan Neraca Keuangan</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="22" value="Buku Kas">
            <label for="22">Buku Kas</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="23" value="Utang Usaha">
            <label for="23">Utang Usaha</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="24" value="Piutang Usaha">
            <label for="24">Piutang Usaha</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="25" value="Paket Produk">
            <label for="25">Paket Produk</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="26" value="Diskon Produk">
            <label for="26">Diskon Produk</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="27" value="Penerimaan Resep">
            <label for="27">Penerimaan Resep</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="28" value="Penebusan Resep">
            <label for="28">Penebusan Resep</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="29" value="Daftar Pengguna">
            <label for="29">Daftar Pengguna</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="30" value="Role & Hak Akses">
            <label for="30">Role & Hak Akses</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="31" value="Kontak Karyawan">
            <label for="31">Kontak Karyawan</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="32" value="Kontak Pelanggan">
            <label for="32">Kontak Pelanggan</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="33" value="Kontak Supplier">
            <label for="33">Kontak Supplier</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="34" value="Outlet">
            <label for="34">Outlet</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="headofficePermissions[]" class="headofficePermissions" id="35" value="Web">
            <label for="35">Web</label>
        </div>
    </div>`;
    const adminPermissions = `<h4 class="modal-checkbox-title">Hak Akses Halaman Admin :</h4>

    <div class="modal-checkbox-items">
        <div class="modal-checkbox-item">
            <input type="checkbox" name="adminPermissions[]" class="adminPermissions" id="1" value="Persediaan Produk">
            <label for="1">Persediaan Produk</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="adminPermissions[]" class="adminPermissions" id="2" value="Defecta">
            <label for="2">Defecta</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="adminPermissions[]" class="adminPermissions" id="3" value="Stok Kadaluwarsa">
            <label for="3">Stok Kadaluwarsa</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="adminPermissions[]" class="adminPermissions" id="4" value="Stok Opname">
            <label for="4">Stok Opname</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="adminPermissions[]" class="adminPermissions" id="5" value="Riwayat Stok Opname">
            <label for="5">Riwayat Stok Opname</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="adminPermissions[]" class="adminPermissions" id="6" value="Kasir">
            <label for="6">Kasir</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="adminPermissions[]" class="adminPermissions" id="7" value="Penerimaan Resep">
            <label for="7">Penerimaan Resep</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="adminPermissions[]" class="adminPermissions" id="8" value="Penebusan Resep">
            <label for="8">Penebusan Resep</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="adminPermissions[]" class="adminPermissions" id="9" value="Pembelian">
            <label for="9">Pembelian</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="adminPermissions[]" class="adminPermissions" id="10" value="Penjualan">
            <label for="10">Penjualan</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="adminPermissions[]" class="adminPermissions" id="11" value="Penjualan Per Produk">
            <label for="11">Penjualan Per Produk</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="adminPermissions[]" class="adminPermissions" id="12" value="Penjualan Per Hari">
            <label for="12">Penjualan Per Hari</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="adminPermissions[]" class="adminPermissions" id="13" value="Penjualan Per Kategori">
            <label for="13">Penjualan Per Kategori</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="adminPermissions[]" class="adminPermissions" id="14" value="Penjualan Per Pelanggan">
            <label for="14">Penjualan Per Pelanggan</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="adminPermissions[]" class="adminPermissions" id="15" value="Penjualan Per Pengguna">
            <label for="15">Penjualan Per Pengguna</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="adminPermissions[]" class="adminPermissions" id="16" value="Pembelian Per Produk">
            <label for="16">Pembelian Per Produk</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="adminPermissions[]" class="adminPermissions" id="17" value="Pembelian Per Supplier">
            <label for="17">Pembelian Per Supplier</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="adminPermissions[]" class="adminPermissions" id="18" value="Laporan Laba / Rugi">
            <label for="18">Laporan Laba / Rugi</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="adminPermissions[]" class="adminPermissions" id="19" value="Kontak Pelanggan">
            <label for="19">Kontak Pelanggan</label>
        </div>
        <div class="modal-checkbox-item">
            <input type="checkbox" name="adminPermissions[]" class="adminPermissions" id="20" value="Kontak Supplier">
            <label for="20">Kontak Supplier</label>
        </div>
    </div>`;
    
    $('.chooseOutlet').on('change', function() {
        if($('#headoffice').prop("checked") == true) {
            $(".roleHakAkses-checkbox").html(headofficePermissions);
        } else if ($('#admin').prop("checked") == true) {
            $(".roleHakAkses-checkbox").html(adminPermissions);
        }
    })

    //add 
    $(".add-role").on("click", function () {
        $(".modal-title").html("Tambah role baru");
        $(".modal-footer button[type=submit]").html("Tambah");
        $(".modal-body form").attr("action", "<?= BASEURL ?>/headofficeOpp/addRole" );
        $('.configAlert').addClass('d-none')
        $(".modal-footer button[type=submit]").removeClass('disabled')
        $("#id").val(''),
        $("#name").val(''),
        $(".roleHakAkses-checkbox").html("");
        $(".modal input[type=radio],.modal input[type=checkbox]").prop('checked', false)
    });

    function crudOperation() {    
        // delete confirm custom
        $('.alertConfirmRole').on('click', function(e) {
            const href = $(this).attr('href');
            e.preventDefault();
            Swal.fire({
            title: "Apakah Anda Yakin?",
            text: "Semua akun pengguna yang berkaitan dengan role ini akan ikut terhapus!",
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
        $('.alertConfirmRole2').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: "Apakah Anda Yakin?",
                text: "Semua akun pengguna yang berkaitan dengan role ini akan ikut terhapus!",
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
        $(".edit-role").on("click", function () {
            $(".modal input[type=radio],.modal input[type=checkbox]").prop('checked', false)
            $(".modal-title").html("Edit role");
            $(".modal-footer button[type=submit]").html("Edit");
            $(".modal-body form").attr("action","<?= BASEURL ?>/headofficeOpp/editRole");
            $('.configAlert').addClass('d-none')
            $(".modal-footer button[type=submit]").removeClass('disabled')
    
            const id = $(this).data("id");
            const roleName = $(this).data("role")
            const outletType = $(this).data("outlettype");
    
            $.ajax({
            url: "<?= BASEURL ?>/headofficeOpp/getDataEditRole",
            data: { id: id , outletType: outletType},
            method: "post",
            dataType: "json",
            success: function (data) {
                $("#id").val(id),
                $("#name").val(roleName)
                
                let permissions = [];
                
                data.forEach(data => {
                    permissions.push(data['name'])
                });
                
                if(outletType == 'Headoffice') {
                    $('#headoffice').prop('checked', true);
                    $(".roleHakAkses-checkbox").html(headofficePermissions);
                    const headofficePermissionsCheckbox = $('.headofficePermissions').toArray();
                    
                    headofficePermissionsCheckbox.forEach(permissionCheckbox => {
                        if(permissions.includes(permissionCheckbox.value)) {
                            $(permissionCheckbox).prop('checked', true);
                        }
                    });
                } else if(outletType == 'Admin') {
                    $('#admin').prop('checked', true)
                    $(".roleHakAkses-checkbox").html(adminPermissions);
                    const adminPermissionsCheckbox = $('.adminPermissions').toArray();
    
                    adminPermissionsCheckbox.forEach(permissionCheckbox => {
                        if(permissions.includes(permissionCheckbox.value)) {
                            $(permissionCheckbox).prop('checked', true);
                        }
                    });
                }
            },
            });
    
        });
    
        // detail
        $('.detail-role').on('click', function() {
            $(".modal-title").html("Detail role");
            $('.configAlert').addClass('d-none')
            $(".modal-footer button[type=submit]").removeClass('disabled')
            const id = $(this).data("id");
            const roleName = $(this).data("role")
            const outletType = $(this).data("outlettype");
    
            $('.modal-detail-header').html("<h5 class='modal-detail-title'>" + roleName + "<span class='role-detail-header-outletType'> (" + outletType + ")</span></h5>")
    
            $.ajax({
            url: "<?= BASEURL ?>/headofficeOpp/getDataEditRole",
            data: { id: id , outletType: outletType},
            method: "post",
            dataType: "json",
            success: function (data) {
                let permissions = "<h6 class='role-detail-title'>Hak-hak akses:</h6>";
                
                data.forEach(permission => {
                    permissions += '<p class="role-detail-permission"><i class="bi bi-check2-square"></i> '+permission['name']+
                    '</p>'
                });
    
                $('.modal-detail-body').html(permissions)
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
    $('#name').on('keyup', function() {
        let inputn = $(this).val();
        $.ajax({
            url: "<?= BASEURL ?>/headofficeOpp/getRoleConfig",
            data: { name: inputn },
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
    $('.modal-footer button[type=submit]').on('click', function(e) {
        if($(this).hasClass('disabled')) {
            e.preventDefault();
        } else {
            e.unbind();
        }
    })

    // search
    $('.tabledata-role-search').on('keyup', function() {
        let keyword = $(this).val();
        $(".no").removeClass('d-none');
        $(".tabledata-choose-checkbox").addClass('d-none')
        $(".tabledata-choose").removeClass('d-none')
        $(".tabledata-choose-cancel").addClass('d-none')
        $(".tabledata-choose-delete").addClass('d-none')
        $(".tabledata-choose-checkbox").prop('checked', false);
        $(".tabledata-choose-delete").text(`Hapus`)

        $.ajax({
        url: "<?= BASEURL ?>/headofficeOpp/searchRole",
        data: { keyword: keyword},
        method: "post",
        dataType: "json",
        success: function (data) {
            let i = 1;

            let roles = "";
            data.forEach(role => {
                roles += `<div class="tabledata-item">
                    <p class="no">` + i++ +`</p><input type="checkbox" class="tabledata-choose-checkbox d-none" name="deletemanyroles[]" id="`+role.id+`" value="`+role.id+`">
                    <p>` + role.name +`</p>
                    <p>` + role.outlet_type +`</p>
                    <div class="tabledata-actions">
                        <div class="action openModalBtn detail-role" data-id="`+role.id+`" data-role="`+role.name+`" data-outlettype="`+role.outlet_type+`"><i class="bi bi-eye detail-icon"></i></div>
                        <div class="action openModalBtn edit-role" data-id="`+role.id+`" data-role="`+role.name+`" data-outlettype="` + role.outlet_type +`"><i class="bi bi-pencil"></i></div>
                        <a href="<?= BASEURL ?>/headofficeOpp/deleteRole/`+role.id+`" class="action alertConfirmRole"><i class="bi bi-trash"></i></a>
                    </div>
                </div>`;
            })

            if(data.length !== 0) {
                $('#tabledata-items').html(roles)
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