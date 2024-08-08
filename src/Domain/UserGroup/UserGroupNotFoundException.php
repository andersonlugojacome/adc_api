<?php

declare(strict_types=1);

namespace App\Domain\UserGroup;

use App\Domain\DomainException\DomainRecordNotFoundException;

class UserGroupNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The user groups you requested does not exist.';
}
