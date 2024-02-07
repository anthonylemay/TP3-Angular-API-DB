<?php

require_once '../modeles/avis.php';

class ControlleurAvis {

    function ajouterAvisJSON($data) {
        $message = modele_avis::ajouterAvis($data['video_id'], $data['auteur_id'], $data['commentaire'], $data['reaction'], $data['note'], $data['date']);
        echo json_encode(['message' => $message]);
    }

    function obtenirAvisParVideoJSON($video_id) {
        $avis = modele_avis::obtenirTousAvisVideo($video_id);
        echo json_encode($avis);
    }

    function modifierAvisJSON($id, $data) {
        $message = modele_avis::modifierAvis($id, $data['commentaire'], $data['reaction'], $data['note']);
        echo json_encode(['message' => $message]);
    }

    function supprimerAvisJSON($id) {
        $message = modele_avis::supprimerAvis($id);
        echo json_encode(['message' => $message]);
    }
}

?>