<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
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
     */
    public static function ajouterAvis($video_id, $auteur_id, $commentaire, $reaction, $note, $date) {
        $mysqli = self::connecter();
        if ($requete = $mysqli->prepare("INSERT INTO avis (video_id, auteur_id, commentaire, reaction, note, date) VALUES (?, ?, ?, ?, ?, ?)")) {
            $requete->bind_param("iissis", $video_id, $auteur_id, $commentaire, $reaction, $note, $date);
            if ($requete->execute()) {
                    // Ajuster le score en conséquence:
                $ajustement = 0;  // par défault - 0 si null
                if ($reaction === 'like') {
                    $ajustement = 100; // ajuste le score à +100
                } elseif ($reaction === 'dislike') {
                    $ajustement = -100; // ajuste le score à -100
                }
    
                // Appel ajusterScoreVideo si nécessaire
                if ($ajustement !== 0) {
                    self::ajusterScoreVideo($video_id, $ajustement);
                }
                
                return "Commentaire ajouté à la vidéo #$video_id" . ($ajustement !== 0 ? ", avec ajustement de score de $ajustement" : ".");;
            } else {
                return "Erreur lors de l'ajout du commentaire: " . $requete->error;
            }
        } else {
            return "Erreur de préparation de la requête: " . $mysqli->error;
        }
    }
    
    /**
     * Fonction pour ajuster le score d'une vidéo.
     * 
     * @param int $videoId Le ID de la vidéo à ajuster.
     * @param int $ajustement Le montant d'ajustement de la vidéo, qui peut être positif ou négatif.
     */
    public static function ajusterScoreVideo($videoId, $ajustement) {
        $mysqli = self::connecter();

        try {
            if ($requete = $mysqli->prepare("UPDATE videos SET score = score + ? WHERE id = ?")) {
                $requete->bind_param("ii", $ajustement, $videoId);
                if (!$requete->execute()) {
                    return "Erreur lors de la mise à jour du score de la vidéo: " . $requete->error;
                }
                $requete->close();
                return "Score de la vidéo #$videoId ajusté avec succès.";
            } else {
                return "Erreur de préparation de la requête d'ajustement de score: " . $mysqli->error;
            }
        } catch (Exception $e) {
            return $e->getMessage(); // Return error message
        }
    }


    /**
     * Fonction pour avoir tous les avis d'une vidéo
     */
    public static function obtenirAvisParVideo($video_id) {
        $mysqli = self::connecter();
        $avis = [];
        // aller chercher le score de vidéo:
        $videoScore = null;
        if ($scoreRequete = $mysqli->prepare("SELECT score FROM videos WHERE id = ?")) {
            $scoreRequete->bind_param("i", $video_id);
            if ($scoreRequete->execute()) {
                $result = $scoreRequete->get_result();
                if ($row = $result->fetch_assoc()) {
                    $videoScore = $row['score'];
                }
                $scoreRequete->close();
            }
        }
        // aller chercher les commentaires:
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
        } else {
            echo "Erreur de préparation de la requête: " . $mysqli->error;
            return [];
        }
        return ['avis' => $avis, 'score' => $videoScore];
    }

    /**
     * Fonction pour modifier un avis
     */
    public static function modifierAvis($id, $commentaire, $reaction, $note, $date) {
        $mysqli = self::connecter();
        // Aller chercher la réaction actuelle + video_id
        $video_id = null;
        $presentReaction = null;
        if ($fetchRequest = $mysqli->prepare("SELECT reaction, video_id FROM avis WHERE id = ?")) {
            $fetchRequest->bind_param("i", $id);
            if ($fetchRequest->execute()) {
                $result = $fetchRequest->get_result();
                if ($row = $result->fetch_assoc()) {
                    $presentReaction = $row['reaction'];
                    $video_id = $row['video_id'];
                }
                $fetchRequest->close();
            }
        }
        
        // Mettre à jour l'avis si nécessaire.
        if ($requete = $mysqli->prepare("UPDATE avis SET commentaire = ?, reaction = ?, note = ?, date = ? WHERE id = ?")) {
            $requete->bind_param("ssisi", $commentaire, $reaction, $note, $date, $id);
            if ($requete->execute()) {
                // Mettre à jour le score versus la réaction si nécessaire.
                $ajustement = 0;
                if ($presentReaction !== $reaction) {
                    if ($reaction === 'like') { // si c'est devenu un like.
                        $ajustement = 100;
                    } elseif ($reaction === 'dislike') {  // si c'est devenu un dislike
                        $ajustement = -100;
                    } elseif ($presentReaction === 'like') {
                        $ajustement = -100; // Si c'était un like, mais c'Est devenu null
                    } elseif ($presentReaction === 'dislike') {
                        $ajustement = 100;  // Si c'était un dislike, mais c'est devenu null
                    }
                    
                    // Ajuster le score s'il y a un changement
                    if ($ajustement !== 0) {
                        self::ajusterScoreVideo($video_id, $ajustement);
                    }
                }
    
                return "Avis #$id de la vidéo #$video_id modifié avec succès" . ($ajustement !== 0 ? ", avec ajustement de score de $ajustement" : ".");
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

    public static function modifierReaction($id, $newReaction) {
        $mysqli = self::connecter();
        // Obtenir la réaction actuelle et le video_id
        $video_id = null;
        $presentReaction = null;
        if ($fetchRequest = $mysqli->prepare("SELECT reaction, video_id FROM avis WHERE id = ?")) {
            $fetchRequest->bind_param("i", $id);
            if ($fetchRequest->execute()) {
                $result = $fetchRequest->get_result();
                if ($row = $result->fetch_assoc()) {
                    $presentReaction = $row['reaction'];
                    $video_id = $row['video_id'];
                }
                $fetchRequest->close();
            }
        }
    
        // Déterminer l'ajustement
        $ajustement = 0;
        if ($presentReaction === 'like' && $newReaction !== 'like') {
            $ajustement -= 100;
        } elseif ($presentReaction === 'dislike' && $newReaction !== 'dislike') {
            $ajustement += 100;
        }
        if ($newReaction === 'like' && $presentReaction !== 'like') {
            $ajustement += 100;
        } elseif ($newReaction === 'dislike' && $presentReaction !== 'dislike') {
            $ajustement -= 100;
        }
    
        // Mettre à jour la réaction en cas de changement :
        if ($presentReaction !== $newReaction) {
            if ($updateRequest = $mysqli->prepare("UPDATE avis SET reaction = ? WHERE id = ?")) {
                $updateRequest->bind_param("si", $newReaction, $id);
                if ($updateRequest->execute()) {
                    // Ajuster le score si nécessaire
                    if ($ajustement !== 0) {
                        self::ajusterScoreVideo($video_id, $ajustement);
                    }
                    return "Réaction sur l'avis #$id mise à jour" . ($ajustement !== 0 ? ", avec ajustement de score de $ajustement." : ".");
                } else {
                    return "Erreur lors de la mise à jour de la réaction sur l'avis: " . $updateRequest->error;
                }
            } else {
                return "Erreur de préparation de la requête: " . $mysqli->error;
            }
        } else {
            return "Aucun changement de réaction détecté pour l'avis #$id.";
        }
    }
    
    public static function viderCommentaire($id) {
        $mysqli = self::connecter();
        if ($requete = $mysqli->prepare("UPDATE avis SET commentaire = NULL, note = NULL WHERE id = ?")) {
            $requete->bind_param("i", $id);
            if ($requete->execute()) {
                return "Commentaire et note vidés avec succès pour l'avis #$id.";
            } else {
                return "Erreur lors du vidage du commentaire et de la note: " . $requete->error;
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
        // Va chercher la réaction actuelle avant de tout supprimer, pour ajuster le score de vidéo.
        $video_id = null;
        $presentReaction = null;
        if ($fetchRequest = $mysqli->prepare("SELECT reaction, video_id FROM avis WHERE id = ?")) {
            $fetchRequest->bind_param("i", $id);
            if ($fetchRequest->execute()) {
                $result = $fetchRequest->get_result();
                if ($row = $result->fetch_assoc()) {
                    $presentReaction = $row['reaction'];
                    $video_id = $row['video_id'];
                }
                $fetchRequest->close();
            }
        }
    
        // Détermine l'ajustement de score nécéssaire.
        $ajustement = 0;
        if ($presentReaction === 'like') {
            $ajustement = -100;
        } elseif ($presentReaction === 'dislike') {
            $ajustement = 100;
        }
        // Supprimer l'avis
        if ($requete = $mysqli->prepare("DELETE FROM avis WHERE id = ?")) {
            $requete->bind_param("i", $id);
            if ($requete->execute()) {
                // Ajuster le score si nécessaire
                if ($ajustement !== 0) {
                    self::ajusterScoreVideo($video_id, $ajustement);
                }
                return "Avis #$id supprimé"  . ($ajustement !== 0 ? ", avec ajustement de score de $ajustement." : ".");
            } else {
                return "Erreur lors de la suppression de l'avis: " . $requete->error;
            }
        } else {
            return "Erreur de préparation de la requête: " . $mysqli->error;
        }
    }
    

}

?>
