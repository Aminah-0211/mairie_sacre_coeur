<?php
session_start();
require_once 'config.php';

// Sécurité : Optionnel - Vérifier si l'utilisateur est connecté
// if (!isset($_SESSION['user_id'])) { header("Location: connexion.php"); exit(); }

try {
    // Récupération des dossiers (Union des Naissances et des Extraits)
    $sql = "
        (SELECT id, 'Naissance' AS type_doc, CONCAT(prenom_enfant, ' ', nom_enfant) AS info, statut, created_at AS date_action, 'Mairie' AS mode, '' AS email 
         FROM declarations)
        UNION ALL
        (SELECT id, 'Extrait' AS type_doc, beneficiaire AS info, statut, date_demande AS date_action, mode_reception AS mode, email 
         FROM demandes)
        ORDER BY date_action DESC
    ";
    
    $stmt = $pdo->query($sql);
    $liste = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Erreur SQL : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Mairie de Sacré-Cœur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        .navbar-admin { background: #1a237e; color: white; padding: 15px; margin-bottom: 30px; }
        .admin-card { background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<nav class="navbar-admin shadow">
    <div class="container d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="bi bi-bank me-2"></i> Espace Officier | Sacré-Cœur</h4>
        <a href="deconnexion.php" class="btn btn-outline-light btn-sm shadow-sm">
            <i class="bi bi-box-arrow-right me-1"></i> Quitter l'Espace
        </a>
    </div>
</nav>

<div class="container">
    <div class="admin-card">
        <h3 class="fw-bold mb-4"><i class="bi bi-folder2-open text-primary me-2"></i>Gestion des Dossiers</h3>

        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'ok'): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
                <i class="bi bi-check-circle-fill me-2"></i> Le statut du dossier a été mis à jour !
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Bénéficiaire</th>
                        <th>Réception</th>
                        <th>Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($liste as $d): ?>
                    <tr>
                        <td class="text-muted small"><?= date('d/m/Y', strtotime($d['date_action'])) ?></td>
                        <td><span class="badge bg-info-subtle text-info border border-info px-3"><?= $d['type_doc'] ?></span></td>
                        <td><strong><?= htmlspecialchars($d['info']) ?></strong></td>
                        <td>
                            <?php if($d['mode'] == 'WhatsApp'): ?>
                                <span class="text-success fw-bold"><i class="bi bi-whatsapp"></i> WhatsApp</span>
                            <?php elseif($d['mode'] == 'Email'): ?>
                                <span class="text-primary fw-bold"><i class="bi bi-envelope-at"></i> Gmail</span>
                            <?php else: ?>
                                <span class="text-muted">Mairie</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-<?= ($d['statut']=='Validé')?'success':(($d['statut']=='Rejeté')?'danger':'warning text-dark') ?>">
                                <?= $d['statut'] ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group shadow-sm">
                                <a href="traiter_dossier.php?id=<?= $d['id'] ?>&type=<?= urlencode($d['type_doc']) ?>&action=valider" 
                                   class="btn btn-sm btn-success" title="Valider"><i class="bi bi-check-circle"></i></a>
                                
                                <a href="traiter_dossier.php?id=<?= $d['id'] ?>&type=<?= urlencode($d['type_doc']) ?>&action=rejeter" 
                                   class="btn btn-sm btn-danger" title="Rejeter"><i class="bi bi-x-circle"></i></a>

                                <a href="apercu_document.php?id=<?= $d['id'] ?>&type=<?= urlencode($d['type_doc']) ?>" 
                                   target="_blank" class="btn btn-sm btn-outline-secondary" title="Voir"><i class="bi bi-eye"></i></a>

                                <?php if($d['statut'] == 'Validé'): ?>
                                    <?php if($d['mode'] == 'WhatsApp'): ?>
                                        <a href="https://wa.me/?text=Bonjour, votre acte est pret ici : http://localhost/mairie_sacre_coeur/telecharger_acte.php?id=<?= $d['id'] ?>" 
                                           target="_blank" class="btn btn-sm btn-outline-success"><i class="bi bi-whatsapp"></i></a>
                                    <?php elseif($d['mode'] == 'Email'): ?>
                                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=<?= $d['email'] ?>&su=Acte Civil - Mairie Sacré-Cœur&body=Bonjour, votre demande a été validée. Retrouvez votre document ici : http://localhost/mairie_sacre_coeur/telecharger_acte.php?id=<?= $d['id'] ?>" 
                                           target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-envelope-at-fill"></i></a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>