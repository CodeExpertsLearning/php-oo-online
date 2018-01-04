<?php
namespace CodeExperts\MVC;

class BaseController
{
    protected function getConfig($configName)
    {
        if(!file_exists($config = CONFIG_PATH . $configName . '.php')) {
            throw new \Exception("Config not found!");
        }

        return require $config;
    }

    protected function redirect($path)
    {
        return header('Location: ' . HOME . $path);
    }

    protected function sanitizerString($data, $filters)
    {
        return filter_var_array($data, $filters);
    }

    protected function addFlash($flag, $msg)
    {
        return \CodeExperts\Tools\Session::addFlash($flag, $msg);
    }
}