// ============================================
// ZOMATO-INSPIRED UI FUNCTIONALITY
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    initDarkMode();
    initMobileMenu();
    initOrderQuantity();
    initFormValidation();
});

// ============================================
// DARK MODE TOGGLE
// ============================================

function initDarkMode() {
    const darkModeToggle = document.getElementById("darkModeToggle");
    const isDarkMode = localStorage.getItem("darkMode") === "true";
    
    // Apply saved preference
    if (isDarkMode) {
        document.body.classList.add("dark-mode");
    }
    
    // Toggle dark mode
    if (darkModeToggle) {
        darkModeToggle.addEventListener("click", function(e) {
            e.preventDefault();
            document.body.classList.toggle("dark-mode");
            const isDark = document.body.classList.contains("dark-mode");
            localStorage.setItem("darkMode", isDark);
        });
    }
}

// ============================================
// MOBILE MENU TOGGLE
// ============================================

function initMobileMenu() {
    const menuToggle = document.getElementById("menuToggle");
    const navLinks = document.getElementById("navLinks");
    
    if (menuToggle && navLinks) {
        menuToggle.addEventListener("click", function() {
            navLinks.classList.toggle("active");
        });
        
        // Close menu when a link is clicked
        navLinks.querySelectorAll("a").forEach(link => {
            link.addEventListener("click", function() {
                navLinks.classList.remove("active");
            });
        });
        
        // Close menu when clicking outside
        document.addEventListener("click", function(e) {
            if (!e.target.closest(".navbar")) {
                navLinks.classList.remove("active");
            }
        });
    }
}

// ============================================
// ORDER QUANTITY CALCULATOR
// ============================================

function initOrderQuantity() {
    const quantityInput = document.getElementById("quantity");
    const unitPrice = document.getElementById("unitPrice");
    const totalPrice = document.getElementById("totalPrice");
    
    if (quantityInput && unitPrice && totalPrice) {
        const updateTotal = () => {
            let qty = parseInt(quantityInput.value, 10);
            
            // Ensure minimum quantity of 1
            if (qty < 1) {
                qty = 1;
                quantityInput.value = 1;
            }
            
            // Ensure maximum quantity of 10
            if (qty > 10) {
                qty = 10;
                quantityInput.value = 10;
            }
            
            const price = parseFloat(unitPrice.innerText) || 0;
            const total = (qty * price).toFixed(2);
            totalPrice.innerText = total;
        };
        
        quantityInput.addEventListener("input", updateTotal);
        updateTotal();
    }
}

// ============================================
// FORM VALIDATION
// ============================================

function initFormValidation() {
    const forms = document.querySelectorAll("form");
    
    forms.forEach(form => {
        form.addEventListener("submit", function(e) {
            const inputs = form.querySelectorAll("input[required], select[required], textarea[required]");
            let isValid = true;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add("error");
                } else {
                    input.classList.remove("error");
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showNotification("Please fill in all required fields", "error");
            }
        });
    });
}

// ============================================
// NOTIFICATION SYSTEM
// ============================================

function showNotification(message, type = "info") {
    const notification = document.createElement("div");
    notification.className = `notification ${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 16px 24px;
        background: ${type === "error" ? "#f8d7da" : type === "success" ? "#d4edda" : "#d1ecf1"};
        color: ${type === "error" ? "#721c24" : type === "success" ? "#155724" : "#0c5460"};
        border: 1px solid ${type === "error" ? "#f5c6cb" : type === "success" ? "#c3e6cb" : "#bee5eb"};
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 9999;
        animation: slideInRight 0.3s ease-out;
        font-weight: 500;
        max-width: 400px;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = "slideOutRight 0.3s ease-out";
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// ============================================
// SMOOTH SCROLL BEHAVIOR
// ============================================

function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// ============================================
// UTILITY FUNCTIONS
// ============================================

// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR',
        minimumFractionDigits: 2
    }).format(amount);
}

// Debounce function for search inputs
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// ============================================
// ANIMATIONS
// ============================================

const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
    
    input.error,
    select.error,
    textarea.error {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1) !important;
    }
`;
document.head.appendChild(style);

// Initialize smooth scroll on page load
initSmoothScroll();
