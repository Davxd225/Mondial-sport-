/* ========================= */
/* WHATSAPP SYSTEM */
/* ========================= */

const whatsappPhone = "2250555755829";

function getWhatsAppUrl(text) {
    return `https://wa.me/${whatsappPhone}?text=${encodeURIComponent(text)}`;
}

function initializeWhatsAppLinks() {
    const links = document.querySelectorAll("a.whatsapp-link[data-whatsapp-text]");
    links.forEach(link => {
        link.href = getWhatsAppUrl(link.dataset.whatsappText);
        link.target = "_blank";
        link.rel = "noopener noreferrer";
    });
}

if (document.readyState === "loading") {
    window.addEventListener("DOMContentLoaded", initializeWhatsAppLinks);
} else {
    initializeWhatsAppLinks();
}

function orderOnWhatsApp(productId) {
    const products = JSON.parse(localStorage.getItem("mondialProducts")) || [];
    const product = products.find(p => p.id == productId);

    if (!product) {
        console.error(`Produit avec l'ID ${productId} introuvable.`);
        alert("Désolé, ce produit n'est plus disponible.");
        return;
    }

    /* ========================= */
    /* MAGASIN FIXE */
    /* ========================= */
    const storeName = "Mondial Sport";

    /* ========================= */
    /* QUANTITY */
    /* ========================= */
    const quantityInput = prompt("Quantité souhaitée :", "1");
    if (quantityInput === null) return;

    const quantity = parseInt(quantityInput, 10);
    if (isNaN(quantity) || quantity <= 0) {
        alert("Veuillez entrer une quantité valide supérieure à 0.");
        return;
    }

    /* ========================= */
    /* TOTAL */
    /* ========================= */
    const total = product.price * quantity;

    /* ========================= */
    /* MESSAGE */
    /* ========================= */
    const message = `Bonjour Mondial Sport 👋\n\nJe souhaite commander :\n\n🛍 Produit : ${product.name}\n\n📦 Quantité : ${quantity}\n\n💰 Prix unitaire : ${Number(product.price).toLocaleString()} FCFA\n\n💵 Total : ${total.toLocaleString()} FCFA\n\n🏬 Boutique choisie : ${storeName}\n\nMerci.`;

    /* ========================= */
    /* WHATSAPP REDIRECTION */
    /* ========================= */
    const url = getWhatsAppUrl(message);
    window.open(url, "_blank");

    /* ========================= */
    /* SAVE ORDER HISTORY */
    /* ========================= */
    const orders = JSON.parse(localStorage.getItem("mondialOrders")) || [];
    orders.push({
        id: Date.now(),
        product: product.name,
        quantity: quantity,
        total: total,
        store: storeName,
        date: new Date().toLocaleString()
    });
    localStorage.setItem("mondialOrders", JSON.stringify(orders));

    /* ========================= */
    /* AJOUT : NOTIFICATION DE VALIDATION */
    /* ========================= */
    showSuccessToast(product.name, storeName);
}

// Fonction magique qui crée et affiche la notification à l'écran
function showSuccessToast(productName, storeName) {
    // 1. Création de la div de notification
    const toast = document.createElement("div");
    toast.className = "order-toast";
    
    // 2. Contenu HTML (on utilise Remix Icon pour l'icône de validation)
    toast.innerHTML = `
        <div class="toast-icon">
            <i class="ri-checkbox-circle-fill"></i>
        </div>
        <div class="toast-content">
            <h4>Commande Enregistrée !</h4>
            <p>Votre demande pour <strong>${productName}</strong> a été validée pour la boutique de <strong>${storeName}</strong>. Vérifiez votre onglet WhatsApp.</p>
        </div>
    `;

    // 3. Injection dans la page
    document.body.appendChild(toast);

    // 4. Déclenchement de l'animation d'apparition
    setTimeout(() => {
        toast.classList.add("show");
    }, 100);

    // 5. Disparition automatique après 5 secondes
    setTimeout(() => {
        toast.classList.remove("show");
        // On attend la fin de l'animation CSS pour supprimer l'élément du DOM
        setTimeout(() => {
            toast.remove();
        }, 400);
    }, 5000);
}

window.orderOnWhatsApp = orderOnWhatsApp;