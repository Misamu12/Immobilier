<?php
require_once("../../config/config.php");

if (!isset($_SESSION["admin"])) {
    header("Location: ../connexion.php");
    exit;
}

$id_commissionnaire = intval($_POST['id_commissionnaire'] ?? 0);

if ($id_commissionnaire > 0) {
    $stmt = $connexion->prepare("UPDATE commissionnaire SET statut_validation = 1 WHERE id_commissionnaire = ?");
    $stmt->execute([$id_commissionnaire]);
}

header("Location: admin.php");
exit;