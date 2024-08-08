<?php

declare(strict_types=1);

use App\Application\Actions\Swagger\SwaggerAction;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use App\Application\Services\JWTService;
use OpenApi\Annotations as OA;
use OpenApi\Generator;


return function (ContainerBuilder $containerBuilder) {
   
    

    // Global Settings Object
    $containerBuilder->addDefinitions([
        
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        'settings' => function () {
            return [
                'db' => [
                    'driver' => getenv('DB_DRIVER')?: 'pdo_mysql',
                    'host' => getenv('DB_HOST')?: 'localhost',
                    'dbname' => getenv('DB_NAME')?: 'not62bog_notaria62web',
                    'user' => getenv('DB_USER')?: 'user',
                    'password' => getenv('DB_PASSWORD')?: 'User',
                    'charset' => 'utf8mb4',
                ],
            ];
        },
       
            JWTService::class => function () {
                $secret = getenv('JWT_SECRET') ?: 'Notaria62Notaria62Notaria62Notaria62';
                return new JWTService($secret);
            },
            
        'openapi' => require __DIR__ . '/swagger.php',


    ]);
    // Doctrine DBAL
    $containerBuilder->addDefinitions([
        'db' => function ($c) {
            $config = new Configuration();
            $connectionParams = [
                'dbname' => $c->get('settings')['db']['dbname'],
                'user' => $c->get('settings')['db']['user'],
                'password' => $c->get('settings')['db']['password'],
                'host' => $c->get('settings')['db']['host'],
                'driver' => $c->get('settings')['db']['driver'],
                'charset' => $c->get('settings')['db']['charset'],
            ];
            return DriverManager::getConnection($connectionParams, $config);
        },
    ]);
};
