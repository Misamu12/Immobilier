<?php
require_once("../config/config.php");

// Nombre de propriétés actives
$stmt = $connexion->query("SELECT COUNT(*) FROM annonce");
$nb_proprietes = $stmt->fetchColumn();

// Nombre d'agents immobiliers validés
$stmt = $connexion->query("SELECT COUNT(*) FROM commissionnaire WHERE statut_validation = 1");
$nb_agents = $stmt->fetchColumn();

// Nombre de villes couvertes (lieux distincts dans annonce)
$stmt = $connexion->query("SELECT COUNT(DISTINCT lieu) FROM annonce");
$nb_villes = $stmt->fetchColumn();

// Propriétés en vedette (3 dernières annonces)
$stmt = $connexion->query("
    SELECT a.*, u.nom AS nom_agent, c.telephone AS tel_agent
    FROM annonce a
    JOIN commissionnaire c ON a.id_commissionnaire = c.id_commissionnaire
    JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
    ORDER BY a.date_publication DESC
    LIMIT 3
");
$annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Pour chaque annonce, récupérer la première photo (optionnel)
foreach ($annonces as &$annonce) {
    $stmtPhoto = $connexion->prepare("SELECT url_photo FROM photo_annonce WHERE id_annonce = ? LIMIT 1");
    $stmtPhoto->execute([$annonce['id_annonce']]);
    $annonce['photo'] = $stmtPhoto->fetchColumn();
}
unset($annonce);

// Note : Pour les avis clients, tu peux ajouter une requête si tu as une table d'avis
?>

<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Accueil - ImmobilierKin</title>
    <link rel="stylesheet" href="css/styles.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
      rel="stylesheet"
    />
    <script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.js"></script>
  </head>
  <body>
    <!-- Navigation -->
    <?php include "header.php"; ?>

    <!-- Main Content -->
    <main>
      <!-- Hero Section -->
      <section class="hero">
        <div class="hero-overlay"></div>
        <div class="container">
          <div class="hero-content">
            <h1 class="hero-title">Trouvez votre bien immobilier à Kinshasa</h1>
            <p class="hero-subtitle">
              Connectez-vous avec les meilleurs agents immobiliers de la RDC
              pour acheter, vendre ou louer en toute confiance.
            </p>
            <div class="hero-actions">
              <a href="rechercher.php" class="btn btn-light">
                Commencer votre recherche
                <i data-lucide="arrow-right"></i>
              </a>
              <a href="inscription.php" class="btn btn-outline-light">
                Devenir agent immobilier
              </a>
            </div>
          </div>
        </div>
      </section>

      <!-- Search Form -->
      <section class="search-section">
        <div class="container">
          <div class="search-card">
            <form class="search-form" action="rechercher.php" method="get">
              <div class="search-grid">
                <div class="form-group">
                  <label>Type de transaction</label>
                  <select name="transaction">
                    <option value="">Acheter/Louer</option>
                    <option value="achat">Achat</option>
                    <option value="location">Location</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Type de bien</label>
                  <select name="propertyType">
                    <option value="">Tous les types</option>
                    <option value="maison">Maison</option>
                    <option value="appartement">Appartement</option>
                    <option value="terrain">Terrain</option>
                    <option value="hotel">Hôtel</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Localisation</label>
                  <div class="input-with-icon">
                    <i data-lucide="map-pin"></i>
                    <input
                      type="text"
                      name="location"
                      placeholder="Ville, quartier..."
                    />
                  </div>
                </div>
                <div class="form-group">
                  <label>Fourchette de prix</label>
                  <div class="price-range">
                    <div class="input-with-icon">
                      <i data-lucide="dollar-sign"></i>
                      <input type="number" name="minPrice" placeholder="Min" />
                    </div>
                    <div class="input-with-icon">
                      <i data-lucide="dollar-sign"></i>
                      <input type="number" name="maxPrice" placeholder="Max" />
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-primary btn-full">
                    <i data-lucide="search"></i>
                    Rechercher
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </section>

      <!-- Stats Section -->
      <section class="stats-section">
        <div class="container">
          <div class="stats-grid">
            <div class="stat-item">
              <div class="stat-icon">
                <i data-lucide="building-2"></i>
              </div>
              <div class="stat-value"><?= $nb_proprietes ?></div>
              <div class="stat-label">Propriétés actives</div>
            </div>
            <div class="stat-item">
              <div class="stat-icon">
                <i data-lucide="users"></i>
              </div>
              <div class="stat-value"><?= $nb_agents ?></div>
              <div class="stat-label">Agents immobiliers</div>
            </div>
            <div class="stat-item">
              <div class="stat-icon">
                <i data-lucide="star"></i>
              </div>
              <div class="stat-value">4.8/5</div>
              <div class="stat-label">Avis clients</div>
            </div>
            <div class="stat-item">
              <div class="stat-icon">
                <i data-lucide="map-pin"></i>
              </div>
              <div class="stat-value"><?= $nb_villes ?></div>
              <div class="stat-label">Villes couvertes</div>
            </div>
          </div>
        </div>
      </section>

      <!-- Featured Properties -->
      <section class="featured-properties">
        <div class="container">
          <div class="section-header">
            <h2>Propriétés en vedette</h2>
            <p>Découvrez une sélection de nos meilleures offres immobilières</p>
          </div>
          <div class="properties-grid">
            <?php foreach ($annonces as $annonce): ?>
              <div class="property-card">
                <div class="property-image">
                  <div class="property-badges">
                    <span class="badge badge-primary">À vendre</span>
                    <span class="badge badge-outline"><?= htmlspecialchars($annonce['type_bien']) ?></span>
                  </div>
                  <?php if ($annonce['photo']): ?>
                    <img src="../<?= htmlspecialchars($annonce['photo']) ?>" alt="Photo annonce" style="width:100%;height:180px;object-fit:cover;">
                  <?php else: ?>
                    <div style="width:100%;height:180px;background:#eee;display:flex;align-items:center;justify-content:center;">
                      <i data-lucide="image"></i>
                    </div>
                  <?php endif; ?>
                  <button class="favorite-btn">
                    <i data-lucide="heart"></i>
                  </button>
                </div>
                <div class="property-content">
                  <div class="property-price">
                    <i data-lucide="dollar-sign"></i>
                    <?= number_format($annonce['prix'], 0, ',', ' ') ?> $
                  </div>
                  <h3 class="property-title">
                    <?= htmlspecialchars($annonce['titre']) ?>
                  </h3>
                  <div class="property-location">
                    <i data-lucide="map-pin"></i>
                    <span><?= htmlspecialchars($annonce['lieu']) ?></span>
                  </div>
                  <div class="property-details">
                    <?php if (!empty($annonce['description'])): ?>
                      <div class="detail-item">
                        <i data-lucide="info"></i>
                        <span><?= htmlspecialchars(mb_strimwidth($annonce['description'], 0, 60, "...")) ?></span>
                      </div>
                    <?php endif; ?>
                  </div>
                  <div class="property-agent">
                    <div class="agent-info">
                      <div class="agent-avatar"><?= strtoupper(substr($annonce['nom_agent'], 0, 1)) ?></div>
                      <div class="agent-details">
                        <div class="agent-name"><?= htmlspecialchars($annonce['nom_agent']) ?></div>
                        <div class="agent-role">Agent Immobilier</div>
                        <div class="agent-tel"><?= htmlspecialchars($annonce['tel_agent']) ?></div>
                      </div>
                    </div>
                  </div>
                  <!-- Bouton contacter l'agent -->
                  <button class="btn btn-primary btn-sm contact-agent-btn" data-annonce="<?= $annonce['id_annonce'] ?>" data-agent="<?= htmlspecialchars($annonce['nom_agent']) ?>">
                    <i data-lucide="mail"></i>
                    Contacter l'agent
                  </button>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="section-footer">
            <a href="rechercher.php" class="btn btn-outline">
              Voir toutes les propriétés
              <i data-lucide="arrow-right"></i>
            </a>
          </div>
        </div>
      </section>

      <!-- Features Section -->
      <section class="features-section">
        <div class="container">
          <div class="section-header">
            <h2>Pourquoi choisir ImmobilierKin ?</h2>
            <p>
              Notre plateforme vous offre les meilleurs outils pour réussir vos
              projets immobiliers
            </p>
          </div>
          <div class="features-grid">
            <div class="feature-card">
              <div class="feature-icon">
                <i data-lucide="shield"></i>
              </div>
              <h3>Agents vérifiés</h3>
              <p>
                Tous nos agents immobiliers sont certifiés et font l'objet d'une
                validation rigoureuse
              </p>
            </div>
            <div class="feature-card">
              <div class="feature-icon">
                <i data-lucide="trending-up"></i>
              </div>
              <h3>Recherche intelligente</h3>
              <p>
                Trouvez rapidement le bien idéal gr��ce à nos filtres avancés et
                notre algorithme de matching
              </p>
            </div>
            <div class="feature-card">
              <div class="feature-icon">
                <i data-lucide="users"></i>
              </div>
              <h3>Support personnalisé</h3>
              <p>
                Notre équipe vous accompagne à chaque étape de votre projet
                immobilier
              </p>
            </div>
          </div>
        </div>
      </section>

      <!-- CTA Section -->
      <section class="cta-section">
        <div class="container">
          <div class="cta-content">
            <h2>Prêt à commencer votre recherche ?</h2>
            <p>
              Rejoignez des milliers d'utilisateurs qui font confiance à
              ImmobilierKin pour leurs projets immobiliers en RDC
            </p>
            <div class="cta-actions">
              <button class="btn btn-light"><a href="inscription.php">Créer un compte gratuit</a></button>
            </div>
          </div>
        </div>
      </section>

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
    </main>

    <!-- Formulaire de contact agent (popup) -->
    <div id="contactAgentModal" class="modal">
      <div class="modal-content">
        <button class="modal-close" id="closeContactModal">&times;</button>
        <h2>Contacter l'agent</h2>
        <form method="post" action="envoyer_demande.php" id="contactAgentForm">
          <input type="hidden" name="id_annonce" id="contactAnnonceId" value="">
          <div class="form-group">
            <label for="contactMessage">Votre message *</label>
            <textarea name="message" id="contactMessage" required placeholder="Votre message à l'agent"></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Envoyer la demande</button>
        </form>
      </div>
    </div>
    <style>
    .modal { display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); align-items:center; justify-content:center; z-index:1000; }
    .modal .modal-content { background:#fff; padding:2rem; border-radius:8px; min-width:320px; position:relative; }
    .modal .modal-close { position:absolute; top:10px; right:10px; background:none; border:none; font-size:2rem; cursor:pointer; }
    .modal.show { display:flex; }
    </style>

    <script>
document.querySelectorAll('.contact-agent-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    document.getElementById('contactAnnonceId').value = this.dataset.annonce;
    const modal = document.getElementById('contactAgentModal');
    modal.classList.add('show');
    modal.classList.remove('hidden');
  });
});
document.getElementById('closeContactModal').onclick = function() {
  const modal = document.getElementById('contactAgentModal');
  modal.classList.remove('show');
  modal.classList.remove('hidden');
};
</script>
  </body>
</html>
