<?php

require_once 'app/models/user.php';

/**
 * Kontrolloi käyttäjiin liittyviä toimintoja
 */
class UserController extends BaseController {

    /**
     * Luo kirjautumissivun
     */
    public static function login() {
        self::check_logged_in_already();
        View::make('suunnitelmat/login.html');
    }

    /**
     * Kirjaa käyttäjän sisään jos tunnus ja sitä vastaava salasana
     * löytyvät tietokannasta
     */
    public static function handle_login() {
        self::check_logged_in_already();
        $params = $_POST;

        $user = User::authenticate($params['username'], $params['password']);

        if (!$user) {
            View::make('suunnitelmat/login.html', array('message' => 'Wrong username or password!', 'username' => $params['username']));
        } else {
            $_SESSION['user'] = $user->user_id;

            Redirect::to('/', array('message' => 'Greetings ' . $user->username . '!'));
        }
    }

    /**
     * Kirjaa käyttäjän ulos
     */
    public static function logout() {
        self::check_logged_in();
        $_SESSION['user'] = null;
        Redirect::to('/', array('message' => 'Logged out!'));
    }

    /**
     * Luo rekisteröitymissivun
     */
    public static function register() {
        self::check_logged_in_already();
        View::make('suunnitelmat/register.html');
    }

    /**
     * Rekisteröi käyttäjän post/pyynn;n parametrilla jos ei l;ydy virheitä,
     * muuten vie takaisin lomakkeeseen
     */
    public static function handle_register() {
        self::check_logged_in_already();
        $params = $_POST;
        $attributes = array(
            'username' => $params['username'],
            'password' => $params['password'],
            'admini' => false
        );
        $user = new User($attributes);
        $errors = $user->errors();
        if (count($errors) == 0) {
            $user->save();
            $user2 = User::authenticate($params['username'], $params['password']);
            $_SESSION['user'] = $user2->user_id;
            Redirect::to('/mypokemon', array('message' => 'Registration succeeded. Welcome ' . $params['username']));
        } else {
            View::make('suunnitelmat/register.html', array('errors' => $errors));
        }
    }

}
