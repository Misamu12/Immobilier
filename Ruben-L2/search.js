// Search Page Functionality
document.addEventListener("DOMContentLoaded", function () {
  initializeSearchPage();
});

let currentPage = 1;
const itemsPerPage = 6;
let allProperties = [];
let filteredProperties = [];
let currentFilters = {};
let currentSort = "recent";
let currentView = "grid";

function initializeSearchPage() {
  // Load mock properties
  allProperties = generateMockProperties();
  filteredProperties = [...allProperties];

  // Setup search form
  setupSearchForm();

  // Setup filters
  setupFilters();

  // Setup view controls
  setupViewControls();

  // Setup sorting
  setupSorting();

  // Load URL parameters if any
  loadURLParameters();

  // Initial render
  renderProperties();
  updateResultsCount();
  updateActiveFilters();

  console.log(
    "🔍 Search page initialized with",
    allProperties.length,
    "properties",
  );
}

function generateMockProperties() {
  return [
    {
      id: 1,
      title: "Villa moderne avec piscine - Quartier résidentiel calme",
      price: 450000,
      location: "Gombe, Kinshasa",
      type: "Maison",
      transaction: "achat",
      bedrooms: 4,
      bathrooms: 3,
      surface: 280,
      images: ["/placeholder.svg"],
      description:
        "Magnifique villa moderne avec piscine dans un quartier résidentiel calme de Gombe.",
      agent: {
        name: "Marie Katanga",
        phone: "+243 81 234 56 78",
        email: "marie.katanga@immobilierkin.cd",
        avatar: "M",
      },
      features: ["Piscine", "Garage", "Jardin", "Sécurité 24h"],
      dateAdded: "2024-01-15",
      views: 145,
      isFavorite: false,
    },
    {
      id: 2,
      title: "Appartement 3 pièces - Centre-ville avec vue panoramique",
      price: 180000,
      location: "Kinshasa Centre",
      type: "Appartement",
      transaction: "achat",
      bedrooms: 2,
      bathrooms: 2,
      surface: 85,
      images: ["/placeholder.svg"],
      description:
        "Bel appartement au cœur de Kinshasa avec toutes commodités à proximité.",
      agent: {
        name: "Jean Kasongo",
        phone: "+243 82 345 67 89",
        email: "jean.kasongo@immobilierkin.cd",
        avatar: "J",
      },
      features: ["Ascenseur", "Parking", "Balcon", "Vue sur fleuve"],
      dateAdded: "2024-01-14",
      views: 98,
      isFavorite: true,
    },
    {
      id: 3,
      title: "Terrain commercial - Zone industrielle viabilisé",
      price: 120000,
      location: "Limete, Kinshasa",
      type: "Terrain",
      transaction: "achat",
      surface: 500,
      images: ["/placeholder.svg"],
      description:
        "Terrain commercial idéalement situé en zone industrielle de Limete.",
      agent: {
        name: "Grace Mbuyi",
        phone: "+243 83 456 78 90",
        email: "grace.mbuyi@immobilierkin.cd",
        avatar: "G",
      },
      features: ["Accès facile", "Viabilisé", "Proche aéroport"],
      dateAdded: "2024-01-13",
      views: 67,
      isFavorite: false,
    },
    {
      id: 4,
      title: "Studio meublé - Location courte durée",
      price: 800,
      location: "Bandalungwa, Kinshasa",
      type: "Appartement",
      transaction: "location",
      bedrooms: 1,
      bathrooms: 1,
      surface: 35,
      images: ["/placeholder.svg"],
      description:
        "Studio entièrement meublé, idéal pour jeunes professionnels.",
      agent: {
        name: "Paul Kabongo",
        phone: "+243 84 567 89 01",
        email: "paul.kabongo@immobilierkin.cd",
        avatar: "P",
      },
      features: ["Meublé", "Wifi", "Climatisation", "Proche transports"],
      dateAdded: "2024-01-12",
      views: 89,
      isFavorite: false,
    },
    {
      id: 5,
      title: "Hôtel boutique - Investissement rentable",
      price: 800000,
      location: "Ma Campagne, Kinshasa",
      type: "Hôtel",
      transaction: "achat",
      surface: 800,
      images: ["/placeholder.svg"],
      description:
        "Hôtel boutique en activité avec excellent taux d'occupation.",
      agent: {
        name: "Claudine Ilunga",
        phone: "+243 85 678 90 12",
        email: "claudine.ilunga@immobilierkin.cd",
        avatar: "C",
      },
      features: ["En activité", "Restaurant", "Piscine", "Parking"],
      dateAdded: "2024-01-11",
      views: 234,
      isFavorite: true,
    },
    {
      id: 6,
      title: "Maison familiale - 3 chambres avec jardin",
      price: 220000,
      location: "Lemba, Kinshasa",
      type: "Maison",
      transaction: "achat",
      bedrooms: 3,
      bathrooms: 2,
      surface: 150,
      images: ["/placeholder.svg"],
      description:
        "Belle maison familiale avec grand jardin dans un quartier dynamique.",
      agent: {
        name: "Patient Mukendi",
        phone: "+243 86 789 01 23",
        email: "patient.mukendi@immobilierkin.cd",
        avatar: "P",
      },
      features: ["Jardin", "Garage", "Véranda", "Proche écoles"],
      dateAdded: "2024-01-10",
      views: 156,
      isFavorite: false,
    },
    {
      id: 7,
      title: "Duplex moderne - Standing élevé",
      price: 350000,
      location: "Ngaliema, Kinshasa",
      type: "Appartement",
      transaction: "achat",
      bedrooms: 3,
      bathrooms: 3,
      surface: 120,
      images: ["/placeholder.svg"],
      description:
        "Magnifique duplex moderne dans un quartier huppé de Kinshasa.",
      agent: {
        name: "Marie Katanga",
        phone: "+243 81 234 56 78",
        email: "marie.katanga@immobilierkin.cd",
        avatar: "M",
      },
      features: ["Duplex", "Terrasse", "Parking privé", "Sécurité"],
      dateAdded: "2024-01-09",
      views: 203,
      isFavorite: false,
    },
    {
      id: 8,
      title: "Bureau moderne - Zone d'affaires",
      price: 2500,
      location: "Gombe, Kinshasa",
      type: "Commercial",
      transaction: "location",
      surface: 200,
      images: ["/placeholder.svg"],
      description:
        "Espace de bureau moderne dans le quartier d'affaires de Gombe.",
      agent: {
        name: "Jean Kasongo",
        phone: "+243 82 345 67 89",
        email: "jean.kasongo@immobilierkin.cd",
        avatar: "J",
      },
      features: ["Climatisation", "Internet fibre", "Parking", "Sécurité 24h"],
      dateAdded: "2024-01-08",
      views: 87,
      isFavorite: false,
    },
  ];
}

