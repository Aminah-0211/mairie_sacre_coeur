<?php
require_once 'config.php';
$id = $_GET['id']; $type = $_GET['type'];
$table = ($type === 'Naissance') ? 'declarations' : 'demandes';
$data = $pdo->query("SELECT * FROM $table WHERE id = $id")->fetch();
?>
<div style="padding:40px; font-family:sans-serif; background:#f4f4f4; height:100vh;">
    <div style="background:#fff; padding:20px; border-radius:10px; max-width:500px; margin:auto; border-top:5px solid blue;">
        <h2>Détails du dossier</h2>
        <hr>
        <p><strong>Type :</strong> <?= $type ?></p>
        <p><strong>Info :</strong> <?= $data['beneficiaire'] ?? $data['prenom_enfant'] ?></p>
        <p><strong>Statut actuel :</strong> <?= $data['statut'] ?></p>
        <button onclick="window.close()">Fermer</button>
    </div>
</div>