<?php

namespace admin\controller\common;

class Auth extends \system\Controller{

    public function index(){
        if($this->auth->getUser('logged')){
            $this->redirect('/admin');
        }
        $this->load->language('common/auth');

        $this->document->setTitle($this->language->get('title'));
        $this->document->setDescription($this->language->get('description'));

        $this->data['text_login'] = $this->language->get('text_login');
        $this->data['text_password'] = $this->language->get('text_password');
        $this->data['button_submit'] = $this->language->get('button_submit');

        $this->child = [
            'common/header',
            'common/footer'
        ];

        $this->template = 'common/login';
        $this->setOutput();
    }

    public function login(){
        if(isset($this->request->post['username']) && $this->request->post['password']){
            $this->load->model('common/auth');
            if($this->model_common_auth->hasUser($this->request->post) == 0){
                $this->json['error'] = 'Not found user!';
                $this->jsonOutput();
                return false;
            }

            $this->auth->setToken($this->model_common_auth->getUser($this->request->post));
            $this->json['success'] = $this->auth->getUser('logged');
            $this->jsonOutput();
        }
    }


}