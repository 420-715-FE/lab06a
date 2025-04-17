<?php

function title() {
    echo "Liste de contacts";
}

function body($bd) {
    ?>

    <h1>Liste de contacts</h1>
    <ul>
        <?php

        /*
        On exécute d'abord une requête pour récupérer tous les contacts de la base de données.
        On n'utilise pas une requête préparée ici, car il n'y a pas de paramètres à passer.
        */
        $requeteContacts = $bd->query(
            "SELECT id, CONCAT(prenom, ' ', nom) AS nom_complet FROM contacts ORDER BY nom_complet"
        );

        /*
        On peut utiliser une boucle `foreach` pour parcourir les résultats de la requête.
        Chaque résultat est un tableau associatif dont les clés sont les noms des colonnes de la requête.
        On aurait pu utiliser `fetchAll` pour récupérer tous les résultats d'un coup avant de les parcourir,
        mais ce n'est pas nécessaire ici.
        */
        foreach ($requeteContacts as $contact) {
            echo "<li><a href=\"?page=afficher&id={$contact['id']}\">{$contact['nom_complet']}</a></li>";
        }

        /* Voici le même exemple avec un `fetchAll`:

        $contacts = $requeteContacts->fetchAll();
        foreach ($contacts as $contact) {
            echo "<li><a href=\"?page=afficher&id={$contact['id']}\">{$contact['nom_complet']}</a></li>";
        }
        */

        ?>
    </ul>

    <p><a href="?page=ajouter">Ajouter un contact</a></p>
    
    <?php
}

?>
