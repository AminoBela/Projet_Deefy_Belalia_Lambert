<?php

namespace iutnc\deefy\repository;

use PDO;
use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\AudioTrack;

class DeefyRepository
{
    private PDO $bd;

    public function __construct(){
        $this->bd = ConnectionFactory::makeConnection();
    }

    public function chercherPlaylistID(int $id): ?PlayList {
        $query = "SELECT nom FROM playlist WHERE id = ?";
        $prep = $this->db->prepare($query);
        $prep->bindParam(1, $id);
        $prep->execute();
        $data = $prep->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $playlist = new PlayList($data['nom'], []);
        $this->ajouteraPlaylist($playlist);
        return $playlist;
    }

    public function ajouteraPlaylist(PlayList $playlist): void {
        $query = "SELECT * FROM track t INNER JOIN playlist2track p2t ON t.id = p2t.id_track WHERE p2t.id_pl = ?";
        $prep = $this->db->prepare($query);
        $prep->bindParam(1, $playlist->__get('id'));
        $prep->execute();

        while ($trackData = $prep->fetch(PDO::FETCH_ASSOC)) {
            $track = $this->creerPistesDonnes($trackData);
            $playlist->ajouterPiste($track);
        }
    }

    private function creerPistesDonnes(array $data): AudioTrack {
        if ($data['type'] === 'A') {
            $track = new AlbumTrack($data['titre'], $data['filename']);
            $track->__set('artiste', $data['artiste_album']);
            $track->__set('genre', $data['genre']);
            $track->__set('duree', $data['duree']);
            $track->__set('annee', $data['annee_album']);
            $track->__set('album', $data['titre_album']);
            $track->__set('numPiste', $data['numero_album']);
        } else {
            $track = new PodcastTrack($data['titre'], $data['filename']);
            $track->__set('artiste', $data['auteur_podcast']);
            $track->__set('genre', $data['genre']);
            $track->__set('duree', $data['duree']);
            $track->__set('annee', $data['date_posdcast']);
        }
        return $track;
    }
}