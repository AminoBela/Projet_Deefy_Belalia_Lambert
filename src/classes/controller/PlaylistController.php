<?php

namespace iutnc\deefy\controller;

use iutnc\deefy\auth\Auth;
use iutnc\deefy\repository\DeefyRepository;

class PlaylistController extends BaseController {

    public function execute() : string
    {
        if ($this->http_method == "GET") {
            return $this->createPlaylist();
        } else {
            return $this->displayCurrentPlaylist();
        }
    }

    public static function displayUserPlaylists() {
        if (!AuthController::isAuthenticated()) {
            echo "Vous devez être connecté pour voir vos playlists.";
            return;
        }

        $repository = DeefyRepository::getInstance();
        $userId = $_SESSION['user_id'];

        $stmt = $repository->pdo->prepare("
            SELECT p.* FROM playlist p
            JOIN user2playlist u2p ON p.id = u2p.id_pl
            WHERE u2p.id_user = :user_id
        ");
        $stmt->execute(['user_id' => $userId]);
        $playlists = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $res = "<h2>Mes Playlists</h2>";
        foreach ($playlists as $playlist) {
            $res .= '<a href="?action=display-playlist&id=' . $playlist['id'] . '">' . $playlist['nom'] . '</a><br>';
        }
        return $res;
    }

    public function createPlaylist(): string {
        if (!Auth::isAuthenticated()) {
            return "Vous devez être connecté pour créer une playlist.";
        }

        if ($this->http_method == "GET") {
            return '<form method="post" action="?action=create-playlist">
                <input type="text" name="nom" placeholder="Nom de la playlist" required>
                <input type="submit" value="Créer">
                </form>';
        } else {
            $nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
            $repository = DeefyRepository::getInstance();
            $playlistId = $repository->savePlaylist($nom);

            $stmt = $repository->pdo->prepare("INSERT INTO user2playlist (id_user, id_pl) VALUES (:user_id, :id_pl)");
            $stmt->execute(['user_id' => $_SESSION['user']['id'], 'id_pl' => $playlistId]);

            $_SESSION['current_playlist'] = $playlistId;
            return "Playlist créée avec succès.";
        }
    }

    public function displayCurrentPlaylist(): string {
        if (!isset($_SESSION['current_playlist'])) {
            return "Aucune playlist sélectionnée.";
        }

        $repository = DeefyRepository::getInstance();
        $playlist = $repository->findPlaylistById($_SESSION['current_playlist']);

        $res = "Playlist courante : " . $playlist['nom'] . "<br>";
        foreach ($playlist['tracks'] as $track) {
            $res .= $track['titre'] . "<br>";
        }
        return $res;
    }
}