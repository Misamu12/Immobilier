<?php
session_start();
require_once("../../config/config.php");

// Vérifier si l'utilisateur est connecté et est un commissionnaire
if (!isset($_SESSION["user_id"]) || ($_SESSION["user_role"] ?? '') !== "commissionnaire") {
    header("Location: ../connexion.php");
    exit;
}

$id_demande = intval($_POST['id_demande'] ?? 0);
$action = $_POST['action'] ?? '';

if ($id_demande && in_array($action, ['accepter', 'refuser'])) {
    // Vérifier que la demande appartient bien à ce commissionnaire
    $stmt = $connexion->prepare("
        SELECT d.id_demande
        FROM demande_logement d
        JOIN commissionnaire c ON d.id_commissionnaire = c.id_commissionnaire
        WHERE d.id_demande = ? AND c.id_utilisateur = ?
    ");
    $stmt->execute([$id_demande, $_SESSION["user_id"]]);
    if ($stmt->fetch()) {
        $etat = $action === 'accepter' ? 'acceptée' : 'refusée';
        $stmt = $connexion->prepare("UPDATE demande_logement SET etat = ? WHERE id_demande = ?");
        $stmt->execute([$etat, $id_demande]);
    }
}

header("Location: demande.php");
exit;