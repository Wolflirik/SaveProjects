<?php

namespace admin\controller\common;

class Header extends \system\Controller{

    public function index()
    {
        $this->data['description'] = $this->document->getDescription();
        $this->data['keywords'] = $this->document->getKeywords();
        $this->data['title'] = $this->document->getTitle();
        $this->data['links'] = $this->document->getLinks();
        $this->data['styles'] = $this->document->getStyles();

        $this->template = 'common/header';

        $this->render();
    }
}