<?php

declare(strict_types=1);

namespace iutnc\deefy\render;

class AlbumTrackRenderer extends AudioTrackRenderer{

    public function __construct(){
        parent::__construct();
     }

    public function long():string{
        return "<p>titre : {$this->piste->titre}</p> 
                    <p>Album : {$this->piste->album}</p> 
                    <p>Genre : {$this->piste->genre}</p> 
                    <p>Duree : {$this->piste->duree}</p> 
                    <p>Numéro : {$this->piste->numPiste}</p>  
                    <p>Année : {$this->piste->annee}</p> 
                    <p>Emplacement du fichier : {$this->piste->nomFichier}</p>";
    }

    public function compact():string{
        return "<p>{$this->piste->__toString()}</p>";
    }   
}
