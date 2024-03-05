<?php

require_once "../include/config.php";

class modele_auteur {

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


    public static function getAllAuteurs() {
        $mysqli = self::connecter();
        $query = "SELECT * FROM auteurs";
        $liste = [];

        if ($resultatRequete = $mysqli->query($query)) {
            while ($enregistrement = $resultatRequete->fetch_assoc()) {
                $auteur = [
                    'id' => (int) $enregistrement['id'],
                    'url_pic' => $enregistrement['url_pic'],
                    'nom' => $enregistrement['nom'],
                    'pseudo' => $enregistrement['pseudo'],
                    'verifie' => (int) $enregistrement['verifie'],
                    'description' => $enregistrement['description']
                ];
                $liste[] = $auteur;
            }
        }   else {
            echo "Une erreur a été détectée dans la requête utilisée : " . $mysqli->error;
            return null;
        }
    
        return $liste;
    }
    
}

?>
