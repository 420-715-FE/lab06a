<?php

/*
Envoie un code d'erreur 404 au navigateur.
Cela signifie que la page demandée n'a pas été trouvée.
La fonction `http_response_code` doit être appelée avant tout envoi de contenu au navigateur.
*/
http_response_code(404);

function title() {
    echo "Page introuvable";
}

function body() {
    ?>
    <h1>Page introuvable</h1>
    <p>
        La page que vous cherchez n'existe pas ou a été supprimée.
        Vous pouvez retourner à la <a href="index.php">page d'accueil</a>.
    </p>
    <?php
}

?>
