<?php

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

    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $adresseDom = trim($_POST['adresseDom']);
    $adresseTrv = trim($_POST['adresseTrv']);
    $numeroTelDom = trim($_POST['numeroTelDom']);
    $numeroTelCel = trim($_POST['numeroTelCel']);
    $numeroTelTrv = trim($_POST['numeroTelTrv']);
    $courrielPer = trim($_POST['courrielPer']);
    $courrielPro = trim($_POST['courrielPro']);

    /*
        On utilise une transaction pour s'assurer que les opérations sur la BD n'aient effet
        que si toutes les requêtes réussissent.
    */
    $bd->beginTransaction();

    $requete = $bd->prepare('INSERT INTO contacts(nom, prenom) VALUES(?, ?)');
    $requete->execute([ $nom, $prenom ]);

    $idContact = $bd->lastInsertId();

    if (!empty($adresseDom)) {
        $requete = $bd->prepare("INSERT INTO adresses(contact_id, type_adresse, adresse) VALUES($idContact, 'DOM', ?)");
        $requete->execute([ $adresseDom ]);
    }
    if (!empty($adresseTrv)) {
        $requete = $bd->prepare("INSERT INTO adresses(contact_id, type_adresse, adresse) VALUES($idContact, 'TRV', ?)");
        $requete->execute([ $adresseTrv ]);
    } 
    if (!empty($numeroTelDom)) {
        $requete = $bd->prepare("INSERT INTO numeros_tel(contact_id, type_numero_tel, numero_tel) VALUES($idContact, 'DOM', ?)");
        $requete->execute([ $numeroTelDom ]);
    }
    if (!empty($numeroTelCel)) {
        $requete = $bd->prepare("INSERT INTO numeros_tel(contact_id, type_numero_tel, numero_tel) VALUES($idContact, 'CEL', ?)");
        $requete->execute([ $numeroTelCel ]);
    }
    if (!empty($numeroTelTrv)) {
        $requete = $bd->prepare("INSERT INTO numeros_tel(contact_id, type_numero_tel, numero_tel) VALUES($idContact, 'TRV', ?)");
        $requete->execute([ $numeroTelTrv ]);
    }
    if (!empty($courrielPer)) {
        $requete = $bd->prepare("INSERT INTO courriels(contact_id, type_courriel, courriel) VALUES($idContact, 'PER', ?)");
        $requete->execute([ $courrielPer ]);
    }
    if (!empty($courrielPro)) {
        $requete = $bd->prepare("INSERT INTO courriels(contact_id, type_courriel, courriel) VALUES($idContact, 'PRO', ?)");
        $requete->execute([ $courrielPro ]);
    }
    
    $bd->commit(); // Fermer la transaction

    /*
        Redirection vers l'affichage du contact nouvellement créé
        L'utilisation de la fonction `header` exige de la placer avant l'envoi de tout contenu au navigateur,
        incluant par des lignes de code HTML.
        Voir la documentation pour plus d'informations: https://www.php.net/manual/fr/function.header.php
    */
    header("Location: ?page=afficher&id=$idContact");
    exit;
}

function title($bd, $contact)
{
    echo "Ajouter un contact";
}

function body($bd, $contact)
{
?>

<p>
    <nav>
        <a href="?page=liste">Contacts</a>
        &nbsp;/&nbsp;
        Ajouter un contact
    </nav>       
<h1>Ajouter un contact</h1>
<main>
    <form method="POST">
        <p>
            <label for="nom_input">Nom:</label>
            <input type="text" id="nom_input" name="nom" required />
        </p>
        <p>
            <label for="prenom_input">Prénom:</label>
            <input type="text" id="prenom_input" name="prenom" required />
        </p>

        <h2>Numéros de téléphone</h2>
        <p>
            <label label for="numero_tel_dom_input">Domicile:</label>
            <input type="text" id="numero_tel_dom_input" name="numeroTelDom" />
        </p>
        <p>
            <label for="numero_tel_cel_input">Cellulaire:</label>
            <input type="text" id="numero_tel_cel_input" name="numeroTelCel" />              
        </p>                          
        <p>
            <label for="numero_tel_trv_input">Travail:</label>
            <input type="text" id="numero_tel_trv_input" name="numeroTelTrv" />              
        </p>

        <h2>Adresses</h2>
        <p>
            <label for="adresse_dom_input">Domicile:</label>
            <input type="text" id="adresse_dom_input" name="adresseDom" />
        </p>              
        <p>
            <label for="adresse_trv_input">Travail:</label>
            <input type="text" id="adresse_trv_input" name="adresseTrv" />              
        </p>

        <h2>Adresses courriel</h2>
        <p>
            <label for="courriel_per_input">Personnelle:</label>
            <input type="text" id="courriel_per_input" name="courrielPer" />
        </p>
        <p>
            <label for="courriel_pro_input">Professionnelle:</label>
            <input type="text" id="courriel_pro_input" name="courrielPro" />
        </p>

        <input type="submit" name="submit" value="Soumettre" />
    </form>
</main>

<?php
}
