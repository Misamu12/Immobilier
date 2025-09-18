-- 1. UTILISATEUR
CREATE TABLE utilisateur (
    id_utilisateur INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    rôle ENUM('utilisateur', 'commissionnaire') NOT NULL,
    date_inscription DATE NOT NULL
);

-- 2. COMMISSIONNAIRE
CREATE TABLE commissionnaire (
    id_commissionnaire INT PRIMARY KEY AUTO_INCREMENT,
    id_utilisateur INT UNIQUE NOT NULL,
    numéro_agrement VARCHAR(100) NOT NULL,
    statut_validation BOOLEAN DEFAULT FALSE,
    telephone VARCHAR(20),
    adresse TEXT,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur)
);

-- 3. ANNONCE
CREATE TABLE annonce (
    id_annonce INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    lieu VARCHAR(150),
    type_bien ENUM('maison', 'parcelle', 'hôtel') NOT NULL,
    prix DECIMAL(12,2) NOT NULL,
    date_publication DATE NOT NULL,
    id_commissionnaire INT NOT NULL,
    FOREIGN KEY (id_commissionnaire) REFERENCES commissionnaire(id_commissionnaire)
);

-- 4. PHOTO_ANNONCE
CREATE TABLE photo_annonce (
    id_photo INT PRIMARY KEY AUTO_INCREMENT,
    url_photo TEXT NOT NULL,
    id_annonce INT NOT NULL,
    FOREIGN KEY (id_annonce) REFERENCES annonce(id_annonce)
);

-- 5. DEMANDE_LOGEMENT
CREATE TABLE demande_logement (
    id_demande INT PRIMARY KEY AUTO_INCREMENT,
    message TEXT,
    date_demande DATE NOT NULL,
    etat ENUM('en attente', 'acceptée', 'refusée') DEFAULT 'en attente',
    reponse_commissionnaire TEXT,
    id_utilisateur INT NOT NULL,
    id_commissionnaire INT NOT NULL,
    id_annonce INT,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur),
    FOREIGN KEY (id_commissionnaire) REFERENCES commissionnaire(id_commissionnaire),
    FOREIGN KEY (id_annonce) REFERENCES annonce(id_annonce)
);

-- 6. MESSAGE
CREATE TABLE message (
    id_message INT PRIMARY KEY AUTO_INCREMENT,
    contenu TEXT NOT NULL,
    date_envoi DATETIME NOT NULL,
    id_utilisateur INT NOT NULL,
    id_annonce INT NOT NULL,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur),
    FOREIGN KEY (id_annonce) REFERENCES annonce(id_annonce)
);

-- 7. ADMIN
CREATE TABLE admin (
    id_admin INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL
);
