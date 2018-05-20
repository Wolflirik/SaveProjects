<?php

namespace system;

use system\core\AbstractConstructor;

class Controller extends AbstractConstructor{

    protected $template;
    private $output;
    protected $child = [];
    protected $data = [];
    const CONTROLLER_NAMESPACE = '\\' . LOCATION . '\\controller\\%s\\%s';
    const TEMPLATE_PATH = '%s/../' . LOCATION . '/view/' . (LOCATION == 'public' ? 'default/' : '') . 'template/%s.tpl';

    /**
     * Controller constructor.
     * @param $container
     */
    public function __construct($container){
        parent::__construct($container);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function __get($key){
        return $this->container->get($key);
    }

    /**
     * @param $key
     * @param $val
     */
    public function __set($key, $val){
        $this->container->set($key, $val);
    }

    /**
     * @param $url
     * @param int $status
     */
    protected function redirect($url, $status = 302){
        header('Status: ' . $status);
        header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $url));
    }

    /**
     * @param $child
     * @param array $args
     * @return mixed
     */
    protected function getChild($child, $args = [])
    {
        list($path, $class) = explode('/', $child);
        $controller_namespace = sprintf(self::CONTROLLER_NAMESPACE, $path, ucfirst($class));
        if(class_exists($controller_namespace))
        {
            $controller = new $controller_namespace($this->container);
            $controller->index();
            return $controller->output;
        }else{
            trigger_error('Error: Could not load child ' . $controller_namespace . '!');
            exit();
        }
    }

    /**
     * @return string
     */
    protected function render()
    {
        foreach($this->child as $child)
        {
            $this->data[basename($child)] = $this->getChild($child);
        }
        //echo __DIR__ . '/../' . LOCATION . '/view/template/' . $this->template . '.tpl';
        $file = sprintf(self::TEMPLATE_PATH, __DIR__, $this->template);
        if (file_exists($file))
        {
            extract($this->data);

            ob_start();

            require $file;

            $this->output = ob_get_contents();

            ob_end_clean();

            return $this->output;
        } else {
            trigger_error('Error: Could not load template ' . $file . '!');
            exit();
        }
    }

    /**
     * Output html
     */
    protected function setOutput(){
        $this->render();
        echo $this->output;
    }
}