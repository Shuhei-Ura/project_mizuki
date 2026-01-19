const hamburger = document.querySelector(".hamburger");
const nav = document.querySelector(".header-nav");

hamburger.addEventListener("click", () => {
    hamburger.classList.toggle("is-open"); // ← 追加
    nav.classList.toggle("is-open");
});
