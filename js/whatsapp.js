const whatsappPhone = "2250555755829";

function getWhatsAppUrl(text) {
  return `https://wa.me/${whatsappPhone}?text=${encodeURIComponent(text)}`;
}

function initializeWhatsAppLinks() {
  const links = document.querySelectorAll("a.whatsapp-link[data-whatsapp-text]");
  links.forEach((link) => {
    link.href = getWhatsAppUrl(link.dataset.whatsappText);
    link.target = "_blank";
    link.rel = "noopener noreferrer";
  });
}

async function orderOnWhatsApp(productId) {
  let products = window.MondialAPI?.getCachedProducts?.() || [];
  let product = products.find((item) => Number(item.id) === Number(productId));

  if (!product && window.MondialAPI) {
    try {
      const data = await window.MondialAPI.listProducts();
      products = data.products || [];
      product = products.find((item) => Number(item.id) === Number(productId));
    } catch (error) {
      console.error(error);
    }
  }

  if (!product) {
    alert("Desole, ce produit n'est plus disponible.");
    return;
  }

  const quantityInput = prompt("Quantite souhaitee :", "1");
  if (quantityInput === null) return;

  const quantity = parseInt(quantityInput, 10);
  if (Number.isNaN(quantity) || quantity <= 0) {
    alert("Veuillez entrer une quantite valide superieure a 0.");
    return;
  }

  const storeName = "Mondial Sport";
  const total = Number(product.prix || product.price || 0) * quantity;
  const productName = product.nom || product.name;
  const unitPrice = Number(product.prix || product.price || 0).toLocaleString("fr-FR");

  const message = [
    "Bonjour Mondial Sport",
    "",
    "Je souhaite commander :",
    "",
    `Produit : ${productName}`,
    `Quantite : ${quantity}`,
    `Prix unitaire : ${unitPrice} FCFA`,
    `Total : ${total.toLocaleString("fr-FR")} FCFA`,
    `Boutique choisie : ${storeName}`,
    "",
    "Merci.",
  ].join("\n");

  window.open(getWhatsAppUrl(message), "_blank");

  const orders = JSON.parse(localStorage.getItem("mondialOrders") || "[]");
  orders.push({
    id: Date.now(),
    product: productName,
    quantity,
    total,
    store: storeName,
    date: new Date().toLocaleString(),
  });
  localStorage.setItem("mondialOrders", JSON.stringify(orders));

  showSuccessToast(productName, storeName);
}

function showSuccessToast(productName, storeName) {
  const toast = document.createElement("div");
  toast.className = "order-toast";
  toast.innerHTML = `
    <div class="toast-icon">
      <i class="ri-checkbox-circle-fill"></i>
    </div>
    <div class="toast-content">
      <h4>Commande enregistree !</h4>
      <p>Votre demande pour <strong>${escapeHtml(productName)}</strong> a ete validee pour <strong>${escapeHtml(storeName)}</strong>. Verifiez votre onglet WhatsApp.</p>
    </div>
  `;

  document.body.appendChild(toast);
  setTimeout(() => toast.classList.add("show"), 100);
  setTimeout(() => {
    toast.classList.remove("show");
    setTimeout(() => toast.remove(), 400);
  }, 5000);
}

function escapeHtml(value) {
  return String(value ?? "")
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&#039;");
}

if (document.readyState === "loading") {
  window.addEventListener("DOMContentLoaded", initializeWhatsAppLinks);
} else {
  initializeWhatsAppLinks();
}

window.orderOnWhatsApp = orderOnWhatsApp;
