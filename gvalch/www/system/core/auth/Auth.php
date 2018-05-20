<?php

namespace system\core\auth;

use system\libs\jwt\JWT;
use system\helper\Cookie;

class Auth implements AuthInterface{

    private $db;

    private $cookie;

    private $user = array();

    public function __construct($container)
    {
        $this->db = $container->get('db');
        $this->cookie = $container->get('request')->cookie;
    }

    public function setUserData()
    {

    }

    public function setToken($login, $password)
    {

    }

    public function refreshToken(){

    }

    public function check($page)
    {
        if($page)
        {

        }else{

        }
    }
}