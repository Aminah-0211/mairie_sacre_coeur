<?php
session_start();
require_once 'config.php';

// 1. PROTECTION : Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php?target=suivi");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_nom = $_SESSION['user_nom'] ?? 'Citoyen';

try {
    // 2. STATISTIQUES : Somme des deux tables (Déclarations + Extraits)
    $sql_stats = "SELECT 
        (SELECT COUNT(*) FROM declarations WHERE user_id = :uid) + 
        (SELECT COUNT(*) FROM demandes_extraits WHERE user_id = :uid) as total,
        
        (SELECT COUNT(*) FROM declarations WHERE user_id = :uid AND statut = 'Validé') + 
        (SELECT COUNT(*) FROM demandes_extraits WHERE user_id = :uid AND statut = 'Validé') as valides,
        
        (SELECT COUNT(*) FROM declarations WHERE user_id = :uid AND statut = 'En attente') + 
        (SELECT COUNT(*) FROM demandes_extraits WHERE user_id = :uid AND statut = 'En attente') as attente";
    
    $stmt_stats = $pdo->prepare($sql_stats);
    $stmt_stats->execute(['uid' => $user_id]);
    $stats = $stmt_stats->fetch();

    // 3. RÉCUPÉRATION DE LA LISTE MIXTE (UNION ALL)
    // On harmonise les colonnes : id, nom du bénéficiaire, type de dossier, statut et date
    $sql_liste = "
        SELECT id, prenom_enfant as beneficiaire, 'Naissance' as type, statut, created_at 
        FROM declarations 
        WHERE user_id = :uid
        
        UNION ALL
        
        SELECT id, nom_beneficiaire as beneficiaire, 'Extrait' as type, statut, date_demande as created_at 
        FROM demandes_extraits 
        WHERE user_id = :uid
        
        ORDER BY created_at DESC";

    $stmt = $pdo->prepare($sql_liste);
    $stmt->execute(['uid' => $user_id]);
    $mes_dossiers = $stmt->fetchAll();

} catch (Exception $e) {
    $stats = ['total' => 0, 'valides' => 0, 'attente' => 0];
    $mes_dossiers = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi de mes dossiers - Mairie de Sacré-Cœur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', Tahoma, Geneva, sans-serif; }
        .table-container { background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); padding: 30px; }
        .status-badge { font-size: 0.8rem; padding: 6px 15px; border-radius: 50px; font-weight: 600; }
        .type-badge { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; padding: 4px 8px; border-radius: 5px; }
        .stat-card { border: none; border-radius: 15px; transition: 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
        .nav-mairie { background: #1a237e; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark nav-mairie shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="acceuil.php">🏛️ Mairie de Sacré-Cœur</a>
        <a href="logout.php" class="btn btn-outline-light btn-sm rounded-pill">Déconnexion</a>
    </div>
</nav>

<div class="container">
    <div class="mb-4">
        <h2 class="fw-bold">Suivi de mes demandes</h2>
        <p class="text-muted">Bonjour <strong><?php echo htmlspecialchars($user_nom); ?></strong>, voici l'état d'avancement de vos dossiers.</p>
    </div>

    <div class="row g-3 mb-5">
        <div class="col-md-4">
            <div class="card stat-card bg-primary text-white p-3">
                <small>Total dossiers</small>
                <h3 class="fw-bold mb-0"><?php echo $stats['total']; ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card bg-success text-white p-3">
                <small>Dossiers Validés</small>
                <h3 class="fw-bold mb-0"><?php echo (int)$stats['valides']; ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card bg-warning text-dark p-3">
                <small>En attente / En cours</small>
                <h3 class="fw-bold mb-0"><?php echo (int)$stats['attente']; ?></h3>
            </div>
        </div>
    </div>

    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr class="text-muted">
                        <th>N° Dossier</th>
                        <th>Type & Bénéficiaire</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Statut</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($mes_dossiers)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Aucun dossier trouvé dans votre historique.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($mes_dossiers as $d): ?>
                        <tr>
                            <td class="fw-bold">#<?php echo str_pad($d['id'], 4, '0', STR_PAD_LEFT); ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($d['beneficiaire']); ?></div>
                                <span class="type-badge <?php echo ($d['type'] == 'Naissance') ? 'bg-info-subtle text-info' : 'bg-secondary-subtle text-secondary'; ?>">
                                    <?php echo $d['type']; ?>
                                </span>
                            </td>
                            <td class="text-center small text-muted">
                                <?php echo date('d/m/Y', strtotime($d['created_at'])); ?>
                            </td>
                            <td class="text-center">
                                <?php if ($d['statut'] == 'Validé'): ?>
                                    <span class="status-badge bg-success-subtle text-success">Validé</span>
                                <?php elseif ($d['statut'] == 'Rejeté'): ?>
                                    <span class="status-badge bg-danger-subtle text-danger">Rejeté</span>
                                <?php else: ?>
                                    <span class="status-badge bg-warning-subtle text-dark text-nowrap">En attente</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <?php if ($d['statut'] == 'Validé'): ?>
                                    <a href="telecharger.php?id=<?php echo $d['id']; ?>&type=<?php echo $d['type']; ?>" class="btn btn-sm btn-dark px-3 rounded-pill">
                                        <i class="bi bi-download"></i>
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-light text-muted rounded-pill" disabled>Traitement...</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="text-center mt-4">
        <a href="acceuil.php" class="btn btn-link text-decoration-none text-muted">
            <i class="bi bi-arrow-left"></i> Retour au portail
        </a>
    </div>
</div>

</body>
</html>