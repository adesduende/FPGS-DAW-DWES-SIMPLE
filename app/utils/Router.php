<?php
namespace sportshop\app\utils;

class Router
{

    public function map(string $metod, string $path, callable $callback)
    {
        if($metod !== $_SERVER['REQUEST_METHOD']&& $metod !== "ALL") return;
        if($path !== ($_SERVER['PATH_INFO']??'/')&& $path !== "default") return;
        
        $callback($_REQUEST);
        exit();
    }
    public function get(string $path, callable $callback)
    {
    }
    public function post()
    {
    }

}
?>