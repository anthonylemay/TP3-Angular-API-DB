<?php
ini_set('display_errors', 1); // Pour le débogage. À ajuster pour un environnement de production
error_reporting(E_ALL); // Pour le débogage. À ajuster pour un environnement de production

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Note : À ajuster pour un environnement de production

require_once '../controlleurs/categories.php';


$controlleurCategorie = new ControlleurCategorie();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Récupération des commentaires pour toutes les catégories
        $controlleurCategorie->afficherCategoriesJSON();
        break;
   

        default:
        echo json_encode(['message' => 'Méthode HTTP non prise en charge pour cette opération.']);
        break;

}

?>



