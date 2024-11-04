<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\PlayList;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\auth\Auth;
use Exception;

class DisplayPlaylistAction extends Action {

    public function __construct(){
        parent::__construct();
    }

    public function execute() : string{
        $res = "";

        // Check if the user is authenticated
        if (!isset($_SESSION['user']['id'])) {
            return "Vous devez être connecté pour accéder à cette page.";
        }

        if (isset($_GET['id'])) {
            if (Auth::checkAccess(intval($_GET['id']))) {
                $p = PlayList::find(intval($_GET['id']));
                $r = new AudioListRenderer($p);
                $res = $r->render();
            } else {
                try {
                    $p = PlayList::find(intval($_GET['id']));
                    $res = "Accès refusé à la playlist {$p->nom}";
                } catch (Exception $e) {
                    $res = "Playlist avec id {$_GET['id']} n'existe pas";
                }
            }
        } else {
            if ($this->http_method == "GET") {
                $res = '<form method="post" action="?action=display-playlist">
                    <input type="number" name="id" placeholder="id" autofocus>
                    <input type="submit" name="connex" value="Chercher">
                    </form>';
            } else {
                $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
                $res = <<<aff
                <a href="?action=display-playlist&id=$id">-> Afficher PlayListe</a>
                aff;
            }
        }
        return $res;
    }
}