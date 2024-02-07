<?php

require_once "./include/config.php";

class modele_video {
    public $id;
    public $url_img;
    public $nom;
    public $description;
    public $code;
    public $categorie_id;
    public $auteur_id;
    public $date;
    public $duree;
    public $vues;
    public $score;
    public $closedcaption;
    public $subtitle;

    /**
     * Fonction permettant de construire un type d'objet  modele_video
     */
    public function __construct($id, $url_img, $nom, $description, $code, $categorie_id, $auteur_id, $date, $duree, $vues, $score, $closedcaption, $subtitle) {
        $this->id = $id;
        $this->url_img = $url_img;
        $this->nom = $nom;
        $this->description = $description;
        $this->code = $code;
        $this->categorie_id = $categorie_id;
        $this->auteur_id = $auteur_id;
        $this->date = $date;
        $this->duree = $duree;
        $this->vues = $vues;
        $this->score = $score;
        $this->closedcaption = $closedcaption;
        $this->subtitle = $subtitle;
    }

    /**
     * Fonction permettant de se connecter à la base de données
     */
    static function connecter() {
        $mysqli = new mysqli(Db::$host, Db::$username, Db::$password, Db::$database);

        if ($mysqli->connect_errno) {
            echo "Échec de connexion à la base de données MySQL: " . $mysqli->connect_error;
            exit();
        }

        return $mysqli;
    }

    /**
     * Fonction permettant de récupérer l'ensemble des vidéos
     */
    public static function ObtenirTous() {
        $mysqli = self::connecter();
        $liste = [];
    
        // Va chercher toute l'info sur la vidéo, l'auteur et ses coorodnnées, ainsi que les avis par vidéo.
        $query = "
            SELECT 
                videos.*, 
                auteurs.nom AS auteur_nom, 
                auteurs.pseudo, 
                auteurs.verifie, 
                auteurs.description AS auteur_description, 
                auteurs.url_pic, 
                coordonnees.courriel, 
                coordonnees.facebook, 
                coordonnees.instagram, 
                coordonnees.twitch, 
                coordonnees.site_web 
            FROM videos
            JOIN auteurs ON videos.auteur_id = auteurs.id
            LEFT JOIN coordonnees ON auteurs.id = coordonnees.auteur_id
        ";
        
        if ($resultatRequete = $mysqli->query($query)) {
            while ($enregistrement = $resultatRequete->fetch_assoc()) {
                $video = [
                    'id' => $enregistrement['id'],
                    'url_img' => $enregistrement['url_img'],
                    'nom' => $enregistrement['nom'],
                    'description' => $enregistrement['description'],
                    'code' => $enregistrement['code'],
                    'categorie_id' => $enregistrement['categorie_id'],
                    'auteur' => [
                        'nom' => $enregistrement['auteur_nom'],
                        'pseudo' => $enregistrement['pseudo'],
                        'verifie' => (bool) $enregistrement['verifie'],
                        'description' => $enregistrement['auteur_description'],
                        'url_pic' => $enregistrement['url_pic'],
                        'coordonnees' => [
                            'courriel' => $enregistrement['courriel'],
                            'facebook' => $enregistrement['facebook'],
                            'instagram' => $enregistrement['instagram'],
                            'twitch' => $enregistrement['twitch'],
                            'site_web' => $enregistrement['site_web'],
                        ],
                    ],
                    'date' => $enregistrement['date'],
                    'duree' => $enregistrement['duree'],
                    'vues' => $enregistrement['vues'],
                    'score' => $enregistrement['score'],
                    'closedcaption' => $enregistrement['closedcaption'],
                    'subtitle' => $enregistrement['subtitle'],
                    // Aller chercher tous les avis reliés à chaque vidéo
                    'avis' => self::ObtenirAvisVideo($enregistrement['id']),
                ];
                $liste[] = $video;
            }
        } else {
            echo "Une erreur a été détectée dans la requête utilisée : " . $mysqli->error;
            return null;
        }
    
        return $liste;
    }
    

