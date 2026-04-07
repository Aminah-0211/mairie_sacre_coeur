<?php 
session_start(); 

$est_connecte = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mairie de Sacré-Cœur - État Civil en ligne</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #1a237e;
            --accent-color: #00c853;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: #ffffff;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                        url('https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 120px 0;
            min-height: 80vh;
            display: flex;
            align-items: center;
        }

        .navbar-brand {
            font-weight: 800;
            letter-spacing: 1px;
            color: var(--primary-color) !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
        }

        .btn-primary:hover {
            background-color: #0d1440;
        }

        .feature-card {
            border: none;
            border-radius: 20px;
            transition: all 0.3s ease;
            background: #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        .icon-box {
            width: 80px;
            height: 80px;
            background: rgba(26, 35, 126, 0.1);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            margin-bottom: 25px;
            font-size: 2.2rem; /* Taille uniforme pour toutes les icônes */
        }

        .footer {
            background: #f8f9fa;
            padding: 40px 0;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="bi bi-bank2 me-2"></i>MAIRIE SACRÉ-CŒUR
        </a>
        
        <div class="ms-auto d-flex gap-2 align-items-center">
            <?php if($est_connecte): ?>
                <a href="accueil.php" class="btn btn-outline-primary rounded-pill px-4 fw-bold">
                    <i class="bi bi-person-circle me-1"></i> Mon Espace
                </a>
                <a href="deconnexion.php" class="btn btn-danger rounded-circle shadow-sm" title="Quitter">
                    <i class="bi bi-power"></i>
                </a>
            <?php else: ?>
                <a href="connexion.php" class="btn btn-link text-decoration-none text-dark fw-bold">Connexion</a>
                <a href="inscription.php" class="btn btn-primary rounded-pill px-4 shadow-sm">S'inscrire</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<header class="hero-section text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h1 class="display-3 fw-bold mb-4">Vos démarches administratives simplifiées</h1>
                <p class="lead mb-5 opacity-90">Déclarez une naissance ou demandez un extrait d'acte civil en quelques minutes, sans vous déplacer à la mairie.</p>
                
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <?php if($est_connecte): ?>
                        <a href="accueil.php" class="btn btn-light btn-lg px-5 rounded-pill fw-bold shadow">
                            <i class="bi bi-rocket-takeoff me-2"></i>Mon tableau de bord
                        </a>
                    <?php else: ?>
                        <a href="inscription.php" class="btn btn-light btn-lg px-5 rounded-pill fw-bold shadow">
                            Commencer maintenant
                        </a>
                    <?php endif; ?>
                    
                    <a href="#services" class="btn btn-outline-light btn-lg px-5 rounded-pill fw-bold">
                        Nos services
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<section id="services" class="py-5">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Nos services en ligne</h2>
            <p class="text-muted">Gagnez du temps avec nos outils numériques sécurisés</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card p-4 feature-card text-center">
                    <div class="icon-box mx-auto">
                        <i class="bi bi-person-plus"></i>
                    </div>
                    <h4 class="fw-bold">Déclaration Naissance</h4>
                    <p class="text-muted small">Enregistrez un nouveau-né en ligne en joignant simplement le certificat d'accouchement.</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card p-4 feature-card text-center">
                    <div class="icon-box mx-auto">
                        <i class="bi bi-file-earmark-pdf"></i>
                    </div>
                    <h4 class="fw-bold">Demande d'Extrait</h4>
                    <p class="text-muted small">Commandez votre acte de naissance et recevez-le directement via Gmail ou WhatsApp.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-4 feature-card text-center">
                    <div class="icon-box mx-auto">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h4 class="fw-bold">Suivi de dossier</h4>
                    <p class="text-muted small">Consultez à tout moment l'état d'avancement de vos demandes en temps réel.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="footer">
    <div class="container text-center">
        <p class="mb-0 text-muted small">&copy; 2026 Mairie de Sacré-Cœur - Service de l'État Civil. Tous droits réservés.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>