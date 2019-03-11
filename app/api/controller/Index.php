<?php

namespace app\api\controller;

class Index extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $this->result = [
            'index' => '中国'
        ];
        $this->jsonReturn();
    }
}