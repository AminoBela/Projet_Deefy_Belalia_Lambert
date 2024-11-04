<?php
namespace iutnc\deefy\dispatch;

use iutnc\deefy\action\AddUserAction;
use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\AddPodcasttrackAction;
use iutnc\deefy\action\SigninAction;
use iutnc\deefy\action\DisplayPlaylistAction;

class Dispatcher{
    private string $action;

    public function __construct(){
        $this->action ="";
        if(isset($_GET['action'])) $this->action = $_GET['action'];
    }

    public function run(): void {
        $res="Bienvenue !";
        switch($this->action){
            case 'add-user': 
                $res = (new AddUserAction())->execute();
                break;
            case 'add-playlist':
                $res = (new AddPlaylistAction())->execute();
                break;
            case 'add-podcasttrack': 
                $res = (new AddPodcasttrackAction())->execute();
                break;
            case 'sign-in': 
                $res = (new SigninAction())->execute();
                break;
            case 'display-playlist':
                $res = (new DisplayPlaylistAction())->execute();
                break;
        }
        $this->renderPage($res);
    }

    private function renderPage(string $html): void{
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
                        <li><a href="?action=add-playlist" class ="boutton">Créer Playlist</a></li>
                        <li><a href="?action=add-podcasttrack" class ="boutton">Ajouter une piste à la playlist</a></li>
                        <li><a href="?action=display-playlist" class ="boutton">Afficher Playlist</a></li>
                        <p>---------------------</p>
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