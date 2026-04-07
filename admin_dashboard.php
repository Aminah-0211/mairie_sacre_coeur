<?php
session_start();
require_once 'config.php';

// SÉCURITÉ : Si pas admin, dehors !
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: acceuil.php");
    exit();
}

// Action de validation/rejet
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $nouveau_statut = ($_GET['action'] == 'valider') ? 'Validé' : 'Rejeté';
    
    $stmt = $pdo->prepare("UPDATE declarations SET statut = ? WHERE id = ?");
    $stmt->execute([$nouveau_statut, $id]);
    header("Location: admin_dashboard.php?success=1");
    exit();
}

// Récupérer toutes les demandes
$stmt = $pdo->query("SELECT d.*, u.nom as nom_parent FROM declarations d 
                     JOIN utilisateurs u ON d.user_id = u.id 
                     ORDER BY d.created_at DESC");
$demandes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration - Mairie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white p-5">
    <div class="container bg-white text-dark p-4 rounded shadow">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>🏢 Gestion de l'État Civil</h2>
            <a href="acceuil.php" class="btn btn-outline-secondary btn-sm">Quitter l'admin</a>
        </div>

        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Parent</th>
                    <th>Enfant</th>
                    <th>Justificatif</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($demandes as $d): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($d['created_at'])) ?></td>
                    <td><?= htmlspecialchars($d['nom_parent']) ?></td>
                    <td><strong><?= htmlspecialchars($d['prenom_enfant']." ".$d['nom_enfant']) ?></strong></td>
                    <td>
                        <a href="data/pieces/<?= $d['certificat_path'] ?>" target="_blank" class="btn btn-sm btn-link">Voir l'image</a>
                    </td>
                    <td>
                        <span class="badge <?= $d['statut'] == 'Validé' ? 'bg-success' : ($d['statut'] == 'Rejeté' ? 'bg-danger' : 'bg-warning text-dark') ?>">
                            <?= $d['statut'] ?>
                        </span>
                    </td>
                    <td class="text-end">
                        <?php if($d['statut'] == 'En attente'): ?>
                            <a href="admin_dashboard.php?action=valider&id=<?= $d['id'] ?>" class="btn btn-success btn-sm">Valider</a>
                            <a href="admin_dashboard.php?action=rejeter&id=<?= $d['id'] ?>" class="btn btn-danger btn-sm">Rejeter</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>