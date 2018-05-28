<?php

$this->router->add('dashboard',     '/admin',               'common\Dashboard:index');
$this->router->add('auth',          '/admin/auth',          'common\Auth:index');
$this->router->add('auth_login',    '/admin/auth/login',    'common\Auth:login',            'POST');
$this->router->add('auth_logout',   '/admin/auth/logout',   'common\Auth:logout');
//$this->router->add('signup', '/admin/auth/signup', 'auth\Signup:index');