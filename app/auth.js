// Authentication Pages Functionality
document.addEventListener("DOMContentLoaded", function () {
  initializeAuthPages();
});

function initializeAuthPages() {
  // Check which page we're on
  const isLoginPage = document.getElementById("loginForm");
  const isSignupPage = document.getElementById("signupForm");

  if (isLoginPage) {
    initializeLoginPage();
  }

  if (isSignupPage) {
    initializeSignupPage();
  }

  // Common auth functionality
  setupPasswordToggles();
  setupSocialLogin();

  console.log("🔐 Authentication pages initialized");
}

// Login Page
function initializeLoginPage() {
  const loginForm = document.getElementById("loginForm");

  if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
      e.preventDefault();
      handleLogin(new FormData(this));
    });
  }
}

function handleLogin(formData) {
  const email = formData.get("email");
  const password = formData.get("password");
  const remember = formData.get("remember");

  // Basic validation
  if (!email || !password) {
    showNotification("Veuillez remplir tous les champs", "error");
    return;
  }

  if (!isValidEmail(email)) {
    showNotification("Adresse email invalide", "error");
    return;
  }

  // Simulate login process
  showNotification("Connexion en cours...", "info");

  // Simulate API call
  setTimeout(() => {
    // Mock authentication logic
    const users = getStoredUsers();
    const user = users.find((u) => u.email === email);

    if (user && user.password === password) {
      // Store login session
      if (remember) {
        localStorage.setItem("rememberedEmail", email);
      }

      sessionStorage.setItem("currentUser", JSON.stringify(user));
      showNotification("Connexion réussie ! Redirection...", "success");

      // Redirect based on user type
      setTimeout(() => {
        if (user.accountType === "agent") {
          window.location.href = "dashboard.php";
        } else if (user.accountType === "admin") {
          window.location.href = "admin.php";
        } else {
          window.location.href = "accueil.php";
        }
      }, 1500);
    } else {
      showNotification("Email ou mot de passe incorrect", "error");
    }
  }, 1500);
}

// Signup Page
function initializeSignupPage() {
  const accountTypeSelection = document.getElementById("accountTypeSelection");
  const registrationForm = document.getElementById("registrationForm");
  const signupForm = document.getElementById("signupForm");
  const backBtn = document.getElementById("backBtn");

  // Account type selection
  const accountTypeCards = document.querySelectorAll(".account-type-card");
  accountTypeCards.forEach((card) => {
    card.addEventListener("click", function () {
      const accountType = this.getAttribute("data-type");
      showRegistrationForm(accountType);
    });
  });

  // Back button
  if (backBtn) {
    backBtn.addEventListener("click", function () {
      showAccountTypeSelection();
    });
  }

  // Signup form submission
  if (signupForm) {
    signupForm.addEventListener("submit", function (e) {
      // NE METS PAS e.preventDefault() ici !
      // NE FAIS PAS handleSignup ici !
      // Laisse le formulaire s'envoyer normalement
    });
  }

  // Load remembered email if exists
  loadRememberedEmail();
}

function showRegistrationForm(accountType) {
  const accountTypeSelection = document.getElementById("accountTypeSelection");
  const registrationForm = document.getElementById("registrationForm");
  const accountTypeInput = document.getElementById("accountType");
  const formTitle = document.getElementById("formTitle");
  const formSubtitle = document.getElementById("formSubtitle");
  const agentFields = document.getElementById("agentFields");
  const agentTerms = document.getElementById("agentTerms");
  const submitText = document.getElementById("submitText");

  // Hide account type selection and show form
  accountTypeSelection.classList.add("hidden");
  registrationForm.classList.remove("hidden");

  // Set account type
  accountTypeInput.value = accountType;

  // Update form text based on account type
  if (accountType === "agent") {
    formTitle.textContent = "Créer un compte agent immobilier";
    formSubtitle.textContent =
      "Rejoignez notre réseau d'agents immobiliers professionnels";
    submitText.textContent = "Créer mon compte agent";
    agentFields.classList.remove("hidden");
    agentTerms.classList.remove("hidden");

    // Make agent fields required
    const agentInputs = agentFields.querySelectorAll("input, select");
    agentInputs.forEach((input) => {
      if (input.name === "license") {
        input.required = true;
      }
    });
  } else {
    formTitle.textContent = "Créer un compte utilisateur";
    formSubtitle.textContent =
      "Rejoignez notre communauté et trouvez votre bien idéal";
    submitText.textContent = "Créer mon compte";
    agentFields.classList.add("hidden");
    agentTerms.classList.add("hidden");
  }
}

function showAccountTypeSelection() {
  const accountTypeSelection = document.getElementById("accountTypeSelection");
  const registrationForm = document.getElementById("registrationForm");

  accountTypeSelection.classList.remove("hidden");
  registrationForm.classList.add("hidden");

  // Reset form
  const signupForm = document.getElementById("signupForm");
  if (signupForm) {
    signupForm.reset();
  }
}

