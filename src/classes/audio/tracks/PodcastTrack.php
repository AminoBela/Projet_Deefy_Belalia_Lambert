<?php

declare(strict_types=1);

namespace iutnc\deefy\audio\tracks;

class PodcastTrack extends AudioTrack{

    public function __construct(string $nom, string $chemin){
        parent::__construct($nom,$chemin);
    }
}