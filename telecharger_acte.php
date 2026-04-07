<?php
// 1. Les imports (TOUJOURS en haut du fichier PHP)
use Dompdf\Dompdf;
use Dompdf\Options;

// 2. Vérification et chargement de la bibliothèque
$path = 'dompdf/autoload.inc.php';

if (!file_exists($path)) {
    die("ERREUR : Le fichier est introuvable dans : " . realpath($path) . "<br>Vérifiez que le dossier s'appelle bien 'dompdf' (minuscules).");
}

require_once $path;

// 3. Configuration de Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

// 4. Le contenu de ton document (Exemple pour Amy SALL)
$html = '
<div style="font-family: Arial, sans-serif; text-align: center; border: 5px solid #1a237e; padding: 30px;">
    <h1 style="color: #1a237e; margin-bottom: 0;">MAIRIE DE SACRÉ-CŒUR</h1>
    <p style="margin-top: 0;">République du Sénégal</p>
    <hr style="width: 50%; margin: 20px auto;">
    
    <h2 style="text-transform: uppercase;">Extrait de Naissance</h2>
    
    <div style="text-align: left; margin-top: 40px; line-height: 1.8;">
        <p><b>Prénom :</b> Amy</p>
        <p><b>Nom :</b> SALL</p>
        <p><b>Date de naissance :</b> 03/04/2026</p>
        <p><b>État du dossier :</b> Validé officiellement</p>
    </div>

    <div style="margin-top: 50px; text-align: right;">
        <p>Fait à Sacré-Cœur, le ' . date('d/m/Y') . '</p>
        <p><i>Signé numériquement par l\'officier d\'état civil</i></p>
    </div>
</div>';

// 5. Génération et affichage
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// "Attachment" => false permet d'ouvrir dans le navigateur au lieu de forcer le téléchargement
$dompdf->stream("acte_naissance_amy_sall.pdf", ["Attachment" => false]);