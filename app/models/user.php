<?php

/**
 * Model joka käsittelee tietokannan
 * User-taulun kyselyitä
 */
class User extends BaseModel {

    public $user_id,
            $username,
            $password,
            $admini;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_username', 'validate_password');
    }

    /**
     * Tallentaa uuden käyttäjän tietokantaan
     */
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Trainer (username, password, admini) VALUES (:username, :password, false)');
        $query->execute(array('username' => $this->username, 'password' => $this->password));
    }

    /**
     * Validoikäyttäjänimen pituuden ja ettei se ole jo käytössä
     * @return string
     */
    public function validate_username() {
        $errors = parent::validate_string_length($this->username, '3', '15', 'Username');
        if (count($errors) != 0) {
            return $errors;
        }
        $query = DB::connection()->prepare('SELECT * FROM Trainer WHERE username = :n LIMIT 1');
        $query->execute(array('n' => $this->username));
        $rows = $query->fetchAll();
        foreach ($rows as $row) {
            $errors[] = 'Username already taken';
        }
        return $errors;
    }

    /**
     * Validoi salasanan pituuden
     * @return type
     */
    public function validate_password() {
        $errors = parent::validate_string_length($this->password, '3', '15', 'Password');
        return $errors;
    }

    /**
     * Palauttaa käyttäjän jos sellainen tietokannasta löytyy 
     * annetulla nimellä ja salasanalla
     * 
     * @param type $username nimi
     * @param type $password salasana
     * @return type
     */
    public function authenticate($username, $password) {
        $query = DB::connection()->prepare('SELECT * FROM Trainer WHERE username = :name AND password = :password LIMIT 1');
        $query->execute(array('name' => $username, 'password' => $password));
        $row = $query->fetch();
        if ($row) {
            $user = new User(array(
                'user_id' => $row['trainer_id'],
                'username' => $row['username'],
                'password' => $row['password'],
                'admini' => $row['admini']
            ));
        } else {
            $user = null;
        }
        return $user;
    }

    /**
     * Etsii käyttäjän tietokannasta id:n perusteella
     * @param type $id
     * @return type
     */
    public function findById($id) {
        $query = DB::connection()->prepare('SELECT * FROM Trainer WHERE trainer_id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        if ($row) {
            $user = new User(array(
                'user_id' => $row['trainer_id'],
                'username' => $row['username'],
                'password' => $row['password'],
                'admini' => $row['admini']
            ));
        } else {
            $user = null;
        }
        return $user;
    }

}
