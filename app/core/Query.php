<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 07/03/2019
 * Time: 02:01
 */

namespace App\Core;


class Query
{

    private $from;

    private $where = [];

    private $limit;

    private $order;

    private $select;

    private $joins;

    private $groupe;

    protected $params;

    public function from(string $table, ?string $alias = null): self
    {
        if ($alias) {
            $this->from = $table . ' as ' . $alias;
            return $this;
        }
        $this->from = $table;
        return $this;
    }

    public function select(string ... $column): self
    {
        $this->select = $column;
        return $this;
    }

    public function where(string ...$conds): self
    {
        $this->where = array_merge($this->where, $conds);
        return $this;
    }

    public function order(string $field): self
    {
        $this->order[] = $field;
        return $this;
    }

    public function limit(int $length, int $offset): self
    {
        $this->limit = "$offset, $length";
        return $this;
    }

    public function join(string $table, string $cond, string $type = 'LEFT'): self
    {
        $this->joins[$type][] = [$table, $cond];
        return $this;
    }

    public function params(array $params): self
    {
        $this->params = array_merge($this->params, $params);
        return $this;
    }

    public function groupeBy(?string $column): self
    {
        $this->groupe = $column;
        return $this;
    }

    public function __toString()
    {
        $part = ['SELECT'];
        if (!empty($this->select)) {
            $part[] = join(', ', $this->select);
        }else {
            $part[] = '*';
        }
        $part[] = 'FROM';
        $part[] = $this->from;

        if ($this->joins) {
            foreach ($this->joins as $type => $joins) {
                foreach ($joins as [$table, $cond]) {
                    $part[] = strtoupper($type) . " JOIN $table ON $cond";
                }
            }
        }
        if (!empty($this->where)) {
            $part[] = 'WHERE';
            $part[] = '(' . join(') AND (', $this->where) . ')';
        }

        if ($this->order) {
            $part[] = 'ORDER BY';
            $part[] = join(', ', $this->order);
        }

        if ($this->limit) {
            $part[] = 'LIMIT';
            $part[] = $this->limit;
        }

        if ($this->groupe) {
            $part[] = 'GROUP BY';
            $part[] = $this->groupe;
        }
        return join(' ', $part);
    }

}