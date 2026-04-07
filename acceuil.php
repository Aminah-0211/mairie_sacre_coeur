<?php
session_start();
if(!isset($_SESSION['user_id'])) { header("Location: connexion.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - Citoyen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { background: #1a237e; min-height: 100vh; color: white; padding: 20px; }
        .nav-link { color: rgba(255,255,255,0.7); border-radius: 10px; margin-bottom: 5px; }
        .nav-link:hover, .nav-link.active { color: white; background: rgba(255,255,255,0.1); }
        .stat-card { border: none; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 sidebar">
            <h5 class="fw-bold mb-5 mt-2 text-center">MAIRIE SACRE_COEUR</h5>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link active" href="acceuil.php"><i class="bi bi-grid me-2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="declaration.php"><i class="bi bi-baby me-2"></i> Naissance</a></li>
                <li class="nav-item"><a class="nav-link" href="demande.php"><i class="bi bi-file-text me-2"></i> Extrait</a></li>
                <li class="nav-item"><a class="nav-link" href="suivi.php"><i class="bi bi-search me-2"></i> Suivi Dossiers</a></li>
                <li class="nav-item mt-5"><a class="nav-link text-danger" href="logout.php"><i class="bi bi-box-arrow-left me-2"></i> Déconnexion</a></li>
            </ul>
        </nav>

        <main class="col-md-9 col-lg-10 p-5">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="fw-bold">Bonjour, <?= $_SESSION['user_nom'] ?> 👋</h2>
                    <p class="text-muted">Bienvenue sur votre portail citoyen sécurisé.</p>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <div class="card stat-card p-4 h-100 bg-white">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                <i class="bi bi-plus-circle text-primary fs-3"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Nouvelle Naissance</h5>
                                <p class="text-muted small mb-3">Déclarer un nouveau-né en quelques secondes.</p>
                                <a href="declaration.php" class="btn btn-primary btn-sm rounded-pill px-4">Démarrer</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card stat-card p-4 h-100 bg-white">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                                <i class="bi bi-file-earmark-arrow-down text-success fs-3"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Faire une demande </h5>
                                <p class="text-muted small mb-3">Besoin d'un acte officiel rapidement ?</p>
                                <a href="demande.php" class="btn btn-success btn-sm rounded-pill px-4">Demander</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

</body>
</html>