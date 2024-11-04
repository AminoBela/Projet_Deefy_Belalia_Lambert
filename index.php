<?php
declare(strict_types=1);
require_once 'vendor/autoload.php';

session_start();
if(!isset($_SESSION['user'])){
    $t = [
        "email" =>"",
        "age" =>0,
        "genre" =>"",
        "playlist" =>null
    ];
    $_SESSION['user'] = $t;
}

\iutnc\deefy\db\ConnectionFactory::setConfig('conf.ini');

$d = new \iutnc\deefy\dispatch\Dispatcher();
$d->run();


