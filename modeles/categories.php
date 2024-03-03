<?php

require_once "../include/config.php";

class modele_categorie {

    public $id;
    public $nom;

    public function __construct($id, $nom) {
        $this->id = $id;
        $this->nom = $nom;
    }



    static function connecter() {
        $mysqli = new mysqli(Db::$host, Db::$username, Db::$password, Db::$database);

        if ($mysqli->connect_errno) {
            echo "Échec de connexion à la base de données MySQL: " . $mysqli->connect_error;
            exit();
        }

        return $mysqli;
    }


    public static function getAllCategories() {
        $mysqli = self::connecter();
        $query = "SELECT * FROM categories";
        $liste = [];

        if ($resultatRequete = $mysqli->query($query)) {
            while ($enregistrement = $resultatRequete->fetch_assoc()) {
                $categorie = [
                    'id' => (int) $enregistrement['id'],
                    'nom' => $enregistrement['nom']
                ];
                $liste[] = $categorie;
            }
        }   else {
            echo "Une erreur a été détectée dans la requête utilisée : " . $mysqli->error;
            return null;
        }
    
        return $liste;
    }
    
}

?>
