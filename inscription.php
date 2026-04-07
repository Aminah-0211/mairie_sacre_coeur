<?php
require_once 'config.php';

// On récupère la cible (par défaut 'acceuil.php')
// Note: j'ai ajouté .php pour éviter les erreurs de redirection plus tard
$target = $_GET['target'] ?? ($_POST['target'] ?? 'acceuil.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Nettoyage des entrées
    $nom = htmlspecialchars(trim($_POST['nom']));
    $tel = htmlspecialchars(trim($_POST['tel']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    
    // CRUCIAL : Hachage du mot de passe
    // Sans cette ligne, la connexion ne marchera JAMAIS avec password_verify
    $pass_brut = trim($_POST['pass']); 
    $pass_hache = password_hash($pass_brut, PASSWORD_DEFAULT);

    try {
        // Ajout de la colonne 'role' par défaut à 'user' pour la cohérence
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, telephone, email, password, role) VALUES (?, ?, ?, ?, 'user')");
        
        if ($stmt->execute([$nom, $tel, $email, $pass_hache])) {
            // Une fois inscrit, on envoie vers la connexion en GARDANT la cible
            header("Location: connexion.php?target=" . urlencode($target) . "&reg_success=1");
            exit();
        }
    } catch (PDOException $e) {
        // Gestion de l'email en double
        if ($e->getCode() == 23000) {
            $error = "Erreur : Cet email est déjà utilisé par un autre compte.";
        } else {
            $error = "Une erreur est survenue lors de l'enregistrement.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Mairie de Sacré-Cœur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; display: flex; align-items: center; min-height: 100vh; }
        .card-auth { max-width: 450px; width: 100%; margin: auto; border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .btn-primary { background-color: #1a237e; border: none; }
        .btn-primary:hover { background-color: #0d1440; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card card-auth p-4">
            <div class="text-center mb-4">
                <i class="bi bi-person-plus-fill text-primary" style="font-size: 3rem;"></i>
                <h2 class="fw-bold mt-2">Créer un compte</h2>
                <p class="text-muted small">Portail Citoyen de Sacré-Cœur</p>
            </div>
            
            <?php if(isset($error)): ?>
                <div class="alert alert-danger py-2 small border-0 shadow-sm text-center">
                    <i class="bi bi-exclamation-circle me-2"></i><?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="target" value="<?php echo htmlspecialchars($target); ?>">
                
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nom complet</label>
                    <input type="text" name="nom" class="form-control" placeholder="AMY SALL" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Téléphone</label>
                    <input type="text" name="tel" class="form-control" placeholder="77 000 00 00" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="amy@gmail.com" required>
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-bold">Mot de passe</label>
                    <input type="password" name="pass" class="form-control" placeholder="••••••••" required>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold">
                    S'INSCRIRE
                </button>
            </form>
            
            <div class="text-center mt-4">
                <p class="mb-0 small text-muted">Déjà inscrit ? 
                    <a href="connexion.php?target=<?php echo urlencode($target); ?>" class="text-primary fw-bold text-decoration-none">
                        Connectez-vous ici
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>