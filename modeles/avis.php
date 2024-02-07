<?php

require_once "../include/config.php";

class modele_avis {
    
    public $id;
    public $video_id;
    public $auteur_id;
    public $reaction;
    public $commentaire;
    public $note;
    public $date;

    public function __construct($id, $video_id, $auteur_id, $commentaire, $reaction, $note, $date) {
        $this->id = $id;
        $this->video_id = $video_id;
        $this->auteur_id = $auteur_id;
        $this->commentaire = $commentaire;
        $this->reaction = $reaction;
        $this->note = $note;
        $this->date = $date;
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
     * Fonction pour ajouter un commentaire à une vidéo
     */public static function ajouterAvis($video_id, $auteur_id, $commentaire, $reaction, $note, $date) {
        $mysqli = self::connecter();
        if ($requete = $mysqli->prepare("INSERT INTO avis (video_id, auteur_id, commentaire, reaction, note, date) VALUES (?, ?, ?, ?, ?, ?)")) {
            $requete->bind_param("iissis", $video_id, $auteur_id, $commentaire, $reaction, $note, $date);
            if ($requete->execute()) {
                return "Commentaire ajouté à la vidéo #$video_id.";
            } else {
                return "Erreur lors de l'ajout du commentaire: " . $requete->error;
            }
        } else {
            return "Erreur de préparation de la requête: " . $mysqli->error;
        }
    }
    /**
     * Fonction pour avoir tous les avis d'une vidéo
     */
    public static function obtenirAvisParVideo($video_id) {
        $mysqli = self::connecter();
        $avis = [];
        if ($requete = $mysqli->prepare("SELECT avis.id AS avis_id, avis.video_id, avis.auteur_id, avis.commentaire, avis.reaction, avis.note, avis.date, auteurs.nom, auteurs.pseudo, auteurs.verifie, auteurs.description, auteurs.url_pic, coordonnees.*
        FROM avis
        JOIN auteurs ON avis.auteur_id = auteurs.id
        JOIN coordonnees ON auteurs.id = coordonnees.auteur_id
        WHERE avis.video_id = ?"        
        )) {
            $requete->bind_param("i", $video_id);
            $requete->execute();
            $result = $requete->get_result();
            while ($row = $result->fetch_assoc()) {
                $avis[] = new modele_avis(
                    $row['avis_id'],
                    $row['video_id'],
                    $row['auteur_id'],
                    $row['commentaire'],
                    $row['reaction'],
                    $row['note'],
                    $row['date']
                );
            }
            $requete->close();
            return $avis;
        } else {
            echo "Erreur de préparation de la requête: " . $mysqli->error;
            return [];
        }
    }

    /**
     * Fonction pour modifier un avis
     */
    public static function modifierAvis($id, $commentaire, $reaction, $note, $date) {
        $mysqli = self::connecter();
        if ($requete = $mysqli->prepare("UPDATE avis SET commentaire = ?, reaction = ?, note = ?, date = ? WHERE id = ?")) {
            $requete->bind_param("ssisi", $commentaire, $reaction, $note, $date, $id);
            if ($requete->execute()) {
                return "Avis #$id mis à jour avec succès.";
            } else {
                return "Erreur lors de la mise à jour de l'avis: " . $requete->error;
            }
        } else {
            return "Erreur de préparation de la requête: " . $mysqli->error;
        }
    }

    /**
     * Fonction pour modifier une Réaction (like, dislike, null)
     */
    public static function modifierReaction($id, $reaction) {
        $mysqli = self::connecter();
        if ($requete = $mysqli->prepare("UPDATE avis SET reaction = ? WHERE id = ?")) {
            $requete->bind_param("si", $reaction, $id);
            if ($requete->execute()) {
                return "Réaction sur l'avis #$id mis à jour avec succès.";
            } else {
                return "Erreur lors de la mise à jour de la réaction sur l'avis: " . $requete->error;
            }
        } else {
            return "Erreur de préparation de la requête: " . $mysqli->error;
        }
    }

    public static function viderCommentaire($id) {
        $mysqli = self::connecter();
        if ($requete = $mysqli->prepare("UPDATE avis SET commentaire = NULL, note = NULL WHERE id = ?")) {
            $requete->bind_param("i", $id);
            if ($requete->execute()) {
                return "Commentaire et note vidés avec succès pour l'avis #$id.";
            } else {
                return "Erreur lors de la vidange du commentaire et de la note: " . $requete->error;
            }
        } else {
            return "Erreur de préparation de la requête: " . $mysqli->error;
        }
    }
    


    /**
     * Fonction pour modifier un commentaire / score
     */
    public static function modifierCommentaire($id, $commentaire, $note) {
        $mysqli = self::connecter();
        if ($requete = $mysqli->prepare("UPDATE avis SET commentaire = ?, note = ? WHERE id = ?")) {
            $requete->bind_param("sii", $commentaire, $note, $id);
            if ($requete->execute()) {
                return "Commentaire et note sur l'avis #$id mis à jour avec succès.";
            } else {
                return "Erreur lors de la mise à jour du commentaire et de la note sur l'avis: " . $requete->error;
            }
        } else {
            return "Erreur de préparation de la requête: " . $mysqli->error;
        }
    }


    /**
     * Fonction pour supprimer un commentaire
     */
    public static function supprimerAvis($id) {
        $mysqli = self::connecter();
        if ($requete = $mysqli->prepare("DELETE FROM avis WHERE id = ?")) {
            $requete->bind_param("i", $id);
            if ($requete->execute()) {
                return "Avis #$id supprimé avec succès.";
            } else {
                return "Erreur lors de la suppression du commentaire: " . $requete->error;
            }
        } else {
            return "Erreur de préparation de la requête: " . $mysqli->error;
        }
    }

}

?>
