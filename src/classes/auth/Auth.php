<?php

namespace iutnc\deefy\auth;

use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\exception\AuthException;

use PDO;

class Auth{

    const MIN_PASSWORD_LENGTH = 10;
    const ERROR_PASSWORD_MISMATCH = 'Les mots de passe ne correspondent pas';
    const ERROR_PASSWORD_LENGTH = 'Le mot de passe doit contenir au moins 10 caractères';
    const ERROR_AUTH_FAILED = 'Identifiant ou mot de passe invalide';

    public static function authenticate(string $e, string $p): bool {
        $bd = ConnectionFactory::makeConnection();
        $query = "SELECT passwd, role FROM User WHERE email = ?";
        $prep = $bd->prepare($query);
        $prep->bindParam(1, $e);
        $prep->execute();
        $data = $prep->fetch(\PDO::FETCH_ASSOC);
        $hash = $data['passwd'];
        if (!password_verify($p, $hash)) {
            throw new AuthException(self::ERROR_AUTH_FAILED);
        }
        $_SESSION['user']['id'] = $e;
        $_SESSION['user']['role'] = $data['role'];
        return true;
    }

    public static function register(string $e, string $p): string {
        $res = "Echec inscription";
        $bd = ConnectionFactory::makeConnection();
        $query = "SELECT passwd FROM User WHERE email = ?";
        $prep = $bd->prepare($query);
        $prep->bindParam(1, $e);
        $prep->execute();
        $d = $prep->fetchAll(\PDO::FETCH_ASSOC);
        if (strlen($p) >= self::MIN_PASSWORD_LENGTH && empty($d)) {
            $hash = password_hash($p, PASSWORD_DEFAULT, ['cost' => 10]);
            $insert = "INSERT INTO user (email, passwd) VALUES (?, ?)";
            $prep = $bd->prepare($insert);
            $prep->bindParam(1, $e);
            $prep->bindParam(2, $hash);
            if ($prep->execute()) {
                $res = "Inscription réussie";
            }
        }
        return $res;
    }

    public static function checkAccess(int $id):bool{
        $res=false;
        
        $bd = \iutnc\deefy\db\ConnectionFactory::makeConnection();
        $query = "SELECT u.email as email from user u inner join user2playlist p on u.id = p.id_user where id_pl = ? ";
        $prep = $bd->prepare($query);
        $prep->bindParam(1,$id);
        $bool = $prep->execute();
        $d = $prep->fetchall(PDO::FETCH_ASSOC);
        if($bool && sizeof($d)>0){
            if($d[0]['email'] === $_SESSION['user']['id']||$_SESSION['user']['role']===100){
                $res=true;
            }
        }
        return $res;
    }

}