function setupSearchForm() {
  const searchForm = document.getElementById("searchForm");
  if (searchForm) {
    searchForm.addEventListener("submit", function (e) {
      e.preventDefault();
      performSearch();
    });

    // Add real-time search on input change
    const inputs = searchForm.querySelectorAll("input, select");
    inputs.forEach((input) => {
      input.addEventListener("change", debounce(performSearch, 300));
    });
  }
}

function setupFilters() {
  const clearAllBtn = document.getElementById("clearAllFilters");
  if (clearAllBtn) {
    clearAllBtn.addEventListener("click", clearAllFilters);
  }

  const clearFiltersBtn = document.getElementById("clearFiltersBtn");
  if (clearFiltersBtn) {
    clearFiltersBtn.addEventListener("click", clearAllFilters);
  }
}

function setupViewControls() {
  const gridViewBtn = document.getElementById("gridView");
  const listViewBtn = document.getElementById("listView");

  if (gridViewBtn && listViewBtn) {
    gridViewBtn.addEventListener("click", () => setView("grid"));
    listViewBtn.addEventListener("click", () => setView("list"));
  }
}

function setupSorting() {
  const sortSelect = document.getElementById("sortBy");
  if (sortSelect) {
    sortSelect.addEventListener("change", function () {
      currentSort = this.value;
      currentPage = 1;
      renderProperties();
    });
  }
}

