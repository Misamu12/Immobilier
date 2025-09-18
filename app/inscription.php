<?php
require_once("../config/config.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupération et sécurisation des champs
    $prenom = htmlspecialchars(trim($_POST["firstName"] ?? ""));
    $nom = htmlspecialchars(trim($_POST["lastName"] ?? ""));
    $email = htmlspecialchars(trim($_POST["email"] ?? ""));
    $telephone = htmlspecialchars(trim($_POST["phone"] ?? ""));
    $mot_de_passe = $_POST["password"] ?? "";
    $confirm_mot_de_passe = $_POST["confirmPassword"] ?? "";
    $type_compte = $_POST["accountType"] ?? "user";
    $role = ($type_compte === "agent") ? "commissionnaire" : "utilisateur";
    $date_inscription = date("Y-m-d");

    // Vérification des champs obligatoires
    if (!$prenom || !$nom || !$email || !$mot_de_passe || !$confirm_mot_de_passe || !$telephone) {
        $message = "Veuillez remplir tous les champs obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Adresse email invalide.";
    } elseif ($mot_de_passe !== $confirm_mot_de_passe) {
        $message = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier si l'email existe déjà
        $stmt = $connexion->prepare("SELECT id_utilisateur FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $message = "Cet email est déjà utilisé.";
        } else {
            // Hash du mot de passe
            $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

            // Insertion dans la table utilisateur
            $stmt = $connexion->prepare("INSERT INTO utilisateur (nom, email, mot_de_passe, rôle, date_inscription) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$prenom . " " . $nom, $email, $hash, $role, $date_inscription]);
            $id_utilisateur = $connexion->lastInsertId();

            // Si agent, insérer dans commissionnaire
            if ($role === "commissionnaire") {
                $numero_agrement = htmlspecialchars(trim($_POST["license"] ?? ""));
                $adresse = htmlspecialchars(trim($_POST["company"] ?? ""));
                $stmt = $connexion->prepare("INSERT INTO commissionnaire (id_utilisateur, numéro_agrement, telephone, adresse) VALUES (?, ?, ?, ?)");
                $stmt->execute([$id_utilisateur, $numero_agrement, $telephone, $adresse]);
            }

            $message = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        }
    }
}
?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inscription - ImmobilierKin</title>
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
              <h1>Rejoignez ImmobilierKin</h1>
              <p>
                Créez votre compte et accédez aux meilleures opportunités
                immobilières de Kinshasa. Que vous soyez acheteur ou agent
                immobilier, nous avons la solution qu'il vous faut.
              </p>
              <div class="auth-features">
                <div class="auth-feature">
                  <i data-lucide="users"></i>
                  <span>Communauté active</span>
                </div>
                <div class="auth-feature">
                  <i data-lucide="building-2"></i>
                  <span>+2,500 propriétés</span>
                </div>
                <div class="auth-feature">
                  <i data-lucide="shield-check"></i>
                  <span>100% sécurisé</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Right side - Registration Form -->
          <div class="auth-form-container">
            <div class="auth-form-wrapper">
              <?php if (!empty($message)): ?>
                <div class="alert-message"><?= $message ?></div>
              <?php endif; ?>
              <!-- Account Type Selection -->
              <div class="account-type-selection" id="accountTypeSelection">
                <div class="auth-form-header">
                  <h2>Choisissez votre type de compte</h2>
                  <p>
                    Sélectionnez le type de compte qui correspond à vos besoins
                  </p>
                </div>

                <div class="account-types">
                  <button
                    type="button"
                    class="account-type-card"
                    data-type="user"
                  >
                    <div class="account-type-icon">
                      <i data-lucide="user"></i>
                    </div>
                    <h3>Utilisateur</h3>
                    <p>
                      Recherchez et achetez des propriétés, contactez des agents
                      immobiliers
                    </p>
                    <ul>
                      <li>
                        <i data-lucide="check"></i>
                        Recherche avancée
                      </li>
                      <li>
                        <i data-lucide="check"></i>
                        Favoris et alertes
                      </li>
                      <li>
                        <i data-lucide="check"></i>
                        Contact direct agents
                      </li>
                    </ul>
                  </button>

                  <button
                    type="button"
                    class="account-type-card"
                    data-type="agent"
                  >
                    <div class="account-type-icon">
                      <i data-lucide="briefcase"></i>
                    </div>
                    <h3>Agent Immobilier</h3>
                    <p>
                      Publiez vos annonces, gérez vos clients et développez
                      votre activité
                    </p>
                    <ul>
                      <li>
                        <i data-lucide="check"></i>
                        Dashboard professionnel
                      </li>
                      <li>
                        <i data-lucide="check"></i>
                        Gestion des annonces
                      </li>
                      <li>
                        <i data-lucide="check"></i>
                        Outils marketing
                      </li>
                    </ul>
                  </button>
                </div>
              </div>

              <!-- Registration Form -->
              <div class="registration-form hidden" id="registrationForm">
                <div class="auth-form-header">
                  <button type="button" class="back-btn" id="backBtn">
                    <i data-lucide="arrow-left"></i>
                  </button>
                  <div>
                    <h2 id="formTitle">Créer un compte utilisateur</h2>
                    <p id="formSubtitle">
                      Remplissez les informations ci-dessous pour créer votre
                      compte
                    </p>
                  </div>
                </div>

                <form class="auth-form" id="signupForm" method="post">
                  <input type="hidden" id="accountType" name="accountType" />

                  <div class="form-row">
                    <div class="form-group">
                      <label for="firstName">Prénom *</label>
                      <div class="input-with-icon">
                        <i data-lucide="user"></i>
                        <input
                          type="text"
                          id="firstName"
                          name="firstName"
                          placeholder="Votre prénom"
                          required
                        />
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="lastName">Nom *</label>
                      <div class="input-with-icon">
                        <i data-lucide="user"></i>
                        <input
                          type="text"
                          id="lastName"
                          name="lastName"
                          placeholder="Votre nom"
                          required
                        />
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="email">Adresse email *</label>
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
                    <label for="phone">Numéro de telephone *</label>
                    <div class="input-with-icon">
                      <i data-lucide="phone"></i>
                      <input
                        type="tel"
                        id="phone"
                        name="phone"
                        placeholder="+243 XX XXX XX XX"
                        required
                      />
                    </div>
                  </div>

                  <!-- Agent-specific fields -->
                  <div class="agent-fields hidden" id="agentFields">
                    <div class="form-group">
                      <label for="company">Agence/Entreprise</label>
                      <div class="input-with-icon">
                        <i data-lucide="building"></i>
                        <input
                          type="text"
                          id="company"
                          name="company"
                          placeholder="Nom de votre agence"
                        />
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="license"
                        >Numéro de licence professionnelle</label
                      >
                      <div class="input-with-icon">
                        <i data-lucide="file-text"></i>
                        <input
                          type="text"
                          id="license"
                          name="license"
                          placeholder="Numéro de licence"
                        />
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="experience">Années d'expérience</label>
                      <select id="experience" name="experience">
                        <option value="">Sélectionnez...</option>
                        <option value="0-1">Moins d'1 an</option>
                        <option value="1-3">1 à 3 ans</option>
                        <option value="3-5">3 à 5 ans</option>
                        <option value="5-10">5 à 10 ans</option>
                        <option value="10+">Plus de 10 ans</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group">
                      <label for="password">Mot de passe *</label>
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

                    <div class="form-group">
                      <label for="confirmPassword"
                        >Confirmer le mot de passe *</label
                      >
                      <div class="input-with-icon">
                        <i data-lucide="lock"></i>
                        <input
                          type="password"
                          id="confirmPassword"
                          name="confirmPassword"
                          placeholder="••••••••"
                          required
                        />
                        <button
                          type="button"
                          class="password-toggle"
                          id="toggleConfirmPassword"
                        >
                          <i data-lucide="eye"></i>
                        </button>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="checkbox-label">
                      <input type="checkbox" name="terms" required />
                      <span class="checkmark"></span>
                      J'accepte les
                      <a href="#" class="link">conditions d'utilisation</a> et
                      la
                      <a href="#" class="link">politique de confidentialité</a>
                    </label>
                  </div>

                  <div class="form-group agent-terms hidden" id="agentTerms">
                    <label class="checkbox-label">
                      <input type="checkbox" name="agentTerms" />
                      <span class="checkmark"></span>
                      En tant qu'agent immobilier, je certifie que mes
                      informations professionnelles sont exactes et que je
                      possède les qualifications nécessaires
                    </label>
                  </div>

                  <button type="submit" class="btn btn-primary btn-full">
                    <i data-lucide="user-plus"></i>
                    <span id="submitText">Créer mon compte</span>
                  </button>

                  <div class="auth-divider">
                    <span>ou</span>
                  </div>

                  <div class="auth-footer">
                    <p>
                      Vous avez déjà un compte ?
                      <a href="connexion.php">Se connecter</a>
                    </p>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
    <script>
// Affichage dynamique des champs agent
document.addEventListener("DOMContentLoaded", function () {
  // Sélection des boutons de type de compte
  const userBtn = document.querySelector('.account-type-card[data-type="user"]');
  const agentBtn = document.querySelector('.account-type-card[data-type="agent"]');
  const accountTypeInput = document.getElementById("accountType");
  const registrationForm = document.getElementById("registrationForm");
  const accountTypeSelection = document.getElementById("accountTypeSelection");
  const agentFields = document.getElementById("agentFields");
  const agentTerms = document.getElementById("agentTerms");
  const formTitle = document.getElementById("formTitle");
  const formSubtitle = document.getElementById("formSubtitle");
  const backBtn = document.getElementById("backBtn");

  // Affiche le formulaire selon le type choisi
  function showForm(type) {
    accountTypeInput.value = type;
    accountTypeSelection.classList.add("hidden");
    registrationForm.classList.remove("hidden");
    if (type === "agent") {
      agentFields.classList.remove("hidden");
      agentTerms.classList.remove("hidden");
      formTitle.textContent = "Créer un compte agent immobilier";
      formSubtitle.textContent = "Rejoignez notre réseau d'agents immobiliers professionnels";
    } else {
      agentFields.classList.add("hidden");
      agentTerms.classList.add("hidden");
      formTitle.textContent = "Créer un compte utilisateur";
      formSubtitle.textContent = "Rejoignez notre communauté et trouvez votre bien idéal";
    }
  }

  if (userBtn) userBtn.onclick = () => showForm("user");
  if (agentBtn) agentBtn.onclick = () => showForm("agent");
  if (backBtn) backBtn.onclick = () => {
    registrationForm.classList.add("hidden");
    accountTypeSelection.classList.remove("hidden");
  };

  // Validation du formulaire à la soumission
  const signupForm = document.getElementById("signupForm");
  if (signupForm) {
    signupForm.addEventListener("submit", function (e) {
      let errors = [];
      // Champs communs
      if (!signupForm.firstName.value.trim()) errors.push("Le prénom est requis.");
      if (!signupForm.lastName.value.trim()) errors.push("Le nom est requis.");
      if (!signupForm.email.value.trim()) errors.push("L'email est requis.");
      if (!signupForm.phone.value.trim()) errors.push("Le telephone est requis.");
      if (!signupForm.password.value) errors.push("Le mot de passe est requis.");
      if (signupForm.password.value !== signupForm.confirmPassword.value) errors.push("Les mots de passe ne correspondent pas.");
      if (!signupForm.terms.checked) errors.push("Vous devez accepter les conditions d'utilisation.");

      // Champs agent
      if (accountTypeInput.value === "agent") {
        if (!signupForm.company.value.trim()) errors.push("Le nom de l'agence est requis.");
        if (!signupForm.license.value.trim()) errors.push("Le numéro de licence est requis.");
        if (!signupForm.agentTerms.checked) errors.push("Vous devez certifier vos informations professionnelles.");
      }

      // Affichage des erreurs
      let alertBox = document.querySelector(".alert-message");
      if (!alertBox) {
        alertBox = document.createElement("div");
        alertBox.className = "alert-message";
        signupForm.parentNode.insertBefore(alertBox, signupForm);
      }
      if (errors.length > 0) {
        e.preventDefault();
        alertBox.innerHTML = errors.map(e => `<div>${e}</div>`).join("");
        alertBox.style.color = "#ef4444";
        window.scrollTo({top: alertBox.offsetTop - 40, behavior: "smooth"});
      } else {
        alertBox.innerHTML = "";
      }
    });
  }
});
</script>
  </body>
</html>
