<?php
/**
 * Created by PhpStorm.
 * User: canals5
 * Date: 28/10/2019
 * Time: 16:16
 */

use MongoDB\Client;
require_once __DIR__ . "/../vendor/autoload.php";

$c = new Client("mongodb://mongo");
echo "connected to mongo <br>";
$db = $c->chopizza;
$produits = $db->produits;
$recettes = $db->recettes;


//Requêtes en PHP

//1. afficher la liste des produits: numero, categorie, libelle

$cursor = $produits->find(
    [],
    ['projection' => ['_id' => 0, 'numero' => 1, 'categorie' => 1, 'libelle' => 1]]
);

foreach ($cursor as $p) {
    echo $p['numero'] . " - " . $p['categorie'] . " - " . $p['libelle'] . "<br>";
}


//2. afficher le produit numéro 6, préciser : libellé, catégorie, description, tarifs


//3. liste des produits dont le tarif en taille normale est <= 3.0

$cursor = $produits->find([
    'tarifs' => [
        '$elemMatch' => [
            'taille' => 'normale',
            'tarif' => ['$lte' => 3.0]
        ]
    ]
]);

foreach ($cursor as $p) {
    echo $p['numero'] . "-" . $p['libelle'] . "<br>";
}

//4. liste des produits associés à 4 recettes


//5. afficher le produit n°6, compléter en listant les recettes associées (nom et difficulté)
$prod = $produits->findOne(['numero' => 6]);
// tableau d'objectid
$ids = $prod['recettes'];

$cursor = $recettes->find(
    ['_id' => ['$in' => $ids]],
    ['projection' => ['_id' => 0, 'nom' => 1, 'difficulte' => 1]]
);

echo "<h3>" . $prod['libelle'] . "</h3>";
echo "<p>Catégorie : " . $prod['categorie'] . "</p>";
echo "<p>Description : " . $prod['description'] . "</p>";

echo "<h3>recettes :</h3>";
foreach ($cursor as $r) {
    echo "- " . $r['nom'] . " (" . $r['difficulte'] . ")<br>";
}


//6. créer une fonction qui reçoit en paramètre un numéro de produit et une taille et retourne un
// tableau contenant les données descriptives de ce produit : numéro, libellé, catégorie, taille,
// tarif ; utiliser cette fonction et afficher le résultat en json.


