// Dark mode toggle + navbar toggle + live order total
document.addEventListener("DOMContentLoaded", function () {
    const body = document.body;
    const toggleBtn = document.getElementById("darkModeToggle");
    const menuToggle = document.getElementById("menuToggle");
    const navLinks = document.getElementById("navLinks");

    // Restore dark mode preference
    if (localStorage.getItem("theme") === "dark") {
        body.classList.add("dark-mode");
    }

    if (toggleBtn) {
        toggleBtn.addEventListener("click", function () {
            body.classList.toggle("dark-mode");
            localStorage.setItem("theme", body.classList.contains("dark-mode") ? "dark" : "light");
        });
    }

    if (menuToggle && navLinks) {
        menuToggle.addEventListener("click", function () {
            navLinks.classList.toggle("show");
        });
    }

    const priceInput = document.getElementById("food_price");
    const quantityInput = document.getElementById("quantity");
    const totalInput = document.getElementById("total_price");

    function updateTotal() {
        if (!priceInput || !quantityInput || !totalInput) return;
        const price = parseFloat(priceInput.value || "0");
        const quantity = parseInt(quantityInput.value || "1", 10);
        const total = price * quantity;
        totalInput.value = total.toFixed(2);
    }

    if (priceInput && quantityInput && totalInput) {
        quantityInput.addEventListener("input", updateTotal);
        priceInput.addEventListener("input", updateTotal);
        updateTotal();
    }
});