    /***
     * Fonction permettant de récupérer une vidéo en fonction de son identifiant unique (id).
     */
    public static function ObtenirUn($id) {
        $mysqli = self::connecter();
        $video = null;
    
        // Commande SQL pour aller chercher l'info de l'auteur
        if ($requete = $mysqli->prepare("
            SELECT 
                videos.*, 
                auteurs.nom AS auteur_nom, 
                auteurs.pseudo, 
                auteurs.verifie, 
                auteurs.description AS auteur_description, 
                auteurs.url_pic, 
                coordonnees.courriel, 
                coordonnees.facebook, 
                coordonnees.instagram, 
                coordonnees.twitch, 
                coordonnees.site_web 
            FROM videos
            JOIN auteurs ON videos.auteur_id = auteurs.id
            LEFT JOIN coordonnees ON auteurs.id = coordonnees.auteur_id 
            WHERE videos.id=?
        ")) { //requête du id de la vidéo.
            $requete->bind_param("i", $id);
            $requete->execute();
            $result = $requete->get_result();
    
            if ($enregistrement = $result->fetch_assoc()) {
                //Compilation des données avec l'ajout détaillé de l'auteur
                $video = [
                    'id' => $enregistrement['id'],
                    'url_img' => $enregistrement['url_img'],
                    'nom' => $enregistrement['nom'],
                    'description' => $enregistrement['description'],
                    'code' => $enregistrement['code'],
                    'categorie_id' => $enregistrement['categorie_id'],
                    'auteur' => [ 
                        'nom' => $enregistrement['auteur_nom'],
                        'pseudo' => $enregistrement['pseudo'],
                        'verifie' => (bool) $enregistrement['verifie'],
                        'description' => $enregistrement['auteur_description'],
                        'url_pic' => $enregistrement['url_pic'],
                        'coordonnees' => [
                            'courriel' => $enregistrement['courriel'],
                            'facebook' => $enregistrement['facebook'],
                            'instagram' => $enregistrement['instagram'],
                            'twitch' => $enregistrement['twitch'],
                            'site_web' => $enregistrement['site_web'],
                        ],
                    ],
                    'date' => $enregistrement['date'],
                    'duree' => $enregistrement['duree'],
                    'vues' => $enregistrement['vues'],
                    'score' => $enregistrement['score'],
                    'closedcaption' => $enregistrement['closedcaption'],
                    'subtitle' => $enregistrement['subtitle'],
                    // Aller chercher tous les avis pour la vidéo
                    'avis' => self::ObtenirAvisVideo($enregistrement['id']),
                ];
            }
            $requete->close();
        } else {
            echo "Une erreur a été détectée dans la requête utilisée : " . $mysqli->error;
            return null;
        }
    
        return $video;
    }
    
    
    // Fonction pour aller chercher le ID d'une vidéo et prendre tous ses avis.
    private static function ObtenirAvisVideo($videoId) {
        $mysqli = self::connecter();
        $avis = [];
        if ($requete = $mysqli->prepare("
            SELECT 
                avis.*, 
                auteurs.nom AS auteur_nom, 
                auteurs.pseudo, 
                auteurs.verifie, 
                auteurs.description AS auteur_description, 
                auteurs.url_pic, 
                coordonnees.courriel, 
                coordonnees.facebook, 
                coordonnees.instagram, 
                coordonnees.twitch, 
                coordonnees.site_web 
            FROM avis 
            JOIN auteurs ON avis.auteur_id = auteurs.id 
            LEFT JOIN coordonnees ON auteurs.id = coordonnees.auteur_id 
            WHERE video_id=?
        ")) {
            $requete->bind_param("i", $videoId);
            $requete->execute();
            $result = $requete->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $avis[] = [
                    'auteur' => [
                        'nom' => $row['auteur_nom'],
                        'pseudo' => $row['pseudo'],
                        'verifie' => (bool) $row['verifie'],
                        'description' => $row['auteur_description'],
                        'url_pic' => $row['url_pic'],
                        'coordonnees' => [
                            'courriel' => $row['courriel'],
                            'facebook' => $row['facebook'],
                            'instagram' => $row['instagram'],
                            'twitch' => $row['twitch'],
                            'site_web' => $row['site_web'],
                        ],
                    ],
                    'note' => $row['note'],
                    'commentaires' => $row['commentaire'],
                    'reaction' => $row['reaction'],
                    'date' => $row['date'],
                ];
            }
            $requete->close();
        }
        return $avis;
    }
    
    
    
    /***
     * Fonction permettant d'ajouter une vidéo
     */

    public static function ajouter($url_img, $nom, $description, $code, $categorie_id, $auteur_id, $date, $duree, $vues, $score, $closedcaption, $subtitle) {
        $mysqli = self::connecter();
        $message = '';

        if ($requete = $mysqli->prepare("INSERT INTO videos (url_img, nom, description, code, categorie_id, auteur_id, date, duree, vues, score, closedcaption, subtitle) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {

        /************************* ATTENTION **************************/
        /* On ne fait présentement peu de validation des données.     */
        /**************************************************************/

            $requete->bind_param("ssssiisiiiii", $url_img, $nom, $description, $code, $categorie_id, $auteur_id, $date, $duree, $vues, $score, $closedcaption, $subtitle);
        

            if ($requete->execute()) {
                $message = "Vidéo ajoutée avec succès.";
            } else {
                $message = "Une erreur est survenue lors de l'ajout: " . $requete->error;
            }

            $requete->close();
        } else {
            $message = "Une erreur a été détectée dans la requête utilisée : " . $mysqli->error;
            exit();
        }

        return $message;
    }


    /**
     * Updates an existing video in the database.
     */
    public static function modifier($id, $url_img, $nom, $description, $code, $categorie_id, $auteur_id, $date, $duree, $vues, $score, $closedcaption, $subtitle) {
        $mysqli = self::connecter();
        $message = '';

        if ($requete = $mysqli->prepare("UPDATE videos SET url_img=?, nom=?, description=?, code=?, categorie_id=?, auteur_id=?, date=?, duree=?, vues=?, score=?, closedcaption=?, subtitle=? WHERE id=?")) {
           
        /************************* ATTENTION **************************/
        /* On ne fait présentement peu de validation des données.     */
        /**************************************************************/
           
            $requete->bind_param("ssssiisiiiiii", $url_img, $nom, $description, $code, $categorie_id, $auteur_id, $date, $duree, $vues, $score, $closedcaption, $subtitle, $id);

            if ($requete->execute()) {
                $message = "Vidéo mise à jour avec succès.";
            } else {
                $message = "Une erreur est survenue lors de l'édition: " . $requete->error;
            }

            $requete->close();
        } else {
            $message = "Une erreur a été détectée dans la requête utilisée : " . $mysqli->error;
            exit();
        }

        return $message;
    }


    /**
     * Fonction permettant de supprimer une vidéo.
     */
    public static function supprimer($id) {
        $mysqli = self::connecter();
        $message = '';
        
        // Tentative de suppression de la vidéo
        if ($requete = $mysqli->prepare("DELETE FROM videos WHERE id=?")) {
            $requete->bind_param("i", $id);
            $requete->execute();
            
            // Vérifier si des lignes ont été affectées par la requête de suppression
            if ($requete->affected_rows > 0) {
                $message = "Vidéo supprimée avec succès.";
            } else {
                // Si aucune ligne n'est affectée, cela signifie que l'ID n'existait pas
                $message = "Vidéo non trouvée. Validez le ID et réessayer.";
            }
            
            $requete->close();
        } else {
            $message = "Une erreur a été détectée dans la requête utilisée : " . $mysqli->error;
        }
        
        return $message;
    }
    


}

?>
