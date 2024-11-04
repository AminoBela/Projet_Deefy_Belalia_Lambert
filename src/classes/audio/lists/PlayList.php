<?php

declare(strict_types=1);
namespace iutnc\deefy\audio\lists;

use Exception;
use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\repository\DeefyRepository;


class PlayList extends AudioList{

    public function __construct(String $nom, iterable $tab){
        parent::__construct($nom, $tab);
    }

    public function ajouterPiste(AudioTrack $piste):void{
        $this->list[] = $piste;
        $this->dureeTotale += $piste->duree;
        $this->nbPiste++;
    }

    public function supprimerPiste(int $indice):void{
        $this->list->unset($indice);
    }

    public function ajouterListe(AudioList $liste):void{
        $temp = [];
        foreach ($liste->list as $value) {
            if(!in_array($value, $this->list)) $this->list[] = $value;
        }
    }

    public static function find(int $id):mixed{
        $repo = new DeefyRepository();
        $playlist = $repo->chercherPlaylistID($id);
        if ($playlist == null) {
            throw new Exception("Playlist non trouvée");

        }
        return $playlist;
    }

}