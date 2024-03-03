<?php

ini_set('display_errors', 1); // For debugging, adjust for production environment
error_reporting(E_ALL); // For debugging, adjust for production environment

header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json, charset=utf-8');
header("Access-Control-Allow-Methods: POST, DELETE, PUT, GET, OPTIONS");

require_once '../controlleurs/categories.php';

// Handle OPTIONS request method explicitly for CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // No further action is needed, just respond with success status
    http_response_code(200);
    exit();
}

$controlleurCategorie = new ControlleurCategorie();

switch ($_SERVER['REQUEST_METHOD']) {
    
    case 'GET':
        // Fetching categories
        $controlleurCategorie->afficherCategoriesJSON();
        break;
    default:
        echo json_encode(['message' => 'HTTP method not supported for this operation.']);
        break;
}

?>



