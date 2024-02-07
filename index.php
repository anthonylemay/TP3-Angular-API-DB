<?php
ini_set('display_errors', 1); // debug - enlever au déploiement réel
error_reporting(E_ALL); // debug - enlever au déploiement réel

    header('Content-Type: application/json;');
    header('Access-Control-Allow-Origin: *'); // Permet de faire n'importe quel action dans l'API. zéro sécuritaire en vrai environnement live.
    require_once 'controlleurs/videos.php';
    $controlleurVideo = new ControlleurVideo;

    switch($_SERVER['REQUEST_METHOD']){
        case 'GET': // Gestion des demandes de type get
        if (isset($_GET['id'])){ // récupère l'enregistrement correspondant à l'identifiant passé en paramètre
            $controlleurVideo->afficherFicheJSON($_GET['id']); }
        else{
            $controlleurVideo->afficherListeJSON();
        }
        break;
        case 'POST': // Pour ajouter des enregistrements au dB
            $corpsJSON = file_get_contents('php://input');
            $data = json_decode($corpsJSON, TRUE);
            error_log("Received date: " . $data['date']);
            $controlleurVideo->ajouterJSON($data);
            break;

        case 'PUT': // Mise à jour d'enregistrement à l'identifiant passé en paramètre.
        if (isset($_GET['id'])){
            $corpsJSON = file_get_contents('php://input');
            $data = json_decode($corpsJSON, TRUE);
            $controlleurVideo->modifierJSON($data);
        } else {
            echo "Erreur : ID manquant";
        }
        break;
        case 'DELETE': // code qui permet de supprimer l'enregistrement correspondant à l'identifiant en paramètre.
            if (isset($_GET['id'])) {
                $controlleurVideo->supprimerJSON($_GET['id']);
            } else {
                echo "Erreur : ID non trouvé. Veuillez valider votre requête.";
            }
            break;
        
            default:
     
         }

?>