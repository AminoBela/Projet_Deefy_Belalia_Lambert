<?php

declare(strict_types=1);

namespace iutnc\deefy\render;

use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\audio\tracks\AudioTrack;

class PodcastTrackRenderer extends AudioTrackRenderer
{
    public AudioTrack $piste;

    public function __construct(PodcastTrack $piste){
        $this->piste = $piste;
    }

    public function long():string{
        return  "<p>Titre : {$this->piste->titre}</p> 
                 <p>Genre : {$this->piste->genre}</p> 
                 <p>Durée : {$this->piste->duree}</p>  
                 <p>Année : {$this->piste->annee}</p> 
                 <p>Emplacement du fichier : {$this->piste->nomFichier}</p>";
    }

    public function compact():string{
        return "<p>{$this->piste->__toString()}</p>";
    } 
}
