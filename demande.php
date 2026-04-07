<?php
session_start();
require_once 'config.php';

// Vérification de connexion
if (!isset($_SESSION['user_id'])) {
    die("Erreur : Veuillez vous connecter pour accéder à ce formulaire.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id    = $_SESSION['user_id']; // L'ID qui manquait
    $num_reg    = htmlspecialchars($_POST['num_reg']);
    $nom_benef  = htmlspecialchars($_POST['nom_benef']); 
    $email      = htmlspecialchars($_POST['email']);
    $mode       = $_POST['mode'];

    try {
        $sql = "INSERT INTO demandes (user_id, num_registre, beneficiaire, email, mode_reception, statut) 
                VALUES (?, ?, ?, ?, ?, 'En attente')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id, $num_reg, $nom_benef, $email, $mode]);

        header("Location: admin.php?msg=success");
        exit();
    } catch (PDOException $e) {
        die("Erreur SQL : " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Nouvelle Demande - Sacré-Cœur</title>
</head>
<body class="bg-light p-5">
    <div class="card shadow mx-auto" style="max-width: 500px; border-radius: 15px;">
        <div class="card-body">
            <h3 class="text-center mb-4">Demande d'Extrait</h3>
            <form method="POST">
                <div class="mb-3"><label>N° Registre</label><input type="text" name="num_reg" class="form-control" placeholder="Ex: 120/2024" required></div>
                <div class="mb-3"><label>Bénéficiaire</label><input type="text" name="nom_benef" class="form-control" required></div>
                <div class="mb-3"><label>Email de réception</label><input type="email" name="email" class="form-control" required></div>
                <div class="mb-3">
                    <label>Mode d'envoi souhaité</label>
                    <select name="mode" class="form-select">
                        <option value="Email">📧 Email</option>
                        <option value="WhatsApp">📱 WhatsApp</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">ENVOYER LA DEMANDE</button>
                 <div class="text-center mt-3">
                    <a href="acceuil.php" class="text-muted">Annuler et retour</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>