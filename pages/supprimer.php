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
    On exécute une requête `DELETE` avec l'ID du contact à supprimer.
    */
    $requete = $bd->prepare('DELETE FROM contacts WHERE id = ?');
    $requete->execute([ $contact['id'] ]);
  
    /* On redirige ensuite l'utilisateur vers la liste de contacts. */
    header('Location: ?page=liste');
}

?>
