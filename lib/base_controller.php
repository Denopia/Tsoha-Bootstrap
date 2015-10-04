<?php

require_once 'app/models/user.php';

class BaseController {

    public static function get_user_logged_in() {
        // Katsotaan onko user-avain sessiossa
        if (isset($_SESSION['user'])) {
            $user_id = $_SESSION['user'];
            // Pyydetään User-mallilta käyttäjä session mukaisella id:llä
            $user = User::findById($user_id);

            return $user;
        }
        // Käyttäjä ei ole kirjautunut sisään
        return null;
    }

    public static function check_logged_in() {
        if (!isset($_SESSION['user'])) {
            Redirect::to('/login', array('message' => 'You need to log in first :]'));
        }
    }
    
    public static function check_logged_in_already() {
        if (isset($_SESSION['user'])) {
            Redirect::to('/species', array('error' => 'You are already logged in'));
        }
    }
    
    public static function check_admin() {
        if (!isset($_SESSION['user']) || !self::get_user_logged_in()->admini) {
            Redirect::to('/species', array('error' => 'You don\'t have rights to do that :[' ));
        }
    }

}
