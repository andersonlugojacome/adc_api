<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Actions\User\CreateUserAction;
use App\Application\Actions\User\UpdateUserAction;
use App\Application\Actions\User\LoginAction;
use App\Application\Actions\Swagger\SwaggerAction;
use App\Application\Middleware\JwtMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;



use App\Application\Actions\Role\ListRolesAction;
use App\Application\Actions\Role\CreateRoleAction;
use App\Application\Actions\Role\UpdateRoleAction;
use App\Application\Actions\Role\FindRolePermissionsByIdAction;
use App\Application\Actions\Role\AssignPermissionToRoleAction;
use App\Application\Actions\Role\RemovePermissionFromRoleAction;
use App\Application\Actions\Role\FindRolesByUserIdAction;
use App\Application\Actions\Role\AssignRoleToUserAction;
use App\Application\Actions\Role\RemoveRoleFromUser;




use App\Application\Actions\Permission\ListPermissionsAction;
use App\Application\Actions\Permission\CreatePermissionAction;
use App\Application\Actions\Permission\UpdatePermissionAction;
use App\Application\Actions\Menu\ListMenusAction;
use App\Application\Actions\Menu\ViewMenuAction;
use App\Application\Actions\Menu\CreateMenuAction;
use App\Application\Actions\Menu\UpdateMenuAction;
use App\Application\Actions\Menu\AssignMenuPermissionAction;
use App\Application\Actions\Menu\RemoveMenuPermissionAction;
use App\Application\Actions\Menu\FindPermissionsByMenuItemsIdAction;



use App\Application\Actions\CertificateConsecutives\ListCertificateConsecutivesAction;
use App\Application\Actions\CertificateConsecutives\ListCertificateConsecutivesByDateAction;
use App\Application\Actions\CertificateConsecutives\CreateCertificateConsecutivesAction;
use App\Application\Actions\CertificateConsecutives\UpdateCertificateConsecutivesAction;
use App\Application\Actions\CertificateConsecutives\NextConsecutiveAction;
use App\Application\Actions\CertificateConsecutives\CheckConsecutiveAction;


use App\Application\Actions\RemissionConsecutives\ListRemissionConsecutivesAction;
use App\Application\Actions\RemissionConsecutives\ListRemissionConsecutivesByDateAction;
use App\Application\Actions\RemissionConsecutives\CreateRemissionConsecutivesAction;
use App\Application\Actions\RemissionConsecutives\UpdateRemissionConsecutivesAction;
use App\Application\Actions\RemissionConsecutives\NextRemissionConsecutivesAction;
use App\Application\Actions\RemissionConsecutives\CheckRemissionConsecutivesAction;


use App\Application\Actions\SoapSIGNO\ObtenerFirmasRegistradasAction;
use App\Application\Actions\SoapSIGNO\ObtenerEscriturasAction;

use App\Application\Actions\PrintedStickers\ListPrintedStickersAction;
use App\Application\Actions\PrintedStickers\ViewPrintedStickerAction;
use App\Application\Actions\PrintedStickers\CreatePrintedStickerAction;
use App\Application\Actions\PrintedStickers\ViewByCodigocryptoAction;









