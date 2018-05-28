<?php

namespace system\core\language;

use system\core\AbstractProvider;

class Provider extends AbstractProvider{

    public $name = 'language';

    public function init()
    {
        $language = new Language($this->container->get('settings')['language']);
        $this->container->set($this->name, $language);
    }
}
