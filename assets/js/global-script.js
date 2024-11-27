$(document).ready(function() {
  global();
  inputHarga();

  // unable autocomplete input
  $("input").attr("autocomplete", "off")

  $(".tabledata-searchinput").on('change', global)

  // input harga
  $(".inputHargaTrigger").on("click", inputHarga);
  
  // sweet alert
  if (alert) {
    const header = $('#alert').data('alertheader');
    const description = $('#alert').data('alertdescription');
    const type = $('#alert').data('alerttype');
    const alert = $('#alert').data('alertaction');
    if(alert == 'tambah' || alert == 'edit' || alert == 'hapus') {
      Swal.fire({
        title: header,
        text: description,
        icon: type
      });
    }
  }

  // phonenumber input limit
  $('#phonenumber').on('input', function() {
    var value = $(this).val();
    if (value.length > 12) {
      // Jika panjang nilai melebihi batasan, pangkas nilai
      $(this).val(value.slice(0, 12));
    }
  });

  // image drag & drop
  $("#drop_image").on("change", function() {
    uploadDropImage();
  });
  function uploadDropImage() {
    let gambar = $('#drop_image').get(0).files[0];
    if (gambar) {
      let imgLink = URL.createObjectURL(gambar);
      $('.image-view').css('background-image', `url(${imgLink})`);
      $('.image-view').html('');
      $('.image-view').css('border', 'none');
    }
  }
  $(".form-drop-image").on("dragover", function(e){
    e.preventDefault();
  })
  $(".form-drop-image").on("drop", function(e){
    e.preventDefault();
    let gambar = e.originalEvent.dataTransfer.files[0];
    if (gambar) {
      $("#drop_image").prop("files", e.originalEvent.dataTransfer.files);
      uploadDropImage(gambar);
    }
  })

  // auto set datetime-local input
  // Dapatkan tanggal dan waktu saat ini
  let now = new Date();

  // Ubah format tanggal dan waktu ke format yang diperlukan oleh input datetime-local
  let formattedDate = now.getFullYear() + '-' + ('0' + (now.getMonth() + 1)).slice(-2) + '-' + ('0' + now.getDate()).slice(-2);
  let formattedTime = ('0' + now.getHours()).slice(-2) + ':' + ('0' + now.getMinutes()).slice(-2) + ':' + ('0' + now.getSeconds()).slice(-2);

  // Set nilai input datetime-local
  $(".datetimenow").val(formattedDate + 'T' + formattedTime);
})

function global() {
  // modal
  let modal = document.getElementById("modal");
  let btns = document.querySelectorAll(".openModalBtn");
  let modalClose = document.querySelectorAll(".close");
  btns.forEach(btn => {
    btn.onclick = function () {
      modal.style.display = "block";
    };
  });

  modalClose.forEach(modalClose => {
    modalClose.onclick = function () {
      modal.classList.add("fadeOut");
      setTimeout(function () {
        modal.style.display = "none";
        modal.classList.remove("fadeOut");
      }, 250); // Duration of fadeOut animation (0.3 seconds)
    };
  });

  window.onclick = function (event) {
    if (event.target == modal) {
      modal.classList.add("fadeOut");
      setTimeout(function () {
        modal.style.display = "none";
        modal.classList.remove("fadeOut");
      }, 250); // Duration of fadeOut animation (0.3 seconds)
    }
  };

  $('.alertConfirm').on('click', function(e) {
    const href = $(this).attr('href');
    e.preventDefault();
    Swal.fire({
      title: "Apakah Anda Yakin?",
      text: "Anda tidak dapat mengembalikannya lagi!",
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

  $(document).on('click', function(e) {
    const targetElement = $(e.target);
    
    if (targetElement.hasClass('disabled')) {
        e.preventDefault();
    }
  });
}

function inputHarga() {
  function formatRibuan(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }

  function handleInput(event) {
    const input = $(event.target);
    const value = input.val().replace(/\./g, '');
    if (!isNaN(value) && value.length > 0) {
        input.val(formatRibuan(Number(value)));
    } else {
        input.val('');
    }
  }

  $('.inputHarga').on('input', handleInput);
}




