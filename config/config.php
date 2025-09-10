<?php

session_start();
$servername = "localhost";
$username = "root";
$password = "";
try{
    $connexion = new PDO("mysql:host=$servername;dbname=immobiliers", $username, $password);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo " ERREUR " . $e->getMessage();
}
?>
