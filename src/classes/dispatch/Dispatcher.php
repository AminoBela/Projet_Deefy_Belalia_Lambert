<?php

namespace iutnc\deefy\dispatch;

use iutnc\deefy\controller\PlaylistController;
use iutnc\deefy\controller\TrackController;
use iutnc\deefy\controller\UserController;


class Dispatcher {
    private string $action;

    public function __construct() {
        $this->action = "";
        if (isset($_GET['action'])) $this->action = $_GET['action'];
    }

    public function run(): void {
        $res = "Bienvenue !";
        switch ($this->action) {
            case 'add-user':
                $res = (new UserController())->addUser();
                break;
            case 'sign-in':
                $res = (new UserController())->signIn();
                break;
            case 'create-playlist':
                $res = (new PlaylistController())->createPlaylist();
                break;
            case 'display-playlist':
                $res = (new PlaylistController())->displayCurrentPlaylist();
                break;
            case 'add-track':
                $res = (new TrackController())->addTrack();
                break;
            case 'my-playlists':
                $res = (new PlaylistController())->displayUserPlaylists();
                break;
            default:
                $res = "Action inconnue.";
                break;
        }
        $this->renderPage($res);
    }

    private function renderPage(string $html): void {
        echo <<<end
            <!DOCTYPE html>
            <html lang="fr" dir="ltr">
            <head>
                <meta charset="utf-8">
                <meta name=”viewport” content="initial-scale=1.0">
                <link rel="stylesheet" href="./main.css">
                <title>Index</title>
            </head>
            <body>
                <nav>
                    <h1>Deefy</h1>
                    <ul>
                        <li><a href="?" class ="boutton">Accueil</a></li>
                        <li><a href="?action=my-playlists" class ="boutton">Mes Playlists</a></li>
                        <li><a href="?action=create-playlist" class ="boutton">Créer Playlist</a></li>
                        <li><a href="?action=display-playlist" class ="boutton">Afficher Playlist</a></li>
                        <li><a href="?action=add-user" class ="boutton">Créer mon compte</a></li>
                        <li><a href="?action=sign-in" class ="boutton">Se connecter</a></li>
                    </ul>
                </nav>
                <div class="wrapper">
                    $html
                </div>
            </body>
            </html>
            end;
    }
}