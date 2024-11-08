<?php

declare(strict_types=1);
namespace iutnc\deefy\audio\lists;

use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\NonEditablePropertyException;

class AlbumList extends AudioList{

    private String $artiste;
    private int $dateSortie;

    public function __construct(String $nom, iterable $tab, String $artiste, int $date){
        parent::__construct($nom, $tab);
        $this->artiste = $artiste;
        $this->dateSortie = $date;
    }

    public function __set(String $arg1, mixed $arg2):void{
        if($arg1==="nom"||$arg1==="nbPiste"||$arg1==="dureeTotale"||$arg1==="list") throw new NonEditablePropertyException("On ne peux pas modifier : $arg1");;
        if(property_exists($this, $arg1)){$this->$arg1=$arg2;
        }else{throw new InvalidPropertyNameException ("$arg1: Propiete invalide");}
    }

}