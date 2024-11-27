$(document).ready(function () {
  // set trigger and container variables
  var linkButton = $("a.link"),
    container = $("#content");

  // fire on click
  linkButton.on("click", function () {
    // set $this for re-use. set target from data attribute
    var $this = $(this);
    target = $this.data("target");

    sendRequestToController(target);

    // rewrite url
    window.history.pushState(
      null,
      null,
      "http://localhost/Apotek%20Berkah/app/admin/" + target
    );

    // stop normal link behavior
    return false;
  });

  function sendRequestToController(page) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4 && xhr.status == 200) {
        // Tanggapan dari server
        container.html(xhr.responseText);
      }
    };
    xhr.open(
      "POST",
      "http://localhost/Apotek%20Berkah/app/adminContent/" + page,
      true
    );
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send();
  }
});