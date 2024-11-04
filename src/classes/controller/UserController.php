<?php

namespace iutnc\deefy\controller;

use iutnc\deefy\auth\Auth;
use iutnc\deefy\auth\AuthException;
use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\model\User;
use PDO;


class UserController extends BaseController {

    public function execute(): string {
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'add-user':
                    return $this->addUser();
                case 'sign-in':
                    return $this->signIn();
            }
        }
        return "Action inconnue.";
    }

    public function addUser(): string {
        $res = "";
        if ($this->http_method == "GET") {
            $res = '<form method="post" action="?action=add-user">
                <input type="email" name="email" placeholder="email" autofocus required>
                <input type="password" name="passwd1" placeholder="password 1" required>
                <input type="password" name="passwd2" placeholder="password 2" required>
                <input type="submit" name="connex" value="Connexion">
                </form>';
        } else {
            $e = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $p1 = $_POST['passwd1'];
            $p2 = $_POST['passwd2'];
            if ($p1 === $p2) {
                if (strlen($p1) >= 10) {
                    $res = "<p>" . Auth::register($e, $p1) . "</p>";
                } else {
                    $res = "<p>Le mot de passe doit contenir au moins 10 caractères</p>";
                }
            } else {
                $res = '<p>Les mots de passe ne correspondent pas</p>
                <form method="post" action="?action=add-user">
                <input type="email" name="email" placeholder="email" autofocus required>
                <input type="password" name="passwd1" placeholder="password 1" required>
                <input type="password" name="passwd2" placeholder="password 2" required>
                <input type="submit" name="connex" value="Connexion">
                </form>';
            }
        }
        return $res;
    }

    public function signIn(): string {
        $res = "";
        if ($this->http_method == "GET") {
            $res = '<form method="post" action="?action=sign-in">
                <input type="email" name="email" placeholder="email" autofocus>
                <input type="text" name="password" placeholder="mot de passe">
                <input type="submit" name="connex" value="Connexion">
                </form>';
        } else {
            $e = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $p = $_POST['password'];
            $bool = false;
            try {
                $bool = Auth::authenticate($e, $p);
            } catch (AuthException $e) {
                $res = "<p>Identifiant ou mot de passe invalide</p>";
            }

            if ($bool) {
                $u = new User($e, $p, 1);
                $t = $u->getPlaylists();
                $res = <<<start
                    <h3>Connexion réussite pour $e</h3>
                    <h3>Playlists de l'utilisateur : </h3>
                start;
                $bd = ConnectionFactory::makeConnection();
                foreach ($t as $k => $value) {
                    $nom = $value->__get("nom");
                    $query = "SELECT id from playlist p where p.nom like ?";
                    $playlists = $bd->prepare($query);
                    $playlists->bindParam(1, $nom);
                    $playlists->execute();

                    while ($play = $playlists->fetch(PDO::FETCH_ASSOC)) {
                        $res .= '<a href="?action=display-playlist&id=' . $play['id'] . '"> - ' . $nom . '</a>';
                    }
                }
            }
        }
        return $res;
    }
}