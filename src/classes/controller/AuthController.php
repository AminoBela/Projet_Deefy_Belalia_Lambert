<?php

namespace iutnc\deefy\controller;

use iutnc\deefy\repository\DeefyRepository;

class AuthController extends BaseController{

    public function execute(): string
    {
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'register':
                    return $this->register();
                case 'login':
                    return $this->login();
                case 'logout':
                    return $this->logout();
            }
        }
        return "Action inconnue.";
    }

    public function register(): string
    {
        if ($this->http_method == "GET") {
            return '<form method="post" action="?action=register">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <input type="submit" value="S\'inscrire">
                </form>';
        } else {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            Auth::register($email, $password);
            return "Inscription réussie.";
        }
    }

    public function login(): string {
        if ($this->http_method == "GET") {
            return '<form method="post" action="?action=login">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <input type="submit" value="Se connecter">
                </form>';
        } else {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            Auth::login($email, $password);
            return "Connexion réussie.";
        }
    }

    public static function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }

    public static function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] == 100;
    }
}