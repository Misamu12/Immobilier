// Property Detail Page Functionality
document.addEventListener("DOMContentLoaded", function () {
  initializePropertyDetail();
});

const propertyImages = [
  {
    src: "https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=2075&auto=format&fit=crop",
    alt: "Vue extérieure",
    thumb:
      "https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=300&auto=format&fit=crop",
  },
  {
    src: "https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?q=80&w=2075&auto=format&fit=crop",
    alt: "Salon moderne",
    thumb:
      "https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?q=80&w=300&auto=format&fit=crop",
  },
  {
    src: "https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?q=80&w=2075&auto=format&fit=crop",
    alt: "Cuisine équipée",
    thumb:
      "https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?q=80&w=300&auto=format&fit=crop",
  },
  {
    src: "https://images.unsplash.com/photo-1600566752355-35792bedcfea?q=80&w=2075&auto=format&fit=crop",
    alt: "Chambre principale",
    thumb:
      "https://images.unsplash.com/photo-1600566752355-35792bedcfea?q=80&w=300&auto=format&fit=crop",
  },
  {
    src: "https://images.unsplash.com/photo-1600607687644-c7171b42498f?q=80&w=2075&auto=format&fit=crop",
    alt: "Piscine privée",
    thumb:
      "https://images.unsplash.com/photo-1600607687644-c7171b42498f?q=80&w=300&auto=format&fit=crop",
  },
  {
    src: "https://images.unsplash.com/photo-1600566752734-ce7ba73a2d24?q=80&w=2075&auto=format&fit=crop",
    alt: "Salle de bain",
    thumb:
      "https://images.unsplash.com/photo-1600566752734-ce7ba73a2d24?q=80&w=300&auto=format&fit=crop",
  },
  {
    src: "https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?q=80&w=2075&auto=format&fit=crop",
    alt: "Jardin paysager",
    thumb:
      "https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?q=80&w=300&auto=format&fit=crop",
  },
  {
    src: "https://images.unsplash.com/photo-1600566752229-450dd2c4fe15?q=80&w=2075&auto=format&fit=crop",
    alt: "Terrasse",
    thumb:
      "https://images.unsplash.com/photo-1600566752229-450dd2c4fe15?q=80&w=300&auto=format&fit=crop",
  },
];

let currentImageIndex = 0;

function initializePropertyDetail() {
  // Initialize image gallery
  initializeImageGallery();

  // Setup property interactions
  setupPropertyInteractions();

  // Setup contact form
  setupContactForm();

  // Setup agent contact
  setupAgentContact();

  // Setup similar properties
  setupSimilarProperties();

  // Update page views
  updatePropertyViews();

  console.log("🏠 Property detail page initialized");
}

function initializeImageGallery() {
  const mainImage = document.getElementById("mainImage");
  const prevBtn = document.getElementById("prevImage");
  const nextBtn = document.getElementById("nextImage");
  const fullscreenBtn = document.getElementById("fullscreenBtn");
  const thumbnails = document.querySelectorAll(".thumbnail");

  if (!mainImage) return;

  // Set initial image
  updateMainImage(0);

  // Previous image
  if (prevBtn) {
    prevBtn.addEventListener("click", function (e) {
      e.stopPropagation();
      showPreviousImage();
    });
  }

  // Next image
  if (nextBtn) {
    nextBtn.addEventListener("click", function (e) {
      e.stopPropagation();
      showNextImage();
    });
  }

  // Fullscreen
  if (fullscreenBtn) {
    fullscreenBtn.addEventListener("click", function (e) {
      e.stopPropagation();
      openImageFullscreen();
    });
  }

  // Thumbnail clicks
  thumbnails.forEach((thumbnail, index) => {
    if (thumbnail.classList.contains("view-all")) {
      thumbnail.addEventListener("click", function () {
        openImageGallery();
      });
    } else {
      thumbnail.addEventListener("click", function () {
        showImage(index);
      });
    }
  });

  // Keyboard navigation
  document.addEventListener("keydown", function (e) {
    if (e.key === "ArrowLeft") {
      showPreviousImage();
    } else if (e.key === "ArrowRight") {
      showNextImage();
    } else if (e.key === "Escape") {
      closeFullscreen();
    }
  });
}

