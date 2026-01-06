<?php

namespace sportshop;

use sportshop\app\data\CartRepository;
use sportshop\app\data\CategoryRepository;
use sportshop\app\data\DbContext;
use sportshop\app\data\OrderRepository;
use sportshop\app\data\ProductRepository;
use sportshop\app\data\UserRepository;
use sportshop\app\services\Hash;
use sportshop\app\services\RouteMapper;
use sportshop\app\utils\DotEnv;
use sportshop\app\utils\ServiceCollection;

//Global variables
define('BASE_PATH', dirname(__DIR__));
define('LAYOUT', BASE_PATH . "/app/views/shared/layout.php");
setlocale(LC_TIME, 'es_ES');

//Autoloader of classes
//TODO: Move to a simple file the autoloader
spl_autoload_register(function ($className) {
    $namespace = str_replace("\\", "/", __NAMESPACE__);
    $className = str_replace($namespace, '', $className);
    $class = BASE_PATH . "{$className}.php";
    include_once($class);
});

//Load .env
new DotEnv(BASE_PATH)->load();

// Create the database
$database = new DbContext(
    getenv('DB_HOST'),
    getenv('DB_NAME'),
    getenv('DB_USER'),
    getenv('DB_PASSWORD')
);


//Create a service collection to use DI
$service_collection = ServiceCollection::GetInstance();
$service_collection->Add('UserRepository', new UserRepository($database));
$service_collection->Add('ProductRepository', new ProductRepository($database));
$service_collection->Add('CategoryRepository', new CategoryRepository($database));
$service_collection->Add('CartRepository', new CartRepository($database));
$service_collection->Add('OrderRepository', new OrderRepository($database));
$service_collection->Add('Hash', new Hash());

//Instance route mapper
new RouteMapper($service_collection)->Start();


?>