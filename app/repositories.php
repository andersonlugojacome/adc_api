<?php

declare(strict_types=1);

use App\Domain\User\UserRepository as IUserRepository;
use App\Infrastructure\Persistence\User\DBUserRepository;
use App\Domain\UserGroup\UserGroupRepository as IUserGroupRepository;
use App\Infrastructure\Persistence\UserGroup\DBUserGroupRepository;

use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        // UserRepository::class => \DI\autowire(InMemoryUserRepository::class),
        IUserRepository::class => function ($c) {
            return new DBUserRepository($c->get('db'));
        },
        IUserGroupRepository::class => function ($c) {
            return new DBUserGroupRepository($c->get('db'));
        },
       
    ]);
};
