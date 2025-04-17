<?php

require_once('connexionBD.php');

$requeteTaches = $bd->query("
    SELECT tache.id, tache.description, tache.completee, pri.id AS id_priorite, pri.description AS description_priorite
    FROM tache
    JOIN priorite pri ON tache.id_priorite = pri.id
    ORDER BY tache.id_priorite, tache.description
");

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
