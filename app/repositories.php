<?php

declare(strict_types=1);

use App\Domain\User\UserRepository as IUserRepository;
use App\Infrastructure\Persistence\User\DBUserRepository;

use App\Domain\Role\RoleRepository as IRoleRepository;
use App\Infrastructure\Persistence\Role\DBRoleRepository;
use App\Domain\Permission\PermissionRepository as IPermissionRepository;
use App\Infrastructure\Persistence\Permission\DBPermissionRepository;
use App\Domain\Menu\MenuRepository as IMenuRepository;
use App\Infrastructure\Persistence\Menu\DBMenuRepository;
use App\Domain\CertificateConsecutives\CertificateConsecutivesRepository as ICertificateConsecutivesRepository;
use App\Infrastructure\Persistence\CertificateConsecutives\DBCertificateConsecutivesRepository;



use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        // UserRepository::class => \DI\autowire(InMemoryUserRepository::class),
        IUserRepository::class => function ($c) {
            return new DBUserRepository($c->get('db'));
        },
        
        IRoleRepository::class => function ($c) {
            return new DBRoleRepository($c->get('db'));
        },
        IPermissionRepository::class => function ($c) {
            return new DBPermissionRepository($c->get('db'));
        },
        IMenuRepository::class => function ($c) {
            return new DBMenuRepository($c->get('db'));
        },
        ICertificateConsecutivesRepository::class => function ($c) {
            return new DBCertificateConsecutivesRepository($c->get('db'));
        },
        
       
    ]);
};
