<?php

declare(strict_types=1);

namespace App\Domain\Menu;

use App\Domain\DomainException\DomainRecordNotFoundException;


class MenuNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'Menu not found';
}
