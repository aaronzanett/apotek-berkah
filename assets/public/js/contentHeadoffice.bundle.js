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

/***/ "./assets/js/content-headoffice.js":
/*!*****************************************!*\
  !*** ./assets/js/content-headoffice.js ***!
  \*****************************************/
/***/ (() => {

eval("$(document).ready(function () {\r\n  // set trigger and container variables\r\n  var linkButton = $(\"a.link\"),\r\n    container = $(\"#content\");\r\n\r\n  // fire on click\r\n  linkButton.on(\"click\", function () {\r\n    // set $this for re-use. set target from data attribute\r\n    var $this = $(this);\r\n    target = $this.data(\"target\");\r\n\r\n    sendRequestToController(target);\r\n\r\n    // rewrite url\r\n    window.history.pushState(\r\n      null,\r\n      null,\r\n      \"http://localhost/Apotek%20Berkah/app/headoffice/\" + target\r\n    );\r\n\r\n    // stop normal link behavior\r\n    return false;\r\n  });\r\n\r\n  function sendRequestToController(page) {\r\n    var xhr = new XMLHttpRequest();\r\n    xhr.onreadystatechange = function () {\r\n      if (xhr.readyState == 4 && xhr.status == 200) {\r\n        // Tanggapan dari server\r\n        container.html(xhr.responseText);\r\n      }\r\n    };\r\n    xhr.open(\r\n      \"POST\",\r\n      \"http://localhost/Apotek%20Berkah/app/headofficeContent/\" + page,\r\n      true\r\n    );\r\n    xhr.setRequestHeader(\"Content-Type\", \"application/x-www-form-urlencoded\");\r\n    xhr.send();\r\n  }\r\n});\r\n\n\n//# sourceURL=webpack:///./assets/js/content-headoffice.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./assets/js/content-headoffice.js"]();
/******/ 	
/******/ })()
;