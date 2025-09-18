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
            <a href="../accueil.php" class="nav-link">
              <i data-lucide="home"></i>
              <span>Accueil</span>
            </a>
            <a href="utilisateur.php" class="nav-link">
              <i data-lucide="search"></i>
              <span>utilisateur</span>
            </a>
            <a href="commissaire.php" class="nav-link">
              <i data-lucide="building-2"></i>
              <span>commissaire</span>
            </a>
            <a href="#" class="nav-link active">
              <i data-lucide="shield"></i>
              <span>annonce</span>
            </a>
          </div>

          <div class="nav-actions desktop-only">
            <a href="logout.php" class="nav-link active">
              <i data-lucide="log-out"></i>
              <span>deconnexion</span>
            </a>
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
            <a href="utilisateur.php" class="mobile-nav-link">
              <i data-lucide="search"></i>
              <span>utilisateur</span>
            </a>
            <a href="dashboard.php" class="mobile-nav-link">
              <i data-lucide="building-2"></i>
              <span>Dashboard</span>
            </a>
            <a href="admin.php" class="mobile-nav-link active">
              <i data-lucide="shield"></i>
              <span>Admin</span>
            </a>
          </div>
          <div class="mobile-auth-actions">
            <button class="btn btn-ghost">
              <i data-lucide="user"></i>
              Connexion
            </button>
            <button class="btn btn-primary">S'inscrire</button>
          </div>
        </div>
      </div>
    </nav>