function updateMainImage(index) {
  const mainImage = document.getElementById("mainImage");
  const thumbnails = document.querySelectorAll(".thumbnail");

  if (!mainImage || !propertyImages[index]) return;

  currentImageIndex = index;
  const imageData = propertyImages[index];

  // Update main image
  const img = mainImage.querySelector("img");
  if (img) {
    img.src = imageData.src;
    img.alt = imageData.alt;
  }

  // Update active thumbnail
  thumbnails.forEach((thumb, i) => {
    thumb.classList.toggle("active", i === index);
  });
}

function showImage(index) {
  if (index >= 0 && index < propertyImages.length) {
    updateMainImage(index);
  }
}

function showNextImage() {
  const nextIndex = (currentImageIndex + 1) % propertyImages.length;
  showImage(nextIndex);
}

function showPreviousImage() {
  const prevIndex =
    currentImageIndex === 0 ? propertyImages.length - 1 : currentImageIndex - 1;
  showImage(prevIndex);
}

function openImageFullscreen() {
  // Create fullscreen modal
  const modal = document.createElement("div");
  modal.className = "image-fullscreen-modal";
  modal.innerHTML = `
    <div class="fullscreen-content">
      <button class="fullscreen-close">
        <i data-lucide="x"></i>
      </button>
      <button class="fullscreen-prev">
        <i data-lucide="chevron-left"></i>
      </button>
      <button class="fullscreen-next">
        <i data-lucide="chevron-right"></i>
      </button>
      <img src="${propertyImages[currentImageIndex].src}" alt="${propertyImages[currentImageIndex].alt}" />
      <div class="fullscreen-counter">
        ${currentImageIndex + 1} / ${propertyImages.length}
      </div>
    </div>
  `;

  document.body.appendChild(modal);
  document.body.style.overflow = "hidden";

  // Setup fullscreen controls
  const closeBtn = modal.querySelector(".fullscreen-close");
  const prevBtn = modal.querySelector(".fullscreen-prev");
  const nextBtn = modal.querySelector(".fullscreen-next");

  closeBtn.addEventListener("click", () => closeFullscreen(modal));
  prevBtn.addEventListener("click", () => updateFullscreenImage(modal, -1));
  nextBtn.addEventListener("click", () => updateFullscreenImage(modal, 1));

  // Close on background click
  modal.addEventListener("click", (e) => {
    if (e.target === modal) {
      closeFullscreen(modal);
    }
  });

  lucide.createIcons();
}

function updateFullscreenImage(modal, direction) {
  let newIndex = currentImageIndex + direction;
  if (newIndex < 0) newIndex = propertyImages.length - 1;
  if (newIndex >= propertyImages.length) newIndex = 0;

  currentImageIndex = newIndex;
  const img = modal.querySelector("img");
  const counter = modal.querySelector(".fullscreen-counter");

  img.src = propertyImages[currentImageIndex].src;
  img.alt = propertyImages[currentImageIndex].alt;
  counter.textContent = `${currentImageIndex + 1} / ${propertyImages.length}`;

  // Update main gallery
  updateMainImage(currentImageIndex);
}

function closeFullscreen(modal) {
  if (modal) {
    document.body.removeChild(modal);
  } else {
    const existingModal = document.querySelector(".image-fullscreen-modal");
    if (existingModal) {
      document.body.removeChild(existingModal);
    }
  }
  document.body.style.overflow = "";
}

function openImageGallery() {
  showNotification("Galerie complète (8 photos)", "info");
  openImageFullscreen();
}

