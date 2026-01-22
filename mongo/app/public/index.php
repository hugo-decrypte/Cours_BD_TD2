<?php
require_once __DIR__ . "/../vendor/autoload.php";
$db = (new MongoDB\Client("mongodb://mongo"))->chopizza;

// --- TRAITEMENT DU FORMULAIRE D'AJOUT ---
if (isset($_POST['ajouter'])) {
    $db->produits->insertOne([
        'numero'      => (int)$_POST['numero'],
        'libelle'     => $_POST['libelle'],
        'categorie'   => $_POST['categorie'],
        'description' => $_POST['description'],
        'tarifs'      => [
            ['taille' => $_POST['taille'], 'tarif' => (float)$_POST['prix']]
        ],
        'recettes'    => []
    ]);
    echo "<b>Produit ajouté avec succès !</b><br><br>";
}

// --- NAVIGATION PAR CATÉGORIE ---
echo "<h2>Catalogue par catégorie</h2>";
$categories = $db->produits->distinct("categorie");

foreach ($categories as $cat) {
    echo "[ <a href='?cat=$cat'>$cat</a> ] ";
}
echo "<br><br>";

if (isset($_GET['cat'])) {
    $cursor = $db->produits->find(['categorie' => $_GET['cat']]);
    foreach ($cursor as $p) {
        echo "<b>N°" . $p['numero'] . " - " . $p['libelle'] . "</b><br>";
        echo "<i>" . $p['description'] . "</i><br>";
        echo "Tarifs : ";
        foreach ($p['tarifs'] as $t) {
            echo " [ " . $t['taille'] . " : " . $t['tarif'] . "€ ] ";
        }
        echo "<hr>";
    }
}

// --- FORMULAIRE D'AJOUT (VIA ECHO) ---
echo "<h2>Ajouter un nouveau produit</h2>";
echo "<form method='POST'>";
echo "Numéro : <input type='number' name='numero' required><br>";
echo "Libellé : <input type='text' name='libelle' required><br>";
echo "Description : <input type='text' name='description'><br>";

echo "Catégorie : <select name='categorie'>";
foreach ($categories as $cat) {
    echo "<option value='$cat'>$cat</option>";
}
echo "</select><br>";

echo "Taille : <select name='taille'>";
echo "<option value='normale'>Normale</option>";
echo "<option value='grande'>Grande</option>";
echo "<option value='junior'>Junior</option>";
echo "</select> ";

echo "Prix : <input type='number' step='0.1' name='prix' required><br><br>";
echo "<input type='submit' name='ajouter' value='Enregistrer le produit'>";
echo "</form>";