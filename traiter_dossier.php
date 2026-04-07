<?php
require_once 'config.php';
$id = $_GET['id']; $type = $_GET['type']; $action = $_GET['action'];

$table = ($type === 'Naissance') ? 'declarations' : 'demandes';
$statut = ($action === 'valider') ? 'Validé' : 'Rejeté';

$stmt = $pdo->prepare("UPDATE $table SET statut = ? WHERE id = ?");
$stmt->execute([$statut, $id]);

header("Location: admin.php?msg=updated");
exit();