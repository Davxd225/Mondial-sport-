/* ========================= */
/* LOADER */
/* ========================= */

window.addEventListener("load", () => {

    const loader = document.querySelector(".loader");

    setTimeout(() => {
        loader.style.opacity = "0";
        loader.style.visibility = "hidden";
    }, 1200);

});

/* ========================= */
/* MENU MOBILE */
/* ========================= */

const menuToggle = document.getElementById("menu-toggle");
const navMenu = document.getElementById("nav-menu");

menuToggle.addEventListener("click", () => {

    navMenu.classList.toggle("active");

});

/* ========================= */
/* HEADER SCROLL */
/* ========================= */

window.addEventListener("scroll", () => {

    const header = document.getElementById("header");

    if(window.scrollY > 50){
        header.classList.add("scroll-header");
    }else{
        header.classList.remove("scroll-header");
    }

});