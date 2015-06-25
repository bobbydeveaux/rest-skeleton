<?php

require_once __DIR__ . '/bootstrap.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use DVO\Provider\PdoServiceProvider;
use Igorw\Silex\ConfigServiceProvider;

$app = new Application();
$app['cache'] = $app->share(function () {
    return new DVO\Cache;
});

$app['validator'] = $app->share(function () {
    return new DVO\Validator();
});

// setup the user gateway
$app['user.gateway'] = $app->share(function () use ($app) {
    return new DVO\Entity\User\UserGateway($app['pdo']);
});

// setup the user factory
$app['user.factory'] = $app->share(function () use ($app) {
    return new DVO\Entity\User\UserFactory(
        $app['user.gateway'],
        $app['memcache']
    );
});

// setup the user controller
$app['user.controller'] = $app->share(function () use ($app) {
    return new DVO\Controller\UserController($app['user.factory']);
});

// setup the user controller
$app['rpc.controller'] = $app->share(function () use ($app) {
    return new DVO\Controller\RpcController();
});

$app->get('/', function () {
    return 'API is stable (' . gethostname() . ')';
});

$app->register(new ConfigServiceProvider(
    __DIR__ . "/../config/" . APPLICATION_ENV . ".json"
));

$app->register(new DVO\Provider\MemcacheProvider(), array(
    'caching' => $app['config']['caching']
));

$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../logs/development.log',
));

$app->register(
    new PdoServiceProvider(),
    array(
        'pdo.dsn' => $app['config']['pdo.dsn'],
        'pdo.username' => $app['config']['pdo.username'],
        'pdo.password' => $app['config']['pdo.password'],
        'pdo.options' => array( // optional
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
            PDO::ATTR_TIMEOUT => $app['config']['pdo.timeout'],
        )
    )
);

$app->register(new Silex\Provider\ServiceControllerServiceProvider());

require_once __DIR__ . '/routes.php';

return $app;
