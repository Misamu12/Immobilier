<?php
require_once("../../config/config.php");

// Vérifier si l'utilisateur est connecté et est un commissionnaire
if (!isset($_SESSION["user_id"]) || ($_SESSION["user_role"] ?? '') !== "commissionnaire") {
    header("Location: connexion.php");
    exit;
}

// Récupérer les infos de l'agent connecté
$stmt = $connexion->prepare("SELECT nom, email FROM utilisateur WHERE id_utilisateur = ?");
$stmt->execute([$_SESSION["user_id"]]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupérer l'id du commissionnaire lié à cet utilisateur
$stmt = $connexion->prepare("SELECT id_commissionnaire FROM commissionnaire WHERE id_utilisateur = ?");
$stmt->execute([$_SESSION["user_id"]]);
$commissionnaire = $stmt->fetch(PDO::FETCH_ASSOC);
$id_commissionnaire = $commissionnaire ? $commissionnaire['id_commissionnaire'] : 0;

// Nombre d'annonces actives
$stmt = $connexion->prepare("SELECT COUNT(*) FROM annonce WHERE id_commissionnaire = ?");
$stmt->execute([$id_commissionnaire]);
$nb_annonces = $stmt->fetchColumn();

// Nombre total de vues (exemple: à adapter si tu as une table de vues)
$nb_vues = 0; // Par défaut
// Si tu as une colonne "vues" dans annonce, décommente ci-dessous
// $stmt = $connexion->prepare("SELECT SUM(vues) FROM annonce WHERE id_commissionnaire = ?");
// $stmt->execute([$id_commissionnaire]);
// $nb_vues = $stmt->fetchColumn();

// Nombre de demandes reçues
$stmt = $connexion->prepare("SELECT COUNT(*) FROM demande_logement WHERE id_commissionnaire = ?");
$stmt->execute([$id_commissionnaire]);
$nb_demandes = $stmt->fetchColumn();

// Taux de conversion (exemple simple : demandes acceptées / demandes totales)
$stmt = $connexion->prepare("SELECT COUNT(*) FROM demande_logement WHERE id_commissionnaire = ? AND etat = 'acceptée'");
$stmt->execute([$id_commissionnaire]);
$nb_acceptes = $stmt->fetchColumn();
$taux_conversion = $nb_demandes > 0 ? round(($nb_acceptes / $nb_demandes) * 100, 1) : 0;

// Annonces récentes (3 dernières)
$stmt = $connexion->prepare("SELECT * FROM annonce WHERE id_commissionnaire = ? ORDER BY date_publication DESC LIMIT 3");
$stmt->execute([$id_commissionnaire]);
$annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Demandes récentes (3 dernières)
$stmt = $connexion->prepare("
    SELECT d.*, u.nom AS nom_demandeur, a.titre AS titre_annonce
    FROM demande_logement d
    LEFT JOIN utilisateur u ON d.id_utilisateur = u.id_utilisateur
    LEFT JOIN annonce a ON d.id_annonce = a.id_annonce
    WHERE d.id_commissionnaire = ?
    ORDER BY d.date_demande DESC
    LIMIT 3
");
$stmt->execute([$id_commissionnaire]);
$demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Agent - ImmobilierKin</title>
    <link rel="stylesheet" href="../css/styles.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
      rel="stylesheet"
    />
    <script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  </head>
  <body>
    <!-- Navigation -->
    <?php include "header.php"; ?>

    <!-- Main Content -->
    <main>
      <div class="dashboard-container">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
          <div class="dashboard-title">
            <h1>Dashboard Agent Immobilier</h1>
            <p>Bienvenue dans votre espace de gestion immobilière à Kinshasa</p>
          </div>
        </div>

        <!-- Dashboard Stats -->
        <div class="dashboard-stats">
          <div class="stat-card">
            <div class="stat-icon blue">
              <i data-lucide="building-2"></i>
            </div>
            <div class="stat-content">
              <div class="stat-value"><?= $nb_annonces ?></div>
              <div class="stat-label">Annonces actives</div>
              <!-- Optionnel : évolution -->
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon green">
              <i data-lucide="eye"></i>
            </div>
            <div class="stat-content">
              <div class="stat-value"><?= $nb_vues ?></div>
              <div class="stat-label">Vues totales</div>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon purple">
              <i data-lucide="message-square"></i>
            </div>
            <div class="stat-content">
              <div class="stat-value"><?= $nb_demandes ?></div>
              <div class="stat-label">Demandes reçues</div>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon orange">
              <i data-lucide="trending-up"></i>
            </div>
            <div class="stat-content">
              <div class="stat-value"><?= $taux_conversion ?>%</div>
              <div class="stat-label">Taux de conversion</div>
            </div>
          </div>
        </div>

        <!-- Dashboard Content -->
        <div class="dashboard-content">
          <div class="dashboard-grid">
            <!-- Recent Properties -->
            <div class="dashboard-card">
              <div class="card-header">
                <h3>
                  <i data-lucide="building-2"></i>
                  Mes annonces récentes
                </h3>
              </div>
              <div class="card-content">
                <div class="recent-properties">
                  <?php foreach ($annonces as $annonce): ?>
                  <div class="property-item">
                    <div class="property-thumbnail"></div>
                    <div class="property-info">
                      <h4><?= htmlspecialchars($annonce['titre']) ?></h4>
                      <p><?= number_format($annonce['prix'], 0, ',', ' ') ?> $ • <?= htmlspecialchars($annonce['type_bien']) ?></p>
                      <span class="views"><?= htmlspecialchars($annonce['lieu']) ?></span>
                    </div>
                    <button class="btn btn-ghost">Voir</button>
                  </div>
                  <?php endforeach; ?>
                </div>
                <button class="btn btn-outline full-width">
                  Voir toutes mes annonces
                </button>
              </div>
            </div>

            <!-- Recent Requests -->
            <div class="dashboard-card">
              <div class="card-header">
                <h3>
                  <i data-lucide="message-square"></i>
                  Demandes récentes
                </h3>
              </div>
              <div class="card-content">
                <div class="requests-list">
                  <?php foreach ($demandes as $demande): ?>
                  <div class="request-item">
                    <div class="request-info">
                      <h4><?= htmlspecialchars($demande['titre_annonce']) ?></h4>
                      <p>Demande de visite</p>
                      <span class="request-time"
                        >Par <?= htmlspecialchars($demande['nom_demandeur']) ?> • il y a <?= time_ago($demande['date_demande']) ?></span
                      >
                    </div>
                    <div class="request-actions">
                      <button class="btn btn-outline btn-sm">Refuser</button>
                      <button class="btn btn-primary btn-sm">Accepter</button>
                    </div>
                  </div>
                  <?php endforeach; ?>
                </div>
                <button class="btn btn-outline full-width">
                  Voir toutes les demandes
                </button>
              </div>
            </div>

            <!-- Views Chart -->
           
            <!-- Quick Actions -->
            <div class="dashboard-card">
              <div class="card-header">
                <h3>Actions rapides</h3>
              </div>
              <div class="card-content">
                <div class="quick-actions">
                  <button class="quick-action-btn">
                    <i data-lucide="plus"></i>
                    <span>Ajouter une annonce</span>
                  </button>
                  <button class="quick-action-btn">
                    <i data-lucide="users"></i>
                    <span>Gérer les clients</span>
                  </button>
                  <button class="quick-action-btn">
                    <i data-lucide="message-square"></i>
                    <span>Messages</span>
                  </button>
                  <button class="quick-action-btn">
                    <i data-lucide="bar-chart-3"></i>
                    <span>Rapports</span>
                  </button>
                </div>
              </div>
            </div>

          
           
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

  </body>
</html>
