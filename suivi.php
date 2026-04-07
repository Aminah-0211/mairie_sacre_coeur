<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$liste = [];

try {
   $sql = "
    (SELECT 
        id, 
        CAST(num_registre AS CHAR CHARACTER SET utf8mb4) AS num_registre, 
        CAST(CONCAT(prenom_enfant, ' ', nom_enfant) AS CHAR CHARACTER SET utf8mb4) AS beneficiaire, 
        CAST('Naissance' AS CHAR CHARACTER SET utf8mb4) AS type_doc, 
        CAST(statut AS CHAR CHARACTER SET utf8mb4) AS statut, 
        created_at AS date_action 
     FROM declarations 
     WHERE user_id = ?)
    UNION ALL
    (SELECT 
        id, 
        CAST(num_registre AS CHAR CHARACTER SET utf8mb4) AS num_registre, 
        CAST(num_registre AS CHAR CHARACTER SET utf8mb4) AS beneficiaire, 
        CAST('Extrait' AS CHAR CHARACTER SET utf8mb4) AS type_doc, 
        CAST(statut AS CHAR CHARACTER SET utf8mb4) AS statut, 
        date_demande AS date_action 
     FROM demandes 
     WHERE user_id = ?)
    ORDER BY date_action DESC
";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $user_id]);
    $liste = $stmt->fetchAll();

} catch (PDOException $e) {
    $error_db = "Erreur : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Suivi - Mairie de Sacré-Cœur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; padding-top: 50px; }
        .table-card { background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .badge-status { padding: 6px 12px; border-radius: 50px; font-size: 0.75rem; font-weight: 600; }
    </style>
</head>
<body>

<div class="container" style="max-width: 1000px;">
    <div class="table-card">
        <h3 class="fw-bold mb-4 text-primary"><i class="bi bi-file-earmark-text me-2"></i>Mes Dossiers</h3>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Bénéficiaire</th>
                        <th class="text-center">État</th>
                        <th class="text-center">Document</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($liste as $d): ?>
                    <tr>
                        <td class="small"><?= date('d/m/Y', strtotime($d['date_action'])) ?></td>
                        <td><span class="badge bg-light text-dark border"><?= $d['type_doc'] ?></span></td>
                        <td class="fw-bold"><?= htmlspecialchars($d['beneficiaire']) ?></td>
                        <td class="text-center">
                            <?php if($d['statut'] == 'Validé'): ?>
                                <span class="badge bg-success badge-status">Validé</span>
                            <?php elseif($d['statut'] == 'Rejeté'): ?>
                                <span class="badge bg-danger badge-status">Rejeté</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark badge-status">En cours</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if($d['statut'] == 'Validé'): ?>
                                <a href="telecharger_acte.php?id=<?= $d['id'] ?>&type=<?= $d['type_doc'] ?>" target="_blank" class="btn btn-sm btn-primary px-3">
                                    <i class="bi bi-download"></i> PDF
                                </a>
                            <?php else: ?>
                                <button class="btn btn-sm btn-light border text-muted" disabled><i class="bi bi-lock"></i></button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <a href="acceuil.php" class="btn btn-secondary rounded-pill mt-3">Retour</a>
    </div>
</div>
</body>
</html>