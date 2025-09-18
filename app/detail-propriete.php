<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Villa moderne avec piscine - Gombe, Kinshasa - ImmobilierKin</title>
    <link rel="stylesheet" href="css/styles.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
      rel="stylesheet"
    />
    <script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.js"></script>
  </head>
  <body>
    <!-- Navigation -->
    <nav class="navbar">
      <div class="container">
        <div class="nav-content">
          <a href="accueil.php" class="logo">
            <div class="logo-icon">
              <i data-lucide="building-2"></i>
            </div>
            <span class="logo-text">ImmobilierKin</span>
          </a>

          <div class="nav-links desktop-only">
            <a href="accueil.php" class="nav-link">
              <i data-lucide="home"></i>
              <span>Accueil</span>
            </a>
            <a href="rechercher.php" class="nav-link">
              <i data-lucide="search"></i>
              <span>Rechercher</span>
            </a>
            <a href="dashboard.php" class="nav-link">
              <i data-lucide="building-2"></i>
              <span>Dashboard</span>
            </a>
            <a href="admin.php" class="nav-link">
              <i data-lucide="shield"></i>
              <span>Admin</span>
            </a>
          </div>

          <div class="nav-actions desktop-only">
            <a href="connexion.php" class="btn btn-ghost">Connexion</a>
            <a href="inscription.php" class="btn btn-primary">S'inscrire</a>
          </div>

          <button class="mobile-menu-btn mobile-only">
            <i data-lucide="menu"></i>
          </button>
        </div>

        <!-- Mobile Menu -->
        <div class="mobile-menu hidden">
          <div class="mobile-nav-links">
            <a href="accueil.php" class="mobile-nav-link">
              <i data-lucide="home"></i>
              <span>Accueil</span>
            </a>
            <a href="rechercher.php" class="mobile-nav-link">
              <i data-lucide="search"></i>
              <span>Rechercher</span>
            </a>
            <a href="dashboard.php" class="mobile-nav-link">
              <i data-lucide="building-2"></i>
              <span>Dashboard</span>
            </a>
            <a href="admin.php" class="mobile-nav-link">
              <i data-lucide="shield"></i>
              <span>Admin</span>
            </a>
          </div>
          <div class="mobile-auth-actions">
            <a href="connexion.php" class="btn btn-ghost">
              <i data-lucide="user"></i>
              Connexion
            </a>
            <a href="inscription.php" class="btn btn-primary">S'inscrire</a>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main>
      <!-- Breadcrumb -->
      <div class="breadcrumb-container">
        <div class="container">
          <nav class="breadcrumb">
            <a href="accueil.php">Accueil</a>
            <i data-lucide="chevron-right"></i>
            <a href="rechercher.php">Rechercher</a>
            <i data-lucide="chevron-right"></i>
            <span>Villa moderne avec piscine</span>
          </nav>
        </div>
      </div>

      <!-- Property Details -->
      <div class="property-detail-container">
        <div class="container">
          <!-- Property Header -->
          <div class="property-header">
            <div class="property-title-section">
              <div class="property-badges">
                <span class="badge badge-primary">À vendre</span>
                <span class="badge badge-outline">Maison</span>
              </div>
              <h1>Villa moderne avec piscine - Quartier résidentiel</h1>
              <div class="property-location">
                <i data-lucide="map-pin"></i>
                <span>Gombe, Kinshasa, République Démocratique du Congo</span>
              </div>
              <div class="property-meta">
                <span class="property-id">
                  <i data-lucide="hash"></i>
                  REF: IK-001
                </span>
                <span class="property-views">
                  <i data-lucide="eye"></i>
                  145 vues
                </span>
                <span class="property-date">
                  <i data-lucide="calendar"></i>
                  Publié le 15 Jan 2024
                </span>
              </div>
            </div>
            <div class="property-price-section">
              <div class="property-price">
                <span class="price-amount">$450,000</span>
                <span class="price-per-sqm">$1,607/m²</span>
              </div>
              <div class="property-actions">
                <button class="btn btn-outline favorite-btn">
                  <i data-lucide="heart"></i>
                  Favoris
                </button>
                <button class="btn btn-outline share-btn">
                  <i data-lucide="share-2"></i>
                  Partager
                </button>
              </div>
            </div>
          </div>

          <!-- Property Content -->
          <div class="property-content">
            <!-- Left Column -->
            <div class="property-main">
              <!-- Image Gallery -->
              <div class="property-gallery">
                <div class="gallery-main">
                  <div class="main-image" id="mainImage">
                    <img
                      src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=2075&auto=format&fit=crop"
                      alt="Villa moderne avec piscine"
                    />
                    <div class="image-overlay">
                      <button class="gallery-btn prev-btn" id="prevImage">
                        <i data-lucide="chevron-left"></i>
                      </button>
                      <button class="gallery-btn next-btn" id="nextImage">
                        <i data-lucide="chevron-right"></i>
                      </button>
                      <button
                        class="gallery-btn fullscreen-btn"
                        id="fullscreenBtn"
                      >
                        <i data-lucide="maximize"></i>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="gallery-thumbnails">
                  <div class="thumbnail active">
                    <img
                      src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=300&auto=format&fit=crop"
                      alt="Vue extérieure"
                    />
                  </div>
                  <div class="thumbnail">
                    <img
                      src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?q=80&w=300&auto=format&fit=crop"
                      alt="Salon"
                    />
                  </div>
                  <div class="thumbnail">
                    <img
                      src="https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?q=80&w=300&auto=format&fit=crop"
                      alt="Cuisine"
                    />
                  </div>
                  <div class="thumbnail">
                    <img
                      src="https://images.unsplash.com/photo-1600566752355-35792bedcfea?q=80&w=300&auto=format&fit=crop"
                      alt="Chambre principale"
                    />
                  </div>
                  <div class="thumbnail">
                    <img
                      src="https://images.unsplash.com/photo-1600607687644-c7171b42498f?q=80&w=300&auto=format&fit=crop"
                      alt="Piscine"
                    />
                  </div>
                  <div class="thumbnail view-all">
                    <span>+3 photos</span>
                  </div>
                </div>
              </div>

              <!-- Property Details -->
              <div class="property-details-section">
                <h2>Détails de la propriété</h2>
                <div class="details-grid">
                  <div class="detail-item">
                    <i data-lucide="home"></i>
                    <div>
                      <span class="detail-label">Type</span>
                      <span class="detail-value">Maison</span>
                    </div>
                  </div>
                  <div class="detail-item">
                    <i data-lucide="square"></i>
                    <div>
                      <span class="detail-label">Surface</span>
                      <span class="detail-value">280 m²</span>
                    </div>
                  </div>
                  <div class="detail-item">
                    <i data-lucide="bed"></i>
                    <div>
                      <span class="detail-label">Chambres</span>
                      <span class="detail-value">4</span>
                    </div>
                  </div>
                  <div class="detail-item">
                    <i data-lucide="bath"></i>
                    <div>
                      <span class="detail-label">Salles de bain</span>
                      <span class="detail-value">3</span>
                    </div>
                  </div>
                  <div class="detail-item">
                    <i data-lucide="car"></i>
                    <div>
                      <span class="detail-label">Garage</span>
                      <span class="detail-value">2 places</span>
                    </div>
                  </div>
                  <div class="detail-item">
                    <i data-lucide="calendar"></i>
                    <div>
                      <span class="detail-label">Année de construction</span>
                      <span class="detail-value">2022</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Description -->
              <div class="property-description">
                <h2>Description</h2>
                <div class="description-content">
                  <p>
                    Magnifique villa moderne située dans le quartier résidentiel
                    paisible de Gombe, à Kinshasa. Cette propriété
                    exceptionnelle offre un cadre de vie luxueux avec ses 280 m²
                    de surface habitable soigneusement aménagés.
                  </p>
                  <p>
                    La villa dispose de 4 chambres spacieuses, dont une suite
                    parentale avec dressing et salle de bain privative. Les 3
                    salles de bain sont équipées d'équipements modernes et de
                    finitions haut de gamme.
                  </p>
                  <p>
                    L'espace de vie principal comprend un salon lumineux, une
                    salle à manger élégante et une cuisine moderne entièrement
                    équipée avec des appareils électroménagers de qualité. La
                    propriété bénéficie également d'une magnifique piscine
                    privée, parfaite pour se détendre et profiter du climat
                    tropical.
                  </p>
                  <p>
                    Le garage peut accueillir 2 véhicules et la propriété
                    dispose d'un système de sécurité 24h/24 pour votre
                    tranquillité d'esprit.
                  </p>
                </div>
              </div>

              <!-- Features & Amenities -->
              <div class="property-features">
                <h2>Équipements et caractéristiques</h2>
                <div class="features-grid">
                  <div class="feature-category">
                    <h3>Intérieur</h3>
                    <ul>
                      <li>
                        <i data-lucide="check"></i>
                        Climatisation centrale
                      </li>
                      <li>
                        <i data-lucide="check"></i>
                        Cuisine moderne équipée
                      </li>
                      <li>
                        <i data-lucide="check"></i>
                        Parquet dans les chambres
                      </li>
                      <li>
                        <i data-lucide="check"></i>
                        Dressing dans suite parentale
                      </li>
                    </ul>
                  </div>
                  <div class="feature-category">
                    <h3>Extérieur</h3>
                    <ul>
                      <li>
                        <i data-lucide="check"></i>
                        Piscine privée
                      </li>
                      <li>
                        <i data-lucide="check"></i>
                        Jardin paysager
                      </li>
                      <li>
                        <i data-lucide="check"></i>
                        Terrasse couverte
                      </li>
                      <li>
                        <i data-lucide="check"></i>
                        Barbecue extérieur
                      </li>
                    </ul>
                  </div>
                  <div class="feature-category">
                    <h3>Sécurité & Services</h3>
                    <ul>
                      <li>
                        <i data-lucide="check"></i>
                        Sécurité 24h/24
                      </li>
                      <li>
                        <i data-lucide="check"></i>
                        Portail électrique
                      </li>
                      <li>
                        <i data-lucide="check"></i>
                        Groupe électrogène
                      </li>
                      <li>
                        <i data-lucide="check"></i>
                        Internet fibre optique
                      </li>
                    </ul>
                  </div>
                </div>
              </div>

              <!-- Location & Map -->
              <div class="property-location-section">
                <h2>Localisation</h2>
                <div class="location-info">
                  <div class="location-details">
                    <h3>Gombe, Kinshasa</h3>
                    <p>
                      Situé dans le quartier prestigieux de Gombe, cette
                      propriété bénéficie d'un emplacement de choix au cœur de
                      Kinshasa. Le quartier offre un accès facile aux
                      principales commodités de la ville.
                    </p>
                    <div class="nearby-places">
                      <h4>À proximité :</h4>
                      <ul>
                        <li>
                          <i data-lucide="graduation-cap"></i>
                          École Internationale - 500m
                        </li>
                        <li>
                          <i data-lucide="shopping-cart"></i>
                          Centre commercial - 1.2km
                        </li>
                        <li>
                          <i data-lucide="plane"></i>
                          Aéroport de N'djili - 25km
                        </li>
                        <li>
                          <i data-lucide="hospital"></i>
                          Hôpital du Cinquantenaire - 2km
                        </li>
                      </ul>
                    </div>
                  </div>
                  <div class="map-placeholder">
                    <div class="map-content">
                      <i data-lucide="map-pin"></i>
                      <p>Carte interactive</p>
                      <small>Cliquez pour voir l'emplacement exact</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Right Column - Agent Info -->
            <div class="property-sidebar">
              <!-- Agent Card -->
              <div class="agent-card">
                <div class="agent-header">
                  <div class="agent-avatar">
                    <img
                      src="https://images.unsplash.com/photo-1494790108755-2616b612b786?q=80&w=150&auto=format&fit=crop"
                      alt="Marie Katanga"
                    />
                    <div class="agent-status online"></div>
                  </div>
                  <div class="agent-info">
                    <h3>Marie Katanga</h3>
                    <p>Agent Immobilier Certifié</p>
                    <div class="agent-rating">
                      <div class="stars">
                        <i data-lucide="star" class="filled"></i>
                        <i data-lucide="star" class="filled"></i>
                        <i data-lucide="star" class="filled"></i>
                        <i data-lucide="star" class="filled"></i>
                        <i data-lucide="star" class="filled"></i>
                      </div>
                      <span>4.9 (23 avis)</span>
                    </div>
                  </div>
                </div>

                <div class="agent-stats">
                  <div class="stat">
                    <span class="stat-value">47</span>
                    <span class="stat-label">Propriétés vendues</span>
                  </div>
                  <div class="stat">
                    <span class="stat-value">5</span>
                    <span class="stat-label">Années d'expérience</span>
                  </div>
                </div>

                <div class="agent-contact">
                  <div class="contact-info">
                    <div class="contact-item">
                      <i data-lucide="phone"></i>
                      <span>+243 81 234 56 78</span>
                    </div>
                    <div class="contact-item">
                      <i data-lucide="mail"></i>
                      <span>marie.katanga@immobilierkin.cd</span>
                    </div>
                  </div>

                  <div class="contact-actions">
                    <button class="btn btn-primary btn-full">
                      <i data-lucide="phone"></i>
                      Appeler
                    </button>
                    <button class="btn btn-outline btn-full">
                      <i data-lucide="message-circle"></i>
                      Envoyer un message
                    </button>
                    <button class="btn btn-outline btn-full">
                      <i data-lucide="calendar"></i>
                      Planifier une visite
                    </button>
                  </div>
                </div>
              </div>

              <!-- Contact Form -->
              <div class="contact-form-card">
                <h3>Intéressé par cette propriété ?</h3>
                <form class="contact-form" id="contactForm">
                  <div class="form-group">
                    <label for="contactName">Nom complet *</label>
                    <input
                      type="text"
                      id="contactName"
                      name="contactName"
                      required
                    />
                  </div>
                  <div class="form-group">
                    <label for="contactEmail">Email *</label>
                    <input
                      type="email"
                      id="contactEmail"
                      name="contactEmail"
                      required
                    />
                  </div>
                  <div class="form-group">
                    <label for="contactPhone">telephone *</label>
                    <input
                      type="tel"
                      id="contactPhone"
                      name="contactPhone"
                      required
                    />
                  </div>
                  <div class="form-group">
                    <label for="contactMessage">Message</label>
                    <textarea
                      id="contactMessage"
                      name="contactMessage"
                      rows="4"
                      placeholder="Je suis intéressé par cette propriété. Pouvez-vous me contacter pour plus d'informations ?"
                    ></textarea>
                  </div>
                  <button type="submit" class="btn btn-primary btn-full">
                    <i data-lucide="send"></i>
                    Envoyer la demande
                  </button>
                </form>
              </div>

              <!-- Similar Properties -->
              <div class="similar-properties">
                <h3>Propriétés similaires</h3>
                <div class="similar-property">
                  <div class="similar-property-image">
                    <img
                      src="https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?q=80&w=150&auto=format&fit=crop"
                      alt="Appartement moderne"
                    />
                  </div>
                  <div class="similar-property-info">
                    <h4>Appartement moderne - Centre</h4>
                    <p>$180,000</p>
                    <div class="similar-property-details">
                      <span>2 ch</span>
                      <span>85 m²</span>
                    </div>
                  </div>
                </div>
                <div class="similar-property">
                  <div class="similar-property-image">
                    <img
                      src="https://images.unsplash.com/photo-1600566752229-450dd2c4fe15?q=80&w=150&auto=format&fit=crop"
                      alt="Villa familiale"
                    />
                  </div>
                  <div class="similar-property-info">
                    <h4>Villa familiale - Lemba</h4>
                    <p>$220,000</p>
                    <div class="similar-property-details">
                      <span>3 ch</span>
                      <span>150 m²</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
      <div class="container">
        <div class="footer-content">
          <div class="footer-section">
            <div class="footer-brand">
              <div class="logo-icon">
                <i data-lucide="building-2"></i>
              </div>
              <span class="logo-text">ImmobilierKin</span>
            </div>
            <p>
              La plateforme de référence pour vos projets immobiliers en
              République Démocratique du Congo.
            </p>
          </div>
          <div class="footer-section">
            <h3>Utilisateurs</h3>
            <ul>
              <li><a href="rechercher.php">Rechercher un bien</a></li>
              <li><a href="#">Créer une alerte</a></li>
              <li><a href="#">Guide d'achat</a></li>
            </ul>
          </div>
          <div class="footer-section">
            <h3>Agents Immobiliers</h3>
            <ul>
              <li><a href="dashboard.php">Devenir agent immobilier</a></li>
              <li><a href="dashboard.php">Dashboard</a></li>
              <li><a href="#">Outils de gestion</a></li>
            </ul>
          </div>
          <div class="footer-section">
            <h3>Support</h3>
            <ul>
              <li><a href="#">Centre d'aide</a></li>
              <li><a href="#">Contact</a></li>
              <li><a href="#">Conditions d'utilisation</a></li>
            </ul>
          </div>
        </div>
        <div class="footer-bottom">
          <p>&copy; 2024 ImmobilierKin. Tous droits réservés.</p>
        </div>
      </div>
    </footer>

    <script src="common.js"></script>
    <script src="property-detail.js"></script>
  </body>
</html>
