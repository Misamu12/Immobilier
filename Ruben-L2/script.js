// Initialize Lucide icons
lucide.createIcons();

// Navigation and Page Management
class PageManager {
  constructor() {
    this.currentPage = "home";
    this.init();
  }

  init() {
    this.setupNavigation();
    this.setupMobileMenu();
    this.setupSearchForm();
    this.initCharts();
    this.setupFavorites();
    this.setupPropertyCards();
  }

  setupNavigation() {
    // Desktop navigation
    const navLinks = document.querySelectorAll(".nav-link");
    navLinks.forEach((link) => {
      link.addEventListener("click", (e) => {
        e.preventDefault();
        const href = link.getAttribute("href");
        if (href.startsWith("#")) {
          this.showPage(href.substring(1));
        }
      });
    });

    // Mobile navigation
    const mobileNavLinks = document.querySelectorAll(".mobile-nav-link");
    mobileNavLinks.forEach((link) => {
      link.addEventListener("click", (e) => {
        e.preventDefault();
        const href = link.getAttribute("href");
        if (href.startsWith("#")) {
          this.showPage(href.substring(1));
          this.closeMobileMenu();
        }
      });
    });

    // Logo links
    const logoLinks = document.querySelectorAll(".logo");
    logoLinks.forEach((link) => {
      link.addEventListener("click", (e) => {
        e.preventDefault();
        this.showPage("home");
      });
    });
  }

  setupMobileMenu() {
    const menuBtn = document.querySelector(".mobile-menu-btn");
    const mobileMenu = document.querySelector(".mobile-menu");

    menuBtn.addEventListener("click", () => {
      mobileMenu.classList.toggle("hidden");
      const icon = menuBtn.querySelector("i");
      if (mobileMenu.classList.contains("hidden")) {
        icon.setAttribute("data-lucide", "menu");
      } else {
        icon.setAttribute("data-lucide", "x");
      }
      lucide.createIcons();
    });
  }

  closeMobileMenu() {
    const mobileMenu = document.querySelector(".mobile-menu");
    const menuBtn = document.querySelector(".mobile-menu-btn");
    mobileMenu.classList.add("hidden");
    const icon = menuBtn.querySelector("i");
    icon.setAttribute("data-lucide", "menu");
    lucide.createIcons();
  }

  showPage(pageId) {
    // Hide all pages
    const pages = document.querySelectorAll(".page");
    pages.forEach((page) => page.classList.remove("active"));

    // Show selected page
    const targetPage = document.getElementById(pageId);
    if (targetPage) {
      targetPage.classList.add("active");
      this.currentPage = pageId;
    }

    // Update navigation active states
    this.updateNavActiveStates(pageId);

    // Initialize charts if dashboard or admin page
    if (pageId === "dashboard" || pageId === "admin") {
      setTimeout(() => this.initCharts(), 100);
    }
  }

  updateNavActiveStates(activePageId) {
    // Desktop navigation
    const navLinks = document.querySelectorAll(".nav-link");
    navLinks.forEach((link) => {
      link.classList.remove("active");
      const href = link.getAttribute("href");
      if (
        href === `#${activePageId}` ||
        (href === "#" && activePageId === "home")
      ) {
        link.classList.add("active");
      }
    });

    // Mobile navigation
    const mobileNavLinks = document.querySelectorAll(".mobile-nav-link");
    mobileNavLinks.forEach((link) => {
      link.classList.remove("active");
      const href = link.getAttribute("href");
      if (
        href === `#${activePageId}` ||
        (href === "#" && activePageId === "home")
      ) {
        link.classList.add("active");
      }
    });
  }

  setupSearchForm() {
    const searchForm = document.querySelector(".search-form");
    if (searchForm) {
      searchForm.addEventListener("submit", (e) => {
        e.preventDefault();
        this.showPage("properties");
        // Here you would typically collect form data and filter properties
        this.showNotification(
          "Recherche effectuée ! Redirection vers les résultats...",
          "success",
        );
      });
    }
  }

  setupFavorites() {
    const favoriteButtons = document.querySelectorAll(".favorite-btn");
    favoriteButtons.forEach((btn) => {
      btn.addEventListener("click", (e) => {
        e.stopPropagation();
        btn.classList.toggle("active");
        const isActive = btn.classList.contains("active");
        this.showNotification(
          isActive ? "Ajouté aux favoris" : "Retiré des favoris",
          "info",
        );
      });
    });
  }

  setupPropertyCards() {
    const propertyCards = document.querySelectorAll(".property-card");
    propertyCards.forEach((card) => {
      card.addEventListener("click", (e) => {
        // Don't trigger if clicking on buttons
        if (e.target.closest("button")) return;

        this.showNotification(
          "Ouverture des détails de la propriété...",
          "info",
        );
        // Here you would typically open a property detail modal or page
      });
    });
  }

  initCharts() {
    // Views Chart for Dashboard
    const viewsChartCanvas = document.getElementById("viewsChart");
    if (viewsChartCanvas && this.currentPage === "dashboard") {
      this.createViewsChart(viewsChartCanvas);
    }

    // Users Chart for Admin
    const usersChartCanvas = document.getElementById("usersChart");
    if (usersChartCanvas && this.currentPage === "admin") {
      this.createUsersChart(usersChartCanvas);
    }
  }

