<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 07/07/2018
 * Time: 01:42
 */


/**
 * crypte password
 * @param $pwd password
 * @return string password crypt
 */
function cryptPwd($pwd) {

    $arr1 = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];

    $j = 0;
    foreach ($arr1 as $value) {
        $arr[] = strtoupper($value);
        $arr[] = $value;
        if ($j <= 9) {
            $arr[] = $j ++;
        }
    }

    shuffle($arr);
    $str1 = '';
    $str2 = '';

    for ($i = 0; $i < 25; $i ++ ) {
        $str1 .= $arr[rand(0, count($arr) - 1)];
        $str2 .= $arr[rand(0, count($arr) - 1)];
    }
    $mdp = $str1 . sha1($pwd) . $str2;
    return $mdp;
}

/**
 * compare two passwords
 * @param $password1 first password to crypt
 * @param $password2 second password to compare
 * @return bool true if there two password is egale false else
 */
function equalPwd($password1, $password2) {

    if (strlen($password1) < strlen($password2)) {
        $password1 = sha1($password1);
        if (strpos($password2, $password1) !== 0) {
            $mdp = substr($password2, strpos($password2, $password1), strlen($password1));
            if ($mdp === $password1) {
                return TRUE;
            }
        }
    }else {
        $password2 = sha1($password2);
        if (strpos($password1, $password2) !== 0) {
            $mdp = substr($password1, strpos($password1, $password2), strlen($password2));
            if ($mdp === $password2) {
                return TRUE;
            }
        }
    }

    return FALSE;
}


function dateFormat ($date, $format = 'Y-m-d') {
    
}



#upload the file
/**
 * upload the file
 * @param $file le fichier
 * @param string $nameFile nouveau nom du fichier
 * @param $path destionation
 * @param array $extention tableau des extension autorisees
 * @param int $maxSize taille max
 * @return array|string
 */
function uploadFile($file, $nameFile = '', $path, $extention = ["jpg","jpeg","png","gif"], $maxSize = 5097152){

    if($file['size'] <= $maxSize){

        $extensionUpload = strtolower(substr(strrchr($file['name'], "."), 1));
        $name_avatar = "";

        if(in_array($extensionUpload, $extention)){

            $name_avatar = $nameFile . "." . $extensionUpload;
            $chemin = $path . DS . '' .$name_avatar;
            $resultat = move_uploaded_file($file['tmp_name'], $chemin);

            if($resultat){
                return [
                    'confirm' => 1,
                    'nameFile' => $name_avatar
                ];
            }

        }else{
            return 'Format invalide.<br> <span>Votre fichier doit être au format : ' . implode(' ou ', $extention) . '</span>';
        }

    }else{
        return 'Fichier tros volumineux. <br><span>Votre fichier ne doit pas depasser : ' . $maxSize / 1024 . 'Mo</span>';
    }
}

#gerate aleatoire
function aleatoire(){
    $table = '';
    for($i = 0; $i < 9; $i++)
        $table .= mt_rand(0,9);
    return $table;
}


function formatDate($date, $formatReturn = 'Y-m-d')
{
    $arrayDay = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
    $arrayMonth  = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aoute', 'Septembre', 'Octobre', 'Novembre', 'Decembre'];

    $y = date('Y', strtotime($date));
    $m = date('m', strtotime($date));
    $d = date('d', strtotime($date));

//    $mon = $arrayMonth[$m - 1];

    return date($formatReturn, strtotime($date));
}

#calcucl de temps
function calculTime($tps) {
    $date = date('Y-m-d', strtotime($tps));
    $year = date('Y', strtotime($date));
    $month = date('m', strtotime($date));
    $day = date('d', strtotime($date));

    $tps = date('H:i:s', strtotime($tps));
    $hour = date('H', strtotime($tps));
    $minute = date('i', strtotime($tps));
    $second = date('s', strtotime($tps));

    #current temp
    $date_cur = date("Y-m-d H:i:s",time());
    $year_cur = date('Y', strtotime($date_cur));
    $month_cur = date('m', strtotime($date_cur));
    $day_cur = date('d', strtotime($date_cur));
    $hour_cur = date('H', strtotime($date_cur));
    $minute_cur = date('i', strtotime($date_cur));
    $second_cur = date('s', strtotime($date_cur));

    $my_tps = 'non';
    if($year === $year_cur){
        if($month === $month_cur){
            if ($hour_cur === $hour) {
                if($minute === $minute_cur){
                    $my_tps = $second_cur - $second;
                    $my_tps = $my_tps . ' seconde';
                }else{
                    $my_tps = $minute_cur - $minute;
                    $my_tps = $my_tps . ' minutes';
                }

            }  else {
                $my_tps = $hour_cur - $hour;
                $my_tps = $my_tps . 'h';
            }

        }

    }
    return $my_tps;
}

