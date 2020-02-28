<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 14/08/2018
 * Time: 01:00
 */

namespace App\Core;


class Model
{

    /**
     * instance database
     * @var object Database
     */
    protected $db;

    /**
     * name table in database
     * @var string
     */
    protected $table;

    /**
     * column id in table
     * @var string
     */
    protected $id;

    private $condLogique;

    protected $records;

    public function __construct()
    {
        $this->db = Database::getInstance();

        #get name table
        if (is_null($this->table)) {
            $table = explode('\\', get_called_class());
            $table = end($table);
            $table = str_replace('Model', '', $table);
            $this->table = strtoupper($table) . 'S';
            $this->id = 'Id' . $table;
        }

        $this->condLogique = ['AND', 'OR'];
    }

    /**
     * @param string $table
     */
    public function setTable($table)
    {
        $this->table = $table;
    }


    /**
     * general query
     * @param $statement
     * @param null $variables
     * @param bool $one
     * @return array|bool|mixed|\PDOStatement
     */
    public function execute($statement, $variables = null, $one = FALSE)
    {
        if (is_null($variables)) {
            return $this->db->statementQuery($statement, $one);
        }else {
            return $this->db->statementPrepare($statement, $variables, $one);
        }
    }

    /**
     * get all element in the database
     * @param array $fields
     * @param null $orderBy nameColumn or ['nameColumn1', 'nameColumn', ...]
     * @return array|bool|mixed|\PDOStatement
     */
    public function all(?array $fields = null, ?string $orderBy = null)
    {
        if (is_null($this->records)) {
            $this->records = $this->execute($this->findAllQuery($fields, $orderBy));
        }
        return $this->records;
    }

    /**
     * get element by on column in one table
     * @param string $nameFields name colunm
     * @param $value value colunm
     * @param array $fields nomber colum it want return for reuslt
     * @param string $orderBy
     * @param bool $one number of line it want return
     * @return array|bool|mixed|\PDOStatement
     */
    public function get_by(?string $nameFields = null, $value, ?array $fields = null, ?string $orderBy = null, ?bool $one = TRUE)
    {
        return $this->execute(
            $this->findByQuery($nameFields, $fields, $orderBy),
            [$value],
            $one
        );
    }

    /**
     * @param string $nameFields name column
     * @param $value line that it want delete
     * @return array|bool|mixed|\PDOStatement
     */
    public function delete_by($nameFields = '', $value)
    {
        if ($nameFields == '') {
            $nameFields = $this->id;
        }

        return $this->execute('DELETE FROM ' . $this->table . ' WHERE ' . $nameFields . ' = ?', [$value]);
    }

    /**
     * get by last id inserted
     * @return mixed
     */
    public function lastInsert()
    {
        $res = $this->execute($this->makeQuery()->from($this->table));
        $res = end($res);
        $id = strtolower($this->id);
        if ($res) {
            return $res->$id;
        }
        return null;
    }

    /**
     * @param array $fields ['key1' => 'value1', 'key2' => 'value2', ...]
     * @param null $conds ['nameField' => value, ...]
     * @return array|bool|mixed|\PDOStatement
     */
    public function update($fields = [], $conds = NULL) {

        $column = [];
        $values = [];
        foreach ($fields as $k => $v) {
            $column [] = $k . ' = ?';
            $values [] = $v;
        }
        $column = implode($column, ', ');
        $query = 'UPDATE ' . $this->table . ' SET ' . $column;
        if (!empty($conds)) {
            $query .= ' WHERE ';
            if (is_array($conds)) {
                foreach ($conds as $k => $v) {
                    if (in_array(strtoupper($v), $this->condLogique)) {
                        $query .= $v;
                    }else {
                        $query .= $k . ' = ? ,';
                        $values [] = $v;
                    }
                }
            }
        }
        $query = trim($query, ', ');

        return $this->execute($query, $values);
    }

    /**
     * insert into database
     * @param array $option ['colunm1' => 'value', 'column2' => 'value', ...]
     * @return array|bool|mixed|\PDOStatement
     */
    public function insert($option = [])
    {
        foreach ($option as $k => $v) {
            $value [] = $k;
            $args [] = $v;
            $occ [] = '?';
        }

        $value = implode($value, ', ');
        $occ = implode($occ, ',');
        $req = 'INSERT INTO ' . $this->table . ' (' . $value . ') VALUES (' . $occ . ')';

        return $this->execute($req, $args, false);
    }
    
    public function insertRows($option = [], $multiple = false)
    {
        $value = [];
        if ($multiple) {
            foreach ($option[0] as $k => $v) {
                $fields [] = $k;
                $occ [] = '?';
            }
            $tmp = [];
            foreach ($option as $v) {
                $tmp = [];
                foreach ($v as $k => $val) {
                    $tmp [] = $val;
                }
                $args [] = $tmp;
            }
        } else {
            foreach ($option as $k => $v) {
                $fields [] = $k;
                $args [] = $v;
                $occ [] = '?';
            }
        }

        $fields = implode($fields, ', ');
        $occ = implode($occ, ', ');
        $req = 'INSERT INTO ' . $this->table . ' (' . $fields . ') ';
        if (!$multiple) {
            $req .= 'VALUES (' . $occ . ')';
        }else {
            $req .= 'VALUES ';
            $tmp = [];
            for($i = 0; $i < count($args); $i ++) {
                $tmp [] = '(' . $occ . ')';
            }
            $req .= implode($tmp, ', ');
        }
        var_dump($args);
        var_dump($req);
        
        die();
        return $this->execute($req, $args, false);
    }

    protected function makeQuery()
    {
        return (new Query())->from($this->table);
    }


    /**
     * Génère la requette pour toute les ligne d'une table
     *
     * @param array|null $fields
     * @param null|string $orderBy
     * @return Query
     */
    protected function findAllQuery(?array $fields = [], ?string $orderBy = null)
    {
        $query = $this->makeQuery()->from($this->table);

        if (!empty($fields)) {
            $args = [];
            foreach ($fields as $k => $v) {
                if (is_string($k)) {
                    $args[] = $k . ' as ' . $v;
                }else{
                    $args[] = $v;
                }
            }
            $query = $query->select(join(', ', $args));
        }

        if ($orderBy !== null) {
            if (is_array($orderBy)) {
                $query = $query->order(join(', ', $orderBy));
            }else {
                $query = $query->order($orderBy);
            }
        }
        return $query;
    }

    /**
     * Génère une requre de selection par colonne
     *
     * @param null|string $nameFields
     * @param array|null $fields
     * @param null|string $orderBy
     * @return Query
     */
    protected function findByQuery(?string $nameFields = null, ?array $fields = null, ?string $orderBy = null)
    {
        if ($nameFields === '') {
            $nameFields = $this->id;
        }
        $query = $this->makeQuery()
            ->where($nameFields . ' = ? ')
            ->from($this->table);
        if (!is_null($fields)) {
            $args = [];
            foreach ($fields as $k => $v) {
                if (is_string($k)) {
                    $args[] = $k . ' as ' . $v;
                }else{
                    $args[] = $v;
                }
            }
            $query = $query->select(join(', ', $args));
        }

        if (!is_null($orderBy)) {
            $query = $query->order($orderBy);
        }
        return $query;
    }

}