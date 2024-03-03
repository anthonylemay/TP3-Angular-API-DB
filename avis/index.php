<?php
ini_set('display_errors', 1); // Pour le débogage. À ajuster pour un environnement de production
error_reporting(E_ALL); // Pour le débogage. À ajuster pour un environnement de production

header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json, charset=utf-8');
    header("Access-Control-Allow-Methods: POST, DELETE, PUT, OPTIONS");

require_once '../controlleurs/avis.php';

$controlleurAvis = new ControlleurAvis();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        // Ajout d'un nouveau commentaire
        $data = json_decode(file_get_contents('php://input'), true);
        $controlleurAvis->ajouterAvisJSON($data);
        break;
    case 'GET':
        // Récupération des commentaires pour une vidéo spécifique
        if (isset($_GET['video_id'])) {
            $controlleurAvis->obtenirAvisParVideoJSON($_GET['video_id']);
        } else {
            echo json_encode(['message' => 'Paramètre video_id manquant.']);
        }
        break;
    case 'PUT':
        // Mise à jour d'un avis spécifique
        if (isset($_GET['id'])) {
            $data = json_decode(file_get_contents('php://input'), true);
            $controlleurAvis->modifierAvisJSON($_GET['id'], $data);
        } else {
            echo json_encode(['message' => 'Paramètre id manquant pour la requête (PUT).']);
        }
        break;
    case 'DELETE':
        // Suppression d'un avis spécifique
        if (isset($_GET['id'])) {
            $controlleurAvis->supprimerAvisJSON($_GET['id']);
        } else {
            echo json_encode(['message' => 'Paramètre id manquant pour la requête (DELETE).']);
        }
        break;

    case 'PATCH':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $data = json_decode(file_get_contents('php://input'), true);
            $controlleurAvis->patchAvisJSON($id, $data);
        } else {
            echo json_encode(['message' => 'Paramètre id manquant pour la mise à jour partielle (PATCH)).']);
        }
        break;

        default:
        echo json_encode(['message' => 'Méthode HTTP non prise en charge pour cette opération.']);
        break;

}

?>
