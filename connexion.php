<?php
session_start();
require_once 'config.php';

$message = "";

// Récupération de la cible après connexion (par défaut accueil)
$target = $_GET['target'] ?? ($_POST['target'] ?? 'acceuil.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $pass = trim($_POST['pass']);

    // 1. Rechercher l'utilisateur par son email
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // 2. Vérification avec password_verify (indispensable pour les mots de passe hachés)
    if ($user && password_verify($pass, $user['password'])) {
        // Créer les variables de session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nom'] = $user['nom'];
        $_SESSION['user_role'] = $user['role'];

        // 3. Redirection dynamique
        header("Location: " . $target);
        exit();
    } else {
        // Ton message d'erreur spécifique
        $message = "<div class='alert alert-danger border-0 shadow-sm small text-center'>
                        <i class='bi bi-exclamation-triangle me-2'></i> Email ou mot de passe incorrect.
                    </div>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Mairie de Sacré-Cœur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f0f2f5; display: flex; align-items: center; min-height: 100vh; }
        .auth-card { max-width: 400px; width: 100%; margin: auto; background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .btn-primary { background-color: #1a237e; border: none; padding: 12px; }
        .btn-primary:hover { background-color: #0d1440; }
    </style>
</head>
<body>

<div class="auth-card">
    <div class="text-center mb-4">
        <h3 class="fw-bold text-primary">Connexion</h3>
        <p class="text-muted small">Accédez à votre espace citoyen</p>
    </div>

    <?= $message ?>

    <form action="connexion.php" method="POST">
        <input type="hidden" name="target" value="<?= htmlspecialchars($target) ?>">

        <div class="mb-3">
            <label class="form-label small fw-bold">Email</label>
            <input type="email" name="email" class="form-control bg-light" placeholder="nom@exemple.com" required>
        </div>

        <div class="mb-4">
            <label class="form-label small fw-bold">Mot de passe</label>
            <input type="password" name="pass" class="form-control bg-light" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill mb-3">SE CONNECTER</button>
        
        <div class="text-center mt-3">
            <span class="text-muted small">Pas de compte ?</span> 
            <a href="inscription.php?target=<?= urlencode($target) ?>" class="text-primary small fw-bold text-decoration-none ms-1">Inscrivez-vous</a>
        </div>
    </form>
</div>

</body>
</html>