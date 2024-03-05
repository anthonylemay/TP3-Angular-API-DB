<?php

require_once '../modeles/coordonnees.php';

class ControlleurCoordonnee {


    function obtenirCoordonneeParAuteurJSON($auteur_id) {
        $avis = modele_coordonnee::obtenirCoordonneeParAuteurJSON($auteur_id);
        echo json_encode($avis);
    }


}


?>