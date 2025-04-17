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
    /*
    On utilise une transaction pour s'assurer que
    1) les opérations modifiant la BD n'aient effet que si toutes les requêtes réussissent;
    2) Il n'y ait pas de modification entre temps sur les données lues puis mises à jour.
    */
    $bd->beginTransaction();

    // Récupérer les adresses

    $requete = $bd->prepare("SELECT adresse, type_adresse FROM adresses WHERE contact_id = ?");
    $requete->execute([ $contact['id'] ]);

    $adresses = [];
    foreach ($requete as $donneesAdresse) {
        $adresses[$donneesAdresse['type_adresse']] = $donneesAdresse['adresse'];
    }

    // Récupérer les numéros de téléphone

    $requete = $bd->prepare("SELECT numero_tel, type_numero_tel FROM numeros_tel WHERE contact_id = ?");
    $requete->execute([ $idContact ]);

    $numerosTel = [];
    foreach ($requete as $donneesNumeroTel) {
        $numerosTel[$donneesNumeroTel['type_numero_tel']] = $donneesNumeroTel['numero_tel'];
    }

    // Récupérer les adresses courriel

    $requete = $bd->prepare("SELECT courriel, type_courriel FROM courriels WHERE contact_id = ?");
    $requete->execute([ $idContact ]);

    $courriels = [];
    foreach ($requete as $donneesCourriel) {
        $courriels[$donneesCourriel['type_courriel']] = $donneesCourriel['courriel'];
    }

    // Traiter les données du formulaires ci celles-ci ont été reçues

    if (isset($_POST['submit'])) {
        if (
            !isset($_POST['nom'])
            || !isset($_POST['prenom'])
            || !isset($_POST['adresseDom'])
            || !isset($_POST['adresseTrv'])
            || !isset($_POST['numeroTelDom'])
            || !isset($_POST['numeroTelCel'])
            || !isset($_POST['numeroTelTrv'])
            || !isset($_POST['courrielPer'])
            || !isset($_POST['courrielPro'])
        ) {
            exit;
        }
        if (empty($_POST['nom']) || empty($_POST['prenom'])) {
            exit;
        }

        $nomForm = trim($_POST['nom']);
        $prenomForm = trim($_POST['prenom']);

        $adressesForm = [];
        $adressesForm['DOM'] = trim($_POST['adresseDom']);
        $adressesForm['TRV'] = trim($_POST['adresseTrv']);

        $numerosTelForm = [];
        $numerosTelForm['DOM'] = trim($_POST['numeroTelDom']);
        $numerosTelForm['CEL'] = trim($_POST['numeroTelCel']);
        $numerosTelForm['TRV'] = trim($_POST['numeroTelTrv']);

        $courrielsForm = [];
        $courrielsForm['PER'] = trim($_POST['courrielPer']);
        $courrielsForm['PRO']= trim($_POST['courrielPro']);

        // Mise à jour nom et prénom
        if ($contact['nom'] !== $_POST['nom'] || $contact['prenom'] !== $_POST['prenom']) {
            $requete = $bd->prepare('UPDATE contacts SET nom = ?, prenom = ? WHERE id = ?');
            $requete->execute([ $nomForm, $prenomForm, $idContact ]);
        }

        // Mise à jour des adresses
        $reponse = $bd->query('SELECT code FROM types_adresse');
        foreach ($reponse as $donneesTypeAdresse) {
            $typeAdresse = $donneesTypeAdresse['code'];

            // Si un champ de coordonnée a été vidé, supprimer la donnée.
            if (isset($adresses[$typeAdresse]) && empty($adressesForm[$typeAdresse])) {
                $requete = $bd->prepare("DELETE FROM adresses WHERE contact_id = ? AND type_adresse = '$typeAdresse'");
                $requete->execute([ $idContact ]);
            }
            // Si une coordonnée a été ajoutée, insérer la donnée.
            else if (!isset($adresses[$typeAdresse]) && !empty($adressesForm[$typeAdresse])) {
                $requete = $bd->prepare("INSERT INTO adresses (contact_id, type_adresse, adresse) VALUES (?, '$typeAdresse', ?)");
                $requete->execute([ $idContact, $adressesForm[$typeAdresse] ]);
            }
            // Si une coordonnée a été modifiée, mettre à jour la donnée.
            else if ($adresses[$typeAdresse] !== $adressesForm[$typeAdresse]) {
                $requete = $bd->prepare("UPDATE adresses SET adresse = ? WHERE contact_id = ? AND type_adresse = '$typeAdresse'");
                $requete->execute([ $adressesForm[$typeAdresse], $idContact ]);
            }
        }

        // Mises à jour des numéros de téléphone
        $reponse = $bd->query('SELECT code FROM types_numero_tel');
        foreach ($reponse as $donneesTypeNumeroTel) {
            $typeNumeroTel = $donneesTypeNumeroTel['code'];

            // Si un champ de coordonnée a été vidé, supprimer la donnée.
            if (isset($numerosTel[$typeNumeroTel]) && empty($numerosTelForm[$typeNumeroTel])) {
                $requete = $bd->prepare("DELETE FROM numeros_tel WHERE contact_id = ? AND type_numero_tel = '$typeNumeroTel'");
                $requete->execute([ $idContact ]);
            }
            // Si une coordonnée a été ajoutée, insérer la donnée.
            else if (!isset($numerosTel[$typeNumeroTel]) && !empty($numerosTelForm[$typeNumeroTel])) {
                $requete = $bd->prepare("INSERT INTO numeros_tel (contact_id, type_numero_tel, numero_tel) VALUES (?, '$typeNumeroTel', ?)");
                $requete->execute([ $idContact, $numerosTelForm[$typeNumeroTel] ]);
            }
            // Si une coordonnée a été modifiée, mettre à jour la donnée.
            else if ($numerosTel[$typeNumeroTel] !== $numerosTelForm[$typeNumeroTel]) {
                $requete = $bd->prepare("UPDATE numeros_tel SET numero_tel = ? WHERE contact_id = ? AND type_numero_tel = '$typeNumeroTel'");
                $requete->execute([ $numerosTelForm[$typeNumeroTel], $idContact ]);
            }
        }

        // Mises à jour des adresses courriel
        $reponse = $bd->query('SELECT code FROM types_courriel');
        foreach ($reponse as $donneesTypeCourriel) {
            $typeCourriel = $donneesTypeCourriel['code'];

            // Si un champ de coordonnée a été vidé, supprimer la donnée.
            if (isset($courriels[$typeCourriel]) && empty($courrielsForm[$typeCourriel])) {
                $requete = $bd->prepare("DELETE FROM courriels WHERE contact_id = ? AND type_courriel = '$typeCourriel'");
                $requete->execute([ $idContact ]);
            }
            // Si une coordonnée a été ajoutée, insérer la donnée.
            else if (!isset($courriels[$typeCourriel]) && !empty($courrielsForm[$typeCourriel])) {
                $requete = $bd->prepare("INSERT INTO courriels (contact_id, type_courriel, courriel) VALUES (?, '$typeCourriel', ?)");
                $requete->execute([ $idContact, $courrielsForm[$typeCourriel] ]);
            }
            // Si une coordonnée a été modifiée, mettre à jour la donnée.
            else if (isset($courriels[$typeCourriel]) && $courriels[$typeCourriel] !== $courrielsForm[$typeCourriel]) {
                $requete = $bd->prepare("UPDATE courriels SET courriel = ? WHERE contact_id = ? AND type_courriel = '$typeCourriel'");
                $requete->execute([ $courrielsForm[$typeCourriel], $idContact ]);
            }
        }

        /*
        Redirection vers l'affichage du contact modifié
        L'utilisation de la fonction `header` exige de la placer avant l'envoi de tout contenu au navigateur,
        incluant par des lignes de code HTML.
        Voir la documentation pour plus d'informations: https://www.php.net/manual/fr/function.header.php
        */
        $bd->commit(); // Appliquer les changements et fermer la transaction
        header("Location: ?page=afficher&id=$idContact");
        exit;

    }
    
    $bd->commit(); // Appliquer les changements et fermer la transaction

    function title($bd, $contact)
    {
        echo "Modifier « " . $contact['nom_complet'] . "» | Liste de contacts";
    }

    function body($bd, $contact)
    {
        /*
        Fait en sorte de pouvoir utiliser les variables `$numerosTel`, `$adresses` et `$courriels`
        déclarées plus haut (donc à l'extérieur de cette fonction).

        Il ne faut pas abuser des variables globales, dont l'utilisation est souvent considérée
        comme une mauvaise pratique. Nous améliorerons beaucoup l'organisation de notre code
        lorsque nous introduirons l'architecture MVC.
        */
        global $numerosTel, $adresses, $courriels;

        ?>
            <nav>
                <a href="?page=liste">Contacts</a>
                &nbsp;/&nbsp;
                <?= $contact['nom_complet'] ?>
            </nav>
            <h1><?= 'Modifier « ' . $contact['nom_complet'] . ' »' ?></h1>
            <p>
                <a href="?page=afficher&id=<?= $contact['id'] ?>">Annuler</a>
            </p>
            <main>
                <form method="POST">
                    <p>
                        <label for="nom_input">Nom:</label>
                        <input type="text" id="nom_input" name="nom" value="<?= $contact['nom'] ?>" required />
                    </p>
                    <p>
                        <label for="nom_input">Prénom:</label>
                        <input type="text" id="prenom_input" name="prenom" value="<?= $contact['prenom'] ?>" required />
                    </p>

                    <h2>Numéros de téléphone</h2>
                    <p>
                        <label for="numero_tel_dom_input">Domicile:</label>
                        <input type="text" id="numero_tel_dom_input" name="numeroTelDom" value="<?= isset($numerosTel['DOM']) ? $numerosTel['DOM'] : '' ?>" />
                    </p>
                    <p>
                        <label for="numero_tel_cel_input">Cellulaire:</label>
                        <input type="text" id="numero_tel_cel_input" name="numeroTelCel" value="<?= isset($numerosTel['CEL']) ? $numerosTel['CEL'] : '' ?>" />
                    </p>
                    <p>
                        <label for="numero_tel_trv_input">Travail:</label>
                        <input type="text" id="numero_tel_trv_input" name="numeroTelTrv" value="<?= isset($numerosTel['TRV']) ? $numerosTel['TRV'] : '' ?>" />
                    </p>

                    <h2>Adresses</h2>
                    <?php
                    ?>
                    <p>
                        <label for="adresse_dom_input">Domicile:</label>
                        <input type="text" id="adresse_dom_input" name="adresseDom" value="<?= isset($adresses['DOM']) ? $adresses['DOM'] : '' ?>" />
                    </p>
                    <p>
                        <label for="adresse_trv_input">Travail:</label>
                        <input type="text" id="adresse_trv_input" name="adresseTrv" value="<?= isset($adresses['TRV']) ? $adresses['TRV'] : '' ?>" />
                    </p>

                    <h2>Adresses courriel</h2>
                    <p>
                        <label for="courriel_per_input">Personnelle:</label>
                        <input type="text" id="courriel_per_input" name="courrielPer" value="<?= isset($courriels['PER']) ? $courriels['PER'] : '' ?>" />
                    </p>
                    <p>
                        <label for="courriel_pro_input">Professionnelle:</label>
                        <input type="text" id="courriel_pro_input" name="courrielPro" value="<?= isset($courriels['PRO']) ? $courriels['PRO'] : '' ?>" />
                    </p>

                    <input type="submit" name="submit" value="Soumettre" />
                </form>
            </main>
        <?php
    }
}
