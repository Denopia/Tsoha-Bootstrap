<?php

/**
 * Model joka käsittelee tietokannan
 * Nature-taulun kyselyitä
 */
class Nature extends BaseModel {

    public $nature_name,
            $nature_id,
            $strong_stat,
            $weak_stat;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    /**
     * Palauttaa kaikki tietokannasta löytyvät luonteet
     */
    public static function allNatures() {
        $query = DB::connection()->prepare("SELECT * FROM Nature ORDER BY nature_id");
        $query->execute();
        $rows = $query->fetchAll();
        $allNatures = array();
        foreach ($rows as $row) {
            $allNatures[] = new Nature(array(
                'nature_name' => $row['nature_name'],
                'nature_id' => $row['nature_id']
            ));
        }
        return $allNatures;
    }

}
