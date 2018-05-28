<?php

namespace system\core\auth;

use system\core\AbstractProvider;

class Provider extends AbstractProvider{

    public $name = 'auth';

    public function init()
    {
        $auth = new Auth($this->container);
        $this->container->set($this->name, $auth);
    }
}