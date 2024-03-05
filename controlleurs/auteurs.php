<?php

require_once '../modeles/auteurs.php';


class ControlleurAuteur {

    function afficherAuteursJSON() {
        $auteurs = modele_auteur::getAllAuteurs();
        echo json_encode($auteurs);
    }
    

}
?>