function setupPropertyInteractions() {
  // Favorite button
  const favoriteBtn = document.querySelector(".favorite-btn");
  if (favoriteBtn) {
    favoriteBtn.addEventListener("click", function () {
      this.classList.toggle("active");
      const isActive = this.classList.contains("active");
      const icon = this.querySelector("i");

      if (isActive) {
        icon.setAttribute("data-lucide", "heart");
        icon.style.fill = "#ef4444";
        icon.style.color = "#ef4444";
        showNotification("Propriété ajoutée aux favoris", "success");
      } else {
        icon.setAttribute("data-lucide", "heart");
        icon.style.fill = "none";
        icon.style.color = "currentColor";
        showNotification("Propriété retirée des favoris", "info");
      }

      lucide.createIcons();
    });
  }

  // Share button
  const shareBtn = document.querySelector(".share-btn");
  if (shareBtn) {
    shareBtn.addEventListener("click", function () {
      if (navigator.share) {
        navigator
          .share({
            title: "Villa moderne avec piscine - Gombe, Kinshasa",
            text: "Découvrez cette magnifique villa à Kinshasa",
            url: window.location.href,
          })
          .then(() => {
            showNotification("Propriété partagée avec succès", "success");
          })
          .catch(() => {
            fallbackShare();
          });
      } else {
        fallbackShare();
      }
    });
  }

  // Map placeholder
  const mapPlaceholder = document.querySelector(".map-placeholder");
  if (mapPlaceholder) {
    mapPlaceholder.addEventListener("click", function () {
      showNotification(
        "Ouverture de la carte interactive (non implémentée)",
        "info",
      );
    });
  }
}

function fallbackShare() {
  // Copy URL to clipboard
  navigator.clipboard
    .writeText(window.location.href)
    .then(() => {
      showNotification("Lien copié dans le presse-papiers", "success");
    })
    .catch(() => {
      showNotification("Impossible de copier le lien", "error");
    });
}

function setupContactForm() {
  const contactForm = document.getElementById("contactForm");

  if (contactForm) {
    contactForm.addEventListener("submit", function (e) {
      e.preventDefault();
      handleContactSubmission(new FormData(this));
    });
  }
}

function handleContactSubmission(formData) {
  const contactData = {};
  for (let [key, value] of formData.entries()) {
    contactData[key] = value;
  }

  // Validation
  if (
    !contactData.contactName ||
    !contactData.contactEmail ||
    !contactData.contactPhone
  ) {
    showNotification("Veuillez remplir tous les champs requis", "error");
    return;
  }

  if (!isValidEmail(contactData.contactEmail)) {
    showNotification("Adresse email invalide", "error");
    return;
  }

  showNotification("Envoi de votre demande...", "info");

  // Simulate API call
  setTimeout(() => {
    showNotification(
      "Votre demande a été envoyée à l'agent immobilier. Vous recevrez une réponse sous 24h.",
      "success",
    );

    // Reset form
    document.getElementById("contactForm").reset();

    // Add to agent notifications (simulation)
    addAgentNotification(contactData);
  }, 1500);
}

function addAgentNotification(contactData) {
  // Store notification for agent dashboard
  const notifications = JSON.parse(
    localStorage.getItem("agentNotifications") || "[]",
  );

  notifications.unshift({
    id: Date.now(),
    type: "contact",
    propertyId: "villa-gombe-001",
    propertyTitle: "Villa moderne avec piscine - Gombe",
    contactName: contactData.contactName,
    contactEmail: contactData.contactEmail,
    contactPhone: contactData.contactPhone,
    message: contactData.contactMessage || "Demande d'information",
    timestamp: new Date().toISOString(),
    read: false,
  });

  localStorage.setItem("agentNotifications", JSON.stringify(notifications));
}

