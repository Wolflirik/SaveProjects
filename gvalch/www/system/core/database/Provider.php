<?php

namespace system\core\database;

use system\core\AbstractProvider;

class Provider extends AbstractProvider{

    public $name = 'db';

    public function init()
    {
        $config = $this->container->get('config')->file('database');
        $database = new Database($config['adaptor'], $config['host'], $config['user'], $config['pass'], $config['name']);
        $settings = $database->query("SELECT * FROM `" . $config['prefix'] . "settings` WHERE `status` = '1'")->row;
        $this->container->set($this->name, $database);
        $this->container->set('settings', $settings);
    }
}