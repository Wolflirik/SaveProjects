<?php

namespace system\core\auth;

use system\helper\Cookie;
use system\libs\jwt\JWT;

class Auth implements AuthInterface{

    private $db;
    private $request;
    private $config;
    private $key;
    private $user = [];

    public function __construct($container)
    {
        $this->db = $container->get('db');
        $this->request = $container->get('request');
        $this->config = $container->get('config');
        $this->key = base64_encode($container->get('settings')['encryption_key']);
        $this->user['logged'] = false;
    }

    public function getUser($key)
    {
        return $this->user[$key];
    }

    private function removeToken(){
        Cookie::delete('a_token');
        Cookie::delete('r_token');
        Cookie::delete('expires_in');
        return false;
    }

    private function generateToken($user, $type){
        $time_start = time();
        $time_end = $time_start + 60;
        if($type == 'access')
        {
            $time_end = $time_start + 1800;
        }
        if ($type == 'refresh')
        {
            $time_end = $time_start + 2592000;
        }
        $data = [
            'iat'  => $time_start,
            'jti'  => base64_encode(random_bytes(32) . md5($this->request->server['HTTP_USER_AGENT'] . $time_start)),
            'iss'  => $this->config->item('name', 'server'),
            'nbf'  => $time_start,
            'exp'  => $time_end,
            'data' => [
                'id'        => (int)$user['id'],
                'role'      => (int)$user['role']
            ]
        ];
        $jwt = JWT::encode(
            $data,
            $this->key,
            'HS512'
        );

        return ['token' => $jwt, 'exp' => $time_end];
    }

    public function setToken($user)
    {
        $access = $this->generateToken($user, 'access');
        $refresh = $this->generateToken($user, 'refresh');

        $count_user = $this->db->query("SELECT COUNT(id) AS `count` FROM `" . $this->config->item('prefix', 'database') . "user_refresh_token` WHERE `user_id` = '" . (int)$user['id'] . "'")->row['count'];
        if($count_user < 10){
            $this->db->query("UPDATE `" . $this->config->item('prefix', 'database') . "user` SET `access_token` = '" . $access['token'] . "' WHERE `id` = '" . (int)$user['id'] . "'");
            $count_browser = $this->db->query("SELECT COUNT(id) AS `count` FROM `" . $this->config->item('prefix', 'database') . "user_refresh_token` WHERE `user_id` = '" . (int)$user['id'] . "' AND `user_agent` = '" . $this->db->escape(md5($this->request->server['HTTP_USER_AGENT'])) . "'")->row['count'];
            if($count_browser == 0)
                $this->db->query("INSERT INTO `" . $this->config->item('prefix', 'database') . "user_refresh_token` (`user_id`, `user_agent`, `token`) VALUES ('" . (int)$user['id'] . "', '" . $this->db->escape(md5($this->request->server['HTTP_USER_AGENT'])) . "', '" . $refresh['token'] . "')");
            else
                $this->db->query("UPDATE `" . $this->config->item('prefix', 'database') . "user_refresh_token` SET `token` = '" . $refresh['token'] . "' WHERE `user_id` = '" . (int)$user['id'] . "' AND `user_agent` = '" . $this->db->escape(md5($this->request->server['HTTP_USER_AGENT'])) . "'");
        }else{
            $this->db->query("DELETE FROM `" . $this->config->item('prefix', 'database') . "user_refresh_token` WHERE `user_id` = '" . (int)$user['id'] . "'");
            $this->removeToken();
            return false;
        }

        Cookie::set('a_token', $access['token']);
        Cookie::set('r_token', $refresh['token']);
        Cookie::set('expires_in', $access['exp']);

        $this->user['logged'] = true;
    }

    public function check()
    {
        $exp = Cookie::get($this->request->cookie, 'expires_in');

        if($exp > time())
        {
            $access_token = Cookie::get($this->request->cookie, 'a_token');
            if($access_token) {
                try {
                    $token = JWT::decode($access_token, $this->key, ['HS512']);
                    $user =  $this->db->query("SELECT `id`, `first_name`, `last_name`, `username`, `email`, `role`, `access_token` FROM `" . $this->config->item('prefix', 'database') . "user` WHERE `id` = '" . $token->data->id . "'")->row;
                    if($user['access_token'] == $access_token){
                        unset($user['access_token']);
                        $this->user[] = $user;
                        $this->user['logged'] = true;
                    }else{
                        $this->removeToken();
                    }
                } catch (\Exception $e) {
                    $this->removeToken();
                }
            }else {
                $this->removeToken();
            }
        }else {
            $refresh_token = Cookie::get($this->request->cookie, 'r_token');
            if($refresh_token) {
                try {
                    $token = JWT::decode($refresh_token, $this->key, ['HS512']);
                    $token_data = json_decode(json_encode($token->data), true);
                    $db_refresh_token = $this->db->query("SELECT `token` FROM `" . $this->config->item('prefix', 'database') . "user_refresh_token` WHERE `user_id` = '" . (int)$token_data['id'] . "' AND `user_agent` = '" . $this->db->escape(md5($this->request->server['HTTP_USER_AGENT'])) . "'")->row['token'];
                    if($db_refresh_token == $refresh_token) {
                        $this->user[] = $this->db->query("SELECT `id`, `first_name`, `last_name`, `username`, `email`, `role` FROM `" . $this->config->item('prefix', 'database') . "user` WHERE `id` = '" . (int)$token_data['id'] . "'")->row;
                        $this->setToken($token_data);
                    }else{
                        $this->db->query("DELETE FROM `" . $this->config->item('prefix', 'database') . "user_refresh_token` WHERE `user_id` = '" . (int)$token_data['id'] . "' AND `user_agent` = '" . $this->db->escape(md5($this->request->server['HTTP_USER_AGENT'])) . "'");
                        $this->removeToken();
                    }
                } catch (\Exception $e) {
                    $this->removeToken();
                }
            }else{
                $this->removeToken();
            }
        }
    }
}