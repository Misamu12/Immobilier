<?php
require_once("../config/config.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    // Vérifier admin d'abord
    $stmt = $connexion->prepare("SELECT id_admin, nom, email, mot_de_passe FROM admin WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($admin && password_verify($password, $admin["mot_de_passe"])) {
        $_SESSION["admin"] = $admin["id_admin"];
        header("Location:admin/admin.php");
        exit;
    }

    // Vérifier utilisateur/commissionnaire
    $stmt = $connexion->prepare("SELECT id_utilisateur, nom, email, mot_de_passe, rôle FROM utilisateur WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user["mot_de_passe"])) {
        $_SESSION["user_id"] = $user["id_utilisateur"];
        $_SESSION["user_role"] = $user["rôle"];
        $_SESSION["user_name"] = $user["nom"];
        if ($user["rôle"] === "commissionnaire") {
            header("Location:commissaire/dashboard.php");
        } else {
            header("Location: accueil.php");
        }
        exit;
    }

    $message = "Identifiants incorrects.";
}
?>

<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Connexion - ImmobilierKin</title>
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
      <div class="auth-container">
        <div class="auth-wrapper">
          <!-- Left side - Hero -->
          <div class="auth-hero">
            <div class="auth-hero-content">
              <div class="auth-hero-logo">
                <div class="logo-icon">
                  <i data-lucide="building-2"></i>
                </div>
                <span class="logo-text">ImmobilierKin</span>
              </div>
              <h1>Connectez-vous à votre compte</h1>
              <p>
                Accédez à votre espace personnel pour gérer vos propriétés,
                suivre vos demandes et découvrir les meilleures offres
                immobilières à Kinshasa.
              </p>
              <div class="auth-features">
                <div class="auth-feature">
                  <i data-lucide="shield-check"></i>
                  <span>Sécurisé et fiable</span>
                </div>
                <div class="auth-feature">
                  <i data-lucide="users"></i>
                  <span>+2,500 utilisateurs</span>
                </div>
                <div class="auth-feature">
                  <i data-lucide="star"></i>
                  <span>4.8/5 étoiles</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Right side - Login Form -->
          <div class="auth-form-container">
            <div class="auth-form-wrapper">
              <div class="auth-form-header">
                <h2>Connexion</h2>
                <p>Entrez vos identifiants pour accéder à votre compte</p>
              </div>

              <form class="auth-form" id="loginForm" method="POST" action="">
                <div class="form-group">
                  <label for="email">Adresse email</label>
                  <div class="input-with-icon">
                    <i data-lucide="mail"></i>
                    <input
                      type="email"
                      id="email"
                      name="email"
                      placeholder="votre@email.com"
                      required
                    />
                  </div>
                </div>

                <div class="form-group">
                  <label for="password">Mot de passe</label>
                  <div class="input-with-icon">
                    <i data-lucide="lock"></i>
                    <input
                      type="password"
                      id="password"
                      name="password"
                      placeholder="••••••••"
                      required
                    />
                    <button
                      type="button"
                      class="password-toggle"
                      id="togglePassword"
                    >
                      <i data-lucide="eye"></i>
                    </button>
                  </div>
                </div>

                <div class="form-options">
                  <label class="checkbox-label">
                    <input type="checkbox" name="remember" />
                    <span class="checkmark"></span>
                    Se souvenir de moi
                  </label>
                  <a href="#" class="forgot-password">Mot de passe oublié ?</a>
                </div>

                <button type="submit" class="btn btn-primary btn-full">
                  <i data-lucide="log-in"></i>
                  Se connecter
                </button>

                <div class="auth-divider">
                  <span>ou</span>
                </div>

          
                <div class="auth-footer">
                  <p>
                    Vous n'avez pas de compte ?
                    <a href="inscription.php">Créer un compte</a>
                  </p>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </main>

  </body>
</html>
