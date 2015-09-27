<?php

class BaseModel {

    // "protected"-attribuutti on käytössä vain luokan ja sen perivien luokkien sisällä
    protected $validators;

    public function __construct($attributes = null) {
        // Käydään assosiaatiolistan avaimet läpi
        foreach ($attributes as $attribute => $value) {
            // Jos avaimen niminen attribuutti on olemassa...
            if (property_exists($this, $attribute)) {
                // ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
                $this->{$attribute} = $value;
            }
        }
    }

    public function validate_string_length($string, $length, $length2, $intest) {
        $errors = array();
        if ($string == '' || $string == null) {
            $errors[] = $intest . ' can not be empty';
            return $errors;
        }
        if (strlen($string) < $length) {
            $errors[] = 'Minimum length of ' . $intest . ' is ' . $length . ' characters';
        }
        if (strlen($string) > $length2) {
            $errors[] = 'Maximum length of ' . $intest . ' is ' . $length2 . ' characters';
        }

        return $errors;
    }

    public function validate_integer($number, $min, $max, $intest) {
        $errors = array();
        if (!ctype_digit($number)) {
            $errors[] = $intest . ' must be a number';
            return $errors;
        }
        if ((int) $number < (int) $min) {
            $errors[] = 'Minimum value of ' . $intest . ' is ' . $min;
        }
        if ((int) $number > (int) $max) {
            $errors[] = 'Maximum value of ' . $intest . ' is ' . $max;
        }
        return $errors;
    }

    public function errors() {
        // Lisätään $errors muuttujaan kaikki virheilmoitukset taulukkona
        $errors = array();

        foreach ($this->validators as $validator) {
            $newerrors = $this->{$validator}();
            $errors = array_merge($errors, $newerrors);
        }
        return $errors;
    }

}