function loadURLParameters() {
  const urlParams = new URLSearchParams(window.location.search);

  // Load filters from URL
  const transaction = urlParams.get("transaction");
  const propertyType = urlParams.get("propertyType");
  const location = urlParams.get("location");
  const minPrice = urlParams.get("minPrice");
  const maxPrice = urlParams.get("maxPrice");

  if (transaction) {
    document.getElementById("transaction").value = transaction;
    currentFilters.transaction = transaction;
  }
  if (propertyType) {
    document.getElementById("propertyType").value = propertyType;
    currentFilters.propertyType = propertyType;
  }
  if (location) {
    document.getElementById("location").value = location;
    currentFilters.location = location;
  }
  if (minPrice) {
    document.getElementById("minPrice").value = minPrice;
    currentFilters.minPrice = minPrice;
  }
  if (maxPrice) {
    document.getElementById("maxPrice").value = maxPrice;
    currentFilters.maxPrice = maxPrice;
  }

  if (Object.keys(currentFilters).length > 0) {
    applyFilters();
  }
}

function performSearch() {
  const form = document.getElementById("searchForm");
  if (!form) return;

  const formData = new FormData(form);
  currentFilters = {};

  for (let [key, value] of formData.entries()) {
    if (value.trim()) {
      currentFilters[key] = value.trim();
    }
  }

  currentPage = 1;
  applyFilters();
  updateActiveFilters();
  updateURL();
}

function applyFilters() {
  filteredProperties = allProperties.filter((property) => {
    // Transaction type filter
    if (
      currentFilters.transaction &&
      property.transaction !== currentFilters.transaction
    ) {
      return false;
    }

    // Property type filter
    if (
      currentFilters.propertyType &&
      property.type.toLowerCase() !== currentFilters.propertyType.toLowerCase()
    ) {
      return false;
    }

    // Location filter
    if (
      currentFilters.location &&
      !property.location
        .toLowerCase()
        .includes(currentFilters.location.toLowerCase())
    ) {
      return false;
    }

    // Price range filter
    if (
      currentFilters.minPrice &&
      property.price < parseInt(currentFilters.minPrice)
    ) {
      return false;
    }

    if (
      currentFilters.maxPrice &&
      property.price > parseInt(currentFilters.maxPrice)
    ) {
      return false;
    }

    return true;
  });

  renderProperties();
  updateResultsCount();
}

function sortProperties(properties) {
  const sorted = [...properties];

  switch (currentSort) {
    case "price-asc":
      sorted.sort((a, b) => a.price - b.price);
      break;
    case "price-desc":
      sorted.sort((a, b) => b.price - a.price);
      break;
    case "surface-desc":
      sorted.sort((a, b) => b.surface - a.surface);
      break;
    case "views-desc":
      sorted.sort((a, b) => b.views - a.views);
      break;
    case "recent":
    default:
      sorted.sort((a, b) => new Date(b.dateAdded) - new Date(a.dateAdded));
      break;
  }

  return sorted;
}

function renderProperties() {
  const propertiesGrid = document.getElementById("propertiesGrid");
  const noResults = document.getElementById("noResults");

  if (!propertiesGrid || !noResults) return;

  if (filteredProperties.length === 0) {
    propertiesGrid.style.display = "none";
    noResults.style.display = "block";
    return;
  }

  noResults.style.display = "none";
  propertiesGrid.style.display = "grid";

  // Sort properties
  const sortedProperties = sortProperties(filteredProperties);

  // Paginate properties
  const startIndex = (currentPage - 1) * itemsPerPage;
  const endIndex = startIndex + itemsPerPage;
  const paginatedProperties = sortedProperties.slice(startIndex, endIndex);

  // Clear existing content
  propertiesGrid.innerHTML = "";

  // Apply view mode class
  propertiesGrid.className =
    currentView === "grid" ? "properties-grid" : "properties-list";

  // Render properties
  paginatedProperties.forEach((property) => {
    const propertyCard = createPropertyCard(property);
    propertiesGrid.appendChild(propertyCard);
  });

  // Setup pagination
  setupPagination(sortedProperties.length);

  // Re-initialize icons
  lucide.createIcons();
}

