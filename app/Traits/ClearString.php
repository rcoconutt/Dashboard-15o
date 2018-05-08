<?php
/**
 * Created by PhpStorm.
 * User: rosoriol
 * Date: 7/05/18
 * Time: 04:18 PM
 */

namespace App\Traits;


trait ClearString
{
    public function clearString($string) {
        $string = htmlentities($string);
        $string = str_replace("&Ntilde;", "Ñ", $string);
        $string = str_replace("&ntilde;", "ñ", $string);
        $string = str_replace("&aacute;", "á", $string);
        $string = str_replace("&eacute;", "é", $string);
        $string = str_replace("&iacute;", "í", $string);
        $string = str_replace("&oacute;", "ó", $string);
        $string = str_replace("&uacute;", "ú", $string);
        $string = str_replace("&Aacute;", "Á", $string);
        $string = str_replace("&Eacute;", "É", $string);
        $string = str_replace("&Iacute;", "Í", $string);
        $string = str_replace("&Oacute;", "Ó", $string);
        $string = str_replace("&Uacute;", "Ú", $string);

        return $string;
    }
}