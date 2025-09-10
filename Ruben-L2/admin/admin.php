<?php
require_once("../../config/config.php");

// Nombre total d'utilisateurs (hors commissionnaires)
$stmt = $connexion->query("SELECT COUNT(*) FROM utilisateur WHERE rôle = 'utilisateur'");
$total_utilisateurs = $stmt->fetchColumn();

// Nombre total d'agents immobiliers actifs (commissionnaires validés)
$stmt = $connexion->query("SELECT COUNT(*) FROM commissionnaire WHERE statut_validation = 1");
$total_agents = $stmt->fetchColumn();

// Nombre d'annonces en attente (à modérer, par exemple statut_validation = 0)
$stmt = $connexion->query("SELECT COUNT(*) FROM annonce a JOIN commissionnaire c ON a.id_commissionnaire = c.id_commissionnaire WHERE c.statut_validation = 0");
$annonces_attente = $stmt->fetchColumn();

// Nombre de signalements (exemple : toutes les annonces signalées, à adapter selon ta structure)
$stmt = $connexion->query("SELECT COUNT(*) FROM annonce WHERE description LIKE '%signalé%'");
$signalements = $stmt->fetchColumn();

// Agents en attente de validation
$stmt = $connexion->query("
    SELECT c.*, u.nom, u.email
    FROM commissionnaire c
    JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
    WHERE c.statut_validation = 0
    ORDER BY c.id_commissionnaire DESC
    LIMIT 5
");
$agents_attente = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Annonces à modérer (agents non validés)
$stmt = $connexion->query("
    SELECT a.*, u.nom AS nom_agent, c.statut_validation
    FROM annonce a
    JOIN commissionnaire c ON a.id_commissionnaire = c.id_commissionnaire
    JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
    WHERE c.statut_validation = 0
    ORDER BY a.date_publication DESC
    LIMIT 5
");
$annonces_moderation = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Administration - ImmobilierKin</title>
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
            <h1>Administration</h1>
            <p>Gestion et supervision de la plateforme ImmobilierKin</p>
          </div>
          
        </div>

        <!-- Dashboard Stats -->
        <div class="dashboard-stats">
          <div class="stat-card">
            <div class="stat-icon blue">
              <i data-lucide="users"></i>
            </div>
            <div class="stat-content">
              <div class="stat-value"><?= $total_utilisateurs ?></div>
              <div class="stat-label">Utilisateurs totaux</div>
              <div class="stat-change">+127 ce mois</div>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon green">
              <i data-lucide="shield"></i>
            </div>
            <div class="stat-content">
              <div class="stat-value"><?= $total_agents ?></div>
              <div class="stat-label">Agents immobiliers actifs</div>
              <div class="stat-change">+12 ce mois</div>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon orange">
              <i data-lucide="alert-triangle"></i>
            </div>
            <div class="stat-content">
              <div class="stat-value"><?= $annonces_attente ?></div>
              <div class="stat-label">Annonces en attente</div>
              <div class="stat-change">À modérer</div>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon red">
              <i data-lucide="x-circle"></i>
            </div>
            <div class="stat-content">
              <div class="stat-value"><?= $signalements ?></div>
              <div class="stat-label">Signalements</div>
              <div class="stat-change">À traiter</div>
            </div>
          </div>
        </div>

        <!-- Dashboard Content -->
        <div class="dashboard-content">
          <div class="dashboard-grid">
            <!-- Pending Agents -->
            <div class="dashboard-card">
              <div class="card-header">
                <h3>
                  <i data-lucide="shield"></i>
                  Agents immobiliers en attente
                </h3>
              </div>
              <div class="card-content">
                <div class="pending-list">
                  <?php foreach ($agents_attente as $agent): ?>
                    <div class="pending-item">
                      <div class="pending-info">
                        <h4><?= htmlspecialchars($agent['nom']) ?></h4>
                        <p><?= htmlspecialchars($agent['email']) ?></p>
                        <p><?= htmlspecialchars($agent['telephone']) ?></p>
                        <span class="pending-date">Demande du <?= date('d/m/Y', strtotime($agent['date_inscription'] ?? '')) ?></span>
                      </div>
                      <div class="pending-actions">
                        <button class="btn btn-outline btn-sm">
                          <i data-lucide="eye"></i>
                          
                        </button>
                        <div class="action-buttons">
                          <form method="post" action="valider_agent.php" style="display:inline;">
  <input type="hidden" name="id_commissionnaire" value="<?= $agent['id_commissionnaire'] ?>">
  <button type="submit" class="btn btn-success btn-sm" title="Valider cet agent">
    <i data-lucide="check-circle"></i>
  </button>
</form>
                          <button class="btn btn-danger btn-sm">
                            <i data-lucide="x-circle"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
                <button class="btn btn-outline full-width">
                  Voir toutes les demandes
                </button>
              </div>
            </div>

            <!-- Pending Properties -->
            <div class="dashboard-card">
              <div class="card-header">
                <h3>
                  <i data-lucide="building-2"></i>
                  Annonces à modérer
                </h3>
              </div>
              <div class="card-content">
                <div class="pending-list">
                  <?php foreach ($annonces_moderation as $annonce): ?>
                    <div class="pending-item">
                      <div class="pending-info">
                        <h4><?= htmlspecialchars($annonce['titre']) ?></h4>
                        <p>Par <?= htmlspecialchars($annonce['nom_agent']) ?></p>
                        <p><?= htmlspecialchars($annonce['lieu']) ?></p>
                        <div class="property-badge">
                          <span class="badge badge-success"><?= number_format($annonce['prix'], 0, ',', ' ') ?> $</span>
                          <span class="pending-date">Soumis le <?= date('d/m/Y', strtotime($annonce['date_publication'])) ?></span>
                        </div>
                      </div>
                      <div class="pending-actions">
                        <button class="btn btn-outline btn-sm">
                          <i data-lucide="eye"></i>
                          
                        </button>
                        <div class="action-buttons">
                          <button class="btn btn-success btn-sm">
                            <i data-lucide="check-circle"></i>
                          </button>
                          <button class="btn btn-danger btn-sm">
                            <i data-lucide="x-circle"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
                <button class="btn btn-outline full-width">
                  Voir toutes les annonces
                </button>
              </div>
            </div>
<script>
  lucide.createIcons();
</script>
          
  </body>
</html>