function createPropertyCard(property) {
  const card = document.createElement("div");
  card.className = "property-card";
  card.onclick = () => viewProperty(property.id);

  const formatPrice = (price, transaction) => {
    const formatted = new Intl.NumberFormat("fr-FR").format(price);
    return transaction === "location" ? `$${formatted}/mois` : `$${formatted}`;
  };

  const bedroomsBathrooms =
    property.bedrooms && property.bathrooms
      ? `
    <div class="detail-item">
      <i data-lucide="bed"></i>
      <span>${property.bedrooms}</span>
    </div>
    <div class="detail-item">
      <i data-lucide="bath"></i>
      <span>${property.bathrooms}</span>
    </div>
  `
      : "";

  card.innerHTML = `
    <div class="property-image">
      <div class="property-badges">
        <span class="badge badge-primary">${property.transaction === "achat" ? "À vendre" : "À louer"}</span>
        <span class="badge badge-outline">${property.type}</span>
      </div>
      <button class="favorite-btn ${property.isFavorite ? "active" : ""}" onclick="event.stopPropagation(); toggleFavorite(${property.id})">
        <i data-lucide="heart"></i>
      </button>
    </div>
    <div class="property-content">
      <div class="property-price">
        <i data-lucide="dollar-sign"></i>
        ${formatPrice(property.price, property.transaction)}
      </div>
      <h3 class="property-title">${property.title}</h3>
      <div class="property-location">
        <i data-lucide="map-pin"></i>
        <span>${property.location}</span>
      </div>
      <div class="property-details">
        ${bedroomsBathrooms}
        <div class="detail-item">
          <i data-lucide="square"></i>
          <span>${property.surface}m²</span>
        </div>
      </div>
      <div class="property-agent">
        <div class="agent-info">
          <div class="agent-avatar">${property.agent.avatar}</div>
          <div class="agent-details">
            <div class="agent-name">${property.agent.name}</div>
            <div class="agent-role">Agent Immobilier</div>
          </div>
        </div>
        <div class="agent-actions">
          <button class="btn-icon" onclick="event.stopPropagation(); contactAgent('${property.agent.phone}')">
            <i data-lucide="phone"></i>
          </button>
          <button class="btn-icon" onclick="event.stopPropagation(); contactAgent('${property.agent.email}')">
            <i data-lucide="mail"></i>
          </button>
        </div>
      </div>
    </div>
  `;

  return card;
}

function setupPagination(totalItems) {
  const pagination = document.getElementById("pagination");
  const prevPageBtn = document.getElementById("prevPage");
  const nextPageBtn = document.getElementById("nextPage");
  const paginationNumbers = document.getElementById("paginationNumbers");

  if (!pagination || !prevPageBtn || !nextPageBtn || !paginationNumbers) return;

  const totalPages = Math.ceil(totalItems / itemsPerPage);

  if (totalPages <= 1) {
    pagination.style.display = "none";
    return;
  }

  pagination.style.display = "flex";

  // Update prev/next buttons
  prevPageBtn.disabled = currentPage === 1;
  nextPageBtn.disabled = currentPage === totalPages;

  prevPageBtn.onclick = () => {
    if (currentPage > 1) {
      currentPage--;
      renderProperties();
    }
  };

  nextPageBtn.onclick = () => {
    if (currentPage < totalPages) {
      currentPage++;
      renderProperties();
    }
  };

  // Generate page numbers
  paginationNumbers.innerHTML = "";
  for (let i = 1; i <= totalPages; i++) {
    const pageBtn = document.createElement("button");
    pageBtn.textContent = i;
    pageBtn.className = i === currentPage ? "active" : "";
    pageBtn.onclick = () => {
      currentPage = i;
      renderProperties();
    };
    paginationNumbers.appendChild(pageBtn);
  }
}

