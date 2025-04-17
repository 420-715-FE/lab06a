<?php

/*
Cette page a besoin d'un ID de contact valide.
Si la variable $contact vaut `false`, cela signifie soit que le paramètre d'URL `id`
est absent, soit que l'ID de contact qui lui a été passé n'existe pas.
Si c'est le cas, nous voulons afficher une page d'erreur 404.
*/
if (!$contact) {
    include('404.php');
} else {

    function title($bd, $contact)
    {
        echo $contact['nom_complet'] . " | Liste de contacts";
    }

    function body($bd, $contact)
    {
    ?>
        <nav>
            <a href="?page=liste">Contacts</a>
            &nbsp;/&nbsp;
            <?= $contact['nom_complet'] ?>
        </nav>
        <h1><?= $contact['nom_complet'] ?></h1>

        <p>
            <a href="?page=modifier&id=<?= $contact['id'] ?>">
                ✏️
            </a>
            &nbsp;
            <a
                onclick="return confirm('Voulez-vous vraiment supprimer le contact « <?= $contact['nom'] . ', ' . $contact['prenom'] ?> » ?')"
                href="?page=supprimer&id=<?= $contact['id'] ?>">
                ❌
            </a>
        </p>

        <main>
            <h2>Numéros de téléphone</h2>
            <?php
            $requete = $bd->prepare('SELECT numero_tel, types_numero_tel.description FROM numeros_tel JOIN types_numero_tel ON types_numero_tel.code = numeros_tel.type_numero_tel WHERE contact_id = ?');
            $requete->execute(array($contact['id']));

            if ($requete->rowCount() > 0) {
                echo '<ul>';
                foreach ($requete as $donneesNumeroTel) {
                    echo '<li><strong>'
                        . $donneesNumeroTel['description']
                        . ': </strong>'
                        . $donneesNumeroTel['numero_tel']
                        . '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>Aucun</p>';
            }
            ?>

            <h2>Adresses</h2>
            <?php
            $requete = $bd->prepare('SELECT adresse, types_adresse.description FROM adresses JOIN types_adresse ON types_adresse.code = adresses.type_adresse WHERE contact_id = ?');
            $requete->execute(array($contact['id']));

            if ($requete->rowCount() > 0) {
                echo '<ul>';
                foreach ($requete as $donneesNumeroTel) {
                    echo '<li><strong>'
                        . $donneesNumeroTel['description']
                        . ': </strong>'
                        . $donneesNumeroTel['adresse']
                        . '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>Aucun</p>';
            }
            ?>

            <h2>Adresses courriel</h2>
            <?php
            $requete = $bd->prepare('SELECT courriel, types_courriel.description FROM courriels JOIN types_courriel ON types_courriel.code = courriels.type_courriel WHERE contact_id = ?');
            $requete->execute(array($contact['id']));

            if ($requete->rowCount() > 0) {
                echo '<ul>';
                foreach ($requete as $donneesNumeroTel) {
                    echo '<li><strong>'
                        . $donneesNumeroTel['description']
                        . ': </strong>'
                        . $donneesNumeroTel['courriel']
                        . '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>Aucune</p>';
            }
            ?>
        </main>

    <?php
    }
}
