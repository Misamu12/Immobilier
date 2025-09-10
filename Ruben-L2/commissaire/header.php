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
            <a href="annonce.php" class="nav-link">
              <i data-lucide="search"></i>
              <span>annonce</span>
            </a>
            <a href="demande.php" class="nav-link active">
              <i data-lucide="building-2"></i>
              <span>demande</span>
            </a>
            <a href="profil.php" class="nav-link">
              <i data-lucide="shield"></i>
              <span>profil</span>
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
            <a href="annonce.php" class="mobile-nav-link">
              <i data-lucide="search"></i>
              <span>annonce</span>
            </a>
            <a href="demande.php" class="mobile-nav-link active">
              <i data-lucide="building-2"></i>
              <span>demande</span>
            </a>
            <a href="profil.php" class="mobile-nav-link">
              <i data-lucide="shield"></i>
              <span>profil</span>
            </a>
          </div>
          <div class="mobile-auth-actions">
            <button class="btn btn-ghost">
              <i data-lucide="user"></i>
              deconnexion
            </button>
          </div>
        </div>
      </div>
    </nav>