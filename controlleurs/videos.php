<?php

require_once 'modeles/videos.php';

class ControlleurVideo {

    function afficherListeJSON() {
        $videos = modele_video::ObtenirTous();
        echo json_encode($videos);
    }

    function afficherFicheJSON() {
        if (isset($_GET["id"])) {
            $video = modele_video::ObtenirUn($_GET["id"]);
            if ($video) {
                echo json_encode($video);
            } else {
                $erreur = "Aucune vidéo trouvée";
            }
        } else {
            $erreur = "L'identifiant (id) de la vidéo à afficher est manquant dans l'url";
        }
    }

    function ajouterJSON($data) {
        $resultat = new stdClass();
        if (isset($data['url_img']) &&
            isset($data['nom']) &&
            isset($data['description']) &&
            isset($data['code']) &&
            isset($data['categorie_id']) &&
            isset($data['auteur_id']) &&
            isset($data['date']) &&
            isset($data['duree']) &&
            isset($data['vues']) &&
            isset($data['score']) &&
            isset($data['closedcaption']) &&
            isset($data['subtitle'])) {
            $resultat->message = modele_video::ajouter(
                $data['url_img'],
                $data['nom'],
                $data['description'],
                $data['code'],
                $data['categorie_id'],
                $data['auteur_id'],
                $data['date'],
                $data['duree'],
                $data['vues'],
                $data['score'],
                $data['closedcaption'],
                $data['subtitle']
            );
        } else {
            
            $resultat->message = "Impossible d'ajouter une vidéo. Des informations sont manquantes ou erronnées. Veuillez valider et réessayer.";
        }
        echo json_encode($resultat);
    }

    function modifierJSON($data) {
        $resultat = new stdClass();
        if (isset($_GET['id']) &&
            isset($data['url_img']) &&
            isset($data['nom']) &&
            isset($data['description']) &&
            isset($data['code']) &&
            isset($data['categorie_id']) &&
            isset($data['auteur_id']) &&
            isset($data['date']) &&
            isset($data['duree']) &&
            isset($data['vues']) &&
            isset($data['score']) &&
            isset($data['closedcaption']) &&
            isset($data['subtitle'])) {
            $resultat->message = modele_video::modifier(
                $_GET['id'],
                $data['url_img'],
                $data['nom'],
                $data['description'],
                $data['code'],
                $data['categorie_id'],
                $data['auteur_id'],
                $data['date'],
                $data['duree'],
                $data['vues'],
                $data['score'],
                $data['closedcaption'],
                $data['subtitle']
            );
        } else {
            $resultat->message = "Impossible de modifier la vidéo. Des informations sont manquantes ou erronnées. Veuillez valider et réessayer.";
        }
        echo json_encode($resultat);
    }

    function supprimerJSON($id) {
        $resultat = new stdClass();
        $resultat->message = modele_video::supprimer($id);
        echo json_encode($resultat);
    }
}

?>