function setupAgentContact() {
  // Call button
  const callBtns = document.querySelectorAll(
    '.contact-actions .btn-primary, .contact-item:has([data-lucide="phone"])',
  );
  callBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      const phoneNumber = "+243 81 234 56 78";
      if (btn.tagName === "BUTTON") {
        window.location.href = `tel:${phoneNumber}`;
      }
    });
  });

  // Message button
  const messageBtns = document.querySelectorAll(
    '.contact-actions .btn-outline:has([data-lucide="message-circle"])',
  );
  messageBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      // Scroll to contact form
      const contactForm = document.getElementById("contactForm");
      if (contactForm) {
        contactForm.scrollIntoView({ behavior: "smooth" });
        contactForm.querySelector("input").focus();
      }
    });
  });

  // Schedule visit button
  const scheduleBtns = document.querySelectorAll(
    '.contact-actions .btn-outline:has([data-lucide="calendar"])',
  );
  scheduleBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      showNotification(
        "Fonctionnalité de planification de visite à venir",
        "info",
      );
    });
  });

  // Email contact
  const emailContacts = document.querySelectorAll(
    '.contact-item:has([data-lucide="mail"])',
  );
  emailContacts.forEach((item) => {
    item.addEventListener("click", function () {
      const email = "marie.katanga@immobilierkin.cd";
      const subject = "Demande d'information - Villa moderne avec piscine";
      const body = `Bonjour Marie,

Je suis intéressé(e) par la villa moderne avec piscine située à Gombe, Kinshasa (REF: IK-001).

Pourriez-vous me contacter pour plus d'informations ?

Cordialement`;

      window.location.href = `mailto:${email}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
    });
  });
}

function setupSimilarProperties() {
  const similarProperties = document.querySelectorAll(".similar-property");

  similarProperties.forEach((property) => {
    property.addEventListener("click", function () {
      const title = this.querySelector("h4").textContent;
      showNotification(`Ouverture de: ${title}`, "info");
      // In a real app, this would navigate to the property detail page
    });
  });
}

function updatePropertyViews() {
  // Simulate updating property views
  const viewsElement = document.querySelector(
    '.property-meta span:has([data-lucide="eye"])',
  );

  if (viewsElement) {
    // Get current views and increment
    const currentViews = parseInt(viewsElement.textContent.match(/\d+/)[0]);
    const newViews = currentViews + 1;

    // Update display
    setTimeout(() => {
      viewsElement.innerHTML = `
        <i data-lucide="eye"></i>
        ${newViews} vues
      `;
      lucide.createIcons();
    }, 2000);

    // Store view in local storage for analytics
    const propertyViews = JSON.parse(
      localStorage.getItem("propertyViews") || "{}",
    );
    const propertyId = "villa-gombe-001";
    propertyViews[propertyId] = (propertyViews[propertyId] || 0) + 1;
    localStorage.setItem("propertyViews", JSON.stringify(propertyViews));
  }
}

// Utility functions
function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

// Add CSS for fullscreen modal
const style = document.createElement("style");
style.textContent = `
  .image-fullscreen-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.95);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    animation: fadeIn 0.3s ease;
  }

  .fullscreen-content {
    position: relative;
    max-width: 90vw;
    max-height: 90vh;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .fullscreen-content img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    border-radius: 8px;
  }

  .fullscreen-close,
  .fullscreen-prev,
  .fullscreen-next {
    position: absolute;
    background: rgba(255, 255, 255, 0.9);
    border: none;
    border-radius: 50%;
    width: 3rem;
    height: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s ease;
    color: #1f2937;
  }

  .fullscreen-close:hover,
  .fullscreen-prev:hover,
  .fullscreen-next:hover {
    background: white;
    transform: scale(1.1);
  }

  .fullscreen-close {
    top: 1rem;
    right: 1rem;
  }

  .fullscreen-prev {
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
  }

  .fullscreen-next {
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
  }

  .fullscreen-counter {
    position: absolute;
    bottom: 1rem;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 1rem;
    font-size: 0.875rem;
  }

  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }

  @media (max-width: 768px) {
    .fullscreen-prev,
    .fullscreen-next {
      width: 2.5rem;
      height: 2.5rem;
    }

    .fullscreen-close {
      width: 2.5rem;
      height: 2.5rem;
    }

    .fullscreen-counter {
      font-size: 0.75rem;
      padding: 0.375rem 0.75rem;
    }
  }
`;
document.head.appendChild(style);

// Export for debugging
window.propertyDetail = {
  showImage,
  updatePropertyViews,
  handleContactSubmission,
  openImageFullscreen,
};
