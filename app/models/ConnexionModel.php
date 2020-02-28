<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 28/10/2018
 * Time: 03:03
 */

namespace App\Models;


use App\Core\Model;

class ConnexionModel extends Model
{

    public function __construct()
    {
        parent::__construct();

        #donne le nom de la table manuelement ici
        $this->table = 'connexions';
        $this->id = 'id';
    }

    /**
     * permet faire une authentification dans la base de donnee
     * @param $username le nom de l'utilisateur
     * @param $password mot de passe de l'utilisateur
     * @return bool retourne faux si le mot de passe ou login n'est pas correcte
     */
    public function authentification($username, $password)
    {
        $this->table = 'users';
        $user = $this->get_by('uselogin', $username);

        #teste si l'utilisateur qui ce nom d'utilisateur existe
        if ($user) {
            #si le nom de cet utilisateur existe on test les si le mot de passe en parametre et le mot de passe charger dans la base de donnee correpsondent
            if (equalPwd($user->usemdp, $password)) {
                $_SESSION['stkiduser'] = $user->useid;
                $_SESSION['stkusername'] = $user->uselogin;
                $_SESSION['stkdroits'] = json_decode($user->usedroits);
                \App\Core\Router::setDroits(json_decode($user->usedroits));
                return true;
            }else {
                return false;
            }
        }else {
            return false;
        }
    }


    /**
     * sauvegarder les trace des connexions des utilisateurs.
     * @param $compte
     * @param $datedebut
     * @param $machinesource
     * @param $ipsource
     * @param $connexion
     * @param $datefin
     * @param $deconnexion
     */
    public function save($compte, $datedebut, $machinesource, $ipsource, $connexion, $datefin = null, $deconnexion = '')
    {
        $this->table = 'connexions';
        $option = [
            'user' => $compte,
            'machinesource' => $machinesource,
            'ipsource' => $ipsource,
            'datedebut' => $datedebut,
            'connexion' => $connexion,
            'datefin' => $datefin,
            'deconnexion' => $deconnexion
        ];

        $this->insert($option);

        $_SESSION['stkidconnexion'] = $this->lastInsert();
//        die('je suis dans le save');
    }

    /**
     * mettre a jour la table connexion. cette s'est action uniquement lorsqu'une deconnexion normal est lancee
     * @param $idconnexion
     * @param $connexion
     * @param $datefin
     * @param $deconnexion
     * @return array|bool|mixed|\PDOStatement
     */
    public function updateConnexion($idconnexion, $connexion, $datefin, $deconnexion)
    {
        $options = [
            'connexion' => $connexion,
            'datefin' => $datefin,
            'deconnexion' => $deconnexion
        ];
        return $this->update($options, ['id' => $idconnexion]);
    }

}