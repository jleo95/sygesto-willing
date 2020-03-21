<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 08/03/2019
 * Time: 00:38
 */

namespace App\Models;


use App\Core\Model;

class GroupeMenuModel extends Model
{

    protected $table = 'groupemenu';

    protected $id = 'grpid';

    public function get_by_role(array $roles): array
    {
        $tmps = $this->all();
        $menuModel = new MenuModel();
        $modules = [];

        foreach ($tmps as $module) {
            $menus = $menuModel->get_by_module_droits($module->grpid, $roles);
            if (count($menus) > 0) {
               $modules[] = $module;
            }

        }
        return $modules;
    }


    protected function findAllQuery(?array $fields = [], ?string $orderBy = null)
    {
        return parent::findAllQuery($fields, $orderBy)
                ->where('active = 1');
    }
}