const sidebar = document.querySelector("#sidebar");
const subnav = document.querySelectorAll(".navigation-item-subnav");
const dropdownIcon = document.querySelectorAll("img.dropdown-icon");
const navToggle = document.querySelector("#nav-toggle");
const navToggles = document.querySelectorAll("div.nav-toggle");
const main = document.querySelector("main#main");
const layerCover = document.querySelector("span.layer-cover");
const links = document.querySelectorAll(
  ".navigation-item-subnav a, .navigation-item a"
);

navToggles.forEach((navButton) => {
  navButton.addEventListener("click", function () {
    if (sidebar.classList.contains("off")) {
      sidebar.classList.remove("off");
      navToggle.classList.remove("off");
      main.classList.remove("off");
      layerCover.classList.remove("off");
    } else if (!sidebar.classList.contains("off")) {
      sidebar.classList.add("off");
      navToggle.classList.add("off");
      main.classList.add("off");
      layerCover.classList.add("off");
    }
  });
});

links.forEach(link => {
  const windowPathname = window.location.pathname;
  const navLinkPathname = new URL(link.href).pathname;
  if (windowPathname === navLinkPathname) {
    if (link.parentElement.parentElement.classList.contains("navigation-item-subnav")) {
      link.children[0].classList.add("active");
      link.parentElement.parentElement.classList.add("active");
      link.parentElement.parentElement.classList.add("activate");
    } else if (link.parentElement.classList.contains("navigation-item")) {
      link.parentElement.classList.add("active");
    }
  }

  link.addEventListener("click", function(){
    links.forEach(b => {
      if (b.parentElement.parentElement.classList.contains("navigation-item-subnav")) {
        b.children[0].classList.remove("active");
        b.parentElement.parentElement.classList.remove("activate");
      } else if (b.parentElement.classList.contains("navigation-item")) {
        b.parentElement.classList.remove("active");
      }
    })
    
    if (link.parentElement.parentElement.classList.contains("navigation-item-subnav")) {
      link.children[0].classList.add("active");
      link.parentElement.parentElement.classList.add("active");
      link.parentElement.parentElement.classList.add("activate");
    } else if (link.parentElement.classList.contains("navigation-item")) {
      link.parentElement.classList.add("active");
    }
  })
})

subnav.forEach((subnav) => {
  let collection = subnav.children[1];

  if (subnav.classList.contains("active")) {
    collection.classList.remove("d-none");
    subnav.classList.add("activate");
  } else if (!subnav.classList.contains("active")) {
    collection.classList.add("d-none");
    subnav.classList.remove("activate");
  }
  
  subnav.addEventListener("click", function () {
    if (!subnav.classList.contains("activate")) {
      subnav.classList.toggle("active");
    }
    
    if (subnav.classList.contains("active")) {
      collection.classList.remove("d-none");
    } else if (!subnav.classList.contains("active") && !subnav.classList.contains("activate")) {
      collection.classList.add("d-none");
    }
  });
});

$('.alertLogout').on('click', function(e) {
  const href = $(this).attr('href');
  e.preventDefault();
  Swal.fire({
    title: "Yakin Untuk Logout?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Logout",
    reverseButtons: true
  }).then((result) => {
    if(result.isConfirmed) {
      document.location.href = href;
    }
  });
})

// live date & time
function liveDateTime() {
  $.ajax({
    url: "http://apotek-berkah.test/liveDateTime/getDate",
    success: function(date) {
      $("#date").html(date)
    }
  })
  $.ajax({
    url: "http://apotek-berkah.test/liveDateTime/getTime",
    success: function(time) {
      $("#time").html(time)
    }
  })
}
liveDateTime();
setInterval(liveDateTime, 1000);