  createViewsChart(canvas) {
    const ctx = canvas.getContext("2d");

    // Destroy existing chart if it exists
    if (this.viewsChart) {
      this.viewsChart.destroy();
    }

    this.viewsChart = new Chart(ctx, {
      type: "line",
      data: {
        labels: ["Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim"],
        datasets: [
          {
            label: "Vues des annonces",
            data: [45, 52, 38, 65, 71, 58, 43],
            borderColor: "#f97316",
            backgroundColor: "rgba(249, 115, 22, 0.1)",
            borderWidth: 2,
            fill: true,
            tension: 0.4,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: "#f3f4f6",
            },
            ticks: {
              color: "#6b7280",
            },
          },
          x: {
            grid: {
              display: false,
            },
            ticks: {
              color: "#6b7280",
            },
          },
        },
        elements: {
          point: {
            backgroundColor: "#f97316",
            borderColor: "#ffffff",
            borderWidth: 2,
            radius: 4,
            hoverRadius: 6,
          },
        },
      },
    });
  }

  createUsersChart(canvas) {
    const ctx = canvas.getContext("2d");

    // Destroy existing chart if it exists
    if (this.usersChart) {
      this.usersChart.destroy();
    }

    this.usersChart = new Chart(ctx, {
      type: "doughnut",
      data: {
        labels: ["Utilisateurs", "Agents Immobiliers", "Administrateurs"],
        datasets: [
          {
            data: [2391, 156, 3],
            backgroundColor: ["#3b82f6", "#f97316", "#10b981"],
            borderWidth: 0,
            hoverOffset: 4,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: "bottom",
            labels: {
              padding: 20,
              color: "#6b7280",
              font: {
                size: 12,
              },
            },
          },
        },
      },
    });
  }

  showNotification(message, type = "info") {
    // Simple notification system
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
        `;

    switch (type) {
      case "success":
        notification.style.backgroundColor = "#10b981";
        break;
      case "error":
        notification.style.backgroundColor = "#ef4444";
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

    // Remove after 3 seconds
    setTimeout(() => {
      notification.style.transform = "translateX(100%)";
      setTimeout(() => {
        document.body.removeChild(notification);
      }, 300);
    }, 3000);
  }
}

// Property Management
class PropertyManager {
  constructor() {
    this.properties = this.generateMockProperties();
    this.favorites = new Set();
  }

  generateMockProperties() {
    return [
      {
        id: 1,
        title: "Villa moderne avec piscine - Quartier résidentiel",
        price: 450000,
        location: "Gombe, Kinshasa",
        type: "Maison",
        transaction: "achat",
        bedrooms: 4,
        bathrooms: 3,
        surface: 280,
        agent: "Marie Katanga",
        features: ["Piscine", "Garage", "Jardin", "Sécurité 24h"],
      },
      {
        id: 2,
        title: "Appartement 3 pièces - Centre-ville",
        price: 180000,
        location: "Kinshasa Centre",
        type: "Appartement",
        transaction: "achat",
        bedrooms: 2,
        bathrooms: 2,
        surface: 85,
        agent: "Jean Kasongo",
        features: ["Ascenseur", "Parking", "Balcon", "Vue sur fleuve"],
      },
      {
        id: 3,
        title: "Terrain commercial - Zone industrielle",
        price: 120000,
        location: "Limete, Kinshasa",
        type: "Terrain",
        transaction: "achat",
        surface: 500,
        agent: "Grace Mbuyi",
        features: ["Accès facile", "Viabilisé", "Proche aéroport"],
      },
    ];
  }

  formatPrice(price, transaction) {
    const formatted = new Intl.NumberFormat("fr-FR").format(price);
    return transaction === "location" ? `$${formatted}/mois` : `$${formatted}`;
  }

  toggleFavorite(propertyId) {
    if (this.favorites.has(propertyId)) {
      this.favorites.delete(propertyId);
      return false;
    } else {
      this.favorites.add(propertyId);
      return true;
    }
  }

  isFavorite(propertyId) {
    return this.favorites.has(propertyId);
  }
}

// Dashboard Analytics
class AnalyticsManager {
  constructor() {
    this.viewsData = this.generateViewsData();
    this.usersData = this.generateUsersData();
  }

  generateViewsData() {
    // Generate random data for the last 7 days
    const days = ["Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim"];
    return days.map((day) => ({
      day,
      views: Math.floor(Math.random() * 50) + 30,
    }));
  }

  generateUsersData() {
    return {
      users: 2391,
      agents: 156,
      admins: 3,
    };
  }

  updateViewsChart(chart) {
    const newData = this.generateViewsData();
    chart.data.datasets[0].data = newData.map((d) => d.views);
    chart.update();
  }
}

// Form Validation
class FormValidator {
  static validateSearch(formData) {
    const errors = [];

    if (formData.minPrice && formData.maxPrice) {
      if (parseInt(formData.minPrice) > parseInt(formData.maxPrice)) {
        errors.push(
          "Le prix minimum ne peut pas être supérieur au prix maximum",
        );
      }
    }

    return {
      isValid: errors.length === 0,
      errors,
    };
  }

  static validateContact(formData) {
    const errors = [];

    if (!formData.name || formData.name.trim().length < 2) {
      errors.push("Le nom doit contenir au moins 2 caractères");
    }

    if (!formData.email || !this.isValidEmail(formData.email)) {
      errors.push("Veuillez saisir une adresse email valide");
    }

    if (!formData.phone || formData.phone.trim().length < 10) {
      errors.push("Veuillez saisir un numéro de telephone valide");
    }

    return {
      isValid: errors.length === 0,
      errors,
    };
  }

  static isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }
}

// Utility Functions
class Utils {
  static formatCurrency(amount, currency = "USD") {
    return new Intl.NumberFormat("fr-FR", {
      style: "currency",
      currency: currency,
      minimumFractionDigits: 0,
      maximumFractionDigits: 0,
    }).format(amount);
  }

  static formatDate(date, locale = "fr-FR") {
    return new Intl.DateTimeFormat(locale).format(new Date(date));
  }

  static debounce(func, wait) {
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

  static throttle(func, limit) {
    let inThrottle;
    return function () {
      const args = arguments;
      const context = this;
      if (!inThrottle) {
        func.apply(context, args);
        inThrottle = true;
        setTimeout(() => (inThrottle = false), limit);
      }
    };
  }
}

// Search and Filter Management
class SearchManager {
  constructor() {
    this.filters = {
      transaction: "",
      propertyType: "",
      location: "",
      minPrice: "",
      maxPrice: "",
    };
  }

  updateFilter(key, value) {
    this.filters[key] = value;
  }

  getActiveFilters() {
    return Object.entries(this.filters)
      .filter(([key, value]) => value !== "")
      .reduce((acc, [key, value]) => {
        acc[key] = value;
        return acc;
      }, {});
  }

  clearFilters() {
    Object.keys(this.filters).forEach((key) => {
      this.filters[key] = "";
    });
  }

  search(properties) {
    return properties.filter((property) => {
      // Transaction type filter
      if (
        this.filters.transaction &&
        property.transaction !== this.filters.transaction
      ) {
        return false;
      }

      // Property type filter
      if (
        this.filters.propertyType &&
        property.type.toLowerCase() !== this.filters.propertyType.toLowerCase()
      ) {
        return false;
      }

      // Location filter
      if (
        this.filters.location &&
        !property.location
          .toLowerCase()
          .includes(this.filters.location.toLowerCase())
      ) {
        return false;
      }

      // Price range filter
      if (
        this.filters.minPrice &&
        property.price < parseInt(this.filters.minPrice)
      ) {
        return false;
      }

      if (
        this.filters.maxPrice &&
        property.price > parseInt(this.filters.maxPrice)
      ) {
        return false;
      }

      return true;
    });
  }
}

// Initialize Application
document.addEventListener("DOMContentLoaded", () => {
  // Initialize managers
  const pageManager = new PageManager();
  const propertyManager = new PropertyManager();
  const analyticsManager = new AnalyticsManager();
  const searchManager = new SearchManager();

  // Make managers globally available for debugging
  window.app = {
    pageManager,
    propertyManager,
    analyticsManager,
    searchManager,
    utils: Utils,
  };

  // Add smooth scrolling to anchor links
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      const targetId = this.getAttribute("href");
      if (targetId === "#" || targetId.length <= 1) return;

      const targetElement = document.querySelector(targetId);
      if (
        targetElement &&
        !targetId.match(/^#(home|properties|dashboard|admin)$/)
      ) {
        e.preventDefault();
        targetElement.scrollIntoView({
          behavior: "smooth",
          block: "start",
        });
      }
    });
  });

  // Add loading states to buttons
  document.querySelectorAll(".btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      if (this.type === "submit" || this.classList.contains("btn-primary")) {
        const originalText = this.innerHTML;
        this.innerHTML =
          '<i data-lucide="loader-2" class="animate-spin"></i> Chargement...';
        this.disabled = true;

        setTimeout(() => {
          this.innerHTML = originalText;
          this.disabled = false;
          lucide.createIcons();
        }, 1000);
      }
    });
  });

  // Add hover effects to cards
  document
    .querySelectorAll(".property-card, .feature-card, .dashboard-card")
    .forEach((card) => {
      card.addEventListener("mouseenter", function () {
        this.style.transform = "translateY(-2px)";
      });

      card.addEventListener("mouseleave", function () {
        this.style.transform = "translateY(0)";
      });
    });

  // Initialize Chart.js defaults
  Chart.defaults.font.family = "Inter";
  Chart.defaults.color = "#6b7280";

  console.log("🏠 ImmobilierKin application initialized successfully!");
});

// Handle window resize for responsive charts
window.addEventListener(
  "resize",
  Utils.debounce(() => {
    if (window.app && window.app.pageManager) {
      window.app.pageManager.initCharts();
    }
  }, 250),
);

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
`;
document.head.appendChild(style);
