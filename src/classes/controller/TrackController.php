<?php

namespace iutnc\deefy\controller;

use iutnc\deefy\repository\DeefyRepository;

class TrackController extends BaseController {

    public function execute(): string {
        if ($this->http_method == "GET") {
            return $this->addTrack();
        } else {
            return $this->displayCurrentPlaylist();
        }
    }

    public function addTrack(): string {
        if (!isset($_SESSION['current_playlist'])) {
            return "Aucune playlist sélectionnée.";
        }

        if ($this->http_method == "GET") {
            return '<form method="post" action="?action=add-track">
                <input type="text" name="titre" placeholder="Titre" required>
                <input type="text" name="genre" placeholder="Genre">
                <input type="number" name="duree" placeholder="Durée" required>
                <input type="submit" value="Ajouter">
                </form>';
        } else {
            $titre = filter_var($_POST['titre'], FILTER_SANITIZE_STRING);
            $genre = filter_var($_POST['genre'], FILTER_SANITIZE_STRING);
            $duree = filter_var($_POST['duree'], FILTER_SANITIZE_NUMBER_INT);

            $repository = DeefyRepository::getInstance();
            $trackId = $repository->saveTrack($titre, $genre, $duree);

            $stmt = $repository->pdo->prepare("INSERT INTO playlist2track (id_pl, id_track) VALUES (:id_pl, :id_track)");
            $stmt->execute(['id_pl' => $_SESSION['current_playlist'], 'id_track' => $trackId]);

            return "Piste ajoutée avec succès.";
        }
    }
}