<?php
// 1. Démarrer la session pour pouvoir la supprimer
session_start();

// 2. Vider toutes les variables de session
$_SESSION = array();

// 3. Détruire la session sur le serveur
session_destroy();

// 4. Redirection vers la page d'accueil (index)
header("Location: index.php"); 
exit();
?>