/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/js/global-script.js":
/*!************************************!*\
  !*** ./assets/js/global-script.js ***!
  \************************************/
/***/ (() => {

eval("$(document).ready(function() {\r\n  global();\r\n  inputHarga();\r\n\r\n  // unable autocomplete input\r\n  $(\"input\").attr(\"autocomplete\", \"off\")\r\n\r\n  $(\".tabledata-searchinput\").on('change', global)\r\n\r\n  // input harga\r\n  $(\".inputHargaTrigger\").on(\"click\", inputHarga);\r\n  \r\n  // sweet alert\r\n  if (alert) {\r\n    const header = $('#alert').data('alertheader');\r\n    const description = $('#alert').data('alertdescription');\r\n    const type = $('#alert').data('alerttype');\r\n    const alert = $('#alert').data('alertaction');\r\n    if(alert == 'tambah' || alert == 'edit' || alert == 'hapus') {\r\n      Swal.fire({\r\n        title: header,\r\n        text: description,\r\n        icon: type\r\n      });\r\n    }\r\n  }\r\n\r\n  // phonenumber input limit\r\n  $('#phonenumber').on('input', function() {\r\n    var value = $(this).val();\r\n    if (value.length > 12) {\r\n      // Jika panjang nilai melebihi batasan, pangkas nilai\r\n      $(this).val(value.slice(0, 12));\r\n    }\r\n  });\r\n\r\n  // image drag & drop\r\n  $(\"#drop_image\").on(\"change\", function() {\r\n    uploadDropImage();\r\n  });\r\n  function uploadDropImage() {\r\n    let gambar = $('#drop_image').get(0).files[0];\r\n    if (gambar) {\r\n      let imgLink = URL.createObjectURL(gambar);\r\n      $('.image-view').css('background-image', `url(${imgLink})`);\r\n      $('.image-view').html('');\r\n      $('.image-view').css('border', 'none');\r\n    }\r\n  }\r\n  $(\".form-drop-image\").on(\"dragover\", function(e){\r\n    e.preventDefault();\r\n  })\r\n  $(\".form-drop-image\").on(\"drop\", function(e){\r\n    e.preventDefault();\r\n    let gambar = e.originalEvent.dataTransfer.files[0];\r\n    if (gambar) {\r\n      $(\"#drop_image\").prop(\"files\", e.originalEvent.dataTransfer.files);\r\n      uploadDropImage(gambar);\r\n    }\r\n  })\r\n\r\n  // auto set datetime-local input\r\n  // Dapatkan tanggal dan waktu saat ini\r\n  let now = new Date();\r\n\r\n  // Ubah format tanggal dan waktu ke format yang diperlukan oleh input datetime-local\r\n  let formattedDate = now.getFullYear() + '-' + ('0' + (now.getMonth() + 1)).slice(-2) + '-' + ('0' + now.getDate()).slice(-2);\r\n  let formattedTime = ('0' + now.getHours()).slice(-2) + ':' + ('0' + now.getMinutes()).slice(-2) + ':' + ('0' + now.getSeconds()).slice(-2);\r\n\r\n  // Set nilai input datetime-local\r\n  $(\".datetimenow\").val(formattedDate + 'T' + formattedTime);\r\n})\r\n\r\nfunction global() {\r\n  // modal\r\n  let modal = document.getElementById(\"modal\");\r\n  let btns = document.querySelectorAll(\".openModalBtn\");\r\n  let modalClose = document.querySelectorAll(\".close\");\r\n  btns.forEach(btn => {\r\n    btn.onclick = function () {\r\n      modal.style.display = \"block\";\r\n    };\r\n  });\r\n\r\n  modalClose.forEach(modalClose => {\r\n    modalClose.onclick = function () {\r\n      modal.classList.add(\"fadeOut\");\r\n      setTimeout(function () {\r\n        modal.style.display = \"none\";\r\n        modal.classList.remove(\"fadeOut\");\r\n      }, 250); // Duration of fadeOut animation (0.3 seconds)\r\n    };\r\n  });\r\n\r\n  window.onclick = function (event) {\r\n    if (event.target == modal) {\r\n      modal.classList.add(\"fadeOut\");\r\n      setTimeout(function () {\r\n        modal.style.display = \"none\";\r\n        modal.classList.remove(\"fadeOut\");\r\n      }, 250); // Duration of fadeOut animation (0.3 seconds)\r\n    }\r\n  };\r\n\r\n  $('.alertConfirm').on('click', function(e) {\r\n    const href = $(this).attr('href');\r\n    e.preventDefault();\r\n    Swal.fire({\r\n      title: \"Apakah Anda Yakin?\",\r\n      text: \"Anda tidak dapat mengembalikannya lagi!\",\r\n      icon: \"warning\",\r\n      showCancelButton: true,\r\n      confirmButtonColor: \"#3085d6\",\r\n      cancelButtonColor: \"#d33\",\r\n      confirmButtonText: \"Ya, hapus!\",\r\n      reverseButtons: true\r\n    }).then((result) => {\r\n      if(result.isConfirmed) {\r\n        document.location.href = href;\r\n      }\r\n    });\r\n  })\r\n\r\n  $(document).on('click', function(e) {\r\n    const targetElement = $(e.target);\r\n    \r\n    if (targetElement.hasClass('disabled')) {\r\n        e.preventDefault();\r\n    }\r\n  });\r\n}\r\n\r\nfunction inputHarga() {\r\n  function formatRibuan(number) {\r\n    return number.toString().replace(/\\B(?=(\\d{3})+(?!\\d))/g, \".\");\r\n  }\r\n\r\n  function handleInput(event) {\r\n    const input = $(event.target);\r\n    const value = input.val().replace(/\\./g, '');\r\n    if (!isNaN(value) && value.length > 0) {\r\n        input.val(formatRibuan(Number(value)));\r\n    } else {\r\n        input.val('');\r\n    }\r\n  }\r\n\r\n  $('.inputHarga').on('input', handleInput);\r\n}\r\n\r\n\r\n\r\n\r\n\n\n//# sourceURL=webpack:///./assets/js/global-script.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./assets/js/global-script.js"]();
/******/ 	
/******/ })()
;