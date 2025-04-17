<?php

/*
Cette application permet de gérer une liste de contacts.
Les contacts sont stockées dans la base de données « contacts ».
Le fichier « SQL/Contacts.sql » permet de créer cette base de données.
*/

/*
Le script `connexionBD.php` se connecte à la base de données et crée la variable `$bd` qui
permet au reste du code d'interagir avec la base de données.
*/
require_once('connexionBD.php');

/*
Le paramètre d'URL `page` identifie la page à afficher. Les valeurs possibles sont:

    - liste: pour afficher la liste des contacts
    - afficher: pour afficher les informations d'un contact
    - ajouter: pour ajouter un contact
    - modifier: pour modifier un contact
    - supprimer: pour supprimer un contact

Si le paramètre `page` est absent ou contient toute autre valeur, on utilisera la valeur par défaut "liste".
*/
$pagesPossibles = ['liste', 'afficher', 'ajouter', 'modifier', 'supprimer'];
$page = isset($_GET['page']) && in_array($_GET['page'], $pagesPossibles) ? $_GET['page'] : 'liste';

/*
Les pages `afficher`, `modifier` et `supprimer` utilisent toutes les trois un deuxième paramètre d'URL
appelé `id`. Ce paramètre contient la clé primaire du contact à afficher, modifier ou supprimer.
*/
if (isset($_GET['id'])) {
    // On veut récupérer les nom, prénom et nom complet du contact afin de pouvoir les afficher.
    $idContact = intval($_GET['id']);

    /*
    CONTRE-EXEMPLE

    On évite d'inclure directement des variables provenant du client dans une requête SQL,
    car cela peut créer des risques d'injection SQL. Dans notre cas, on s'est déjà
    prémuni contre ce risque avec la fonction `intval`, mais ça reste une bonne habitude
    à prendre de toujours éviter cette façon de faire:
    
    $reponse = $bd->query("SELECT nom, prenom FROM contacts WHERE id = $idContact")

    On va donc plutôt utiliser une *requête préparée* :
    */
    $requete = $bd->prepare("SELECT id, nom, prenom, CONCAT(prenom, ' ', nom) AS nom_complet FROM contacts WHERE id = ?");

    /*
    On exécute la requête préparée en lui passant le paramètre `$idContact`.
    Celui-ci remplacera le premier et seul `?` utilisé dans le code SQL.
    */
    $requete->execute([$idContact]);

    // On s'attend à avoir un seul résultat. On peut donc utiliser `fetch()` pour le récupérer.
    $contact = $requete->fetch();

    /*
    Suite à l'exécution de `fetch`, il y a deux possibilités:
    
        - Soit le contact existe, auquel cas la variable `$contact` contiendra un tableau associatif
          dont les clés sont les noms des colonnes de la requête.
        - Soit le contact n'existe pas, auquel cas la variable `$contact` contiendra `false`.
    */
} else {
    // Si aucun paramètre d'URL `id` n'est présent, on met `$contact` à `false`.
    $contact = false;
}

/*
Chargement du bon fichier PHP selon la valeur de `$page`.

Chacun des fichiers dans `pages` contient une fonction `title` qui affiche le contenu de la balise `title` de la page,
et une fonction `body` qui affiche le contenu de la balise `body` de la page. Chacune de ces fonctions accepte au besoin
deux paramètres: `$bd` et `$contact`.
*/
switch($page) {
    case 'liste':
        include('pages/liste.php');
        break;
    case 'afficher':
        include('pages/afficher.php');
        break;
    case 'ajouter':
        include('pages/ajouter.php');
        break;
    case 'modifier':
        include('pages/modifier.php');
        break;
    case 'supprimer':
        include('pages/supprimer.php');
        break;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php title($bd, $contact); ?></title>
    <link rel="stylesheet" href="water.css">
</head>
<body><?php body($bd, $contact) ?></body>
</html>
