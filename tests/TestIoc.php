<?php


class Controller
{
    protected $service;

    public function __construct()
    {
        // 主动创建依赖
        $this->service = new Service(12, 13);
    }
}

class Service
{
    protected $model;
    protected $count;

    public function __construct($param1, $param2)
    {
        $this->count = $param1 + $param2;
        // 主动创建依赖
        $this->model = new Model('test_table');
    }
}

class Model
{
    protected $table;

    public function __construct($table)
    {
        $this->table = $table;
    }
}

$controller = new Controller;
