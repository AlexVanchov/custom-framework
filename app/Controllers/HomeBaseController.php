<?php

namespace App\Controllers;

use Core\Application;
use Core\BaseController;

class HomeBaseController extends BaseController
{
    public function index()
    {
        $this->render('home/index', [
            'title' => 'Welcome',
            'heading' => 'Welcome H',
            'message' => 'Hello WOrld'
        ]);
    }

    public function demoPost()
    {
        var_dump($this->request);
        exit;
    }
}
