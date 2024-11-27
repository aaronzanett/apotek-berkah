const inputs = document.querySelectorAll(".input");
const show = document.querySelector("#eye");
const hide = document.querySelector("#eye-slash");
const displayNone = document.querySelector(".display-none");
const password = document.querySelector("#password");

function focusFunc() {
  let parent = this.parentNode;
  parent.classList.add("focus");
}

function blurFunc() {
  let parent = this.parentNode;
  if (this.value == "") {
    parent.classList.remove("focus");
  }
}

inputs.forEach((input) => {
  input.addEventListener("focus", focusFunc);
  input.addEventListener("blur", blurFunc);
});

show.addEventListener("click", function () {
  let type = password.getAttribute("type");
  if (type == "password") {
    show.classList.add("display-none");
    hide.classList.remove("display-none");
    password.setAttribute("type", "text");
  }
});

hide.addEventListener("click", function () {
  let type = password.getAttribute("type");
  if (type == "text") {
    hide.classList.add("display-none");
    show.classList.remove("display-none");
    password.setAttribute("type", "password");
  }
});