function updateResultsCount() {
  const resultsCount = document.getElementById("results-count");
  if (resultsCount) {
    const count = filteredProperties.length;
    resultsCount.textContent = `${count} propriété${count !== 1 ? "s" : ""} trouvée${count !== 1 ? "s" : ""}`;
  }
}

function updateActiveFilters() {
  const activeFiltersSection = document.getElementById("activeFilters");
  const activeFiltersList = document.getElementById("activeFiltersList");

  if (!activeFiltersSection || !activeFiltersList) return;

  const filterCount = Object.keys(currentFilters).length;

  if (filterCount === 0) {
    activeFiltersSection.style.display = "none";
    return;
  }

  activeFiltersSection.style.display = "block";
  activeFiltersList.innerHTML = "";

  Object.entries(currentFilters).forEach(([key, value]) => {
    const filterTag = document.createElement("div");
    filterTag.className = "filter-tag";

    let displayValue = value;
    switch (key) {
      case "transaction":
        displayValue = value === "achat" ? "Achat" : "Location";
        break;
      case "propertyType":
        displayValue = value.charAt(0).toUpperCase() + value.slice(1);
        break;
      case "minPrice":
      case "maxPrice":
        displayValue = `${value}$`;
        break;
    }

    filterTag.innerHTML = `
      <span>${displayValue}</span>
      <button onclick="removeFilter('${key}')">
        <i data-lucide="x"></i>
      </button>
    `;
    activeFiltersList.appendChild(filterTag);
  });

  lucide.createIcons();
}

function removeFilter(filterKey) {
  delete currentFilters[filterKey];

  // Clear form field
  const formField = document.getElementById(filterKey);
  if (formField) {
    formField.value = "";
  }

  currentPage = 1;
  applyFilters();
  updateActiveFilters();
  updateURL();
}

function clearAllFilters() {
  currentFilters = {};
  currentPage = 1;

  // Clear form
  const form = document.getElementById("searchForm");
  if (form) {
    form.reset();
  }

  filteredProperties = [...allProperties];
  renderProperties();
  updateResultsCount();
  updateActiveFilters();
  updateURL();

  showNotification("Tous les filtres ont été effacés", "info");
}

function setView(viewMode) {
  currentView = viewMode;

  const gridBtn = document.getElementById("gridView");
  const listBtn = document.getElementById("listView");

  if (gridBtn && listBtn) {
    gridBtn.classList.toggle("active", viewMode === "grid");
    listBtn.classList.toggle("active", viewMode === "list");
  }

  renderProperties();
}

function updateURL() {
  const params = new URLSearchParams();

  Object.entries(currentFilters).forEach(([key, value]) => {
    params.set(key, value);
  });

  const newURL =
    window.location.pathname +
    (params.toString() ? "?" + params.toString() : "");
  window.history.replaceState(null, "", newURL);
}

// Property interactions
function viewProperty(propertyId) {
  showNotification(
    `Ouverture des détails de la propriété ${propertyId}...`,
    "info",
  );
  // Here you would typically navigate to property detail page
}

function toggleFavorite(propertyId) {
  const property = allProperties.find((p) => p.id === propertyId);
  if (property) {
    property.isFavorite = !property.isFavorite;
    showNotification(
      property.isFavorite ? "Ajouté aux favoris" : "Retiré des favoris",
      "info",
    );
    renderProperties();
  }
}

function contactAgent(contact) {
  if (contact.includes("@")) {
    window.location.href = `mailto:${contact}`;
  } else {
    window.location.href = `tel:${contact}`;
  }
}

// Export functions for global use
window.searchPage = {
  performSearch,
  clearAllFilters,
  setView,
  toggleFavorite,
  contactAgent,
  viewProperty,
  removeFilter,
};
