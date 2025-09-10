<?php
require_once("../config/config.php");

// Initialisation des filtres
$type_bien = $_GET['propertyType'] ?? '';
$transaction = $_GET['transaction'] ?? '';
$lieu = $_GET['location'] ?? '';
$minPrice = $_GET['minPrice'] ?? '';
$maxPrice = $_GET['maxPrice'] ?? '';

// Construction de la requête SQL dynamique
$sql = "
    SELECT a.*, u.nom AS nom_agent, c.telephone AS tel_agent
    FROM annonce a
    JOIN commissionnaire c ON a.id_commissionnaire = c.id_commissionnaire
    JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
    WHERE 1
";
$params = [];

// Filtres dynamiques
if ($type_bien) {
    $sql .= " AND a.type_bien = ?";
    $params[] = $type_bien;
}
if ($lieu) {
    $sql .= " AND a.lieu LIKE ?";
    $params[] = "%$lieu%";
}
if ($minPrice !== '') {
    $sql .= " AND a.prix >= ?";
    $params[] = $minPrice;
}
if ($maxPrice !== '') {
    $sql .= " AND a.prix <= ?";
    $params[] = $maxPrice;
}

// Tri (par défaut : plus récents)
$sql .= " ORDER BY a.date_publication DESC";

// Exécution de la requête
$stmt = $connexion->prepare($sql);
$stmt->execute($params);
$annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la première photo pour chaque annonce
foreach ($annonces as &$annonce) {
    $stmtPhoto = $connexion->prepare("SELECT url_photo FROM photo_annonce WHERE id_annonce = ? LIMIT 1");
    $stmtPhoto->execute([$annonce['id_annonce']]);
    $annonce['photo'] = $stmtPhoto->fetchColumn();
}
unset($annonce);

// Nombre de résultats
$nb_resultats = count($annonces);
?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Rechercher - ImmobilierKin</title>
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
      <!-- Page Header -->
      <div class="page-header">
        <div class="container">
          <h1>Recherche de propriétés</h1>
          <p id="results-count">Chargement des propriétés...</p>
        </div>
      </div>

      <!-- Search Form -->
      <div class="search-header">
        <div class="container">
          <div class="search-card">
            <form class="search-form" id="searchForm">
              <div class="search-grid">
                <div class="form-group">
                  <label>Type de transaction</label>
                  <select name="transaction" id="transaction">
                    <option value="">Acheter/Louer</option>
                    <option value="achat">Achat</option>
                    <option value="location">Location</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Type de bien</label>
                  <select name="propertyType" id="propertyType">
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
                      id="location"
                      placeholder="Ville, quartier..."
                    />
                  </div>
                </div>
                <div class="form-group">
                  <label>Fourchette de prix</label>
                  <div class="price-range">
                    <div class="input-with-icon">
                      <i data-lucide="dollar-sign"></i>
                      <input
                        type="number"
                        name="minPrice"
                        id="minPrice"
                        placeholder="Min"
                      />
                    </div>
                    <div class="input-with-icon">
                      <i data-lucide="dollar-sign"></i>
                      <input
                        type="number"
                        name="maxPrice"
                        id="maxPrice"
                        placeholder="Max"
                      />
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
      </div>

      <div class="container">
        <div class="search-content">
          <!-- Sidebar -->
          <div class="search-sidebar">
            <!-- Active Filters -->
            <div
              class="filter-section"
              id="activeFilters"
              style="display: none"
            >
              <div class="filter-header">
                <h3>Filtres actifs</h3>
                <button class="btn btn-ghost btn-sm" id="clearAllFilters">
                  Tout effacer
                </button>
              </div>
              <div class="active-filters-list" id="activeFiltersList">
                <!-- Active filters will be populated here -->
              </div>
            </div>

            <!-- View Controls -->
            <div class="filter-section">
              <h3>Affichage</h3>
              <div class="view-controls">
                <div class="view-toggle">
                  <button class="btn btn-outline btn-sm active" id="gridView">
                    <i data-lucide="grid-3x3"></i>
                    Grille
                  </button>
                  <button class="btn btn-outline btn-sm" id="listView">
                    <i data-lucide="list"></i>
                    Liste
                  </button>
                </div>
                <select id="sortBy" class="sort-select">
                  <option value="recent">Plus récents</option>
                  <option value="price-asc">Prix croissant</option>
                  <option value="price-desc">Prix décroissant</option>
                  <option value="surface-desc">Surface décroissante</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Main Content -->
          <div class="search-results">
            <!-- Résultats de la recherche -->
            <div class="properties-grid">
              <?php if ($nb_resultats === 0): ?>
                <div class="no-results-content">
                  <i data-lucide="search-x"></i>
                  <h3>Aucune propriété trouvée</h3>
                  <p>Essayez de modifier vos critères de recherche pour voir plus de résultats.</p>
                </div>
              <?php else: ?>
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
                    </div>
                    <div class="property-content">
                      <div class="property-price">
                        <i data-lucide="dollar-sign"></i>
                        <?= number_format($annonce['prix'], 0, ',', ' ') ?> $
                      </div>
                      <h3 class="property-title"><?= htmlspecialchars($annonce['titre']) ?></h3>
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
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>

            <!-- Pagination -->
            <div class="pagination" id="pagination" style="display: none">
              <button class="btn btn-outline" id="prevPage" disabled>
                Précédent
              </button>
              <div class="pagination-numbers" id="paginationNumbers">
                <!-- Page numbers will be populated here -->
              </div>
              <button class="btn btn-outline" id="nextPage">Suivant</button>
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

  </body>
</html>
