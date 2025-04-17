<?php

require_once('connexionBD.php');

if (isset($_POST['description']) && isset($_POST['priorite'])) {
    $description = trim(htmlspecialchars($_POST['description']));
    $priorite = trim(htmlspecialchars($_POST['priorite']));

    if (!empty($description) && !empty($priorite)) {
        $requete = $bd->prepare("INSERT INTO tache(description, id_priorite) VALUES(?, ?)");
        $requete->execute([$description, $priorite]);
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $requete = $bd->prepare("DELETE FROM tache WHERE id = ?");
    $requete->execute([$id]);
}

$requeteTaches = $bd->query("
    SELECT tache.id, tache.description, pri.id AS id_priorite, pri.description AS description_priorite
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
    <title>Liste de tâches</title>
    <link rel="stylesheet" href="water.css">
</head>
<body>
    <h1>Liste de tâches</h1>
    <form method="POST">
        <table>
            <thead>
                <tr>
                    <th>Action</th>
                    <th>Description</th>
                    <th>Priorité</th>
                </tr>
            </thead>
            <tbody>
                <?php

                foreach ($requeteTaches as $tache) {
                    echo "<tr>";
                    echo "<td style=\"text-align:center;\"><a href=\"?action=supprimer&id={$tache['id']}\">❌ </a></td>";
                    echo "<td>{$tache['description']}</td>";
                    echo "<td>{$tache['description_priorite']}</td>";
                    echo "</tr>";
                }

                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Ajouter une tâche:</th>
                    <td><input style="margin-right: 2px;" type="text" name="description" required></td>
                    <td>
                        <select name="priorite" required>
                            <option disabled selected></option>
                            <?php
                                $requetePriorites = $bd->query("SELECT * FROM priorite ORDER BY id");
                                foreach ($requetePriorites as $priorite) {
                                    echo "<option value='{$priorite['id']}'>{$priorite['description']}</option>";
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <input type="submit" value="Soumettre">
                    </td>
                </tr>
            </tfoot>
        </table>
    </form>
</body>
</html>
