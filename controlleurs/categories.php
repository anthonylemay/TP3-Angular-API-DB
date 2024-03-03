<?php

require_once '../modeles/categories.php';


class ControlleurCategorie {

    function afficherCategoriesJSON() {
        $categories = modele_categorie::getAllCategories();
        echo json_encode($categories);
    }
    

}
?>
