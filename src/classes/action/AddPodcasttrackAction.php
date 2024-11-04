<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\audio\lists\PlayList;
use iutnc\deefy\render\AudioListRenderer;

class AddPodcastTrackAction extends Action {

    public function __construct(){
        parent::__construct();
    }

    public function execute() : string {
        $res = "";
        if ($this->http_method == "GET") {
            $res = <<<FORM
                <form method="post" action="?action=add-podcasttrack">
                    <input type="text" name="titre" placeholder="Titre" required>
                    <input type="text" name="artiste" placeholder="Artiste" required>
                    <input type="text" name="genre" placeholder="Genre" required>
                    <input type="number" name="duree" placeholder="Durée (en secondes)" required>
                    <input type="text" name="nomFichier" placeholder="Nom du fichier" required>
                    <input type="submit" name="ajouter" value="Ajouter Piste">
                </form>
            FORM;
        } else {
            $titre = filter_var($_POST['titre'], FILTER_SANITIZE_STRING);
            $artiste = filter_var($_POST['artiste'], FILTER_SANITIZE_STRING);
            $genre = filter_var($_POST['genre'], FILTER_SANITIZE_STRING);
            $duree = filter_var($_POST['duree'], FILTER_SANITIZE_NUMBER_INT);
            $nomFichier = filter_var($_POST['nomFichier'], FILTER_SANITIZE_STRING);

            $piste = new PodcastTrack($titre, $nomFichier);
            $piste->artiste = $artiste;
            $piste->genre = $genre;
            $piste->duree = $duree;

            $playlist = unserialize($_SESSION['user']['playlist']);
            $playlist->ajouterPiste($piste);
            $_SESSION['user']['playlist'] = serialize($playlist);

            $renderer = new AudioListRenderer($playlist);
            $res = $renderer->render();
            $res .= '<a href="?action=add-podcasttrack">Ajouter une autre piste</a>';
        }
        return $res;
    }
}