return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });


    $app->get('/', function (Request $request, Response $response) {

        $response->getBody()->write('Hello world! - DigitalesWeb');
        return $response;
    });

    // Ruta para la documentación Swagger
    $app->get('/docs/swagger.json', SwaggerAction::class);

    // Ruta para la interfaz Swagger UI
    $app->get('/docs', function (Request $request, Response $response) {
        $response->getBody()->write(file_get_contents(__DIR__ . '/../public/swagger/index.html'));
        return $response->withHeader('Content-Type', 'text/html');
    });

    // Ruta de autenticación
    $app->post('/login', LoginAction::class);
    $app->get('/printed-stickers/codigocrypto/{codigocrypto}', ViewByCodigocryptoAction::class);

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
        $group->post('', CreateUserAction::class);
        $group->put('/{id}', UpdateUserAction::class);
        $group->get('/{id}/roles', FindRolesByUserIdAction::class);
        $group->post('/{id}/roles', AssignRoleToUserAction::class);
        $group->delete('/{id}/roles', RemoveRoleFromUser::class);
    })->add(JwtMiddleware::class);

    // add /roles 
    $app->group('/roles', function (Group $group) {
        $group->get('', ListRolesAction::class);
        // $group->get('/{id}', ViewRoleAction::class);
        $group->post('', CreateRoleAction::class);
        $group->put('/{id}', UpdateRoleAction::class);
        $group->get('/{id}/permissions', FindRolePermissionsByIdAction::class);
        $group->post('/{id}/permissions', AssignPermissionToRoleAction::class);
        $group->delete('/{id}/permissions/{permissionId}', RemovePermissionFromRoleAction::class);
        // $group->delete('/{id}', DeleteRoleAction::class);
    })->add(JwtMiddleware::class);

    // add /permissions
    $app->group('/permissions', function (Group $group) {
        $group->get('', ListPermissionsAction::class);
        // $group->get('/{id}', ViewPermissionAction::class);
        $group->post('', CreatePermissionAction::class);
        $group->put('/{id}', UpdatePermissionAction::class);
        // $group->delete('/{id}', DeletePermissionAction::class);
    })->add(JwtMiddleware::class);

    // add /menus
    $app->group('/menus', function (Group $group) {
        $group->get('', ListMenusAction::class);
        $group->get('/{id}', ViewMenuAction::class);
        $group->post('', CreateMenuAction::class);
        $group->put('/{id}', UpdateMenuAction::class);
        // $group->delete('/{id}', DeleteMenuAction::class);
        $group->post('/{id}/permissions', AssignMenuPermissionAction::class);
        $group->delete('/{id}/permissions/{permissionId}', RemoveMenuPermissionAction::class);
        $group->get('/{id}/permissions', FindPermissionsByMenuItemsIdAction::class);
    })->add(JwtMiddleware::class);

    $app->group('/certificate-consecutives', function (Group $group) {
        $group->get('', ListCertificateConsecutivesAction::class);
        $group->get('/{begingDate}/{endDate}', ListCertificateConsecutivesByDateAction::class);
        // $group->get('/{id}', ViewCertificateConsecutivesAction::class);
        $group->post('', CreateCertificateConsecutivesAction::class);
        $group->get('/next-consecutive', NextConsecutiveAction::class);
        $group->post('/check-consecutive', CheckConsecutiveAction::class);
        //$group->get('/check-nroescritura/{nroescriturapublica}/{dateescritura}', CheckNroescriturapublicaAction::class);
        $group->put('/{id}', UpdateCertificateConsecutivesAction::class);
        // $group->delete('/{id}', DeleteCertificateConsecutivesAction::class);
    })->add(JwtMiddleware::class);

    $app->group('/remission-consecutives', function (Group $group) {
        $group->get('', ListRemissionConsecutivesAction::class);
        $group->get('/{begingDate}/{endDate}', ListRemissionConsecutivesByDateAction::class);
        // $group->get('/{id}', ViewRemissionConsecutivesAction::class);
        $group->get('/next-consecutive', NextRemissionConsecutivesAction::class);
        $group->post('/check-consecutive', CheckRemissionConsecutivesAction::class);
        $group->post('', CreateRemissionConsecutivesAction::class);
        $group->put('/{id}', UpdateRemissionConsecutivesAction::class);
        // $group->delete('/{id}', DeleteRemissionConsecutivesAction::class);
    })->add(JwtMiddleware::class);
    $app->group('/soap-signo', function (Group $group) {
        $group->get('/consultar-firmas-registradas', ObtenerFirmasRegistradasAction::class);
        $group->get('/consultar-radicados', ObtenerEscriturasAction::class);
    })->add(JwtMiddleware::class);

    $app->group('/printed-stickers', function (Group $group) {
        $group->get('', ListPrintedStickersAction::class);
        $group->get('/{id}', ViewPrintedStickerAction::class);
        $group->post('', CreatePrintedStickerAction::class);
        
    })->add(JwtMiddleware::class);

    

};
