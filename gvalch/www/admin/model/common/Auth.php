<?php

namespace admin\model\common;

class Auth extends \system\Model{

    public function hasUser($data){
        return $this->db->query("SELECT COUNT(id) AS `count` FROM `" . $this->config->item('prefix', 'database') . "user` WHERE `username` = '" . $this->db->escape($data['username']) . "' AND `password` = '" . $this->db->escape(md5(md5($data['password']))) . "'")->row['count'];
    }

    public function getUser($data){
        $query = $this->db->query("SELECT `id`, `role` FROM `" . $this->config->item('prefix', 'database') . "user` WHERE `username` = '" . $this->db->escape($data['username']) . "' AND `password` = '" . $this->db->escape(md5(md5($data['password']))) . "'");
        return $query->row;
    }
}