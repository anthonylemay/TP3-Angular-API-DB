<?php
ini_set('display_errors', 1); // Pour le débogage. À ajuster pour un environnement de production
error_reporting(E_ALL); // Pour le débogage. À ajuster pour un environnement de production

header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json, charset=utf-8');
header("Access-Control-Allow-Methods: POST, DELETE, PUT, OPTIONS");

require_once '../controlleurs/coordonnees.php';

$controlleurCoordonnee = new ControlleurCoordonnee();

switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':
        // Récupération des commentaires pour une vidéo spécifique
        if (isset($_GET['auteur_id'])) {
            $controlleurCoordonnee->obtenirCoordonneeParAuteurJSON($_GET['auteur_id']);
        } else {
            echo json_encode(['message' => 'Paramètre auteur_id manquant.']);
        }
        break;
}

?>
