/* ========================= */
/* PRODUITS MONDIAL SPORT */
/* ========================= */

const defaultProducts = [

    {
        id: 1,
        name: "Nike Air Max",
        price: 45000,
        category: "Chaussures",
        image: "https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=1170&auto=format&fit=crop",
        description: "Chaussure premium sport",
        stock: 12,
        featured: true
    },

    {
        id: 2,
        name: "Maillot Sénégal",
        price: 25000,
        category: "Maillot",
        image: "https://images.unsplash.com/photo-1517466787929-bc90951d0974?q=80&w=1170&auto=format&fit=crop",
        description: "Maillot officiel Sénégal",
        stock: 8,
        featured: true
    },

    {
        id: 3,
        name: "Ballon Pro",
        price: 18000,
        category: "Ballon",
        image: "https://images.unsplash.com/photo-1517649763962-0c623066013b?q=80&w=1170&auto=format&fit=crop",
        description: "Ballon professionnel",
        stock: 5,
        featured: true
    },

    {
        id: 4,
        name: "Sac Sport Elite",
        price: 32000,
        category: "Accessoires",
        image: "https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=1170&auto=format&fit=crop",
        description: "Sac premium sportif",
        stock: 10,
        featured: false
    }

];

/* ========================= */
/* LOCAL STORAGE */
/* ========================= */

if(!localStorage.getItem("mondialProducts")){

    localStorage.setItem(
        "mondialProducts",
        JSON.stringify(defaultProducts)
    );

}

/* ========================= */
/* GET PRODUCTS */
/* ========================= */

function getProducts(){

    return JSON.parse(
        localStorage.getItem("mondialProducts")
    ) || [];

}

/* ========================= */
/* DISPLAY PRODUCTS */
/* ========================= */

const productsContainer = document.getElementById("products-container");

function displayProducts(products){

    if(!productsContainer) return;

    productsContainer.innerHTML = "";

    if(products.length === 0){

        productsContainer.innerHTML = `
            <p class="empty-message">
                Aucun produit trouvé.
            </p>
        `;

        return;
    }

    products.forEach(product => {

        productsContainer.innerHTML += `

            <div class="product-card">

                <div class="product-image">

                    <img src="${product.image}" alt="${product.name}">

                </div>

                <div class="product-content">

                    <span class="product-category">
                        ${product.category}
                    </span>

                    <h3 class="product-title">
                        ${product.name}
                    </h3>

                    <p class="product-price">
                        ${product.price.toLocaleString()} FCFA
                    </p>

                    <div class="product-stock">
                        Stock : ${product.stock}
                    </div>

                    <button class="product-btn" onclick="orderOnWhatsApp(${product.id})">
    Commander WhatsApp
</button>

                </div>

            </div>

        `;

    });

}

/* ========================= */
/* INITIAL DISPLAY */
/* ========================= */

let allProducts = getProducts();

displayProducts(allProducts);

/* ========================= */
/* SEARCH */
/* ========================= */

const searchInput = document.getElementById("search-input");

searchInput.addEventListener("input", () => {

    filterProducts();

});

/* ========================= */
/* CATEGORY FILTER */
/* ========================= */

const categoryFilter = document.getElementById("category-filter");

categoryFilter.addEventListener("change", () => {

    filterProducts();

});

/* ========================= */
/* PRICE SORT */
/* ========================= */

const priceSort = document.getElementById("price-sort");

priceSort.addEventListener("change", () => {

    filterProducts();

});

/* ========================= */
/* FILTER FUNCTION */
/* ========================= */

function filterProducts(){

    let filtered = [...allProducts];

    /* SEARCH */

    const searchValue = searchInput.value.toLowerCase();

    filtered = filtered.filter(product => {

        return product.name.toLowerCase().includes(searchValue);

    });

    /* CATEGORY */

    const categoryValue = categoryFilter.value;

    if(categoryValue !== "all"){

        filtered = filtered.filter(product => {

            return product.category === categoryValue;

        });

    }

    /* SORT */

    if(priceSort.value === "low"){

        filtered.sort((a,b) => a.price - b.price);

    }

    if(priceSort.value === "high"){

        filtered.sort((a,b) => b.price - a.price);

    }

    displayProducts(filtered);

}