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
        $this->response['data'] = [
            'index' => '中国'
        ];
        $this->success();
        $this->jsonReturn();
    }
}