<?php

namespace App\Core;

class DateFR {

    private $year;
    private $day;
    private $month;
    private $strtime;  //Date sous forme de chaine renvoyer par strtotime();
    private $jrsFR;
    private $moisFR;

    function __construct($date = "") {
        if (empty($date)) {
            $date = date("Y-m-d", time());
        }
        $this->strtime = strtotime($date);
        $this->jrsFR = array("Mon" => "Lundi", "Tue" => "Mardi", "Wed" => "Mercredi", "Thu" => "Jeudi", "Fri" => "Vendredi", "Sat" => "Samedi", "Sun" => "Dimanche");
        $this->moisFR = array("1" => "Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Decembre");
        $this->year = date("Y", $this->strtime);
        $this->month = date("n", $this->strtime);
        $this->day = date("D", $this->strtime);
    }

    /**
     * Renvoi le jour en francais sous $len caractere. si 0 = tous les caractere. Lun-Dim
     */
    function getJour($len = 0) {
        if ($len == 0) {
            return $this->jrsFR[$this->day];
        } else {
            return substr($this->jrsFR[$this->day], 0, $len);
        }
    }

    /**
     * Renvoi le mois en francais sous $len caractere. si 0 = tous les caractere. Jan - Dec
     */
    function getMois($len = 0) {
        if ($len == 0) {
            return $this->moisFR[$this->month];
        } else {
            if($this->month == 7 || $this->month == 6){
                if($len === 3)
                    $len += 1;
            }
            return substr($this->moisFR[$this->month], 0, $len);
        }
    }

    /**
     * Renvoi la date du jour du mois.1 - 31
     */
    function getDate() {
        return date("d", $this->strtime);
    }

    /**
     * Renvoi l'anne en francais sous $len caractere. si 0 = tous les caractere
     */
    function getYear($len = 0) {
        if ($len == 0) {
            return $this->year;
        } else {
            return substr($this->year, -$len);
        }
    }

    /**
     * Renvoi les heure et seconde sous la forme 10:20
     * @return type
     */
    function getTime() {
        return date("H:i", $this->strtime);
    }

    /**
     * Renvoi le mois sous forme de numero : de 1 a 12
     * @return type
     */
    function getMonth() {
        return date("m", $this->strtime);
    }

    function setSource($date) {
        $this->strtime = strtotime($date);
        $this->year = date("Y", $this->strtime);
        $this->month = date("n", $this->strtime);
        $this->day = date("D", $this->strtime);
    }

    /**
     * REnvoi la description d'une date sous la forme 31  12 2015 (jj mm aaaa)
     * @param type $len
     * @return type
     */
    function fullYear($len = 0) {
        return $this->getDate() . " " . $this->getMois($len) . " " . $this->year;
    }

    /**
     * Affiche un format de date normal qui varie en fonction du jrs, le mois et l'annee
     */
    function getDateMessage($len) {
        if ($this->year == "1970")
            return "";
        //Si c'est l'annee actuelle format AAAA
        if (date("Y", time()) == $this->year) {
            //Si c'est le mois actuel. Format 1-12
            if (date("n", time()) == $this->month) {
                //Si c'est le jour actuel. Format 01-31
                if (date("d", time()) === $this->getDate()) {
                    if (strcmp(date("H:i", $this->strtime), "00:00")) {
                        return date("H:i", $this->strtime);
                    } else {
                        return $this->getDate() . " " . $this->getMois($len);
                    }
                }
                if (date("d", time()) === $this->getDate() + 1)
                    return "Hier " . date("H:i", $this->strtime);
                return $this->getDate() . " " . $this->getMois($len) . " à " . $this->getTime();
            }//Si c'est pas le mois actuel
            return $this->getDate() . " " . $this->getMois($len);
        }//Si c'est pas l'annee actuelle alors afficher
        return $this->getDate() . " " . $this->getMois($len) . " " . $this->year;
    }

}
