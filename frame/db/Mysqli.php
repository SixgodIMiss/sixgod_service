<?php

namespace frame\db;

use function frame\arr_get;
use frame\My_Config;

class My_Mysqli extends \Mysqli
{
    // SQL执行顺序 from->join->where->group by->having->select->order by

    private $fetchSql = false;
    private static $_instance = [];

    private function __construct($host, $username, $passwd, $dbname, $port, $socket)
    {
        parent::__construct($host, $username, $passwd, $dbname, $port, $socket);
    }

    /**
     * @param string $db
     * @return My_Mysqli
     * @throws \Exception
     */
    public static function getInstance($db = 'default')
    {
        if (! arr_get(self::$_instance, $db, false) instanceof self)
        {
            $config = My_Config::get('db', $db);
            self::$_instance[$db] = new self($config['host'], $config['username'], $config['password'], $config['dbname'], $config['port'], null);
        }
        return self::$_instance[$db];
    }

    /**
     * 返回一行
     * @param string $table
     * @param string $fields
     * @param array $condition
     * @param array $join_condition
     * @param string $order
     * @param string $group
     * @param string $having
     * @return array|mixed|string
     */
    public function getRow($table = '', $fields = '*', $condition = [], $join_condition = [], $order = '', $group = '', $having = '')
    {
        $result = [];

        $sql = 'SELECT ';

        if (is_array($fields)) {
            $real_fields = implode(',', $fields);
            $fetch_fields = $fields;
        } else {
            $real_fields = $fields;
            $fetch_fields = explode(',', $fields);
        }
        $sql .= $real_fields .' FROM `'. $table .'`';

        if (!empty($join_condition)) {

        }

        if (!empty($condition)) {
            $sql .= ' WHERE ';

            if (is_array($condition)) {
                $sql .= ''. implode(' AND ', $this->composeCondition($condition)) .'';
            } else {
                $sql .= $condition .' ';
            }

        }

        $sql .= ' limit 1';

        if ($this->fetchSql) {
            $result = $sql;
        } else {
            $query = $this->query($sql);
            if ($query) {
                array_map(function (&$v) {
                    $v = trim($v);
                }, $fetch_fields);

                while ($row = $query->fetch_assoc()) {
                    array_push($result, $row);
                }
            }

        }

        $this->fetchSql(false);

        return $result[0];
    }

    /**
     * 组合查询条件
     * @param $condition
     * @return array
     */
    private function composeCondition($condition)
    {
        $real_condition = [];
        foreach ($condition as $v) {
            $range = '';
            $v[1] = strtoupper($v[1]);
            switch ($v[1]) {
                case '=':
                case '>':
                case '>=':
                case '<':
                case '<=':
                case '<>':
                case 'IS NULL':
                case 'IS NOT NULL':
                case 'LIKE':
                case 'NOT LIKE':
                    $range = implode(' ', $v);
                    break;
                case 'IN':
                    $v[2] = '('. implode(' , ', $v[2]) .')';
                    $range = implode(' ', $v);
                    break;
                case 'BETWEEN':
                    $v[2] = implode(' and ', $v[2]);
                    $range = implode(' ', $v);
                    break;
                case 'OR':
                    $range = '( '. implode(' OR ', $this->composeCondition($v[2])) .' )';
            }
            array_push($real_condition, $range);
        }

        return $real_condition;
    }

    private function composeJoin($join)
    {

    }

    /**
     * 获取SQL
     * @param bool $default
     * @return $this
     */
    public function fetchSql($default = true)
    {
        $this->fetchSql = $default;
        return $this;
    }
}