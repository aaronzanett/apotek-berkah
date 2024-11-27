<?php if (isset($_SESSION['alert']['action'])) : ?>
    <div class="" id="alert" 
    data-alertheader="<?= $_SESSION['alert']['header'] ?>"
    data-alertdescription="<?= $_SESSION['alert']['description'] ?>"
    data-alerttype="<?= $_SESSION['alert']['type'] ?>"
    data-alertaction="<?= $_SESSION['alert']['action'] ?>"
    ></div>
<?php endif; SetAlert::deleteAlert(); ?> 
            
<section id="outlet">
    <h2 class="pageTitle outletTitle">Daftar Outlet</h2>
    <div class="search-group shadow">
        <i class="bi bi-search tabledata-searchicon"></i>
        <input type="text" class="tabledata-searchinput tabledata-outlet-search" placeholder="Cari outlet...">
    </div>
    <div class="outlet-container">
        <div class="outlet-items-container">
            <?php foreach ($data['outlet'] as $outlet) : ?>
                <div class="outlet-item shadow">
                    <h2 class="outlet-header"><?= $outlet['name'] ?></h2>
                    <p class="outlet-address"><?= $outlet['address'] ?></p>
                    <p class="outlet-serialnumber">Serial Number: <?= $outlet['serial_number'] ?></p>
                    <div class="outlet-action">
                        <div class="openModalBtn btn edit edit-outlet" data-id="<?= $outlet['id'] ?>"><i class="bi bi-pencil-square"></i>Edit</div>
                        <a class="btn delete alertConfirmOutlet" href="<?= BASEURL ?>/headofficeOpp/deleteOutlet/<?= $outlet['id'] ?>"><i class="bi bi-trash"></i>Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
                        
        <div class="add-outlet-item shadow openModalBtn">
            <div class="add-outlet-item-icon">
                <i class="bi bi-plus-circle-fill"></i>
            </div>
            <p>Tambah Outlet Baru</p>
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
                        <input type="hidden" name="type" id="type" value="Outlet">
                        <div class="form-group">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required maxlength="50">
                        </div>
                        <div class="form-group">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" required maxlength="200">
                        </div>
                        <div class="form-group">
                            <label for="serial_number" class="form-label">Serial Number</label>
                            <input type="number" class="form-control" id="serial_number" name="serial_number" required maxlength="6">
                        </div>
                        <div class="modal-radio-group">
                            <h4 class="modal-radio-title">Cabang biasa / franchise</h4>
                                
                            <div class="modal-radio-item">
                                <input type="radio" name="status" id="cabang" value="Cabang biasa" required>
                                <label for="cabang">Cabang Biasa</label>
                            </div>
                            <div class="modal-radio-item">
                                <input type="radio" name="status" id="franchise" value="Franchise">
                                <label for="franchise">Franchise</label>
                            </div>
                        </div>
                        
                        <div class="configAlert d-none">
                            <p>Serial number sudah digunakan!</p>
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
$(function () {
    //add 
    $(".add-outlet-item").on("click", function () {
        $(".modal-title").html("Tambah Outlet Baru");
        $(".modal-footer button[type=submit]").html("Tambah");
        $(".modal-body form").attr("action", "<?= BASEURL ?>/headofficeOpp/addOutlet" );
        $('.configAlert').addClass('d-none')
        $(".modal-footer button[type=submit]").removeClass('disabled')
        $("#id").val(''),
        $("#name").val(''),
        $("#address").val(''),
        $("#serial_number").val('')
        $("#status").val('')
    });

    function crudOperation(){
        // delete confirm custom
        $('.alertConfirmOutlet').on('click', function(e) {
            const href = $(this).attr('href');
            e.preventDefault();
            Swal.fire({
            title: "Apakah Anda Yakin?",
            text: "Semua akun pengguna yang berkaitan dengan outlet ini akan ikut terhapus!",
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

        // edit
        $(".edit-outlet").on("click", function () {
            $(".modal-title").html("Edit Outlet");
            $(".modal-footer button[type=submit]").html("Edit");
            $(".modal-body form").attr(
            "action",
            "<?= BASEURL ?>/headofficeOpp/editOutlet"
            );
            $('.configAlert').addClass('d-none')
            $(".modal-footer button[type=submit]").removeClass('disabled')
    
            const id = $(this).data("id");

            $.ajax({
            url: "<?= BASEURL ?>/headofficeOpp/getDataEditOutlet",
            data: { id: id },
            method: "post",
            dataType: "json",
            success: function (data) {
                $("#id").val(data.id),
                $("#name").val(data.name),
                $("#address").val(data.address),
                $("#serial_number").val(data.serial_number)
                $(`input[name="status"][value="${data.status}"]`).prop('checked', true)
            },
            });
        });

    }

    crudOperation();

    // config
    $('#serial_number').on('keyup', function() {
        let inputsn = $(this).val();
        $.ajax({
            url: "<?= BASEURL ?>/headofficeOpp/getOutletConfig",
            data: { serial_number: inputsn },
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
        });
    })
    $('.modal-footer button[type=submit]').on('click', function(e) {
        if($(this).hasClass('disabled')) {
            e.preventDefault();
        } else {
            e.unbind();
        }
    })

    // search
    $('.tabledata-outlet-search').on('input', function() {
        let keyword = $(this).val();

        $.ajax({
        url: "<?= BASEURL ?>/headofficeOpp/searchOutlet",
        data: { keyword: keyword},
        method: "post",
        dataType: "json",
        success: function (data) {
            let outlet = "";
            data.forEach(o => {
                outlet += `<div class="outlet-item shadow">
                    <h2 class="outlet-header">`+o.name+`</h2>
                    <p class="outlet-address">`+o.address+`</p>
                    <p class="outlet-serialnumber">Serial Number: `+o.serial_number+`</p>
                    <div class="outlet-action">
                        <div class="openModalBtn btn edit edit-outlet" data-id="`+o.id+`"><i class="bi bi-pencil-square"></i>Edit</div>
                        <a class="btn delete alertConfirmOutlet" href="<?= BASEURL ?>/headofficeOpp/deleteOutlet/`+o.id+`"><i class="bi bi-trash"></i>Delete</a>
                    </div>
                </div>`;
            })

            if(data.length !== 0) {
                $('.outlet-items-container').html(outlet)
            } else {
                $('.outlet-items-container').html(`<div class="outlet-item outlet-datanotfound shadow">
                    <p>data tidak ditemukan !</p>
                </div>`)
            }

            crudOperation();
        },

        });
    })

});
</script>

<script src="<?= BASEURL?>/assets/js/global-script.js"></script>