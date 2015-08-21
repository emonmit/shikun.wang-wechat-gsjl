<?php

/*
 *  本文件中数组数值应由数据库生成，请与后期修改
 */

class Params{

    private $system = array();

    public function __construct()
    {
        $this->system = array(
            'WelcomeText' => 'hello, welcome to gushijielong.',
            'DeafultText' => 'please contact demon',
            'ErrorText' => 'sorry, this is a error info',

            '故事接龙' => '这里应该呈现故事接龙游戏规则',

            '刘政' => '刘政：“汪汪汪”',
        );
    }

    public function index()
    {
        return $this->system;
    }

}