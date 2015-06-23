<?php

namespace DVO;

class Validator
{
    public function isValidEmail($email)
    {
        return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function isValidName($string)
    {
        if (true === empty($string)) {
            return true;
        }
        if (preg_match('/^[a-zA-Z]+$/', $string)  == false) {
            return false;
        }

        if (is_numeric($string)) {
            return false;
        }

        if (strlen($string) > 30) {
            return false;
        }

        return true;
    }

    public function isValidString($string)
    {
        if (true === empty($string)) {
            return true;
        }
        if (preg_match('/^[a-zA-Z]+$/', $string) == false) {
            return false;
        }
        if (is_numeric($string) === true) {
            return false;
        }
        return true;
    }

    public function isValidInt($int)
    {
        if (is_numeric($int) === true) {
            return true;
        }
        return false;
    }

    public function isValidUrl($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return true;
        }

        if (true === empty($url)) {
            return false;
        }

        if (strlen($url) > 100 || strlen($url) < 13) {
            return false;
        }

        return false;
    }

    public function isValidUsername($name)
    {
        if (strlen($name) > 2  &&  (strlen($name) < 26) && $this->isValidString($name)) {
             return true;
        }

        return false;
    }

    public function isValidPhone($phone)
    {
        $number = substr($phone, 1);

        if (strlen($phone) > 7 && substr($phone, 0, 1) === '+' && is_numeric($number)) {
            return true;
        }

        return false;
    }
}
