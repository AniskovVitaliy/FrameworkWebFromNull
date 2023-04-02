<?php

namespace core\admin\controller;

use core\base\settings\Settings;

class AddController extends BaseAdmin
{

    protected $action = 'add';

    protected function inputData()
    {

        if(!$this->userId) $this->execBase();

        $this->checkPost();

        $this->createTableData();

        $this->createForeignData();

        $this->createMenuPosition();

        $this->createRadio();

        $this->createOutputData();

        $this->createManyToMany();

        return $this->expansion();

//        $this->data = [
//            'name' => 'Masha',
//            'keywords' => 'Ключевики',
//            'img' => '1.png',
//            'gallery_img' => json_encode(['1.jpg', '2.jpg', '3.jpg'])
//        ];

    }

}