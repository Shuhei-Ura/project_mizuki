// ハンバーガーメニューの開閉
const hamburger = document.querySelector(".hamburger");
const nav = document.querySelector(".header-nav");

hamburger.addEventListener("click", () => {
    hamburger.classList.toggle("is-open"); // ← 追加
    nav.classList.toggle("is-open");
});
// ナビゲーションリンククリック時にメニューを閉じる
const navLinks = document.querySelectorAll(".header-nav a");
navLinks.forEach(link => {
    link.addEventListener("click", () => {
        hamburger.classList.remove("is-open");
        nav.classList.remove("is-open");
    });
});
// Recruitセクションのボタン切り替え
const recruitButtons = document.querySelectorAll(".recruit-btn");
const recruitItems = document.querySelectorAll(".recruit-item");

recruitButtons.forEach(button => {
    button.addEventListener("click", () => {
        const target = button.getAttribute("data-target");

        // すべてのボタンからis-activeを削除
        recruitButtons.forEach(btn => btn.classList.remove("is-active"));
        // クリックされたボタンにis-activeを追加
        button.classList.add("is-active");

        // すべてのコンテンツからis-activeを削除
        recruitItems.forEach(item => item.classList.remove("is-active"));
        // 対応するコンテンツにis-activeを追加
        const targetContent = document.querySelector(`[data-content="${target}"]`);
        if (targetContent) {
            targetContent.classList.add("is-active");
        }
    });
});
