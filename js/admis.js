/* ========================= */

/* ADMIN LOGIN */

/* ========================= */

const loginForm = document.getElementById("login-form");

const adminLogin = document.getElementById("admin-login");

const dashboard = document.getElementById("dashboard");

const logoutBtn = document.getElementById("logout-btn");

/* ========================= */

/* DEFAULT ADMIN */

/* ========================= */

const ADMIN_EMAIL = "admin@mondialsport.sn";

const ADMIN_PASSWORD = "123456";

/* ========================= */

/* LOGIN */

/* ========================= */

if (loginForm) {
  loginForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const email = document.getElementById("admin-email").value;

    const password = document.getElementById("admin-password").value;

    if (email === ADMIN_EMAIL && password === ADMIN_PASSWORD) {
      localStorage.setItem(
        "mondialAdmin",

        "true",
      );

      showDashboard();
    } else {
      alert("Identifiants incorrects");
    }
  });
}

/* ========================= */

/* SHOW DASHBOARD */

/* ========================= */

function showDashboard() {
  adminLogin.classList.add("hidden");

  dashboard.classList.remove("hidden");

  loadStats();

  loadRecentProducts();
}

/* ========================= */

/* CHECK SESSION */

/* ========================= */

if (localStorage.getItem("mondialAdmin") === "true") {
  showDashboard();
}

/* ========================= */

/* LOGOUT */

/* ========================= */

logoutBtn.addEventListener("click", () => {
  localStorage.removeItem("mondialAdmin");

  location.reload();
});

/* ========================= */

/* LOAD STATS */

/* ========================= */

function loadStats() {
  const products = JSON.parse(localStorage.getItem("mondialProducts")) || [];

  const orders = JSON.parse(localStorage.getItem("mondialOrders")) || [];

  /* TOTAL PRODUCTS */

  document.getElementById("total-products").textContent = products.length;

  /* OUT OF STOCK */

  const outStock = products.filter((product) => {
    return product.stock <= 0;
  });

  document.getElementById("out-stock").textContent = outStock.length;

  /* TOTAL ORDERS */

  document.getElementById("total-orders").textContent = orders.length;

  /* TOTAL CATEGORIES */

  const categories = [...new Set(products.map((product) => product.category))];

  document.getElementById("total-categories").textContent = categories.length;
}

/* ========================= */

/* RECENT PRODUCTS */

/* ========================= */

function loadRecentProducts() {
  const products = JSON.parse(localStorage.getItem("mondialProducts")) || [];

  const recentProducts = document.getElementById("recent-products");

  recentProducts.innerHTML = "";

  products.slice(0, 5).forEach((product) => {
    recentProducts.innerHTML += `



            <tr>



                <td>

                    ${product.name}

                </td>



                <td>

                    ${product.category}

                </td>



                <td>

                    ${product.price.toLocaleString()} FCFA

                </td>



                <td>

                    ${product.stock}

                </td>



            </tr>



        `;
  });
}

/* ========================= */

/* SHOW PASSWORD */

/* ========================= */

const togglePassword = document.getElementById("toggle-password");

const passwordInput = document.getElementById("admin-password");

if (togglePassword) {
  togglePassword.addEventListener("click", () => {
    const type = passwordInput.getAttribute("type");

    if (type === "password") {
      passwordInput.setAttribute("type", "text");

      togglePassword.innerHTML = `<i class="ri-eye-off-line"></i>`;
    } else {
      passwordInput.setAttribute("type", "password");

      togglePassword.innerHTML = `<i class="ri-eye-line"></i>`;
    }
  });
}

/* ========================= */

/* PRODUCT MANAGEMENT */

/* ========================= */

const productForm = document.getElementById("product-form");

const productsTableBody = document.getElementById("products-table-body");

/* ========================= */

/* LOAD PRODUCTS TABLE */

/* ========================= */