function handleSignup(formData) {
  const userData = {};
  for (let [key, value] of formData.entries()) {
    userData[key] = value;
  }

  // Validation
  const validation = validateSignupForm(userData);
  if (!validation.isValid) {
    showNotification(validation.errors[0], "error");
    return;
  }

  showNotification("Création du compte en cours...", "info");

  // Simulate API call
  setTimeout(() => {
    // Check if email already exists
    const users = getStoredUsers();
    const emailExists = users.find((u) => u.email === userData.email);

    if (emailExists) {
      showNotification("Cette adresse email est déjà utilisée", "error");
      return;
    }

    // Create new user
    const newUser = {
      id: Date.now(),
      ...userData,
      createdAt: new Date().toISOString(),
      status: userData.accountType === "agent" ? "pending" : "active",
    };

    // Store user
    users.push(newUser);
    localStorage.setItem("users", JSON.stringify(users));

    if (userData.accountType === "agent") {
      showNotification(
        "Compte créé ! Votre demande d'agent est en cours de validation.",
        "success",
      );
    } else {
      showNotification("Compte créé avec succès !", "success");
    }

    // Redirect to login
    setTimeout(() => {
      window.location.href = "connexion.php";
    }, 2000);
  }, 2000);
}

function validateSignupForm(userData) {
  const errors = [];

  // Required fields
  const requiredFields = [
    "firstName",
    "lastName",
    "email",
    "phone",
    "password",
    "confirmPassword",
  ];

  for (let field of requiredFields) {
    if (!userData[field] || !userData[field].trim()) {
      errors.push(`Le champ ${getFieldLabel(field)} est requis`);
    }
  }

  // Email validation
  if (userData.email && !isValidEmail(userData.email)) {
    errors.push("Adresse email invalide");
  }

  // Phone validation
  if (userData.phone && !isValidPhone(userData.phone)) {
    errors.push("Numéro de telephone invalide");
  }

  // Password validation
  if (userData.password && userData.password.length < 8) {
    errors.push("Le mot de passe doit contenir au moins 8 caractères");
  }

  // Password confirmation
  if (userData.password !== userData.confirmPassword) {
    errors.push("Les mots de passe ne correspondent pas");
  }

  // Terms acceptance
  if (!userData.terms) {
    errors.push("Vous devez accepter les conditions d'utilisation");
  }

  // Agent-specific validation
  if (userData.accountType === "agent") {
    if (!userData.license || !userData.license.trim()) {
      errors.push("Le numéro de licence professionnelle est requis");
    }
    if (!userData.agentTerms) {
      errors.push("Vous devez accepter les conditions d'agent immobilier");
    }
  }

  return {
    isValid: errors.length === 0,
    errors,
  };
}

function getFieldLabel(field) {
  const labels = {
    firstName: "prénom",
    lastName: "nom",
    email: "email",
    phone: "telephone",
    password: "mot de passe",
    confirmPassword: "confirmation du mot de passe",
  };
  return labels[field] || field;
}

// Password Toggle Functionality
function setupPasswordToggles() {
  const toggleButtons = document.querySelectorAll(".password-toggle");

  toggleButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const input = this.parentNode.querySelector("input");
      const icon = this.querySelector("i");

      if (input.type === "password") {
        input.type = "text";
        icon.setAttribute("data-lucide", "eye-off");
      } else {
        input.type = "password";
        icon.setAttribute("data-lucide", "eye");
      }

      // Re-initialize icons
      lucide.createIcons();
    });
  });
}

// Social Login
function setupSocialLogin() {
  const socialButtons = document.querySelectorAll(".social-btn");

  socialButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const provider = this.textContent.includes("Google")
        ? "Google"
        : "Facebook";
      showNotification(
        `Connexion avec ${provider} non disponible pour le moment`,
        "info",
      );
    });
  });
}

// Utility Functions
function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

function isValidPhone(phone) {
  // DRC phone number validation (+243 followed by 9 digits)
  const phoneRegex = /^\+243\s?\d{2}\s?\d{3}\s?\d{2}\s?\d{2}$/;
  return phoneRegex.test(phone.replace(/\s/g, ""));
}

function getStoredUsers() {
  const users = localStorage.getItem("users");
  return users ? JSON.parse(users) : [];
}

function loadRememberedEmail() {
  const rememberedEmail = localStorage.getItem("rememberedEmail");
  const emailInput = document.getElementById("email");

  if (rememberedEmail && emailInput) {
    emailInput.value = rememberedEmail;
  }
}

// Phone number formatting
document.addEventListener("DOMContentLoaded", function () {
  const phoneInputs = document.querySelectorAll('input[type="tel"]');

  phoneInputs.forEach((input) => {
    input.addEventListener("input", function () {
      let value = this.value.replace(/\D/g, "");

      // Add +243 if not present
      if (!value.startsWith("243") && value.length > 0) {
        if (value.startsWith("0")) {
          value = "243" + value.substring(1);
        } else {
          value = "243" + value;
        }
      }

      // Format: +243 XX XXX XX XX
      if (value.length >= 3) {
        value = value.replace(
          /^(\d{3})(\d{2})?(\d{3})?(\d{2})?(\d{2})?/,
          (match, p1, p2, p3, p4, p5) => {
            let formatted = "+" + p1;
            if (p2) formatted += " " + p2;
            if (p3) formatted += " " + p3;
            if (p4) formatted += " " + p4;
            if (p5) formatted += " " + p5;
            return formatted;
          },
        );
      }

      this.value = value;
    });

    // Set placeholder
    input.placeholder = "+243 XX XXX XX XX";
  });
});

// Export for debugging
window.authDebug = {
  getStoredUsers,
  handleLogin,
  handleSignup,
  validateSignupForm,
};
