// Initialize Lucide icons and common functionality
document.addEventListener("DOMContentLoaded", function () {
  // Initialize Lucide icons
  lucide.createIcons();

  // Mobile menu functionality
  setupMobileMenu();

  // Setup common interactions
  setupCommonInteractions();

  // Setup form validations
  setupFormValidations();

  // Setup favorites
  setupFavorites();

  console.log("🏠 ImmobilierKin common functionality initialized!");
});

// Mobile Menu Management
function setupMobileMenu() {
  const menuBtn = document.querySelector(".mobile-menu-btn");
  const mobileMenu = document.querySelector(".mobile-menu");

  if (menuBtn && mobileMenu) {
    menuBtn.addEventListener("click", function () {
      mobileMenu.classList.toggle("hidden");
      const icon = menuBtn.querySelector("i");
      if (mobileMenu.classList.contains("hidden")) {
        icon.setAttribute("data-lucide", "menu");
      } else {
        icon.setAttribute("data-lucide", "x");
      }
      lucide.createIcons();
    });

    // Close menu when clicking on links
    const mobileLinks = mobileMenu.querySelectorAll("a");
    mobileLinks.forEach((link) => {
      link.addEventListener("click", function () {
        mobileMenu.classList.add("hidden");
        const icon = menuBtn.querySelector("i");
        icon.setAttribute("data-lucide", "menu");
        lucide.createIcons();
      });
    });
  }
}

// Common Interactions
function setupCommonInteractions() {
  // Add loading states to buttons
  const buttons = document.querySelectorAll(".btn");
  buttons.forEach((btn) => {
    btn.addEventListener("click", function (e) {
      if (
        this.type === "submit" ||
        this.classList.contains("btn-primary") ||
        this.textContent.includes("Rechercher") ||
        this.textContent.includes("Accepter") ||
        this.textContent.includes("Créer")
      ) {
        addLoadingState(this);
      }
    });
  });

  // Add hover effects to cards
  const cards = document.querySelectorAll(
    ".property-card, .feature-card, .dashboard-card",
  );
  cards.forEach((card) => {
    card.addEventListener("mouseenter", function () {
      this.style.transform = "translateY(-2px)";
    });

    card.addEventListener("mouseleave", function () {
      this.style.transform = "translateY(0)";
    });
  });

  // Setup notification system
  window.showNotification = showNotification;
}

// Form Validations
function setupFormValidations() {
  const forms = document.querySelectorAll("form");
  forms.forEach((form) => {
    /*
    form.addEventListener("submit", function (e) {
      if (!validateForm(this)) {
        e.preventDefault();
      }
    });
    */
  });
}

// Favorites Management
function setupFavorites() {
  const favoriteButtons = document.querySelectorAll(".favorite-btn");
  favoriteButtons.forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.stopPropagation();
      e.preventDefault();
      this.classList.toggle("active");
      const isActive = this.classList.contains("active");
      showNotification(
        isActive ? "Ajouté aux favoris" : "Retiré des favoris",
        "info",
      );
    });
  });
}

// Utility Functions
function addLoadingState(button) {
  const originalText = button.innerHTML;
  button.innerHTML =
    '<i data-lucide="loader-2" class="animate-spin"></i> Chargement...';
  button.disabled = true;

  setTimeout(() => {
    button.innerHTML = originalText;
    button.disabled = false;
    lucide.createIcons();
  }, 1500);
}

function validateForm(form) {
  const inputs = form.querySelectorAll("input[required], select[required]");
  let isValid = true;

  inputs.forEach((input) => {
    if (!input.value.trim()) {
      showError(input, "Ce champ est requis");
      isValid = false;
    } else {
      clearError(input);
    }
  });

  // Validate email fields
  const emailInputs = form.querySelectorAll('input[type="email"]');
  emailInputs.forEach((input) => {
    if (input.value && !isValidEmail(input.value)) {
      showError(input, "Adresse email invalide");
      isValid = false;
    }
  });

  // Validate price ranges
  const minPrice = form.querySelector('input[name="minPrice"]');
  const maxPrice = form.querySelector('input[name="maxPrice"]');
  if (
    minPrice &&
    maxPrice &&
    minPrice.value &&
    maxPrice.value &&
    parseInt(minPrice.value) > parseInt(maxPrice.value)
  ) {
    showError(maxPrice, "Le prix max doit être supérieur au prix min");
    isValid = false;
  }

  return isValid;
}

function showError(input, message) {
  clearError(input);
  input.classList.add("error");
  const errorDiv = document.createElement("div");
  errorDiv.className = "error-message";
  errorDiv.textContent = message;
  input.parentNode.appendChild(errorDiv);
}

function clearError(input) {
  input.classList.remove("error");
  const errorMessage = input.parentNode.querySelector(".error-message");
  if (errorMessage) {
    errorMessage.remove();
  }
}

function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

function showNotification(message, type = "info", duration = 3000) {
  const notification = document.createElement("div");
  notification.className = `notification notification-${type}`;
  notification.textContent = message;

  // Add styles for notification
  notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 6px;
        color: white;
        font-weight: 500;
        z-index: 1000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        max-width: 300px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    `;

  switch (type) {
    case "success":
      notification.style.backgroundColor = "#10b981";
      break;
    case "error":
      notification.style.backgroundColor = "#ef4444";
      break;
    case "warning":
      notification.style.backgroundColor = "#f59e0b";
      break;
    case "info":
    default:
      notification.style.backgroundColor = "#3b82f6";
      break;
  }

  document.body.appendChild(notification);

  // Animate in
  setTimeout(() => {
    notification.style.transform = "translateX(0)";
  }, 100);

  // Remove after duration
  setTimeout(() => {
    notification.style.transform = "translateX(100%)";
    setTimeout(() => {
      if (document.body.contains(notification)) {
        document.body.removeChild(notification);
      }
    }, 300);
  }, duration);
}

// Utility functions for formatting
function formatPrice(amount, currency = "USD") {
  return new Intl.NumberFormat("fr-FR", {
    style: "currency",
    currency: currency,
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  }).format(amount);
}

function formatDate(date, locale = "fr-FR") {
  return new Intl.DateTimeFormat(locale).format(new Date(date));
}

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

// Add CSS animations via JavaScript
const style = document.createElement("style");
style.textContent = `
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    .notification {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .property-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .btn {
        transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    .error {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
    }
    
    .error-message {
        color: #ef4444;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }
`;
document.head.appendChild(style);

// Export functions for use in other scripts
window.ImmobilierKin = {
  showNotification,
  formatPrice,
  formatDate,
  debounce,
  validateForm,
  addLoadingState,
};
