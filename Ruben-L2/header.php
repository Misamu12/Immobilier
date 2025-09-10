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
            <a href="accueil.php" class="nav-link active">
              <i data-lucide="home"></i>
              <span>Accueil</span>
            </a>
            <a href="rechercher.php" class="nav-link">
              <i data-lucide="search"></i>
              <span>Rechercher</span>
            </a>
            <a href="demande.php" class="nav-link">
              <i data-lucide="building-2"></i>
              <span>demande logement</span>
            </a>
            <a href="admin/admin.php" class="nav-link">
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
            <a href="accueil.php" class="mobile-nav-link active">
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
          <?php var_dump($_SESSION); ?>
          <div class="mobile-auth-actions">
            <?php if (isset($_SESSION['user_id'])): ?>
            <a href="connexion.php" class="btn btn-ghost">
              <i data-lucide="user"></i>
              Connexion
            </a>
            <a href="inscription.php" class="btn btn-primary">S'inscrire</a>
            <?php else: ?>
            <a href="logout.php" class="btn btn-ghost">
              <i data-lucide="log-out"></i>
              Déconnexion
            </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </nav>