<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['certificat']) && $_FILES['certificat']['error'] === 0) {
        
        $ext = strtolower(pathinfo($_FILES['certificat']['name'], PATHINFO_EXTENSION));
        $nom_fichier = time() . "_" . uniqid() . "." . $ext;
        $destination = $dossier_pieces . DIRECTORY_SEPARATOR . $nom_fichier;

        if (move_uploaded_file($_FILES['certificat']['tmp_name'], $destination)) {
            try {
                $sql = "INSERT INTO declarations (user_id, prenom_enfant, nom_enfant, sexe, date_naissance, nom_pere, nom_mere, certificat_path, num_registre, statut, created_at) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'En attente', NOW())";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $_SESSION['user_id'], $_POST['prenom'], $_POST['nom'], $_POST['sexe'], 
                    $_POST['date_n'], $_POST['pere'], $_POST['mere'], $nom_fichier, rand(100,999)."/2026"
                ]);

                header("Location: suivi.php"); // On redirige vers le suivi pour voir le résultat
                exit();
            } catch (PDOException $e) {
                $error = "Erreur SQL : " . $e->getMessage();
            }
        } else {
            $error = "Erreur de transfert de fichier. Vérifiez les permissions du dossier data/pieces.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Déclaration - Mairie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container card shadow p-4" style="max-width: 600px;">
        <h2 class="text-success">👶 Nouvelle Déclaration</h2>
        <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="prenom" class="form-control mb-2" placeholder="Prénom enfant" required>
            <input type="text" name="nom" class="form-control mb-2" placeholder="Nom enfant" required>
            <select name="sexe" class="form-control mb-2"><option value="M">Masculin</option><option value="F">Féminin</option></select>
            <input type="date" name="date_n" class="form-control mb-2" required>
            <input type="text" name="mere" class="form-control mb-2" placeholder="Nom de la mère" required>
            <input type="text" name="pere" class="form-control mb-2" placeholder="Nom du père">
            <label>Certificat d'accouchement :</label>
            <input type="file" name="certificat" class="form-control mb-3" required>
            <button type="submit" class="btn btn-success w-100">Envoyer la déclaration</button>
             <div class="text-center mt-3">
                    <a href="acceuil.php" class="text-muted">Annuler et retour</a>
                </div>
        </form>
    </div>
</body>
</html>