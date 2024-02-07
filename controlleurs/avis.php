<?php

require_once '../modeles/avis.php';

class ControlleurAvis {

    function ajouterAvisJSON($data) {
        $message = modele_avis::ajouterAvis($data['video_id'], $data['auteur_id'], $data['commentaire'], $data['reaction'], $data['note'], $data['date']);
        echo json_encode(['message' => $message]);
    }

    function obtenirAvisParVideoJSON($video_id) {
        $avis = modele_avis::obtenirAvisParVideo($video_id);
        echo json_encode($avis);
    }

    function modifierAvisJSON($id, $data) {
        $message = modele_avis::modifierAvis($id, $data['commentaire'], $data['reaction'], $data['note'], $data['date']);
        echo json_encode(['message' => $message]);
    }

    function patchAvisJSON($id, $data) { // S'assurer dans le cas ici que si je gère des empty strings, qu'il y ait des condiitons d'affichage dans mon interface. Only note = 1 affichage, only comms = 1 affichage, les deux, stack them dans le même div.
        $messages = [];
        
        if (isset($data['reaction'])) {
            $messages['reaction'] = modele_avis::modifierReaction($id, $data['reaction']); // modification de la réaction uniquement.
        }
            // Handle the modification or clearing of comments and notes.
        if (isset($data['viderCommentaire']) && $data['viderCommentaire'] === true) {
            // Supprimer le commentaire en vidant les affichages.
            $messages['viderCommentaire'] = modele_avis::viderCommentaire($id);
        } else{
            if (isset($data['commentaire']) || isset($data['note'])) {
                $commentaire = $data['commentaire'] ?? null; // modification du commentaire ou laisser tel quel si non changé.
                $note = $data['note'] ?? null; // modification du commentaire ou laisser tel quel si non changé.
                $messages['commentaire'] = modele_avis::modifierCommentaire($id, $commentaire, $note);
                }
            }
    
        echo json_encode(['messages' => $messages]);
    }


    function modifierCommentaireJSON($id, $data) {
        $message = modele_avis::modifierCommentaire($id, $data['commentaire'], $data['note']);
        echo json_encode(['message' => $message]);
    }

    function modifierReactionJSON($id, $data) {
        $message = modele_avis::modifierReaction($id, $data['reaction']);
        echo json_encode(['message' => $message]);
    }

    function supprimerAvisJSON($id) {
        $message = modele_avis::supprimerAvis($id);
        echo json_encode(['message' => $message]);
    }


}


?>