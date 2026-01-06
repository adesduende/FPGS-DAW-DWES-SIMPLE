<?php
namespace sportshop\app\utils;

/**
 * Router
 * This class pretends to be a router for handling HTTP requests. 
 */
class Router
{
    /**
     * Maps an HTTP method and path to a callback function.
     *
     * @param string $method - The HTTP method (e.g., 'GET', 'POST', 'ALL').
     * @param string $path - The request path to match.
     * @param callable $callback - The function to call if the method and path match.
     */
    static public function map(string $method, string $path, callable $callback)
    {
        if(!isset($_SERVER['REQUEST_METHOD'])) 
            exit();

        // Get the path from either PATH_INFO or REQUEST_URI
        $requestPath = $_SERVER['PATH_INFO'] ?? parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        
        if($method !== $_SERVER['REQUEST_METHOD'] && $method !== "ALL") return;
        if($path !== $requestPath && $path !== "default") return;
        
        $callback($_REQUEST);
        exit();
    }
    /**
     * Shorthand for mapping GET requests.
     * @param string $path -The request path to match.
     * @param callable $callback - The function to call if the path matches.
     */
    static public function get(string $path, callable $callback)
    {
    }
    /**
     * Shorthand for mapping POST requests.
     * @param string $path - The request path to match.
     * @param callable $callback - The function to call if the path matches.
     */
    static public function post(string $path, callable $callback)
    {
    }

}
?>