/* ========================= */
/* CATEGORY SYSTEM */
/* ========================= */

const pageName = window.location.pathname
    .split("/")
    .pop()
    .replace(".html", "");

/* ========================= */
/* CATEGORY MAP */
/* ========================= */

const categoryMap = {

    chaussures: "Chaussures",
    maillots: "Maillot",
    accessoires: "Accessoires",
    ballons: "Ballon",
    survetements: "Survêtements"

};

/* ========================= */
/* CURRENT CATEGORY */
/* ========================= */

const currentCategory = categoryMap[pageName];

const allProductsCategory = JSON.parse(
    localStorage.getItem("mondialProducts")
) || [];

/* ========================= */
/* FILTER PRODUCTS */
/* ========================= */

const filteredProducts = allProductsCategory.filter(product => {

    return product.category === currentCategory;

});

/* ========================= */
/* DISPLAY */
/* ========================= */

displayProducts(filteredProducts);