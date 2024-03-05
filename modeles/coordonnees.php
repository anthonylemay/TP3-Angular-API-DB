<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require_once "../include/config.php";

class modele_coordonnee {
   
    public $id;
    public $auteur_id;
    public $courriel;
    public $facebook;
    public $instagram;
    public $twitch;
    public $site_web;

    public function __construct($id, $auteur_id, $courriel, $facebook, $instagram, $twitch, $site_web) {
        $this->id = $id;
        $this->auteur_id = $auteur_id;
        $this->courriel = $courriel;
        $this->facebook = $facebook;
        $this->instagram = $instagram;
        $this->twitch = $twitch;
        $this->site_web = $site_web;
    }

    static function connecter(): mysqli {
        $mysqli = new mysqli(Db::$host, Db::$username, Db::$password, Db::$database);
        if ($mysqli->connect_errno) {
            echo "Échec de connexion à la base de données MySQL: " . $mysqli->connect_error;
            exit();
        }
        return $mysqli;
    }


    /**
     * Fonction pour avoir toutes les coordonnees d'un auteur
     */
    public static function obtenirCoordonneeParAuteurJSON($auteur_id) {
        $mysqli = self::connecter();
        $coordonnee = [];
        // Adjusted SQL query to select from the coordonnees table only
        if ($requete = $mysqli->prepare("SELECT * FROM coordonnees WHERE auteur_id = ?")) {
            $requete->bind_param("i", $auteur_id);
            $requete->execute();
            $result = $requete->get_result();
            while ($row = $result->fetch_assoc()) {
                $coordonnee[] = new modele_coordonnee(
                    $row['id'],
                    $row['auteur_id'],
                    $row['courriel'],
                    $row['facebook'],
                    $row['instagram'],
                    $row['twitch'],
                    $row['site_web']
                );
            }
            $requete->close();
        } else {
            echo "Erreur de préparation de la requête: " . $mysqli->error;
            return [];
        }
        return ['coordonnees' => $coordonnee];
    }

}

?>
