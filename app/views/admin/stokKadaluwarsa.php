<section id="kadaluwarsa">
<div class="tabledata shadow">
        <div class="pagetitle-tabledata">
            <h2 class="pageTitle">Stok kadaluwarsa</h2>
        </div>

        <div class="tabledata-header">
            <div class="tabledata-group">
                <i class="bi bi-search tabledata-searchicon"></i>
                <input type="text" class="tabledata-searchinput tabledata-stok-search" placeholder="Cari produk kadaluwarsa...">
            </div>
            <select name="rentang" id="rentang" required>
                <option value="current">Sudah kadaluwarsa</option>
                <option value="3months">Kadaluwarsa < 3 bulan</option>
                <option value="6months">Kadaluwarsa < 6 bulan</option>
                <option value="1year">Kadaluwarsa < 1 tahun</option>
            </select>
        </div>

        <div class="tabledata-body">
            <div class="tabledata-body-overflow-lg">
                <div class="tabledata-header">
                    <p>No</p>
                    <p>Nama produk</p>
                    <p>Stok</p>
                </div>
    
                
                <div id="tabledata-items">
                    
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

    function kadaluwarsa(){
        const rentang = $("#rentang").val();
        const keyword = $(".tabledata-stok-search").val();
        const outlet_id = <?= $_SESSION['outlet_id'] ?>;
        $.ajax({
            url: "<?= BASEURL ?>/adminOpp/getStokKadaluwarsaOutletById",
            data: { rentang: rentang, keyword: keyword, outlet_id: outlet_id },
            method: "post",
            dataType: "json",
            success: function (data) {
                let i = 1;
                let stok = "";
                data.forEach(p => {
                    stok += `<div class="tabledata-item">
                                <p class="no">`+ i++ +`</p>
                                <p>`+ p.product_name +`</p>
                                <p>`+ p.total_stok +`</p>
                            </div>`;
                });

                if(data.length !== 0) {
                    $('#tabledata-items').html(stok);
                } else {
                    $('#tabledata-items').html(`<div class="tabledata-item" id="tabledata-item-datanotfound-dataempty">
                        data kosong / tidak ditemukan !
                    </div>`);
                }
            },
        });
    }
    kadaluwarsa();

    $("#rentang").on("input", kadaluwarsa)
    $(".tabledata-stok-search").on("input", kadaluwarsa)
</script>

<script src="<?= BASEURL?>/assets/js/global-script.js"></script>