function loadProductsTable() {
  if (!productsTableBody) return;

  const products = JSON.parse(localStorage.getItem("mondialProducts")) || [];

  productsTableBody.innerHTML = "";

  products.forEach((product) => {
    productsTableBody.innerHTML += `



            <tr>



                <td>

                    ${product.name}

                </td>



                <td>

                    ${product.price.toLocaleString()} FCFA

                </td>



                <td>

                    ${product.stock}

                </td>



                <td>

                    ${product.category}

                </td>



                <td>



                    <div class="action-buttons">



                        <button

                            class="edit-btn"

                            onclick="editProduct(${product.id})"

                        >

                            Modifier

                        </button>



                        <button

                            class="delete-btn"

                            onclick="deleteProduct(${product.id})"

                        >

                            Supprimer

                        </button>



                    </div>



                </td>



            </tr>



        `;
  });
}

/* ========================= */

/* LOAD TABLE */

/* ========================= */

loadProductsTable();

/* ========================= */

/* SAVE PRODUCT */

/* ========================= */

if (productForm) {
  productForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const id = document.getElementById("product-id").value;

    const name = document.getElementById("product-name").value;

    const price = document.getElementById("product-price").value;

    const category = document.getElementById("product-category").value;

    const stock = document.getElementById("product-stock").value;

    const image = document.getElementById("product-image").value;

    const description = document.getElementById("product-description").value;

    const products = JSON.parse(localStorage.getItem("mondialProducts")) || [];

    /* EDIT */

    if (id) {
      const index = products.findIndex((product) => {
        return product.id == id;
      });

      products[index] = {
        ...products[index],

        name,

        price: Number(price),

        category,

        stock: Number(stock),

        image,

        description,
      };
    } else {
      /* NEW PRODUCT */

      products.push({
        id: Date.now(),

        name,

        price: Number(price),

        category,

        stock: Number(stock),

        image,

        description,

        featured: false,
      });
    }

    /* SAVE */

    localStorage.setItem(
      "mondialProducts",

      JSON.stringify(products),
    );

    /* RESET */

    productForm.reset();

    document.getElementById("product-id").value = "";

    /* RELOAD */

    loadProductsTable();

    loadStats();

    alert("Produit enregistré avec succès");
  });
}

/* ========================= */

/* DELETE PRODUCT */

/* ========================= */

function deleteProduct(id) {
  const confirmDelete = confirm("Supprimer ce produit ?");

  if (!confirmDelete) return;

  let products = JSON.parse(localStorage.getItem("mondialProducts")) || [];

  products = products.filter((product) => {
    return product.id !== id;
  });

  localStorage.setItem(
    "mondialProducts",

    JSON.stringify(products),
  );

  loadProductsTable();

  loadStats();
}

/* ========================= */

/* EDIT PRODUCT */

/* ========================= */

function editProduct(id) {
  const products = JSON.parse(localStorage.getItem("mondialProducts")) || [];

  const product = products.find((product) => {
    return product.id === id;
  });

  if (!product) return;

  document.getElementById("product-id").value = product.id;

  document.getElementById("product-name").value = product.name;

  document.getElementById("product-price").value = product.price;

  document.getElementById("product-category").value = product.category;

  document.getElementById("product-stock").value = product.stock;

  document.getElementById("product-image").value = product.image;

  document.getElementById("product-description").value = product.description;

  window.scrollTo({
    top: 0,

    behavior: "smooth",
  });
}

/* ========================= */

/* SYSTEME DE NAVIGATION (ONGLETS) */

/* ========================= */

const menuItems = document.querySelectorAll(".sidebar-menu .menu-item");

const adminPages = document.querySelectorAll(".dashboard-main .admin-page");

menuItems.forEach((item) => {
  item.addEventListener("click", () => {
    // 1. Retirer la classe 'active' de tous les boutons du menu

    menuItems.forEach((menu) => menu.classList.remove("active"));

    // 2. Ajouter la classe 'active' sur le bouton cliqué

    item.classList.add("active");

    // 3. Cacher toutes les pages du dashboard

    adminPages.forEach((page) => page.classList.add("hidden"));

    // 4. Récupérer l'ID de la page cible et l'afficher

    const targetPageId = item.getAttribute("data-target");

    const targetPage = document.getElementById(targetPageId);

    if (targetPage) {
      targetPage.classList.remove("hidden");
    }
  });
});
