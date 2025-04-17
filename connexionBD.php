<?php

/*
Les informations doivent être adaptées selon la configuration de la base de données.
En production, on ne voudrait probablement pas pousser ces informations sur GitHub, surtout
si le dépôt est public. Dans notre cas, on utilise la configuration par défaut de XAMPP.
Les informations ne sont donc pas critiques.
*/
$infosBD = [
    'hote' => 'localhost',
    'nomBD' => 'taches',
    'nomUtilisateur' => 'root',
    'motDePasse' => ''
];

/*
PDO signifie `PHP Data Objects`. Il s'agit d'une interface de programmation de PHP
permettant d'interagir avec différents moteurs de base de données.
La chaîne de connexion utilisée ici est spécifique à MySQL/MariaDB.
*/
$bd = new PDO("mysql:host={$infosBD['hote']};dbname={$infosBD['nomBD']};charset=utf8", $infosBD['nomUtilisateur'], $infosBD['motDePasse']);

/*
On configure PDO pour qu'il lance des exceptions en cas d'erreurs lors de l'exécution de requêtes SQL.
Cela est utile lors du développement. En production, il faut s'assurer que PHP soit configuré pour
ne pas afficher les erreurs aux utilisateurs, pour des raisons de sécurité.
*/
$bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>
