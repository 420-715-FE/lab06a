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
    <title>Liste de tâches</title>
    <link rel="stylesheet" href="water.css">
</head>
<body>
    <h1>Liste de tâches</h1>
    <form>
        <table>
            <thead>
                <tr>
                    <th>Complétée</th>
                    <th>Description</th>
                    <th>Priorité</th>
                </tr>
            </thead>
            <tbody>
                <?php

                foreach ($requeteTaches as $tache) {
                    $checked = $tache['completee'] ? 'checked' : '';
                    echo "<tr>";
                    echo "<td><input type='checkbox' name='completee[{$tache['id']}]' $checked></td>";
                    echo "<td>{$tache['description']}</td>";
                    echo "<td>{$tache['description_priorite']}</td>";
                    echo "</tr>";
                }

                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Ajouter une tâche:</th>
                    <td><input type="text" name="description"></td>
                    <td>
                        <select name="priorite">
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
            </tfoot>
        </table>
        <p>
            <input type="submit" value="Sauvegarder les modifications" />
        </p>
    </form>
</body>
</html>
