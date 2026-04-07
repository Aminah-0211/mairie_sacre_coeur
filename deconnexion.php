<?php
session_start(); // Démarre la session pour pouvoir la fermer
session_unset(); // Vide toutes les variables de session
session_destroy(); // Détruit la session sur le serveur

// ICI : Vérifie bien le nom de ta page d'accueil !
// Si ton fichier s'appelle 'accueil.php', change 'index.php' par 'accueil.php'
header("Location: index.php"); 
exit();
?>