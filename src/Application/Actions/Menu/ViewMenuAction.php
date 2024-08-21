<?php

declare(strict_types=1);

namespace App\Application\Actions\Menu;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;

class ViewMenuAction extends MenuAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $menuId = (int) $this->resolveArg('id');
        $menu = $this->menuRepository->findMenuForUser($menuId);

        $this->logger->info("Menu of id `{$menuId}` was viewed.");

        return $this->respondWithData($menu);
    }
}
