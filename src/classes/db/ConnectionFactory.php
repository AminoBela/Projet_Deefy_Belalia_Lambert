<?php

declare(strict_types=1);

namespace iutnc\deefy\db;

use PDO;

class  ConnectionFactory
{

    private static array $tab = [];
    public static ?PDO $bd = null;

    public static function setConfig(String $file ){
        self::$tab = parse_ini_file($file);
    }

    public static function makeConnection()
    {
        if(is_null(self::$bd)){
            try{
                $res = self::$tab['driver'].":host=".self::$tab['host'].";dbname=".self::$tab['database'];
                self::$bd = new PDO($res, self::$tab['username'], self::$tab['password']);
                self::$bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Erreur de connexion à la base de données : ".$e->getMessage();
            }
        }
        return self::$bd;
    }
}