#remplissage aleatoir d'une table
function arr_rand($tab1, $tab2){

    $nb = count($tab1) - count($tab2);
    if ($nb < 0){
        $nb = count($tab2) - count($tab1);
        $newTabs = [];
        $tmp = $tab1;

        foreach ($tmp as $tp) {
            $newTabs [] = $tp;
        }

        $tmp = $tab1;
        while ($nb > 0){
            if (empty($tmp)){
                $tmp = $tab1;
            }
            $id = rand(0, count($tmp) - 1);
            $newTabs [] = $tmp [$id];
            unset($tmp[$id]);
            $tmp = array_merge($tmp);
            $nb --;
        }
        return ['tab' => $newTabs, 'type' => 0];
    }elseif ($nb > 0){
        $newTabs = [];
        $tmp = $tab2;
        foreach ($tmp as $tp) {
            $newTabs [] = $tp;
        }

        $tmp = $tab2;
        while ($nb > 0){
            if (empty($tmp)){
                $tmp = $tab2;
            }
            $id = rand(0, count($tmp) - 1);
            $newTabs [] =$tmp [$id];
            unset($tmp[$id]);
            $tmp = array_merge($tmp);
            $nb --;
        }

        return ['tab' => $newTabs, 'type' => 1];
    }
    return ['type' => 2];
}

function validerFile ($file, $sizMax = 5097152, $extension = ["jpg","jpeg","png","gif"], $size = '5 Mo') {
    $ext = strtolower(substr(strrchr($file['name'], "."), 1));

//    return $ext;

    if (in_array($ext, $extension)) {

        if ($file['size'] > $sizMax) {
            return 'La taille de votre fichier ne doit pas de passe ' . $size;
        }else{
            return 'v';
        }

    }else{
        return 'Votre extension doit etre ' . implode(' ou ', $extension);
    }
}

/**
 * add files CSS in header page
 * @param type $files ['file' => ['path' => 'myfile.css', 'title_file' => 'mytile_file']]
 */
function writeExterneFileCSS($files = ['file' => ['path' => '', 'title_file' => '']]) {
    //var_dump($files);
    foreach ($files as $k) {
        echo '<link rel="stylesheet" href="' . $k['path'] . '">' . PHP_EOL;
    }
}

/**
 * @param $date
 * @return int
 */
function calculDate ($date) {

    $currentdate = strtotime(date('Y-m-d', time()));
    $nbmday = 0;
    $month = 0;
    $date = strtotime($date);

    if ($date > $currentdate) {
        while ($date != $currentdate) {
            $currentdate = strtotime(date('Y-m-d', $currentdate) . '+1 DAY');
            $nbmday ++;
        }
    }elseif ($date < $currentdate) {
        while ($date != $currentdate) {
            $currentdate = strtotime(date('Y-m-d', $currentdate) . '-1 DAY');
            $nbmday --;
        }
    }

    return $nbmday;

}

/**
 * debug variables
 * @param $var anay
 */
function dd ($var) {
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}

function quickSort() {
    $tab = [8, 2, 5, 6, 1, 11, 50, 25, 5];

    dd($tab);

    $taile = count($tab);
    for ($i = 0; $i < $taile; $i ++) {
        $trouver = false;
        $v = $tab[$i];
        $j = $i;
        while ($j >= 1 && $v < $tab[$j - 1]) {
            $tab[$j] = $tab[$j - 1];
            $j --;
        }
        $tab[$j] = $v;
    }

    dd($tab);
}

function echange($k, $i, $tab) {
    $tmp = $tab[$k];
    $tab[$k] = $tab[$i];
    $tab[$i] = $tmp;
    return $tab;
}

/**
 * recuperer le premier jour d'un mois
 * @param $month string le mois au quel on veut recuperer son premier jour || par defaut c'est le mois courant
 * @param $year string l'année au quelle on veut recuperer le premier jour d'un mois quelconque || par defaut c'est l'année courante
 * @return string
 */
function getStartingDay ($month = '', $year = '') {
    if (empty($month)) {
        $month = date('m', time());
    }
    if (empty($year)) {
        $year = date('Y', time());
    }

    $date =  new \DateTime($year . '-' . $month . '-01');
    return $date->format('Y-m-d');
}

/**
 * recuperer le dernier jour d'un mois
 * @param $month string le mois au quel on veut recuperer son premier jour || par defaut c'est le mois courant
 * @param $year string l'année au quelle on veut recuperer le premier jour d'un mois quelconque || par defaut c'est l'année courante
 * @param $interval string l'année au quelle on veut recuperer le premier jour d'un mois quelconque || par defaut c'est l'année courante
 * @return string
 */
function getLastDay ($month = '', $year = '', $interval = 'month') {
    if (empty($month)) {
        $month = date('m', time());
    }
    if (empty($year)) {
        $year = date('Y', time());
    }
    $date = new \DateTime(date('Y') . '-' . date('m') . '-01');
    $date = $date->modify("+1 month -1 day")->format('Y-m-d');
    return $date;
}

/**
 * recuperer des jour ou mois a une date quelconque
 * @param $nameInterval string le nom de la periode c'est a dire si c'est les jour qu'on veut ajouter ou les mois ....
 * @param $nbInterval string le nombre de temps qu'on veut ajouter (les nombre de jour ou mois)
 * @param $date string date au quelle on veut ajouter
 * @return string
 */
function addDayInDate ($nameInterval = 'day', $nbInterval = '1', $start = '') {
    if (empty($date)) {
        $start = date('Y-m-d', time());
    }
    
    if (empty($nameInterval)) {
        $nameInterval = 'day';
    }
    if (empty($nbInterval)) {
        $nameInterval = 1;
    }
    $date = new \DateTime($start);
    
    $date = $date->modify('+' . $nbInterval . ' ' . $nameInterval)->format('Y-m-d');
    return $date;
}