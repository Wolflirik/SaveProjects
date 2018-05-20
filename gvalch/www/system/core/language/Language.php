<?php

namespace system\core\language;

class Language
{
    private $data = [];
    private $default = 'ru';
    private $language;
    const LANGUAGE_PATH = '%s/%s/language/%s/%s.php';

    public function __construct($language)
    {
        $this->language = $language;
    }

    public function load($filename)
    {
        $file = sprintf(self::LANGUAGE_PATH, __DIR__, LOCATION, $this->language, $filename);
        if(file_exists($file))
        {
            $_ = [];
            require $file;
            $this->data = array_merge($this->data, $_);

            return $this->data;
        }

        $file = sprintf(self::LANGUAGE_PATH, __DIR__, LOCATION, $this->default, $filename);
        if(file_exists($file))
        {
            $_ = [];
            require $file;
            $this->data = array_merge($this->data, $_);

            return $this->data;
        }else{
            trigger_error('Error: Could not load language ' . $file . '!');
            exit();
        }
    }

    public function get($key)
    {
        return (isset($this->data[$key]) ? $this->data[$key] : $key);
    }
}